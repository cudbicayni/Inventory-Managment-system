<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Include PHPMailer autoloader
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require 'PHPMailer/src/Exception.php';

    // Connect to the database
    $pdo = new PDO('mysql:host=localhost;dbname=sos1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve email address from the form
    $email = $_POST['email'];

    // Check if email address is registered
    $stmt = $pdo->prepare('SELECT user_no FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        // Email not found
        header('Location: forget.php?error=' . urlencode('Email address not found'));
        exit;
    }

    // Generate a random reset code
    $reset_code = bin2hex(random_bytes(3)); // 6-character reset code
    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE user_no = ?');
    $stmt->execute([$reset_code, $user['user_no']]);

    // Send reset code to user's email
    $mail = new PHPMailer(true);

    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cudbiabdullahi1@gmail.com'; // Your Gmail address
    $mail->Password = 'uqklbkrswqtcwalu';   // Your Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email settings
    $mail->setFrom('cudbiabdullahi1@gmail.com', 'sos  MANAGEMENT SYSTEM');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Code';
    $mail->Body = 'Your password reset code is: <strong>' . $reset_code . '</strong>';

    $mail->send();

    // Redirect to reset_password.php
    header('Location: verify_code.php?email=' . urlencode($email));
    exit;

} catch (Exception $e) {
    // Log email errors
    error_log('Mailer Error: ' . $e->getMessage());
    header('Location: forget.php?error=' . urlencode('Email failed to send. Please try again later.'));
    exit;

} catch (PDOException $e) {
    // Log database errors
    error_log('Database Error: ' . $e->getMessage());
    header('Location: forget.php?error=' . urlencode('Internal server error. Please try again later.'));
    exit;
}