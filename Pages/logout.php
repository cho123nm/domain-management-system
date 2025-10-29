<?php
/**
 * Logout Page - Xử lý đăng xuất trực tiếp
 * 
 * @author DAM THANH VU
 * @version 1.0
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Log the logout action
$username = $_SESSION['users'] ?? 'Unknown';
error_log("User logout: " . $username . " at " . date('Y-m-d H:i:s'));

// Clear session
unset($_SESSION['users']);
session_destroy();

// Redirect to home page
header('Location: /');
exit;
?>