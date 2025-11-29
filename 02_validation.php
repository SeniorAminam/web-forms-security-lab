<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * Validation Demo - Server-side input validation demonstration
 * Developed by Amin Davodian
 */

$errors = [];
$success = false;
$name = '';
$email = '';
$age = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input - Developed by Amin Davodian
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = trim($_POST['age'] ?? '');

    // Validation Logic
    if (empty($name)) {
        $errors['name'] = "لطفاً نام خود را وارد کنید.";
    } elseif (mb_strlen($name) < 3) {
        $errors['name'] = "نام باید حداقل ۳ کاراکتر باشد.";
    }

    if (empty($email)) {
        $errors['email'] = "ایمیل الزامی است.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "فرمت ایمیل صحیح نیست.";
    }

    if (empty($age)) {
        $errors['age'] = "سن الزامی است.";
    } elseif (!is_numeric($age) || $age < 18) {
        $errors['age'] = "سن باید عدد و حداقل ۱۸ سال باشد.";
    }

    if (empty($errors)) {
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation Demo | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
    <script src="assets/interceptor.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>2️⃣ اعتبارسنجی ورودی‌ها (Validation)</h1>
        
        <div class="card">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    ✅ <strong>تبریک!</strong> اطلاعات شما با موفقیت تایید شد.<br>
                    نام: <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?><br>
                    ایمیل: <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <div class="examples-container">
                <span class="examples-title">👇 نمونه‌های قابل کلیک:</span>
                <button class="example-btn safe" onclick="fillForm('امین داودیان', 'amin@example.com', '25')">✅ داده معتبر</button>
                <button class="example-btn attack" onclick="fillForm('A', 'invalid-email', '10')">❌ داده نامعتبر</button>
                <button class="example-btn attack" onclick="fillForm('<script>alert(1)</script>', 'admin@site.com', '20')">💉 تست XSS</button>
            </div>

            <form action="" method="POST" novalidate id="validationForm">
                <div class="form-group">
                    <label>نام کامل:</label>
                    <input type="text" name="name" id="inputName" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" 
                           class="<?php echo isset($errors['name']) ? 'input-error' : ''; ?>">
                    <?php if (isset($errors['name'])): ?>
                        <div class="error-msg"><?php echo $errors['name']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>ایمیل:</label>
                    <input type="email" name="email" id="inputEmail" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                           class="<?php echo isset($errors['email']) ? 'input-error' : ''; ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-msg"><?php echo $errors['email']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>سن:</label>
                    <input type="number" name="age" id="inputAge" value="<?php echo htmlspecialchars($age, ENT_QUOTES, 'UTF-8'); ?>"
                           class="<?php echo isset($errors['age']) ? 'input-error' : ''; ?>">
                    <?php if (isset($errors['age'])): ?>
                        <div class="error-msg"><?php echo $errors['age']; ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit">بررسی اطلاعات</button>
            </form>
        </div>
    </div>

    <script>
        function fillForm(name, email, age) {
            const nameInput = document.getElementById('inputName');
            const emailInput = document.getElementById('inputEmail');
            const ageInput = document.getElementById('inputAge');

            nameInput.value = name;
            emailInput.value = email;
            ageInput.value = age;

            [nameInput, emailInput, ageInput].forEach(input => {
                input.style.backgroundColor = '#fffbeb';
                setTimeout(() => input.style.backgroundColor = '', 500);
            });

            // Log to console
            if(window.logger) {
                window.logger.log('Interaction', `Filled Form: Name=${name}, Email=${email}, Age=${age}`, 'info');
            }
        }
    </script>
</body>
</html>
