<?php 
$qry = $_POST['qry'];
include("SYD_Class.php");
$coder = new sydClass();

// ensure connection is created
$coder->connection();
$conn = $coder->db;   // mysqli object

if ($conn->multi_query($qry)) {
    $setIndex = 0;
    do {
        if ($res = $conn->store_result()) {
            if ($setIndex == 0) {
                // First result set: HEADER (only one row expected)
                $header = $res->fetch_assoc();
                echo "<div class='card p-3 mb-3' style='background:#f8f9fa'>";
                echo "<h5 class='text-primary'>Invoice Header</h5>";
                echo "<p><strong>Invoice No:</strong> {$header['invoice_id']}</p>";
                echo "<p><strong>Customer:</strong> {$header['customer_name']}</p>";
                echo "<p><strong>VAT:</strong> {$header['vat']} | 
                           <strong>Discount:</strong> {$header['discount']}</p>";
                echo "<p><strong>Total:</strong> {$header['total_amount']} | 
                           <strong>Paid:</strong> {$header['paid_amount']} | 
                           <strong>Balance:</strong> {$header['balance']}</p>";
                echo "</div>";
            } else {
                // Second result set: ITEMS (multiple rows)
                echo "<h5 class='text-primary'>Invoice Items</h5>";
                echo "<table class='table table-bordered table-sm'>";
                echo "<thead class='table-info'>
                        <tr>
                          <th>Item</th><th>Qty</th><th>Price</th><th>Line Total</th>
                        </tr>
                      </thead><tbody>";
                while ($row = $res->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['item_name']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['price']}</td>
                            <td>{$row['line_total']}</td>
                          </tr>";
                }
                echo "</tbody></table>";
            }
            $res->free();
            $setIndex++;
        }
    } while ($conn->next_result());
}
?>
<style>
#Reports_table {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
#Reports_table td, #Reports_table th {
  border: 1px solid #ddd;
  padding: 8px;
}
#Reports_table tr:hover {background-color: #ddd;}
#Reports_table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #696cff !important;
  color: white;
  font-size: 13px;
}
</style>