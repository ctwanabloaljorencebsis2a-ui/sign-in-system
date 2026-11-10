<?php
require_once "config.php";

function h($str){
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

$errors = [];

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $uname = $_POST['uname'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param('s', $uname);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user && password_verify($password, $user['password'])){
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('location: dashboard.php');
        exit;
    }else{
        $errors[] = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Sign In</h1>
    <h2>Sign in to your account</h2>

    <?php foreach ($errors as $e): ?>
        <p style="color: red;"><?= h($e) ?></p>
    <?php endforeach; ?>

    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="uname" placeholder="Enter username" required autofocus>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <br><br>

        <input type="submit" value="Sign In">
    </form>

    <br>

    <p>No account? <a href="register.php">Register</a></p>

</body>
</html>
