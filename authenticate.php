<?php 

if (session_status () === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
}

function isAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: admin.php");
        exit();
    }
}

function isStudent() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
        header("Location: student.php");
        exit();
    }
}

?>