<?php
include 'connection.php';
include 'authenticate.php';

isLoggedIn();
isAdmin();

$error = "";
$success = "";

if (isset($_POST['add_user'])) {

    $StudentId = trim($_POST['StudentId']);
    $FirstName = trim($_POST['FirstName']);
    $LastName = trim($_POST['LastName']);
    $email = trim($_POST['email']);
    $course = $_POST['course'];
    $YearLevel = $_POST['YearLevel'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!in_array($role, ['admin', 'student'], true)) {
        $error = "Invalid account role!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {

        $check = "SELECT id FROM users
                  WHERE StudentId = ? OR email = ?";

        $stmt = mysqli_prepare($connection, $check);
        mysqli_stmt_bind_param($stmt, "ss", $StudentId, $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            $error = "Student ID or Email already exists!";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users
                    (StudentId, FirstName, LastName, email,
                     course, YearLevel, password, role)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($connection, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ssssssss",
                $StudentId,
                $FirstName,
                $LastName,
                $email,
                $course,
                $YearLevel,
                $hashed_password,
                $role
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = "User successfully added!";
            } else {
                $error = "Failed to add user. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        .navbar {
            background: #45a049;
            padding: 18px 25px;
            display: flex;
            gap: 25px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .form-container {
            width: 420px;
            max-width: calc(100% - 60px);
            margin: 35px auto;
            padding: 25px;
            background: white;
            border-radius: 8px;
        }

        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 11px;
            margin-top: 20px;
            background: #45a049;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            text-align: center;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <a href="admin.php">Dashboard</a>
    <a href="users.php">User Management</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="form-container">

    <h2>Add New User</h2>

    <?php if ($error != "") { ?>
        <p class="message" style="color:red;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php } ?>

    <?php if ($success != "") { ?>
        <p class="message" style="color:green;">
            <?= htmlspecialchars($success) ?>
        </p>
    <?php } ?>

    <form method="POST">

        <label>Student ID</label>
        <input type="text" name="StudentId" required>

        <label>First Name</label>
        <input type="text" name="FirstName" required>

        <label>Last Name</label>
        <input type="text" name="LastName" required>

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Course</label>
        <select name="course" required>
            <option value="">Select Course</option>
            <option value="BSIS">BS Information Systems</option>
            <option value="BSOM">BS Office Management</option>
            <option value="BSAIS">BS Accounting Information System</option>
            <option value="BTVTED">Bachelor of Technical Vocational Teacher Education</option>
        </select>

        <label>Year Level</label>
        <select name="YearLevel" required>
            <option value="">Select Year Level</option>
            <option value="1st Year">1st Year</option>
            <option value="2nd Year">2nd Year</option>
            <option value="3rd Year">3rd Year</option>
            <option value="4th Year">4th Year</option>
        </select>

        <label>Password</label>
        <input type="password" name="password" minlength="8" required>

        <label>Account Role</label>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" name="add_user">Add User</button>

    </form>

    <a href="users.php" class="back">Back to User Management</a>

</div>

</body>
</html>