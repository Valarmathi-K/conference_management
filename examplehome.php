<?php
session_start();
include("configuration.php");

// Check login
if (!isset($_SESSION['register_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch user info
$register_id = $_SESSION['register_id'];
$query = mysqli_query($con, "SELECT fullName FROM registercms WHERE register_id = '$register_id'");
$user = mysqli_fetch_assoc($query);
$username = $user['fullName'];

// Detect current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conference Management System</title>
    <link rel="stylesheet" href="design.css">
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007BFF;
            padding: 15px 30px;
            color: white;
            position: relative;
        }
        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        nav ul li a.active {
            background-color: white;
            color: #007BFF;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .profile {
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
        }
        .profile-name {
            font-weight: bold;
            user-select: none;
        }
        .dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background: white;
            color: black;
            border: 1px solid #ccc;
            border-radius: 5px;
            min-width: 150px;
            z-index: 999;
        }
        .dropdown a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: black;
        }
        .dropdown a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">
        <h1>Conference Management System</h1>
    </div>

    <nav>
        <ul>
            <li><a href="home1.php" class="<?php if($current_page == 'home1.php') echo 'active'; ?>">Home</a></li>
            <li><a href="about.html" class="<?php if($current_page == 'about.html') echo 'active'; ?>">About & Contact Us</a></li>
            <li><a href="register.php" class="<?php if($current_page == 'register.php') echo 'active'; ?>">Register</a></li>
            <li><a href="login.php" class="<?php if($current_page == 'login.php') echo 'active'; ?>">Login</a></li>
            <li><a href="user_schedule.php" class="<?php if($current_page == 'user_schedule.php') echo 'active'; ?>">Schedule</a></li>
            <li><a href="paper_submit.php" class="<?php if($current_page == 'paper_submit.php') echo 'active'; ?>">Submit Paper</a></li>
            <li><a href="paper_list.php" class="<?php if($current_page == 'paper_list.php') echo 'active'; ?>">Review Page</a></li>
        </ul>
    </nav>

    <div class="profile" onclick="toggleDropdown()">
        <img src="profile.png" alt="Profile">
        <div class="profile-name"><?php echo htmlspecialchars($username); ?></div>

        <div id="dropdown" class="dropdown">
            <a href="profile.php">View Profile</a> <!-- optional profile page -->
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>

<script>
    function toggleDropdown() {
        var dropdown = document.getElementById('dropdown');
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    }

    // Close dropdown if clicked outside
    window.onclick = function(event) {
        if (!event.target.closest('.profile')) {
            document.getElementById('dropdown').style.display = 'none';
        }
    }
</script>
</body>
</html>