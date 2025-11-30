# 📘 راهنمای تفصیلی: `dashboard.php`

## 🎯 خلاصه‌ی کلی

**Dashboard** نقطه ورود اصلی پروژه است. این فایل یک **صفحه ناوبری مرکزی** فراهم می‌کند که:
- ✅ تمام 9 دمو را لیست می‌کند
- ✅ توضیح کوتاه برای هر دمو
- ✅ سطح سختی (Basic, Intermediate, Advanced)
- ✅ دکمه برای اجرای همه دموها
- ✅ **NEW!** دکمه 🔄 Reset All برای ریست کردن تمام داده‌ها

---

## 📋 ساختار فایل

```
dashboard.php
├── Hero Section (معرفی)
├── Statistics Bar (آمار)
├── Demo Cards Grid (شبکه دموها)
│   ├── 01_get_post.php
│   ├── 02_validation.php
│   ├── 03_xss_demo.php
│   ├── 04_live_chat_xss.php
│   ├── 05_sql_injection.php
│   ├── 06_csrf_demo.php
│   ├── 07_file_upload.php
│   ├── final/register.php
│   └── slides.html
├── Launch All Button
└── Footer
```

---

## 🔍 تحلیل کد - بخش به بخش

### بخش 1: Hero Section

```html
<div class="hero animate-fade">
    <div data-text="CYBER SECURITY LAB" class="glitch" style="font-size: 3.5rem; color: var(--primary-color); margin-bottom: 1rem;">
        🔐 CYBER SECURITY LAB
    </div>
    <p class="neon-text">Welcome to the Ultimate Web Security Demonstration</p>
    <p style="margin-top: 0.5rem;">by <strong>Amin Davodian</strong></p>
</div>
```

**توضیح:**
- `class="hero"`: بخش معرفی
- `class="glitch"`: افکت glitch (نوسان متن)
- `class="neon-text"`: متن درخشان
- `animate-fade`: انیمیشن ظاهر شدن

---

### بخش 2: Statistics Bar

```html
<div class="stats-bar animate-slide-top">
    <div class="stat-item">
        <div class="stat-number">9</div>
        <div class="stat-label">Labs Available</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">15+</div>
        <div class="stat-label">Attack Vectors</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">100%</div>
        <div class="stat-label">Educational</div>
    </div>
</div>
```

**توضیح:**
- نمایش آمار پروژه
- 9 لب آموزشی
- 15+ بردار حمله
- 100% آموزشی

---

### بخش 3: Demo Cards

```html
<a href="01_get_post.php" class="demo-card animate-slide-left" style="text-decoration: none; color: inherit;">
    <span class="demo-icon">📡</span>
    <h3 class="demo-title">GET vs POST</h3>
    <p class="demo-description">
        مقایسه متدهای HTTP و رهگیری درخواست‌ها
    </p>
    <span class="demo-level level-basic">Basic</span>
</a>
```

**توضیح:**

#### الف) لینک
```html
<a href="01_get_post.php" class="demo-card">
```
- کارت یک لینک است

#### ب) آیکون
```html
<span class="demo-icon">📡</span>
```
- Emoji برای نمایش بصری

#### ج) عنوان و توضیح
```html
<h3 class="demo-title">GET vs POST</h3>
<p class="demo-description">مقایسه متدهای HTTP...</p>
```

#### د) سطح سختی
```html
<span class="demo-level level-basic">Basic</span>
```
- `level-basic`: سبز (ساده)
- `level-intermediate`: نارنجی (متوسط)
- `level-advanced`: قرمز (پیشرفته)

---

### بخش 4: تمام دموها

| شماره | عنوان | آیکون | سطح | لینک |
|------|------|------|------|------|
| 1 | GET vs POST | 📡 | Basic | 01_get_post.php |
| 2 | Input Validation | ✅ | Basic | 02_validation.php |
| 3 | XSS Attack | 💉 | Intermediate | 03_xss_demo.php |
| 4 | Live Chat XSS | 💬 | Intermediate | 04_live_chat_xss.php |
| 5 | SQL Injection | 🗄️ | Advanced | 05_sql_injection.php |
| 6 | CSRF Attack | 🎯 | Advanced | 06_csrf_demo.php |
| 7 | File Upload | 📤 | Advanced | 07_file_upload.php |
| 8 | Final Project | 🏆 | Final | final/register.php |
| 9 | Slides | 🎬 | Presentation | slides.html |

---

### بخش 5: Launch All Button

```html
<div class="launch-all">
    <button onclick="launchAllDemos()" class="launch-btn cyber-button">
        🚀 LAUNCH ALL DEMOS
    </button>
</div>
```

**توضیح:**
- دکمه برای اجرای همه دموها
- هر دمو در تب جدید باز می‌شود

---

### بخش 6: JavaScript - launchAllDemos()

```javascript
function launchAllDemos() {
    const demos = [
        '01_get_post.php',
        '02_validation.php',
        '03_xss_demo.php',
        '04_live_chat_xss.php',
        '05_sql_injection.php',
        '06_csrf_demo.php',
        '07_file_upload.php',
        'final/register.php',
        'slides.html'
    ];
    
    if (confirm('این عملیات ' + demos.length + ' تب جدید باز می‌کند.\n\nادامه می‌دهید؟')) {
        demos.forEach((demo, index) => {
            setTimeout(() => {
                window.open(demo, '_blank');
            }, index * 300); // Stagger the opening
        });
    }
}
```

**توضیح:**

#### الف) آرایه دموها
```javascript
const demos = [
    '01_get_post.php',
    '02_validation.php',
    // ...
];
```

#### ب) تایید کاربر
```javascript
if (confirm('این عملیات ' + demos.length + ' تب جدید باز می‌کند...'))
```
- پیام تایید

#### ج) باز کردن تب‌ها
```javascript
demos.forEach((demo, index) => {
    setTimeout(() => {
        window.open(demo, '_blank');
    }, index * 300);
});
```
- هر دمو در تب جدید
- تأخیر 300ms بین هر تب

---

### بخش 7: Footer

```html
<div style="text-align: center; margin-top: 4rem; padding: 2rem; border-top: 1px solid var(--border-color);">
    <p style="color: var(--text-muted);">
        Developed with ❤️ by <strong style="color: var(--primary-color);">Amin Davodian</strong>
    </p>
    <p style="margin-top: 0.5rem;">
        <a href="https://senioramin.com" target="_blank" style="color: var(--primary-color); text-decoration: none;">senioramin.com</a> | 
        <a href="https://github.com/SeniorAminam" target="_blank" style="color: var(--primary-color); text-decoration: none;">GitHub</a> | 
        <a href="https://linkedin.com/in/SudoAmin" target="_blank" style="color: var(--primary-color); text-decoration: none;">LinkedIn</a>
    </p>
</div>
```

**توضیح:**
- اطلاعات نویسنده
- لینک‌های مهم

---

## 🧪 نحوه استفاده

### مثال 1: باز کردن یک دمو

**مرحله 1:** Dashboard را باز کنید
```
http://localhost:8000/dashboard.php
```

**مرحله 2:** روی کارت "GET vs POST" کلیک کنید

**خروجی:**
```
صفحه 01_get_post.php باز می‌شود
```

---

### مثال 2: اجرای همه دموها

**مرحله 1:** دکمه "LAUNCH ALL DEMOS" را کلیک کنید

**مرحله 2:** تایید کنید

**خروجی:**
```
9 تب جدید باز می‌شود:
- 01_get_post.php
- 02_validation.php
- 03_xss_demo.php
- 04_live_chat_xss.php
- 05_sql_injection.php
- 06_csrf_demo.php
- 07_file_upload.php
- final/register.php
- slides.html
```

---

## 🎨 CSS Styling

### Demo Card Hover Effect

```css
.demo-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.1), transparent);
    transition: left 0.5s;
}

.demo-card:hover::before {
    left: 100%;
}
```

**توضیح:**
- `::before`: pseudo-element
- Gradient از چپ به راست
- `hover`: هنگام ماوس روی کارت

---

### Level Badges

```css
.level-basic { background: rgba(16, 185, 129, 0.2); color: var(--primary-color); }
.level-intermediate { background: rgba(245, 158, 11, 0.2); color: var(--warning-color); }
.level-advanced { background: rgba(239, 68, 68, 0.2); color: var(--error-color); }
```

**توضیح:**
- سبز برای Basic
- نارنجی برای Intermediate
- قرمز برای Advanced

---

## 📊 جدول: سطح‌های سختی

| سطح | رنگ | مثال | تعداد |
|------|------|------|------|
| **Basic** | سبز | GET/POST, Validation | 2 |
| **Intermediate** | نارنجی | XSS, Live Chat | 2 |
| **Advanced** | قرمز | SQL, CSRF, Upload | 3 |
| **Final** | آبی | Registration | 1 |
| **Presentation** | بنفش | Slides | 1 |

---

## 🎓 درس‌های یادگیری

### درس 1: Navigation Hub
- Dashboard نقطه ورود است
- تمام دموها از اینجا قابل دسترسی

### درس 2: User Experience
- کارت‌های بصری
- توضیح کوتاه
- سطح سختی

### درس 3: Progressive Learning
- Basic → Intermediate → Advanced
- هر سطح بر پایه قبلی

---

## 🔗 ارتباط با فایل‌های دیگر

- **شامل:** تمام 9 دمو
- **مرتبط:** assets/style.css، assets/terminal.js
- **نقطه ورود:** http://localhost:8000/dashboard.php

---

## 📝 خلاصه

**`dashboard.php`** صفحه ناوبری مرکزی است که:
- ✅ تمام دموها را لیست می‌کند
- ✅ توضیح و سطح سختی را نشان می‌دهد
- ✅ دکمه برای اجرای همه دموها
- ✅ طراحی جذاب و تعاملی

**نویسنده:** Amin Davodian  
**وبسایت:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

*Developed by Amin Davodian - Web Security Lab*
