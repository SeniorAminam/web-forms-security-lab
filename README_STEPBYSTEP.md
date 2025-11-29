# راهنمای کامل: از شروع تا ارائه

**نویسنده:** Amin Davodian  
**Website:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam  
**License:** MIT

---

## درباره این راهنما

اگر برای اولین بار می‌خواهید این پروژه را استفاده کنید یا یک ارائه آموزشی تهیه می‌کنید، این فایل برای شماست.

در اینجا هر فایل را با مثال‌های واقعی و خروجی‌های احتمالی توضیح می‌دهم.

---

## شروع سریع

سرور را اجرا کنید:

```bash
cd /path/to/project
php -S localhost:8000
```

سپس `http://localhost:8000/dashboard.php` را باز کنید. این صفحه مرکزی است و تمام demos از اینجا شروع می‌شود.

---

## فرم‌ها و GET/POST

اگر می‌خواهید یاد بگیرید چطوری داده‌ها از فرم‌ها دریافت می‌شود، این بخش برای شماست.

**راهنمای کامل:** [`PRESENTATION_FORMS.md`](PRESENTATION_FORMS.md)

### `01_get_post.php` – تفاوت GET و POST

این فایل دو فرم را نمایش می‌دهد. یکی از GET استفاده می‌کند، یکی از POST.

**مثال:**

اگر در فرم GET کلمه "لپ‌تاپ" را بنویسید و ارسال کنید:
- URL می‌شود: `?query=لپ‌تاپ`
- داده در URL دیده می‌شود

اگر در فرم POST رمز "MyPassword123" را بنویسید:
- URL تغییر نمی‌کند
- داده مخفی است

**خروجی:**
```
GET: لپ‌تاپ
POST: MyPassword123
```

**کد:**
```php
if (isset($_GET['query'])) {
    echo htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8');
}
if (isset($_POST['password'])) {
    echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
}
```

**نتیجه:** GET برای جستجو خوب است، POST برای اطلاعات حساس بهتر است.

**مرتبط:** [`PRESENTATION_FORMS.md`](PRESENTATION_FORMS.md) | [`02_validation.php`](#02_validationphp--اعتبارسنجی-ورودی)

---

### `02_validation.php` – اعتبارسنجی ورودی

این فایل یک فرم با سه فیلد (نام، ایمیل، سن) را نمایش می‌دهد و هر فیلد را سمت سرور بررسی می‌کند.

**مثال:**

اگر نام خالی بگذارید:
- خطا: "نام الزامی است."

اگر نام "ام" بنویسید (2 حرف):
- خطا: "نام باید حداقل ۳ کاراکتر باشد."

اگر ایمیل "notanemail" بنویسید:
- خطا: "ایمیل نامعتبر است."

اگر همه را درست پر کنید:
- پیام موفقیت ظاهر می‌شود

**خروجی (خطا):**
```
نام الزامی است.
ایمیل نامعتبر است.
```

**خروجی (موفقیت):**
```
✅ ثبت‌نام موفق!
```

**کد:**
```php
if ($name === '') {
    $errors['name'] = "نام الزامی است.";
} elseif (mb_strlen($name) < 3) {
    $errors['name'] = "نام باید حداقل ۳ کاراکتر باشد.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "ایمیل نامعتبر است.";
}
```

**نتیجه:** Validation سمت سرور الزامی است. حتی اگر JavaScript خاموش باشد، کار می‌کند.

**مرتبط:** [`PRESENTATION_FORMS.md`](PRESENTATION_FORMS.md) | [`03_xss_demo.php`](#03_xss_demophp--xss-و-جلوگیری)

---

### `03_xss_demo.php` – XSS و جلوگیری

این فایل دو فرم را نمایش می‌دهد. یکی ناامن است، یکی امن.

**مثال:**

اگر این کد را بنویسید:
```javascript
<script>alert('Hacked!')</script>
```

**در فرم ناامن:**
- Alert ظاهر می‌شود
- کد اجرا شد

**در فرم امن:**
- فقط متن نمایش داده می‌شود
- کد اجرا نشد

**خروجی (ناامن):**
```
Alert: Hacked!
```

**خروجی (امن):**
```
<script>alert('Hacked!')</script>
```

**کد (ناامن):**
```php
echo $_POST['msg'];  // ❌ خطرناک!
```

**کد (امن):**
```php
echo htmlspecialchars($_POST['msg'], ENT_QUOTES, 'UTF-8');  // ✅ امن!
```

`htmlspecialchars()` کاراکترهای خطرناک را تبدیل می‌کند:
- `<` → `&lt;`
- `>` → `&gt;`
- `"` → `&quot;`

**نتیجه:** همیشه خروجی کاربر را encode کنید.

**مرتبط:** [`PRESENTATION_FORMS.md`](PRESENTATION_FORMS.md) | [`04_live_chat_xss.php`](#04_live_chat_xssphp--persistent-xss)

---

### `final/register.php` و `final/profile.php` – فرم ثبت‌نام کامل

این دو فایل یک فرم ثبت‌نام کامل را نشان می‌دهند. اول validation انجام می‌شود، سپس ریدایرکت، سپس نمایش امن.

**مثال:**

اگر نام خالی بگذارید:
- خطا: "نام الزامی است."

اگر همه را درست پر کنید:
- ریدایرکت به `profile.php`
- داده‌ها به‌صورت امن نمایش داده می‌شود

**خروجی (register.php - خطا):**
```
نام الزامی است.
ایمیل نامعتبر است.
```

**خروجی (profile.php - موفقیت):**
```
نام: محمد علی
ایمیل: test@example.com
```

**کد (register.php):**
```php
if (empty($errors)) {
    header("Location: profile.php?name=" . urlencode($name) . "&email=" . urlencode($email));
    exit;
}
```

**کد (profile.php):**
```php
$name = urldecode($_GET['name'] ?? '');
echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
```

**نتیجه:** Validation → ریدایرکت → نمایش امن. هر مرحله امن است.

**مرتبط:** [`PRESENTATION_FORMS.md`](PRESENTATION_FORMS.md) | [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md)

---

## امنیت وب - 7 حمله

اگر می‌خواهید یاد بگیرید چطوری حملات امنیتی کار می‌کنند و چطوری از آن‌ها جلوگیری کنید، این بخش برای شماست.

**راهنمای کامل:** [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md)

### `04_live_chat_xss.php` – Persistent XSS

این فایل یک چت شبیه‌سازی شده است. اگر کد مخرب بنویسید، ذخیره می‌شود و تمام کاربران متأثر می‌شوند.

**مثال:**

اگر این کد را بنویسید:
```html
<style>body{background:red;}</style>
```

**خروجی:**
- صفحه قرمز می‌شود
- تمام کاربران این را می‌بینند

**نتیجه:** Persistent XSS خطرناک‌تر از Reflected است.

**مرتبط:** [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md) | [`05_sql_injection.php`](#05_sql_injectionphp--sql-injection)

---

### `05_sql_injection.php` – SQL Injection

این فایل نشان می‌دهد چطوری می‌توان از طریق ورودی کاربر، دیتابیس را هک کرد.

**مثال:**

اگر این کد را بنویسید:
```
' OR '1'='1
```

**کوئری ناامن:**
```sql
SELECT * FROM users WHERE username = '' OR '1'='1'
```

**خروجی:**
- تمام کاربرها برگشت می‌خورند
- رمزها دیده می‌شود

**کد (ناامن):**
```php
$query = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "'";
```

**کد (امن):**
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_POST['username']]);
```

**نتیجه:** Prepared Statements داده را از کد جدا می‌کند.

**مرتبط:** [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md) | [`06_csrf_demo.php`](#06_csrf_demophp--csrf)

---

### `06_csrf_demo.php` – CSRF

این فایل نشان می‌دهد چطوری می‌توان بدون اطلاع کاربر، عملیات انجام داد.

**مثال:**

شما به بانک login می‌کنید. بدون اطلاع، یک سایت دیگر Transfer می‌فرستد. بانک فکر می‌کند این درخواست از خود شماست.

**خروجی:**
- پول Transfer می‌شود
- شما متوجه نمی‌شوید

**کد (امن):**
```php
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF Attack Detected!");
}
```

**نتیجه:** CSRF Token یک‌بار مصرف است و تصادفی است.

**مرتبط:** [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md) | [`07_file_upload.php`](#07_file_uploadphp--file-upload)

---

### `07_file_upload.php` – File Upload

این فایل نشان می‌دهد چطوری می‌توان از طریق آپلود فایل، سرور را هک کرد.

**مثال:**

اگر این فایل را آپلود کنید:
```php
<?php system($_GET['cmd']); ?>
```

**خروجی:**
- سرور کنترل کامل در دست هکر است
- تمام فایل‌ها حذف می‌شود

**کد (امن):**
```php
$allowed = ['jpg', 'png', 'gif'];
if (!in_array($ext, $allowed)) die('Invalid!');

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
if (!in_array($mime, ['image/jpeg', 'image/png'])) die('Not image!');

$newName = uniqid() . '.' . $ext;
move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $newName);
```

**نتیجه:** Extension whitelist + MIME type check + random naming.

**مرتبط:** [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md)

---

## تمام فایل‌های پروژه

### فایل‌های Demo

**مرحله 1 - فرم‌ها:**
- [`01_get_post.php`](#01_get_postphp--تفاوت-get-و-post) – GET و POST
- [`02_validation.php`](#02_validationphp--اعتبارسنجی-ورودی) – Validation
- [`03_xss_demo.php`](#03_xss_demophp--xss-و-جلوگیری) – XSS
- [`final/register.php`](#final/registerphp-و-final/profilephp--فرم-ثبت‌نام-کامل) – Registration
- [`final/profile.php`](#final/registerphp-و-final/profilephp--فرم-ثبت‌نام-کامل) – Profile

**مرحله 2 - امنیت:**
- [`04_live_chat_xss.php`](#04_live_chat_xssphp--persistent-xss) – Persistent XSS
- [`05_sql_injection.php`](#05_sql_injectionphp--sql-injection) – SQL Injection
- [`06_csrf_demo.php`](#06_csrf_demophp--csrf) – CSRF
- [`07_file_upload.php`](#07_file_uploadphp--file-upload) – File Upload

### فایل‌های راهنمایی

- [`README.md`](README.md) – نمای کلی پروژه
- [`PRESENTATION_FORMS.md`](PRESENTATION_FORMS.md) – راهنمای فرم‌ها
- [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md) – راهنمای امنیت
- [`CONTRIBUTING.md`](CONTRIBUTING.md) – راهنمای مشارکت
- [`README_STEPBYSTEP.md`](README_STEPBYSTEP.md) – این فایل

### فایل‌های دیگر

- `dashboard.php` – صفحه اصلی
- `slides.html` – اسلایدهای ارائه
- `assets/` – استایل‌ها و اسکریپت‌ها
- `LICENSE` – MIT License
- `.gitignore` – Git ignore rules

---

## چطور از این پروژه استفاده کنید

اگر معلم هستید:
1. [`PRESENTATION_FORMS.md`](PRESENTATION_FORMS.md) را بخوانید
2. [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md) را بخوانید
3. هر فایل را نشان دهید

اگر دانشجو هستید:
1. `dashboard.php` را باز کنید
2. هر demo را تجربه کنید
3. [`PRESENTATION_FORMS.md`](PRESENTATION_FORMS.md) و [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md) را بخوانید

---

## تماس

- **Website:** https://senioramin.com
- **GitHub:** https://github.com/SeniorAminam
- **LinkedIn:** https://linkedin.com/in/SudoAmin

**Developed by Amin Davodian**
