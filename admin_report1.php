<?php
session_start();
include("configuration.php");

// Only Admin Access
if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied'); window.location.href='login.php';</script>";
    exit();
}

// Registered users count
$totalUsers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM registercms WHERE role != 'Admin'"));
$authors = mysqli_num_rows(mysqli_query($con, "SELECT * FROM registercms WHERE role = 'Author'"));
$reviewers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM registercms WHERE role = 'Reviewer'"));
$attendees = mysqli_num_rows(mysqli_query($con, "SELECT * FROM registercms WHERE role = 'Attendee'"));

// Papers
$totalPapers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM papers"));
$acceptedPapers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM papers WHERE status = 'Accepted'"));
$rejectedPapers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM papers WHERE status = 'Rejected'"));

// Reviews
$totalReviews = mysqli_num_rows(mysqli_query($con, "SELECT * FROM reviews"));
$pendingReviews = mysqli_num_rows(mysqli_query($con, "SELECT * FROM reviews WHERE feedback_to_author IS NULL OR feedback_to_author = ''"));
$completedReviews = $totalReviews - $pendingReviews;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Report</title>
    <link rel="stylesheet" type="text/css" href="logout.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffe6f0;
            margin: 0;
            padding: 30px;
        }
        header{
        background-color:rgb(161, 49, 161);
            color:white;
            padding: 10px;
            margin-top:0px;
            width: 100%;
            height:  60px;
            position: fixed;
            top: 0;
            left: 0;
            text-align: center;
            z-index: 1000; 
        }
        h1{
            text-align: center;
            color: #fff;
            margin-bottom: 40px;
            font-size: 30px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
            font-size: 30px;
        }
        .report-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }
        .report-section {
            background: #ffffff;
            padding: 25px 30px;
            width: 90%;
            max-width: 700px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .report-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }
        .report-section h3 {
            margin-bottom: 20px;
            color: #444;
            font-size: 24px;
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .data-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px dashed #ccc;
        }
        .data-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #222;
            font-weight: 600;
        }
    </style>
</head>
<body>
<header><h1>Admin Dashboard</h1></header>
<div class="btncls">
    <a href="admin.php" class="backbtn">Go Back</a>
    <a href="logout.php" class="logout1">Logout</a>
</div>
<h2> Report</h2>
<div class="report-container">

    <div class="report-section">
        <h3> Registered Users</h3>
        <div class="data-row"><span class="label">Total Users:</span> <span class="value"><?= $totalUsers ?></span></div>
        <div class="data-row"><span class="label">Authors:</span> <span class="value"><?= $authors ?></span></div>
        <div class="data-row"><span class="label">Reviewers:</span> <span class="value"><?= $reviewers ?></span></div>
        <div class="data-row"><span class="label">Attendees:</span> <span class="value"><?= $attendees ?></span></div>
    </div>

    <div class="report-section">
        <h3> Paper Submissions</h3>
        <div class="data-row"><span class="label">Total Papers:</span> <span class="value"><?= $totalPapers ?></span></div>
        <div class="data-row"><span class="label">Accepted Papers:</span> <span class="value"><?= $acceptedPapers ?></span></div>
        <div class="data-row"><span class="label">Rejected Papers:</span> <span class="value"><?= $rejectedPapers ?></span></div>
    </div>

    <div class="report-section">
        <h3> Review Status</h3>
        <div class="data-row"><span class="label">Total Reviews:</span> <span class="value"><?= $totalReviews ?></span></div>
        <div class="data-row"><span class="label">Completed Reviews:</span> <span class="value"><?= $completedReviews ?></span></div>
        <div class="data-row"><span class="label">Pending Reviews:</span> <span class="value"><?= $pendingReviews ?></span></div>
    </div>
</div>

</body>
</html>