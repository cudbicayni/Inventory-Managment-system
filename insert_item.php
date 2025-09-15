<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to DB
$host = "localhost";
$user = "root";
$pass = "";
$db = "invent";

$co = new mysqli($host, $user, $pass, $db);
if ($co->connect_error) {
    die("Connection failed: " . $co->connect_error);
}

// Get form data securely
$txt1 = $co->real_escape_string($_POST['i_cat']);
$txt2 = $co->real_escape_string($_POST['i_name']);
$txt3 = floatval($_POST['i_price']);


// Handle file upload
$uploadDir = "image/";
$imgName = time() . "_" . basename($_FILES['txtfile']['name']);
$imgPath = $uploadDir . $imgName;

$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

if (in_array($_FILES['txtfile']['type'], $allowedTypes)) {
    if (move_uploaded_file($_FILES['txtfile']['tmp_name'], $imgPath)) {
       $sql = "INSERT INTO items VALUES (NULL, '$txt1', '$imgPath', '$txt2', '$txt3', 0)";

        $r = $co->query($sql);
        echo $r ? "insert success" : "failed: " . $co->error;
    } else {
        echo "not upload";
    }
} else {
    echo "Fadlan sawir sax ah soo geli (jpeg/png)";
}
?>
