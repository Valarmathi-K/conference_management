<?php
session_start();
include("configuration.php");
$paperId = $_GET['paper_id'] ?? $_post['paper_id']?? null;
$paper = [];
if ($paperId) {
    $query = "SELECT * FROM papers WHERE paper_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $paperId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $paper = mysqli_fetch_assoc($result);
}
if (!$paperId || empty($paper)) {
    echo "<script>alert('Invalid or missing paperId');window.location.href='paper_list.php';</script>";
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paperId = $_POST['paper_id'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $feedback = trim($_POST['feedback']);
    $adminComments = trim($_POST['admin_comments']);
    $reviewerId = $_SESSION['reviewer_id'] ?? null;
    if (!$reviewerId) {
        echo "<script>alert('Reviewer not logged in');window.location.href='login.php';</script>";
        exit();
    }
    if (empty($paperId) || empty($rating) || empty($feedback)) {
        echo "<script>alert('PaperId, Rating, and Feedback are required.'); window.history.back();</script>";
        exit();
    }
    $stmt = mysqli_prepare($con, "SELECT assign_id FROM reviewer_assignments WHERE paper_id = ? AND reviewer_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $paperId, $reviewerId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $assignment = mysqli_fetch_assoc($result);
    if (!$assignment) {
        echo "<script>alert('You are not assigned to this paper.');window.location.href='review.php';</script>";
        exit();
    }
    $assignmentId = $assignment['assign_id'];
    $stmt = mysqli_prepare($con, "SELECT review_id FROM reviews WHERE assignment_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $assignmentId);
    mysqli_stmt_execute($stmt);
    $reviewExists = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if ($reviewExists) {
        echo "<script>alert('Review already submitted.'); window.location.href='paper_list.php';</script>";
        exit();
    }
    $insertQuery= "INSERT INTO reviews (assignment_id, rating, feedback_to_author, confidential_comments) VALUES (?, ?, ?, ?)";
    $stmt=mysqli_prepare($con,$insertQuery);
    if($stmt){
      mysqli_stmt_bind_param($stmt, "iiss", $assignmentId, $rating, $feedback, $adminComments);
      if (mysqli_stmt_execute($stmt)) {
        $update_status="UPDATE reviewer_assignments
                        SET status='Reviewed'
                        WHERE reviewer_id=$reviewerId 
                        AND paper_id=$paperId ";
        mysqli_query($con,$update_status);
        echo "<script>alert('Review submitted successfully!');window.location.href='paper_list.php';</script>";
        exit();
      } else {
        echo "<script>alert('Database error: " . mysqli_stmt_error($stmt) . "');</script>";
      }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Paper Review Page</title>
  <link rel="stylesheet" type="text/css" href="logout.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #ffe6f0;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 800px;
      margin: auto;
      background:rgba(200, 226, 252, 0.94);
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      margin-top:20px;
    }
    h2 {
      text-align: center;
      color: #333;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      color: #444;
    }
    input[type="text"],
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    textarea {
      resize: vertical;
      height: 100px;
    }
    .button {
      margin-top: 20px;
      text-align: center;
    }
    button {
      padding: 10px 20px;
      background:purple;
      border: none;
      color: #fff;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background:purple;
    }
    .back-button
     {
      display: inline-block;
      padding: 10px 20px;
      font-size: 16px;
      color: white;
      background-color: #007BFF;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .back-button:hover 
    {
      background-color: #0056b3;
    }
    .paper-section {
      background: #eef2f5;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .star-rating {
      direction: rtl;
      display: inline-flex;
      font-size: 30px;
      margin-top: 10px;
    }
    .star-rating input {
      display: none;
    }
    .star-rating label {
      color: #ccc;
      cursor: pointer;
      transition: color 0.2s;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
      color: gold;
    }
    .form-group{
      position: relative;
    }
    .form-group input,
    .form-group select{
      padding:8px;
      padding-right:40px;
      border:1px solid #ccc;
      border-radius:5px;
      box-sizing:border-box;
    }
    .required-star{
       position:absolute;
       right:5px;
       top:30%;
       transform:translateY(-50%);
       color:red;
       font-size:20px;
       pointer-events:none;
    }
  </style>
</head>
<body>
<div class="btncls">
      <a href="paper_list.php" class="backbtn">Go Back</a>
      <a href="logout.php"  class="logout1">Logout</a>
</div>
<div class="container">
  <header>
   <h2>Review Paper</h2>
  </header>
  <div class="paper-section">
    <p><strong>Paper ID:</strong> <?= htmlspecialchars($paper['paper_id'] ?? 'N/A') ?></p>
    <p><strong>Title:</strong> <?= htmlspecialchars($paper['title'] ?? 'N/A') ?></p>
    <p><strong>Abstract:</strong> <?= htmlspecialchars($paper['abstract'] ?? 'N/A') ?></p>
    <?php if (!empty($paper['file_path'])): ?>
      <a href="<?= htmlspecialchars($paper['file_path']) ?>" target="_blank">Download DOCX</a>
    <?php endif; ?>
  </div>
  <form action="review.php?paper_id=<?= htmlspecialchars($paper['paper_id']) ?>" method="POST">
    <input type="hidden" name="paper_id" value="<?= htmlspecialchars($paper['paper_id']) ?>">
    <label for="rating">Rating (1–5 Stars)<span style="left:150px; top:90px; color:red;">*</span></label>
    <div class="star-rating">
      <input type="radio" id="star5" name="rating" value="5"><label for="star5">&#9733;</label>
      <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
      <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
      <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
      <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
    </div>
  <div class="form-group">
    <label for="feedback">Feedback to Author</label>
    <textarea id="feedback" name="feedback" placeholder="Write your comments here..." required></textarea>
    <span class="required-star">*</span>
  </div>
    <label for="admin_comments">Confidential Comments to Admin (optional)</label>
    <textarea id="admin_comments" name="admin_comments" placeholder="Only visible to admin..."></textarea>
<div class="button">
  <button type="submit">Submit Review</button>
</div>
</div>
</form>
</div>
<script> 
document.querySelector("form").addEventListener("submit", function(event) {
  const ratingChecked = document.querySelector('input[name="rating"]:checked');
  const feedback = document.getElementById("feedback").value.trim();
  const adminComments = document.getElementById("admin_comments").value.trim();
  let errors = [];
  if (!ratingChecked) {
    errors.push("Please select a star rating.");
  }
  if (feedback.length < 10) {
    errors.push("Feedback must be at least 10 characters.");
  }
  if (adminComments.length > 500) {
    errors.push("Confidential comments should not exceed 500 characters.");
  }
  if (errors.length > 0) {
    event.preventDefault();
    alert(errors.join("\n"));
  }
});
</script>
</body>
</html>
