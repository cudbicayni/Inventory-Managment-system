<?php
// Database connection
$host = 'localhost';  // Your database host
$dbname = 'Inventory1';  // Your database name
$username = 'root';  // Your database username
$password = '';  // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Query to calculate Sales Revenue
$salesRevenueQuery = "SELECT FORMAT(SUM(amount), 2) AS total_sales FROM receipts";
$salesRevenue = $pdo->query($salesRevenueQuery)->fetch(PDO::FETCH_ASSOC)['total_sales'];

// Query to calculate individual expenses
$expensesQuery = "
    SELECT e.Exp_name AS expense_name, FORMAT(SUM(ep.amount), 2) AS total_expense
    FROM expenses e
    JOIN expense_payment ep ON e.ex_no = ep.ex_no
    GROUP BY e.ex_no
";
$expenses = $pdo->query($expensesQuery)->fetchAll(PDO::FETCH_ASSOC);

// Query to calculate Total Expenses
$totalExpensesQuery = "SELECT FORMAT(SUM(amount), 2) AS total_expenses FROM expense_payment";
$totalExpenses = $pdo->query($totalExpensesQuery)->fetch(PDO::FETCH_ASSOC)['total_expenses'];

// Calculate Net Income
$netIncome = number_format(floatval(str_replace(',', '', $salesRevenue)) - floatval(str_replace(',', '', $totalExpenses)), 2);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Statement Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 2px solid #4CAF50;
            display: inline-block;
            padding-bottom: 5px;
        }
        .section table {
            width: 100%;
            border-collapse: collapse;
        }
        .section table th, .section table td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .section table th {
            background: #f4f4f4;
        }
        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            background: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }
        /* Print Button Style */
        .print-btn {
            display: block;
            width: 150px;
            padding: 10px;
            margin: 20px auto;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-btn:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            Income Statement Report
        </div>
        <div class="content">
            <!-- Sales Revenue Section -->
            <div class="section">
                <h2>Revenue</h2>
                <table>
                    <tr>
                        <td>Total Sales Revenue</td>
                        <td style="text-align: right;">$<?= $salesRevenue ?></td>
                    </tr>
                </table>
            </div>

            <!-- Expenses Section -->
            <div class="section">
                <h2>Expenses</h2>
                <table>
                    <tr>
                        <th>Expense Category</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td><?= htmlspecialchars($expense['expense_name']) ?></td>
                            <td style="text-align: right;">$<?= $expense['total_expense'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="total">
                    Total Expenses: $<?= $totalExpenses ?>
                </div>
            </div>

            <!-- Net Income Section -->
            <div class="section">
                <h2>Net Income</h2>
                <table>
                    <tr>
                        <td style="font-weight: bold;">Net Income</td>
                        <td style="text-align: right; font-weight: bold;">$<?= $netIncome ?></td>
                    </tr>
                </table>
            </div>

            <!-- Print Button -->
            <button class="print-btn" onclick="printReport()">Print Report</button>
        </div>
        <div class="footer">
            &copy; <?= date('Y') ?> Income Statement Report
        </div>
    </div>
</body>
</html>
