<?php
session_start();
include("configuration.php");
if (!isset($_SESSION['register_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access denied'); window.location.href='login.php';</script>";
    exit();
}
mysqli_query($con, "DELETE FROM schedule WHERE STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') < NOW()");
if (isset($_POST['add'])) {
    $title = trim($_POST['title']);
    $speaker = trim($_POST['speaker']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    if (!preg_match("/^[A-Za-z0-9 ,.'-]{5,50}$/", $title)) {
        echo "<script>alert('Session title must be 5-50 characters long.');window.location.href='schedule1.php';</script>";
        exit();
    }
    if (strlen($speaker) < 3) {
        echo "<script>alert('Speaker name must be at least 3 characters long.');window.location.href='schedule1.php';</script>";
        exit();
    }
    $datetime = strtotime("$date $time");
    if (!$datetime || $datetime <= time()) {
        echo "<script>alert('Date and time must be a valid future date.');window.location.href='schedule1.php';</script>";
        exit();
    }
    $check_duplicate = mysqli_query($con, "SELECT * FROM schedule WHERE session_title='$title' AND speaker='$speaker' AND date='$date' AND time='$time'");
    if (mysqli_num_rows($check_duplicate) > 0) {
        echo "<script>alert('Duplicate session already exists.');window.location.href='schedule1.php';</script>";
        exit();
    }
    mysqli_query($con, "INSERT INTO schedule (session_title, speaker, date, time) VALUES ('$title', '$speaker', '$date', '$time')");
    echo "<script>alert('Session created successfully.');window.location.href='schedule1.php';</script>";
    exit();
}
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM session_registrations WHERE session_id = $id");
    mysqli_query($con, "DELETE FROM schedule WHERE session_id = $id");
    echo "<script>alert('Session deleted successfully.');window.location.href='schedule1.php';</script>";
    exit();
}
if (isset($_POST['update'])) {
    $id = intval($_POST['session_id']);
    $title = trim($_POST['title']);
    $speaker = trim($_POST['speaker']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    if (!preg_match("/^[A-Za-z0-9 ,.'-]{5,50}$/", $title)) {
        echo "<script>alert('Session title must be 5-50 characters long.');window.location.href='schedule1.php';</script>";
        exit();
    }
    if (strlen($speaker) < 3) {
        echo "<script>alert('Speaker name must be at least 3 characters long.');window.location.href='schedule1.php';</script>";
        exit();
    }
    $datetime = strtotime("$date $time");
    if (!$datetime || $datetime <= time()) {
        echo "<script>alert('Date and time must be a valid future date.');window.location.href='schedule1.php';</script>";
        exit();
    }
    mysqli_query($con, "UPDATE schedule SET session_title='$title', speaker='$speaker', date='$date', time='$time' WHERE session_id=$id");
    echo "<script>alert('Session updated successfully.');window.location.href='schedule1.php';</script>";
    exit();
}
$schedules = mysqli_query($con, "SELECT * FROM schedule ORDER BY date ASC, time ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Schedule</title>
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
            height:  60px;
            position: fixed;
            top: 0;
            left: 0;
            text-align: center;
            z-index: 1000; /* Keeps it above other content */
        }
        h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            margin: auto;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            margin-top:40px;
        }
        h2{
            text-align: center;
            color:  purple;
            margin-bottom: 30px;  
        }
        input, button {
            padding: 10px;
            margin: 8px 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 50%;
            margin-top:10px;
        }
        button {
            background-color: #5A005A;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #800080;
        }
        table {
            width: 90%;
            margin: auto;
            background: #fff;
            border-collapse: collapse;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #5A005A;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        a.action-btn {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            color: white;
        }
        a.edit-btn {
            background-color: #4CAF50;
        }
        a.delete-btn {
            background-color: #f44336;
        }
     .form-group{
      position: relative;
    }
    .required-star{
       position:absolute;
       right:160px;
       top:45%;
       transform:translateY(-50%);
       color:red;
       font-size:20px;
       pointer-events:none;
    }
    </style>
</head>
<body>
<header>
<h1>Manage Conference Schedule</h1>
</header>
<div class="btncls">
    <a href="admin.php" class="backbtn">Go Back</a>
    <a href="logout.php" class="logout1">Logout</a>
</div>
<?php
$edit = false;
$edit_data = ['session_id' => '', 'session_title' => '', 'speaker' => '', 'date' => '', 'time' => ''];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = intval($_GET['edit']);
    $res = mysqli_query($con, "SELECT * FROM schedule WHERE session_id = $id");
    $edit_data = mysqli_fetch_assoc($res);
}
?>
<form method="post">
    <h2>create&edit session</h2>
    <center>
    <input type="hidden" name="session_id" value="<?= $edit_data['session_id'] ?>">
    <div class="form-group">
    <input type="text" name="title" placeholder="Session Title" value="<?= htmlspecialchars($edit_data['session_title']) ?>" required>
    <span class="required-star">*</span>
    </div>
    <div class="form-group">
    <input type="text" name="speaker" placeholder="Speaker Name" value="<?= htmlspecialchars($edit_data['speaker']) ?>" required>
    <span class="required-star">*</span>
    </div>
    <div class="form-group">
    <input type="date" name="date" value="<?= $edit_data['date'] ?>" required>
    <span class="required-star">*</span>
    </div>
    <div class="form-group">
    <input type="time" name="time" value="<?= $edit_data['time'] ?>" required>
    <span class="required-star">*</span>
    </div>
    </center>
    <center><button type="submit" name="<?= $edit ? 'update' : 'add' ?>"><?= $edit ? 'Update' : 'Add' ?> Session</button></center>
</form>
<table>
    <thead>
        <tr>
            <th>Session Title</th>
            <th>Speaker</th>
            <th>Date</th>
            <th>Time</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($schedules) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($schedules)): ?>
            <tr>
                <td><?= htmlspecialchars($row['session_title']) ?></td>
                <td><?= htmlspecialchars($row['speaker']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['time']) ?></td>
                <td>
                    <a href="?edit=<?= $row['session_id'] ?>" class="action-btn edit-btn">Edit</a>
                    <a href="?delete=<?= $row['session_id'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this session?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No sessions scheduled.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>