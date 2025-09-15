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
$sql = "SELECT * FROM registration ORDER BY sos_no DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Check if there is a record
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("No records found.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  Birth Notification</title>
   
    
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Birth Notification -->
    <div class="birth-notification">
        <div class="header">
            <img src="birth.png" class="logo">
        </div>
        <div class="No">
            <div class="no">No. <?php echo htmlspecialchars($row['sos_no']); ?></div>
        </div>

        <div class="form-title">Birth Notification</div>

        <div class="form">
            <p><strong>Baby Name:</strong> <?php echo htmlspecialchars($row['baby_name']); ?></p>
            
            <div class="form-line"></div>
        </div>

        <div class="footer">A Loving home for every child</div>
    </div>

    <!-- Maternity Invoice -->
    <div class="invoice">
        <div class="invoice-header">
            This is to certify that in the maternity ward of this clinic has delivered<br>
            <small>Waxaa la cadeynayaa in qaybta umulaha ee Isbitaalka ay ku dhashay</small>
        </div>

        <div class="section">
            <div class="left">
                <span><span class="label">Mrs:</span> <?php echo htmlspecialchars($row['Mrs']); ?></span>
                <span><span class="label">Age:</span> <?php echo htmlspecialchars($row['age']); ?></span>
                <span><span class="label">Profession:</span> <?php echo htmlspecialchars($row['Profession']); ?></span>
                <span><span class="label">Hospital Director:</span> <?php echo htmlspecialchars($row['Hospital_Director']); ?></span>
                <span><span class="label">Date:</span> <?php echo htmlspecialchars($row['Tariikh']); ?></span>
                
            </div>
            <div class="right">
                <span><span class="label">Tell:</span> <?php echo htmlspecialchars($row['Tell']); ?></span>
                <span><span class="label">Sex:</span> <?php echo htmlspecialchars($row['gender']); ?></span>
                <span><span class="label">Name of Father:</span> <?php echo htmlspecialchars($row['Father_name']); ?></span>
                <span><span class="label">Profession Father:</span> <?php echo htmlspecialchars($row['Profession_father']); ?></span>
                <span><span class="label">Midwife:</span> <?php echo htmlspecialchars($row['Midwife']); ?></span>
                <!-- <span><span class="label">Baby Name:</span> <?php echo htmlspecialchars($row['baby_name']); ?></span> -->
            </div>
        </div>
        <div class="divider"></div>
        <div class="print-btn" onclick="printDocument()">Print</div>
        
    </div>
   
</body>
<script>
        function printDocument() {
            window.print();
        }
    </script>
</html>
