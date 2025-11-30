<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-30
 * 
 * Live Chat XSS Lab - Multi-user chat simulation demonstrating persistent XSS
 * Developed by Amin Davodian
 */

$chatFile = 'chat_messages.json';
$secureMode = isset($_GET['secure']) && $_GET['secure'] == 1;

// Initialize chat file
if (!file_exists($chatFile)) {
    file_put_contents($chatFile, json_encode([]));
}

// Reset chat
if (isset($_GET['reset'])) {
    file_put_contents($chatFile, json_encode([]));
    header('Location: 04_live_chat_xss.php' . ($secureMode ? '?secure=1' : ''));
    exit;
}

// Handle new messages - Developed by Amin Davodian
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['message'])) {
    $username = $_POST['username'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Store message as-is (no validation) - for XSS demonstration
    if (strlen($username) > 0 && strlen($message) > 0) {
        $messages = json_decode(file_get_contents($chatFile), true) ?? [];
        
        $messages[] = [
            'user' => $username,
            'msg' => $message,
            'time' => date('H:i:s')
        ];
        
        file_put_contents($chatFile, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        // Redirect to prevent form resubmission
        header('Location: 04_live_chat_xss.php' . ($secureMode ? '?secure=1' : ''));
        exit;
    }
}

// Load messages
$allMessages = json_decode(file_get_contents($chatFile), true) ?? [];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat XSS Lab | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
    <style>
        .chat-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        .chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            background: rgba(0,0,0,0.5);
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .msg {
            margin-bottom: 0.75rem;
            padding: 0.75rem;
            border-radius: 4px;
            background: rgba(255,255,255,0.05);
            border-left: 3px solid var(--primary-color);
        }
        .msg-user { color: var(--primary-color); font-weight: bold; }
        .msg-time { color: var(--text-muted); font-size: 0.8rem; display: block; margin-bottom: 0.25rem; }
        .msg-content { word-break: break-word; }
        @media (max-width: 768px) {
            .chat-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem;">
            <h1>💬 چت‌روم عمومی (XSS Lab)</h1>
            <div>
                <a href="?secure=0" class="badge" style="<?php echo !$secureMode ? 'background:var(--error-color);' : ''; ?> padding:0.5rem 1rem; margin:0.25rem; text-decoration:none; color:white;">❌ Vulnerable</a>
                <a href="?secure=1" class="badge" style="<?php echo $secureMode ? 'background:var(--primary-color); color:#000;' : ''; ?> padding:0.5rem 1rem; margin:0.25rem; text-decoration:none; color:white;">✅ Secure</a>
                <a href="reset_page.php?page=04_live_chat_xss.php<?php echo $secureMode ? '&secure=1' : ''; ?>" class="badge" style="background:var(--warning-color); color:#000; padding:0.5rem 1rem; margin:0.25rem; text-decoration:none;">🔄 Reset All</a>
            </div>
        </div>

        <div class="chat-container">
            <!-- Chat Display -->
            <div class="card">
                <h2>📨 Chat Messages</h2>
                <div class="chat-box" id="chatBox">
                    <?php if (empty($allMessages)): ?>
                        <p style="color: var(--text-muted); text-align: center; padding: 2rem 0;">No messages yet...</p>
                    <?php else: ?>
                        <?php foreach ($allMessages as $msg): ?>
                            <div class="msg">
                                <span class="msg-time"><?php echo htmlspecialchars($msg['time']); ?></span>
                                <span class="msg-user"><?php echo htmlspecialchars($msg['user']); ?>:</span>
                                <span class="msg-content">
                                    <?php if ($secureMode): ?>
                                        <?php echo htmlspecialchars($msg['msg'], ENT_QUOTES, 'UTF-8'); ?>
                                    <?php else: ?>
                                        <?php echo $msg['msg']; ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Message Form -->
            <div class="card" style="border-top: 4px solid var(--secondary-color);">
                <h2 style="color: var(--secondary-color);">💀 Send Message</h2>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" placeholder="Enter your name" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea name="message" id="message" rows="4" placeholder="<script>alert('XSS')</script>" required></textarea>
                    </div>
                    <button type="submit">Send Message</button>
                </form>

                <div class="examples-container" style="margin-top: 1.5rem;">
                    <span class="examples-title">👇 Ready Payloads:</span>
                    <button type="button" class="example-btn attack" data-payload="&lt;script&gt;console.log('XSS')&lt;/script&gt;">Script Tag</button>
                    <button type="button" class="example-btn attack" data-payload="&lt;b onmouseover=alert(1)&gt;Hover me&lt;/b&gt;">Event Handler</button>
                    <button type="button" class="example-btn safe" data-payload="Hello, everyone!">Normal Message</button>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="card" style="margin-top: 2rem;">
            <h2>📚 Persistent XSS (Stored XSS)</h2>
            <p>In <strong>Vulnerable mode</strong>, messages are displayed without sanitization, allowing XSS payloads to execute.</p>
            <p>In <strong>Secure mode</strong>, messages are escaped using htmlspecialchars(), preventing code execution.</p>
        </div>
    </div>

    <script>
        // Developed by Amin Davodian - Live Chat XSS Security Handler
        
        // Decode HTML entities - Developed by Amin Davodian
        function decodeHTML(html) {
            const txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            // Setup payload buttons - only fill input, no submission
            document.querySelectorAll('button[data-payload]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const encodedPayload = btn.getAttribute('data-payload');
                    const payload = decodeHTML(encodedPayload);
                    
                    const usernameInput = document.getElementById('username');
                    const messageInput = document.getElementById('message');
                    
                    if (!usernameInput.value) {
                        usernameInput.value = 'Hacker_' + Math.floor(Math.random() * 1000);
                    }
                    
                    messageInput.value = payload;
                    messageInput.style.backgroundColor = '#fffbeb';
                    setTimeout(() => messageInput.style.backgroundColor = '', 500);
                    
                    if (window.logger) {
                        window.logger.log('Interaction', `Filled payload: ${payload.substring(0, 50)}`, 'warning');
                    }
                });
            });
            
            // Auto-scroll chat to bottom
            const chatBox = document.getElementById('chatBox');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
            
            if (window.logger) {
                window.logger.log('Security', 'Live Chat XSS Lab Loaded', 'info');
            }
        });
    </script>
</body>
</html>
