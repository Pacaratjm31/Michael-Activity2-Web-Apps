<?php
session_start();
if(!isset($_SESSION['email'])) {
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background: #fff;">
    <div class="box">
        <!-- image -->
        <img src="CoolMasterSword.jpeg" alt="Admin Avatar" style="width:150px; border-radius:10px; margin-bottom: 20px;">

        <h1>Welcome, <span><?= $_SESSION['name'];?></span></h1>
        <p>This is an <span>admin</span> page</p>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </div>
</body>
</html>