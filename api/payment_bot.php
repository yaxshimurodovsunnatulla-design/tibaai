<?php
/**
 * Tiba AI — Telegram Bot Webhook
 * To'lovlarni tasdiqlash/rad etish uchun
 * 
 * Webhook URL: https://tibaai.uz/api/payment_bot.php
 */

require_once __DIR__ . '/config.php';

// Only execute if called directly as a webhook
$botLogFile = __DIR__ . '/../tmp/bot_updates.log';
if (!is_dir(dirname($botLogFile))) @mkdir(dirname($botLogFile), 0755, true);

function botDebug($msg) {
    global $botLogFile;
    $line = date('[Y-m-d H:i:s] ') . $msg . "\n";
    @file_put_contents($botLogFile, $line, FILE_APPEND);
    error_log("BotDebug: " . $msg);
}

if (basename($_SERVER['PHP_SELF']) == 'payment_bot.php' || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'payment_bot.php') !== false)) {
    $rawInput = file_get_contents('php://input');
    botDebug("Incoming Request: " . ($rawInput ?: "EMPTY INPUT"));
    
    if ($rawInput) {
        $input = json_decode($rawInput, true);
        handleBotUpdate($input);
    }
    echo json_encode(['ok' => true]);
    exit;
}

function handleBotUpdate($input) {
    global $botLogFile;
    // Get bot token reliably
    $botToken = $_ENV['TELEGRAM_BOT_TOKEN'] ?? $_SERVER['TELEGRAM_BOT_TOKEN'] ?? getenv('TELEGRAM_BOT_TOKEN');
    if (!$botToken) {
        botDebug("CRITICAL ERROR: Bot token not found in ENV/SERVER/GETENV");
        return;
    }
    botDebug("Bot Token identified (starts with: " . substr($botToken, 0, 10) . "...)");

    // Callback query (inline button bosilganda)
    if (isset($input['callback_query'])) {
        $callbackQuery = $input['callback_query'];
        $chatId = $callbackQuery['message']['chat']['id'] ?? null;
        $messageId = $callbackQuery['message']['message_id'] ?? null;
        $data = $callbackQuery['data'] ?? '';
        $callbackId = $callbackQuery['id'] ?? '';
        $adminName = $callbackQuery['from']['first_name'] ?? 'Admin';

        botDebug("Callback Received: $data from Chat: $chatId");

        // Telegram "o'ylanib qolmasligi" uchun darhol javob beramiz
        try {
            answerCallback($botToken, $callbackId, "Jarayon bajarilmoqda...");
        } catch (Exception $e) {
            botDebug("answerCallback failed (ignoring): " . $e->getMessage());
        }

        if (preg_match('/^(approve|reject)_(\d+)$/', $data, $matches)) {
            $action = $matches[1];
            $paymentId = intval($matches[2]);
            if ($action === 'reject') {
                botDebug("Reject button clicked for #$paymentId. Sending prompt...");
                $promptMsg = "📝 To'lov #{$paymentId} ni rad etish sababini yuboring (ushbu xabarga javob bering):";
                $res = sendTgMessage($botToken, $chatId, $promptMsg, true);
                botDebug("Prompt send result: " . $res);
                return;
            }
            botDebug("[Line 71] Calling processPaymentAction: $action #$paymentId");
            processPaymentAction($botToken, $chatId, $messageId, $callbackId, $paymentId, $action, $adminName);
        } else {
            botDebug("Unknown callback data format: $data");
        }
        return;
    }

    // Text command: /approve 123 yoki /reject 123 sabab
    if (isset($input['message']['text'])) {
        $text = trim($input['message']['text']);
        $chatId = $input['message']['chat']['id'] ?? null;
        $adminName = $input['message']['from']['first_name'] ?? 'Admin';

        if (preg_match('/^\/approve\s+(\d+)/i', $text, $m)) {
            processPaymentAction($botToken, $chatId, null, null, intval($m[1]), 'approve', $adminName);
        } elseif (preg_match('/^\/reject\s+(\d+)\s*(.*)/i', $text, $m)) {
            processPaymentAction($botToken, $chatId, null, null, intval($m[1]), 'reject', $adminName, trim($m[2]));
        }
    }

    // Reply handling (To'lovni rad etish sababi uchun)
    if (isset($input['message']['reply_to_message'])) {
        $replyTo = $input['message']['reply_to_message']['text'] ?? '';
        $reason = trim($input['message']['text'] ?? '');
        $chatId = $input['message']['chat']['id'] ?? null;
        $adminName = $input['message']['from']['first_name'] ?? 'Admin';
        
        if (preg_match('/To\'lov #(\d+)/i', $replyTo, $m)) {
            $paymentId = intval($m[1]);
            botDebug("Reason received via reply for #$paymentId: $reason");
            processPaymentAction($botToken, $chatId, null, null, $paymentId, 'reject', $adminName, $reason);
            return;
        }
    }
}

// ========== PAYMENT ACTION ==========

function processPaymentAction($botToken, $chatId, $messageId, $callbackId, $paymentId, $action, $adminName, $reason = '') {
    $chatId = (string)$chatId;
    $paymentId = (int)$paymentId; // Ensure paymentId is an integer
    botDebug("--- Starting ProcessAction: $action #$paymentId (Admin: $adminName) ---");
    
    try {
        // Immediate feedback: Update buttons first to stop the loading spinner in Telegram
        if ($messageId) {
            $statusPrefix = ($action === 'approve') ? "✅ Tasdiqlanmoqda..." : "❌ Rad etilmoqda...";
            editMessageButtons($botToken, $chatId, (int)$messageId, "$statusPrefix — $adminName");
        }

        $db = getDB();
        if (!$db) throw new Exception("Database ulanishida xatolik.");

        // Payment mavjudligini tekshirish
        botDebug("Fetching payment #$paymentId with user details...");
        $stmt = $db->prepare("SELECT p.*, u.name as user_name, u.email as user_email, u.balance as current_balance FROM payments p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
        $stmt->execute([$paymentId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            botDebug("CRITICAL: Payment #$paymentId not found or user association missing.");
            sendTgMessage($botToken, $chatId, "❌ To'lov #{$paymentId} topilmadi yoki foydalanuvchi ma'lumotlari xato.");
            if ($messageId) editMessageButtons($botToken, $chatId, (int)$messageId, "❌ Xatolik: Topilmadi");
            return;
        }

        botDebug("Current Status: {$payment['status']}");
        if ($payment['status'] !== 'pending') {
            $statusText = $payment['status'] === 'approved' ? '✅ Tasdiqlangan' : '❌ Rad etilgan';
            botDebug("Already processed: {$payment['status']}");
            sendTgMessage($botToken, $chatId, "⚠️ To'lov #{$paymentId} allaqachon: {$statusText}");
            if ($messageId) editMessageButtons($botToken, $chatId, (int)$messageId, "$statusText — {$payment['admin_note']}");
            return;
        }

        $now = date('Y-m-d H:i:s');
        if ($action === 'approve') {
            botDebug("Applying approval to DB...");
            
            $db->beginTransaction();
            try {
                // To'lovni tasdiqlsh
                botDebug("Updating payment status to 'approved' for #$paymentId...");
                $stmt = $db->prepare("UPDATE payments SET status = 'approved', admin_note = ?, updated_at = ? WHERE id = ?");
                $stmt->execute(["Tasdiqladi: $adminName", $now, $paymentId]);

                // Tangalarni qo'shish
                $creditsToAdd = (int)$payment['credits'];
                $userId = (int)$payment['user_id'];
                botDebug("Adding $creditsToAdd credits to user #$userId...");
                $stmt = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
                $stmt->execute([$creditsToAdd, $userId]);
                
                $db->commit();
                botDebug("DB updated successfully (Approved).");
            } catch (Exception $dbErr) {
                $db->rollBack();
                botDebug("DB Transaction Rollback (Approval): " . $dbErr->getMessage());
                throw $dbErr;
            }

            // Yangi balansni olish
            $newBalance = (int)$payment['current_balance'] + (int)$payment['credits'];

            $priceFormatted = number_format($payment['amount'], 0, '', ',');
            $safeName = htmlspecialchars($payment['user_name'] ?? 'Noma\'lum');
            $safeEmail = htmlspecialchars($payment['user_email'] ?? '');
            $safePkg = htmlspecialchars($payment['package_name'] ?? 'Paket');
            $safeAdmin = htmlspecialchars($adminName);

            $msg = "✅ <b>TO'LOV TASDIQLANDI #{$paymentId}</b>\n\n"
                . "👤 {$safeName} ({$safeEmail})\n"
                . "📦 {$safePkg} — {$payment['credits']} tanga\n"
                . "💵 {$priceFormatted} so'm\n"
                . "💰 Yangi balans: <b>{$newBalance}</b> tanga\n"
                . "👨‍💼 Tasdiqladi: {$safeAdmin}";

            botDebug("Sending success message...");
            sendTgMessage($botToken, $chatId, $msg);

            if ($messageId) {
                editMessageButtons($botToken, $chatId, (int)$messageId, "✅ Tasdiqlandi — $adminName");
            }

        } else { // Reject action
            botDebug("Applying rejection to DB...");
            $note = $reason ?: "Rad etdi: $adminName";
            botDebug("Updating payment status to 'rejected' for #$paymentId with note: '$note'...");
            $stmt = $db->prepare("UPDATE payments SET status = 'rejected', admin_note = ?, updated_at = ? WHERE id = ?");
            $stmt->execute([$note, $now, $paymentId]);
            botDebug("DB updated successfully (Rejected).");

            $safeName = htmlspecialchars($payment['user_name'] ?? 'Noma\'lum');
            $safePkg = htmlspecialchars($payment['package_name'] ?? 'Paket');
            $safeAdmin = htmlspecialchars($adminName);
            $safeReason = htmlspecialchars($reason);

            $msg = "❌ <b>TO'LOV RAD ETILDI #{$paymentId}</b>\n\n"
                . "👤 {$safeName}\n"
                . "📦 {$safePkg}\n"
                . "👨‍💼 Rad etdi: {$safeAdmin}\n"
                . ($reason ? "📝 Sabab: {$safeReason}" : "");

            botDebug("Sending rejection message...");
            sendTgMessage($botToken, $chatId, $msg);

            if ($messageId) {
                editMessageButtons($botToken, $chatId, (int)$messageId, "❌ Rad etildi — $adminName");
            }
        }
        botDebug("=== Action completed successfully ===");
    } catch (Exception $e) {
        botDebug("FATAL ERROR in processPaymentAction for #$paymentId: " . $e->getMessage());
        sendTgMessage($botToken, $chatId, "❌ Tizimda xatolik yuz berdi: " . $e->getMessage());
        if ($messageId) editMessageButtons($botToken, $chatId, (int)$messageId, "❌ Xatolik yuz berdi");
    }
}

// ========== TELEGRAM HELPERS ==========

function sendTgMessage($botToken, $chatId, $text, $forceReply = false) {
    global $botLogFile;
    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
    $ch = curl_init();
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
    ];
    if ($forceReply) {
        $data['reply_markup'] = json_encode(['force_reply' => true, 'selective' => true]);
    }
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'TibaAIBot/1.0',
    ]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($err) {
        botDebug("Send Message Curl Error: $err");
    } else {
        botDebug("Send Message Response ($httpCode): $res");
    }
    return $res;
}

function answerCallback($botToken, $callbackId, $text) {
    $url = "https://api.telegram.org/bot{$botToken}/answerCallbackQuery";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'callback_query_id' => $callbackId,
            'text' => $text,
            'show_alert' => true,
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'TibaAIBot/1.0',
    ]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($err) {
        botDebug("answerCallback Curl Error: $err");
    } else {
        botDebug("answerCallback Response ($httpCode): $res");
    }
}

function editMessageButtons($botToken, $chatId, $messageId, $newText) {
    $url = "https://api.telegram.org/bot{$botToken}/editMessageText";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $newText,
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'TibaAIBot/1.0',
    ]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($err) {
        botDebug("editMessageButtons Curl Error: $err");
    } else {
        botDebug("editMessageButtons Response ($httpCode): $res");
    }
}
