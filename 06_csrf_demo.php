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
    
    // Determine which form was submitted
    $isSecureForm = isset($_POST['csrf_token']);
    
    // Validate inputs first
    if (empty($recipient)) {
        $message = '❌ Recipient name is required!';
    } elseif ($amount <= 0) {
        $message = '❌ Amount must be greater than zero!';
    } elseif ($amount > $_SESSION['balance']) {
        $message = '❌ Insufficient balance!';
    } elseif ($isSecureForm) {
        // SECURE VERSION - Check CSRF token AND mode
        if (!$secureMode) {
            // Block if not in secure mode
            $message = '❌ Transfer blocked! You must be in Secure Mode to use this form.';
        } else {
            $providedToken = $_POST['csrf_token'] ?? '';
            
            if (!hash_equals($_SESSION['csrf_token'], $providedToken)) {
                $message = '❌ CSRF token validation failed! Transfer blocked.';
            } else {
                // Only process transfer if BOTH token is valid AND in secure mode
                $_SESSION['balance'] -= $amount;
                $transferSuccess = true;
                $message = "✅ Successfully transferred $" . number_format($amount, 2) . " to " . htmlspecialchars($recipient);
            }
        }
    } else {
        // VULNERABLE VERSION - No CSRF protection (only in vulnerable mode)
        if (!$secureMode) {
            $_SESSION['balance'] -= $amount;
            $transferSuccess = true;
            $message = "✅ Transferred $" . number_format($amount, 2) . " to " . htmlspecialchars($recipient);
        } else {
            $message = '❌ Transfer blocked in secure mode!';
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

        <div class="transfer-form-container">
            <!-- Vulnerable Transfer Form -->
            <div class="transfer-card vulnerable">
                <h3 style="color: var(--error-color);">❌ Vulnerable Bank</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted);">بدون حفاظت CSRF</p>
                
                <form action="" method="POST" id="vulnerableForm" <?php echo $secureMode ? 'style="opacity:0.5; pointer-events:none;"' : ''; ?>>
                    <div class="form-group">
                        <label for="vulnRecipient">مقصد:</label>
                        <input type="text" name="recipient" id="vulnRecipient" placeholder="نام حساب" required <?php echo $secureMode ? 'disabled' : ''; ?>>
                    </div>
                    <div class="form-group">
                        <label for="vulnAmount">مبلغ ($):</label>
                        <input type="number" name="amount" id="vulnAmount" placeholder="0.00" step="0.01" min="0" required <?php echo $secureMode ? 'disabled' : ''; ?>>
                    </div>
                    <input type="hidden" name="transfer" value="1">
                    <button type="submit" style="background: var(--error-color); border-color: var(--error-color);" <?php echo $secureMode ? 'disabled' : ''; ?>>
                        ارسال (بدون حفاظت)
                    </button>
                </form>
                
                <?php if ($secureMode): ?>
                    <div style="margin-top: 1rem; padding: 1rem; background: rgba(16, 185, 129, 0.2); border-radius: 4px; border: 1px solid var(--primary-color);">
                        <strong style="color: var(--primary-color);">✅ Disabled in Secure Mode</strong>
                        <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">این فرم در حالت امن غیرفعال است.</p>
                    </div>
                <?php endif; ?>

                <div style="margin-top: 1rem; padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 4px; font-size: 0.85rem;">
                    <strong style="color: var(--error-color);">⚠️ مشکل:</strong>
                    <p style="margin: 0.5rem 0 0 0;">هیچ CSRF token وجود ندارد. حمله‌گر می‌تواند فرم را جعل کند.</p>
                </div>
            </div>

            <!-- Secure Transfer Form -->
            <div class="transfer-card secure">
                <h3 style="color: var(--primary-color);">✅ Secure Bank</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted);">با حفاظت CSRF Token</p>
                
                <form action="" method="POST" id="secureForm" <?php echo !$secureMode ? 'style="opacity:0.5; pointer-events:none;"' : ''; ?>>
                    <div class="form-group">
                        <label for="secRecipient">مقصد:</label>
                        <input type="text" name="recipient" id="secRecipient" placeholder="نام حساب" required <?php echo !$secureMode ? 'disabled' : ''; ?>>
                    </div>
                    <div class="form-group">
                        <label for="secAmount">مبلغ ($):</label>
                        <input type="number" name="amount" id="secAmount" placeholder="0.00" step="0.01" min="0" required <?php echo !$secureMode ? 'disabled' : ''; ?>>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <input type="hidden" name="transfer" value="1">
                    <button type="submit" style="background: var(--primary-color); border-color: var(--primary-color); color: #000;" <?php echo !$secureMode ? 'disabled' : ''; ?>>
                        ارسال (محافظت شده)
                    </button>
                </form>
                
                <?php if (!$secureMode): ?>
                    <div style="margin-top: 1rem; padding: 1rem; background: rgba(239, 68, 68, 0.2); border-radius: 4px; border: 1px solid var(--error-color);">
                        <strong style="color: var(--error-color);">❌ Disabled in Vulnerable Mode</strong>
                        <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">برای دیدن حالت امن، روی دکمه "✅ Secure Bank" کلیک کنید.</p>
                    </div>
                <?php endif; ?>

                <div style="margin-top: 1rem; padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 4px; font-size: 0.85rem;">
                    <strong style="color: var(--primary-color);">✅ حفاظت:</strong>
                    <p style="margin: 0.5rem 0 0 0;">CSRF token تصادفی در هر درخواست بررسی می‌شود.</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card" style="margin-top: 2rem; border-top: 4px solid var(--warning-color);">
            <h2 style="color: var(--warning-color);">⚡ Quick Actions</h2>
            <p>پیام‌های آماده برای تست:</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <button onclick="fillVulnerableTransfer('Attacker', 1000)" class="badge" style="background: var(--error-color); color: white; padding: 0.75rem; cursor: pointer; border: none; border-radius: 4px;">
                    💰 Transfer $1,000
                </button>
                <button onclick="fillVulnerableTransfer('Hacker_Account', 5000)" class="badge" style="background: var(--error-color); color: white; padding: 0.75rem; cursor: pointer; border: none; border-radius: 4px;">
                    💀 Transfer $5,000
                </button>
                <button onclick="fillSecureTransfer('Friend', 500)" class="badge" style="background: var(--primary-color); color: #000; padding: 0.75rem; cursor: pointer; border: none; border-radius: 4px;">
                    ✅ Transfer $500 (Secure)
                </button>
                <button onclick="fillSecureTransfer('Charity', 2000)" class="badge" style="background: var(--primary-color); color: #000; padding: 0.75rem; cursor: pointer; border: none; border-radius: 4px;">
                    ✅ Transfer $2,000 (Secure)
                </button>
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
        // Fill vulnerable form - Developed by Amin Davodian
        function fillVulnerableTransfer(recipient, amount) {
            const recipientInput = document.getElementById('vulnRecipient');
            const amountInput = document.getElementById('vulnAmount');
            
            if (recipientInput && amountInput) {
                recipientInput.value = recipient;
                amountInput.value = amount;
                
                [recipientInput, amountInput].forEach(input => {
                    input.style.backgroundColor = '#fffbeb';
                    setTimeout(() => input.style.backgroundColor = '', 500);
                });
                
                if(window.logger) {
                    window.logger.log('Interaction', `Filled Vulnerable Transfer: To=${recipient}, Amount=${amount}`, 'warning');
                }
            }
        }

        // Fill secure form
        function fillSecureTransfer(recipient, amount) {
            const recipientInput = document.getElementById('secRecipient');
            const amountInput = document.getElementById('secAmount');
            
            if (recipientInput && amountInput) {
                recipientInput.value = recipient;
                amountInput.value = amount;
                
                [recipientInput, amountInput].forEach(input => {
                    input.style.backgroundColor = '#fffbeb';
                    setTimeout(() => input.style.backgroundColor = '', 500);
                });
                
                if(window.logger) {
                    window.logger.log('Interaction', `Filled Secure Transfer: To=${recipient}, Amount=${amount}`, 'success');
                }
            }
        }

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

