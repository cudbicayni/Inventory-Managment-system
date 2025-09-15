<?php
// get_sales_data.php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "invent"; // change this

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Sample query: SUM sales per month (modify to match your schema)
$sql = "SELECT MONTHNAME(sale_date) AS month, SUM(si.total_amount) AS total 
        FROM sales s,  sales_invoice si WHERE si.invo_no=s.invo_no
        GROUP BY MONTH(sale_date) 
        ORDER BY MONTH(sale_date) ASC";

$result = $conn->query($sql);

$data = array("labels" => [], "totals" => []);

while ($row = $result->fetch_assoc()) {
  $data["labels"][] = $row["month"];
  $data["totals"][] = $row["total"];
}

header('Content-Type: application/json');
echo json_encode($data);
$conn->close();
