<!-- Wrapper for all tab contents -->


<!-- Dashboard -->
<div class="tab-pane fade show active" id="dash" role="tabpanel">
  <!-- dashboard content here if any -->
<?php include "dash.php"; ?>
  

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

<!-- Sale (Empty for now) -->
<!-- people -->
<div class="tab-pane fade" id="sup" role="tabpanel"  aria-labelledby="contact-tab">
  <button type="button" class="btn btn-primary mb-2 btn_new_people" data-toggle="modal" data-target="#mdl_people">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_people" class="tbl_cls_people">
     <?php $co->Table("call get_all_people()","dt_people","n"); ?>
    </div>
  </div>
</div>
<!-- people end -->

<!-- Address -->
<div class="tab-pane fade" id="add" role="tabpanel">
  <button type="button" class="btn btn-primary mb-2 btn_new_address" data-toggle="modal" data-target="#mdl_address">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_address" class="tbl_cls_address">
      <?php $co->Table("call address_view()","dt_address","n"); ?>
    </div>
  </div>
</div>
<!-- address end -->

<!-- Employee -->
<div class="tab-pane fade" id="emp" role="tabpanel">
  <button type="button" class="btn btn-primary mb-2 btn_new_employee" data-toggle="modal" data-target="#mdl_employee">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_employee" class="tbl_cls_employee">
      <?php $co->Table("call employee_view()","dt_employee","n"); ?>
    </div>
  </div>
</div>
<!-- employee end -->

<!-- User -->
<div class="tab-pane fade" id="user" role="tabpanel" aria-labelledby="contact-tab">
  <button type="button" class="btn btn-primary mb-2 btn_new_users" data-toggle="modal" data-target="#mdl_users">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_users" class="tbl_cls_users">
      <?php $co->Table("call users_view()" ,"dt_users","n"); ?>
    </div>
  </div>
</div>
<!-- user end -->

<!-- purchase -->
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
<!-- end purchase -->

<!-- pur return -->
<div class="tab-pane fade" id="pur_return" role="tabpanel">
   <button type="button" class="btn btn-primary mb-2 btn_new_purchase_return" data-toggle="modal" data-target="#mdl_purchase_return">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_purchase_return" class="tbl_cls_purchase_return">
      <?php $co->Table("call pre_view()","dt_purchase_return","n"); ?>
    </div>
  </div>
</div>
<!-- sales return -->

<div class="tab-pane fade" id="sal_return" role="tabpanel">
   <button type="button" class="btn btn-primary mb-2 btn_new_sales_return" data-toggle="modal" data-target="#mdl_sales_return">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_sales_return" class="tbl_cls_sales_return">
      <?php $co->Table("call sr_view()","dt_sales_return","n"); ?>
    </div>
  </div>
</div>
<!-- end sales return -->

<!-- receipt -->
<div class="tab-pane fade" id="rec" role="tabpanel">
  <button type="button" class="btn btn-primary mb-2 btn_new_receipts" data-toggle="modal" data-target="#mdl_receipts">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_receipts" class="tbl_cls_receipts">
      <?php $co->Table("call rec_view()","dt_receipts","n"); ?>
    </div>
  </div>
</div>
<!-- end receipt -->

<!-- payment -->
<div class="tab-pane fade" id="pay" role="tabpanel">
  <button type="button" class="btn btn-primary mb-2 btn_new_payments" data-toggle="modal" data-target="#mdl_payments">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_payments" class="tbl_cls_payments">
      <?php $co->Table("call pay_view()","dt_payments","n"); ?>
    </div>
  </div>
</div>
<!-- end payment -->

<!-- expenses -->
<div class="tab-pane fade" id="expenses" role="tabpanel">
  <button type="button" class="btn btn-primary mb-2 btn_new_expenses" data-toggle="modal" data-target="#mdl_expenses">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_expenses" class="tbl_cls_expenses">
      <?php $co->Table("call exp_view()","dt_expenses","n"); ?>
    </div>
  </div>
</div>
<!-- end expenses -->

<!-- expense payment -->
<div class="tab-pane fade" id="exp_pay" role="tabpanel">
  <button type="button" class="btn btn-primary mb-2 btn_new_expense_payment" data-toggle="modal" data-target="#mdl_expense_payment">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_expense_payment" class="tbl_cls_expense_payment">
      <?php $co->Table("call ex_pay_view()","dt_expense_payment","n"); ?>
    </div>
  </div>
</div>
<!-- end expense payment -->

<!-- salary charge -->
<div class="tab-pane fade" id="sal_ch" role="tabpanel">
  <button type="button" class="btn btn-primary mb-2 btn_new_salary_charge" data-toggle="modal" data-target="#mdl_salary_charge">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_salary_charge" class="tbl_cls_salary_charge">
      <?php $co->Table("call salary_charge_view()","dt_salary_charge","n"); ?>
    </div>
  </div>
</div>
<!-- end salary charge -->

<!-- salary payment -->
<div class="tab-pane fade" id="sal_py" role="tabpanel">
  <button type="button" class="btn btn-primary mb-2 btn_new_salary_payment" data-toggle="modal" data-target="#mdl_salary_payment">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_salary_payment" class="tbl_cls_salary_payment">
      <?php $co->Table("call salary_payment_view()","dt_salary_payment","n"); ?>
    </div>
  </div>
</div>
<!-- end salary payment -->

<!-- POS -->
<div class="tab-pane fade" id="pos" role="tabpanel">
   <?php include "data/pas.php"; ?>
</div>
<!-- end POS -->

<!-- categories -->
<div class="tab-pane fade" id="cat" role="tabpanel">
  <button type="button" class="btn btn-primary mb-2 btn_new_categories" data-toggle="modal" data-target="#mdl_categories">Add New</button>
  <div class="col-md-12">
    <div id="tbl_id_categories" class="tbl_cls_categories">
      <?php $co->Table("call cat_view()","dt_people","n"); ?>
    </div>
  </div>
</div>
<!-- end categories -->

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

<!-- reports -->
<!-- ... (unchanged report code, fillCombo is already fine) ... -->


<!-- reports -->


  <!-- report reciept -->
<div class="tab-pane fade" id="receipt" role="tabpanel">
<div class="row">
 
      <!-- payment -->
     <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">Receipts Report</h5>
            <?php $co->fillCombo("SELECT DISTINCT r.rec_no, p.name
FROM people p
JOIN receipts r ON r.per_no = p.per_no
WHERE p.name <> 'WALK-IN'
ORDER BY r.rec_no ASC", "cbm_accou_receipts_print", "Select customers"); ?>
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
            <?php $co->fillCombo("SELECT py_no,p.name from people p,accounts a,payments r WHERE r.per_no=p.per_no and a.acc_no=r.acc_no  order by py_no ASC", "cbm_accou_payment_print", "Select payment"); ?>
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-info btn_payment_all mb-2 mt-2">Payment  All</button>
              <button class="btn btn-sm btn-outline-success btn_payment_single">payment Single</button>
            </div>
          </div>
        </div>
      </div>
       <!-- end payment -->
</div>
       <!-- end payment -->
</div>
<!-- row two -->
 <div class="row">
  <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded">
          <div class="card-body">

            <h5 class="card-title text-center text-info mb-3">customer statement</h5>
            <?php $co->fillCombo("SELECT per_no,name from people WHERE parties='customer'   order by per_no ASC;", "cbm_accou_customer_print", "Select customers"); ?>
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
            <?php $co->fillCombo("SELECT p.per_no, p.name
FROM people p
WHERE p.parties = 'supplier'
ORDER BY p.per_no ASC;", "cbm_accou_suppliers_print", "Select suppliers"); ?>
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
      <!-- sales statemnt -->
      <div class="col-md-6 mb-4">
  <div class="card shadow-sm border-0 rounded">
    <div class="card-body">

      <h5 class="card-title text-center text-info mb-3">Sales Statement</h5>

      <!-- Invoice ID input -->
      <div class="form-group mb-3">
        <label for="invoice_id" class="form-label">Enter Invoice No:</label>
        <input type="number" id="invoice_id" class="form-control" placeholder="Invoice ID">
      </div>

      <!-- Action button -->
      <div class="btn-group-vertical w-100">
        <button class="btn btn-sm btn-info btn_summrysale_all mb-2 mt-2">
          Show Report
        </button>
      </div>

    </div>
  </div>
</div>
       
      <!-- sales statemnt -->
       
       <!-- end payment -->
</div>
 <!-- end row two -->
  <!-- row three -->

      <!-- sales statemnt -->
    
       
      <!-- sales statemnt -->
       
       <!-- end payment -->
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
      
      <div class="col-md-6 mb-4">
  <div class="card shadow-sm border-0 rounded">
    <div class="card-body">

      <h5 class="card-title text-center text-info mb-3">Balance Sheet</h5>
      <div class="btn-group-vertical w-100">
        <button class="btn btn-sm btn-info btn_balance_sheet mb-2 mt-2">View Balance Sheet</button>
      </div>

    </div>
  </div>
</div>


</div>
</div>
   <!-- end row three -->
      <!-- end report receipt -->
<!-- end reports -->

<!-- End .tab-content -->
