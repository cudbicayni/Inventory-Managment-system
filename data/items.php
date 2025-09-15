<?php 
include "Codes.php";
$ob=new Codes();
$ob->setConnect();
?>
<link rel="stylesheet" 
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Bootstrap JS -->
 <!-- datatable -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <!-- end datatable -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<button type="button" class="btn btn-primary m-3" data-toggle="modal" data-target="#exampleModal" id="added">
  Add Item
</button>
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
                  $ob->fillCombo("SELECT cat_no, cat_name as Name FROM categories", "cbm_reg_cat_no_print", "Select category");
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

<?php
  $db = new mysqli("localhost", "root", "", "invent");
$sql = "SELECT item_no AS ID, c.cat_name AS category, item_name AS Items, Price, balance
        FROM items i
        JOIN categories c ON c.cat_no = i.cat_no
        ORDER BY item_no ASC";

$res = $db->query($sql);
$fields = $res->fetch_fields();
?>
<table id="myTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
      <tr>
        <?php foreach ($fields as $field): ?>
          <th><?=htmlspecialchars($field->name)?></th>
        <?php endforeach; ?>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($res as $row): ?>
        <tr>
          <?php foreach ($row as $value): ?>
            <td><?=htmlspecialchars($value)?></td>
          <?php endforeach; ?>
          <td>
             <input type="hidden" class="ID" value="<?php echo $row['ID']; ?>">
<button 
  class="btn btn-sm btn-info btn-update" 
  data-id="<?=$row['ID']?>" 
  data-toggle="modal" 
  data-target="#exampleModal">Update</button>

            <button class="btn btn-sm btn-danger btn-delete" data-id="<?=$row['ID']?>">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
      url: 'update_item.php',
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      dataType: 'json'
    }).done(function (res) {
      if (res.success) {
        alert('Item updated successfully.');
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
     

     
</script>