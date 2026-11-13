<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$message = "";
$messageType = "";

if (isset($_POST['add_user'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Please fill in all fields.";
        $messageType = "error";

    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "Username already exists.";
            $messageType = "error";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = "user";
            $status = "pending";

            $stmt = $conn->prepare(
                "INSERT INTO users (username, password, role, status)
                 VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param("ssss", $username, $hashedPassword, $role, $status);

            if ($stmt->execute()) {
                $message = "User added successfully!";
                $messageType = "success";
            } else {
                $message = "Something went wrong.";
                $messageType = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
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

        .nav-links {
            display: flex;
            gap: 10px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            padding: 9px 14px;
            border-radius: 6px;
            background: #334155;
        }

        .nav-links a:hover {
            background: #475569;
        }

        .container {
            width: 90%;
            max-width: 500px;
            margin: 60px auto;
        }

        .form-card {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.06);
        }

        .form-card h1 {
            font-size: 26px;
            margin-bottom: 10px;
        }

        .description {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 28px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 14px;
            outline: none;
        }

        input:focus {
            border-color: #2563eb;
        }

        .submit-btn {
            width: 100%;
            padding: 13px;
            border: none;
            background: #2563eb;
            color: white;
            border-radius: 7px;
            font-size: 15px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .message {
            padding: 12px;
            border-radius: 7px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 18px 5%;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .container {
                margin: 30px auto;
            }

            .form-card {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <h2>Admin Panel</h2>

    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="form-card">

        <h1>Add New User</h1>

        <p class="description">
            Create a new account for your system.
        </p>

        <?php if (!empty($message)): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label>Username</label>
            <input type="text"
                   name="username"
                   placeholder="Enter username"
                   required>

            <label>Password</label>
            <input type="password"
                   name="password"
                   placeholder="Enter password"
                   required>

            <button type="submit"
                    name="add_user"
                    class="submit-btn">
                Create User
            </button>

        </form>

        <a href="users.php" class="back-link">
            ← Back to User Management
        </a>

    </div>

</div>

</body>
</html>