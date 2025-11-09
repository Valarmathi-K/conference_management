<?php
session_start();
include("configuration.php");

// Optional: Only allow admin to access this page
if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}

$query = "SELECT * FROM registercms WHERE role != 'Admin'";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Registered Users</title>
    <link rel="stylesheet" type="text/css" href="logout.css">
    <style>
         body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffe6f0;
            margin: 0;
            padding: 20px;
        }
        header {
            background-color:rgb(143, 14, 143);
            color:white;
            padding: 10px;
            margin-top:0px;
            width: 100%;
            height:  50px;
            position: fixed;
            top: 0;
            left: 0;
            text-align: center;
            z-index: 1000; /* Keeps it above other content */
        }
        h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }
        table {
            width: 95%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            margin-top:60px;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color:rgb(103, 22, 116);
            color: #fff;
            font-size: 16px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        tr:nth-child(even){
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <header><h2 align="center">Registered Users </h2></header>
    <div class="btncls">
      <a href="admin.php" class="backbtn">Go Back</a>
      <a href="logout.php"  class="logout1">Logout</a>
     </div>
    <table>
        <tr >
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
        </tr>
    
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($user = mysqli_fetch_assoc($result)) {
                echo  "<tr>
                        <td>{$user['fullName']}</td>
                        <td>{$user['email']}</td>
                        <td>{$user['phone']}</td>
                        <td>{$user['role']}</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4' align='center'>No users found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>