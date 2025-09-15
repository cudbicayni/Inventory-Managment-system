<?php
// data/get_image.php
$conn = new mysqli("localhost", "root", "", "invent");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit("Invalid ID");
}

$id = intval($_GET['id']);
$sql = "SELECT image FROM items WHERE item_no = $id";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    $imgData = $row['image'];
    if (!$imgData) {
        http_response_code(404);
        exit("Image not found");
    }

    // Detect MIME type dynamically
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($imgData);

    header("Content-Type: $mime");
    echo $imgData;
} else {
    http_response_code(404);
    exit("Item not found");
}
?>
