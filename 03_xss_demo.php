<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-30
 * 
 * XSS Security Demo - Demonstrates Cross-Site Scripting vulnerabilities and prevention
 * Developed by Amin Davodian
 */

// Handle form submissions - Developed by Amin Davodian
$msg_vulnerable = '';
$msg_secure = '';
$show_vulnerable = false;
$show_secure = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['vulnerable_msg'])) {
        $msg_vulnerable = $_POST['vulnerable_msg'] ?? '';
        $show_vulnerable = !empty($msg_vulnerable);
    }
    
    if (isset($_POST['secure_msg'])) {
        $msg_secure = $_POST['secure_msg'] ?? '';
        $show_secure = !empty($msg_secure);
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Security Demo | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
</head>
<body>
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem;">
            <h1>3️⃣ حمله XSS (Cross-Site Scripting)</h1>
            <a href="reset_page.php?page=03_xss_demo.php" class="badge" style="background:var(--warning-color); color:#000; padding:0.5rem 1rem; text-decoration:none; border-radius:4px;">🔄 Reset All</a>
        </div>

        <div class="grid">
            <!-- Vulnerable Form -->
            <div class="card" style="border-top: 4px solid var(--error-color);">
                <h2>❌ روش ناامن (Vulnerable)</h2>
                <p>بدون فیلتر - کد مستقیم اجرا می‌شود!</p>
                
                <div class="examples-container">
                    <span class="examples-title">👇 نمونه‌های آماده:</span>
                    <button type="button" class="example-btn safe" data-payload="سلام دنیا!">متن ساده</button>
                    <button type="button" class="example-btn attack" data-payload="&lt;script&gt;console.log('XSS')&lt;/script&gt;">Script Tag</button>
                    <button type="button" class="example-btn attack" data-payload="&lt;b onmouseover=alert(1)&gt;Hover me&lt;/b&gt;">Event Handler</button>
                </div>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.5rem;">⚠️ Click buttons to fill input safely</p>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="vulnInput">پیام:</label>
                        <input type="text" name="vulnerable_msg" id="vulnInput" placeholder="<script>alert('Hacked!')</script>" autocomplete="off">
                    </div>
                    <button type="submit" class="btn-submit-bad">ارسال (بدون حفاظت)</button>
                </form>

                <?php if ($show_vulnerable): ?>
                    <div class="alert alert-error" style="margin-top: 1rem;">
                        <strong>نتیجه (بدون فیلتر):</strong><br>
                        <div style="background: rgba(0,0,0,0.3); padding: 0.75rem; border-radius: 4px; margin-top: 0.5rem; min-height: 2rem;">
                            <?php echo $msg_vulnerable; ?>
                        </div>
                        <pre style="background: rgba(0,0,0,0.5); padding: 0.5rem; border-radius: 4px; margin-top: 0.5rem; font-size: 0.8rem;">echo $input;</pre>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Secure Form -->
            <div class="card" style="border-top: 4px solid var(--success-color);">
                <h2>✅ روش امن (Secure)</h2>
                <p>با استفاده از htmlspecialchars()</p>

                <div class="examples-container">
                    <span class="examples-title">👇 نمونه‌های آماده:</span>
                    <button type="button" class="example-btn safe" data-payload-secure="سلام دنیا!">متن ساده</button>
                    <button type="button" class="example-btn attack" data-payload-secure="&lt;script&gt;console.log('XSS')&lt;/script&gt;">Script Tag</button>
                    <button type="button" class="example-btn attack" data-payload-secure="&lt;b onmouseover=alert(1)&gt;Hover me&lt;/b&gt;">Event Handler</button>
                </div>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.5rem;">⚠️ Click buttons to fill input safely</p>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="secureInput">پیام:</label>
                        <input type="text" name="secure_msg" id="secureInput" placeholder="<script>alert('Safe')</script>" autocomplete="off">
                    </div>
                    <button type="submit" class="btn-submit-good">ارسال (محافظت شده)</button>
                </form>

                <?php if ($show_secure): ?>
                    <div class="alert alert-success" style="margin-top: 1rem;">
                        <strong>نتیجه (ایمن شده):</strong><br>
                        <div style="background: rgba(0,0,0,0.3); padding: 0.75rem; border-radius: 4px; margin-top: 0.5rem; min-height: 2rem;">
                            <?php echo htmlspecialchars($msg_secure, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <pre style="background: rgba(0,0,0,0.5); padding: 0.5rem; border-radius: 4px; margin-top: 0.5rem; font-size: 0.8rem;">htmlspecialchars($input, ENT_QUOTES, 'UTF-8');</pre>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Information Section -->
        <div class="card" style="margin-top: 2rem;">
            <h2>📚 XSS Prevention Methods</h2>
            <ul style="margin: 0.5rem 0; padding-right: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
                <li><strong>htmlspecialchars():</strong> تبدیل کاراکترهای خاص به HTML entities</li>
                <li><strong>htmlentities():</strong> تبدیل تمام کاراکترهای قابل تبدیل</li>
                <li><strong>strip_tags():</strong> حذف تمام تگ‌های HTML</li>
                <li><strong>Content Security Policy (CSP):</strong> محدود کردن منابع اسکریپت</li>
                <li><strong>Input Validation:</strong> بررسی و تایید ورودی‌ها</li>
            </ul>
        </div>
    </div>

    <script>
        // Developed by Amin Davodian - XSS Demo Handler
        
        // Decode HTML entities safely - Developed by Amin Davodian
        function decodeHTML(html) {
            const txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }
        
        function fillInput(inputId, encodedPayload) {
            const payload = decodeHTML(encodedPayload);
            const input = document.getElementById(inputId);
            input.value = payload;
            input.style.backgroundColor = '#fffbeb';
            setTimeout(() => input.style.backgroundColor = '', 500);
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            // Vulnerable buttons
            document.querySelectorAll('button[data-payload]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const encodedPayload = btn.getAttribute('data-payload');
                    fillInput('vulnInput', encodedPayload);
                    
                    if (window.logger) {
                        const payload = decodeHTML(encodedPayload);
                        window.logger.log('Interaction', `Filled Vulnerable Input: ${payload.substring(0, 50)}`, 'warning');
                    }
                });
            });
            
            // Secure buttons
            document.querySelectorAll('button[data-payload-secure]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const encodedPayload = btn.getAttribute('data-payload-secure');
                    fillInput('secureInput', encodedPayload);
                    
                    if (window.logger) {
                        const payload = decodeHTML(encodedPayload);
                        window.logger.log('Interaction', `Filled Secure Input: ${payload.substring(0, 50)}`, 'success');
                    }
                });
            });
            
            if (window.logger) {
                window.logger.log('Security', 'XSS Demo Loaded - Educational Purpose Only', 'info');
            }
        });
    </script>
</body>
</html>

