<?php
session_start();
include("configuration.php");

// Only Admin Access
if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied'); window.location.href='login.php';</script>";
    exit();
}

// Data Counts
$totalUsers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM registercms WHERE role != 'Admin'"));
$authors = mysqli_num_rows(mysqli_query($con, "SELECT * FROM registercms WHERE role = 'Author'"));
$reviewers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM registercms WHERE role = 'Reviewer'"));
$attendees = mysqli_num_rows(mysqli_query($con, "SELECT * FROM registercms WHERE role = 'Attendee'"));

$totalPapers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM papers"));
$acceptedPapers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM papers WHERE status = 'Accepted'"));
$rejectedPapers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM papers WHERE status = 'Rejected'"));

$totalReviews = mysqli_num_rows(mysqli_query($con, "SELECT * FROM reviews"));
$pendingReviews = mysqli_num_rows(mysqli_query($con, "SELECT * FROM reviews WHERE feedback_to_author IS NULL OR feedback_to_author = ''"));
$completedReviews = $totalReviews - $pendingReviews;

// Session Registrations
$totalSessionRegistrations = mysqli_num_rows(mysqli_query($con, "SELECT * FROM session_registrations"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            margin: 0;
            padding: 30px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
            font-size: 36px;
        }
        .report-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .accordion {
            background: #ffffff;
            cursor: pointer;
            padding: 20px;
            width: 90%;
            max-width: 700px;
            border: none;
            text-align: left;
            outline: none;
            font-size: 20px;
            transition: background 0.3s;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .accordion:hover {
            background-color: #f1f1f1;
        }
        .active, .accordion:active {
            background-color: #ddd;
        }
        .panel {
            padding: 0 20px;
            background-color: white;
            display: none;
            overflow: hidden;
            width: 90%;
            max-width: 700px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
        .btn-back {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #6a11cb;
            background-image: linear-gradient(315deg, #6a11cb 0%, #2575fc 74%);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            text-align: center;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-back:hover {
            background-image: linear-gradient(315deg, #2575fc 0%, #6a11cb 74%);
        }
    </style>
</head>
<body>

<h2>Admin Report Dashboard</h2>

<div class="report-container">

    <button class="accordion">1. Registered Users</button>
    <div class="panel">
        <div class="data-row"><span class="label">Total Users:</span> <span class="value"><?= $totalUsers ?></span></div>
        <div class="data-row"><span class="label">Authors:</span> <span class="value"><?= $authors ?></span></div>
        <div class="data-row"><span class="label">Reviewers:</span> <span class="value"><?= $reviewers ?></span></div>
        <div class="data-row"><span class="label">Attendees:</span> <span class="value"><?= $attendees ?></span></div>
    </div>

    <button class="accordion">2. Paper Submissions</button>
    <div class="panel">
        <div class="data-row"><span class="label">Total Papers:</span> <span class="value"><?= $totalPapers ?></span></div>
        <div class="data-row"><span class="label">Accepted Papers:</span> <span class="value"><?= $acceptedPapers ?></span></div>
        <div class="data-row"><span class="label">Rejected Papers:</span> <span class="value"><?= $rejectedPapers ?></span></div>
    </div>

    <button class="accordion">3. Review Status</button>
    <div class="panel">
        <div class="data-row"><span class="label">Total Reviews:</span> <span class="value"><?= $totalReviews ?></span></div>
        <div class="data-row"><span class="label">Completed Reviews:</span> <span class="value"><?= $completedReviews ?></span></div>
        <div class="data-row"><span class="label">Pending Reviews:</span> <span class="value"><?= $pendingReviews ?></span></div>
    </div>

    <button class="accordion">4. Session Registrations</button>
    <div class="panel">
        <div class="data-row"><span class="label">Total Session Registered Persons:</span> <span class="value"><?= $totalSessionRegistrations ?></span></div>
    </div>

    <a href="admin.php" class="btn-back">Back to Admin Home</a>

</div>

<script>
    const acc = document.getElementsByClassName("accordion");
    for (let i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            const panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }
</script>

</body>
</html>