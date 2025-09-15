<?php
$mysqli = new mysqli("localhost", "root", "", "invent");
if ($mysqli->connect_error) { http_response_code(500); exit("DB error"); }

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT image FROM items WHERE item_no = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if (!$row || empty($row['image'])) { http_response_code(404); exit("No image"); }

$file = $row['image']; // e.g. "uploads/bati.jpg"

if (!file_exists($file)) { http_response_code(404); exit("File not found"); }

$mime = mime_content_type($file);
header("Content-Type: $mime");
readfile($file);
exit;