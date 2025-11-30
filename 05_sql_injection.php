<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * SQL Injection Lab - Educational demonstration of SQL injection vulnerabilities
 * Developed by Amin Davodian
 */

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
$results = [];
$query = '';
$isVulnerable = !isset($_GET['secure']);

// Handle search - Developed by Amin Davodian
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = $searchTerm;
    
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
    } else {
        // SECURE VERSION - Using parameterized queries simulation
        $searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8');
        foreach ($users as $user) {
            if (stripos($user['username'], $searchTerm) !== false) {
                $results[] = $user;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection Lab | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
    <link rel="stylesheet" href="assets/animations.css">
    <style>
        .db-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: rgba(0,0,0,0.5);
        }
        .db-table th, .db-table td {
            padding: 0.75rem;
            text-align: right;
            border: 1px solid var(--border-color);
        }
        .db-table th {
            background: rgba(16, 185, 129, 0.1);
            color: var(--primary-color);
            font-weight: bold;
        }
        .sql-query {
            background: #000;
            color: var(--primary-color);
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: var(--font-mono);
            margin: 1rem 0;
            direction: ltr;
            text-align: left;
        }
        .mode-toggle {
            text-align: center;
            margin-bottom: 2rem;
        }
        .payload-list {
            background: rgba(0,0,0,0.3);
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
        }
        .payload-item {
            cursor: pointer;
            padding: 0.5rem;
            margin: 0.25rem 0;
            background: rgba(255,255,255,0.05);
            border-radius: 2px;
            transition: all 0.3s;
            direction: ltr;
            text-align: left;
        }
        .payload-item:hover {
            background: rgba(16, 185, 129, 0.2);
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="neon-text">💉 SQL Injection Laboratory</h1>
        
        <div class="mode-toggle">
            <a href="?secure=0" class="badge" style="<?php echo $isVulnerable ? 'background:var(--error-color);' : ''; ?> padding:0.5rem 1rem; margin:0.5rem; text-decoration:none; color:white;">
                ❌ Vulnerable Mode
            </a>
            <a href="?secure=1" class="badge" style="<?php echo !$isVulnerable ? 'background:var(--primary-color); color:#000;' : ''; ?> padding:0.5rem 1rem; margin:0.5rem; text-decoration:none; color:white;">
                ✅ Secure Mode
            </a>
            <a href="reset_page.php?page=05_sql_injection.php<?php echo $isVulnerable ? '' : '&secure=1'; ?>" class="badge" style="background:var(--warning-color); color:#000; padding:0.5rem 1rem; margin:0.5rem; text-decoration:none;">
                🔄 Reset All
            </a>
        </div>

        <div class="grid">
            <!-- Search Interface -->
            <div class="card cyber-border">
                <h2>🔍 User Search System</h2>
                <p class="alert <?php echo $isVulnerable ? 'alert-error' : 'alert-success'; ?>">
                    <strong>Current Mode:</strong> <?php echo $isVulnerable ? 'Vulnerable (No Protection)' : 'Secure (Parameterized Query)'; ?>
                </p>
                
                <form action="" method="GET">
                    <?php if (!$isVulnerable): ?>
                        <input type="hidden" name="secure" value="1">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Search Username:</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                               placeholder="Enter username...">
                    </div>
                    <button type="submit" class="cyber-button">Execute Query</button>
                </form>

                <?php if ($query): ?>
                    <div class="sql-query">
                        <strong>Simulated SQL:</strong><br>
                        SELECT * FROM users WHERE username = '<?php echo htmlspecialchars($query); ?>'
                    </div>
                <?php endif; ?>

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
            </div>

            <!-- Payload Examples & Best Practices - Developed by Amin Davodian -->
            <div class="card">
                <h2>💉 SQL Injection Payloads</h2>
                <p class="alert alert-warning" style="margin-bottom: 1rem;">
                    روی هر payload کلیک کنید تا فیلد جستجو پر شود، سپس خودتان روی <strong>Execute Query</strong> کلیک کنید.
                </p>

                <div class="payload-list">
                    <div class="payload-item" data-payload="' OR '1'='1" onclick="setPayload(this.dataset.payload)">
                        <div><strong>' OR '1'='1</strong></div>
                        <div>Authentication Bypass - بازگشت تمام کاربران</div>
                    </div>
                    <div class="payload-item" data-payload="' UNION SELECT * FROM users --" onclick="setPayload(this.dataset.payload)">
                        <div><strong>' UNION SELECT * FROM users --</strong></div>
                        <div>UNION Attack - استخراج داده‌ها</div>
                    </div>
                    <div class="payload-item" data-payload="'; DROP TABLE users; --" onclick="setPayload(this.dataset.payload)">
                        <div><strong>'; DROP TABLE users; --</strong></div>
                        <div>Destructive - حذف جدول شبیه‌سازی‌شده</div>
                    </div>
                    <div class="payload-item" data-payload="' --" onclick="setPayload(this.dataset.payload)">
                        <div><strong>' --</strong></div>
                        <div>Comment Injection - قطع بقیه کوئری</div>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(0,0,0,0.5); border-radius: 4px;">
                    <h3 style="color: var(--primary-color); font-size: 1rem;">🛡️ SQL Injection Defenses</h3>
                    <ul style="margin: 0.5rem 0; padding-right: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
                        <li>Input Validation &amp; Sanitization</li>
                        <li>Principle of Least Privilege</li>
                        <li>Use ORM frameworks</li>
                        <li>Escape special characters (for output only)</li>
                    </ul>
                </div>

                <div style="margin-top: 1rem; padding: 1rem; background: rgba(0,0,0,0.5); border-radius: 4px;">
                    <h3 style="color: var(--primary-color); font-size: 1rem;">📖 Secure Code Example:</h3>
                    <pre style="margin: 0; font-size: 0.85rem;">// PHP PDO Prepared Statement
$stmt = $pdo->prepare(
    "SELECT * FROM users 
     WHERE username = :username"
);
$stmt->execute(['username' => $input]);
$result = $stmt->fetchAll();</pre>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="slides.html" style="color: var(--primary-color); text-decoration: none;">
                ← Back to Slides
            </a>
        </div>
    </div>

    <script>
        function setPayload(payload) {
            const searchInput = document.querySelector('input[name="search"]');
            searchInput.value = payload;
            searchInput.focus();
            
            // Visual feedback
            searchInput.style.borderColor = 'var(--error-color)';
            setTimeout(() => {
                searchInput.style.borderColor = '';
            }, 500);
        }
    </script>
</body>
</html>

