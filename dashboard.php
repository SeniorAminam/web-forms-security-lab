<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * Central Dashboard - Main navigation hub for all demonstrations
 * Developed by Amin Davodian
 */
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Lab Dashboard | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
    <link rel="stylesheet" href="assets/animations.css">
    <style>
        .hero {
            text-align: center;
            padding: 3rem 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(139, 92, 246, 0.1));
            border-radius: 8px;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.2rem;
            color: var(--text-muted);
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .demo-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
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
        
        .demo-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }
        
        .demo-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .demo-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .demo-description {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .demo-level {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            margin: 0.5rem;
        }
        
        .level-basic { background: rgba(16, 185, 129, 0.2); color: var(--primary-color); }
        .level-intermediate { background: rgba(245, 158, 11, 0.2); color: var(--warning-color); }
        .level-advanced { background: rgba(239, 68, 68, 0.2); color: var(--error-color); }
        
        .stats-bar {
            display: flex;
            justify-content: space-around;
            padding: 2rem;
            background: rgba(0,0,0,0.3);
            border-radius: 8px;
            margin: 2rem 0;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            color: var(--primary-color);
            font-weight: bold;
        }
        
        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .launch-all {
            text-align: center;
            margin: 3rem 0;
        }
        
        .launch-btn {
            display: inline-block;
            padding: 1.5rem 3rem;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: #000;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s;
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
        }
        
        .launch-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 50px rgba(16, 185, 129, 0.8);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero animate-fade">
            <div data-text="CYBER SECURITY LAB" class="glitch" style="font-size: 3.5rem; color: var(--primary-color); margin-bottom: 1rem;">
                🔐 CYBER SECURITY LAB
            </div>
            <p class="neon-text">Welcome to the Ultimate Web Security Demonstration</p>
            <p style="margin-top: 0.5rem;">by <strong>Amin Davodian</strong></p>
        </div>

        <!-- Statistics -->
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

        <!-- Demo Cards Grid -->
        <h2 style="text-align: center; margin: 3rem 0 2rem 0; color: var(--primary-color);">
            🎯 Choose Your Challenge
        </h2>
        
        <div class="demo-grid">
            <!-- Demo 1: GET vs POST -->
            <a href="01_get_post.php" class="demo-card animate-slide-left" style="text-decoration: none; color: inherit;">
                <span class="demo-icon">📡</span>
                <h3 class="demo-title">GET vs POST</h3>
                <p class="demo-description">
                    مقایسه متدهای HTTP و رهگیری درخواست‌ها
                </p>
                <span class="demo-level level-basic">Basic</span>
            </a>

            <!-- Demo 2: Validation -->
            <a href="02_validation.php" class="demo-card animate-slide-left" style="text-decoration: none; color: inherit; animation-delay: 0.1s;">
                <span class="demo-icon">✅</span>
                <h3 class="demo-title">Input Validation</h3>
                <p class="demo-description">
                    اعتبارسنجی داده‌های ورودی و جلوگیری از ورودی‌های نامعتبر
                </p>
                <span class="demo-level level-basic">Basic</span>
            </a>

            <!-- Demo 3: XSS -->
            <a href="03_xss_demo.php" class="demo-card animate-slide-left" style="text-decoration: none; color: inherit; animation-delay: 0.2s;">
                <span class="demo-icon">💉</span>
                <h3 class="demo-title">XSS Attack</h3>
                <p class="demo-description">
                    تزریق کد JavaScript مخرب و روش‌های دفاعی
                </p>
                <span class="demo-level level-intermediate">Intermediate</span>
            </a>

            <!-- Demo 4: Live Chat XSS -->
            <a href="04_live_chat_xss.php" class="demo-card animate-slide-right" style="text-decoration: none; color: inherit; animation-delay: 0.3s;">
                <span class="demo-icon">💬</span>
                <h3 class="demo-title">Live Chat XSS Lab</h3>
                <p class="demo-description">
                    حمله Persistent XSS در سیستم چت زنده
                </p>
                <span class="demo-level level-intermediate">Intermediate</span>
            </a>

            <!-- Demo 5: SQL Injection -->
            <a href="05_sql_injection.php" class="demo-card animate-slide-right" style="text-decoration: none; color: inherit; animation-delay: 0.4s;">
                <span class="demo-icon">🗄️</span>
                <h3 class="demo-title">SQL Injection</h3>
                <p class="demo-description">
                    دستکاری پایگاه داده با تزریق کد SQL
                </p>
                <span class="demo-level level-advanced">Advanced</span>
            </a>

            <!-- Demo 6: CSRF -->
            <a href="06_csrf_demo.php" class="demo-card animate-slide-right" style="text-decoration: none; color: inherit; animation-delay: 0.5s;">
                <span class="demo-icon">🎯</span>
                <h3 class="demo-title">CSRF Attack</h3>
                <p class="demo-description">
                    جعل درخواست بین‌سایتی و دفاع با CSRF Token
                </p>
                <span class="demo-level level-advanced">Advanced</span>
            </a>

            <!-- Demo 7: File Upload -->
            <a href="07_file_upload.php" class="demo-card animate-slide-left" style="text-decoration: none; color: inherit; animation-delay: 0.6s;">
                <span class="demo-icon">📤</span>
                <h3 class="demo-title">File Upload Attack</h3>
                <p class="demo-description">
                    آپلود Webshell و دستکاری سرور
                </p>
                <span class="demo-level level-advanced">Advanced</span>
            </a>

            <!-- Demo 8: Final Project -->
            <a href="final/register.php" class="demo-card animate-slide-left" style="text-decoration: none; color: inherit; animation-delay: 0.7s;">
                <span class="demo-icon">🏆</span>
                <h3 class="demo-title">Final Project</h3>
                <p class="demo-description">
                    سیستم ثبت‌نام امن با تمام تکنیک‌های دفاعی
                </p>
                <span class="demo-level level-intermediate">Final</span>
            </a>

            <!-- Presentation Slides -->
            <a href="slides.html" class="demo-card animate-slide-left" style="text-decoration: none; color: inherit; animation-delay: 0.8s;">
                <span class="demo-icon">🎬</span>
                <h3 class="demo-title">Presentation Slides</h3>
                <p class="demo-description">
                    اسلایدهای ارائه با Matrix Effect
                </p>
                <span class="demo-level level-basic">Presentation</span>
            </a>
        </div>

        <!-- Launch All Button -->
        <div class="launch-all">
            <button onclick="launchAllDemos()" class="launch-btn cyber-button">
                🚀 LAUNCH ALL DEMOS
            </button>
        </div>

        <!-- Terminal Integration -->
        <div id="cyber-terminal" style="margin-top: 3rem;"></div>

        <!-- Footer -->
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
    </div>

    <script src="assets/terminal.js"></script>
    <script>
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

        // Add glitch effect data attribute
        document.addEventListener('DOMContentLoaded', () => {
            const glitchElement = document.querySelector('.glitch');
            if (glitchElement) {
                glitchElement.setAttribute('data-text', glitchElement.textContent);
            }
        });
    </script>
</body>
</html>

