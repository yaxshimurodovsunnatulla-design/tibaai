<?php
require_once __DIR__ . '/config.php';

// Webhook Verification (for setup)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_mode']) && $_GET['hub_mode'] === 'subscribe') {
    $verifyToken = getenv('INSTA_WEBHOOK_VERIFY_TOKEN') ?: 'tibaai_secret';
    if ($_GET['hub_verify_token'] === $verifyToken) {
        header('Content-Type: text/plain');
        echo $_GET['hub_challenge'];
        exit;
    }
}

// Handle Incoming Notifications (Comments)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = getInput();
    
    // Log for debugging
    $logDir = __DIR__ . '/../tmp';
    if (!is_dir($logDir)) mkdir($logDir, 0755, true);
    file_put_contents($logDir . '/insta_webhook.log', date('[Y-m-d H:i:s] ') . json_encode($input) . "\n", FILE_APPEND);

    foreach ($input['entry'] ?? [] as $entry) {
        $accountId = $entry['id']; // Instagram Professional Account ID
        
        foreach ($entry['changes'] ?? [] as $change) {
            if ($change['field'] === 'comments') {
                $comment = $change['value'];
                $commentText = strtolower($comment['text'] ?? '');
                $recipientId = $comment['from']['id']; // User who commented

                // Find rules for this Instagram Account
                try {
                    $db = getDB();
                    // Select users who have this account_id linked
                    $stmtUser = $db->prepare("SELECT id, insta_access_token FROM users WHERE insta_account_id = ?");
                    $stmtUser->execute([$accountId]);
                    $users = $stmtUser->fetchAll();

                    foreach ($users as $u) {
                        // Find active rules for this user that match the comment trigger
                        $stmtRules = $db->prepare("SELECT * FROM insta_rules WHERE user_id = ? AND is_active = 1");
                        $stmtRules->execute([$u['id']]);
                        $rules = $stmtRules->fetchAll();

                        foreach ($rules as $rule) {
                            $trigger = strtolower($rule['trigger_word']);
                            if (strpos($commentText, $trigger) !== false) {
                                // MATCH! Send DM
                                sendInstaDM($u['insta_access_token'], $recipientId, $rule['dm_text'], $rule['button_text'], $rule['button_url']);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("Webhook Error: " . $e->getMessage());
                }
            }
        }
    }
    echo "OK";
}

function sendInstaDM($accessToken, $recipientId, $text, $btnText = null, $btnUrl = null) {
    if (!$accessToken) return;

    $url = "https://graph.facebook.com/v19.0/me/messages?access_token=" . $accessToken;
    
    // Format message
    $msgContent = ['text' => $text];
    if ($btnText && $btnUrl) {
        $msgContent['text'] .= "\n\n🔗 " . $btnText . ": " . $btnUrl;
        
        /* 
        Generic Template (requires Messenger/Insta approval usually)
        $msgContent = [
            'attachment' => [
                'type' => 'template',
                'payload' => [
                    'template_type' => 'generic',
                    'elements' => [[
                        'title' => $text,
                        'buttons' => [[
                            'type' => 'web_url',
                            'url' => $btnUrl,
                            'title' => $btnText
                        ]]
                    ]]
                ]
            ]
        ];
        */
    }

    $payload = [
        'recipient' => ['id' => $recipientId],
        'message' => $msgContent
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false // Often needed on shared hosting/localhost if certs are old
    ]);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code !== 200) {
        error_log("Insta DM Send Failed ($code): " . $res);
    }
}
