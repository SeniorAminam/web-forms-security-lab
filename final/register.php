<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * Registration Form - Final exercise combining validation and security
 * Developed by Amin Davodian
 */

$errors = [];
$name = '';
$email = '';
$password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input - Developed by Amin Davodian
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation Logic
    if ($name === '') {
        $errors['name'] = "نام الزامی است.";
    } elseif (mb_strlen($name) < 3) {
        $errors['name'] = "نام باید حداقل ۳ کاراکتر باشد.";
    }

    if ($email === '') {
        $errors['email'] = "ایمیل الزامی است.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "ایمیل نامعتبر است.";
    }

    if ($password === '') {
        $errors['password'] = "رمز عبور الزامی است.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "رمز عبور باید حداقل ۶ کاراکتر باشد.";
    }

    // Process if no errors
    if (empty($errors)) {
        // In production: Save to database
        // For demo: Redirect to profile
        $safe_name = urlencode($name);
        $safe_email = urlencode($email);
        header("Location: profile.php?name=$safe_name&email=$safe_email");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نام در دوره | Amin Davodian</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/console-logger.js"></script>
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <div class="card">
            <h1 style="text-align: center;">📝 ثبت نام در دوره</h1>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;">
                پروژه نهایی: ترکیب تمام مفاهیم
            </p>

            <form action="" method="POST">
                <div class="form-group">
                    <label>نام و نام خانوادگی:</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if (isset($errors['name'])): ?>
                        <div style="color: var(--error-color); font-size: 0.8rem;"><?php echo $errors['name']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>ایمیل دانشجویی:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div style="color: var(--error-color); font-size: 0.8rem;"><?php echo $errors['email']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>رمز عبور:</label>
                    <input type="password" name="password">
                    <?php if (isset($errors['password'])): ?>
                        <div style="color: var(--error-color); font-size: 0.8rem;"><?php echo $errors['password']; ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" style="width: 100%;">ثبت نام نهایی</button>
            </form>
        </div>
    </div>
</body>
</html>
