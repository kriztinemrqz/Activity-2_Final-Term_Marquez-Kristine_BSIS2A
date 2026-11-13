<?php
include 'connection.php';
include 'authenticate.php';

isLoggedIn();
isAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: users.php");
    exit();
}

if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    header("Location: users.php");
    exit();
}

$id = (int) $_POST['id'];

// Prevent admin from deleting their own account
if ($id === (int) $_SESSION['user_id']) {
    header("Location: users.php?error=self");
    exit();
}

$sql = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: users.php?deleted=1");
    exit();
} else {
    header("Location: users.php?error=delete");
    exit();
}
?>