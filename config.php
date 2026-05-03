<?php
// 1. بدء الجلسة فقط إذا لم تكن بدأت مسبقاً (لمنع رسائل التنبيه)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$db   = "gpa_management_system";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}

// 2. 🔐 حماية الصلاحيات (مغلفة بشرط لمنع خطأ Cannot redeclare)
if (!function_exists('requireRole')) {
    function requireRole($role) {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit();
        }

        if ($_SESSION['user']['role'] !== $role) {
            die("Access denied ❌");
        }

        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            session_destroy();
            die("Session expired ⏰");
        }

        $_SESSION['last_activity'] = time();
    }
}