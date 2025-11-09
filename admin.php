<?php
session_start();
include("configuration.php");

if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}
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
    <link rel="stylesheet" href="design3.css">
</head>
<body>

    <header>
    <div class="logo">
        <h1>Welcome to CMS admin control</h1>
        <nav>
            <ul>
                <li><a href="admin.php">Home</a></li>
                <li><a href="view_user.php"> view user</a></li>
                <li><a href="view_paper.php">view paper</a></li>
                <li><a href="schedule1.php">schedule</a></li> 
                <li><a href="admin_report1.php">Report</a></li>
                <!-- <li><a href="logout.php">Logout</a></li>-->

            </ul>
        </nav>
        <div class="profile" onclick="toggleDropdown()">
        <img src="profile.png" alt="Profile">
        <div class="profile-name"><?php echo htmlspecialchars($username); ?></div>
        <div id="dropdown" class="dropdown">
        <a href="logout.php">Logout</a>
        </div>
    </div>
    </header>
    <div class="quote-container">
        <div class="image">
        <div class="quote-text"> “Coming together is a beginning; keeping together is progress; working together is success.”</div>
      </div>    
</div>
<script>
    function toggleDropdown() {
        var dropdown = document.getElementById('dropdown');
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    }
    window.onclick = function(event) {
        if (!event.target.closest('.profile')) {
            document.getElementById('dropdown').style.display = 'none';
        }
    }
</script>
</body>
</html>

