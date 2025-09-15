<?php 
include "Codes.php";
$coder = new Codes();
$coder->setConnect();

$db = new mysqli("localhost", "root", "", "invent");

$sql = "SELECT item_no AS ID, c.cat_name AS category, item_name AS Items, Price, balance
        FROM items i
        JOIN categories c ON c.cat_no = i.cat_no
        ORDER BY item_no ASC";

$res = $db->query($sql);
$fields = $res->fetch_fields();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Items DataTable</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap 4 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- DataTables CSS (Bootstrap 4) -->
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
</head>
<body>

<button type="button" class="btn btn-primary m-3" data-toggle="modal" data-target="#exampleModal">
  Open Modal
</button>

<!-- Modal -->
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
          <input type="hidden" name="items_pro" value="items_pro">
          <input type="hidden" name="item_no" id="item_no">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cat">Category</label>
              <select name="i_cat" id="cat" class="form-control" required>
                <option value="">Select Category</option>
                <?php
                  $coder->fillCombo("SELECT cat_no, cat_name as Name FROM categories", "cbm_reg_cat_no_print", "Select category");
                ?>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="image">Item Image</label>
              <input type="file" name="txtfile" id="image" class="form-control-file" accept="image/*">
              <small id="image_filename" class="form-text text-muted"></small>
            </div>

            <div class="form-group col-md-6">
              <label for="item_name">Item Name</label>
              <input type="text" name="i_name" id="item_name" class="form-control" placeholder="Enter item name" required>
            </div>

            <div class="form-group col-md-6">
              <label for="price">Price</label>
              <input type="number" name="i_price" id="price" class="form-control" placeholder="Enter price" required>
            </div>

            <div class="form-group col-md-6">
              <label for="balance">Balance</label>
              <input type="number" name="i_balance" id="balance" class="form-control" placeholder="Enter balance" required>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btn_save">Save</button>
          <button type="button" class="btn btn-primary" id="btn_update" style="display:none;">Update</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="container mt-4">
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
            <button class="btn btn-sm btn-info btn-update" data-id="<?=$row['ID']?>" data-toggle="modal" data-target="#exampleModal">Update</button>
            <button class="btn btn-sm btn-danger btn-delete" data-id="<?=$row['ID']?>">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- JS libs -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
  $('#myTable').DataTable();

  function validateForm() {
    let valid = true;
    $('#imageUploadForm select[required], #imageUploadForm input[required]').each(function(){
      if(!$(this).val()) {
        alert('Please fill all required fields.');
        valid = false;
        return false; // break
      }
    });
    return valid;
  }

  $('#btn_save').click(function(e) {
    e.preventDefault();
    if (!validateForm()) return;

    let formData = new FormData($('#imageUploadForm')[0]);
    formData.append('oper', 'insert');

    $.ajax({
      url: 'insert_item.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(res) {
        alert('Insert successful: ' + res);
        $('#exampleModal').modal('hide');
        location.reload();
      },
      error: function() {
        alert('Error during insert operation.');
      }
    });
  });

  // TODO: add btn_update and btn_delete handlers

});
</script>

</body>
</html>
