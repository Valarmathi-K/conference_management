<?php
session_start();
include("configuration.php"); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $adminEmail="cmsdrvk25@gmail.com";
    if (empty($email) || empty($password)) {
        echo "<script>alert('Both email and password are required.');</script>";
    } 
    else {
        $query = "SELECT * FROM registercms WHERE email = ?";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($user = mysqli_fetch_assoc($result)) {
                if (($password ===$user['password'])) {
                    $_SESSION['user'] = $user['fullName'];
                    $_SESSION['register_id']=$user["register_id"];
                    $_SESSION['role']=$user['role'];
                    $_SESSION['reviewer_id']=$user["register_id"];
                    $role=$user['role'];
                    $_SESSION['email']=$user['email'];
                   /* $_SESSION['paper_id']=$user['paper_id'];*/
                     if($role==="Admin" && $email===$adminEmail){
                         echo  "<script> alert('Login successful! Welcome, " . $user['fullName'] . "');
                         window.location.href = 'admin.php';
                       </script>";
                         exit();
                    }
                    else if($role==="Author"){
                        echo "<script>alert('Login successful! Welcome, " . $user['fullName'] . "');
                            window.location.href = 'author_home.php';
                          </script>";
                         exit();
                    }
                    else if($role==="Reviewer"){
                      echo "<script>alert('Login successful! Welcome, " . $user['fullName'] . "');
                          window.location.href = 'reviewer_home.php';
                        </script>";
                       exit();
                  }
                  else {
                    echo "<script>alert('Login successful! Welcome, " . $user['fullName'] . "');
                        window.location.href = 'user_home.php';
                      </script>";
                     exit();
                }
                } else {
                    echo "<script>alert('Invalid password.');window.location.href='login.php';</script>";
                }
            } else {
                echo "<script>alert('Email not registered.');window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('Database error: " . mysqli_error($con) . "');window.location.href='login.php';</script>";
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link rel="stylesheet" href="style2.css">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
  <div class="image">
    <form id="loginForm" action="login.php" method ="POST" novalidate>
      <div class="a">
        <center>
          <h1 style="color:aqua">LOGIN FOR CONFERENCE</h1>
        </center>
      </div>
      <div class="b">
        <table class="design">
          <tr>
            <td>
              <div class="input-box">
              <div class="form-group">
                <input type="email" placeholder="Email" id="email" name="email" required>
                <i class='bx bxs-user-circle' ></i>
                <span class="required-star">*</span>
              </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="input-box">
              <div class="form-group">
                <input type="password" placeholder="Password" id="password" name="password" required>
                <i class='bx bxs-lock-alt'></i>
                <span class="required-star">*</span>
              </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="remember-forgot">
                <label>
                  <input type="checkbox"> Remember me
                </label>
                <a href="forgot_password.php" style="margin-left: 80px;">Forgot password?</a>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <center>
                <button type="submit" class="btn">Login</button>
              </center>
            </td>
          </tr>
          <tr>
            <td>
              <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </form>
  </div>
  <script>
   document.getElementById("loginForm").addEventListener("submit", function(event) {
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    let errors = [];
    if (!email) {
        errors.push("Email is required.");
    }
    if (!password) {
        errors.push("Password is required.");
    }
    if (errors.length > 0) {
        event.preventDefault();
        alert(errors.join("\n"));
    }
});
</script>
</body>
</html>