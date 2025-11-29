<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * GET vs POST Demo - Demonstrates the difference between HTTP methods
 * Developed by Amin Davodian
 */
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GET vs POST Demo | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
    <script src="assets/interceptor.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>1️⃣ تفاوت GET و POST</h1>
        
        <div class="grid">
            <!-- GET Form - Developed by Amin Davodian -->
            <div class="card">
                <h2><span class="badge badge-get">GET</span> متد</h2>
                <p class="alert alert-info">
                    داده‌ها در <strong>URL</strong> ارسال می‌شوند.<br>
                    مناسب برای جستجو و فیلتر کردن.
                </p>
                
                <div class="examples-container">
                    <span class="examples-title">👇 نمونه‌های قابل کلیک:</span>
                    <button class="example-btn safe" onclick="fillGet('لپ‌تاپ')">لپ‌تاپ</button>
                    <button class="example-btn safe" onclick="fillGet('گوشی موبایل')">گوشی موبایل</button>
                    <button class="example-btn attack" onclick="fillGet('<script>alert(1)</script>')">تست XSS</button>
                </div>

                <form action="" method="GET" id="getForm">
                    <div class="form-group">
                        <label>جستجو (Query):</label>
                        <input type="text" name="query" id="getQuery" placeholder="مثلاً: لپ‌تاپ">
                    </div>
                    <button type="submit">ارسال با GET</button>
                </form>

                <?php if (isset($_GET['query'])): ?>
                    <div class="alert alert-success" style="margin-top: 1rem;">
                        <strong>دریافت شد (GET):</strong><br>
                        <?php echo htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <pre>URL: <?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'); ?></pre>
                <?php endif; ?>
            </div>

            <!-- POST Form - Developed by Amin Davodian -->
            <div class="card">
                <h2><span class="badge badge-post">POST</span> متد</h2>
                <p class="alert alert-warning">
                    داده‌ها در <strong>بدنه درخواست</strong> مخفی هستند.<br>
                    مناسب برای رمز عبور و ثبت اطلاعات.
                </p>

                <div class="examples-container">
                    <span class="examples-title">👇 نمونه‌های قابل کلیک:</span>
                    <button class="example-btn safe" onclick="fillPost('123456')">رمز ساده</button>
                    <button class="example-btn safe" onclick="fillPost('P@ssw0rd!')">رمز قوی</button>
                    <button class="example-btn attack" onclick="fillPost('\' OR \'1\'=\'1')">تست SQL Injection</button>
                </div>

                <form action="" method="POST" id="postForm">
                    <div class="form-group">
                        <label>رمز عبور (Password):</label>
                        <input type="password" name="password" id="postPassword" placeholder="رمز عبور...">
                    </div>
                    <button type="submit">ارسال با POST</button>
                </form>

                <?php if (isset($_POST['password'])): ?>
                    <div class="alert alert-success" style="margin-top: 1rem;">
                        <strong>دریافت شد (POST):</strong><br>
                        <?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <pre>$_POST Array:
<?php echo htmlspecialchars(print_r($_POST, true), ENT_QUOTES, 'UTF-8'); ?></pre>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function fillGet(value) {
            const input = document.getElementById('getQuery');
            input.value = value;
            input.style.backgroundColor = '#fffbeb';
            
            // Log to console
            if(window.logger) {
                window.logger.log('Interaction', `Filled GET input with: ${value}`, 'info');
            }
            
            // Submit form after a short delay
            setTimeout(() => {
                input.style.backgroundColor = '';
                document.getElementById('getForm').submit();
            }, 300);
        }

        function fillPost(value) {
            const input = document.getElementById('postPassword');
            input.value = value;
            input.style.backgroundColor = '#fffbeb';
            
            // Log to console
            if(window.logger) {
                window.logger.log('Interaction', `Filled POST input with: ${value}`, 'info');
            }
            
            // Submit form after a short delay
            setTimeout(() => {
                input.style.backgroundColor = '';
                document.getElementById('postForm').submit();
            }, 300);
        }
    </script>
</body>
</html>

