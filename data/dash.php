
<?php include "expect.php"; ?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
  /* === Card Summary === */
  .card-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    border-radius: 10px;
    background: linear-gradient(135deg, #ffffff, #f7f7f7);
    box-shadow: 0 0 10px #f9f9f9;
    margin-bottom: 0;
    transition: transform 0.2s ease-in-out;
  }

  .card-summary:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .summary-icon {
    font-size: 30px;
    padding: 15px;
    border-radius: 50%;
    background: #f9f9f9;
  }

  .summary-box .title {
    font-size: 14px;
    color: #888;
  }

  .summary-box .amount {
    font-size: 20px;
    font-weight: bold;
  }

  /* === Box Card === */
  .box-card {
    color: #fff;
    padding: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    transition: transform 0.2s ease-in-out;
  }

  .box-card:hover {
    transform: translateY(-3px);
  }

  .box-card i {
    font-size: 32px;
  }

  /* === Best Seller Section === */
  .best-seller-list {
    max-width: 400px;
    margin: 20px auto;
    font-family: Arial, sans-serif;
  }

  .best-seller-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
  }

  .best-seller-item img {
    width: 100px;
    height: 100px;
    object-fit: contain;
    margin-right: 15px;
    border: 1px solid #ddd;
    padding: 3px;
    background: #fff;
  }

  .best-seller-item div {
    line-height: 1.2;
  }

  .best-seller-item div strong {
    font-weight: bold;
  }
</style>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<div class="container-fluid">

  <!-- Summary Cards -->
  <div class="row no-gutters">
    <div class="col-md-3 px-2">
      <div class="card-summary bg-light">
        <div class="summary-box">
          <div class="amount text-success">$<?php echo number_format(receivable()); ?></div>
          <div class="title">Total Receivable Due</div>
        </div>
        <div class="summary-icon text-danger bg-light"><i class="fas fa-shopping-bag"></i></div>
      </div>
    </div>

    <div class="col-md-3 px-2">
      <div class="card-summary bg-light">
        <div class="summary-box">
          <div class="amount text-danger">$<?php echo number_format(payable()); ?></div>
          <div class="title">Total Payable Due</div>
        </div>
        <div class="summary-icon text-success bg-light"><i class="fas fa-money-bill-wave"></i></div>
      </div>
    </div>

    <div class="col-md-3 px-1">
      <div class="card-summary bg-light">
        <div class="summary-box">
          <div class="amount text-info">$<?php echo number_format(TotalSales()); ?></div>
          <div class="title">Total Sale Amount</div>
        </div>
        <div class="summary-icon text-info bg-light"><i class="fas fa-arrow-circle-down"></i></div>
      </div>
    </div>

    <div class="col-md-3 px-1">
      <div class="card-summary bg-light">
        <div class="summary-box">
          <div class="amount text-danger">$<?php echo number_format(expences()); ?></div>
          <div class="title">Total Expense Amount</div>
        </div>
        <div class="summary-icon text-danger bg-light"><i class="fas fa-arrow-circle-up"></i></div>
      </div>
    </div>
  </div>

  <!-- Colored Box Cards -->
  <div class="row mt-3">
    <div class="col-md-3">
      <div class="box-card bg-warning">
        <div>
          <h4><?php echo customer(); ?></h4>
          <p>Customers</p>
        </div>
        <i class="fas fa-user"></i>
      </div>
    </div>

 

    <div class="col-md-3">
      <div class="box-card bg-dark">
        <div>
          <h4><?php echo purdash(); ?></h4>
          <p>Purchase </p>
        </div>
        <i class="fas fa-file-invoice"></i>
      </div>
    </div>

<div class="col-md-3">
  <div class="box-card bg-success">
    <div>
     <!-- <?php 
  // $income = dailyAuditToday(); 
  // $color = $income < 0 ? 'red' : 'green';
?> -->
<h4>
  $<?php echo number_format(dailyAuditToday(), 2); ?>
</h4>

      <p>Today's Net Income</p>
    </div>
    <div class="icon">
      <i class="fas fa-calendar-day"></i>
    </div>
  </div>
</div>


</div>

  <!-- Sales Chart -->
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Sales Overview</h5>
          <canvas id="salesChart" width="1000px" height="400px"></canvas>
        </div>
      </div>
    </div>
  </div>
<div style="color:#b55a0b;"><h2>Best Sales</h2></div>
</div>
<?php
// Create a new MySQLi connection directly
$db = new mysqli("localhost", "root", "", "invent");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Your SQL query
$sql = "call GetTopSales()";

// Run query
$result = $db->query($sql);

$rank = 1;
?>

<div style="display: flex; flex-wrap: wrap; gap: 20px;">
<?php while ($row = $result->fetch_array(MYSQLI_ASSOC)) { ?>
    <div style="
        flex: 1 1 calc(20% - 20px);
        max-width: calc(20% - 20px);
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 15px;
        background: #fff;
        font-family: Arial, sans-serif;
        position: relative;
        text-align: center;
        box-sizing: border-box;
    ">
        <div style="
            position: absolute;
            top: 10px;
            left: 10px;
            background: #b55a0b;
            color: white;
            padding: 2px 8px;
            font-weight: bold;
            border-radius: 3px;
            font-size: 14px;
            width: 30px;
            text-align: center;
        ">
            #<?php echo $rank; ?>
        </div>

        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>" 
             style="width: 140px; height: 140px; object-fit: contain; margin-bottom: 15px; border-radius: 8px;">

        <h4 style="font-weight: 600; font-size: 18px; min-height: 50px; margin: 0 0 10px 0;">
            <?php echo htmlspecialchars($row['item_name']); ?>
        </h4>

        <h5 style="color: #e67e22; font-weight: 700; margin: 0 0 6px 0;">
            $<?php echo number_format($row['total_sales_amount'], 2); ?>
        </h5>
    </div>
<?php 
    $rank++;
} 
?>
</div>


<!-- Flex container -->
<div style="display: flex; flex-wrap: wrap; gap: 20px;">

<?php
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    ?>
    <div style="flex: 1 1 calc(20% - 20px); max-width: calc(20% - 20px); border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); padding: 15px; background: #fff; font-family: Arial, sans-serif; position: relative; text-align: center; box-sizing: border-box;">
        <div style="position: absolute; top: 10px; left: 10px; background: #b55a0b; color: white; padding: 2px 8px; font-weight: bold; border-radius: 3px; font-size: 14px; width: 30px; text-align: center;">
            #<?php echo $rank; ?>
        </div>

        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>" 
             style="width: 140px; height: 140px; object-fit: contain; margin-bottom: 15px; border-radius: 8px;">

        <h4 style="font-weight: 600; font-size: 18px; min-height: 50px; margin: 0 0 10px 0;">
            <?php echo htmlspecialchars($row['item_name']); ?>
        </h4>

        <h5 style="color: #e67e22; font-weight: 700; margin: 0 0 6px 0;">
            $<?php echo number_format($row['total_sales_amount'], 2); ?>
        </h5>
    </div>
    <?php
    $rank++;
}
?>
</div>

<!-- Include SweetAlert2 library -->











<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
  $(document).ready(function () {
    $.ajax({
      url: 'data/get_sales_data.php',
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: response.labels,
            datasets: [{
              label: 'Monthly Sales ($)',
              data: response.totals,
              backgroundColor: 'rgba(75, 192, 192, 0.6)',
              borderColor: 'rgba(75, 192, 192, 1)',
              borderWidth: 1,
              borderRadius: 5
            }]
          },
          options: {
            responsive: false,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback: function(value) {
                    return value.toLocaleString();
                  }
                }
              }
            }
          }
        });
      },
      error: function (xhr, status, error) {
        console.log('AJAX Error:', error);
      }
    });
  });
</script>

</body>
</html>
