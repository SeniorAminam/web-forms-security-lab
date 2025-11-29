# 📘 راهنمای تفصیلی: `05_sql_injection.php`

## 🎯 خلاصه‌ی کلی

این فایل یکی از **خطرناک‌ترین حملات دیتابیس** را نشان می‌دهد: **SQL Injection**.

SQL Injection یعنی اینکه هکر می‌تواند **کوئری SQL** را تغییر دهد:
- 🔓 تمام کاربران را بیرون بیاورد
- 💾 دیتابیس را حذف کند
- 🔑 رمز عبور را تغییر دهد
- 💰 پول را انتقال دهد

**مثال:**
```
جستجو: ' OR '1'='1
کوئری: SELECT * FROM users WHERE username = '' OR '1'='1'
نتیجه: تمام کاربران برگشت می‌خورند!
```

---

## 📋 ساختار فایل

```
05_sql_injection.php
├── PHP Logic
│   ├── Simulated Database (JSON)
│   ├── Vulnerable Mode
│   │   ├── Direct string concatenation
│   │   └── SQL injection detection
│   └── Secure Mode
│       └── Parameterized query simulation
├── HTML Structure
│   ├── Mode Toggle
│   ├── Search Form
│   ├── SQL Query Display
│   ├── Results Table
│   └── Payload Examples
└── JavaScript Functions
    └── setPayload()
```

---

## 🔍 تحلیل کد - بخش به بخش

### بخش 1: Simulated Database

```php
// Simulated database using file storage
$dbFile = 'users_db.json';

// Initialize database if not exists
if (!file_exists($dbFile)) {
    $initialData = [
        ['id' => 1, 'username' => 'admin', 'password' => 'Admin@123', 'role' => 'Administrator'],
        ['id' => 2, 'username' => 'alice', 'password' => 'Alice@456', 'role' => 'User'],
        ['id' => 3, 'username' => 'bob', 'password' => 'Bob@789', 'role' => 'User'],
    ];
    file_put_contents($dbFile, json_encode($initialData, JSON_PRETTY_PRINT));
}

$users = json_decode(file_get_contents($dbFile), true);
```

**توضیح:**

#### الف) فایل دیتابیس
```php
$dbFile = 'users_db.json';
```
- فایل JSON برای ذخیره‌سازی کاربران
- شبیه‌سازی دیتابیس واقعی

#### ب) داده‌های اولیه
```php
$initialData = [
    ['id' => 1, 'username' => 'admin', 'password' => 'Admin@123', 'role' => 'Administrator'],
    ['id' => 2, 'username' => 'alice', 'password' => 'Alice@456', 'role' => 'User'],
    ['id' => 3, 'username' => 'bob', 'password' => 'Bob@789', 'role' => 'User'],
];
```

**کاربران:**
- **admin**: رمز `Admin@123`، نقش Administrator
- **alice**: رمز `Alice@456`، نقش User
- **bob**: رمز `Bob@789`، نقش User

#### ج) خواندن دیتابیس
```php
$users = json_decode(file_get_contents($dbFile), true);
```
- خواندن فایل JSON
- تبدیل به آرایه PHP

---

### بخش 2: Mode Selection

```php
$results = [];
$query = '';
$isVulnerable = !isset($_GET['secure']);
```

**توضیح:**
- `$isVulnerable = !isset($_GET['secure'])`: اگر `?secure=1` نبود، حالت ناامن
- `$results`: نتایج جستجو
- `$query`: کوئری نمایش‌داده شده

---

### بخش 3: Vulnerable Mode

```php
if ($isVulnerable) {
    // VULNERABLE VERSION - For educational purposes only!
    // Simulating SQL injection by evaluating the search term
    foreach ($users as $user) {
        // Dangerous: Direct string concatenation
        $simulatedSQL = "SELECT * FROM users WHERE username = '{$searchTerm}'";
        
        // Simulate SQL injection attacks
        if (strpos($searchTerm, "' OR '1'='1") !== false) {
            // Return all users
            $results = $users;
            break;
        } elseif (strpos($searchTerm, "' UNION") !== false) {
            // Simulate UNION attack
            $results = $users;
            break;
        } elseif (stripos($searchTerm, 'DROP') !== false) {
            // Simulate DROP TABLE attack
            $results = [['username' => '⚠️ TABLE DROPPED!', 'password' => 'DATABASE DESTROYED', 'role' => 'CRITICAL ERROR']];
            break;
        } else {
            // Normal search
            if (stripos($user['username'], $searchTerm) !== false) {
                $results[] = $user;
            }
        }
    }
}
```

**توضیح:**

#### الف) کوئری مستقیم (خطرناک!)
```php
$simulatedSQL = "SELECT * FROM users WHERE username = '{$searchTerm}'";
```
- **خطرناک!** داده مستقیم در کوئری قرار می‌گیرد
- هکر می‌تواند کوئری را تغییر دهد

#### ب) حمله 1: OR '1'='1
```php
if (strpos($searchTerm, "' OR '1'='1") !== false) {
    $results = $users;
    break;
}
```

**مثال:**
```
جستجو: ' OR '1'='1
کوئری: SELECT * FROM users WHERE username = '' OR '1'='1'
```

**توضیح:**
- `'' OR '1'='1'` همیشه true است
- تمام کاربران برگشت می‌خورند

#### ج) حمله 2: UNION
```php
elseif (strpos($searchTerm, "' UNION") !== false) {
    $results = $users;
    break;
}
```

**مثال:**
```
جستجو: ' UNION SELECT * FROM admin_users --
کوئری: SELECT * FROM users WHERE username = '' UNION SELECT * FROM admin_users --'
```

#### د) حمله 3: DROP TABLE
```php
elseif (stripos($searchTerm, 'DROP') !== false) {
    $results = [['username' => '⚠️ TABLE DROPPED!', 'password' => 'DATABASE DESTROYED', 'role' => 'CRITICAL ERROR']];
    break;
}
```

**مثال:**
```
جستجو: '; DROP TABLE users; --
کوئری: SELECT * FROM users WHERE username = ''; DROP TABLE users; --'
```

#### ه) جستجوی عادی
```php
else {
    if (stripos($user['username'], $searchTerm) !== false) {
        $results[] = $user;
    }
}
```
- اگر payload نبود، جستجوی عادی

---

### بخش 4: Secure Mode

```php
else {
    // SECURE VERSION - Using parameterized queries simulation
    $searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8');
    foreach ($users as $user) {
        if (stripos($user['username'], $searchTerm) !== false) {
            $results[] = $user;
        }
    }
}
```

**توضیح:**

#### الف) Escape کردن
```php
$searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8');
```
- کاراکترهای خطرناک escape می‌شوند
- `'` → `&#039;`
- `"` → `&quot;`

#### ب) جستجوی عادی
```php
if (stripos($user['username'], $searchTerm) !== false) {
    $results[] = $user;
}
```
- فقط جستجوی عادی

---

### بخش 5: نمایش کوئری

```php
<?php if ($query): ?>
    <div class="sql-query">
        <strong>Simulated SQL:</strong><br>
        SELECT * FROM users WHERE username = '<?php echo htmlspecialchars($query); ?>'
    </div>
<?php endif; ?>
```

**توضیح:**
- نمایش کوئری شبیه‌سازی‌شده
- کاربر می‌تواند ببیند چه کوئری‌ای اجرا شد

---

### بخش 6: نمایش نتایج

```php
<?php if ($results): ?>
    <h3 style="margin-top: 2rem; color: var(--primary-color);">Query Results:</h3>
    <table class="db-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['password'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($user['role'] ?? '-'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
```

**توضیح:**
- جدول برای نمایش نتایج
- تمام داده‌ها escape می‌شوند

---

### بخش 7: Mode Toggle

```html
<div class="mode-toggle">
    <a href="?secure=0" class="badge" style="<?php echo $isVulnerable ? 'background:var(--error-color);' : ''; ?> padding:0.5rem 1rem; margin:0.5rem; text-decoration:none; color:white;">
        ❌ Vulnerable Mode
    </a>
    <a href="?secure=1" class="badge" style="<?php echo !$isVulnerable ? 'background:var(--primary-color); color:#000;' : ''; ?> padding:0.5rem 1rem; margin:0.5rem; text-decoration:none; color:white;">
        ✅ Secure Mode
    </a>
</div>
```

**توضیح:**
- دو دکمه برای تبدیل بین حالت‌ها
- قرمز برای ناامن
- آبی برای امن

---

## 🧪 نحوه استفاده - مثال‌های عملی

### مثال 1: جستجوی عادی

**مرحله 1:** حالت ناامن را انتخاب کنید
```
?secure=0
```

**مرحله 2:** جستجو کنید
```
جستجو: alice
```

**خروجی:**
```
Simulated SQL:
SELECT * FROM users WHERE username = 'alice'

Query Results:
ID: 2
Username: alice
Password: Alice@456
Role: User
```

---

### مثال 2: SQL Injection - OR '1'='1

**مرحله 1:** حالت ناامن را انتخاب کنید
```
?secure=0
```

**مرحله 2:** جستجو کنید
```
جستجو: ' OR '1'='1
```

**خروجی:**
```
Simulated SQL:
SELECT * FROM users WHERE username = '' OR '1'='1'

Query Results:
ID: 1, Username: admin, Password: Admin@123, Role: Administrator
ID: 2, Username: alice, Password: Alice@456, Role: User
ID: 3, Username: bob, Password: Bob@789, Role: User
```

**توضیح:**
- **تمام کاربران** برگشت خوردند!
- رمز عبور **admin** نمایش داده شد!
- **خطرناک!**

---

### مثال 3: SQL Injection - DROP TABLE

**مرحله 1:** حالت ناامن را انتخاب کنید
```
?secure=0
```

**مرحله 2:** جستجو کنید
```
جستجو: '; DROP TABLE users; --
```

**خروجی:**
```
Simulated SQL:
SELECT * FROM users WHERE username = ''; DROP TABLE users; --'

Query Results:
ID: -, Username: ⚠️ TABLE DROPPED!, Password: DATABASE DESTROYED, Role: CRITICAL ERROR
```

**توضیح:**
- دیتابیس **حذف شد**!
- **بسیار خطرناک!**

---

### مثال 4: Secure Mode

**مرحله 1:** حالت امن را انتخاب کنید
```
?secure=1
```

**مرحله 2:** جستجو کنید
```
جستجو: ' OR '1'='1
```

**خروجی:**
```
Query Results:
(بدون نتیجه)
```

**توضیح:**
- هیچ نتیجه‌ای نیافت
- Payload escape شد
- **محفوظ!**

---

## 🔐 نکات امنیتی

### ✅ نقاط قوت این کد

1. **Mode Toggle**
   ```php
   $isVulnerable = !isset($_GET['secure']);
   ```
   - کاربر می‌تواند بین ناامن و امن انتخاب کند

2. **SQL Display**
   ```php
   SELECT * FROM users WHERE username = '<?php echo htmlspecialchars($query); ?>'
   ```
   - کاربر می‌تواند ببیند چه کوئری‌ای اجرا شد

3. **Escape in Display**
   ```php
   htmlspecialchars($user['username'])
   ```
   - نتایج escape می‌شوند

### ⚠️ نقاط ضعف (برای آموزش)

1. **Vulnerable Mode در تولید**
   - این کد **هرگز** در تولید استفاده نشود

2. **بدون Prepared Statements**
   - در PHP واقعی، PDO استفاده شود

3. **بدون Logging**
   - حملات ثبت نمی‌شوند

---

## 📊 جدول SQL Injection Payloads

| Payload | نوع | نتیجه | خطر |
|---------|------|------|------|
| `' OR '1'='1` | Authentication Bypass | تمام کاربران | ⚠️⚠️⚠️ |
| `' UNION SELECT ...` | Data Extraction | داده‌های دیگر | ⚠️⚠️⚠️ |
| `'; DROP TABLE ...` | Destructive | حذف جدول | ⚠️⚠️⚠️⚠️ |
| `' --` | Comment | تغییر کوئری | ⚠️⚠️ |

---

## 🎓 درس‌های یادگیری

### درس 1: SQL Injection چیست؟
- حمله‌ای که کوئری SQL را تغییر می‌دهد
- می‌تواند دیتابیس را حذف کند

### درس 2: انواع حملات
- **Authentication Bypass:** رمز عبور بدون دانستن
- **Data Extraction:** اطلاعات سرقت
- **Destructive:** حذف دیتابیس

### درس 3: دفاع
- **Prepared Statements:** بهترین روش
- **Input Validation:** اعتبارسنجی
- **Escape:** فقط برای نمایش

### درس 4: Best Practices
- **Parameterized Queries:**
  ```php
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  ```
- **ORM Frameworks:** Eloquent, Doctrine
- **Least Privilege:** کاربر دیتابیس محدود

---

## 🔗 ارتباط با فایل‌های دیگر

- **قبلی:** `04_live_chat_xss.php` - Persistent XSS
- **بعدی:** `06_csrf_demo.php` - CSRF Attack
- **مرتبط:** `final/register.php` - فرم امن

---

## 📝 خلاصه

**`05_sql_injection.php`** یک دمو تعاملی است که:
- ✅ SQL Injection را نشان می‌دهد
- ✅ سه نوع حمله را پوشش می‌دهد
- ✅ Mode toggle برای مقایسه
- ✅ نمایش کوئری برای درک بهتر

**نویسنده:** Amin Davodian  
**وبسایت:** https://senioramin.com  
**GitHub:** https://github.com/SeniorAminam

---

*Developed by Amin Davodian - Web Security Lab*
