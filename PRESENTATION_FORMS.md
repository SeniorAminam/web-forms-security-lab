# فرم‌ها و دریافت داده از کاربر

**نویسنده:** Amin Davodian (Mohammadamin Davodian)  
**Website:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

## چطور این فایل‌ها را استفاده کنی

سرور را اجرا کن:

```bash
php -S localhost:8000
```

بعد برو به `http://localhost:8000/dashboard.php`

یا اگر XAMPP داری، پوشه پروژه را داخل `C:\xampp\htdocs\tsw` کپی کن و `http://localhost/tsw/dashboard.php` را باز کن.

---

## `01_get_post.php` – تفاوت GET و POST

این فایل دو فرم دارد:

**فرم GET:**
```html
<form method="GET">
  <input name="query">
  <button>جستجو</button>
</form>
```

وقتی کاربر "لپ‌تاپ" می‌نویسد و Submit می‌کند، URL این‌طور می‌شود:
```
http://localhost:8000/01_get_post.php?query=لپ‌تاپ
```

داده در URL دیده می‌شود. اگر این لینک را به دوست بفرستی، اون هم همان جستجو را می‌بیند.

**فرم POST:**
```html
<form method="POST">
  <input type="password" name="password">
  <button>ارسال</button>
</form>
```

وقتی رمز عبور را می‌نویسی، URL تغییر نمی‌کند. داده در بدنه درخواست ارسال می‌شود (Request Body).

**کد PHP:**
```php
<?php
if (isset($_GET['query'])) {
    echo "جستجو: " . htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['password'])) {
    echo "رمز دریافت شد: " . htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
}
?>
```

**نتیجه:**
- GET برای جستجو و فیلتر خوب است (داده در URL دیده می‌شود).
- POST برای اطلاعات حساس (رمز، اطلاعات شخصی) بهتر است.
- هر دو باید با HTTPS استفاده شوند تا داده رمزگذاری شود.

---

## `02_validation.php` – اعتبارسنجی ورودی

این فایل یک فرم دارد با سه فیلد: نام، ایمیل، سن.

**کد PHP:**
```php
<?php
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = trim($_POST['age'] ?? '');

    // Validation
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

    if ($age === '') {
        $errors['age'] = "سن الزامی است.";
    } elseif (!is_numeric($age) || $age < 1 || $age > 150) {
        $errors['age'] = "سن باید عدد معتبر باشد.";
    }

    if (empty($errors)) {
        echo "✅ تبریک! اطلاعات شما تایید شد.";
        echo "نام: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        echo "ایمیل: " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    }
}
?>
```

**نتیجه:**
- اگر نام خالی باشد → خطا: "نام الزامی است."
- اگر نام کمتر از ۳ کاراکتر باشد → خطا: "نام باید حداقل ۳ کاراکتر باشد."
- اگر ایمیل فرمت درست نداشته باشد → خطا: "ایمیل نامعتبر است."
- اگر سن عدد نباشد → خطا: "سن باید عدد معتبر باشد."
- اگر همه چیز درست باشد → پیام موفقیت و نمایش داده‌ها (با `htmlspecialchars` برای امنیت).

**نکته مهم:** این Validation در **سمت سرور** انجام می‌شود. حتی اگر کاربر JavaScript را خاموش کند، باز هم کار می‌کند.

---

## `03_xss_demo.php` – حمله XSS و جلوگیری

این فایل دو فرم دارد: یکی ناامن، یکی امن.

**فرم ناامن (Vulnerable):**
```php
<?php
if (isset($_POST['msg_bad'])) {
    echo "پیام شما: " . $_POST['msg_bad'];  // ❌ خطرناک!
}
?>
```

اگر کاربر این کد را وارد کند:
```html
<script>alert('Hacked!');</script>
```

مرورگر این را **اجرا می‌کند** و Popup ظاهر می‌شود.

**فرم امن (Secure):**
```php
<?php
if (isset($_POST['msg_good'])) {
    echo "پیام شما: " . htmlspecialchars($_POST['msg_good'], ENT_QUOTES, 'UTF-8');  // ✅ امن!
}
?>
```

اگر کاربر همان کد را وارد کند، این‌بار فقط **متن** نمایش داده می‌شود:
```
<script>alert('Hacked!');</script>
```

چون `htmlspecialchars` کاراکترهای خطرناک را تبدیل می‌کند:
- `<` → `&lt;`
- `>` → `&gt;`
- `"` → `&quot;`

**نتیجه:** مرورگر دیگر این را کد نمی‌شناسد، فقط متن است.

---

## `final/register.php` – فرم ثبت‌نام

این فایل یک فرم ثبت‌نام دارد با نام، ایمیل و رمز عبور.

**کد PHP:**
```php
<?php
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
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

    if (empty($errors)) {
        // ریدایرکت به صفحه پروفایل
        $safe_name = urlencode($name);
        $safe_email = urlencode($email);
        header("Location: profile.php?name=$safe_name&email=$safe_email");
        exit;
    }
}
?>
```

**نتیجه:**
- اگر فرم اشتباه پر شود → پیام‌های خطا نمایش داده می‌شود.
- اگر فرم درست پر شود → ریدایرکت به `profile.php` با داده‌های امن‌شده در URL.

---

## `final/profile.php` – نمایش نتیجه

این فایل داده‌های name و email را از URL می‌گیرد و نمایش می‌دهد.

**کد PHP:**
```php
<?php
if (!isset($_GET['name']) || !isset($_GET['email'])) {
    header("Location: register.php");
    exit;
}

$name = urldecode($_GET['name']);
$email = urldecode($_GET['email']);
?>

<h1>پروفایل</h1>
<p>نام: <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></p>
<p>ایمیل: <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
```

**نتیجه:**
- اگر name یا email در URL نباشد → برگشت به `register.php`.
- اگر داده‌ها موجود باشند → نمایش آن‌ها با `htmlspecialchars` برای امنیت.

---

## خلاصه: چطور همه‌چیز با هم کار می‌کند

1. **`01_get_post.php`** → نشان می‌دهد داده‌ها چطور ارسال می‌شوند (GET در URL، POST در بدنه).
2. **`02_validation.php`** → نشان می‌دهد چطور ورودی‌ها را اعتبارسنجی کنیم.
3. **`03_xss_demo.php`** → نشان می‌دهد خطر XSS و چطور از آن جلوگیری کنیم.
4. **`final/register.php` + `final/profile.php`** → جمع همه‌چیز: فرم ثبت‌نام، Validation، و نمایش امن نتیجه.
