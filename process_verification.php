<?php
// Connect to database
$pdo = new PDO('mysql:host=localhost;dbname=sos1', 'root', '');

// Retrieve reset code and new password from form
$reset_code = $_POST['reset_code'];
$new_password = $_POST['new_password'];

// Check if code is valid
$stmt = $pdo->prepare('SELECT user_no FROM users WHERE password = ?');
$stmt->execute([$reset_code]);
$user = $stmt->fetch();

if (!$user) {
    // If  code is invalid,  back to reset_password.php with an error message
    header('Location: verify_code.php?error=Invalid reset code');
    exit;
}

// Update user's password in the database
$hashed_password = $new_password;
$stmt = $pdo->prepare('UPDATE users SET password = ?  WHERE user_no = ?');
$stmt->execute([$hashed_password, $user['user_no']]);


header('Location: login.php?message=Password reset successfully');
exit;
//