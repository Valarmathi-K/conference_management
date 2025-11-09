<?php
session_start();
include("configuration.php");
if (!isset($_SESSION['register_id'])) {
    echo "<script>alert('please login first.'); window.location.href='login.php';</script>";
    exit();
    }
    $reviewer_id=$_SESSION['register_id'];
    $role = $_SESSION['role'];
    
    if (!isset($_SESSION['register_id'] )|| $_SESSION['role'] !=="Author" ) {
     echo "<script>alert('Access denied: You are not allowed to access this page.'); window.location.href='home1.php';</script>";
     exit();
    }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $abstract = trim($_POST["abstract"]);
    $file = $_FILES["file"];
    $user_id=$_SESSION["register_id"];
    if (empty($title) || empty($abstract) || empty($file["name"])) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }
    $allowedTypes = ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!in_array($file["type"], $allowedTypes)) {
        echo "only docx files are allowed.".$conn->error;
        exit();
    }
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileName = time() . "_" . basename($file["name"]);
    $filePath = $uploadDir . $fileName;
    if (move_uploaded_file($file["tmp_name"], $filePath)) {
        $query = "INSERT INTO papers (title, abstract, file_path,user_id) VALUES ( ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssi", $title, $abstract, $filePath,$user_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Paper submitted successfully!'); window.location.href='paper_submit.php';</script>";
            } else {
                echo "<script>alert('Database error: " . mysqli_stmt_error($stmt) . "');</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Failed to prepare database statement.');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload file.');</script>";
    }
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper Submission</title>
    <link rel="stylesheet" type="text/css" href="logout.css">
    <link rel="stylesheet" type="text/css" href="paper.css">
</head>
<body>
<div class ='image'>
</div>
<div class="btncls" style="position:absolute;top:20px;right:20px;display:flex;gap:10px;">
      <a href="author_home.php" class="backbtn">Go Back</a>
      <a href="logout.php"  class="logout1">Logout</a>
</div>
   <div class="container">
        <center>
            <h1>Paper Submission</h1>
            <form action="paper_submit.php" method="post" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td><label for="title">Title:</label></td>
                        <td>   
                        <input type="text" id="title" name="title" required>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="abstract">Abstract:</label></td>
                        <td>   
                        <textarea id="abstract" name="abstract" rows="5" required></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="file">Upload File:</label></td>
                        <td>
                        <input type="file" id="file" name="file" accept=".docx" required>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Submit"></td>
                    </tr>
                </table>
            </form>
        </center>
    </div>
<script>
document.querySelector("form").addEventListener("submit", function(event) {
    const title = document.getElementById("title").value.trim();
    const abstract = document.getElementById("abstract").value.trim();
    const fileInput = document.getElementById("file");
    const file = fileInput.files[0];
    let errors = [];
    if (title === "") {
        errors.push("Title is required.");
    }
    if (abstract === "") {
        errors.push("Abstract is required.");
    } 
    else {
    const wordCount = abstract.trim().split(/\s+/).length;
    if (wordCount < 25|| wordCount > 100) {
        errors.push("Abstract must be between 25 to 100 words.");
    }
    }
    if (!file) {
        errors.push("Please upload a file.");
    } else {
        const allowedExtensions = /(\.docx)$/i;
        if (!allowedExtensions.test(file.name)) {
            errors.push("Only .docx files are allowed.");
        }
    }
    if (errors.length > 0) {
        event.preventDefault();
        alert(errors.join("\n"));
    }
});
</script>
</body>
</html>
