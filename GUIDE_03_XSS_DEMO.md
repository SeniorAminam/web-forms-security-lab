# 📘 راهنمای تفصیلی: `03_xss_demo.php`

## 🎯 خلاصه‌ی کلی

این فایل یکی از **خطرناک‌ترین حملات وب** را نشان می‌دهد: **XSS (Cross-Site Scripting)**.

XSS یعنی اینکه هکر می‌تواند **کد JavaScript خطرناک** را در صفحه شما اجرا کند:
- 🍪 کوکی‌ها را سرقت کند
- 👤 اطلاعات کاربر را بگیرد
- 🔑 رمز عبور را بگیرد
- 💰 پول را انتقال دهد

این فایل **دو روش** را مقایسه می‌کند:
- ❌ **روش ناامن:** کد مستقیم چاپ می‌شود (خطرناک!)
- ✅ **روش امن:** کد escape می‌شود (محفوظ)

---

## 📋 ساختار فایل

```
03_xss_demo.php
├── Grid Layout (2 Cards)
│   ├── Vulnerable Card (ناامن)
│   │   ├── توضیح
│   │   ├── دکمه‌های مثال
│   │   ├── فرم
│   │   └── نمایش بدون فیلتر (خطرناک!)
│   └── Secure Card (امن)
│       ├── توضیح
│       ├── دکمه‌های مثال
│       ├── فرم
│       └── نمایش با htmlspecialchars()
└── JavaScript Functions
    ├── fillBad()
    └── fillGood()
```

---

## 🔍 تحلیل کد - بخش به بخش

### بخش 1: Header

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
 * XSS Security Demo - Demonstrates Cross-Site Scripting vulnerabilities and prevention
 * Developed by Amin Davodian
 */
?>
```

**توضیح:**
- نام پروژه: Web Design Course Presentation - Hacker Edition
- هدف: نمایش حملات XSS و دفاع

---

### بخش 2: Vulnerable Card (ناامن)

```html
<div class="card" style="border-top: 4px solid var(--error-color);">
    <h2>❌ روش ناامن (Vulnerable)</h2>
    <p>هر چیزی بنویسید، مستقیم چاپ می‌شود.</p>
```

**توضیح:**
- قرمز رنگ برای نشان دادن خطر
- ❌ نشانه‌ی ناامن بودن

---

### بخش 3: دکمه‌های مثال - Vulnerable

```html
<div class="examples-container">
    <span class="examples-title">👇 نمونه‌های قابل کلیک:</span>
    <button class="example-btn safe" onclick="fillBad('سلام دنیا!')">متن ساده</button>
    <button class="example-btn attack" onclick="fillBad('<script>alert(1)</script>')">اسکریپت Alert</button>
    <button class="example-btn attack" onclick="fillBad('<img src=x onerror=alert(1)>')">تصویر مخرب</button>
</div>
```

**توضیح:**

#### الف) دکمه 1: متن ساده
```javascript
fillBad('سلام دنیا!')
```
- متن عادی
- هیچ خطری ندارد

#### ب) دکمه 2: اسکریپت Alert
```javascript
fillBad('<script>alert(1)</script>')
```
- کد JavaScript
- اگر escape نشود، اسکریپت اجرا می‌شود
- `alert(1)` یک پنجره هشدار نمایش می‌دهد

#### ج) دکمه 3: تصویر مخرب
```javascript
fillBad('<img src=x onerror=alert(1)>')
```
- تصویر نامعتبر
- `onerror` event زمانی اجرا می‌شود که تصویر بارگذاری نشود
- اسکریپت اجرا می‌شود

---

### بخش 4: فرم Vulnerable

```html
<form action="" method="POST" id="badForm">
    <div class="form-group">
        <label>پیام شما:</label>
        <input type="text" name="msg_bad" id="inputBad" placeholder="<script>alert('Hacked!')</script>">
    </div>
    <button type="submit" style="background-color: var(--error-color);">ارسال خطرناک</button>
</form>
```

**توضیح:**
- `name="msg_bad"`: نام ورودی
- `placeholder`: نمونه‌ی کد XSS
- دکمه قرمز برای نشان دادن خطر

---

### بخش 5: نمایش بدون فیلتر (خطرناک!)

```php
<?php if (isset($_POST['msg_bad'])): ?>
    <div class="alert alert-error" style="margin-top: 1rem;">
        <strong>نتیجه (بدون فیلتر):</strong><br>
        <?php 
        // VULNERABLE - For demonstration only! Never do this in production!
        echo $_POST['msg_bad']; 
        ?>
    </div>
<?php endif; ?>
```

**توضیح:**

#### الف) بررسی POST
```php
if (isset($_POST['msg_bad']))
```
- بررسی می‌کند که آیا فرم ارسال شده است

#### ب) نمایش بدون فیلتر
```php
echo $_POST['msg_bad'];
```
- **خطرناک!** داده‌ها مستقیم چاپ می‌شوند
- اگر `<script>alert(1)</script>` ارسال شود، اسکریپت اجرا می‌شود

#### ج) نظر هشدار
```php
// VULNERABLE - For demonstration only! Never do this in production!
```
- نظر برای نشان دادن خطر
- این روش **هرگز** در تولید استفاده نشود

---

### بخش 6: Secure Card (امن)

```html
<div class="card" style="border-top: 4px solid var(--success-color);">
    <h2>✅ روش امن (Secure)</h2>
    <p>با استفاده از <code>htmlspecialchars()</code></p>
```

**توضیح:**
- سبز رنگ برای نشان دادن امنیت
- ✅ نشانه‌ی امن بودن
- نام تابع: `htmlspecialchars()`

---

### بخش 7: دکمه‌های مثال - Secure

```html
<div class="examples-container">
    <span class="examples-title">👇 نمونه‌های قابل کلیک:</span>
    <button class="example-btn safe" onclick="fillGood('سلام دنیا!')">متن ساده</button>
    <button class="example-btn attack" onclick="fillGood('<script>alert(1)</script>')">تست حمله</button>
</div>
```

**توضیح:**
- دو دکمه برای تست
- دکمه دوم: همان کد XSS، اما امن‌تر

---

### بخش 8: فرم Secure

```html
<form action="" method="POST" id="goodForm">
    <div class="form-group">
        <label>پیام شما:</label>
        <input type="text" name="msg_good" id="inputGood" placeholder="<script>alert('Safe')</script>">
    </div>
    <button type="submit" style="background-color: var(--success-color);">ارسال امن</button>
</form>
```

**توضیح:**
- `name="msg_good"`: نام ورودی
- دکمه سبز برای نشان دادن امنیت

---

### بخش 9: نمایش با Escape (امن)

```php
<?php if (isset($_POST['msg_good'])): ?>
    <div class="alert alert-success" style="margin-top: 1rem;">
        <strong>نتیجه (ایمن شده):</strong><br>
        <?php echo htmlspecialchars($_POST['msg_good'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <pre>Code: htmlspecialchars($input);</pre>
<?php endif; ?>
```

**توضیح:**

#### الف) استفاده از `htmlspecialchars()`
```php
echo htmlspecialchars($_POST['msg_good'], ENT_QUOTES, 'UTF-8');
```

#### ب) پارامترها
- `$_POST['msg_good']`: داده ورودی
- `ENT_QUOTES`: escape کردن هم `"` و هم `'`
- `'UTF-8'`: کدگذاری فارسی

#### ج) نمایش کد
```php
<pre>Code: htmlspecialchars($input);</pre>
```
- نمایش کد استفاده شده

---

### بخش 10: htmlspecialchars() - چگونه کار می‌کند؟

```
ورودی:  <script>alert(1)</script>
خروجی: &lt;script&gt;alert(1)&lt;/script&gt;

ورودی:  <img src=x onerror=alert(1)>
خروجی: &lt;img src=x onerror=alert(1)&gt;
```

**توضیح:**
- `<` → `&lt;` (less than)
- `>` → `&gt;` (greater than)
- `"` → `&quot;` (quote)
- `'` → `&#039;` (apostrophe)

**نتیجه:**
- کد HTML/JavaScript تبدیل می‌شود
- مرورگر آن را **متن** می‌بیند، نه **کد**
- اسکریپت اجرا نمی‌شود

---

### بخش 11: JavaScript Functions

```javascript
function fillBad(value) {
    const input = document.getElementById('inputBad');
    input.value = value;
    input.style.backgroundColor = '#fffbeb';
    setTimeout(() => input.style.backgroundColor = '', 500);
    
    if(window.logger) {
        window.logger.log('Interaction', `Filled Vulnerable Input: ${value}`, 'warning');
    }
}

function fillGood(value) {
    const input = document.getElementById('inputGood');
    input.value = value;
    input.style.backgroundColor = '#fffbeb';
    setTimeout(() => input.style.backgroundColor = '', 500);
    
    if(window.logger) {
        window.logger.log('Interaction', `Filled Secure Input: ${value}`, 'success');
    }
}
```

**توضیح:**
- `fillBad()`: دکمه‌های مثال را در فرم ناامن پر می‌کند
- `fillGood()`: دکمه‌های مثال را در فرم امن پر می‌کند
- رنگ زرد برای نشان دادن تغییر
- ثبت تعاملات در کنسول

---

## 🧪 نحوه استفاده - مثال‌های عملی

### مثال 1: متن ساده

**مرحله 1:** روی دکمه "متن ساده" در بخش ناامن کلیک کنید
```
ورودی: سلام دنیا!
```

**مرحله 2:** دکمه "ارسال خطرناک" را کلیک کنید

**خروجی (ناامن):**
```
نتیجه (بدون فیلتر):
سلام دنیا!
```

**مرحله 3:** روی دکمه "متن ساده" در بخش امن کلیک کنید

**مرحله 4:** دکمه "ارسال امن" را کلیک کنید

**خروجی (امن):**
```
نتیجه (ایمن شده):
سلام دنیا!

Code: htmlspecialchars($input);
```

**نتیجه:** هر دو روش متن ساده را صحیح نمایش می‌دهند

---

### مثال 2: XSS Alert

**مرحله 1:** روی دکمه "اسکریپت Alert" در بخش ناامن کلیک کنید
```
ورودی: <script>alert(1)</script>
```

**مرحله 2:** دکمه "ارسال خطرناک" را کلیک کنید

**خروجی (ناامن - خطرناک!):**
```
نتیجه (بدون فیلتر):
[پنجره Alert ظاهر می‌شود!]
```

**توضیح:**
- اسکریپت اجرا می‌شود
- `alert(1)` یک پنجره هشدار نمایش می‌دهد
- **این خطرناک است!**

---

### مثال 3: XSS Alert - روش امن

**مرحله 1:** روی دکمه "تست حمله" در بخش امن کلیک کنید
```
ورودی: <script>alert(1)</script>
```

**مرحله 2:** دکمه "ارسال امن" را کلیک کنید

**خروجی (امن):**
```
نتیجه (ایمن شده):
<script>alert(1)</script>

Code: htmlspecialchars($input);
```

**توضیح:**
- اسکریپت اجرا **نمی‌شود**
- کد به صورت **متن** نمایش داده می‌شود
- `<` و `>` به `&lt;` و `&gt;` تبدیل شدند

---

### مثال 4: XSS تصویر

**مرحله 1:** روی دکمه "تصویر مخرب" در بخش ناامن کلیک کنید
```
ورودی: <img src=x onerror=alert(1)>
```

**مرحله 2:** دکمه "ارسال خطرناک" را کلیک کنید

**خروجی (ناامن - خطرناک!):**
```
نتیجه (بدون فیلتر):
[پنجره Alert ظاهر می‌شود!]
```

**توضیح:**
- تصویر نامعتبر (`src=x`)
- `onerror` event اجرا می‌شود
- اسکریپت اجرا می‌شود

---

## 🔐 نکات امنیتی

### ✅ نقاط قوت این کد

1. **مقایسه Side-by-Side**
   - ناامن و امن در کنار هم
   - تفاوت واضح است

2. **استفاده از `htmlspecialchars()`**
   ```php
   echo htmlspecialchars($_POST['msg_good'], ENT_QUOTES, 'UTF-8');
   ```
   - تمام کاراکترهای خطرناک escape می‌شوند

3. **نمایش کد**
   ```php
   <pre>Code: htmlspecialchars($input);</pre>
   ```
   - کاربر می‌داند چه کد استفاده شده

4. **رنگ‌های مختلف**
   - قرمز برای ناامن
   - سبز برای امن

### ⚠️ نقاط ضعف (برای آموزش)

1. **روش ناامن در تولید**
   - این کد **هرگز** در تولید استفاده نشود
   - فقط برای آموزش است

2. **بدون Content Security Policy (CSP)**
   - CSP می‌تواند XSS را مسدود کند

3. **بدون Input Sanitization**
   - فقط escape می‌کند، sanitize نمی‌کند

---

## 📊 جدول XSS Payloads

| Payload | نوع | خطر | Escape شده |
|---------|------|------|-----------|
| `<script>alert(1)</script>` | Script | ⚠️ بالا | `&lt;script&gt;...` |
| `<img src=x onerror=alert(1)>` | Event | ⚠️ بالا | `&lt;img ...&gt;` |
| `<style>body{background:red;}</style>` | CSS | ⚠️ متوسط | `&lt;style&gt;...` |
| `<b>Bold</b>` | HTML | ✅ کم | `&lt;b&gt;...` |

---

## 🎓 درس‌های یادگیری

### درس 1: XSS چیست؟
- حمله‌ای که هکر کد JavaScript را اجرا می‌کند
- می‌تواند کوکی‌ها و اطلاعات را سرقت کند

### درس 2: انواع XSS
- **Reflected:** داده در URL یا فرم
- **Persistent:** داده در دیتابیس
- **DOM-based:** مشکل در JavaScript

### درس 3: دفاع
- `htmlspecialchars()` برای output
- `filter_var()` برای input
- Content Security Policy (CSP)

### درس 4: Best Practices
- **همیشه** داده‌ها را escape کنید
- Validation سمت سرور
- HTTPS استفاده کنید

---

## 🔗 ارتباط با فایل‌های دیگر

- **قبلی:** `02_validation.php` - Validation
- **بعدی:** `04_live_chat_xss.php` - Persistent XSS
- **مرتبط:** `final/register.php` - فرم امن

---

## 📝 خلاصه

**`03_xss_demo.php`** یک دمو تعاملی است که:
- ✅ حمله XSS را نشان می‌دهد
- ✅ روش ناامن و امن را مقایسه می‌کند
- ✅ `htmlspecialchars()` را توضیح می‌دهد
- ✅ مثال‌های عملی برای تست فراهم می‌کند

**نویسنده:** Amin Davodian  
**وبسایت:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

*Developed by Amin Davodian - Web Security Lab*
