<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sos1"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest sos_no (highest sos_no)
$sql = "SELECT sos_no, baby_name FROM registration ORDER BY sos_no DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Check if there is a record
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $sos_no = $row['sos_no']; // Get the latest sos_no
} else {
    die("No records found.");
}

// Now, fetch the details of the latest sos_no
$sql = "SELECT sos_no, baby_name FROM registration WHERE sos_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sos_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("No record found for sos_no: " . $sos_no);
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;a
            height: 100vh;
            background-color: #e0f7e8; /* Light green background */
        }

        .birth-notification {
            width: 300px;
            height: 450px;
            background: #d4f3db; /* Light green card background */
            border: 4px solid rgba(32, 127, 211, 0.87); /* Blue border */
            border-radius: 8px;
            padding: 20px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 180px;
            height: 80px;
            border-radius: 8px;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            color:rgb(24, 146, 223); /* Blue color */
        }

        .form-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 50px;
        }

        .form {
            position: absolute;
            bottom: 200px;
            left: 20px;
            right: 20px;
            text-align: center;
        }

        .form-line {
            border-bottom: 2px solid #000;
            margin: 5px 0;
            height: 1px;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 5px;
            text-align: right;
            font-size: 14px;
            color:rgba(52, 133, 218, 0.76);;
        }
        .no{
            margin-left:250px;
        }
        
    </style>
</head>
<body>
    <div class="birth-notification">
        <div class="header">
            <img src="birth.png"  class="logo" >
            <!-- <div class="header-title">SOS CHILDREN'S VILLAGES</div> -->
            
        </div>
        <div class="No">
            <div class="no">No. _______</div>
        </div>

        <div class="form-title">Birth Notification</div>

        <div class="form">
            <div class="form-line"></div>
        </div>

        <div class="footer">A Loving home for every child</div>
    </div>
</body>
</html>
