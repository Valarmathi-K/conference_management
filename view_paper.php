<?php
session_start();
include("configuration.php");
if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}
$query = "SELECT papers.*,registercms.fullName 
FROM papers JOIN registercms ON papers.user_id=registercms.register_id";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submitted Papers</title>
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
            z-index: 1000;
        }
        h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }
        h3 {
            text-align: center;
            color: purple;
            letter-spacing: 2px;
            margin-top:50;
        }
        table {
            width: 95%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
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
        .btn {
            background-color:rgb(44, 73, 189);
            padding: 8px 15px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color:rgb(121, 146, 209);
        }
    </style>
</head>
<body>
    <header><h1>Submitted Papers<h1></header>
    <div class="btncls">
      <a href="admin.php" class="backbtn">Go Back</a>
      <a href="logout.php"  class="logout1">Logout</a>
     </div>
    <h3>assign reviwer and decision</h3>
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
                        <td><a href=" .htmlspecialchars($paper['file_path'])." target='_blank'>Download</a></td>
                        <td>" . htmlspecialchars($paper['submitted_at']) . "</td>
                        <td>" . htmlspecialchars($paper['status']) . "</td>
                        <td>
                            <a href='assign_reviewer.php?paper_id={$paper['paper_id']}' class='btn'>Assign</a>
                            <a href='decision.php?paper_id={$paper['paper_id']}' class='btn'>Decision</a>
                        </td>
                    </tr>";
                    $sn++;
                }
            } else {
                echo "<tr><td colspan='7' align='center'>No papers submitted yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>