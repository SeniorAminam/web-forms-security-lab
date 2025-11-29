# 📘 راهنمای تفصیلی: `final/register.php` و `final/profile.php`

## 🎯 خلاصه‌ی کلی

**Final Project** یک **پروژه جمع‌بندی** است که تمام مفاهیم قبلی را ترکیب می‌کند:

- ✅ **GET vs POST**: فرم POST استفاده می‌کند
- ✅ **Validation**: اعتبارسنجی سمت سرور
- ✅ **XSS Prevention**: `htmlspecialchars()` برای نمایش
- ✅ **Secure Redirect**: URL encoding برای ریدایرکت

---

## 📋 ساختار فایل

```
final/
├── register.php
│   ├── PHP Logic
│   │   ├── Validation
│   │   └── Redirect
│   └── HTML Form
└── profile.php
    ├── Data Verification
    └── Secure Display
```

---

## 🔍 تحلیل کد - `register.php`

### بخش 1: متغیرهای اولیه

```php
$errors = [];
$name = '';
$email = '';
$password = '';
```

**توضیح:**
- `$errors`: آرایه خطاها
- متغیرهای ورودی

---

### بخش 2: دریافت و Validation

```php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
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
```

**توضیح:**

#### الف) نام
- خالی نباشد
- حداقل ۳ کاراکتر

#### ب) ایمیل
- خالی نباشد
- فرمت صحیح

#### ج) رمز عبور
- خالی نباشد
- حداقل ۶ کاراکتر

---

### بخش 3: Redirect موفق

```php
// Process if no errors
if (empty($errors)) {
    // In production: Save to database
    // For demo: Redirect to profile
    $safe_name = urlencode($name);
    $safe_email = urlencode($email);
    header("Location: profile.php?name=$safe_name&email=$safe_email");
    exit;
}
```

**توضیح:**

#### الف) بررسی خطاها
```php
if (empty($errors))
```
- اگر خطایی نبود

#### ب) URL Encoding
```php
$safe_name = urlencode($name);
$safe_email = urlencode($email);
```
- تبدیل کاراکترهای خاص
- مثال: `علی` → `%D8%B9%D9%84%DB%8C`

#### ج) Redirect
```php
header("Location: profile.php?name=$safe_name&email=$safe_email");
exit;
```
- ریدایرکت به صفحه پروفایل

---

### بخش 4: HTML Form

```html
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
```

**توضیح:**

#### الف) POST Method
```html
<form action="" method="POST">
```
- داده‌ها در بدنه درخواست

#### ب) Value Preservation
```html
value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
```
- اگر خطا بود، داده‌ها باقی می‌ماند

#### ج) Error Display
```php
<?php if (isset($errors['name'])): ?>
    <div style="color: var(--error-color);">...</div>
<?php endif; ?>
```

---

## 🔍 تحلیل کد - `profile.php`

### بخش 1: Data Verification

```php
// Verify required data exists
if (!isset($_GET['name']) || !isset($_GET['email'])) {
    header("Location: register.php");
    exit;
}

$name = $_GET['name'];
$email = $_GET['email'];
```

**توضیح:**
- بررسی وجود داده‌ها
- اگر نبود، بازگشت به register.php

---

### بخش 2: Secure Display

```html
<div style="margin-top: 2rem; text-align: right; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 4px;">
    <p><strong>نام:</strong> <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>ایمیل:</strong> <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
</div>
```

**توضیح:**
- `htmlspecialchars()`: جلوگیری از XSS
- داده‌ها به صورت امن نمایش داده می‌شوند

---

## 🧪 نحوه استفاده - مثال عملی

### مثال 1: ثبت نام موفق

**مرحله 1:** `final/register.php` را باز کنید

**مرحله 2:** فرم را پر کنید
```
نام: علی محمدی
ایمیل: ali@example.com
رمز عبور: Password123
```

**مرحله 3:** دکمه "ثبت نام نهایی" را کلیک کنید

**خروجی:**
```
صفحه profile.php باز می‌شود:
🎉 ثبت نام موفقیت‌آمیز بود!

نام: علی محمدی
ایمیل: ali@example.com
```

---

### مثال 2: Validation Error

**مرحله 1:** فرم را اشتباه پر کنید
```
نام: AB (کوتاه!)
ایمیل: invalid-email (نامعتبر!)
رمز عبور: 123 (کوتاه!)
```

**مرحله 2:** دکمه "ثبت نام نهایی" را کلیک کنید

**خروجی:**
```
❌ نام باید حداقل ۳ کاراکتر باشد.
❌ ایمیل نامعتبر است.
❌ رمز عبور باید حداقل ۶ کاراکتر باشد.
```

---

### مثال 3: XSS Test

**مرحله 1:** فرم را پر کنید
```
نام: <script>alert(1)</script>
ایمیل: test@example.com
رمز عبور: Password123
```

**مرحله 2:** دکمه "ثبت نام نهایی" را کلیک کنید

**خروجی:**
```
❌ نام باید حداقل ۳ کاراکتر باشد.
```

**توضیح:**
- `<script>alert(1)</script>` = 21 کاراکتر ✅
- اما validation دیگر بررسی می‌کند

**اگر validation نبود:**
```
profile.php:
نام: &lt;script&gt;alert(1)&lt;/script&gt;
```
- اسکریپت اجرا نمی‌شود (htmlspecialchars)

---

## 🔐 نکات امنیتی

### ✅ نقاط قوت

1. **Validation سمت سرور**
   ```php
   if ($name === '' || mb_strlen($name) < 3)
   ```

2. **htmlspecialchars() برای Output**
   ```php
   echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
   ```

3. **URL Encoding برای Redirect**
   ```php
   $safe_name = urlencode($name);
   ```

4. **Data Verification**
   ```php
   if (!isset($_GET['name']) || !isset($_GET['email']))
   ```

### ⚠️ نقاط ضعف (برای آموزش)

1. **بدون Database**
   - داده‌ها ذخیره نمی‌شوند

2. **بدون Password Hashing**
   - رمز عبور ذخیره نمی‌شود

3. **بدون CSRF Token**
   - حمله CSRF ممکن است

4. **بدون HTTPS**
   - داده‌ها رمزگذاری نمی‌شوند

---

## 📊 جدول: مفاهیم ترکیب‌شده

| مفهوم | فایل | استفاده |
|------|------|--------|
| **GET vs POST** | register.php | POST برای فرم |
| **Validation** | register.php | اعتبارسنجی سمت سرور |
| **XSS Prevention** | profile.php | htmlspecialchars() |
| **Redirect** | register.php | header() |
| **URL Encoding** | register.php | urlencode() |

---

## 🎓 درس‌های یادگیری

### درس 1: Complete Flow
```
User Input → Validation → Redirect → Display
```

### درس 2: Security Layers
```
1. Server-side Validation
2. URL Encoding
3. Output Escaping
```

### درس 3: Best Practices
```
✅ POST برای داده‌های حساس
✅ Validation سمت سرور
✅ htmlspecialchars() برای output
✅ urlencode() برای URL
```

---

## 🔗 ارتباط با فایل‌های دیگر

- **قبلی:** `07_file_upload.php` - File Upload
- **مرتبط:** `02_validation.php` - Validation
- **مرتبط:** `03_xss_demo.php` - XSS Prevention

---

## 📝 خلاصه

**`final/register.php` و `final/profile.php`** یک پروژه نهایی است که:
- ✅ تمام مفاهیم را ترکیب می‌کند
- ✅ Validation و Security را نشان می‌دهد
- ✅ Complete workflow را پوشش می‌دهد
- ✅ Production-ready patterns استفاده می‌کند

**نویسنده:** Amin Davodian  
**وبسایت:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

*Developed by Amin Davodian - Web Security Lab*
