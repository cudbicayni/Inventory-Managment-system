<?php
define("APP_PATH", __DIR__ . "/../");
include(APP_PATH."config/SYD_Class.php");
$coder = new sydClass();
$qry = $_POST['qry'];
$result = $coder->search($qry);

echo '<div class="p-3">';
echo '<h4 class="text-center mb-4">Income Statement</h4>';
echo '<table class="table table-bordered income-statement">';
while($row = $result->fetch_assoc()){
    $desc  = $row['description'];
    $amt   = number_format($row['amount'],2);
    $type  = $row['type'];

    $rowClass = '';
    if ($desc == 'Gross Profit' || $desc == 'Operating Profit') {
        $rowClass = 'subtotal';
    } elseif ($desc == 'Net Income') {
        $rowClass = 'netincome';
    } elseif ($type == 'Expense') {
        $rowClass = 'expense';
    } elseif ($type == 'Revenue') {
        $rowClass = 'revenue';
    }

    echo "<tr class='$rowClass'><td>$desc</td><td class='text-end'>$amt</td></tr>";
}
echo '</table></div>';
?>
<style>
.income-statement {
  width: 100%;
  background: #fff;
  font-size: 15px;
}
.income-statement td {
  padding: 8px 12px;
}
.income-statement .revenue td {
  font-weight: 600;
}
.income-statement .expense td {
  padding-left: 30px;
  color: #444;
}
.income-statement .subtotal td {
  font-weight: 700;
  border-top: 2px solid #000;
}
.income-statement .netincome td {
  font-weight: 800;
  font-size: 16px;
  background: #696cff;
  color: white;
}
.text-end {
  text-align: right;
}
</style>