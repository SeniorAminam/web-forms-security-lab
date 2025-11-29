# امنیت وب – حملات و جلوگیری

**نویسنده:** Amin Davodian (Mohammadamin Davodian)  
**Website:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

## چطور شروع کنی

سرور را اجرا کن:

```bash
php -S localhost:8000
```

بعد برو به `http://localhost:8000/dashboard.php`

یا با XAMPP: پوشه پروژه را داخل `C:\xampp\htdocs\tsw` کپی کن و `http://localhost/tsw/dashboard.php` را باز کن.

---

## فایل‌های اصلی و نقش هر کدام

### `01_get_post.php` – GET و POST

**مثال:**
- فرم GET: کاربر "لپ‌تاپ" می‌نویسد → URL: `?query=لپ‌تاپ`
- فرم POST: رمز عبور می‌نویسد → URL تغییر نمی‌کند

**کد:**
```php
if (isset($_GET['query'])) {
    echo htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8');
}
```

**نتیجه:** GET برای جستجو، POST برای اطلاعات حساس.

---

### `02_validation.php` – Validation

**مثال:**
- نام خالی → خطا: "نام الزامی است."
- ایمیل نامعتبر → خطا: "ایمیل نامعتبر است."
- سن غیرعددی → خطا: "سن باید عدد باشد."

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

**نتیجه:** Validation در سمت سرور، نه کلاینت.

---

### `03_xss_demo.php` – XSS

**مثال ناامن:**
```php
echo $_POST['msg'];  // ❌ اگر کاربر <script>alert(1)</script> بفرستد، اجرا می‌شود
```

**مثال امن:**
```php
echo htmlspecialchars($_POST['msg'], ENT_QUOTES, 'UTF-8');  // ✅ فقط متن
```

**نتیجه:** `htmlspecialchars` کاراکترهای خطرناک را تبدیل می‌کند (`<` → `&lt;`).

---

### `04_live_chat_xss.php` – Persistent XSS

**مثال:**
- کاربر 1 این کد را در چت می‌نویسد: `<script>alert('Hacked')</script>`
- اگر ناامن باشد → کاربر 2 وقتی چت را باز کند، کد اجرا می‌شود.
- اگر امن باشد → فقط متن نمایش داده می‌شود.

**نتیجه:** Persistent XSS خطرناک‌تر از Reflected است (ذخیره می‌شود).

---

### `05_sql_injection.php` – SQL Injection

**مثال ناامن:**
```php
$query = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "'";
```

اگر کاربر این را بفرستد: `' OR '1'='1`

Query می‌شود:
```sql
SELECT * FROM users WHERE username = '' OR '1'='1'
```

نتیجه: **همه کاربرها برگشت می‌خورند** (حتی رمزها).

**مثال امن:**
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_POST['username']]);
```

**نتیجه:** Prepared Statement داده را از کد جدا می‌کند.

---

### `06_csrf_demo.php` – CSRF

**مثال:**
- کاربر به بانک آنلاین login کرده.
- بدون اطلاع، یک سایت دیگر درخواست Transfer می‌فرستد.
- اگر بدون CSRF Token باشد → Transfer انجام می‌شود.
- اگر CSRF Token داشته باشد → درخواست رد می‌شود.

**کد امن:**
```php
// هنگام نمایش فرم:
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

// هنگام Submit:
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF Attack Detected!");
}
```

**نتیجه:** CSRF Token یک‌بار مصرف است و تصادفی.

---

### `07_file_upload.php` – File Upload

**مثال ناامن:**
```php
move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $_FILES['file']['name']);
```

کاربر می‌تواند `shell.php` آپلود کند:
```php
<?php system($_GET['cmd']); ?>
```

بعد: `uploads/shell.php?cmd=ls` → تمام فایل‌های سرور را می‌بیند.

**مثال امن:**
```php
$allowed = ['jpg', 'png', 'gif'];
$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    die("Invalid file type!");
}

// بررسی محتوا
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['file']['tmp_name']);

if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) {
    die("Not a valid image!");
}

// نام تصادفی
$new_name = bin2hex(random_bytes(16)) . "." . $ext;
move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $new_name);
```

**نتیجه:** Whitelist پسوند، بررسی MIME، و نام تصادفی.

---

### `final/register.php` + `final/profile.php` – فرم ثبت‌نام

**فرم ثبت‌نام:**
```php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    // Validation
    if ($name === '') $errors['name'] = "نام الزامی است.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "ایمیل نامعتبر است.";
    
    if (empty($errors)) {
        header("Location: profile.php?name=" . urlencode($name) . "&email=" . urlencode($email));
        exit;
    }
}
```

**صفحه پروفایل:**
```php
$name = urldecode($_GET['name']);
$email = urldecode($_GET['email']);

echo "نام: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
echo "ایمیل: " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
```

**نتیجه:** Validation → ریدایرکت → نمایش امن.

---

## خلاصه: 7 حمله و جلوگیری

| حمله | مثال | جلوگیری |
|------|------|---------|
| GET/POST | داده در URL | POST برای حساس، HTTPS |
| Validation | نام خالی | Server-side check |
| XSS | `<script>` | `htmlspecialchars()` |
| Persistent XSS | چت مخرب | Escape در ذخیره و نمایش |
| SQL Injection | `' OR '1'='1` | Prepared Statement |
| CSRF | Transfer بدون اجازه | CSRF Token |
| File Upload | `shell.php` | Whitelist + MIME + نام تصادفی |

