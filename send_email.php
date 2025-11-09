<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require Composer's autoloader
require 'vendor/autoload.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form fields
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags($_POST['message']));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'valarmathikaruna1562006@gmail.com'; // Replace with your SMTP username
        $mail->Password   = 'valar@53/;';          // Replace with your SMTP password
        $mail->SMTPSecure = 'PHPMailer::ENCRYPTION-STARTTLS';                    // Enable TLS encryption; 'ssl' also accepted
        $mail->Port       = 587;                      // TCP port to connect to

        // Recipients
        $mail->setFrom('valarmathikaruna1562006@gmail.com', 'MATHI'); // Replace with your email and name
        $mail->addAddress('valarmathikaruna1562006@gmail.com', 'MATHI'); // Replace with recipient's email and name

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body    = "Name:$name\n".
                         "Email:$email\n".
                         "Message:$message";
          
       if($mail->send()){
        echo "Message has been sent successfully.";
       }else{
        echo "Message could not be sent. Mailer Error:" .$mail->ErrorInfo;
       }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request method.";
}
?>