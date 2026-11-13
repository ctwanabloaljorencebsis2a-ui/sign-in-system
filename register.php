<?php
require_once "config.php";

function h($str){
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

$mess = [];
$errors = [];

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $uname = $_POST['uname'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if($password === $password2){

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, role, status) VALUES (?, ?, 'user', 'pending')");
        $stmt->bind_param('ss', $uname, $hashed_password);

        if($stmt->execute()){
            $mess[] = "Registered successfully! You can now sign in.";
        }else{
            $errors[] = "Something went wrong. Please try again.";
        }

    }else{
        $errors[] = "Passwords should match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Sign In</h1>
    <h2>Create your Account</h2>

    <?php foreach ($errors as $e): ?>
        <p style="color: red;"><?= h($e) ?></p>
    <?php endforeach; ?>

    <?php foreach ($mess as $m): ?>
        <p style="color: green;"><?= h($m) ?></p>
    <?php endforeach; ?>

    <form action="" method="post">

        <label>Username:</label>
        <input type="text" name="uname" placeholder="Enter username" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <br><br>

        <label>Re-enter Password:</label>
        <input type="password" name="password2" placeholder="Re-enter password" required>

        <br><br>

        <input type="submit" value="Register">

    </form>

    <br><br>

    <p>Already have an Account? <a href="index.php">Sign in</a></p>

</body>
</html>