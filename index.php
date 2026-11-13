<?php
session_start();
require_once "config.php";

function h($str){
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $uname = trim($_POST['uname']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $uname);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password']) && $user['status'] === 'approved') {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header('Location: dashboard.php');
        exit;

    } else {
        $errors[] = "Invalid login or your account is not yet approved.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In</title>

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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #1e293b;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 40px 35px;
            border-radius: 14px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 7px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 13px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            margin-bottom: 20px;
            outline: none;
            font-size: 14px;
        }

        input:focus {
            border-color: #2563eb;
        }

        input[type="submit"] {
            width: 100%;
            padding: 13px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 5px;
        }

        input[type="submit"]:hover {
            background: #1d4ed8;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #64748b;
        }

        .register-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 23px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="login-header">
            <h1>Welcome Back!</h1>
            <p>Sign in to your account</p>
        </div>

        <?php foreach ($errors as $e): ?>
            <div class="error-message">
                <?= h($e) ?>
            </div>
        <?php endforeach; ?>

        <form action="" method="POST">

            <label>Username</label>
            <input
                type="text"
                name="uname"
                placeholder="Enter your username"
                required
                autofocus
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <input type="submit" value="Sign In">

        </form>

        <div class="register-link">
            No account yet? <a href="register.php">Register here</a>
        </div>

    </div>

</body>
</html>