# 🚀 دستورهای پابلیش کردن به GitHub

## مرحله 1: تنظیم Git (اگر قبلاً انجام نشده)

```bash
# تنظیم نام و ایمیل
git config --global user.name "Amin Davodian"
git config --global user.email "your-email@example.com"

# ایجاد مخزن محلی
cd d:\Amin\Projects\Programming\Telegram\Bots\Tests\Erae\Tsw
git init
```

## مرحله 2: اضافه کردن تمام فایل‌ها

```bash
# اضافه کردن تمام فایل‌ها
git add .

# یا اضافه کردن فایل‌های خاص
git add *.php *.html *.md *.css *.js
git add assets/
git add final/
```

## مرحله 3: ایجاد Commit

```bash
# Commit اول
git commit -m "Initial commit: Web Security Lab - Hacker Edition

- 9 interactive security demonstrations
- CSRF demo with balance protection in Secure mode
- Scoped reset functionality (reset_page.php)
- Complete documentation in Persian and English
- Cyberpunk UI with Matrix theme
- Educational payloads and examples"
```

## مرحله 4: ایجاد مخزن در GitHub

1. برو به https://github.com/new
2. نام مخزن: `web-security-lab-hacker-edition`
3. توضیح: `Interactive web security demonstrations with vulnerable and secure modes`
4. انتخاب: Public
5. اضافه کردن README: No (چون قبلاً داریم)
6. اضافه کردن .gitignore: PHP
7. اضافه کردن License: MIT
8. کلیک بر "Create repository"

## مرحله 5: اتصال به GitHub

```bash
# اضافه کردن remote
git remote add origin https://github.com/SeniorAminam/web-security-lab-hacker-edition.git

# یا اگر SSH استفاده می‌کنی:
git remote add origin git@github.com:SeniorAminam/web-security-lab-hacker-edition.git

# تغییر نام branch (اگر لازم باشد)
git branch -M main

# Push کردن
git push -u origin main
```

## مرحله 6: بروزرسانی‌های بعدی

```bash
# برای هر بروزرسانی:
git add .
git commit -m "توضیح تغییرات"
git push origin main
```

## 📝 Commit Messages مثال

```bash
# اصلاح CSRF
git commit -m "Fix: CSRF demo balance only changes in Vulnerable mode"

# اضافه کردن reset_page.php
git commit -m "Feature: Add scoped reset functionality (reset_page.php)"

# اپدیت راهنماها
git commit -m "Docs: Update guides for new reset and CSRF fixes"

# اصلاح باگ
git commit -m "Fix: Correct reset button links in all pages"
```

## 🔍 بررسی وضعیت

```bash
# مشاهده وضعیت
git status

# مشاهده تاریخچه
git log --oneline

# مشاهده تغییرات
git diff
```

## ⚠️ نکات مهم

1. **فایل‌های حساس:** اگر فایل‌های حساس دارید، اضافه کنید به `.gitignore`
2. **حجم مخزن:** اگر فایل‌های بزرگ دارید، از Git LFS استفاده کنید
3. **Branches:** برای تغییرات بزرگ، branch جداگانه ایجاد کنید

## 🎯 خلاصه دستورات سریع

```bash
# تمام مراحل در یک دستور
git add . && git commit -m "Update: CSRF fix and scoped reset functionality" && git push origin main
```

---

**نویسنده:** Amin Davodian  
**وبسایت:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam
