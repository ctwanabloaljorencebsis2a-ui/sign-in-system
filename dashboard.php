<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Welcome!</h1>
        <h2>Hello, <?php echo $username; ?>!</h2>

        <p>You have successfully signed in.</p>

        <a href="logout.php">Logout</a>
    </div>

</body>
</html>