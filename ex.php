<?php
session_start();
include("configuration.php");

// Only allow admin
if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch submitted papers
$query = "SELECT papers.*, registercms.fullName 
          FROM papers 
          JOIN registercms ON papers.user_id = registercms.register_id";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submitted Papers</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #f8cdda, #1d2b64);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
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
            background-color: #50105A;
            color: #fff;
            font-size: 16px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        tr:nth-child(even){
            background-color: #f9f9f9;
        }
        .btncls {
            display: flex;
            justify-content: flex-end;
            margin: 10px 50px;
        }
        .btncls a {
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #50105A;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btncls a:hover {
            background-color: #7d3c98;
        }
        .btn {
            background-color: #1d2b64;
            padding: 8px 15px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #344675;
        }
    </style>
</head>
<body>
    <div class="btncls">
        <a href="admin.php" class="backbtn">Go Back</a>
        <a href="logout.php" class="logout1">Logout</a>
    </div>

    <h2>Submitted Papers</h2>

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
                <th>Actions</th>
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
                        <td><a href='" . htmlspecialchars($paper['file_path']) . "' target='_blank' class='btn'>Download</a></td>
                        <td>" . htmlspecialchars($paper['submitted_at']) . "</td>
                        <td>" . htmlspecialchars($paper['status']) . "</td>
                        <td>
                            <a href='assign_reviewer.php?paper_id={$paper['paper_id']}' class='btn'>Assign</a>
                            <a href='decision.php?paper_id={$paper['paper_id']}' class='btn' style='margin-left:5px;'>Decision</a>
                        </td>
                    </tr>";
                    $sn++;
                }
            } else {
                echo "<tr><td colspan='8' style='text-align:center;'>No papers submitted yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>