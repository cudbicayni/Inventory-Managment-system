<?php
$qry = $_POST['qry'];

$conn = new mysqli("localhost", "root", "", "invent"); // adjust connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query($qry);

echo '<table class="table table-hover table-bordered align-middle">';
echo '<thead class="table-primary text-center">';
echo '<tr>
        <th>Description</th>
        <th class="text-end">Amount</th>
        <th>Type</th>
      </tr>';
echo '</thead><tbody>';

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $type = $row['type'];
        $class = '';

        if ($type == 'Total') {
            $class = 'table-info fw-bold';
        } elseif ($type == 'VAT') {
            $class = 'table-warning fw-bold';
        } elseif ($type == 'Discount') {
            $class = 'table-danger fw-bold';
        } elseif ($type == 'Net Sales') {
            $class = 'table-success fw-bold';
        } elseif ($type == 'Expense') {
            $class = '';
        } elseif ($type == 'TotalExpense') {
            $class = 'table-warning fw-bold';
        } elseif ($type == 'Result') {
            $class = 'table-success fw-bold text-white bg-success';
        }

        echo '<tr class="'.$class.'">';
        echo '<td>'.htmlspecialchars($row['description']).'</td>';
        echo '<td class="text-end">'.number_format($row['amount'],2).'</td>';
        echo '<td>'.htmlspecialchars($row['type']).'</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3" class="text-center text-muted">No data found</td></tr>';
}

echo '</tbody></table>';
?>