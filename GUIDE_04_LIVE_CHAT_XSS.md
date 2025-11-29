# 📘 راهنمای تفصیلی: `04_live_chat_xss.php`

## 🎯 خلاصه‌ی کلی

این فایل **XSS ماندگار (Persistent XSS)** را نشان می‌دهد - یکی از خطرناک‌ترین انواع XSS.

تفاوت با فایل قبلی:
- **Reflected XSS** (فایل 3): داده در URL/فرم → فقط کاربر فعلی متأثر
- **Persistent XSS** (این فایل): داده در دیتابیس → **تمام کاربران** متأثر

**مثال واقعی:**
- هکر یک پیام مخرب در چت می‌فرستد
- پیام در فایل ذخیره می‌شود
- هر کاربری که چت را باز کند، حمله را می‌بیند
- 💥 **خطرناک!**

---

## 📋 ساختار فایل

```
04_live_chat_xss.php
├── PHP Logic
│   ├── فایل ذخیره‌سازی (chat_data.txt)
│   ├── Reset functionality
│   ├── دریافت پیام‌ها
│   └── ذخیره‌سازی JSON
├── HTML Structure
│   ├── Chat Box (نمایشگر)
│   ├── Security Toggle (ناامن/امن)
│   └── Attacker Console (کنسول هکر)
└── JavaScript Functions
    └── fillChat()
```

---

## 🔍 تحلیل کد - بخش به بخش

### بخش 1: تنظیمات فایل

```php
$file = 'chat_data.txt';
if (!file_exists($file)) {
    file_put_contents($file, '');
}
```

**توضیح:**
- `$file = 'chat_data.txt'`: نام فایل ذخیره‌سازی
- `file_exists($file)`: بررسی وجود فایل
- `file_put_contents($file, '')`: ایجاد فایل خالی اگر وجود نداشت

**نکته:** این فایل **دیتابیس نیست**، فقط یک فایل متنی است

---

### بخش 2: Reset Functionality

```php
// Reset Chat functionality
if (isset($_GET['reset'])) {
    file_put_contents($file, '');
    header("Location: 04_live_chat_xss.php");
    exit;
}
```

**توضیح:**
- `isset($_GET['reset'])`: بررسی پارامتر `reset` در URL
- `file_put_contents($file, '')`: پاک کردن محتوای فایل
- `header("Location: ...")`: ریدایرکت به صفحه اصلی
- `exit`: توقف اجرای کد

**استفاده:**
```
http://localhost:8000/04_live_chat_xss.php?reset=1
```

---

### بخش 3: دریافت و ذخیره‌سازی پیام

```php
// Handle incoming messages - Developed by Amin Davodian
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? 'Anonymous';
    $msg = $_POST['msg'] ?? '';
    
    // Store message (simulating database storage)
    $entry = json_encode([
        'user' => $user,
        'msg' => $msg,
        'time' => date('H:i:s')
    ]) . "\n";
    file_put_contents($file, $entry, FILE_APPEND);
}
```

**توضیح:**

#### الف) بررسی POST
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
```
- بررسی می‌کند که آیا فرم ارسال شده است

#### ب) دریافت داده‌ها
```php
$user = $_POST['user'] ?? 'Anonymous';
$msg = $_POST['msg'] ?? '';
```
- اگر نام کاربری خالی بود، `'Anonymous'` استفاده می‌شود
- اگر پیام خالی بود، رشته خالی استفاده می‌شود

#### ج) ایجاد JSON
```php
$entry = json_encode([
    'user' => $user,
    'msg' => $msg,
    'time' => date('H:i:s')
]) . "\n";
```

**مثال:**
```json
{"user":"علی","msg":"سلام","time":"14:30:45"}
```

#### د) ذخیره‌سازی
```php
file_put_contents($file, $entry, FILE_APPEND);
```
- `FILE_APPEND`: اضافه کردن به انتهای فایل (نه جایگزینی)

---

### بخش 4: خواندن پیام‌ها

```php
$messages = file($file);
```

**توضیح:**
- `file($file)`: خواندن فایل و تبدیل به آرایه
- هر خط یک پیام است

**مثال:**
```php
$messages = [
    '{"user":"علی","msg":"سلام","time":"14:30:45"}\n',
    '{"user":"محمد","msg":"سلام!","time":"14:30:50"}\n'
]
```

---

### بخش 5: نمایش Chat Box

```php
<div class="chat-box" id="chatBox">
    <?php 
    foreach ($messages as $line): 
        $data = json_decode($line, true);
        if (!$data) continue;
        
        // Security mode toggle
        $secure_view = isset($_GET['secure']) && $_GET['secure'] == 1;
        $display_msg = $secure_view ? htmlspecialchars($data['msg']) : $data['msg'];
    ?>
        <div class="msg">
            <span class="msg-time"><?php echo htmlspecialchars($data['time']); ?></span>
            <span class="msg-user"><?php echo htmlspecialchars($data['user']); ?>:</span>
            <span><?php echo $display_msg; ?></span>
        </div>
    <?php endforeach; ?>
</div>
```

**توضیح:**

#### الف) حلقه بر روی پیام‌ها
```php
foreach ($messages as $line)
```
- هر خط یک پیام است

#### ب) تبدیل JSON
```php
$data = json_decode($line, true);
if (!$data) continue;
```
- تبدیل JSON به آرایه
- اگر JSON نامعتبر بود، ادامه دهید

#### ج) Security Toggle
```php
$secure_view = isset($_GET['secure']) && $_GET['secure'] == 1;
$display_msg = $secure_view ? htmlspecialchars($data['msg']) : $data['msg'];
```

**منطق:**
- اگر `?secure=1` در URL بود: escape کن
- اگر `?secure=0` در URL بود: escape نکن

#### د) نمایش پیام
```php
<span class="msg-time"><?php echo htmlspecialchars($data['time']); ?></span>
<span class="msg-user"><?php echo htmlspecialchars($data['user']); ?>:</span>
<span><?php echo $display_msg; ?></span>
```

**نکته:** زمان و نام کاربری **همیشه** escape می‌شوند، اما پیام بستگی به `$secure_view` دارد

---

### بخش 6: Security Toggle Buttons

```html
<div style="text-align: center;">
    <a href="?secure=0" class="badge" style="background:var(--error-color); color:white; text-decoration:none;">حالت ناامن (Vulnerable)</a>
    <a href="?secure=1" class="badge" style="background:var(--primary-color); color:black; text-decoration:none;">حالت امن (Secure)</a>
</div>
```

**توضیح:**
- دو دکمه برای تبدیل بین حالت‌های ناامن و امن
- قرمز برای ناامن
- آبی برای امن

---

### بخش 7: Attacker Console

```html
<div class="card" style="border-color: var(--secondary-color);">
    <h2 style="color: var(--secondary-color);">💀 کنسول هکر</h2>
    <form action="" method="POST" id="chatForm">
        <div class="form-group">
            <label>نام کاربری:</label>
            <input type="text" name="user" id="chatUser" placeholder="Hacker101">
        </div>
        <div class="form-group">
            <label>پیام (Payload):</label>
            <textarea name="msg" id="chatMsg" rows="4" placeholder="<script>alert('Hacked!');</script>"></textarea>
        </div>
        <button type="submit" style="border-color: var(--secondary-color); color: var(--secondary-color);">ارسال پیام</button>
    </form>
```

**توضیح:**
- فرم برای ارسال پیام‌های مخرب
- `textarea` برای پیام‌های طولانی
- Placeholder نشان می‌دهد که چه چیزی می‌تواند ارسال شود

---

### بخش 8: Payload Examples

```html
<div class="examples-container" style="margin-top: 2rem;">
    <span class="examples-title">👇 Payloadهای آماده (کلیک کنید):</span>
    <button class="example-btn attack" onclick="fillChat('<script>alert(document.cookie)</script>')">سرقت کوکی</button>
    <button class="example-btn attack" onclick="fillChat('<style>body{background:red;}</style>')">تغییر ظاهر (CSS)</button>
    <button class="example-btn attack" onclick="fillChat('<img src=x onerror=alert(\'XSS\')>')">تزریق تصویر</button>
    <button class="example-btn safe" onclick="fillChat('سلام دوستان! چطورید؟')">پیام معمولی</button>
</div>
```

**توضیح:**

#### الف) سرقت کوکی
```javascript
<script>alert(document.cookie)</script>
```
- `document.cookie`: کوکی‌های کاربر
- در واقع، می‌تواند کوکی‌ها را به سرور هکر بفرستد

#### ب) تغییر ظاهر
```javascript
<style>body{background:red;}</style>
```
- تغییر رنگ پس‌زمینه
- می‌تواند صفحه را کاملاً تغییر دهد

#### ج) تزریق تصویر
```javascript
<img src=x onerror=alert('XSS')>
```
- تصویر نامعتبر
- `onerror` event اجرا می‌شود

#### د) پیام معمولی
```javascript
سلام دوستان! چطورید؟
```
- پیام عادی برای تست

---

### بخش 9: JavaScript - fillChat()

```javascript
function fillChat(payload) {
    const msgInput = document.getElementById('chatMsg');
    const userInput = document.getElementById('chatUser');
    
    if(!userInput.value) userInput.value = 'Hacker_' + Math.floor(Math.random() * 100);
    
    msgInput.value = payload;
    msgInput.style.backgroundColor = '#fffbeb';
    setTimeout(() => msgInput.style.backgroundColor = '', 500);
    
    if(window.logger) {
        window.logger.log('Interaction', `Prepared Chat Payload: ${payload}`, 'warning');
    }
}
```

**توضیح:**
- `fillChat()`: textarea را پر می‌کند
- اگر نام کاربری خالی بود، نام تصادفی ایجاد می‌کند
- رنگ زرد برای نشان دادن تغییر
- ثبت تعاملات در کنسول

---

## 🧪 نحوه استفاده - مثال‌های عملی

### مثال 1: پیام معمولی

**مرحله 1:** روی دکمه "پیام معمولی" کلیک کنید
```
نام کاربری: علی
پیام: سلام دوستان! چطورید؟
```

**مرحله 2:** دکمه "ارسال پیام" را کلیک کنید

**خروجی (حالت ناامن - ?secure=0):**
```
علی: سلام دوستان! چطورید؟
```

**خروجی (حالت امن - ?secure=1):**
```
علی: سلام دوستان! چطورید؟
```

**نتیجه:** هر دو حالت یکسان است

---

### مثال 2: XSS ماندگار - سرقت کوکی

**مرحله 1:** روی دکمه "سرقت کوکی" کلیک کنید
```
نام کاربری: Hacker_42
پیام: <script>alert(document.cookie)</script>
```

**مرحله 2:** دکمه "ارسال پیام" را کلیک کنید

**خروجی (حالت ناامن - ?secure=0):**
```
[پنجره Alert ظاهر می‌شود!]
alert: "PHPSESSID=abc123..."
```

**توضیح:**
- اسکریپت اجرا می‌شود
- کوکی‌ها نمایش داده می‌شوند
- **خطرناک!**

**مهم:** اگر صفحه را refresh کنید، اسکریپت **دوباره** اجرا می‌شود!

---

### مثال 3: XSS ماندگار - حالت امن

**مرحله 1:** URL را تغییر دهید
```
?secure=1
```

**مرحله 2:** صفحه را refresh کنید

**خروجی (حالت امن - ?secure=1):**
```
Hacker_42: <script>alert(document.cookie)</script>
```

**توضیح:**
- اسکریپت اجرا **نمی‌شود**
- کد به صورت **متن** نمایش داده می‌شود
- **محفوظ!**

---

### مثال 4: تغییر ظاهر

**مرحله 1:** روی دکمه "تغییر ظاهر (CSS)" کلیک کنید
```
نام کاربری: Hacker_99
پیام: <style>body{background:red;}</style>
```

**مرحله 2:** دکمه "ارسال پیام" را کلیک کنید

**خروجی (حالت ناامن):**
```
[پس‌زمینه صفحه قرمز می‌شود!]
```

**توضیح:**
- CSS اجرا می‌شود
- صفحه تغییر می‌کند
- **خطرناک!**

---

## 🔐 نکات امنیتی

### ✅ نقاط قوت این کد

1. **Security Toggle**
   ```php
   $secure_view = isset($_GET['secure']) && $_GET['secure'] == 1;
   $display_msg = $secure_view ? htmlspecialchars($data['msg']) : $data['msg'];
   ```
   - کاربر می‌تواند بین ناامن و امن انتخاب کند

2. **Reset Functionality**
   ```php
   if (isset($_GET['reset'])) {
       file_put_contents($file, '');
   }
   ```
   - کاربر می‌تواند چت را پاک کند

3. **JSON Storage**
   ```php
   $entry = json_encode([...]) . "\n";
   ```
   - ساختار داده‌ها منظم است

### ⚠️ نقاط ضعف (برای آموزش)

1. **بدون دیتابیس**
   - فایل متنی استفاده می‌شود
   - در تولید، دیتابیس استفاده شود

2. **بدون Authentication**
   - هر کسی می‌تواند هر نام کاربری را استفاده کند

3. **بدون Rate Limiting**
   - کاربر می‌تواند بی‌نهایت پیام ارسال کند

4. **بدون Moderation**
   - هیچ تایید یا فیلتری نیست

---

## 📊 جدول: Reflected vs Persistent XSS

| ویژگی | Reflected | Persistent |
|------|-----------|-----------|
| **جایگاه داده** | URL/فرم | دیتابیس/فایل |
| **تأثیر** | کاربر فعلی | تمام کاربران |
| **دوام** | یک‌بار | دائمی |
| **خطر** | ⚠️ متوسط | ⚠️⚠️ بسیار بالا |
| **مثال** | جستجو | نظر، چت |

---

## 🎓 درس‌های یادگیری

### درس 1: Persistent XSS
- داده در دیتابیس ذخیره می‌شود
- تمام کاربران متأثر می‌شوند
- خطرناک‌تر از Reflected XSS

### درس 2: File Storage
- فایل‌های متنی برای ذخیره‌سازی ساده
- JSON برای ساختار داده‌ها
- در تولید، دیتابیس استفاده شود

### درس 3: Security Toggle
- نمایش تفاوت ناامن و امن
- کاربر می‌تواند انتخاب کند

### درس 4: Defense
- **همیشه** داده‌ها را escape کنید
- Content Security Policy (CSP)
- Input validation و sanitization

---

## 🔗 ارتباط با فایل‌های دیگر

- **قبلی:** `03_xss_demo.php` - Reflected XSS
- **بعدی:** `05_sql_injection.php` - SQL Injection
- **مرتبط:** `final/register.php` - فرم امن

---

## 📝 خلاصه

**`04_live_chat_xss.php`** یک دمو تعاملی است که:
- ✅ Persistent XSS را نشان می‌دهد
- ✅ فایل ذخیره‌سازی و JSON را استفاده می‌کند
- ✅ Security toggle برای مقایسه
- ✅ Payload‌های آماده برای تست

**نویسنده:** Amin Davodian  
**وبسایت:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

*Developed by Amin Davodian - Web Security Lab*
