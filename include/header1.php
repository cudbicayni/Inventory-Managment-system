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
<?php echo number_format(TotalSales()); ?>
  <div class="col-md-3 px-1">
      <div class="card-summary bg-light">
        <div class="summary-box">
          <div class="amount text-info">$<?php echo number_format(TotalSales()); ?></div>
          <div class="title">Total Sale Amount</div>
        </div>
        <div class="summary-icon text-info bg-light"><i class="fas fa-arrow-circle-down"></i></div>
      </div>
    </div>
//top sales
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
<!-- chart -->
 <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
  $(document).ready(function () {
    $.ajax({
      url: '',
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
<!-- cards -->
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
      <div class="box-card bg-info">
        <div>
          <h4><?php echo supplier(); ?></h4>
          <p>Suppliers</p>
        </div>
        <i class="fas fa-user-check"></i>
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

</div>

<!-- customer & supp -->
 <div class="tab-pane fade" id="cus" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_customer" data-toggle="modal" data-target="#mdl_customer">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_customer" class="tbl_cls_customer">
        <?php $co->Table("call customers_view()", "dt_customer", "n"); ?>
      </div>
    </div>
  </div>

  <!-- Supplier -->
  <div class="tab-pane fade" id="sup" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_suppliers" data-toggle="modal" data-target="#mdl_suppliers">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_suppliers" class="tbl_cls_suppliers">
        <?php $co->Table("call suppliers_view()", "dt_suppliers", "n"); ?>
      </div>
    </div>
  </div>
 <!-- end c&s -->
<!-- pur -->
 <div class="tab-pane fade" id="pur" role="tabpanel">
 <button class="btn btn-primary" data-toggle="modal" data-target="#purchaseModal">➕ New Purchase Order</button>
    <?php
  $db = new mysqli("localhost", "root", "", "invent");
$sql = "
  SELECT pur_no ID,
  s.per_no,
   s.name ,
   br.br_no,
   i.item_no,
    po.order_date AS Date, 
    p.qty AS quantity,  
    p.cost, 
    p.discount,
    (p.qty * p.cost * (1 - p.discount / 100)) AS total,
    i.item_name
FROM 
    purchase p
JOIN 
    purchase_order po ON po.po_no = p.po_no
JOIN 
    items i ON i.item_no = p.item_no
JOIN 
    people s ON s.per_no = po.per_no
JOIN 
    branches br ON br.br_no = po.br_no
ORDER BY 
    p.pur_no ASC";

$res = $db->query($sql);
$fields = $res->fetch_fields();
?>

<div class="container mt-4" id="myTableContainer">
  
  <div id="tableContent">
    <?php include 'data/invoice.php'; ?>
  </div>
</div>
</div>
<!-- end pur -->
 <!-- reprts -->
  <!-- report reciept -->
<div class="tab-pane fade" id="receipt" role="tabpanel">
<div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">Receipts Report</h5>
            <?php $co->fillCombo("SELECT rec_no,Cus_name from customers c,accounts a,receipts r WHERE r.cus_no=c.cus_no and a.acc_no=r.acc_no  order by rec_no ASC;", "cbm_accou_receipt_print", "Select customers"); ?>
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-info btn_receipt_all mb-2 mt-2">receipts All</button>
              <button class="btn btn-sm btn-outline-success btn_receipt_single">receipts Single</button>
            </div>
          </div>
        </div>
      </div>
      <!-- payment -->
       <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">payment Report</h5>
            <?php $co->fillCombo("SELECT py_no,name from people c,accounts a,payments r WHERE r.supp_no=c.supp_no and a.acc_no=r.acc_no  order by py_no ASC;", "cbm_accou_payment_print", "Select payment"); ?>
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-info btn_payment_all mb-2 mt-2">Payment  All</button>
              <button class="btn btn-sm btn-outline-success btn_payment_single">payment Single</button>
            </div>
          </div>
        </div>
      </div>
       <!-- end payment -->
</div>
<!-- row two -->
 <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">customer statement</h5>
            <?php $co->fillCombo("SELECT per_no,name from people WHERE parties='customer'  order by per_no ASC;", "cbm_accou_customer_print", "Select customers"); ?>
            <div class="btn-group-vertical w-100">
              
              <button class="btn btn-sm btn-outline-success btn_customer_single">Customer Single</button>
            </div>
          </div>
        </div>
      </div>
      <!-- supplier statement -->
        <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">supplier statement</h5>
            <?php $co->fillCombo("SELECT per_no,name from people WHERE parties='supplier'  order by per_no ASC;", "cbm_accou_suppliers_print", "Select suppliers"); ?>
            <div class="btn-group-vertical w-100">
              
              <button class="btn btn-sm btn-outline-success btn_suppliers_single">supplier Single</button>
            </div>
          </div>
        </div>
      </div>
       <!-- end -->
      
</div>
 <!-- end row two -->
  <!-- row two -->
 <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">Account receivable</h5>
           
            <div class="btn-group-vertical w-100">
                <button class="btn btn-sm btn-info btn_receivable_all mb-2 mt-2">  All</button>
             
            </div>
          </div>
        </div>
      </div>
      <!-- sales statemnt -->
       <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3"> Account paybal</h5>
            
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-info btn_payable_all mb-2 mt-2">  All</button>
             
            </div>
          </div>
        </div>
      </div>
       <!-- end payment -->
</div>
 <!-- end row two -->
  <!-- row two -->
 <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">inventory statement</h5>
           
            <div class="btn-group-vertical w-100">
               
               fisrt date: <input type="date" class="form-control" id="cbm_accou_date1_print"> last date<input type="date" name="" id="cbm_accou_date2_print" class="form-control">
                <button class="btn btn-sm btn-info btn_inventory_statement_all mb-2 mt-2">  All</button>
            </div>
          </div>
        </div>
      </div>
      <!-- sales statemnt -->
       <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">Sales Statement</h5>
       
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-info btn_summrysale_all mb-2 mt-2">sales  All</button>
         
            </div>
          </div>
        </div>
      </div>
       
      <!-- sales statemnt -->
       
       <!-- end payment -->
</div>
 <!-- end row two -->
  <!-- row three -->
   <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">net income</h5>
           
            <div class="btn-group-vertical w-100">
               
               fisrt date: <input type="date" class="form-control" id="cbm_accou_date3_print"> last date<input type="date" name="" id="cbm_accou_date4_print" class="form-control">
                <button class="btn btn-sm btn-info btn_net_income_all mb-2 mt-2">  All</button>
            </div>
          </div>
        </div>
      </div>
      <!-- sales statemnt -->
    
       
      <!-- sales statemnt -->
       
       <!-- end payment -->
</div>
   <!-- end row three -->
</div>



<!-- all home -->
 <!-- Wrapper for all tab contents -->


  <!-- Dashboard -->
  <div class="tab-pane fade show active" id="dash" role="tabpanel">
    <!-- dashboard content here if any -->
   

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



<!-- Include SweetAlert2 library -->











<!-- Scripts -->

</body>
</html>

     
  </div>

  <!-- Customer -->
  <div class="tab-pane fade" id="cus" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_customer" data-toggle="modal" data-target="#mdl_customer">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_customer" class="tbl_cls_customer">
        <?php $co->Table("call customers_view()", "dt_customer", "n"); ?>
      </div>
    </div>
  </div>

  <!-- Supplier -->
  <div class="tab-pane fade" id="sup" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_suppliers" data-toggle="modal" data-target="#mdl_suppliers">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_suppliers" class="tbl_cls_suppliers">
        <?php $co->Table("call suppliers_view()", "dt_suppliers", "n"); ?>
      </div>
    </div>
  </div>

  <!-- Address -->
  <div class="tab-pane fade" id="add" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_address" data-toggle="modal" data-target="#mdl_address">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_address" class="tbl_cls_address">
        <?php $co->Table("call address_view()", "dt_address", "n"); ?>
      </div>
    </div>
  </div>

  <!-- Employee -->
  <div class="tab-pane fade" id="emp" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_employee" data-toggle="modal" data-target="#mdl_employee">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_employee" class="tbl_cls_employee">
        <?php $co->Table("call employee_view()", "dt_employee", "n"); ?>
      </div>
    </div>
  </div>

  <!-- User -->
  <div class="tab-pane fade" id="user" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_users" data-toggle="modal" data-target="#mdl_users">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_users" class="tbl_cls_users">
        <?php $co->Table("call users_view()", "dt_users", "n"); ?>
      </div>
    </div>
  </div>

  <!-- Sale (Empty for now) -->
  <div class="tab-pane fade" id="address" role="tabpanel">
    <!-- sale content goes here -->
  </div>
  
 

 <!-- purchase -->
<div class="tab-pane fade" id="pur" role="tabpanel">
 <button class="btn btn-primary" data-toggle="modal" data-target="#purchaseModal">➕ New Purchase Order</button>
    <?php
  $db = new mysqli("localhost", "root", "", "invent");
$sql = "
  SELECT pur_no ID,
  s.supp_no,
   s.supp_name ,
   br.br_no,
   i.item_no,
    po.order_date AS Date, 
    p.qty AS quantity,  
    p.cost, 
    p.discount,
    (p.qty * p.cost * (1 - p.discount / 100)) AS total,
    i.item_name
FROM 
    purchase p
JOIN 
    purchase_order po ON po.po_no = p.po_no
JOIN 
    items i ON i.item_no = p.item_no
JOIN 
    suppliers s ON s.supp_no = po.supp_no
JOIN 
    branches br ON br.br_no = po.br_no
ORDER BY 
    p.pur_no ASC";

$res = $db->query($sql);
$fields = $res->fetch_fields();
?>

<div class="container mt-4" id="myTableContainer">
  
  <div id="tableContent">
    <?php include 'data/invoice.php'; ?>
  </div>
</div>
</div>
<!-- end purchase -->
<!-- pur return -->
  <div class="tab-pane fade" id="pur_return" role="tabpanel">
     <button type="button" class="btn btn-primary mb-2 btn_new_purchase_return" data-toggle="modal" data-target="#mdl_purchase_return">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_purchase_return" class="tbl_cls_purchase_return">
        <?php $co->Table("call pre_view()", "dt_purchase_return", "n"); ?>
      </div>
    </div>
  </div>
 <!-- end return -->
<!-- sales return -->
 <div class="tab-pane fade" id="sal_return" role="tabpanel">
     <button type="button" class="btn btn-primary mb-2 btn_new_sales_return" data-toggle="modal" data-target="#mdl_sales_return">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_sales_return" class="tbl_cls_sales_return">
        <?php $co->Table("call sr_view()", "dt_sales_return", "n"); ?>
      </div>
    </div>
  </div>
 <!-- end sales return -->
  <!-- sales -->
  <div class="tab-pane fade" id="pos" role="tabpanel">
     <?php include "data/pas.php"; ?>
  </div>
   <!-- end sales -->

   <!-- receipt -->
     <div class="tab-pane fade" id="rec" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_receipts" data-toggle="modal" data-target="#mdl_receipts">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_receipts" class="tbl_cls_receipts">
        <?php $co->Table("call rec_view()", "dt_receipts", "n"); ?>
      </div>
    </div>
  </div>
  <!-- end receipt -->

  <!-- payment -->
     <div class="tab-pane fade" id="pay" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_payments" data-toggle="modal" data-target="#mdl_payments">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_payments" class="tbl_cls_payments">
        <?php $co->Table("call pay_view()", "dt_payments", "n"); ?>
      </div>
    </div>
  </div>
  <!-- end payment -->
  <!-- expenses -->
   <div class="tab-pane fade" id="expenses" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_expenses" data-toggle="modal" data-target="#mdl_expenses">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_expenses" class="tbl_cls_expenses">
        <?php $co->Table("call exp_view()", "dt_expenses", "n"); ?>
      </div>
    </div>
  </div>
  <!-- end expenses -->
  
<!-- expenses -->
   <div class="tab-pane fade" id="exp_pay" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_expense_payment" data-toggle="modal" data-target="#mdl_expense_payment">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_expense_payment" class="tbl_cls_expense_payment">
        <?php $co->Table("call ex_pay_view()", "dt_expense_payment", "n"); ?>
      </div>
    </div>
  </div>
  <!-- end expenses -->
   
  <!-- salar_charge -->
   <div class="tab-pane fade" id="sal_ch" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_salary_charge" data-toggle="modal" data-target="#mdl_salary_charge">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_salary_charge" class="tbl_cls_salary_charge">
        <?php $co->Table("call salary_charge_view()", "dt_salary_charge", "n"); ?>
      </div>
    </div>
  </div>
  <!-- end salar_charge -->

  <!-- salary_payment -->
  <div class="tab-pane fade" id="sal_py" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_salary_payment" data-toggle="modal" data-target="#mdl_salary_payment">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_salary_payment" class="tbl_cls_salary_payment">
        <?php $co->Table("call salary_payment_view()", "dt_salary_payment", "n"); ?>
      </div>
    </div>
  </div>
  <!-- end salary_payment -->
  <!-- POS -->
<div class="tab-pane fade" id="pos" role="tabpanel">
 
</div>


  <!-- end POS -->
    <!-- cat -->
   <div class="tab-pane fade" id="cat" role="tabpanel">
    <button type="button" class="btn btn-primary mb-2 btn_new_categories" data-toggle="modal" data-target="#mdl_categories">Add New</button>
    <div class="col-md-12">
      <div id="tbl_id_categories" class="tbl_cls_categories">
        <?php $co->Table("call cat_view()", "dt_categories", "n"); ?>
      </div>
    </div>
  </div>
   <!-- end cat -->
    
<!-- items -->
<div class="tab-pane fade" id="item" role="tabpanel">


<div class="container mt-4" id="myTableContainer">
<button type="button" class="btn btn-primary m-3" data-toggle="modal" data-target="#exampleModal" id="added">
  Add Item
</button>
  <div id="tables">
  <?php include "data/load_table.php"; ?>
  </div>
</div>
    
  </div>
  <!-- end items -->
<!-- report reciept -->
<div class="tab-pane fade" id="receipt" role="tabpanel">
<div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">Receipts Report</h5>
            <?php $co->fillCombo("SELECT rec_no,Cus_name from customers c,accounts a,receipts r WHERE r.cus_no=c.cus_no and a.acc_no=r.acc_no  order by rec_no ASC;", "cbm_accou_receipt_print", "Select customers"); ?>
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-info btn_receipt_all mb-2 mt-2">receipts All</button>
              <button class="btn btn-sm btn-outline-success btn_receipt_single">receipts Single</button>
            </div>
          </div>
        </div>
      </div>
      <!-- payment -->
       <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">payment Report</h5>
            <?php $co->fillCombo("SELECT py_no,supp_name from suppliers c,accounts a,payments r WHERE r.supp_no=c.supp_no and a.acc_no=r.acc_no  order by py_no ASC;", "cbm_accou_payment_print", "Select payment"); ?>
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-info btn_payment_all mb-2 mt-2">Payment  All</button>
              <button class="btn btn-sm btn-outline-success btn_payment_single">payment Single</button>
            </div>
          </div>
        </div>
      </div>
       <!-- end payment -->
</div>
<!-- row two -->
 <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">customer statement</h5>
            <?php $co->fillCombo("SELECT cus_no,Cus_name from customers   order by cus_no ASC;", "cbm_accou_customer_print", "Select customers"); ?>
            <div class="btn-group-vertical w-100">
              
              <button class="btn btn-sm btn-outline-success btn_customer_single">Customer Single</button>
            </div>
          </div>
        </div>
      </div>
      <!-- supplier statement -->
        <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">supplier statement</h5>
            <?php $co->fillCombo("SELECT supp_no,supp_name from suppliers   order by supp_no ASC;", "cbm_accou_suppliers_print", "Select suppliers"); ?>
            <div class="btn-group-vertical w-100">
              
              <button class="btn btn-sm btn-outline-success btn_suppliers_single">supplier Single</button>
            </div>
          </div>
        </div>
      </div>
       <!-- end -->
      
</div>
 <!-- end row two -->
  <!-- row two -->
 <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">Account receivable</h5>
           
            <div class="btn-group-vertical w-100">
                <button class="btn btn-sm btn-info btn_receivable_all mb-2 mt-2">  All</button>
             
            </div>
          </div>
        </div>
      </div>
      <!-- sales statemnt -->
       <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3"> Account paybal</h5>
            
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-info btn_payable_all mb-2 mt-2">  All</button>
             
            </div>
          </div>
        </div>
      </div>
       <!-- end payment -->
</div>
 <!-- end row two -->
  <!-- row two -->
 <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">inventory statement</h5>
           
            <div class="btn-group-vertical w-100">
               
               fisrt date: <input type="date" class="form-control" id="cbm_accou_date1_print"> last date<input type="date" name="" id="cbm_accou_date2_print" class="form-control">
                <button class="btn btn-sm btn-info btn_inventory_statement_all mb-2 mt-2">  All</button>
            </div>
          </div>
        </div>
      </div>
      <!-- sales statemnt -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">Sales Statement</h5>
       
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-info btn_summrysale_all mb-2 mt-2">sales  All</button>
         
            </div>
          </div>
        </div>
      </div>
       
      <!-- sales statemnt -->
       
       <!-- end payment -->
</div>
 <!-- end row two -->
  <!-- row three -->
   <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">net income</h5>
           
            <div class="btn-group-vertical w-100">
               
               fisrt date: <input type="date" class="form-control" id="cbm_accou_date3_print"> last date<input type="date" name="" id="cbm_accou_date4_print" class="form-control">
                <button class="btn btn-sm btn-info btn_net_income_all mb-2 mt-2">  All</button>
            </div>
          </div>
        </div>
      </div>
      <!-- sales statemnt -->
    
       
      <!-- sales statemnt -->
       
       <!-- end payment -->
</div>
   <!-- end row three -->
</div>
      <!-- end report receipt -->

 <!-- End .tab-content -->
<!--  -->