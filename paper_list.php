<?php
session_start();
include("configuration.php");
if (!isset($_SESSION['register_id'])) {
  echo "<script>alert('please login first.'); window.location.href='login.php';</script>";
  exit();
  }
  $reviewer_id=$_SESSION['register_id'];
  $role = $_SESSION['role'];
  
  if (!isset($_SESSION['register_id'] )|| $_SESSION['role'] !=="Reviewer" ) {
   echo "<script>alert('Access denied: You are not allowed to access this page.'); window.location.href='home1.php';</script>";
   exit();
  }
$reviewer_id=$_SESSION['register_id'];
$query = "SELECT p.paper_id, p.title, p.abstract
          FROM papers p
          INNER JOIN reviewer_assignments r ON p.paper_id = r.paper_id
          WHERE r.reviewer_id = $reviewer_id AND r.status='pending'";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Paper List</title>
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
        h3 {
            text-align: center;
            color: purple;
            font-size:25px;
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
    a.button 
    {
      background-color: #4CAF50;
      color: white;
      padding: 8px 16px;
      text-decoration: none;
      border-radius: 5px;
    }
    a.button:hover 
    {
      background-color: #45a049;
    }
    button:hover 
    {
      background:purple;
    }
  </style>
</head>
<body>
  <header>
  <h2>Assigned papers</h2>
  </header>
  <div class="btncls" style="margin-top:40px;">
      <a href="reviewer_home.php" class="backbtn">Go Back</a>
      <a href="logout.php"  class="logout1">Logout</a>
  </div>
  <h3>click the review button to review</h3>
  <table>
    <thead>
      <tr>
        <th>Paper ID</th>
        <th>Title</th>
        <th>Abstract</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php if(mysqli_num_rows($result)>0):?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= htmlspecialchars($row['paper_id']) ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['abstract']) ?></td>
        <td><a class="button" href="review.php?paper_id=<?= $row['paper_id'] ?>">Review</a></td>
      </tr>
    <?php endwhile; ?>
    <?php else:?>
      <tr>
        <td colspan="4">no papers assigned for review.</td>
    </tr>
    <?php endif;?>
    </tbody>
  </table>  
  </body>
</html>