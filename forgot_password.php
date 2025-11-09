<?php
session_start();
include("configuration.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_email'])) {
    $email = trim($_POST['email']);
    if (empty($email)) {
        $error = "Please enter your registered email.";
    } else {
        $query = "SELECT * FROM registercms WHERE email = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            $_SESSION['reset_email'] = $email;
            header("Location: reset_password.php");
            exit();
        } else {
            $error = "Email not found. Please check again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
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
        input[type="email"], button {
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
        h2{
            text-align:center;
        }
        .form-group{
      position: relative;
    }
    .form-group input,
    .form-group select{
      padding:15px;
      padding-right:20px;
      border:1px solid #ccc;
      border-radius:5px;
      box-sizing:border-box;
    }
    .required-star{
       position:absolute;
       right:15px;
       top:30%;
       transform:translateY(-50%);
       color:red;
       font-size:20px;
       pointer-events:none;
    }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Forgot Password</h2>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <form method="POST" action="">
    <div class="form-group">
        <label for="email">Enter your registered email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit" name="check_email">Submit</button>
        <span class="required-star">*</span>
        </div>
    </form>
</div>
</body>
</html>