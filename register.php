<?php
session_start();
include("configuration.php");
if (isset($_POST["submit"])) {
    $fullName = trim($_POST["fullName"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $role = $_POST["role"];
    $adminEmail="cmsdrvk25@gmail.com";
    if($role==="Admin" && $email!==$adminEmail){
      echo "<script>alert('you are not authorized to register as Admin kindly choose other role');window.location.href='register.php';</script>";
      exit();
    }
    if (empty($fullName) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword) || empty($role)) {
        echo "All fields are required.";
        exit();
    }
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }
    $checkQuery = "SELECT * FROM registercms WHERE email = ?";
    $checkStmt = mysqli_prepare($con, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);
    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        echo "<script>alert('This email is already registered. Please use another email.'); window.location.href='register.php';</script>";
        exit();
    }
    mysqli_stmt_close($checkStmt);
    $query = "INSERT INTO registercms (fullName, email, phone, password, Role) VALUES (?,?,?,?,?)";
    $stmt = mysqli_prepare($con, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt,"sssss", $fullName, $email, $phone, $password, $role);
        if (mysqli_stmt_execute($stmt)) 
        {
            echo "<script>alert('Registration successful! You can login now.');window.location.href='login.php';</script>";
        } else {
            echo "Error executing query: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing the statement: " . mysqli_error($con);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Conference Registration</title>
  <style> 
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
     .register {
            background-image: url('http://localhost/home%20page/register.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            width: 100vw;
            height: 100vh;
            position: absolute;
            top: 0;
            left: 0;
    }
     input,select,textarea{
      width:300px;
      height:50px;
      padding:10px;
      border-radius:5px;
      margin: 10px 0;  
     } 
    .register-container { 
    width: 300px;
    padding:10px;
    color:block;
    border:none;
    outline:none;
    border-radius:40px;
    box-shadow: 0 0 10px rgba(0, 0,0,.1);
    cursor:pointer;
    font-size:16px;
    margin-top:70px;
    font-weight: 500;
    }
    .register-container h2 {
      margin-bottom:5px;
      font-size: 24px;
      color:blue;
    }
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"] ,
    input[type="select"]
    {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    input[type="submit"] {
      width: 100%;
      padding: 5px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      margin: 10px 0;
    }
    input[type="submit"]:hover {
      background-color: #30b025;
    }
    .form-group{
      position: relative;
    }
    
    .required-star{
       position:absolute;
       right:10px;
       top:45%;
       transform:translateY(-50%);
       color:red;
       font-size:20px;
       pointer-events:none;
    }
  </style>
</head>
<body>
  <center>
  <div class="register">
  <div class="register-container">
    <h2>Register for Conference</h2>
    <form id="registrationForm" method="POST" action="register.php" >
        <div class="form-group">
          <input style="width: 300px;" type="text" id="fullName" name="fullName" placeholder="Full Name" required>
          <span class="required-star">*</span>   
       </div>
      <div class="form-group">
          <input style="width: 300px;" type="text" id="email" name="email" placeholder="Email Address" required onkeyup="checkEmail()">
          <span class="required-star">*</span>
  </div>
    <div class="form-group">
        <input style="width: 300px;"type="tel" id="phone" name="phone" placeholder="phone Number" required>
        <span class="required-star">*</span>        
    </div>
  <div class="form-group">
        <input style="width: 300px;" type="password" id="password" name="password" placeholder="password" required>
          <span class="required-star">*</span>
  </div>
  <div class="form-group">
        <input style="width: 300px;" type="password" id="confirmPassword" name="confirmPassword" placeholder="confirmPassword" required>
          <span class="required-star">*</span>
  </div>
  <div class="form-group">
      <select id="role" name="role"  required>
        <option value="">---Role---</option>
        <option value="Admin">Admin</option>
        <option value="Author">Author</option>
        <option value="Reviwer">Reviewer</option>
        <option value="Attendee">Attendee</option>
      </select>
      <span class="required-star">*</span>
    </div>
      <input type="submit" name="submit" value="Register">
    </form>
  </center>
  </div>
</div>
<script>
   document.addEventListener("DOMContentLoaded", function () {
      const form = document.getElementById("registrationForm");  
      form.addEventListener("submit", function (event) {
        let errors = [];
        const fullName = document.getElementById("fullName").value.trim();
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirmPassword").value;
        if (fullName === "") {
          errors.push("Full Name is required.");
        } else {
          if (fullName.length > 15) {
            errors.push("Full Name must be 15 characters or fewer.");
          }
          if (!/^[A-Za-z\s]+$/.test(fullName)) {
            errors.push("Full Name can only contain letters and spaces.");
          }
        }
        if (email === "") {
          errors.push("Email is required.");
        } else if (!validateEmail(email)) {
          errors.push("Please enter a valid email address.");
        }
        if (phone === "") {
          errors.push("Phone number is required.");
        } else if (!/^\d{10}$/.test(phone)) {
          errors.push("Phone number must contain exactly 10 digits.");
        }        
        if (password === "") {
        errors.push("Password is required.");
       }
       else {
        if (password.length !== 8) {
          errors.push("Password must be exactly 8 characters long.");
        }
        if (!/(?=.*[a-z])/.test(password)) {
          errors.push("Password must contain at least one lowercase letter.");
        }
        if (!/(?=.*[A-Z])/.test(password)) {
          errors.push("Password must contain at least one uppercase letter.");
        }
        if (!/(?=.*\d)/.test(password)) {
          errors.push("Password must contain at least one digit.");
        }
        if (!/(?=.[!@#$%^&(),.?":{}|<>])/g.test(password)) {
                errors.push("Password must contain at least one special character (e.g., !@#$%^&*).");
        }
      }
        if (confirmPassword === "") {
          errors.push("Confirm Password is required.");
        } else if (password !== confirmPassword) {
          errors.push("Passwords do not match.");
        }
        if (errors.length > 0) {
          event.preventDefault();
          alert(errors.join("\n"));
          return;
        }
        //alert("Registration successful!");
        form.submit();
      });
        function validateEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
      }
    });
    </script>  
</body>
</html>
