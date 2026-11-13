<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f1f5f9;
            min-height: 100vh;
            color: #1e293b;
        }

        .navbar {
            background: #1e293b;
            color: white;
            padding: 20px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            font-size: 21px;
        }

        .navbar span {
            font-size: 14px;
            color: #cbd5e1;
        }

        .container {
            width: 85%;
            max-width: 1100px;
            margin: 40px auto;
        }

        .welcome {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        .welcome h1 {
            font-size: 27px;
            margin-bottom: 10px;
        }

        .welcome p {
            color: #64748b;
            margin-top: 8px;
        }

        .section-title {
            margin-bottom: 18px;
            font-size: 20px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
            transition: 0.2s;
        }


        .card h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .card p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .card a {
            display: inline-block;
            text-decoration: none;
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
        }

        .card a:hover {
            background: #1d4ed8;
        }

        .logout {
            text-decoration: none;
            color: white;
            background: #dc2626;
            padding: 9px 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        .logout:hover {
            background: #b91c1c;
        }

        .footer {
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
            margin-top: 50px;
            padding-bottom: 20px;
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 18px 5%;
            }

            .container {
                width: 90%;
                margin-top: 25px;
            }

            .welcome {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h2>My Dashboard</h2>
        <a class="logout" href="logout.php">Logout</a>
    </div>

    <div class="container">

        <div class="welcome">
            <h1>Hello, <?php echo $username; ?>!</h1>
            <p>Welcome to your dashboard. You have successfully signed in.</p>
            <p>Account Role: <strong><?php echo htmlspecialchars(ucfirst($role)); ?></strong></p>
        </div>

        <h2 class="section-title">Main Menu</h2>

        <div class="cards">

            <div class="card">
                <h3>Dashboard</h3>
                <p>View your account dashboard and information.</p>
                <a href="dashboard.php">Open Dashboard</a>
            </div>

            <?php if ($role === 'admin'): ?>

            <div class="card">
                <h3>User Management</h3>
                <p>Manage, approve, disable, and delete user accounts.</p>
                <a href="users.php">Manage Users</a>
            </div>

            <div class="card">
                <h3>Add User</h3>
                <p>Create a new user account.</p>
                <a href="add_user.php">Add New User</a>
            </div>

            <?php endif; ?>

        </div>

        <div class="footer">
            My Dashboard | Account Management System
        </div>

    </div>

</body>
</html>