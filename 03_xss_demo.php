<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * XSS Security Demo - Demonstrates Cross-Site Scripting vulnerabilities and prevention
 * Developed by Amin Davodian
 */
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Security Demo | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
    <script src="assets/interceptor.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>3️⃣ حمله XSS (Cross-Site Scripting)</h1>

        <div class="grid">
            <!-- Vulnerable Form - For educational purposes only -->
            <div class="card" style="border-top: 4px solid var(--error-color);">
                <h2>❌ روش ناامن (Vulnerable)</h2>
                <p>هر چیزی بنویسید، مستقیم چاپ می‌شود.</p>
                
                <div class="examples-container">
                    <span class="examples-title">👇 نمونه‌های قابل کلیک:</span>
                    <button class="example-btn safe" onclick="fillBad('سلام دنیا!')">متن ساده</button>
                    <button class="example-btn attack" onclick="fillBad('<script>alert(1)</script>')">اسکریپت Alert</button>
                    <button class="example-btn attack" onclick="fillBad('<img src=x onerror=alert(1)>')">تصویر مخرب</button>
                </div>

                <form action="" method="POST" id="badForm">
                    <div class="form-group">
                        <label>پیام شما:</label>
                        <input type="text" name="msg_bad" id="inputBad" placeholder="<script>alert('Hacked!')</script>">
                    </div>
                    <button type="submit" style="background-color: var(--error-color);">ارسال خطرناک</button>
                </form>

                <?php if (isset($_POST['msg_bad'])): ?>
                    <div class="alert alert-error" style="margin-top: 1rem;">
                        <strong>نتیجه (بدون فیلتر):</strong><br>
                        <?php 
                        // VULNERABLE - For demonstration only! Never do this in production!
                        echo $_POST['msg_bad']; 
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Secure Form - Developed by Amin Davodian -->
            <div class="card" style="border-top: 4px solid var(--success-color);">
                <h2>✅ روش امن (Secure)</h2>
                <p>با استفاده از <code>htmlspecialchars()</code></p>

                <div class="examples-container">
                    <span class="examples-title">👇 نمونه‌های قابل کلیک:</span>
                    <button class="example-btn safe" onclick="fillGood('سلام دنیا!')">متن ساده</button>
                    <button class="example-btn attack" onclick="fillGood('<script>alert(1)</script>')">تست حمله</button>
                </div>

                <form action="" method="POST" id="goodForm">
                    <div class="form-group">
                        <label>پیام شما:</label>
                        <input type="text" name="msg_good" id="inputGood" placeholder="<script>alert('Safe')</script>">
                    </div>
                    <button type="submit" style="background-color: var(--success-color);">ارسال امن</button>
                </form>

                <?php if (isset($_POST['msg_good'])): ?>
                    <div class="alert alert-success" style="margin-top: 1rem;">
                        <strong>نتیجه (ایمن شده):</strong><br>
                        <?php echo htmlspecialchars($_POST['msg_good'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <pre>Code: htmlspecialchars($input);</pre>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function fillBad(value) {
            const input = document.getElementById('inputBad');
            input.value = value;
            input.style.backgroundColor = '#fffbeb';
            setTimeout(() => input.style.backgroundColor = '', 500);
            
            if(window.logger) {
                window.logger.log('Interaction', `Filled Vulnerable Input: ${value}`, 'warning');
            }
        }

        function fillGood(value) {
            const input = document.getElementById('inputGood');
            input.value = value;
            input.style.backgroundColor = '#fffbeb';
            setTimeout(() => input.style.backgroundColor = '', 500);
            
            if(window.logger) {
                window.logger.log('Interaction', `Filled Secure Input: ${value}`, 'success');
            }
        }
    </script>
</body>
</html>

