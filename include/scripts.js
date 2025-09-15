// scripts.php content

$(document).ready(function () {
  // Modal reset and show logic for Add New button only
  $('#exampleModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    if (button.hasClass('btn-new')) {
      $('#btn_save').show();
      $('#btn_update').hide();
      $('#imageUploadForm')[0].reset();
      $('#image_filename').text('');
      $('#item_no').val('');
    }
  });

  // Open modal for Update button click
  $(document).on('click', '.btn-update', function () {
    $('#btn_update').show();
    $('#btn_save').hide();

    // Find the row data from the button's table row
    let tr = $(this).closest('tr');
    let id = tr.find('td:first').text().trim();

    $.ajax({
      url: 'search.php',
      type: 'GET',
      data: { qry: "SELECT item_no, cat_no, item_name, price, balance FROM items WHERE item_no='" + id + "'" },
      success: function (res) {
        let arr = res.split(',').map(x => x.trim());
        if (arr.length >= 5) {
          $('#item_no').val(arr[0]);
          $('#cat').val(arr[1]);
          $('#item_name').val(arr[2]);
          $('#price').val(arr[3]);
          $('#balance').val(arr[4]);
          $('#image_filename').text(''); // File input cannot be pre-filled
          $('#exampleModal').modal('show');
        } else {
          alert('Error loading item data.');
        }
      },
      error: function () {
        alert('AJAX error while fetching item data.');
      }
    });
  });

  // Delete button click
  $(document).on('click', '.btn-delete', function () {
    if (!confirm('Ma hubtaa inaad delete garayso?')) return;

    let tr = $(this).closest('tr');
    let id = tr.find('td:first').text().trim();

    $.ajax({
      url: 'opera.php',
      type: 'POST',
      data: { oper: 'delete', id: id },
      success: function (res) {
        alert('Delete successful: ' + res);
        location.reload(); // Refresh table after delete
      },
      error: function () {
        alert('Error during delete operation.');
      }
    });
  });

  // Save (insert) button click
  $('#btn_save').on('click', function (e) {
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
      success: function (res) {
        alert('Insert successful: ' + res);
        $('#exampleModal').modal('hide');
        location.reload();
      },
      error: function () {
        alert('Error during insert operation.');
      }
    });
  });

  // Update button click
  $('#btn_update').on('click', function (e) {
    e.preventDefault();
    if (!validateForm()) return;

    let formData = new FormData($('#imageUploadForm')[0]);
    formData.append('oper', 'update');

    $.ajax({
      url: 'opera.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function (res) {
        alert('Update successful: ' + res);
        $('#exampleModal').modal('hide');
        location.reload();
      },
      error: function () {
        alert('Error during update operation.');
      }
    });
  });

  // Validate form fields except file input
  function validateForm() {
    let valid = true;
    $('#imageUploadForm').find('input, select').not('[type="file"]').each(function () {
      if ($(this).val().trim() === '') {
        alert('Fadlan formka buuxi');
        valid = false;
        return false; // break loop
      }
    });
    return valid;
  }
});
