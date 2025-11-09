<?php
session_start();
include("configuration.php");

// Only allow admin
if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch submitted papers where status is not Reviewed
$query = "SELECT papers.*, registercms.fullName 
          FROM papers 
          JOIN registercms ON papers.user_id = registercms.register_id
          WHERE papers.status != 'Reviewed'";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submitted Papers</title>
    <link rel="stylesheet" type="text/css" href="logout.css"> <!-- Optional if you already have -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f8fc;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #50105A;
            padding: 15px;
            color: white;
            text-align: center;
        }
        .btncls {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            width: 90%;
            margin: auto;
        }
        .btncls a {
            padding: 10px 20px;
            background-color: #50105A;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btncls a:hover {
            background-color: #6a1b9a;
        }
        h2 {
            text-align: center;
            margin-top: 10px;
            color: #333;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 14px 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #50105A;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        tr:nth-child(even){
            background-color: #f9f9f9;
        }
        a.btn {
            background-color: #007BFF;
            color: white;
            padding: 7px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            margin: 2px;
            display: inline-block;
        }
        a.btn:hover {
            background-color: #0056b3;
        }
        a.download-link {
            color: #28a745;
            text-decoration: underline;
            font-weight: bold;
        }
        a.download-link:hover {
            color: #218838;
        }
    </style>
</head>
<body>

<div class="btncls">
    <a href="admin.php" class="backbtn">Go Back</a>
    <a href="logout.php" class="logout1">Logout</a>
</div>

<h2>Submitted Papers (Pending Review)</h2>

<table>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Author</th>
            <th>Title</th>
            <th>Abstract</th>
            <th>Download</th>
            <th>Submitted On</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sn = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($paper = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$sn}</td>
                    <td>" . htmlspecialchars($paper['fullName']) . "</td>
                    <td>" . htmlspecialchars($paper['title']) . "</td>
                    <td>" . htmlspecialchars($paper['abstract']) . "</td>
                    <td><a href='uploads/" . htmlspecialchars($paper['file_path']) . "' target='_blank' class='download-link'>Download</a></td>
                    <td>" . htmlspecialchars($paper['submitted_at']) . "</td>
                    <td>" . htmlspecialchars($paper['status']) . "</td>
                    <td>
                        <a href='assign_reviewer.php?paper_id={$paper['paper_id']}' class='btn'>Assign Reviewer</a><br>
                        <a href='decision.php?paper_id={$paper['paper_id']}' class='btn'>Make Decision</a>
                    </td>
                </tr>";
                $sn++;
            }
        } else {
            echo "<tr><td colspan='8'>No papers pending review.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>