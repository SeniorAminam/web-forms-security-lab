# 📘 راهنمای تفصیلی: `06_csrf_demo.php`

## 🎯 خلاصه‌ی کلی

این فایل یکی از **خطرناک‌ترین حملات وب** را نشان می‌دهد: **CSRF (Cross-Site Request Forgery)**.

CSRF یعنی اینکه هکر می‌تواند **بدون اطلاع کاربر** اقدامات حساسی را انجام دهد:
- 💸 پول را انتقال دهد
- 🔑 رمز عبور را تغییر دهد
- 📧 ایمیل را تغییر دهد
- 🗑️ حساب را حذف کند

**مثال:**
```
1. کاربر در بانک وارد می‌شود
2. بدون خروج، یک وبسایت خطرناک را باز می‌کند
3. وبسایت خطرناک یک فرم مخفی ارسال می‌کند
4. بانک فرم را قبول می‌کند (کاربر وارد است!)
5. پول انتقال می‌یابد!
```

---

## 📋 ساختار فایل

```
06_csrf_demo.php
├── PHP Logic
│   ├── Session Management
│   ├── CSRF Token Generation
│   ├── Vulnerable Mode
│   │   └── بدون CSRF token
│   └── Secure Mode
│       └── با CSRF token validation
├── HTML Structure
│   ├── Balance Display
│   ├── Mode Toggle
│   ├── Malicious Form
│   └── Protection Methods
└── JavaScript Functions
    ├── fillTransfer()
    └── executeCSRF()
```

---

## 🔍 تحلیل کد - بخش به بخش

### بخش 1: Session و Balance

```php
session_start();

// Initialize balance
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 10000; // Initial balance: $10,000
}
```

**توضیح:**
- `session_start()`: شروع session
- `$_SESSION['balance']`: موجودی حساب
- مقدار اولیه: $10,000

---

### بخش 2: CSRF Token Generation

```php
// Generate CSRF token for secure mode
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
```

**توضیح:**

#### الف) توکن تصادفی
```php
bin2hex(random_bytes(32))
```
- `random_bytes(32)`: ۳۲ بایت تصادفی
- `bin2hex()`: تبدیل به hexadecimal (۶۴ کاراکتر)

**مثال:**
```
a3f8b2c1d9e4f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0
```

#### ب) ذخیره‌سازی
```php
$_SESSION['csrf_token'] = ...
```
- توکن در session ذخیره می‌شود
- تنها سرور می‌داند

---

### بخش 3: Mode Selection

```php
$message = '';
$transferSuccess = false;
$secureMode = isset($_GET['secure']) && $_GET['secure'] == 1;
```

**توضیح:**
- `$secureMode`: اگر `?secure=1` بود، حالت امن
- `$message`: پیام نتیجه
- `$transferSuccess`: آیا انتقال موفق بود

---

### بخش 4: Vulnerable Mode

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transfer'])) {
    $amount = floatval($_POST['amount'] ?? 0);
    $recipient = $_POST['recipient'] ?? '';
    
    if ($secureMode) {
        // SECURE VERSION - Check CSRF token
        // ...
    } else {
        // VULNERABLE VERSION - No CSRF protection
        if ($amount > 0 && $amount <= $_SESSION['balance']) {
            $_SESSION['balance'] -= $amount;
            $transferSuccess = true;
            $message = "✅ Transferred $" . number_format($amount, 2) . " to " . htmlspecialchars($recipient);
        } else {
            $message = '❌ Invalid amount or insufficient balance.';
        }
    }
}
```

**توضیح:**

#### الف) دریافت داده‌ها
```php
$amount = floatval($_POST['amount'] ?? 0);
$recipient = $_POST['recipient'] ?? '';
```
- مبلغ و گیرنده

#### ب) بدون بررسی CSRF
```php
if ($amount > 0 && $amount <= $_SESSION['balance']) {
    $_SESSION['balance'] -= $amount;
    $transferSuccess = true;
}
```
- **خطرناک!** هیچ بررسی‌ای نیست
- هکر می‌تواند فرم مخفی ارسال کند

---

### بخش 5: Secure Mode

```php
if ($secureMode) {
    // SECURE VERSION - Check CSRF token
    $providedToken = $_POST['csrf_token'] ?? '';
    
    if (!hash_equals($_SESSION['csrf_token'], $providedToken)) {
        $message = '❌ CSRF token validation failed! Transfer blocked.';
    } elseif ($amount > 0 && $amount <= $_SESSION['balance']) {
        $_SESSION['balance'] -= $amount;
        $transferSuccess = true;
        $message = "✅ Successfully transferred $" . number_format($amount, 2) . " to " . htmlspecialchars($recipient);
    } else {
        $message = '❌ Invalid amount or insufficient balance.';
    }
}
```

**توضیح:**

#### الف) دریافت توکن
```php
$providedToken = $_POST['csrf_token'] ?? '';
```
- توکن ارسال‌شده از فرم

#### ب) مقایسه توکن‌ها
```php
if (!hash_equals($_SESSION['csrf_token'], $providedToken)) {
    $message = '❌ CSRF token validation failed! Transfer blocked.';
}
```

**نکته:** `hash_equals()` برای جلوگیری از timing attacks استفاده می‌شود

#### ج) انتقال موفق
```php
elseif ($amount > 0 && $amount <= $_SESSION['balance']) {
    $_SESSION['balance'] -= $amount;
    $transferSuccess = true;
}
```
- اگر توکن معتبر بود، انتقال انجام می‌شود

---

### بخش 6: Balance Display

```html
<div class="balance-display animate-fade">
    <div style="color: var(--text-muted); font-size: 1.2rem; margin-bottom: 0.5rem;">
        💰 Current Balance
    </div>
    <div class="balance-amount">
        $<?php echo number_format($_SESSION['balance'], 2); ?>
    </div>
</div>
```

**توضیح:**
- نمایش موجودی فعلی
- `number_format()`: فرمت پول (مثلاً $10,000.00)

---

### بخش 7: Malicious Form

```html
<div class="malicious-form">
    <h3 style="color: var(--error-color); font-size: 1rem;">⚠️ Hidden CSRF Attack Form</h3>
    <p style="font-size: 0.9rem; color: var(--text-muted);">
        این فرم به صورت خودکار ارسال می‌شود بدون اطلاع کاربر!
    </p>
    
    <form action="" method="POST" id="maliciousForm">
        <input type="hidden" name="recipient" value="Hacker_Account">
        <input type="hidden" name="amount" value="5000">
        <input type="hidden" name="transfer" value="1">
    </form>
    
    <button onclick="executeCSRF()" style="background: var(--error-color); border-color: var(--error-color);">
        🎯 Execute CSRF Attack
    </button>
```

**توضیح:**

#### الف) فرم مخفی
```html
<form action="" method="POST" id="maliciousForm">
    <input type="hidden" name="recipient" value="Hacker_Account">
    <input type="hidden" name="amount" value="5000">
    <input type="hidden" name="transfer" value="1">
</form>
```
- `type="hidden"`: ورودی‌های مخفی
- کاربر نمی‌بیند

#### ب) مقادیر
- `recipient`: حساب هکر
- `amount`: $5,000
- `transfer`: نشانگر انتقال

---

### بخش 8: Attack Code Preview

```html
<div class="attack-preview">
    <strong style="color: var(--error-color);">Attack Code:</strong>
    <pre style="margin-top: 0.5rem; color: var(--primary-color);">&lt;!-- Malicious Website --&gt;
&lt;img src="bank.com/transfer.php?
     recipient=Hacker&amount=5000" 
     style="display:none"&gt;

&lt;!-- OR Auto-submit form --&gt;
&lt;body onload="document.forms[0].submit()"&gt;
    &lt;form action="bank.com/transfer" method="POST"&gt;
        &lt;input name="amount" value="5000"&gt;
        &lt;input name="recipient" value="Hacker"&gt;
    &lt;/form&gt;
&lt;/body&gt;</pre>
</div>
```

**توضیح:**

#### الف) روش 1: تصویر
```html
<img src="bank.com/transfer.php?recipient=Hacker&amount=5000" style="display:none">
```
- تصویر مخفی
- مرورگر درخواست را ارسال می‌کند

#### ب) روش 2: Auto-submit
```html
<body onload="document.forms[0].submit()">
    <form action="bank.com/transfer" method="POST">
        <input name="amount" value="5000">
        <input name="recipient" value="Hacker">
    </form>
</body>
```
- صفحه بارگذاری می‌شود
- فرم خودکار ارسال می‌شود

---

### بخش 9: Protection Methods

```html
<div style="margin-top: 2rem; padding: 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 4px;">
    <h3 style="color: var(--primary-color); font-size: 1rem;">🛡️ CSRF Protection Methods:</h3>
    <ul style="margin: 0.5rem 0; padding-right: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
        <li><strong>CSRF Tokens:</strong> Random token در هر فرم</li>
        <li><strong>SameSite Cookies:</strong> محدود کردن ارسال کوکی‌ها</li>
        <li><strong>Origin Header Check:</strong> بررسی منبع درخواست</li>
        <li><strong>Re-authentication:</strong> تایید دوباره برای اقدامات حساس</li>
        <li><strong>CAPTCHA:</strong> برای عملیات‌های مهم</li>
    </ul>
</div>
```

**توضیح:**

#### الف) CSRF Tokens
- توکن تصادفی در هر فرم
- هکر نمی‌داند توکن چیست

#### ب) SameSite Cookies
```
Set-Cookie: session=abc123; SameSite=Strict
```
- کوکی تنها در درخواست‌های same-site ارسال می‌شود

#### ج) Origin Header Check
```php
if ($_SERVER['HTTP_ORIGIN'] !== 'https://mybank.com') {
    die('CSRF attack detected!');
}
```

#### د) Re-authentication
- برای انتقال پول، رمز عبور دوباره بخواهید

#### ه) CAPTCHA
- برای عملیات‌های مهم

---

### بخش 10: JavaScript Functions

```javascript
function executeCSRF() {
    if (<?php echo $secureMode ? 'true' : 'false'; ?>) {
        alert('🛡️ Attack Blocked!\n\nThe secure mode has CSRF token validation.\nThe attack cannot succeed without a valid token.');
        if(window.logger) window.logger.log('Security', 'CSRF Attack Blocked by Token', 'success');
    } else {
        if (confirm('⚠️ Warning!\n\nThis will transfer $5,000 to Hacker_Account.\n\nIn a real attack, this would happen without user knowledge.\n\nProceed with demo?')) {
            if(window.logger) window.logger.log('Security', 'CSRF Attack Executed!', 'error');
            document.getElementById('maliciousForm').submit();
        }
    }
}
```

**توضیح:**
- اگر حالت امن: حمله مسدود می‌شود
- اگر حالت ناامن: فرم ارسال می‌شود

---

## 🧪 نحوه استفاده - مثال‌های عملی

### مثال 1: Vulnerable Mode

**مرحله 1:** حالت ناامن را انتخاب کنید
```
?secure=0
```

**مرحله 2:** دکمه "Execute CSRF Attack" را کلیک کنید

**مرحله 3:** تایید کنید

**خروجی:**
```
💰 Current Balance
$5,000.00

✅ Transferred $5,000.00 to Hacker_Account
```

**توضیح:**
- موجودی از $10,000 به $5,000 کاهش یافت
- **حمله موفق!**

---

### مثال 2: Secure Mode

**مرحله 1:** حالت امن را انتخاب کنید
```
?secure=1
```

**مرحله 2:** دکمه "Execute CSRF Attack" را کلیک کنید

**خروجی:**
```
🛡️ Attack Blocked!

The secure mode has CSRF token validation.
The attack cannot succeed without a valid token.
```

**توضیح:**
- حمله مسدود شد
- موجودی تغییر نکرد
- **محفوظ!**

---

### مثال 3: Reset Balance

**مرحله 1:** دکمه "Reset Balance" را کلیک کنید

**خروجی:**
```
💰 Current Balance
$10,000.00
```

**توضیح:**
- موجودی به $10,000 بازگشت

---

## 🔐 نکات امنیتی

### ✅ نقاط قوت این کد

1. **CSRF Token Generation**
   ```php
   $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
   ```
   - توکن تصادفی و قوی

2. **Secure Comparison**
   ```php
   hash_equals($_SESSION['csrf_token'], $providedToken)
   ```
   - جلوگیری از timing attacks

3. **Mode Toggle**
   - کاربر می‌تواند بین ناامن و امن انتخاب کند

### ⚠️ نقاط ضعف (برای آموزش)

1. **Vulnerable Mode در تولید**
   - این کد **هرگز** در تولید استفاده نشود

2. **بدون SameSite Cookies**
   - باید در تولید استفاده شود

3. **بدون Origin Check**
   - بررسی منبع درخواست

---

## 📊 جدول: CSRF vs XSS

| ویژگی | CSRF | XSS |
|------|------|-----|
| **نوع حمله** | Request Forgery | Script Injection |
| **نیاز به کاربر** | وارد شده | هر کاربری |
| **اقدام** | انتقال پول، تغییر رمز | سرقت کوکی، redirect |
| **دفاع** | CSRF Token | htmlspecialchars() |

---

## 🎓 درس‌های یادگیری

### درس 1: CSRF چیست؟
- حمله‌ای که فرم مخفی ارسال می‌کند
- کاربر بدون اطلاع متأثر می‌شود

### درس 2: چگونه کار می‌کند
- کاربر در بانک وارد است
- وبسایت خطرناک فرم مخفی ارسال می‌کند
- بانک فرم را قبول می‌کند

### درس 3: دفاع
- **CSRF Token:** بهترین روش
- **SameSite Cookies:** محدود کردن
- **Origin Check:** بررسی منبع

### درس 4: Best Practices
- **هر فرم:** توکن CSRF
- **هر session:** توکن جدید
- **حساس:** re-authentication

---

## 🔗 ارتباط با فایل‌های دیگر

- **قبلی:** `05_sql_injection.php` - SQL Injection
- **بعدی:** `07_file_upload.php` - File Upload
- **مرتبط:** `final/register.php` - فرم امن

---

## 📝 خلاصه

**`06_csrf_demo.php`** یک دمو تعاملی است که:
- ✅ حمله CSRF را نشان می‌دهد
- ✅ Session و Balance را استفاده می‌کند
- ✅ CSRF Token validation را نشان می‌دهد
- ✅ روش‌های دفاع را توضیح می‌دهد

**نویسنده:** Amin Davodian  
**وبسایت:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

*Developed by Amin Davodian - Web Security Lab*
