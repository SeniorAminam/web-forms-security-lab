# 📘 راهنمای تفصیلی: فایل‌های Assets و تنظیمات

## 🎯 خلاصه‌ی کلی

این بخش فایل‌های **پشتیبانی** و **تنظیمات** پروژه را توضیح می‌دهد:

- 🎨 **CSS Files**: استایل‌ها و انیمیشن‌ها
- 🔧 **JavaScript Files**: کارکردهای تعاملی
- ⚙️ **Configuration Files**: تنظیمات پروژه

---

## 📋 ساختار Assets

```
assets/
├── style.css              # استایل اصلی (Cyberpunk theme)
├── animations.css         # انیمیشن‌های اضافی
├── console-logger.js      # ثبت تعاملات
├── interceptor.js         # رهگیری درخواست‌ها
├── terminal.js            # شبیه‌سازی ترمینال
└── examples.css           # استایل مثال‌ها
```

---

## 🎨 `assets/style.css`

### نقش اصلی

فایل **استایل اصلی** پروژه است که:
- ✅ تم Cyberpunk/Hacker را تعریف می‌کند
- ✅ متغیرهای CSS (CSS Variables)
- ✅ استایل‌های عمومی
- ✅ Responsive Design

### متغیرهای CSS

```css
:root {
    --primary-color: #10b981;        /* سبز (اصلی) */
    --secondary-color: #8b5cf6;      /* بنفش */
    --error-color: #ef4444;          /* قرمز */
    --warning-color: #f59e0b;        /* نارنجی */
    --success-color: #10b981;        /* سبز */
    --text-color: #e5e7eb;           /* متن روشن */
    --text-muted: #9ca3af;           /* متن کم‌رنگ */
    --bg-dark: #0f172a;              /* پس‌زمینه تیره */
    --card-bg: rgba(15, 23, 42, 0.8);/* پس‌زمینه کارت */
    --border-color: #1e293b;         /* رنگ border */
}
```

### استایل‌های عمومی

```css
body {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: var(--text-color);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 2rem;
    backdrop-filter: blur(10px);
}
```

### Neon Effect

```css
.neon-text {
    text-shadow: 0 0 10px var(--primary-color),
                 0 0 20px var(--primary-color),
                 0 0 30px var(--primary-color);
}

.primary-glow {
    box-shadow: 0 0 20px var(--primary-color);
}
```

---

## ✨ `assets/animations.css`

### انیمیشن‌های اصلی

```css
@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-fade {
    animation: fade-in 0.5s ease-in;
}

@keyframes slide-left {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-slide-left {
    animation: slide-left 0.5s ease-out;
}

@keyframes glitch {
    0% {
        text-shadow: -2px 0 #ef4444, 2px 0 #10b981;
    }
    50% {
        text-shadow: 2px 0 #ef4444, -2px 0 #10b981;
    }
    100% {
        text-shadow: -2px 0 #ef4444, 2px 0 #10b981;
    }
}

.glitch {
    animation: glitch 0.3s infinite;
}
```

---

## 🔧 `assets/console-logger.js`

### نقش

ثبت تعاملات کاربر در کنسول مرورگر

### کارکرد اصلی

```javascript
window.logger = {
    log: function(category, message, type) {
        const timestamp = new Date().toLocaleTimeString();
        const style = this.getStyle(type);
        console.log(
            `%c[${timestamp}] ${category}: ${message}`,
            style
        );
    },
    
    getStyle: function(type) {
        const styles = {
            'info': 'color: #10b981; font-weight: bold;',
            'warning': 'color: #f59e0b; font-weight: bold;',
            'error': 'color: #ef4444; font-weight: bold;',
            'success': 'color: #10b981; font-weight: bold;'
        };
        return styles[type] || styles['info'];
    }
};
```

### استفاده

```javascript
if(window.logger) {
    window.logger.log('Interaction', 'User clicked button', 'info');
}
```

---

## 🌐 `assets/interceptor.js`

### نقش

رهگیری و نمایش درخواست‌های HTTP

### کارکرد اصلی

```javascript
window.addEventListener('beforeunload', function(e) {
    // رهگیری درخواست‌ها
    console.log('Request intercepted:', {
        method: 'POST/GET',
        url: window.location.href,
        timestamp: new Date()
    });
});

// Intercept fetch requests
const originalFetch = window.fetch;
window.fetch = function(...args) {
    console.log('Fetch request:', args[0]);
    return originalFetch.apply(this, args);
};
```

---

## 💻 `assets/terminal.js`

### نقش

شبیه‌سازی ترمینال در صفحه

### کارکرد اصلی

```javascript
class CyberTerminal {
    constructor(elementId) {
        this.element = document.getElementById(elementId);
        this.history = [];
    }
    
    execute(command) {
        const output = this.parseCommand(command);
        this.display(output);
        this.history.push(command);
    }
    
    parseCommand(cmd) {
        const commands = {
            'help': 'Available commands: help, clear, info',
            'clear': '',
            'info': 'Web Security Lab v1.0'
        };
        return commands[cmd] || 'Command not found';
    }
    
    display(output) {
        const line = document.createElement('div');
        line.textContent = output;
        this.element.appendChild(line);
    }
}
```

---

## ⚙️ فایل‌های تنظیمات

### `.gitignore`

```
# Uploads
uploads/
chat_data.txt
users_db.json

# IDE
.vscode/
.idea/
*.swp

# OS
.DS_Store
Thumbs.db

# Logs
*.log
```

### `LICENSE`

```
MIT License

Copyright (c) 2025 Amin Davodian

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction...
```

### `.github/workflows/php-lint.yml`

```yaml
name: PHP Lint

on: [push, pull_request]

jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: PHP Lint
        run: |
          find . -name "*.php" -exec php -l {} \;
```

---

## 📊 جدول: فایل‌های Assets

| فایل | نوع | نقش | اندازه |
|------|------|------|--------|
| `style.css` | CSS | استایل اصلی | ~5 KB |
| `animations.css` | CSS | انیمیشن‌ها | ~2 KB |
| `console-logger.js` | JS | ثبت تعاملات | ~1 KB |
| `interceptor.js` | JS | رهگیری درخواست | ~1 KB |
| `terminal.js` | JS | شبیه‌سازی ترمینال | ~2 KB |

---

## 🎓 درس‌های یادگیری

### درس 1: CSS Variables
```css
:root {
    --primary-color: #10b981;
}

.element {
    color: var(--primary-color);
}
```

### درس 2: Animations
```css
@keyframes slide-in {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
}

.animated {
    animation: slide-in 0.5s ease-out;
}
```

### درس 3: JavaScript Interception
```javascript
window.addEventListener('beforeunload', (e) => {
    console.log('Page leaving');
});
```

---

## 🔗 ارتباط با فایل‌های دیگر

- **استفاده شده در:** تمام فایل‌های PHP
- **مرتبط:** dashboard.php، slides.html

---

## 📝 خلاصه

**فایل‌های Assets و تنظیمات** شامل:
- ✅ استایل‌های Cyberpunk
- ✅ انیمیشن‌های جذاب
- ✅ ابزارهای JavaScript
- ✅ تنظیمات پروژه

**نویسنده:** Amin Davodian  
**وبسایت:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

*Developed by Amin Davodian - Web Security Lab*
