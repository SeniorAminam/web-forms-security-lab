<?php
/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-30
 * 
 * Reset All Handler - Clears all session data and temporary files
 * Developed by Amin Davodian
 */

session_start();

// Clear all session data - Developed by Amin Davodian
$_SESSION = [];
session_destroy();

// Reset chat messages
$chatFile = 'chat_messages.json';
if (file_exists($chatFile)) {
    file_put_contents($chatFile, json_encode([]));
}

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

// Redirect back to slides
header('Location: slides.html');
exit;
?>
