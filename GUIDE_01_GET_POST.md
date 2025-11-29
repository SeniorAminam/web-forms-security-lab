# 📘 راهنمای تفصیلی: `01_get_post.php`

## 🎯 خلاصه‌ی کلی

این فایل یکی از مهم‌ترین مفاهیم وب را نشان می‌دهد: **تفاوت بین متدهای HTTP - GET و POST**.

در واقع، هر بار که شما در مرورگر یک فرم پر می‌کنید و ارسال می‌کنید، داده‌ها به یکی از دو روش به سرور می‌رسند:
- **GET**: داده‌ها در URL دیده می‌شوند (نامحفوظ)
- **POST**: داده‌ها در بدنه درخواست مخفی هستند (نسبتاً محفوظ‌تر)

---

## 📋 ساختار فایل

```
01_get_post.php
├── Header (PHP Comment Block)
├── HTML Structure
│   ├── Head (Meta, CSS, JS)
│   └── Body
│       ├── Container
│       ├── Grid Layout (2 Cards)
│       │   ├── GET Card
│       │   └── POST Card
│       └── JavaScript Functions
```

---

## 🔍 تحلیل کد - بخش به بخش

### بخش 1: Header و معلومات پروژه

```php
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
```

**توضیح:**
- این بخش اطلاعات پروژه و نویسنده را مشخص می‌کند
- تاریخ ایجاد: ۲۴ نوامبر ۲۰۲۵
- هدف: نمایش تفاوت GET و POST

---

### بخش 2: HTML Head

```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GET vs POST Demo | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
    <script src="assets/interceptor.js" defer></script>
</head>
```

**توضیح:**
- `charset="UTF-8"`: پشتیبانی از فارسی و کاراکترهای خاص
- `viewport`: طراحی responsive برای موبایل
- `style.css`: استایل‌های Cyberpunk/Hacker
- `console-logger.js`: ثبت تعاملات کاربر
- `interceptor.js`: رهگیری درخواست‌های HTTP

---

### بخش 3: GET Card (فرم GET)

```html
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
```

**توضیح:**

#### الف) بخش توضیح
- نشان می‌دهد که GET داده‌ها را در URL قرار می‌دهد
- مناسب برای جستجو و فیلتر کردن

#### ب) دکمه‌های مثال
- **"لپ‌تاپ"**: مثال عادی
- **"گوشی موبایل"**: مثال دیگر
- **"تست XSS"**: نشان می‌دهد که GET چقدر خطرناک است (اگر escape نشود)

#### ج) فرم GET
```html
<form action="" method="GET" id="getForm">
```
- `method="GET"`: داده‌ها در URL ارسال می‌شوند
- `action=""`: به همان صفحه بازمی‌گردد

#### د) دریافت و نمایش داده
```php
<?php if (isset($_GET['query'])): ?>
    <div class="alert alert-success">
        <strong>دریافت شد (GET):</strong><br>
        <?php echo htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <pre>URL: <?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'); ?></pre>
<?php endif; ?>
```

**نکات امنیتی:**
- `htmlspecialchars()`: کاراکترهای خطرناک را escape می‌کند
- `$_SERVER['REQUEST_URI']`: URL کامل را نمایش می‌دهد

---

### بخش 4: POST Card (فرم POST)

```html
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
```

**توضیح:**

#### الف) بخش توضیح
- POST داده‌ها را در بدنه درخواست قرار می‌دهد
- مناسب برای رمز عبور و اطلاعات حساس

#### ب) دکمه‌های مثال
- **"رمز ساده"**: ۶ رقم
- **"رمز قوی"**: ترکیب حروف، اعداد، نمادها
- **"تست SQL Injection"**: نشان می‌دهد خطر SQL Injection

#### ج) فرم POST
```html
<form action="" method="POST" id="postForm">
    <input type="password" name="password" id="postPassword" placeholder="رمز عبور...">
</form>
```
- `method="POST"`: داده‌ها در بدنه درخواست ارسال می‌شوند
- `type="password"`: ورودی مخفی (نقاط سیاه)

#### د) دریافت و نمایش داده
```php
<?php if (isset($_POST['password'])): ?>
    <div class="alert alert-success">
        <strong>دریافت شد (POST):</strong><br>
        <?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <pre>$_POST Array:
<?php echo htmlspecialchars(print_r($_POST, true), ENT_QUOTES, 'UTF-8'); ?></pre>
<?php endif; ?>
```

---

### بخش 5: JavaScript Functions

```javascript
function fillGet(value) {
    const input = document.getElementById('getQuery');
    input.value = value;
    input.style.backgroundColor = '#fffbeb';
    setTimeout(() => input.style.backgroundColor = '', 500);
    
    // Log to console
    if(window.logger) {
        window.logger.log('Interaction', `Filled GET input with: ${value}`, 'info');
    }
}

function fillPost(value) {
    const input = document.getElementById('postPassword');
    input.value = value;
    input.style.backgroundColor = '#fffbeb';
    setTimeout(() => input.style.backgroundColor = '', 500);
    
    // Log to console
    if(window.logger) {
        window.logger.log('Interaction', `Filled POST input with: ${value}`, 'info');
    }
}
```

**توضیح:**
- `fillGet()`: دکمه‌های مثال را در فرم GET پر می‌کند
- `fillPost()`: دکمه‌های مثال را در فرم POST پر می‌کند
- `backgroundColor = '#fffbeb'`: رنگ زرد برای نشان دادن تغییر
- `window.logger.log()`: ثبت تعاملات در کنسول

---

## 🧪 نحوه استفاده - مثال عملی

### مثال 1: استفاده از GET

**مرحله 1:** صفحه را باز کنید
```
http://localhost:8000/01_get_post.php
```

**مرحله 2:** روی دکمه "لپ‌تاپ" کلیک کنید
```
- ورودی پر می‌شود: "لپ‌تاپ"
- رنگ زرد می‌شود
```

**مرحله 3:** دکمه "ارسال با GET" را کلیک کنید
```
URL تغییر می‌کند:
http://localhost:8000/01_get_post.php?query=لپ‌تاپ
```

**خروجی:**
```
دریافت شد (GET):
لپ‌تاپ

URL: /01_get_post.php?query=لپ‌تاپ
```

---

### مثال 2: استفاده از POST

**مرحله 1:** روی دکمه "رمز قوی" کلیک کنید
```
- ورودی پر می‌شود: "P@ssw0rd!"
- رنگ زرد می‌شود
```

**مرحله 2:** دکمه "ارسال با POST" را کلیک کنید
```
URL تغییر نمی‌کند (مهم!)
http://localhost:8000/01_get_post.php
```

**خروجی:**
```
دریافت شد (POST):
P@ssw0rd!

$_POST Array:
Array
(
    [password] => P@ssw0rd!
)
```

---

### مثال 3: تست XSS با GET

**مرحله 1:** روی دکمه "تست XSS" کلیک کنید
```
- ورودی پر می‌شود: <script>alert(1)</script>
```

**مرحله 2:** دکمه "ارسال با GET" را کلیک کنید

**خروجی (امن - به دلیل htmlspecialchars):**
```
دریافت شد (GET):
<script>alert(1)</script>

URL: /01_get_post.php?query=%3Cscript%3Ealert%281%29%3C%2Fscript%3E
```

**نکته مهم:** اگر `htmlspecialchars()` استفاده نشود، اسکریپت اجرا می‌شود!

---

## 🔐 نکات امنیتی

### ✅ نقاط قوت این کد

1. **استفاده از `htmlspecialchars()`**
   ```php
   echo htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8');
   ```
   - کاراکترهای خطرناک را escape می‌کند
   - جلوگیری از XSS

2. **نمایش URL کامل**
   ```php
   echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
   ```
   - کاربر می‌تواند ببیند که داده‌ها در URL هستند

3. **استفاده از `type="password"`**
   ```html
   <input type="password" name="password">
   ```
   - رمز عبور در مرورگر مخفی می‌شود

### ⚠️ نقاط ضعف (برای آموزش)

1. **GET داده‌ها در URL دیده می‌شوند**
   - اگر کسی شانه‌ی شما را نگاه کند، رمز را می‌بیند
   - در تاریخچه مرورگر ذخیره می‌شود

2. **POST بدون HTTPS**
   - اگر HTTPS نباشد، داده‌ها رمزگذاری نمی‌شوند
   - هکر می‌تواند داده‌ها را رهگیری کند

3. **بدون Validation**
   - کد فقط داده‌ها را escape می‌کند
   - اعتبارسنجی (مثلاً طول) نیست

---

## 📊 جدول مقایسه GET و POST

| ویژگی | GET | POST |
|------|-----|------|
| **جایگاه داده** | URL | بدنه درخواست |
| **دیده شدن** | ✅ دیده می‌شود | ❌ مخفی است |
| **محدودیت اندازه** | ~2000 کاراکتر | نامحدود |
| **کش شدن** | ✅ کش می‌شود | ❌ کش نمی‌شود |
| **نشانک** | ✅ می‌توان ذخیره کرد | ❌ نمی‌توان |
| **مناسب برای** | جستجو، فیلتر | رمز، فایل، حساس |

---

## 🎓 درس‌های یادگیری

### درس 1: HTTP Methods
- GET برای خواندن داده‌ها
- POST برای ارسال داده‌های حساس

### درس 2: امنیت
- هرگز رمز را در URL قرار ندهید
- همیشه HTTPS استفاده کنید
- داده‌ها را escape کنید

### درس 3: کاربر‌پذیری
- دکمه‌های مثال برای تست سریع
- نمایش URL برای درک بهتر
- رنگ‌های مختلف برای GET/POST

---

## 🔗 ارتباط با فایل‌های دیگر

- **بعدی:** `02_validation.php` - اعتبارسنجی ورودی‌ها
- **مرتبط:** `03_xss_demo.php` - XSS و escape
- **داشبورد:** `dashboard.php` - نقطه ورود

---

## 📝 خلاصه

**`01_get_post.php`** یک دمو تعاملی است که:
- ✅ تفاوت GET و POST را نشان می‌دهد
- ✅ داده‌ها را به‌صورت امن escape می‌کند
- ✅ مثال‌های عملی برای تست فراهم می‌کند
- ✅ مبنای یادگیری امنیت وب است

**نویسنده:** Amin Davodian  
**وبسایت:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

*Developed by Amin Davodian - Web Security Lab*
