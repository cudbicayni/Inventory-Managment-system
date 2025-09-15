<?php  
$db = new mysqli("localhost", "root", "", "sos1");
$sql = "select * from registration";
$res = $db->query($sql);
$fields = $res->fetch_fields();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extra Large Modal with Print, DataTable, and Action Buttons</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
   
</head>
<style>
  
  /* Custom styles for centering content and adding padding */
body {
    margin: 0;
    padding: 0;
}

/* Center content and set specific page size for print */
@media print {
    body {
        margin: 0;
        padding: 0;
        width: 8.5in; /* Letter size width */
        height: 11in; /* Letter size height */
    }
    #modalContent {
        margin: auto; /* Center the modal content */
        width: 90%; /* Adjust width for better alignment */
        padding-left: 2in; /* Add padding from the left */
        padding-right: 1in; /* Optional: Add padding from the right */
        box-sizing: border-box;
        background-color: white; Ensure white background for print
    }

    /* Hide everything else during printing */
    body * {
        visibility: hidden;
    }
    #modalContent, #modalContent * {
        visibility: visible;
    }
    #printButton {
        display: none; /* Hide the print button */
        width:50px;
    }
}
</style>
<body>
    <!-- Button to trigger modal -->
    <div class="container mt-5">
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#extraLargeModal">
            Launch Extra Large Modal
        </button> -->
    </div>

    <!-- Extra Large Modal -->
    <div class="modal fade" id="extraLargeModal" tabindex="-1" aria-labelledby="extraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="extraLargeModalLabel"> Birth Notification </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="birth-notification">
                    <div class="header">
                        <img src="birth.png" class="logo">
                    </div>
                        
                    <div class="id">
                        <div class="no">No. <b> <span id="sos_no"></span></b></div>
                    </div>
                        <!-- <div class="form-title">Birth Notification</div> -->

                        <div class="form">
                            <p><strong>Baby Name:   <span id="name"></span></strong> </p>
                            <div class="form-line"></div>
                        </div>
                        <div class="footer">A Loving home for every child</div>
                    </div>
                    <div class="invoice">
                        <div class="invoice-header">
                            This is to certify that in the maternity ward of this clinic has delivered<br>
                            <small>Waxaa la cadeynayaa in qaybta umulaha ee Isbitaalka ay ku dhashay</small>
                        </div>
                        <div class="section">
                            <div class="left">
                                <input type="hidden" name="prc" value="" class="ctr">
                                <span><span class="label">Mrs:</span><span class="ctr"></span> </span>
                                <span><span class="label">Age:</span> <span class="ctr"></span> </span>
                                <span><span class="label">Profession:</span> <span class="ctr"></span> </span>
                                <span><span class="label">Hospital Director:</span> <span class="ctr"></span> </span>
                                <span><span class="label">Date:</span> <span class="ctr"></span> </span>
                                <span><span class="label">Tell:</span> <span class="ctr"></span> </span>
                                <span><span class="label">weight:</span> <span class="ctr"></span> </span>
                            </div>
                            <div class="right">
                                
                                <span><span class="label">Gender:</span> <span class="ctr"></span> </span>
                                <span><span class="label">Father Name:</span> <span class="ctr"></span> </span>
                                <span><span class="label">Profession Father:</span> <span class="ctr"></span> </span>
                                <span><span class="label">Midwife:</span> <span class="ctr"></span> </span>
                             
                            </div>
                        </div>
                        <div class="divider"></div>
                    </div>
                    <div class="mt-6 text-center">
                    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="printBtn">Print</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col">
        <table id="example" class="display table table-bordered table-hover" style="width:100%">
                <thead class="table-dark">
                    <tr>
                    <?php foreach ($fields as $key => $value): ?>
                          <th><?php echo $value->name; ?></th>
                      <?php endforeach; ?>
                          <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($res as $row): ?>
                    <tr>
                    <?php foreach ($row as $key => $value): ?>
                        <td><?php echo $value; ?></td>
                    <?php endforeach; ?>
                        <td>
                            <input class="inp" type="hidden" name="id" value="<?php echo $row['sos_no']; ?>">
                            <button class="btn btn-success btn-sm b1" data-bs-toggle="modal" data-bs-target="#extraLargeModal" data-id="<?php echo $row['sos_no']; ?>">Show</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                paging: true, // Enable pagination
                searching: true, // Enable search
                lengthChange: true, // Enable "Show Entries" dropdown
                pageLength: 5 // Default number of entries to show
            });
        });

        var inp = document.getElementsByClassName("inp");
        var ctr = document.getElementsByClassName("ctr");
        var no = document.getElementById("sos_no");
        var form = document.getElementById("name");

        $(document).on("click", ".b1", function() {
            let index = $(".b1").index(this); 
            let sosNo = inp[index].value;
            let sql = "select * from registration where sos_no='" + inp[index].value + "'";

            $.ajax({
                url: "search.php",
                data: { qry: sql },
                success: function(res) {
                    let arr = res.split(","); 
                    no.innerHTML = arr[0];
                    form.innerHTML = arr[12];

                    for (let i = 1; i < arr.length + 1; i++) {
                        ctr[i].innerHTML = arr[i];
                    }

                    // Move the modal to the parent window and show it
                    let modal = $("#extraLargeModal", window.parent.document);
                    modal.appendTo(window.parent.document.body);
                    modal.modal("show");
                }
            });
        });

        document.getElementById("printBtn").addEventListener("click", function () {
            window.print();
        });
    </script>
</body>
</html>