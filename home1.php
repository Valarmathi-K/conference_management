<?php
session_start();
include("configuration.php");
/*if (!isset($_SESSION['register_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}*/
$register_id = $_SESSION['register_id'];
$query = mysqli_query($con, "SELECT fullName FROM registercms WHERE register_id = '$register_id'");
$user = mysqli_fetch_assoc($query);
$username = $user['fullName'];
$current_page = basename($_SERVER['PHP_SELF']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Management System</title>
    <link rel="stylesheet" href="design.css">
</head>
<body>
    <header>
        <div class="logo">
        <h1>Welcome to the Conference Management System</h1>
        </div>
        <nav>
            <ul>
                <li><a href="home1.php">Home</a></li>
                <li><a href="about.html">About&contact Us</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="help.html">Help</a></li>
            </ul>
        </nav>
    </header>
    <div class="quote-container">
        <div class="image">
        <div class="quote-text"> “Coming together is a beginning; keeping together is progress; working together is success.”</div>
      </div>          
</div>
</body>
</html>

