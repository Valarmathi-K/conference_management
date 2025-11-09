<?php
session_start();
include("configuration.php");
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}
$email = $_SESSION['reset_email'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    if (empty($newPassword) || empty($confirmPassword)) {
        $error = "Please fill all fields.";
    } 
    elseif ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    }
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newPassword)) {
        $error = "Password must be at least 8 characters long, include 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.";
     } 
    else {
        $query = "UPDATE registercms SET password = ? WHERE email = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $newPassword, $email);
        if (mysqli_stmt_execute($stmt)) {
            unset($_SESSION['reset_email']);
            echo "<script>alert('Password reset successfully! Please login.'); window.location.href='login.php';</script>";
            exit();
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        input[type="password"], button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
        }
        button {
            background: teal;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: darkcyan;
        }
        .error {
            color: red;
        }
        .form-group{
      position: relative;
    }
    .form-group input,
    .form-group select{
      padding:8px;
      padding-right:20px;
      border:1px solid #ccc;
      border-radius:5px;
      box-sizing:border-box;
    }
    .required-star{
       position:absolute;
       right:15px;
       top:60%;
       transform:translateY(-50%);
       color:red;
       font-size:20px;
       pointer-events:none;
    }
    h2{
        text-align:center;
    }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Reset Password</h2>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <form method="POST" action="">
       <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
        <span class="required-star">*</span>
        </div>
        <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <span class="required-star">*</span>
        </div>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
</div>
</body>
</html>