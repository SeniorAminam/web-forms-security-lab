# 🔐 Web Security Lab - Hacker Edition

Interactive security demonstration project showcasing web vulnerabilities and defense techniques with a premium Cyberpunk/Hacker theme.

**راهنمای کوتاه (فارسی):**  
این پروژه یک لَب آموزشی امنیت وب است که برای ارائه‌های دانشگاهی و تمرین شخصی طراحی شده.  
با استفاده از آن می‌توانید روی لوکال‌هاست سناریوهای حمله را تست کنید، نسخه‌های آسیب‌پذیر را ببینید و بلافاصله نسخه‌های امن را هم مقایسه کنید.  
اسکریپت‌های ارائه (فارسی/انگلیسی) و همه‌ی دموها داخل همین مخزن قرار داده شده‌اند.

## ✨ Features

### 🎯 **9 Interactive Demonstrations**
1. **GET vs POST** - HTTP method comparison with request interceptor
2. **Input Validation** - Server-side validation examples
3. **XSS Attack** - Cross-Site Scripting vulnerability demo
4. **Live Chat XSS** - Persistent XSS in chat system
5. **SQL Injection** - Database manipulation attacks (NEW! 💎)
6. **CSRF Attack** - Cross-Site Request Forgery with token protection (NEW! 💎)
7. **File Upload** - Webshell upload vulnerability (NEW! 💎)
8. **Final Exercise** - Complete secure registration system
9. **Presentation Slides** - Full-screen Matrix-themed slides

### 🎨 **Premium UI/UX**
- 🌟 Cyberpunk neon theme with glitch effects
- ✨ Matrix rain animation background
- 🎭 Smooth transitions and hover effects
- 💫 Interactive terminal emulator
- 📱 Fully responsive design
- 🎬 Professional presentation slides

### 🛡️ **Security Features**
- ✅ Vulnerable vs Secure mode comparison
- 📚 Ready-to-use attack payloads
- 🎓 Educational explanations
- 💡 Best practices documentation
- 🔒 Real-world defense techniques

## 🚀 Quick Start

### Installation

```bash
# Navigate to project directory
cd path/to/project

# Start PHP development server
php -S localhost:8000

# Open in browser
http://localhost:8000/dashboard.php
```

#### Alternative: Run with XAMPP (Windows)

If you prefer XAMPP on Windows, you can run the project like this:

1. Install XAMPP and start **Apache** from the XAMPP Control Panel.
2. Copy this project folder into your `htdocs` directory, for example:
   - `C:\xampp\htdocs\tsw`
3. Open the dashboard in your browser:
   - `http://localhost/tsw/dashboard.php`

> نکته (فارسی): اگر سر کلاس یا روی لپ‌تاپ شخصی کار می‌کنی، استفاده از XAMPP روی ویندوز هم کاملاً کافی و راحت است.

### Requirements
- PHP 7.4 or higher
- Modern web browser (Chrome, Firefox, Edge)
- No database required (uses file storage)

## 📚 Project Structure

```
Tsw/
├── dashboard.php            # 🎯 Main navigation hub
├── slides.html             # 🎬 Presentation slides
├── 01_get_post.php        # GET vs POST demo
├── 02_validation.php      # Input validation
├── 03_xss_demo.php        # XSS basics
├── 04_live_chat_xss.php   # Persistent XSS
├── 05_sql_injection.php   # SQL injection lab (NEW!)
├── 06_csrf_demo.php       # CSRF attack demo (NEW!)
├── 07_file_upload.php     # File upload vuln (NEW!)
├── assets/
│   ├── style.css          # Cyberpunk theme
│   ├── animations.css     # Advanced animations (NEW!)
│   ├── interceptor.js     # Request interceptor
│   ├── console-logger.js  # Console logger helper
│   └── terminal.js        # Interactive terminal (NEW!)
├── final/
│   ├── register.php       # Secure registration form
│   └── profile.php        # User profile page
├── PRESENTATION_SCRIPT.md  # Full 60min security talk script (FA/EN)
├── PRESENTATION_FORMS.md   # Forms/GET/POST/Validation/XSS script (FA/EN)
├── CONTRIBUTING.md         # Contribution guidelines
├── LICENSE                 # Project license
└── README.md
```

### 📁 File-by-file guide (FA/EN)

| Path | Description (EN) | توضیح کوتاه (فارسی) |
| --- | --- | --- |
| `dashboard.php` | Main dashboard to launch all security labs and demos. | داشبورد اصلی برای دسترسی به همه‌ی سناریوها و دموها. |
| `slides.html` | Full-screen HTML slides (Matrix-style) used during the talk. | اسلایدهای HTML برای استفاده در کلاس و ویدئوپروژکتور. |
| `01_get_post.php` | Demonstrates the difference between GET and POST, with simple request logging. | دمو تفاوت GET و POST و نمایش داده‌ها در URL و بدنه درخواست. |
| `02_validation.php` | Server-side input validation lab with success/error messages. | آزمایش اعتبارسنجی ورودی‌ها در سمت سرور با مثال‌های معتبر و نامعتبر. |
| `03_xss_demo.php` | Basic reflected XSS demo (vulnerable vs secure). | دمو ساده XSS بازتابی؛ نسخه ناامن در برابر نسخه امن با `htmlspecialchars()`. |
| `04_live_chat_xss.php` | Simulated live chat showing persistent XSS. | چت شبیه‌سازی‌شده برای نمایش XSS ماندگار (ذخیره‌شده در دیتابیس/فایل). |
| `05_sql_injection.php` | SQL injection lab with vulnerable and secure query examples. | سناریوی SQL Injection با نمایش کوئری ناامن و نسخه‌ی امن (Prepared Statement). |
| `06_csrf_demo.php` | CSRF attack demo with vulnerable and token-protected flows. | دمو CSRF با نسخه‌ی بدون توکن و نسخه‌ی دارای CSRF Token. |
| `07_file_upload.php` | File upload vulnerability lab with webshell example. | آزمایش آپلود فایل و خطر Webshell، به‌همراه نسخه‌ی امن با محدودیت نوع فایل. |
| `assets/style.css` | Main stylesheet with the cyberpunk/hacker theme. | استایل اصلی رابط کاربری با تم Cyberpunk / Hacker. |
| `assets/animations.css` | Extra animations (glitch, neon, transitions). | انیمیشن‌های اضافی برای افکت‌های تصویری و ترنزیشن‌ها. |
| `assets/interceptor.js` | JavaScript interceptor for visualizing outgoing requests. | اسکریپتی برای رهگیری و نمایش درخواست‌ها (برای دمو GET/POST و غیره). |
| `assets/console-logger.js` | Small helper for structured console logging. | ابزار کمکی برای لاگ‌ گرفتن مرتب در کنسول مرورگر. |
| `assets/terminal.js` | Interactive fake terminal used in the dashboard. | ترمینال تعاملی برای نمایش دستورها و لاگ‌ها در داشبورد. |
| `final/register.php` | Final secure registration form combining validation and security. | فرم ثبت‌نام نهایی برای جمع‌بندی مفاهیم GET/POST، Validation و XSS. |
| `final/profile.php` | Profile page that safely displays registered user data. | صفحه پروفایل که اطلاعات ثبت‌نام را به‌صورت امن نمایش می‌دهد. |
| `PRESENTATION_SCRIPT.md` | Main 60-minute security presentation script (EN overview + full FA script). | اسکریپت کامل ارائه‌ی ۶۰ دقیقه‌ای امنیت وب با توضیحات فارسی و مقدمه انگلیسی. |
| `PRESENTATION_FORMS.md` | Focused presentation script about forms, GET/POST, validation and XSS. | اسکریپت ارائه‌ی مخصوص فرم‌ها، GET/POST، اعتبارسنجی و XSS (برای درس طراحی وب). |
| `README.md` | Main project documentation (this file). | مستند اصلی پروژه و نقطه‌ی شروع خواننده. |
| `CONTRIBUTING.md` | Guidelines for contributing and running checks. | راهنما برای مشارکت در پروژه و اجرای تست‌ها. |
| `LICENSE` | License terms for reusing the code. | متن لایسنس پروژه (نوع مجوز استفاده از کد). |
| `.github/workflows/php-lint.yml` | GitHub Actions workflow for automatic PHP linting. | تنظیمات CI در گیت‌هاب برای اجرای خودکار PHP lint روی pull request ها. |

## 🎓 Usage Guide - مرحله‌ای از شروع تا انتها

### 📖 راهنمای مرحله‌ای کامل

**برای یک راهنمای بسیار تفصیلی و مرحله‌ای، لطفاً [`README_STEPBYSTEP.md`](README_STEPBYSTEP.md) را بخوانید.**

این فایل شامل:
- ✅ توضیح تفصیلی هر فایل
- ✅ مثال‌های عملی برای هر demo
- ✅ کد‌های PHP واقعی
- ✅ نقشه راه برای معلمان و دانشجویان
- ✅ لینک‌های مستقیم به هر فایل

---

## 🎓 Usage Guide - مرحله‌ای از شروع تا انتها (خلاصه)

### 📍 مرحله 0: شروع کار

**فایل:** `dashboard.php`

1. سرور را اجرا کنید: `php -S localhost:8000`
2. `http://localhost:8000/dashboard.php` را باز کنید
3. این صفحه مرکزی است و لینک تمام demos را دارد

---

### 📍 مرحله 1: فرم‌ها و GET/POST (25-35 دقیقه)

**راهنمای کامل:** [`PRESENTATION_FORMS.md`](PRESENTATION_FORMS.md)

#### 1.1 فایل اول: `01_get_post.php`
- **نقش:** تفاوت GET و POST را نشان می‌دهد
- **چه کاری کنید:** 
  - فرم GET: کلمه "لپ‌تاپ" بنویسید → URL تغییر می‌کند
  - فرم POST: رمز بنویسید → URL تغییر نمی‌کند
- **مفهوم:** GET در URL دیده می‌شود، POST مخفی است

#### 1.2 فایل دوم: `02_validation.php`
- **نقش:** Server-side validation را نشان می‌دهد
- **چه کاری کنید:**
  - نام خالی بگذارید → خطا
  - ایمیل نامعتبر بنویسید → خطا
  - همه را درست پر کنید → موفقیت
- **مفهوم:** Validation سمت سرور الزامی است

#### 1.3 فایل سوم: `03_xss_demo.php`
- **نقش:** XSS و جلوگیری را نشان می‌دهد
- **چه کاری کنید:**
  - فرم ناامن: `<script>alert('Hacked!')</script>` بنویسید → Alert ظاهر می‌شود
  - فرم امن: همان کد را بنویسید → فقط متن نمایش داده می‌شود
- **مفهوم:** `htmlspecialchars()` کاراکترهای خطرناک را تبدیل می‌کند

#### 1.4 فایل چهارم: `final/register.php` → `final/profile.php`
- **نقش:** فرم ثبت‌نام کامل (جمع مفاهیم قبلی)
- **چه کاری کنید:**
  1. فرم را اشتباه پر کنید → خطاهای Validation
  2. فرم را درست پر کنید → ریدایرکت به profile.php
  3. داده‌ها به‌صورت امن نمایش داده می‌شود
- **مفهوم:** Validation → ریدایرکت → نمایش امن

---

### 📍 مرحله 2: امنیت وب - 7 حمله (45-60 دقیقه)

**راهنمای کامل:** [`PRESENTATION_SCRIPT.md`](PRESENTATION_SCRIPT.md)

#### 2.1 فایل اول: `01_get_post.php` (دوباره)
- **حمله:** داده در URL دیده می‌شود
- **جلوگیری:** POST + HTTPS

#### 2.2 فایل دوم: `02_validation.php` (دوباره)
- **حمله:** بدون validation، کاربر هر چیزی می‌فرستد
- **جلوگیری:** Server-side validation

#### 2.3 فایل سوم: `03_xss_demo.php` (Reflected XSS)
- **حمله:** `<script>alert(document.cookie)</script>` → Session سرقت می‌شود
- **جلوگیری:** `htmlspecialchars()`

#### 2.4 فایل چهارم: `04_live_chat_xss.php` (Persistent XSS)
- **حمله:** کد مخرب در چت ذخیره می‌شود → همه کاربران متأثر می‌شوند
- **جلوگیری:** Escape در ذخیره و نمایش

#### 2.5 فایل پنجم: `05_sql_injection.php`
- **حمله:** `' OR '1'='1` → تمام کاربرها برگشت می‌خورند
- **جلوگیری:** Prepared Statements

#### 2.6 فایل ششم: `06_csrf_demo.php`
- **حمله:** Transfer بدون اجازه
- **جلوگیری:** CSRF Token

#### 2.7 فایل هفتم: `07_file_upload.php`
- **حمله:** `shell.php` آپلود → کنترل کامل سرور
- **جلوگیری:** Extension whitelist + MIME type check

---

### 📊 خلاصه: تمام فایل‌های پروژه

#### 🎮 فایل‌های Demo (9 فایل)

| فایل | اندازه | نقش | مرحله |
|------|--------|------|--------|
| `01_get_post.php` | 5.6 KB | HTTP Methods | 1 & 2 |
| `02_validation.php` | 5.7 KB | Validation | 1 & 2 |
| `03_xss_demo.php` | 5.3 KB | Reflected XSS | 1 & 2 |
| `04_live_chat_xss.php` | 6.4 KB | Persistent XSS | 2 |
| `05_sql_injection.php` | 9.6 KB | SQL Injection | 2 |
| `06_csrf_demo.php` | 10.8 KB | CSRF | 2 |
| `07_file_upload.php` | 13.1 KB | File Upload | 2 |
| `final/register.php` | - | Registration | 1 |
| `final/profile.php` | - | Profile | 1 |

#### 📚 فایل‌های راهنمایی (4 فایل)

| فایل | اندازه | نقش | لینک |
|------|--------|------|------|
| `PRESENTATION_FORMS.md` | 8.3 KB | راهنمای فرم‌ها | [مطالعه](PRESENTATION_FORMS.md) |
| `PRESENTATION_SCRIPT.md` | 6.5 KB | راهنمای امنیت | [مطالعه](PRESENTATION_SCRIPT.md) |
| `README.md` | - | نمای کلی (این فایل) | - |
| `CONTRIBUTING.md` | 2 KB | راهنمای مشارکت | [مطالعه](CONTRIBUTING.md) |

#### 🎨 فایل‌های استایل و Asset (7 فایل)

| فایل | نقش |
|------|------|
| `assets/style.css` | استایل اصلی (Cyberpunk) |
| `assets/animations.css` | انیمیشن‌ها |
| `assets/console-logger.js` | ثبت تعاملات |
| `assets/interceptor.js` | نمایش HTTP requests |
| `assets/terminal.js` | شبیه‌سازی Terminal |
| `assets/examples.css` | استایل مثال‌ها |

#### 🏠 فایل‌های اصلی (2 فایل)

| فایل | نقش |
|------|------|
| `dashboard.php` | صفحه اصلی (نقطه ورود) |
| `slides.html` | اسلایدهای ارائه |

#### ⚙️ فایل‌های تنظیمات (4 فایل)

| فایل | نقش |
|------|------|
| `.gitignore` | Git ignore rules |
| `.github/workflows/php-lint.yml` | CI/CD workflow |
| `LICENSE` | MIT License |

---

### For Presenters

1. **Start Dashboard**: Open `dashboard.php` for navigation
2. **Use Slides**: `slides.html` for full presentation
3. **Live Demos**: Click any lab for interactive demonstration
4. **Terminal**: Use terminal for quick commands and info
5. **Presentation Scripts**: Review `PRESENTATION_SCRIPT.md` and `PRESENTATION_FORMS.md` before class (both have English notes + full Persian script).

### For Students

1. **Explore Labs**: Try each vulnerability in safe environment
2. **Test Payloads**: Use provided attack strings
3. **Compare Modes**: Toggle between vulnerable/secure versions
4. **Learn Defenses**: Study secure code examples
5. **Read Scripts (FA/EN)**: Use the presentation scripts to review the theory in Persian with short English summaries.

## 🎯 Penetration Testing Guide

### SQL Injection Payloads
```sql
' OR '1'='1          # Authentication bypass
' UNION SELECT *     # Data extraction
'; DROP TABLE --     # Destructive attack
```

### XSS Payloads
```html
<script>alert(document.cookie)</script>
<img src=x onerror=alert(1)>
<style>body{background:red;}</style>
```

### CSRF Attack
```html
<img src="bank.com/transfer?amount=5000&to=hacker">
<form action="target.com/action" method="POST" id="csrf">
```

### File Upload
```php
shell.php: <?php system($_GET['cmd']); ?>
webshell.php: Full command panel
```

## 🛡️ Security Best Practices

### Input Validation
✅ Server-side validation  
✅ Whitelist approach  
✅ Type checking  
✅ Length limits

### XSS Prevention
✅ `htmlspecialchars()` for output  
✅ Content Security Policy  
✅ Input sanitization  
✅ HTTPOnly cookies

### SQL Injection Prevention
✅ Prepared statements (PDO)  
✅ ORM frameworks  
✅ Input validation  
✅ Least privilege principle

### CSRF Protection
✅ CSRF tokens  
✅ SameSite cookies  
✅ Origin header validation  
✅ Re-authentication for sensitive actions

### File Upload Security
✅ Extension whitelist  
✅ MIME type validation  
✅ File content inspection  
✅ Random filename generation  
✅ Upload outside webroot

## 🎬 Presentation Tips

### Recommended Flow (1 hour)
1. **Introduction** (5 min) - Dashboard + overview
2. **Basic Concepts** (10 min) - GET/POST, Validation
3. **XSS Demonstrations** (15 min) - Demos 3-4
4. **Advanced Attacks** (20 min) - SQL, CSRF, File Upload
5. **Defense Techniques** (8 min) - Secure implementations
6. **Q&A** (2 min)

### Pro Tips
🎤 Use terminal for visual effect  
🎨 Keep vulnerable mode for impact  
💡 Explain each payload before demo  
⚡ Use "Launch All" for quick access  
🎯 Focus on real-world scenarios

## 🏆 Key Highlights

✅ **9 Complete Labs** - Comprehensive security coverage  
✅ **15+ Attack Vectors** - Real-world scenarios  
✅ **Vulnerable + Secure** - Side-by-side comparison  
✅ **Premium Design** - Professional appearance  
✅ **Zero Dependencies** - Pure PHP & JavaScript  
✅ **Educational Focus** - Perfect for learning  
✅ **Ready to Present** - No setup required

## 📖 Learning Outcomes

After completing this lab, you will understand:
- HTTP methods and their security implications
- Input validation and sanitization techniques
- XSS attack vectors and prevention
- SQL injection mechanics and defenses
- CSRF attack methodology and tokens
- File upload vulnerabilities and protection
- Real-world security best practices

## 👨‍💻 Author

**Amin Davodian**  
Senior Full-Stack Developer & Security Researcher

- 🌐 Website: [senioramin.com](https://senioramin.com)
- 💼 LinkedIn: [linkedin.com/in/SudoAmin](https://linkedin.com/in/SudoAmin)
- 🐙 GitHub: [github.com/SeniorAminam](https://github.com/SeniorAminam)

## ⚠️ Disclaimer

This project is for **educational purposes only**. All vulnerabilities are demonstrated in a controlled environment. Do NOT use these techniques on systems you don't own or have permission to test.

## 📄 License

Developed by Amin Davodian for educational use.

---

**Made with ❤️ and☕ by Amin Davodian**

