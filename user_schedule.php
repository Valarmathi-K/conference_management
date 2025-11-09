<?php
session_start();
include("configuration.php");
if (!isset($_SESSION['register_id'])) {
    echo "<script>alert('Please login first'); window.location.href='login.php';</script>";
    exit();
}
if(isset($_SESSION['role'])){
  $role=$_SESSION['role'];
}
else{
  $role='user';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_session'])) {
    $session_id = intval($_POST['session_id']);
    $user_id    = $_SESSION['register_id'];
    $check = mysqli_query($con, 
        "SELECT * FROM session_registrations 
         WHERE session_id = $session_id AND user_id = $user_id"
    );
    if (mysqli_num_rows($check) === 0) {
        mysqli_query($con, 
            "INSERT INTO session_registrations (session_id, user_id) 
             VALUES ($session_id, $user_id)"
        );
        echo "<script>alert('You have successfully registered for this session.');</script>";
    } else {
        echo "<script>alert('You are already registered for this session.');</script>";
    }
}
$schedules = mysqli_query($con, "SELECT * FROM schedule ORDER BY date, time");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Home</title>
  <link rel="stylesheet" type="text/css" href="logout.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #ffe6f0;
      padding: 20px;
      margin: 0;
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
        h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }
    h2 {
      text-align: center;
      margin-top:100px;
      margin-bottom: 20px;
      color: #50105A;
    }
    table {
      width: 90%;
      margin: auto;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }
    th {
      background-color: #50105A;
      color: #fff;
      font-size: 16px;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .btn-register {
      padding: 6px 12px;
      background-color:rgb(80, 15, 90);
      color: white;
      text-decoration: none;
      border-radius: 4px;
      border-color:rgb(80, 15, 90);
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .btn-register:hover {
      background-color: #7d3c98;
    }
    /* Modal Styles */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5);
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }
    .modal {
      background: white;
      padding: 20px;
      border-radius: 8px;
      width: 90%;
      max-width: 400px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    }
    .modal h3 {
      margin-top: 0;
      color: #50105A;
      text-align:center;
    }
    .modal label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }
    .modal input[type="text"],
    .modal input[type="email"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .modal button {
      margin-top: 15px;
      padding: 10px 20px;
      background:rgb(93, 22, 104);
      align:center;
      color: white;
      border: none;
      border-radius: 4px;
      border-color:rgb(93, 22, 104);
      cursor: pointer;
      transition: background 0.3s;
    }
    .modal button:hover {
      background: #7d3c98;
    }
    .modal .close-btn {
      background: #aaa;
      float: right;
    }
    .modal .close-btn:hover {
      background: #888;
    }
  </style>
</head>
<body>
<header><h1>Conference schedule</h1></header>
<h2>Available Sessions</h2>
<table>
  <thead>
    <tr>
      <th>Session Title</th>
      <th>Speaker</th>
      <th>Date</th>
      <th>Time</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = mysqli_fetch_assoc($schedules)): ?>
    <tr>
      <td><?= htmlspecialchars($row['session_title']) ?></td>
      <td><?= htmlspecialchars($row['speaker']) ?></td>
      <td><?= htmlspecialchars($row['date']) ?></td>
      <td><?= htmlspecialchars($row['time']) ?></td>
      <td>
        <button 
          class="btn-register" 
          data-session="<?= $row['session_id'] ?>" 
          data-title="<?= htmlspecialchars(addslashes($row['session_title'])) ?>"
        >Register</button>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<div class="modal-overlay" id="modalOverlay">
  <div class="modal">
    <button class="close-btn" id="modalClose">&times;</button>
    <h3 id="modalTitle">Register for Session</h3>
    <form method="POST" action="">
      <input type="hidden" name="session_id" id="modalSessionId">
      <label>Your Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($_SESSION['user'] ?? '') ?>" readonly>
      <label>Email</label>
      <input type="email" name="email" value="<?=htmlspecialchars($_SESSION['email']?? '') ?>" readonly>
      <center><button type="submit" name="register_session">Confirm Registration</button></center>
    </form>
  </div>
</div>
<div class="btncls" style="position:absolute; top:40px;right:20px; display:flex; gap:10px;">
    <?php
    if($role==="Author"){
     echo '<a href="author_home.php" class="backbtn">Go Back</a>';
    }
    else if($role==="Reviewer"){
      echo '<a href="reviewer_home.php" class="backbtn">Go Back</a>';
    }  
    else{
      echo '<a href="user_home.php" class="backbtn">Go Back</a>';
    } ?>
    <a href="logout.php"  class="logout1">Logout</a>
</div>
<script>
  const overlay = document.getElementById('modalOverlay');
  const closeBtn = document.getElementById('modalClose');
  const modalTitle = document.getElementById('modalTitle');
  const modalSessionId = document.getElementById('modalSessionId');
  document.querySelectorAll('.btn-register').forEach(btn => {
    btn.addEventListener('click', () => {
      const sessionId = btn.dataset.session;
      const title = btn.dataset.title;
      modalTitle.textContent = `Register for: ${title}`;
      modalSessionId.value = sessionId;
      overlay.style.display = 'flex';
    });
  });
  closeBtn.addEventListener('click', () => overlay.style.display = 'none');
  overlay.addEventListener('click', e => {
    if (e.target === overlay) overlay.style.display = 'none';
  });
</script>
</body>
</html>