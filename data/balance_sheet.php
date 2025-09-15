<?php
// Database connection
$pdo = new PDO("mysql:host=localhost;dbname=invent", "root", "");

// Your SQL query
$sql = "
SELECT  
    (SELECT SUM(balance) FROM accounts) AS Cash_And_Bank,
    (SELECT SUM(total_accounts_receivable) FROM AccountsReceivable) AS AccountsReceivable,
    (SELECT SUM(balance) FROM items) AS Inventory,
    (
        (SELECT SUM(balance) FROM accounts) +
        (SELECT SUM(total_accounts_receivable) FROM AccountsReceivable) +
        (SELECT SUM(balance) FROM items)
    ) AS Total_Assets,
    COALESCE((SELECT SUM(total_accounts_payable) FROM AccountsPayable), 0) AS AccountsPayable,
    COALESCE((SELECT SUM(amount) FROM expense_payment), 0) AS Other_Liabilities,
    (
        COALESCE((SELECT SUM(total_accounts_payable) FROM AccountsPayable), 0) +
        COALESCE((SELECT SUM(amount) FROM expense_payment), 0)
    ) AS Total_Liabilities,
    (
        (
            COALESCE((SELECT SUM(balance) FROM accounts), 0) +
            COALESCE((SELECT SUM(total_accounts_receivable) FROM AccountsReceivable), 0) +
            COALESCE((SELECT SUM(balance) FROM items), 0)
        ) - 
        (
            COALESCE((SELECT SUM(total_accounts_payable) FROM AccountsPayable), 0) +
            COALESCE((SELECT SUM(amount) FROM expense_payment), 0)
        )
    ) AS Total_Equity
";

$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate Liabilities + Equity
$liabilities_and_equity = $row['Total_Liabilities'] + $row['Total_Equity'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Balance Sheet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
      body { background: #f9fafb; }
      .balance-sheet {
          max-width: 800px;
          margin: 20px auto;
          background: white;
          padding: 25px;
          border-radius: 10px;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      }
      h2 { text-align: center; margin-bottom: 25px; }
      .table th { background: #f0f2f5; }
      .highlight { font-weight: bold; background: #eef6ff; }
      
  </style>
</head>
<body>

<div class="balance-sheet">
  <!-- Print Button at Top -->
  

  <h2>Balance Sheet</h2>
  <table class="table table-bordered">
    <tbody>
      <tr><th colspan="2">Assets</th></tr>
      <tr><td>Cash & Bank</td><td><?= number_format($row['Cash_And_Bank'], 2) ?></td></tr>
      <tr><td>Accounts Receivable</td><td><?= number_format($row['AccountsReceivable'], 2) ?></td></tr>
      <tr><td>Inventory</td><td><?= number_format($row['Inventory'], 2) ?></td></tr>
      <tr class="highlight"><td>Total Assets</td><td><?= number_format($row['Total_Assets'], 2) ?></td></tr>

      <tr><th colspan="2">Liabilities</th></tr>
      <tr><td>Accounts Payable</td><td><?= number_format($row['AccountsPayable'], 2) ?></td></tr>
      <tr><td>Other Liabilities</td><td><?= number_format($row['Other_Liabilities'], 2) ?></td></tr>
      <tr class="highlight"><td>Total Liabilities</td><td><?= number_format($row['Total_Liabilities'], 2) ?></td></tr>

      <tr class="highlight"><td>Total Equity</td><td><?= number_format($row['Total_Equity'], 2) ?></td></tr>
      
      <!-- New Row: Liabilities + Equity -->
      <tr class="highlight table-success"><td>Total Liabilities + Equity</td><td><?= number_format($liabilities_and_equity, 2) ?></td></tr>
    </tbody>
  </table>
</div>

</body>
</html>
