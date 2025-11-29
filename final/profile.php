<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * User Profile Page - Secure display of registered user data
 * Developed by Amin Davodian
 */

// Verify required data exists
if (!isset($_GET['name']) || !isset($_GET['email'])) {
    header("Location: register.php");
    exit;
}

$name = $_GET['name'];
$email = $_GET['email'];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل کاربری | Amin Davodian</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/console-logger.js"></script>
</head>
<body>
    <div class="container" style="text-align: center; margin-top: 5rem;">
        <div class="card">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
            <h1 style="color: var(--success-color);">ثبت نام موفقیت‌آمیز بود!</h1>
            <p>اطلاعات شما با امنیت کامل دریافت و نمایش داده شد.</p>
            
            <div style="margin-top: 2rem; text-align: right; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 4px;">
                <p><strong>نام:</strong> <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>ایمیل:</strong> <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <a href="register.php" style="display: inline-block; margin-top: 2rem; text-decoration: none; color: var(--primary-color);">
                &larr; بازگشت به صفحه ثبت نام
            </a>
        </div>
    </div>
</body>
</html>
