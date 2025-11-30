<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-30
 * 
 * Reset Page Handler - Resets specific page data and reloads
 * Developed by Amin Davodian
 */

session_start();

// Get the page to reset
$page = isset($_GET['page']) ? basename($_GET['page']) : 'dashboard.php';
$secure = isset($_GET['secure']) ? '?secure=1' : '';

// Reset specific page data - Developed by Amin Davodian
switch ($page) {
    case '01_get_post.php':
        // Clear GET/POST data from session
        unset($_SESSION['get_query']);
        unset($_SESSION['post_password']);
        break;
        
    case '02_validation.php':
        // Clear validation data
        unset($_SESSION['form_name']);
        unset($_SESSION['form_email']);
        unset($_SESSION['form_age']);
        break;
        
    case '03_xss_demo.php':
        // Clear XSS demo data
        unset($_SESSION['xss_vulnerable']);
        unset($_SESSION['xss_secure']);
        break;
        
    case '04_live_chat_xss.php':
        // Reset chat messages
        $chatFile = 'chat_messages.json';
        if (file_exists($chatFile)) {
            file_put_contents($chatFile, json_encode([]));
        }
        break;
        
    case '05_sql_injection.php':
        // Reset database
        $dbFile = 'users_db.json';
        if (file_exists($dbFile)) {
            $initialData = [
                ['id' => 1, 'username' => 'admin', 'password' => 'Admin@123', 'role' => 'Administrator'],
                ['id' => 2, 'username' => 'alice', 'password' => 'Alice@456', 'role' => 'User'],
                ['id' => 3, 'username' => 'bob', 'password' => 'Bob@789', 'role' => 'User'],
            ];
            file_put_contents($dbFile, json_encode($initialData, JSON_PRETTY_PRINT));
        }
        break;
        
    case '06_csrf_demo.php':
        // Reset balance and CSRF token
        $_SESSION['balance'] = 10000;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        break;
        
    case '07_file_upload.php':
        // Clear uploaded files
        $uploadDir = 'uploads/';
        if (is_dir($uploadDir)) {
            $files = glob($uploadDir . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        break;
        
    case 'dashboard.php':
        // Clear all session data
        $_SESSION = [];
        break;
        
    case 'slides.html':
        // Clear all session data
        $_SESSION = [];
        break;
        
    default:
        // Clear all session data
        $_SESSION = [];
        break;
}

// Redirect back to the same page
header('Location: ' . $page . $secure);
exit;
?>
