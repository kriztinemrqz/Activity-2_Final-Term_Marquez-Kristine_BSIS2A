<?php
include 'connection.php';
include 'authenticate.php';

isLoggedIn();
isAdmin();

$error = "";

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$id = (int) $_GET['id'];

// Get existing user
$stmt = mysqli_prepare($connection, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: users.php");
    exit();
}

// Update user
if (isset($_POST['update_user'])) {

    $StudentId = trim($_POST['StudentId']);
    $FirstName = trim($_POST['FirstName']);
    $LastName = trim($_POST['LastName']);
    $email = trim($_POST['email']);
    $course = $_POST['course'];
    $YearLevel = $_POST['YearLevel'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!in_array($role, ['admin', 'student'], true)) {
        $error = "Invalid account role!";
    } else {

        // Check duplicate Student ID or email
        $check = "SELECT id FROM users
                  WHERE (StudentId = ? OR email = ?)
                  AND id != ?";

        $stmt = mysqli_prepare($connection, $check);
        mysqli_stmt_bind_param($stmt, "ssi", $StudentId, $email, $id);
        mysqli_stmt_execute($stmt);

        $duplicate = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($duplicate) > 0) {

            $error = "Student ID or Email already exists!";

        } else {

            if ($password !== "") {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "UPDATE users SET
                        StudentId = ?, FirstName = ?, LastName = ?,
                        email = ?, course = ?, YearLevel = ?,
                        role = ?, password = ?
                        WHERE id = ?";

                $stmt = mysqli_prepare($connection, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssssssi",
                    $StudentId, $FirstName, $LastName,
                    $email, $course, $YearLevel,
                    $role, $hashed_password, $id
                );

            } else {

                $sql = "UPDATE users SET
                        StudentId = ?, FirstName = ?, LastName = ?,
                        email = ?, course = ?, YearLevel = ?,
                        role = ?
                        WHERE id = ?";

                $stmt = mysqli_prepare($connection, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssssi",
                    $StudentId, $FirstName, $LastName,
                    $email, $course, $YearLevel,
                    $role, $id
                );
            }

            if (mysqli_stmt_execute($stmt)) {
                header("Location: users.php");
                exit();
            } else {
                $error = "Failed to update user.";
            }
        }
    }

    // Keep submitted values in the form if there is an error
    $user['StudentId'] = $StudentId;
    $user['FirstName'] = $FirstName;
    $user['LastName'] = $LastName;
    $user['email'] = $email;
    $user['course'] = $course;
    $user['YearLevel'] = $YearLevel;
    $user['role'] = $role;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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

    <h2>Edit User Information</h2>

    <?php if ($error != "") { ?>
        <p style="color:red; text-align:center;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php } ?>

    <form method="POST">

        <label>Student ID</label>
        <input type="text" name="StudentId"
               value="<?= htmlspecialchars($user['StudentId']) ?>" required>

        <label>First Name</label>
        <input type="text" name="FirstName"
               value="<?= htmlspecialchars($user['FirstName']) ?>" required>

        <label>Last Name</label>
        <input type="text" name="LastName"
               value="<?= htmlspecialchars($user['LastName']) ?>" required>

        <label>Email Address</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Course</label>
        <select name="course" required>
            <?php
            $courses = [
                'BSIS' => 'BS Information Systems',
                'BSOM' => 'BS Office Management',
                'BSAIS' => 'BS Accounting Information System',
                'BTVTED' => 'Bachelor of Technical Vocational Teacher Education'
            ];

            foreach ($courses as $value => $label) {
                $selected = ($user['course'] == $value) ? 'selected' : '';
                echo "<option value='$value' $selected>$label</option>";
            }
            ?>
        </select>

        <label>Year Level</label>
        <select name="YearLevel" required>
            <?php
            $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

            foreach ($years as $year) {
                $selected = ($user['YearLevel'] == $year) ? 'selected' : '';
                echo "<option value='$year' $selected>$year</option>";
            }
            ?>
        </select>

        <label>Account Role</label>
        <select name="role" required>
            <option value="student"
                <?= $user['role'] == 'student' ? 'selected' : '' ?>>
                Student
            </option>

            <option value="admin"
                <?= $user['role'] == 'admin' ? 'selected' : '' ?>>
                Admin
            </option>
        </select>

        <label>New Password (Optional)</label>
        <input type="password" name="password"
               placeholder="Leave blank to keep current password">

        <button type="submit" name="update_user">
            Update User
        </button>

    </form>

    <a href="users.php" class="back">Back to User Management</a>

</div>

</body>
</html>
