<?php
session_start();
include("configuration.php");

if (!isset($_SESSION['register_id'])) {
     echo "<script>alert('Please login first'); window.location.href='login.php';</script>";
      exit();
}
// Fetch scheduled sessions
$schedules = mysqli_query($con, "SELECT * FROM schedule ORDER BY date, time");
?>

<!DOCTYPE html>
<html>
<head>
<title>User Home</title>
 <style>
body { 
    font-family: Arial, sans-serif;
     background-color:pink;
      padding: 20px; 
    }
h2 { 
    text-align: center;
}
table {
     width: 90%; 
     margin: auto;
      border-collapse: collapse; 
      background: #fff; 
    } 
      th, td {
         padding: 12px; 
         border: 1px solid #ccc; 
         text-align: center; 
        }
 th {
     background-color:50105A; 
     color: white; 
    }
tr:nth-child(even) {
     background-color: #f2f2f2; 
    }
 </style>
</head>
<body>

</div>

<h3 style="text-align:center;">Available Sessions</h3>

<table>
 <tr>
 <th>Session Title</th>
<th>Speaker</th>
 <th>Date</th>
 <th>Time</th>
</tr>
<?php while ($row = mysqli_fetch_assoc($schedules)) { ?>
 <tr>
<td><?= htmlspecialchars($row['session_title']) ?></td>
<td><?= htmlspecialchars($row['speaker']) ?></td>
 <td><?= htmlspecialchars($row['date']) ?></td>
<td><?= htmlspecialchars($row['time']) ?></td>
 </tr>
<?php } 
?>
</table>
</body>
</html>