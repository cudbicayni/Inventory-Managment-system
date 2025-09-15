<script>
	SYD_ALL("mdl_purchase","select * from purchase where pur_no=","");
	// SYD_ALL("mdl_acc","SELECT * FROM accounts WHERE acc_no=","");
	SYD_ALL("mdl_address","SELECT * FROM address WHERE add_no=","");
	SYD_ALL("mdl_categories","SELECT * FROM categories WHERE cat_no=","");
	SYD_ALL("mdl_people","SELECT * FROM people WHERE per_no=","");
	SYD_ALL("mdl_employee","SELECT * FROM employee WHERE emp_no=","");
	SYD_ALL("mdl_expenses","SELECT * FROM expenses WHERE ex_no=","");
	SYD_ALL("mdl_expense_payment","SELECT * FROM expense_payment WHERE exp_no=","");
	// SYD_ALL("mdl_items","SELECT * FROM items WHERE item_no=","");
	SYD_ALL("mdl_payments","SELECT * FROM payments WHERE py_no=","");
	SYD_ALL("mdl_purchase_order","SELECT * FROM purchase_order WHERE po_no=","");
	SYD_ALL("mdl_purchase_return","SELECT * FROM purchase_return WHERE prt_no=","");
	SYD_ALL("mdl_receipts","SELECT * FROM receipts WHERE rec_no=","");
	SYD_ALL("mdl_salary_payment","SELECT * FROM salary_payment WHERE sr_py_no=","");
  SYD_ALL("mdl_salary_charge","SELECT * FROM salary_charge WHERE sr_ch_no=","");
	// SYD_ALL("mdl_sales","SELECT * FROM sales WHERE sal_no=","");
	SYD_ALL("mdl_sales_return","SELECT * FROM sales_return WHERE sr_no=","");
	// SYD_ALL("mdl_sale_deleviry","SELECT * FROM sale_delivery WHERE sd_no=","");
	SYD_ALL("mdl_suppliers","SELECT * FROM suppliers WHERE supp_no=","");
	SYD_ALL("mdl_users","SELECT * FROM users WHERE user_no=","");

	
	// SYD_ALL("mdl_customer","SELECT * FROM customer WHERE cus_no=","");

		// $(".btn_receivable_all").click(function(){
		// $("#mdl_All_reports").modal("show");
		// var qry="call  cat_view()";
		// $.post("config/SYD_Table.php","qry="+qry,function(data){
 		// // alert(qry);
		// 	$("#rpt_show").html(data);
		// });
	// })

	$(".btn_receipt_all").click(function(){
		$("#mdl_All_reports").modal("show");
		var qry="call  receipts_rep_all()";
		$.post("config/SYD_Table.php","qry="+qry,function(data){
 		// alert(qry);
			$("#rpt_show").html(data);
		});
	})

	$(".btn_receipt_single").click(function(){
		$("#mdl_All_reports").modal("show");
		var qry="call receipts_rep_single('"+$("#cbm_accou_receipts_print").val()+"');";
		$.post("config/SYD_Table.php","qry="+qry,function(data){
			$("#rpt_show").html(data);
		});
	})
	// // peyment
	$(".btn_payment_all").click(function(){
		$("#mdl_All_reports").modal("show");
		var qry="call  payments_rep_all()";
		$.post("config/SYD_Table.php","qry="+qry,function(data){
 		// alert(qry);
			$("#rpt_show").html(data);
		});
	})

	$(".btn_payment_single").click(function(){
		$("#mdl_All_reports").modal("show");
		var qry="call payments_rep_single('"+$("#cbm_accou_payment_print").val()+"');";
		$.post("config/SYD_Table.php","qry="+qry,function(data){
			$("#rpt_show").html(data);
		});
	})
	// // payment
	// // customer statment
	$(".btn_customer_single").click(function(){
		$("#mdl_All_reports").modal("show");
		var qry="call customer_statments('"+$("#cbm_accou_customer_print").val()+"');";
		$.post("config/SYD_Table.php","qry="+qry,function(data){
			$("#rpt_show").html(data);
		});
	})
	// // supplier statment
	$(".btn_suppliers_single").on("click", function(){
  var perNo = $("#cbm_accou_suppliers_print").val();
  if (!perNo) { alert("Select a supplier"); return; }
  $("#mdl_All_reports").modal("show");
  var qry = "CALL supplier_statments(" + parseInt(perNo, 10) + ");"; // numeric, no quotes
  $.post("config/SYD_Table.php", { qry: qry }, function(data){
    $("#rpt_show").html(data);
  });
});
  // sales statement
     $(".btn_sales_all").click(function(){
		$("#mdl_All_reports").modal("show");
		var qry="call sales_rep_all('"+$("#cbm_accou_sales_print").val()+"');";
		$.post("config/sales_report.php","qry="+qry,function(data){
			$("#rpt_show").html(data);
		});
	})
    $(".btn_summrysale_all").click(function () {
    $("#mdl_All_reports").modal("show");

    // Get invoice ID from input
    var invo_id = $("#invoice_id").val();

    if (invo_id === "") {
        alert("Please enter an Invoice ID");
        return;
    }

    var qry = "CALL summrysale(" + invo_id + ")";

    $.post("config/sales_report.php", { qry: qry }, function (data) {
        $("#rpt_show").html(data);
    });
});

  //  $(".btn_inventory_statement_all").click(function(){
	// 	$("#mdl_All_reports").modal("show");
	// 	var qry="call  inventory_statement()";
	// 	$.post("config/SYD_Table.php","qry="+qry,function(data){
 	// 	// alert(qry);
	// 		$("#rpt_show").html(data);
	// 	});
	// })

  $(".btn_payable_all").click(function(){
		$("#mdl_All_reports").modal("show");
		var qry="CALL GetAccountsPayable()";
		$.post("config/SYD_Table.php","qry="+qry,function(data){
 		// alert(qry);
			$("#rpt_show").html(data);
		});
	})
	
  $(".btn_receivable_all").click(function(){
		$("#mdl_All_reports").modal("show");
		var qry="call  GetAccountsReceivable()";
		$.post("config/SYD_Table.php","qry="+qry,function(data){
 		// alert(qry);
			$("#rpt_show").html(data);
		});
	})
//balance sheet
$(".btn_balance_sheet").click(function(){
    $("#mdl_All_reports").modal("show");

    // Load balance_sheet.php into modal body
    $.post("data/balance_sheet.php", function(data){
        $("#rpt_show").html(data);
    });
});

//   // between two date

$(".btn_inventory_statement_all").click(function() {
    $("#mdl_All_reports").modal("show");

    var date1 = $("#cbm_accou_date1_print").val();
    var date2 = $("#cbm_accou_date2_print").val();

    // Make sure both dates are selected
    if (!date1 || !date2) {
        alert("Please select both start and end dates.");
        return;
    }

    // Call stored procedure with two dates
    var qry = "CALL inventory_statement('" + date1 + "', '" + date2 + "');";

    $.post("config/SYD_Table.php", "qry=" + qry, function(data) {
        $("#rpt_show").html(data);
    });
});
// net income
$(".btn_net_income_all").click(function() {
    $("#mdl_All_reports").modal("show");

    var date3 = $("#cbm_accou_date3_print").val();
    var date4 = $("#cbm_accou_date4_print").val();

    if (!date3 || !date4) {
        alert("⚠️ Please select both start and end dates.");
        return;
    }

    var qry = "CALL auditBetweenDates('" + date3 + "', '" + date4 + "');";

    

    // ❌ nothing executes here
     $.post("data/net_income.php", "qry=" + qry, function(data) {
        $("#rpt_show").html(data);
    });
});
// end net
//balance sheet

// items
// items
$(document).ready(function () {
    $('#btn_update').hide();

    $('#btn_save').on('click', function (e) {
        e.preventDefault();

        // Disable Update button while saving
       

        // Prepare the form data for AJAX
        var formData = new FormData($('#imageUploadForm')[0]);

        $.ajax({
            url: 'insert_item.php', // 🔁 Change this to your PHP backend URL
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#btn_save').text('Saving...').prop('disabled', true);
            },
            success: function (response) {
                console.log("Server Response:", response);

                // Handle response (e.g., JSON, success message, error, etc.)
                // Assuming response returns "success"
                if (response.trim() === "success") {
                    alert('Item inserted successfully!');
          $('#myTable').load(location.href + ' #myTable > *', function () {
          $('#myTable').DataTable({ destroy: true, aaSorting: [[0, 'desc']] });
       });




                    $('#imageUploadForm')[0].reset();
                    $('#exampleModal').modal('hide');
                } else {
                    alert( response);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                alert('An error occurred while saving.');
            },
            complete: function () {
                $('#btn_save').text('Save').prop('disabled', false);
                $('#btn_update').prop('disabled', false);
            }
        });
    });
});
// When user clicks the update button in a row

// Keep selected item number here
 let item_no=null
$(document).on('click', '.btn-update', function() {
  // your update logic…
 $('#btn_save').hide();
    $('#btn_update').show();
  // Get index of the clicked button
  let rowIndex = $('.btn-update').index(this);

  // Get the corresponding .ID input value
  let itemNo = $('.ID').eq(rowIndex).val();

  // Build the query (⚠️ still unsafe if used directly in PHP)
  let sql = "select * from items where item_no='" + itemNo + "'";

  //alert(sql);

  // Example AJAX call (better to send only itemNo, not SQL)
   let ctr = $(".ctr");
   let item_no = $("#item_no");
  // then reload page or reload data

   $.ajax({
    url:"search.php",
    data:"qry="+sql,
    success:function(xog){
      arr=xog.split(",")
      item_no.val(arr[0])
      for(i=1; i<arr.length; i++){
        ctr[i].value=arr[i]
      
      }
    }
  })
  
  // or
  // loadItems();     // partial reload
});
$(function () {
  $('#btn_update').on('click', function (e) {
    e.preventDefault();

    const formEl = document.getElementById('imageUploadForm');
    const fd = new FormData(formEl);

    // Required fields
    if (!fd.get('item_no')) { alert('Missing item_no'); return; }
    if (!fd.get('i_cat')) { alert('Select a category'); return; }
    const price = parseFloat(fd.get('i_price'));
    if (!(price > 0)) { alert('Price must be greater than 0'); return; }

    const $btn = $('#btn_update');
    const originalText = $btn.text();
    $btn.prop('disabled', true).text('Updating...');

    $.ajax({
      url: 'data/update_item.php',
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      dataType: 'json'
    }).done(function (res) {
      if (res.success) {
        alert('Item updated successfully.');
            $('#myTable').load(location.href + ' #myTable > *', function () {
          $('#myTable').DataTable({ destroy: true, aaSorting: [[0, 'desc']] });
       });





        $('#exampleModal').modal('hide');
        // TODO: refresh grid/list if needed
      } else {
        alert(res.message || 'Update failed.');
      }
    }).fail(function (xhr) {
      alert('Server error: ' + (xhr.responseText || xhr.status));
    }).always(function () {
      $btn.prop('disabled', false).text(originalText);
    });
  });
});
// end items
// Delete
$(document).on("click", ".b2", function () {
  let index = $(".b2").index(this);
  btn_update.style.display = "block";
  btn_save.style.display = "none";
  let sql = "select * from items where item_no='" + inp[index].value + "'";
  id = inp[index].value;

  $.ajax({
    url: "search.php",
    data: { qry: sql },
    success: function (res) {
      let arr = res.split(",");
      id = arr[0];
      for (let i = 1; i < arr.length; i++) {
        ctr[i].value = arr[i];
      }

      // Basic confirm box
      if (confirm("Ma hubtaa inaad delete garayso?")) {
        operations("delete");
        alert("Delete successful.");
      } else {
        alert("Delete cancelled.");
      }
    }
  });
});




// $(document).ready(function () {
//   $('#myTable').DataTable();

  // Reset form on Add click
  // $('#added').on('click', function () {
  //   $('#imageUploadForm')[0].reset();
  //   $('#btn_save').show();
  //   $('#btn_update').hide();
  // });

  // function validateForm() {
  //   let valid = true;
  //   $('#imageUploadForm [required]').each(function () {
  //     if (!$(this).val()) {
  //       Swal.fire('Warning', 'Please fill all required fields!', 'warning');
  //       valid = false;
  //       return false;
  //     }
  //   });
  //   return valid;
  // }
// purchase
// example
// $(document).ready(function () {
//   // ADD ITEM
//   $('#addItem').click(function () {
//     const row = $('.item-row').first().clone();
//     row.find('input, select').val('');
//     $('#itemsContainer').append(row);
//   });

//   // REMOVE ITEM
//   $(document).on('click', '.remove-item', function () {
//     if ($('.item-row').length > 1) {
//       $(this).closest('.item-row').remove();
//     }
//   });

//   // ✅ SUBMIT HANDLER (insert or update)
//   $('#frm_purchase_order_multi').on('submit', function (e) {
//     e.preventDefault();

//     const form = this;
//     if (form.checkValidity() === false) {
//       form.classList.add('was-validated');
//       return;
//     }

//     // Decide action: insert or update
//     let actionUrl = $('#update_id').val() 
//         ? 'data/update_purchase_order.php'   // update existing
//         : 'data/submit_order1.php'; // insert new

// //     $.post(actionUrl, $(this).serialize(), function (res) {
// //       if (res.status === 'success' || res === 'success') {
// //         $('#purchaseModal').modal('hide'); // close modal
// //         $('#po-form-container').show();
// //         // $('#po-invoice').hide().html('');
// // // fetchInvoice(res.po_no);
// // alert(res)
// //         // Reload table to reflect changes
// //         $('#pur_table').load(location.href + ' #pur_table > *', function () {
// //           $('#pur_table').DataTable({ destroy: true, aaSorting: [[0, 'desc']] });
// //         });
// //       } else {
// //         alert('Error: ' + (res.message || res));
// //       }
// //     }, 'json').fail(function (xhr, status, error) {
// //       console.error('AJAX error:', status, error);
// //       console.log(xhr.responseText);
// //       alert('AJAX failed. Check the console for more info.');
// //     });
// //   });
//   //  $.post('data/submit_order1.php', $(this).serialize(), function (res) {
//   //           if (res.status === 'success') {
//   //               fetchInvoice(res.po_no);
//   //             //   $('#purchaseModal').modal('hide'); // close modal
//   //           } else {
//   //               alert('Error: ' + res.message);
//   //           }
//   //       }, 'json');
//   //   });

//   // ✅ LOAD INVOICE
//  function fetchInvoice(po_no) {
//         $.get('data/get_invoice.php', { po_no }, function (html) {
//             $('#po-invoice').html(html).show();
            
//         });
//     }

//   // ✅ RESET MODAL ON CLOSE
  // $('#purchaseModal').on('hidden.bs.modal', function () {
  //   $('#po-form-container').show();
  //   $('#po-invoice').hide().html('');
  //   $('#frm_purchase_order_multi')[0].reset();
  //   $('#frm_purchase_order_multi').removeClass('was-validated');
  //   $('#itemsContainer .item-row').slice(1).remove();
  //   $('#itemsContainer .item-row').first().find('input, select').val('');
  //   $('#update_id').val(''); // reset hidden field
  //   $('#purchaseModal .modal-title').text("Create Purchase Order");
  //   $('#purchaseModal button[type=submit]').text("Save Order");
  // });
// });
// save
$(document).ready(function () {

    // Add item row
    $('#addItem').click(function () {
        const row = $('.item-row').first().clone();

        // Clear values
        row.find('input').val('');
        row.find('select').val('');

        // Remove any Select2 container references
        row.find('.select2-container').remove();

        // Append clean row
        $('#itemsContainer').append(row);
    });

    // Remove item row
    $(document).on('click', '.remove-item', function () {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
        }
    });

    // Submit form via AJAX
    $('#frm_purchase_order_multi').on('submit', function (e) {
        e.preventDefault();
        const form = this;

        if (form.checkValidity() === false) {
            form.classList.add('was-validated');
            return;
        }

        $.post('data/submit_order1.php', $(this).serialize(), function (res) {
            if (res.status === 'success') {
                fetchInvoice(res.po_no);
                $('#pur_table').load(location.href + ' #pur_table > *', function () {
                    $('#pur_table').DataTable({ destroy: true, aaSorting: [[0, 'desc']] });
                });
            } else {
                alert('Error: ' + res.message);
            }
        }, 'json');
    });

    function fetchInvoice(po_no) {
        $.get('data/get_invoice.php', { po_no }, function (html) {
            $('#po-form-container').hide();
            $('#po-invoice').html(html).show();
        });
    }

    // Reset modal when closed
    $('#purchaseModal').on('hidden.bs.modal', function () {
        $('#po-form-container').show();
        $('#po-invoice').hide().html('');
        $('#frm_purchase_order_multi')[0].reset();
        $('#frm_purchase_order_multi').removeClass('was-validated');
        $('#itemsContainer .item-row').slice(1).remove();
        $('#itemsContainer .item-row').first().find('input, select').val('');
        $('#update_id').val(''); 
        $('#purchaseModal .modal-title').text("Create Purchase Order");
        $('#purchaseModal button[type=submit]').text("Save Order");
    });
});
//end save

// ✅ UPDATE BUTTON CLICK
$(document).on('click', '.btn-updates', function () {
    let id       = $(this).data('id');
    let supp     = $(this).data('supp');
    let branch   = $(this).data('branch');
    let date     = $(this).data('date');
    let item     = $(this).data('item');
    let cost     = $(this).data('cost');
    let qty      = $(this).data('qty');
    let discount = $(this).data('discount');

    // Fill hidden update_id input
    $('#update_id').val(id);

    // ✅ Directly set values (no Select2 trigger)
    $('#purchaseModal select[name="per_no"]').val(supp);
    $('#purchaseModal select[name="branch_num"]').val(branch);
    $('#purchaseModal input[name="order_date"]').val(date);

    // Fill the first row
    let row = $('#itemsContainer .item-row').first();
    row.find('select[name="item_no[]"]').val(item);
    row.find('input[name="cost[]"]').val(cost);
    row.find('input[name="qty[]"]').val(qty);
    row.find('input[name="discount[]"]').val(discount);

    // Update modal title + button
    $('#purchaseModal .modal-title').text("Update Purchase Order");
    $('#purchaseModal button[type=submit]').text("Update Order");
});

//end purchase
 
</script>

