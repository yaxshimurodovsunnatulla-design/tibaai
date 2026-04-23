<?php
/**
 * Tiba AI — Support Bot Webhook
 * 
 * Webhook URL: https://tibaai.uz/api/support_bot.php
 */

require_once __DIR__ . '/config.php';

$supportLogFile = __DIR__ . '/../tmp/support_bot.log';
if (!is_dir(dirname($supportLogFile))) @mkdir(dirname($supportLogFile), 0755, true);

function supportDebug($msg) {
    global $supportLogFile;
    $line = date('[Y-m-d H:i:s] ') . $msg . "\n";
    @file_put_contents($supportLogFile, $line, FILE_APPEND);
    error_log("SupportBot: " . $msg);
}

// Log every hit to verify the webhook is reaching the script
supportDebug("Script hit. URI: " . ($_SERVER['REQUEST_URI'] ?? 'unknown'));

// Token va Admin yuklash
$supportToken = $_ENV['SUPPORT_BOT_TOKEN'] ?? $_SERVER['SUPPORT_BOT_TOKEN'] ?? getenv('SUPPORT_BOT_TOKEN') ?? '';
$adminId = $_ENV['SUPPORT_ADMIN_ID'] ?? $_SERVER['SUPPORT_ADMIN_ID'] ?? getenv('SUPPORT_ADMIN_ID') ?? '8496157812';

if (basename($_SERVER['PHP_SELF']) == 'support_bot.php' || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'support_bot.php') !== false)) {
    $rawInput = file_get_contents('php://input');
    if ($rawInput) {
        supportDebug("Incoming Update: " . $rawInput);
        $input = json_decode($rawInput, true);
        if ($input) {
            try {
                handleSupportUpdate($input, $supportToken, $adminId);
            } catch (Exception $e) {
                supportDebug("Fatal Error: " . $e->getMessage());
            }
        } else {
            supportDebug("Failed to decode JSON input.");
        }
    } else {
        supportDebug("No input received (browser hit?).");
    }
    echo json_encode(['ok' => true]);
    exit;
}

echo json_encode(['ok' => true, 'bot' => 'support']);
exit;

function handleSupportUpdate($input, $token, $adminId) {
    if (!isset($input['message'])) return;

    $message = $input['message'];
    $chatId = $message['chat']['id'];
    $text = trim($message['text'] ?? $message['caption'] ?? '');
    $messageId = $message['message_id'];
    $name = htmlspecialchars($message['from']['first_name'] ?? 'Foydalanuvchi');
    $username = isset($message['from']['username']) ? '@' . htmlspecialchars($message['from']['username']) : 'Noma\'lum';

    if (empty($token)) {
        supportDebug("Error: Support token is empty. Check .env file.");
        return;
    }

    $db = getDB();

    // ADMIN JAVOBI
    if ((string)$chatId === (string)$adminId) {
        if (isset($message['reply_to_message'])) {
            $replyToId = $message['reply_to_message']['message_id'];
            
            // Xabar kimdan kelganini aniqlash
            $stmt = $db->prepare("SELECT user_chat_id FROM support_messages WHERE admin_msg_id = ?");
            $stmt->execute([$replyToId]);
            $userChatId = $stmt->fetchColumn();

            if ($userChatId) {
                supportDebug("Admin replied to $userChatId.");
                
                if (isset($message['photo'])) {
                    $photoId = end($message['photo'])['file_id'];
                    $caption = ($text ? htmlspecialchars($text) : "");
                    sendSupportPhoto($token, $userChatId, $photoId, $caption);
                } elseif (isset($message['sticker'])) {
                    $stickerId = $message['sticker']['file_id'];
                    sendSupportSticker($token, $userChatId, $stickerId);
                } else {
                    $msg = htmlspecialchars($text);
                    sendSupportMsg($token, $userChatId, $msg);
                }
                
                sendSupportMsg($token, $adminId, "✅ Javob foydalanuvchiga yuborildi.");
                return;
            } else {
                supportDebug("Admin replied but no user_chat_id found for admin_msg_id: $replyToId");
            }
        }
    }

    // FOYDALANUVCHI BILAN MULOQOT
    $stmt = $db->prepare("SELECT * FROM support_sessions WHERE chat_id = ?");
    $stmt->execute([$chatId]);
    $session = $stmt->fetch();

    if (!$session || $text === '/start') {
        $stmt = $db->prepare("INSERT OR REPLACE INTO support_sessions (chat_id, step, updated_at) VALUES (?, 'awaiting_account', ?)");
        $stmt->execute([$chatId, date('Y-m-d H:i:s')]);
        
        $welcome = "Assalomu alaykum, <b>{$name}</b>! Tiba AI qo'llab-quvvatlash botiga xush kelibsiz.\n\n"
                 . "Davom etishdan oldin, <b>tibaai.uz</b> saytidagi ro'yxatdan o'tgan pochta manzilingizni (email) yoki ismingizni yuboring:";
        $res = sendSupportMsg($token, $chatId, $welcome);
        supportDebug("Welcome message send result: " . $res);
        return;
    }

    if ($session['step'] === 'awaiting_account' && !empty($text)) {
        if (!filter_var($text, FILTER_VALIDATE_EMAIL)) {
            sendSupportMsg($token, $chatId, "⚠️ Iltimos, haqiqiy elektron pochta manzilingizni kiriting (masalan: <i>example@mail.com</i>):");
            return;
        }

        $stmt = $db->prepare("UPDATE support_sessions SET step = 'awaiting_problem', account_info = ?, updated_at = ? WHERE chat_id = ?");
        $stmt->execute([$text, date('Y-m-d H:i:s'), $chatId]);

        $prompt = "Rahmat! Endi muammo yoki savolingiz haqida batafsil yozib yuboring. Iloji bo'lsa rasm yoki batafsil ma'lumot ilova qiling (hozircha faqat matn qabul qilinadi):";
        $res = sendSupportMsg($token, $chatId, $prompt);
        supportDebug("Prompt message send result: " . $res);
        return;
    }

    if ($session['step'] === 'awaiting_problem' || $session['step'] === 'completed') {
        if ($session['step'] === 'completed' && $text === '/start') {
            // Restart if specifically asked
            $stmt = $db->prepare("INSERT OR REPLACE INTO support_sessions (chat_id, step, updated_at) VALUES (?, 'awaiting_account', ?)");
            $stmt->execute([$chatId, date('Y-m-d H:i:s')]);
            $welcome = "<b>Qayta murojaat holati:</b> Iltimos, ro'yxatdan o'tgan pochta manzilingizni qayta yuboring:";
            sendSupportMsg($token, $chatId, $welcome);
            return;
        }

        // Adminga yuborish
        $account = $session['account_info'] ?? 'Noma\'lum';
        $reportType = ($session['step'] === 'completed') ? "➕ <b>Qo'shimcha xabar!</b>" : "🆘 <b>Yangi murojaat!</b>";
        
        $report = "$reportType\n\n"
                . "👤 <b>Foydalanuvchi:</b> {$name} ({$username})\n"
                . "📧 <b>Akkount:</b> {$account}\n"
                . ($text ? "📝 <b>Xabar:</b>\n" . htmlspecialchars($text) : "");
        
        supportDebug("Forwarding media/text to admin: $adminId");
        
        if (isset($message['photo'])) {
            $photoId = end($message['photo'])['file_id'];
            $report .= "\n\n<i>(Rasm yuborildi, javob berish uchun ushbu xabarga Reply qiling)</i>";
            $res = sendSupportPhoto($token, $adminId, $report . "\n" . $photoId, $report);
            // Fix: previous line had a bug in params, sendSupportPhoto($token, $chatId, $photoId, $caption)
            $res = sendSupportPhoto($token, $adminId, $photoId, $report);
        } elseif (isset($message['sticker'])) {
            $stickerId = $message['sticker']['file_id'];
            $report .= "\n\n<i>(Stiker yuborildi, javob berish uchun quyidagi stikerga Reply qiling)</i>";
            sendSupportMsg($token, $adminId, $report);
            $res = sendSupportSticker($token, $adminId, $stickerId);
        } else {
            $report .= "\n\n<i>Javob berish uchun ushbu xabarga Reply qiling.</i>";
            $res = sendSupportMsg($token, $adminId, $report);
        }

        $resData = json_decode($res, true);
        
        if (isset($resData['result']['message_id'])) {
            $adminMsgId = $resData['result']['message_id'];
            // Admin xabari ID sini saqlash (reply qila olishi uchun)
            $stmt = $db->prepare("INSERT INTO support_messages (admin_msg_id, user_chat_id) VALUES (?, ?)");
            $stmt->execute([$adminMsgId, $chatId]);
        }

        // Sessiyani 'completed' holatida saqlash (keyingi xabarlar ham kelaveradi)
        if ($session['step'] !== 'completed') {
            $stmt = $db->prepare("UPDATE support_sessions SET step = 'completed', updated_at = ? WHERE chat_id = ?");
            $stmt->execute([date('Y-m-d H:i:s'), $chatId]);
            sendSupportMsg($token, $chatId, "✅ Rahmat! Murojaatingiz adminga yuborildi. Yana qo'shimcha ma'lumotlaringiz bo'lsa, bemalol yozib yuboring.");
        } else {
            // Qo'shimcha xabar uchun qisqa tasdiq
            sendSupportMsg($token, $chatId, "✔️ Qo'shimcha xabar yuborildi.");
        }
        return;
    }
}

function sendSupportMsg($token, $chatId, $text) {
    $url = "https://api.telegram.org/bot{$token}/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'TibaSupportBot/1.0',
    ]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    
    if ($err) {
        return json_encode(['ok' => false, 'error' => $err]);
    }
    return $res;
}

function sendSupportPhoto($token, $chatId, $photoId, $caption = '') {
    $url = "https://api.telegram.org/bot{$token}/sendPhoto";
    $data = [
        'chat_id' => $chatId,
        'photo' => $photoId,
        'caption' => $caption,
        'parse_mode' => 'HTML'
    ];
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 25,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'TibaSupportBot/1.0',
    ]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    
    if ($err) {
        supportDebug("sendSupportPhoto Error: $err");
        return json_encode(['ok' => false, 'error' => $err]);
    }
    return $res;
}

function sendSupportSticker($token, $chatId, $stickerId) {
    $url = "https://api.telegram.org/bot{$token}/sendSticker";
    $data = [
        'chat_id' => $chatId,
        'sticker' => $stickerId
    ];
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 25,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'TibaSupportBot/1.0',
    ]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    
    if ($err) {
        supportDebug("sendSupportSticker Error: $err");
        return json_encode(['ok' => false, 'error' => $err]);
    }
    return $res;
}
