<?php
session_start();
include("configuration.php");
if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}
$papers = mysqli_query($con, "SELECT * FROM papers");
$reviewers = mysqli_query($con, "SELECT * FROM registercms WHERE role = 'Reviewer'");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paper_id = $_POST['paper_id'];
    $reviewer_id = $_POST['reviewer_id'];
    $check = mysqli_query($con, "SELECT * FROM reviewer_assignments WHERE paper_id = '$paper_id' AND reviewer_id = '$reviewer_id'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Reviewer already assigned to this paper');</script>";
    } else {
        $insert = mysqli_query($con, "INSERT INTO reviewer_assignments (paper_id, reviewer_id) VALUES ('$paper_id', '$reviewer_id')");
        if ($insert) {
            echo "<script>alert('Reviewer assigned successfully');</script>";
        } else {
            echo "<script>alert('Failed to assign reviewer');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assign Reviewer</title>
    <link rel="stylesheet" type="text/css" href="logout.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffe6f0;
            margin: 0;
            padding: 20px;
        }
        h2
         { 
            text-align: center;
         }
        form 
        {
             width: 50%;
              margin: auto; 
              background: #fff; 
              padding: 20px;
               border-radius: 8px; 
               box-shadow: 0 0 10px #ccc;
               margin-top:60px;
             }
        label, select, button
         { 
            display: block; 
            width: 100%; 
            margin-top: 10px; 
        }
        button
         {
             padding: 10px;
              background:purple;
               color: #fff; 
               border: none;
                cursor: pointer; 
            }
        button:hover 
        { 
            background:purple;
         }
    </style>
</head>
<body>
    <div class="btncls">
      <a href="view_paper.php" class="backbtn">Go Back</a>
      <a href="logout.php"  class="logout1">Logout</a>
     </div>
    <h2>Assign Reviewer to Paper</h2>
    <form method="POST">
        <label for="paper_id">Select Paper:</label>
        <select name="paper_id" required>
            <option value="">-- Select Paper --</option>
            <?php while ($paper = mysqli_fetch_assoc($papers)) {
                echo "<option value='{$paper['paper_id']}'>" . htmlspecialchars($paper['title']) . "</option>";
            } ?>
        </select>
        <label for="reviewer_id">Select Reviewer:</label>
        <select name="reviewer_id" required>
            <option value="">-- Select Reviewer --</option>
            <?php while ($rev = mysqli_fetch_assoc($reviewers)) {
                echo "<option value='{$rev['register_id']}'>" . htmlspecialchars($rev['fullName']) . " ({$rev['email']})</option>";
            } ?>
        </select>
        <button type="submit">Assign Reviewer</button>
    </form>
</body>
</html>