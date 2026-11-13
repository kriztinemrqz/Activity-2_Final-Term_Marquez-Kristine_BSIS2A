<?php
include 'connection.php';
include 'authenticate.php';

isLoggedIn();
isAdmin();

$sql = "SELECT id, StudentId, FirstName, LastName, email,
        course, YearLevel, role FROM users ORDER BY id ASC";

$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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

        .container {
            padding: 30px;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #45a049;
            color: white;
        }

        .add-btn {
            display: inline-block;
            background: #254e06;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .edit-btn,
        .delete-btn {
            padding: 6px 10px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .edit-btn {
            background: #3157d5;
            margin-right: 8px;
            border: none;
            cursor: pointer;
        }

        .delete-btn {
            background: #c43d4b;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="admin.php">Dashboard</a>
        <a href="users.php">User Management</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h1>User Management</h1>
        <p>Manage registered student and admin accounts.</p>

        <a href="add_user.php" class="add-btn">+ Add New User</a>

        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Year Level</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>

                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['StudentId']) ?></td>
                            <td><?= htmlspecialchars($row['FirstName']) ?></td>
                            <td><?= htmlspecialchars($row['LastName']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['course']) ?></td>
                            <td><?= htmlspecialchars($row['YearLevel']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>

                            <td>
                                <a class="edit-btn" href="edit_user.php?id=<?= $row['id'] ?>">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="delete_user.php"
                                      onsubmit="return confirm('Are you sure you want to delete this user?');"
                                      style="display: inline;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

                                    <button type="submit" class="delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="9">No users found.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>