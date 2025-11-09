<?php
session_start();
include("configuration.php");
if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}
if (isset($_POST['decision'])) {
    $paper_id = $_POST['paper_id'];
    $decision = $_POST['decision'];
    mysqli_query($con, "UPDATE papers SET status = '$decision' WHERE paper_id = '$paper_id'");
    echo "<script>alert('Decision updated successfully');</script>";
}
$query = "
SELECT 
    p.paper_id,
    p.title,
    p.status,
    GROUP_CONCAT(u.fullName SEPARATOR ', ') AS reviewers,
    GROUP_CONCAT(r.rating SEPARATOR ', ') AS ratings,
    GROUP_CONCAT(r.feedback_to_author SEPARATOR ' || ') AS feedbacks,
    COUNT(DISTINCT ra.assign_id) AS total_assignments,
    COUNT(DISTINCT r.review_id) AS total_reviews
FROM papers p
LEFT JOIN reviewer_assignments ra ON p.paper_id = ra.paper_id
LEFT JOIN reviews r ON r.assignment_id = ra.assign_id
LEFT JOIN registercms u ON ra.reviewer_id = u.register_id
GROUP BY p.paper_id
ORDER BY p.paper_id DESC";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Review Decisions</title>
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
            margin-top:70px;
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
            font-size: 12px;
            transition: background-color 0.3s;
            border-color:rgb(44, 73, 189);
            margin-left:10px;
        }
        .btn:hover {
            background-color:rgb(121, 146, 209);
        }

        form {
            display: inline;
        }
        select, button {
            padding: 5px;
        }
    </style>
</head>
<body>
    <header><h2>Paper Reviews & Decisions</h2></header>
    <div class="btncls">
        <a href="view_paper.php" class="backbtn">Go Back</a>
        <a href="logout.php"  class="logout1">Logout</a>
    </div>

    <table>
        <tr>
            <th>Paper Title</th>
            <th>Reviewer(s)</th>
            <th>Rating(s)</th>
            <th>Feedback(s)</th>
            <th>Status</th>
            <th>Make Decision</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $status_display = ($row['total_assignments'] == $row['total_reviews'] && $row['total_assignments'] > 0)
                ? $row['status'] : "Pending";
            echo "<tr>";
            echo "<td>{$row['title']}</td>";
            echo "<td>{$row['reviewers']}</td>";
            echo "<td>{$row['ratings']}</td>";
            echo "<td>{$row['feedbacks']}</td>";
            echo "<td>$status_display</td>";
            echo "<td>";
                if ($status_display === "Pending") {
                 echo "<span style='color: grey;'>Waiting for all reviews</span>";
                } 
                else {
                 echo "<form method='post'>
                 <input type='hidden' name='paper_id' value='{$row['paper_id']}'>
                 <select name='decision'>
                    <option value='Accepted'>Accept</option>
                    <option value='Rejected'>Reject</option>
                </select>
                <button type='submit' class='btn'>Update</button>
              </form>";
                }
            echo "</td>";
            
        }
        ?>
    </table>
</body>
</html>