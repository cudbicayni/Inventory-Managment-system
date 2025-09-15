<?php
session_start();

if (!isset($_POST['code'])) {
    header("Location: verify.php");
    exit();
}

$user_code = $_POST['code'];
$real_code = $_SESSION['code'] ?? null;

if ($user_code == $real_code) {
    $_SESSION['verified'] = true;
    header("Location: index.php");
} else {
    echo "<script>alert('Invalid code!'); window.location.href='verify.php';</script>";
}
