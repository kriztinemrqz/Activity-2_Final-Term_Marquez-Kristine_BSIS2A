<?php
require_once 'connection.php';
require_once 'authenticate.php';

isLoggedIn();
isAdmin();

$totalUsers = $connection->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalStudents = $connection->query("SELECT COUNT(*) AS total FROM users WHERE role = 'student'")->fetch_assoc()['total'];
$totalAdmins = $connection->query("SELECT COUNT(*) AS total FROM users WHERE role = 'admin'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        .navbar {
            display: flex;
            gap: 25px;
            padding: 18px 25px;
            background: #45a049;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .dashboard-content {
            padding: 35px;
        }

        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .stat-card {
            width: 220px;
            padding: 25px;
            color: white;
            text-align: center;
            border-radius: 8px;
        }

        .stat-card h3 {
            margin-bottom: 20px;
        }

        .stat-card p {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }

        .green { background: #45a049; }
        .green { background: #45a049; }
        .green { background: #45a049; }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="users.php">User Management</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main class="dashboard-content">
        <h1>Welcome, Admin!</h1>

        <div class="dashboard-cards">

            <div class="stat-card green">
                <h3>Total Users</h3>
                <p><?php echo $totalUsers; ?></p>
            </div>

            <div class="stat-card green">
                <h3>Total Students</h3>
                <p><?php echo $totalStudents; ?></p>
            </div>

            <div class="stat-card green">
                <h3>Total Admins</h3>
                <p><?php echo $totalAdmins; ?></p>
            </div>

        </div>
    </main>

</body>
</html>