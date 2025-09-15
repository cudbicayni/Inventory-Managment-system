<?php
session_start();
$db = new mysqli("localhost", "root", "", "invent");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$username = $_POST['txtusername'] ?? '';
$password = $_POST['txtpassword'] ?? '';

$stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $_SESSION['user'] = $row['username'];
    $_SESSION['user_no'] = $row['user_no'];
    $_SESSION['phone'] = $row['number']; // Phone number column in your table

    // Generate and store verification code
    $code = rand(100000, 999999);
    $_SESSION['code'] = $code;
    $_SESSION['verified'] = false;

    // ✅ Save code and reset verified flag in the DB
    $update = $db->prepare("UPDATE users SET verification_code = ?, verified = 0 WHERE user_no = ?");
    $update->bind_param("si", $code, $row['user_no']);
    $update->execute();
    $update->close();

    // ✅ Send code via UltraMsg
    $token = "io8yqq5ruifk2wgh";
    $instance = "instance137843";
    $phone = $_SESSION['phone'];
    $message = "Your verification code is: $code";

    $url = "https://api.ultramsg.com/instance137843/";
    $data = [
        'token' => $token,
        'to' => $phone,
        'body' => $message
    ];

    $options = [
        "http" => [
            "header" => "Content-type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);

    echo 2; // Go to verify.php
} else {
    // echo 0; // Invalid login
}

$stmt->close();
$db->close();
?>
