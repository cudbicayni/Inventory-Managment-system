<?php 
include "data/Codes.php";
$coder = new Codes();
$coder->setConnect();
?>
<!-- purchase  -->

 <!-- end purchase -->
<!-- all mdl reports -->
 <!-- All MDL Reports -->
<div class="modal fade" id="mdl_All_reports" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top: 3%">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content rounded shadow-sm">
      <div class="modal-header bg-success text-white">
        <h4 class="modal-title" id="exampleModalLabel">Report System</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">

        </button>
        <button type="button" class="btn btn-light btn-md ml-2" id="btn_prt_dt_rpt">PRINT</button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <form id="rpt_show_PRINT">
              <!-- Report Title -->
             <!--  <div class="text-center mb-3">
                <h5 class="font-weight-bold">Monthly Sales Report</h5>
                <img src="your-logo.png" class="img-fluid" style="max-width: 100%; height: auto; margin-top: -2%;">
              </div> -->

              <!-- Date Range Selection -->
              <!-- <div class="form-group">
                <label for="reportDateRange">Select Date Range:</label>
                <input type="text" class="form-control" id="reportDateRange" placeholder="YYYY-MM-DD to YYYY-MM-DD">
              </div> -->

              <!-- Report Content -->
              <div id="rpt_show" class="p-3 border rounded bg-light mt-4">
                <h6 class="font-weight-bold">Report Details</h6>
                <p class="text-muted">No data available. Please select a date range to generate the report.</p>
                <!-- Placeholder for dynamically loaded report data -->
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- All MDL Reports -->


<!-- -----------MDL DELETE------------ -->
<div style="position: absolute">
  <div class="modal fade" id="mdl_delete_all" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content" style="top: 150px;">
        <div class="model-header bg-primary p-15">
          <h4 class="modal-title text-center"
          style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; color: #fff;">
        Delete Record</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 text-center">
            <h6 style="font-size: 12px; text-align: center;color: primary;"><strong>Are you sure you
            want to delete this record?</strong></h6><br>
            <h1 style="font-family: impact; font-size: 20px; text-align: center; color: blue; "></h1>
          </div>
        </div>
        <div class="col-md-12 text-center ">
          <button type="submit" class="btn btn-danger btn-sm btn-circle "
          id="yes_d_btn"><span>YES</span></button>
          <button type="button" class="btn btn-primary btn-sm m-l-80" data-dismiss="modal"><span
            id="spm_deld_Faculty">NO</span></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 <!-- end repeotd -->
<!-- Customer Modal (was Supplier) -->
<div class="modal fade" id="mdl_customer" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Customer</h4>
      <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
    </div>
    <div id="customer_alert"></div>
    <div class="modal-body">
      <form id="frm_customer" method="post">
        <input type="hidden" name="cus_pro" value="cus_pro">
        <input type="hidden" name="cus_no" id="cus_no">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Magaca</label>
            <input type="text" class="form-control" name="cus_name" id="cus_name" placeholder="Enter Magac">
          </div>
          <div class="form-group col-md-6">
            <label>Tell</label>
            <input type="text" class="form-control" name="cus_tell" id="cus_tell" placeholder="Enter Tell">
          </div>
          <div class="form-group col-md-6">
            <label>Email</label>
            <input type="email" class="form-control" name="email" id="cust_email" placeholder="Enter Email">
          </div>
          <div class="form-group col-md-6">
            <label>Description</label>
            <input type="text" class="form-control" name="description" id="cust_description" placeholder="Enter Description">
          </div>
          <div class="form-group col-md-6">
            <label>Address</label>
            <?php $co->fillCombo("SELECT add_no,district 'dagmada' FROM address WHERE add_no ORDER BY add_no", "cbm_reg_add_no_print", "Select address"); ?>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer bg-whitesmoke br">
      <button type="button" class="btn btn-primary" id="btn_save_customer">Save</button>
      <button type="button" class="btn btn-primary" id="btn_update_customer">Update</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div></div>
</div>
<!-- end customer -->

<!-- Supplier Modal -->
<div class="modal fade" id="mdl_people" tabindex="-1" role="dialog" aria-labelledby="mdl_people_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="mdl_suppliers_label">People</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div id="people_alert"></div>

      <div class="modal-body">
        <form id="frm_people" method="post">
          <input type="hidden" name="people_pro" value="	people_pro">
          <input type="hidden" name="per_no" id="per_no">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Magaca</label>
              <input type="text" name="magac" id="magac" class="form-control" placeholder="Enter Magac">
            </div>
            <div class="form-group col-md-6">
              <label>Tell</label>
              <input type="text" name="tell" id="tell" class="form-control" placeholder="Enter Tell">
            </div>
            <div class="form-group col-md-6">
              <label>Email</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Geli Email">
            </div>
            <div class="form-group col-md-6">
              <label>Description</label>
              <input type="text" name="description" id="description" class="form-control" placeholder="Geli Description">
            </div>
            <div class="form-group col-md-6">
              <label>Address</label>
              <?php $co->fillCombo("SELECT add_no,district 'dagmada' FROM address WHERE add_no ORDER BY add_no", "cbm_reg_address_print", "Select address"); ?>
            </div>
            <div class="form-group col-md-6">
              <label>people</label>
              <?php 
$co->fillCombo("
  SELECT parties AS id, parties AS name 
  FROM people 
  GROUP BY parties
", "cbm_reg_people_print", "Select people"); 
?>

            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_people">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_people">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- end supplier -->
<!-- Address Modal -->
<div class="modal fade" id="mdl_address" tabindex="-1" role="dialog" aria-labelledby="mdl_address_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h4 class="modal-title" id="mdl_address_label">Address</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      
      
      
      <div class="modal-body">
        <form id="frm_address" method="post">
          <input type="hidden" name="add_pro" value="add_pro">
          <input type="hidden" name="add_no" id="add_no">
          
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Country</label>
              <input type="text" class="form-control" name="country" id="country" placeholder="Enter country">
            </div>
            <div class="form-group col-md-6">
              <label>District</label>
              <input type="text" class="form-control" name="district" id="district" placeholder="Enter district">
            </div>
            <div class="form-group col-md-6">
              <label>Village</label>
              <input type="text" class="form-control" name="village" id="village" placeholder="Enter village">
            </div>
            <div class="form-group col-md-6">
              <label>Area</label>
              <input type="text" class="form-control" name="area" id="area" placeholder="Enter area">
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_address">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_address">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>




<!-- end address -->
<!-- Employee Modal -->
<div class="modal fade" id="mdl_employee" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Employee</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <!-- Alert Message -->
      <div id="employee_alert"></div>

      <!-- Modal Body -->
      <div class="modal-body">
        <form id="frm_employee" method="post">
          <input type="hidden" name="emp_pro" value="emp_pro">
          <input type="hidden" name="emp_no" id="emp_no">
          
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="fullname">Full Name</label>
              <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Enter Full Name">
            </div>

            <div class="form-group col-md-6">
              <label for="emp_tell">Phone</label>
              <input type="text" class="form-control" name="tell" id="emp_tell" placeholder="Enter Phone">
            </div>

            <div class="form-group col-md-6">
              <label for="emp_email">Email</label>
              <input type="email" class="form-control" name="email" id="emp_email" placeholder="Enter Email">
            </div>
            <div class="form-group col-md-6">
              <label for="sex">Sex</label>
              <select class="form-control" name="sex" id="sex">
                <option value="">Select Sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="salary">Salary</label>
              <input type="number" class="form-control" name="salary" id="salary" placeholder="Enter Salary">
            </div>

            

            <div class="form-group col-md-6">
              <label for="cbm_emp_address">Address</label>
              <?php $co->fillCombo("SELECT add_no, district AS 'dagmada' FROM address WHERE add_no ORDER BY add_no", "cbm_emp_address", "Select Address"); ?>
            </div>
          </div>
        </form>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_employee">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_employee">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End Employee Modal -->





 <!-- end purchase -->
<!-- Purchase_return Modal -->
<div class="modal fade" id="mdl_purchase_return" tabindex="-1" role="dialog" aria-labelledby="mdl_purchase_return_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" >purchase</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div id="purchase_return_alert"></div>

      <div class="modal-body">
        <form id="frm_purchase_return" method="post">
          <input type="hidden" name="prt_pro" value="prt_pro">
          <input type="hidden" name="prt_no" id="prt_no">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cbm_emp_address">purchase_return </label>
              <?php $co->fillCombo("SELECT pur_no,i.item_name FROM purchase p,items i WHERE p.item_no=i.item_no ORDER BY pur_no ASC", "cbm_reg_pur_no_p_print", "Select puchase "); ?>
            </div>
            <div class="form-group col-md-6">
              <label>Quantity</label>
              <input type="number" name="qty_p" id="qty_p" class="form-control" placeholder="Enter Qty..">
            </div>
            <div class="form-group col-md-6">
              <label>Reason</label>
              <input type="text" name="reason_p" id="reason_p" class="form-control" placeholder="Geli Reason">
            </div>
            <div class="form-group col-md-6">
              <label>Date</label>
              <input type="date" name="return_date_p" id="return_date_p" class="form-control" placeholder="Geli Discount">
            </div>
            
          </div>
        </form>
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_purchase_return">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_purchase_return">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- end prochase_return -->
<!-- sales_return Modal -->
<div class="modal fade" id="mdl_sales_return" tabindex="-1" role="dialog" aria-labelledby="mdl_sales_return_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="mdl_sales_return_label">Receipts</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div id="sales_return_alert"></div>

      <div class="modal-body">
        <form id="frm_sales_return" method="post">
          <input type="hidden" name="sr_pro" value="sr_pro">
          <input type="hidden" name="sr_no" id="sr_no">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cbm_sales_name">sales</label>
              <?php $co->fillCombo(
                "SELECT MIN(s.sal_no) AS Id, i.item_name
FROM sales s
JOIN items i ON s.item_no = i.item_no
GROUP BY i.item_name
ORDER BY Id ASC",
                "cbm_reg_sal_no_print",
                "Select sales"
              ); ?>
            </div>
            <div class="form-group col-md-6">
              <label>qty</label>
              <input type="number" name="re_qty" id="re_qty" class="form-control" placeholder="Enter qty">
            </div>
            <div class="form-group col-md-6">
              <label>reasons</label>
              <input type="text" name="reasons" id="reasons" class="form-control" placeholder="Enter reasons">
            </div>
            <div class="form-group col-md-6">
              <label for="date">Date</label>
              <input type="date" class="form-control" name="return_date" id="return_date" placeholder="Enter Date">
            </div>
             
            <div class="form-group col-md-6">
              <label for="cbm_branches_name">Branches</label>
              <?php $co->fillCombo(
                "SELECT br_no, branches_name FROM branches ORDER BY br_no ASC",
                "cbm_reg_br_no_print",
                "Select branches"
              ); ?>
            </div>
           
          </div>
        </form>
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_sales_return">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_sales_return">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End sales_return Modal -->
<!-- Receipts Modal -->
<div class="modal fade" id="mdl_receipts" tabindex="-1" role="dialog" aria-labelledby="mdl_receipts_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="mdl_receipts_label">Receipts</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div id="receipts_alert"></div>

      <div class="modal-body">
        <form id="frm_receipts" method="post">
          <input type="hidden" name="rec_pro" value="rec_pro">
          <input type="hidden" name="rec_no" id="rec_no">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cbm_customer_name">Customer</label>
              <?php $co->fillCombo(
                "SELECT c.cus_no, c.cus_name as Name FROM customer c",
                "cbm_customer_name",
                "Select customer"
              ); ?>
            </div>
            <div class="form-group col-md-6">
              <label>Amount</label>
              <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter Amount">
            </div>
            <div class="form-group col-md-6">
              <label for="cbm_acc_name">Account</label>
              <?php $co->fillCombo(
                "SELECT c.acc_no, c.acc_name as Name FROM accounts c",
                "cbm_acc_no_name",
                "Select account"
              ); ?>
            </div>
            <div class="form-group col-md-6">
              <label for="date">Date</label>
              <input type="date" class="form-control" name="rec_date" id="rec_date" placeholder="Enter Date">
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_receipts">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_receipts">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End Receipts Modal -->
 <!-- Start Expenses Modal -->
<div class="modal fade" id="mdl_expenses" tabindex="-1" role="dialog" aria-labelledby="mdl_expenses_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="mdl_expenses_label">Expenses</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div id="expenses_alert"></div>

      <div class="modal-body">
        <form id="frm_expenses" method="post">
          <input type="hidden" name="ex_pro" value="ex_pro">
          <input type="hidden" name="ex_no" id="ex_no">

          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="ex_name">Expense Name</label>
              <input type="text" name="ex_name" id="ex_name" class="form-control" placeholder="Enter Expense Name">
            </div>
            
            
          </div>
        </form>
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_expenses">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_expenses">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End Expenses Modal -->
<!-- Expense Payment Modal -->
<div class="modal fade" id="mdl_expense_payment" tabindex="-1" role="dialog" aria-labelledby="mdl_expense_payment_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="mdl_expense_payment_label">Expense Payment</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div id="expense_payment_alert"></div>

      <!-- Body -->
      <div class="modal-body">
        <form id="frm_expense_payment" method="post">
          <input type="hidden" name="pro" value="expy_pro">
          <input type="hidden" name="num" id="num">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="ex_no_p">Expense</label>
              <?php $co->fillCombo(
                "SELECT ex_no, ex_name AS Name FROM expenses",
                "ex_no_p",
                "Select Expense"
              ); ?>
            </div>

            <div class="form-group col-md-6">
              <label>Amount</label>
              <input type="number" name="amount_P" id="amount_P" class="form-control" placeholder="Enter Amount">
            </div>

            <div class="form-group col-md-6">
              <label for="acc_no_p">Account</label>
              <?php $co->fillCombo(
                "SELECT acc_no, acc_name AS Name FROM accounts",
                "acc_no_p",
                "Select Account"
              ); ?>
            </div>

            <div class="form-group col-md-6">
              <label for="exp_date_p">Date</label>
              <input type="date" class="form-control" name="exp_date_p" id="exp_date_p" placeholder="Enter Date">
            </div>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_expense_payment">Save</button>
        <button type="button" class="btn btn-warning" id="btn_update_expense_payment">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End Expense Payment Modal -->
<!-- salary Payment Modal -->
<div class="modal fade" id="mdl_salary_payment" tabindex="-1" role="dialog" aria-labelledby="mdl_salary_payment_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="mdl_salary_payment_label">salary Payment</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div id="salary_payment_alert"></div>

      <!-- Body -->
      <div class="modal-body">
        <form id="frm_salary_payment" method="post">
          <input type="hidden" name="sr_py_pro" value="sr_py_pro">
          <input type="hidden" name="sr_py_no" id="sr_py_no">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="ex_no_p">salary_charge</label>
              <?php $co->fillCombo(
                "SELECT sr_ch_no AS Id, concat(e.FullName,' Amount $',ch_amount)charges FROM salary_charge sc,employee e WHERE sc.emp_no=e.emp_no ORDER BY sr_ch_no ASC",
                "cbm_reg_sr_ch_no_print",
                "Select salary_charge"
              ); ?>
            </div>

            

            <div class="form-group col-md-6">
              <label for="acc_no_p">Account</label>
              <?php $co->fillCombo(
                "SELECT acc_no, acc_name AS Name FROM accounts",
                "cbm_reg_acc_no_print",
                "Select Account"
              ); ?>
            </div>
            <div class="form-group col-md-6">
              <label>Amount</label>
              <input type="number" name="salP_amount" id="salP_amount" class="form-control" placeholder="Enter Amount">
            </div>

            <div class="form-group col-md-6">
              <label for="exp_date_p">Date</label>
              <input type="date" class="form-control" name="salary_pay_date" id="salary_pay_date" placeholder="Enter Date">
            </div>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_salary_payment">Save</button>
        <button type="button" class="btn btn-warning" id="btn_update_salary_payment">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End salary Payment Modal -->
<!-- salary_charge -->
<div class="modal fade" id="mdl_salary_charge" tabindex="-1" role="dialog" aria-labelledby="mdl_salary_charge_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="mdl_salary_charge_label">Salary Charge</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div id="salary_charge_alert"></div>

      <!-- Body -->
      <div class="modal-body">
        <form id="frm_salary_charge" method="post">
          <input type="hidden" name="pro" value="sr_ch_pro">
          <input type="hidden" name="num" id="num">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="emp_no_p">Employee</label>
              <?php $co->fillCombo(
                "SELECT emp_no AS Id, concat(e.FullName,' Amount $',salary)charges FROM  employee e  ORDER BY emp_no ASC",
                "emp_no_p",
                "Select Employee"
              ); ?>
            </div>

            <div class="form-group col-md-6">
              <label>Amount</label>
              <input type="number" name="ch_amount" id="ch_amount" class="form-control" placeholder="Enter Amount">
            </div>

            <div class="form-group col-md-6">
              <label for="salary_date_p">Salary Date</label>
              <input type="date" class="form-control" name="salary_date_p" id="salary_date_p" placeholder="Enter Date">
            </div>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_salary_charge">Save</button>
        <button type="button" class="btn btn-warning" id="btn_update_salary_charge">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End Salary Charge Modal -->
<!-- Categories Modal -->
<div class="modal fade" id="mdl_categories" tabindex="-1" role="dialog" aria-labelledby="mdl_categories_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="mdl_categories_label">Categories</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div id="categories_alert"></div>

      <div class="modal-body">
        <form id="frm_categories" method="post">
          <input type="hidden" name="cat_pro" value="cat_pro">
          <input type="hidden" name="cat_no" id="cat_no">

          <div class="form-row">
            
            <div class="form-group col-md-6">
              <label>categories Name</label>
              <input type="text" class="form-control" name="cat_name" id="cat_name" class="form-control" placeholder="Enter Categories_Name">
            </div>
            
            <div class="form-group col-md-6">
              <label >Description</label>
              <input type="text" class="form-control" name="description_P" id="description_P" placeholder="Enter Description">
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_categories">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_categories">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End Categories Modal -->
<!-- Payments Modal -->
<div class="modal fade" id="mdl_payments" tabindex="-1" role="dialog" aria-labelledby="mdl_payments_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="mdl_payments_label">Payments</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div id="payments_alert"></div>

      <div class="modal-body">
        <form id="frm_payments" method="post">
          <input type="hidden" name="pay_pro" value="pay_pro">
          <input type="hidden" name="pay_no" id="pay_no">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cbm_customer_name">Supplier</label>
              <?php $co->fillCombo(
                "SELECT s.supp_no, s.supp_name as Magac FROM suppliers s ORDER BY supp_no",
                "cbm_reg_suppliers_print",
                "Select suppliers"
              ); ?>
            </div>
            <div class="form-group col-md-6">
              <label>Amount</label>
              <input type="number" name="pay_amount" id="pay_amount" class="form-control" placeholder="Enter Amount">
            </div>
            <div class="form-group col-md-6">
              <label for="cbm_acc_name">Account</label>
              <?php $co->fillCombo(
                "SELECT c.acc_no, c.acc_name as Name FROM accounts c",
                "cbm_item_name",
                "Select account"
              ); ?>
            </div>
            <div class="form-group col-md-6">
              <label for="date">Date</label>
              <input type="date" class="form-control" name="date" id="date" placeholder="Enter Date">
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_payments">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_payments">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End Payments Modal -->
<!-- users Modal -->
<div class="modal fade" id="mdl_users" tabindex="-1" role="dialog" aria-labelledby="mdl_users_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="mdl_users_label">Receipts</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div id="users_alert"></div>

      <div class="modal-body">
        <form id="frm_users" method="post">
          <input type="hidden" name="user_pro" value="user_pro">
          <input type="hidden" name="user_no" id="user_no">
          

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cbm_privillage_name">privillage</label>
              <?php $co->fillCombo(
                "SELECT pr_no,pr_type FROM privillage ORDER BY pr_no ASC",
                "cbm_reg_pr_no_print",
                "Select privillage"
              ); ?>
            </div>
            <div class="form-group col-md-6">
              <label>username</label>
              <input type="text" name="username" id="username" class="form-control" placeholder="Enter username">
            </div>
            <div class="form-group col-md-6">
              <label>password</label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Enter password">
            </div>
           <div class="form-group col-md-6">
              <label>email</label>
              <input type="email" name="user_email" id="user_email" class="form-control" placeholder="Enter email">
            </div>
            <div class="form-group col-md-6">
              <label>Tell</label>
              <input type="number" name="number" id="number" class="form-control" placeholder="Enter Tell">
            </div>
            <input type="hidden" name="verification_code" id="verification_code">
          <input type="hidden" name="verified" value="0"> 
          </div>
        </form>
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="btn_save_users">Save</button>
        <button type="button" class="btn btn-primary" id="btn_update_users">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End users Modal -->
  <!-- Items Modal -->
<!-- Button to open modal -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <form id="imageUploadForm" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Upload Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <!-- <input type="hidden" name="items_pro" value="items_pro"> -->
          <input type="hidden" id="item_no" name="item_no"  class="ctr">


          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cat">Category</label>
              <select name="i_cat" id="cat" class="form-control ctr" required>
                <option value="">Select Category</option>
                <?php
                  $coder->fillCombo("SELECT cat_no, cat_name as Name FROM categories", "cbm_reg_cat_no_print", "Select category");
                ?>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="image">Item Image</label>
              <input type="file" name="txtfile" id="image" class="form-control-file " accept="image/*">
              <input type="hidden" name="item_image" id="item_image" class="ctr">

            </div>

            <div class="form-group col-md-6">
              <label for="item_name">Item Name</label>
              <input type="text" name="i_name" id="item_name" class="form-control ctr" placeholder="Enter item name" required>
            </div>

            <div class="form-group col-md-6">
              <label for="price">Price</label>
              <input type="number" name="i_price" id="price" class="form-control ctr" placeholder="Enter price" required>
            </div>

            
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btn_save">Save</button>
          <button type="button" class="btn btn-primary" id="btn_update" >Update</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>




 <!-- insert -->

<!-- end items -->
 <script>
  document.getElementById("btn_prt_dt_rpt").addEventListener("click", function () {
    const reportContent = document.getElementById("rpt_show").innerHTML;

    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write(`
      <html>
        <head>
          <title>Print Report</title>
          <style>
            body {
              font-family: Arial, sans-serif;
              padding: 20px;
            }
            .print-container {
              border: 1px solid #ccc;
              padding: 20px;
              border-radius: 5px;
              background: #fff;
            }
            @media print {
              body {
                -webkit-print-color-adjust: exact;
              }
            }
          </style>
        </head>
        <body>
          <div class="print-container">
            ${reportContent}
          </div>
        </body>
      </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
  }); 
 </script>
 <style>
  .invoice-box {
        max-width: 720px;
        margin: auto;
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        font-family: Arial, sans-serif;
        box-shadow: 0 0 10px rgba(0,0,0,0.15);
    }
    .divider { border-top: 1px dashed #999; margin: 10px 0; }
    .row-line { display: flex; justify-content: space-between; font-size: 13px; }
    .row-line.bold { font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 5px; }
    .row-line.total { font-weight: bold; }
    .row-line.total.grand { font-size: 15px; border-top: 2px solid #000; margin-top: 5px; padding-top: 5px; }
    .right { text-align: right; }
    #printBtn {
        display: block; width: 100%; background-color: #9115deff; color: white;
        padding: 10px; font-size: 14px; border: none; border-radius: 4px; margin-top: 15px; cursor: pointer;
    }
    #printBtn:hover { background-color: #9115deff; }
    @media print {
        #printBtn { display: none; }
        .invoice-box { box-shadow: none; border: none; max-width: 100%; }
        .modal, .modal-backdrop, .btn, .container, .divider { box-shadow: none !important; }
    }
 </style>


<!-- purchase and supplier -->
 <?php
require 'data/db.php';

$people = $pdo->query("SELECT * FROM people")->fetchAll();
$branches = $pdo->query("SELECT * FROM branches")->fetchAll();
$items = $pdo->query("SELECT * FROM items")->fetchAll();
?>
<div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="purchaseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Create Purchase Order</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <!-- FORM CONTAINER -->
        <div id="po-form-container">
          <form id="frm_purchase_order_multi" class="needs-validation" novalidate>
            <input type="hidden" name="multi_order" value="1">
            <input type="hidden" name="update_id" id="update_id" value="">

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Supplier</label>
                <select name="per_no" class="form-control " required>
  <option value="">Select supplier</option>
  <?php foreach ($people as $s): ?>
    <option value="<?= $s['per_no'] ?>"><?= htmlspecialchars($s['name']) ?></option>
  <?php endforeach; ?>
</select>
                <div class="invalid-feedback">Please select a supplier.</div>
              </div>
              <div class="form-group col-md-6">
                <label>Branch</label>
               <select name="branch_num" class="form-control " required>
  <option value="">Select branch</option>
  <?php foreach ($branches as $b): ?>
    <option value="<?= $b['br_no'] ?>"><?= htmlspecialchars($b['branches_name']) ?></option>
  <?php endforeach; ?>
</select>
                <div class="invalid-feedback">Please select a branch.</div>
              </div>
            </div>

            <div class="form-group">
              <label>Order Date</label>
              <input type="date" name="order_date" class="form-control" required value="<?= date('Y-m-d') ?>">
              <div class="invalid-feedback">Please provide an order date.</div>
            </div>

            <h5>Items</h5>
            <div id="itemsContainer">
              <div class="item-row form-row align-items-end">
                <div class="form-group col-md-4">
                  <label>Item</label>
                  <select name="item_no[]" class="form-control " required>
  <option value="">Select item</option>
  <?php foreach ($items as $item): ?>
    <option value="<?= $item['item_no'] ?>"><?= htmlspecialchars($item['item_name']) ?></option>
  <?php endforeach; ?>
</select>
                  <div class="invalid-feedback">Please select an item.</div>
                </div>
                <div class="form-group col-md-2">
                  <label>Cost</label>
                  <input type="number" name="cost[]" class="form-control" step="0.01" min="0" required>
                  <div class="invalid-feedback">Enter cost.</div>
                </div>
                <div class="form-group col-md-2">
                  <label>Qty</label>
                  <input type="number" name="qty[]" class="form-control" min="1" required>
                  <div class="invalid-feedback">Enter quantity.</div>
                </div>
                <div class="form-group col-md-2">
                  <label>Discount %</label>
                  <input type="number" name="discount[]" class="form-control" min="0" max="100" step="0.01" value="0">
                </div>
                <div class="form-group col-md-2">
                  <button type="button" class="btn btn-danger remove-item">✖</button>
                </div>
              </div>
            </div>

            <button type="button" id="addItem" class="btn btn-success mt-2 mb-3">➕ Add Item</button>

            <div class="modal-footer mt-3 p-0 pt-3">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Order</button>
            </div>
          </form>
        </div>

        <!-- INVOICE CONTAINER -->
         <div id="po-invoice" style="display:none;" class="mt-4"></div>
      </div>

    </div>
  </div>
</div>

 <!-- end -->
  