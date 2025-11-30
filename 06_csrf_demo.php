<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * CSRF Attack Demonstration - Cross-Site Request Forgery lab
 * Developed by Amin Davodian
 */

session_start();

// Initialize balance
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 10000; // Initial balance: $10,000
}

// Generate CSRF token for secure mode
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$transferSuccess = false;
$secureMode = isset($_GET['secure']) && $_GET['secure'] == 1;

// Handle transfer - Developed by Amin Davodian
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transfer'])) {
    $amount = floatval($_POST['amount'] ?? 0);
    $recipient = trim($_POST['recipient'] ?? '');
    $action = $_POST['action'] ?? 'send'; // 'send' or 'receive'
    
    // Determine which form was submitted
    $isSecureForm = isset($_POST['csrf_token']);
    
    // Validate inputs first
    if (empty($recipient)) {
        $message = '❌ Recipient name is required!';
    } elseif ($amount <= 0) {
        $message = '❌ Amount must be greater than zero!';
    } elseif ($action === 'send' && $amount > $_SESSION['balance']) {
        $message = '❌ Insufficient balance!';
    } elseif ($isSecureForm) {
        // SECURE VERSION - Check CSRF token AND mode
        if (!$secureMode) {
            // Block if not in secure mode - form should not be submitted
            $message = '❌ Secure form blocked! You must be in Secure Mode.';
        } else {
            $providedToken = $_POST['csrf_token'] ?? '';
            
            if (!hash_equals($_SESSION['csrf_token'], $providedToken)) {
                $message = '❌ CSRF token validation failed! Transfer blocked.';
            } else {
                // Process transfer based on action
                if ($action === 'send') {
                    $_SESSION['balance'] -= $amount;
                    $message = "✅ Successfully sent $" . number_format($amount, 2) . " to " . htmlspecialchars($recipient);
                } else { // receive
                    $_SESSION['balance'] += $amount;
                    $message = "✅ Successfully received $" . number_format($amount, 2) . " from " . htmlspecialchars($recipient);
                }
                $transferSuccess = true;
            }
        }
    } else {
        // VULNERABLE VERSION - No CSRF protection (only in vulnerable mode)
        if (!$secureMode) {
            // Process transfer based on action
            if ($action === 'send') {
                $_SESSION['balance'] -= $amount;
                $message = "✅ Sent $" . number_format($amount, 2) . " to " . htmlspecialchars($recipient);
            } else { // receive
                $_SESSION['balance'] += $amount;
                $message = "✅ Received $" . number_format($amount, 2) . " from " . htmlspecialchars($recipient);
            }
            $transferSuccess = true;
        } else {
            $message = '❌ Vulnerable form blocked! You must be in Vulnerable Mode.';
        }
    }
}

// Reset balance
if (isset($_GET['reset'])) {
    $_SESSION['balance'] = 10000;
    header('Location: 06_csrf_demo.php' . ($secureMode ? '?secure=1' : ''));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Attack Lab | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
    <link rel="stylesheet" href="assets/animations.css">
    <style>
        .balance-display {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(139, 92, 246, 0.1));
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .balance-amount {
            font-size: 2.5rem;
            color: var(--primary-color);
            text-shadow: 0 0 20px var(--primary-glow);
        }
        .malicious-form {
            background: rgba(239, 68, 68, 0.1);
            border: 2px dashed var(--error-color);
            padding: 1.5rem;
            border-radius: 4px;
        }
        .attack-preview {
            background: #000;
            padding: 1rem;
            border: 1px solid var(--error-color);
            border-radius: 4px;
            margin-top: 1rem;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            direction: ltr;
            text-align: left;
            overflow-x: auto;
        }
        .transfer-form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        .transfer-card {
            padding: 1.5rem;
            border-radius: 8px;
            border-top: 4px solid;
        }
        .transfer-card.vulnerable {
            border-top-color: var(--error-color);
            background: rgba(239, 68, 68, 0.05);
        }
        .transfer-card.secure {
            border-top-color: var(--primary-color);
            background: rgba(16, 185, 129, 0.05);
        }
        .transfer-card h3 {
            margin-top: 0;
            margin-bottom: 1rem;
        }
        .transfer-card .form-group {
            margin-bottom: 1rem;
        }
        .transfer-card input,
        .transfer-card button {
            width: 100%;
            box-sizing: border-box;
        }
        @media (max-width: 768px) {
            .transfer-form-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .balance-amount {
                font-size: 2rem;
            }
            .attack-preview {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="neon-text">🎯 CSRF Attack Laboratory</h1>
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="?secure=0" class="badge" style="<?php echo !$secureMode ? 'background:var(--error-color);' : ''; ?> padding:0.5rem 1rem; margin:0.5rem; text-decoration:none; color:white;">
                ❌ Vulnerable Bank
            </a>
            <a href="?secure=1" class="badge" style="<?php echo $secureMode ? 'background:var(--primary-color); color:#000;' : ''; ?> padding:0.5rem 1rem; margin:0.5rem; text-decoration:none; color:white;">
                ✅ Secure Bank
            </a>
            <a href="reset_page.php?page=06_csrf_demo.php<?php echo $secureMode ? '&secure=1' : ''; ?>" class="badge" style="background:var(--warning-color); color:#000; padding:0.5rem 1rem; margin:0.5rem; text-decoration:none;">
                🔄 Reset All
            </a>
        </div>

        <!-- Balance Display -->
        <div class="balance-display animate-fade">
            <div style="color: var(--text-muted); font-size: 1.2rem; margin-bottom: 0.5rem;">
                💰 Current Balance
            </div>
            <div class="balance-amount">
                $<?php echo number_format($_SESSION['balance'], 2); ?>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert <?php echo $transferSuccess ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="card" style="margin-top: 2rem; border-top: 4px solid var(--warning-color);">
            <h2 style="color: var(--warning-color);">⚡ Quick Actions - Pre-built Payloads</h2>
            <p>کلیک کنید تا تراکنش‌های آماده اجرا شوند:</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <form method="POST" style="display: contents;">
                    <input type="hidden" name="recipient" value="Attacker">
                    <input type="hidden" name="amount" value="1000">
                    <input type="hidden" name="action" value="send">
                    <input type="hidden" name="transfer" value="1">
                    <button type="submit" class="badge" style="background: var(--error-color); color: white; padding: 0.75rem; cursor: pointer; border: none; border-radius: 4px;">
                        💸 Send $1,000 (Vuln)
                    </button>
                </form>
                
                <form method="POST" style="display: contents;">
                    <input type="hidden" name="recipient" value="Friend">
                    <input type="hidden" name="amount" value="2000">
                    <input type="hidden" name="action" value="receive">
                    <input type="hidden" name="transfer" value="1">
                    <button type="submit" class="badge" style="background: var(--error-color); color: white; padding: 0.75rem; cursor: pointer; border: none; border-radius: 4px;">
                        💰 Receive $2,000 (Vuln)
                    </button>
                </form>
                
                <form method="POST" style="display: contents;">
                    <input type="hidden" name="recipient" value="Charity">
                    <input type="hidden" name="amount" value="500">
                    <input type="hidden" name="action" value="send">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <input type="hidden" name="transfer" value="1">
                    <button type="submit" class="badge" style="background: var(--primary-color); color: #000; padding: 0.75rem; cursor: pointer; border: none; border-radius: 4px;">
                        💸 Send $500 (Secure)
                    </button>
                </form>
                
                <form method="POST" style="display: contents;">
                    <input type="hidden" name="recipient" value="Donor">
                    <input type="hidden" name="amount" value="3000">
                    <input type="hidden" name="action" value="receive">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <input type="hidden" name="transfer" value="1">
                    <button type="submit" class="badge" style="background: var(--primary-color); color: #000; padding: 0.75rem; cursor: pointer; border: none; border-radius: 4px;">
                        💰 Receive $3,000 (Secure)
                    </button>
                </form>
            </div>
        </div>

        <!-- Attack Demonstration -->
        <div class="card" style="margin-top: 2rem; border-top: 4px solid var(--secondary-color);">
            <h2 style="color: var(--secondary-color);">💀 Attack Demonstration</h2>
            <p>محتوای ظاهراً بی‌ضرر (مثلاً تبلیغات، عکس خنده‌دار، و...)</p>
            
            <div class="malicious-form">
                <h3 style="color: var(--error-color); font-size: 1rem;">⚠️ Hidden CSRF Attack Form</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted);">
                    این فرم به صورت خودکار ارسال می‌شود بدون اطلاع کاربر!
                </p>
                
                <form action="" method="POST" id="maliciousForm">
                    <input type="hidden" name="recipient" value="Hacker_Account">
                    <input type="hidden" name="amount" value="5000">
                    <input type="hidden" name="transfer" value="1">
                </form>
                
                <button onclick="executeCSRF()" style="background: var(--error-color); border-color: var(--error-color); width: 100%;">
                    🎯 Execute CSRF Attack
                </button>
                
                <div class="attack-preview">
                    <strong style="color: var(--error-color);">Attack Code:</strong>
                    <pre style="margin-top: 0.5rem; color: var(--primary-color);">&lt;!-- Malicious Website --&gt;
&lt;img src="bank.com/transfer.php?
     recipient=Hacker&amount=5000" 
     style="display:none"&gt;

&lt;!-- OR Auto-submit form --&gt;
&lt;body onload="document.forms[0].submit()"&gt;
    &lt;form action="bank.com/transfer" method="POST"&gt;
        &lt;input name="amount" value="5000"&gt;
        &lt;input name="recipient" value="Hacker"&gt;
    &lt;/form&gt;
&lt;/body&gt;</pre>
                </div>
            </div>
        </div>

        <!-- Protection Methods -->
        <div class="card" style="margin-top: 2rem;">
            <h2>🛡️ CSRF Protection Methods</h2>
            <ul style="margin: 0.5rem 0; padding-right: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
                <li><strong>CSRF Tokens:</strong> Random token در هر فرم</li>
                <li><strong>SameSite Cookies:</strong> محدود کردن ارسال کوکی‌ها</li>
                <li><strong>Origin Header Check:</strong> بررسی منبع درخواست</li>
                <li><strong>Re-authentication:</strong> تایید دوباره برای اقدامات حساس</li>
                <li><strong>CAPTCHA:</strong> برای عملیات‌های مهم</li>
            </ul>
        </div>

        <!-- Implementation Guide -->
        <div class="card" style="margin-top: 2rem;">
            <h2>📖 Secure Implementation</h2>
            <pre style="margin: 0; font-size: 0.85rem; overflow-x: auto;">// Generate CSRF Token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// In Form
&lt;input type="hidden" name="csrf_token" 
       value="&lt;?= $_SESSION['csrf_token'] ?&gt;"&gt;

// Validate Token
if (!hash_equals($_SESSION['csrf_token'], 
                 $_POST['csrf_token'])) {
    die('CSRF attack detected!');
}</pre>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="slides.html" style="color: var(--primary-color); text-decoration: none;">
                ← Back to Slides
            </a>
        </div>
    </div>

    <script>
        // Execute CSRF Attack - Developed by Amin Davodian
        function executeCSRF() {
            if (<?php echo $secureMode ? 'true' : 'false'; ?>) {
                alert('🛡️ Attack Blocked!\n\nThe secure mode has CSRF token validation.\nThe attack cannot succeed without a valid token.');
                if(window.logger) window.logger.log('Security', 'CSRF Attack Blocked by Token', 'success');
            } else {
                if (confirm('⚠️ Warning!\n\nThis will transfer $5,000 to Hacker_Account.\n\nIn a real attack, this would happen without user knowledge.\n\nProceed with demo?')) {
                    if(window.logger) window.logger.log('Security', 'CSRF Attack Executed!', 'error');
                    document.getElementById('maliciousForm').submit();
                }
            }
        }

        // Simulate auto-submit attack (disabled for safety)
        // window.onload = function() {
        //     document.getElementById('maliciousForm').submit();
        // };
    </script>
</body>
</html>

