<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Table with Row Limit</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Controls */
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .controls select,
        .controls input,
        .controls button {
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            outline: none;
        }
        .controls input {
            max-width: 300px;
        }
        .controls button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            border: none;
        }
        .controls button:hover {
            background-color: #0056b3;
        }
        .data-counter {
            font-size: 14px;
            color: #6c757d;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border: 1px solid #dee2e6;
        }
        th {
            background-color: #f1f1f1;
            cursor: pointer;
        }
        th.sortable {
            user-select: none;
        }
        th.sortable span {
            font-size: 0.8rem;
            margin-left: 5px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .actions button {
            padding: 6px 10px;
            font-size: 12px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .actions button.update {
            background-color: #ffc107;
            color: #000;
        }
        .actions button.delete {
            background-color: #dc3545;
            color: #fff;
        }
        .actions button:hover.update {
            background-color: #e0a800;
        }
        .actions button:hover.delete {
            background-color: #b21f2d;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .controls {
                flex-direction: column;
                gap: 10px;
            }
            .controls input,
            .controls select,
            .controls button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dynamic Table with Row Limit</h1>

        <?php
        // Database credentials
        $host = 'localhost'; // Database host
        $username = 'root'; // Database username
        $password = ''; // Database password
        $dbname = 'invent'; // Database name

        // Connect to the database
        $conn = new mysqli($host, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Function to read and display a table
        function readTable($query, $conn) {
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                // Fetch columns
                $columns = array_keys($result->fetch_assoc());
                $result->data_seek(0); // Reset pointer

                // Controls
                echo "
                <div class='controls'>
                    <div>
                        <label for='entriesSelect'>Show</label>
                        <select id='entriesSelect'>
                            <option value='10'>10</option>
                            <option value='20'>20</option>
                            <option value='50'>50</option>
                            <option value='100'>100</option>
                            <option value='1000'>1000</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div>
                        <input id='searchInput' type='text' placeholder='Search table...'>
                    </div>
                    <div>
                        <button id='downloadCSV'>Download CSV</button>
                    </div>
                    <div>
                        <span id='dataCounter' class='data-counter'>Showing 0 of {$result->num_rows} entries</span>
                    </div>
                </div>";

                // Display table
                echo "<table id='datatable'>";
                echo "<thead><tr>";
                foreach ($columns as $col) {
                    echo "<th data-order='asc' class='sortable'>$col<span></span></th>";
                }
                echo "<th>Actions</th>";
                echo "</tr></thead><tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($columns as $col) {
                        echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
                    }
                    echo "<td class='actions'>
                            <button class='update'>Update</button>
                            <button class='delete'>Delete</button>
                        </td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No data available for this query.</p>";
            }
        }

        // Example usage of the function
        // readTable("SELECT * FROM customers", $conn); // Replace 'customers' with your desired table or query

        // $conn->close();
        ?>
    </div>

    <script>
        function initializeTable(tableId, searchInputId, entriesSelectId, dataCounterId, downloadButtonId) {
            const table = document.getElementById(tableId);
            const searchInput = document.getElementById(searchInputId);
            const dataCounter = document.getElementById(dataCounterId);
            const tableBody = table.querySelector('tbody');
            const headers = table.querySelectorAll('.sortable');
            const entriesSelect = document.getElementById(entriesSelectId);
            const downloadButton = document.getElementById(downloadButtonId);

            let rows = Array.from(tableBody.querySelectorAll('tr')); // All rows
            let currentLimit = parseInt(entriesSelect.value); // Initial limit

            // Update visible rows based on the current limit
            function updateRows() {
                rows.forEach((row, index) => {
                    row.style.display = index < currentLimit ? '' : 'none';
                });
                updateDataCounter();
            }

            // Update the data counter
            function updateDataCounter() {
                const visibleRows = rows.filter(row => row.style.display !== 'none');
                dataCounter.textContent = `Showing ${visibleRows.length} of ${rows.length} entries`;
            }

            // Handle entries limit change
            entriesSelect.addEventListener('change', function () {
                currentLimit = parseInt(entriesSelect.value);
                updateRows();
            });

            // Search functionality
            searchInput.addEventListener('input', function () {
                const filter = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                    row.style.display = rowText.includes(filter) ? '' : 'none';
                });

                updateDataCounter();
            });

            // Sorting functionality
            headers.forEach(header => {
                header.addEventListener('click', function () {
                    const columnIndex = Array.from(header.parentElement.children).indexOf(header);
                    const order = header.getAttribute('data-order');
                    const isAscending = order === 'asc';

                    rows = rows.sort((a, b) => {
                        const aText = a.children[columnIndex].textContent.trim();
                        const bText = b.children[columnIndex].textContent.trim();

                        return isAscending
                            ? aText.localeCompare(bText, undefined, { numeric: true })
                            : bText.localeCompare(aText, undefined, { numeric: true });
                    });

                    // Toggle sort order
                    header.setAttribute('data-order', isAscending ? 'desc' : 'asc');

                    // Update arrow
                    headers.forEach(h => h.querySelector('span').textContent = ''); // Clear all arrows
                    header.querySelector('span').textContent = isAscending ? '▲' : '▼';

                    // Reorder rows in the table
                    rows.forEach(row => tableBody.appendChild(row));

                    updateRows();
                });
            });

            // Initialize rows and data counter
            updateRows();

            // Download CSV functionality
            downloadButton.addEventListener('click', function () {
                const csvContent = [];
                const headerRow = Array.from(headers).map(header => header.textContent.trim());
                csvContent.push(headerRow.join(',')); // Add the headers

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const rowData = Array.from(cells).map((cell, index) => {
                        if (index === cells.length - 1) return ''; // Empty for the "Actions" column
                        
                        let cellData = cell.textContent.trim();

                        // If the column is a date column, ensure it's in MM/DD/YYYY format
                        if (headerRow[index].toLowerCase().includes('date')) {
                            const date = new Date(cellData);
                            if (!isNaN(date.getTime())) {
                                const formattedDate = (date.getMonth() + 1).toString().padStart(2, '0') + '/' +
                                                      date.getDate().toString().padStart(2, '0') + '/' +
                                                      date.getFullYear();
                                cellData = formattedDate;
                            }
                        }

                        // If the column is a number column, ensure it's a proper number
                        if (headerRow[index].toLowerCase().includes('number') && !isNaN(cellData)) {
                            cellData = parseFloat(cellData).toFixed(2); // Format as a number with 2 decimal places
                        }

                        return cellData;
                    });
                    csvContent.push(rowData.join(','));
                });

                const csvBlob = new Blob([csvContent.join('\n')], { type: 'text/csv' });
                const url = URL.createObjectURL(csvBlob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'table-data.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        }

        // Run the function for your table when the page loads
        document.addEventListener('DOMContentLoaded', function () {
            initializeTable('datatable', 'searchInput', 'entriesSelect', 'dataCounter', 'downloadCSV');
        });
    </script>
</body>
</html>
