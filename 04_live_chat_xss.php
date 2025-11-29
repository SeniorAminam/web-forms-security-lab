<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * Live Chat XSS Lab - Multi-user chat simulation demonstrating persistent XSS
 * Developed by Amin Davodian
 */

$file = 'chat_data.txt';
if (!file_exists($file)) {
    file_put_contents($file, '');
}

// Reset Chat functionality
if (isset($_GET['reset'])) {
    file_put_contents($file, '');
    header("Location: 04_live_chat_xss.php");
    exit;
}

// Handle incoming messages - Developed by Amin Davodian
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? 'Anonymous';
    $msg = $_POST['msg'] ?? '';
    
    // Store message (simulating database storage)
    $entry = json_encode([
        'user' => $user,
        'msg' => $msg,
        'time' => date('H:i:s')
    ]) . "\n";
    file_put_contents($file, $entry, FILE_APPEND);
}

$messages = file($file);
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
        .chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            background: rgba(0,0,0,0.5);
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .msg {
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            border-radius: 4px;
            background: rgba(255,255,255,0.05);
        }
        .msg-user { color: var(--primary-color); font-weight: bold; }
        .msg-time { color: var(--text-muted); font-size: 0.8rem; float: left; }
    </style>
</head>
<body>
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h1>💬 چت‌روم عمومی (XSS Lab)</h1>
            <a href="?reset=1" style="color:var(--error-color); text-decoration:none;">[پاکسازی چت]</a>
        </div>

        <div class="grid">
            <!-- Chat View - Developed by Amin Davodian -->
            <div class="card">
                <h2>نمایشگر چت</h2>
                <div class="chat-box" id="chatBox">
                    <?php 
                    foreach ($messages as $line): 
                        $data = json_decode($line, true);
                        if (!$data) continue;
                        
                        // Security mode toggle
                        $secure_view = isset($_GET['secure']) && $_GET['secure'] == 1;
                        $display_msg = $secure_view ? htmlspecialchars($data['msg']) : $data['msg'];
                    ?>
                        <div class="msg">
                            <span class="msg-time"><?php echo htmlspecialchars($data['time']); ?></span>
                            <span class="msg-user"><?php echo htmlspecialchars($data['user']); ?>:</span>
                            <span><?php echo $display_msg; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="text-align: center;">
                    <a href="?secure=0" class="badge" style="background:var(--error-color); color:white; text-decoration:none;">حالت ناامن (Vulnerable)</a>
                    <a href="?secure=1" class="badge" style="background:var(--primary-color); color:black; text-decoration:none;">حالت امن (Secure)</a>
                </div>
            </div>

            <!-- Attacker Console - Developed by Amin Davodian -->
            <div class="card" style="border-color: var(--secondary-color);">
                <h2 style="color: var(--secondary-color);">💀 کنسول هکر</h2>
                <form action="" method="POST" id="chatForm">
                    <div class="form-group">
                        <label>نام کاربری:</label>
                        <input type="text" name="user" id="chatUser" placeholder="Hacker101">
                    </div>
                    <div class="form-group">
                        <label>پیام (Payload):</label>
                        <textarea name="msg" id="chatMsg" rows="4" placeholder="<script>alert('Hacked!');</script>"></textarea>
                    </div>
                    <button type="submit" style="border-color: var(--secondary-color); color: var(--secondary-color);">ارسال پیام</button>
                </form>

                <div class="examples-container" style="margin-top: 2rem;">
                    <span class="examples-title">👇 Payloadهای آماده (کلیک کنید):</span>
                    <button class="example-btn attack" onclick="fillChat('<script>alert(document.cookie)</script>')">سرقت کوکی</button>
                    <button class="example-btn attack" onclick="fillChat('<style>body{background:red;}</style>')">تغییر ظاهر (CSS)</button>
                    <button class="example-btn attack" onclick="fillChat('<img src=x onerror=alert(\'XSS\')>')">تزریق تصویر</button>
                    <button class="example-btn safe" onclick="fillChat('سلام دوستان! چطورید؟')">پیام معمولی</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillChat(payload) {
            const msgInput = document.getElementById('chatMsg');
            const userInput = document.getElementById('chatUser');
            
            if(!userInput.value) userInput.value = 'Hacker_' + Math.floor(Math.random() * 100);
            
            msgInput.value = payload;
            msgInput.style.backgroundColor = '#fffbeb';
            setTimeout(() => msgInput.style.backgroundColor = '', 500);
            
            if(window.logger) {
                window.logger.log('Interaction', `Prepared Chat Payload: ${payload}`, 'warning');
            }
        }
    </script>
</body>
</html>
