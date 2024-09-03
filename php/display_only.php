<?php
$csv_url = 'https://docs.google.com/spreadsheets/d/19q86Qia2R48v_nvR6xzbtBS9Nw1h6O0LfbZtYKW7Eds/pub?output=csv';
$csv_data = file_get_contents($csv_url);
$rows = array_map('str_getcsv', explode("\n", $csv_data));
$header = array_shift($rows);   // Skip the header row
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance List</title>
    <style>
        body {
            margin: 0 20%;
        }
        .title-sec {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #000;
        }
        .pagination a.active {
            background-color: #8f8787c5;
            color: white;
        }
    </style>
</head>
<body>
    <div class="title-sec">
        <h1>Maintenance List</h1>
        <a href="https://docs.google.com/spreadsheets/d/19q86Qia2R48v_nvR6xzbtBS9Nw1h6O0LfbZtYKW7Eds/edit?usp=sharing">Edit here</a>
    </div>

    <label for="search">Search:</label>
    <input type="text" id="search" placeholder="Search by first column...">

    <label for="rows_per_page">Rows per page:</label>
    <select id="rows_per_page">
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
    </select>

    <table>
        <thead>
            <tr>
                <?php foreach ($header as $col_name): ?>
                    <th><?php echo htmlspecialchars($col_name); ?></th>
                <?php endforeach; ?>
                <th>Days left until end date</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <!-- Rows will be injected here by JavaScript -->
        </tbody>
    </table>

    <div class="pagination" id="pagination">
        <!-- Pagination links will be injected here by JavaScript -->
    </div>

    <script>
        const rows = <?php echo json_encode($rows); ?>;
        const header = <?php echo json_encode($header); ?>;
        let filteredRows = [...rows];
        let currentPage = 1;
        let rowsPerPage = 10;

        function calculateDaysLeft(endDate) {
            const [day, month, year] = endDate.split('-');  // Split the date into day, month, year
            const formattedEndDate = `${year}-${month}-${day}`;  // Reformat to yyyy-mm-dd
            const currentDate = new Date().toISOString().split('T')[0];
            const endDateObj = new Date(formattedEndDate);
            const diffTime = endDateObj - new Date(currentDate);
            return Math.floor(diffTime / (1000 * 60 * 60 * 24));
        }

        function renderTable(page = 1) {
            currentPage = page;
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedRows = filteredRows.slice(start, end);

            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            paginatedRows.forEach(row => {
                const companyEmail = row[0] || '';
                const secondColumn = row[1] || '';
                const endDate = row[2] || '';
                const daysLeft = endDate ? calculateDaysLeft(endDate) : 'Invalid date';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${companyEmail}</td>
                    <td>${secondColumn}</td>
                    <td>${endDate}</td>
                    <td>${daysLeft < 0 ? "No need liao hehe." : "Days left: " + daysLeft}</td>
                `;
                tableBody.appendChild(tr);
            });

            renderPagination();
        }

        function renderPagination() {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';

            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            const maxPagesToShow = 4;
            let startPage = Math.max(currentPage - Math.floor(maxPagesToShow / 2), 1);
            let endPage = Math.min(startPage + maxPagesToShow - 1, totalPages);

            if (endPage - startPage < maxPagesToShow - 1) {
                startPage = Math.max(endPage - maxPagesToShow + 1, 1);
            }

            if (currentPage > 1) {
                const topLink = document.createElement('a');
                topLink.href = '#';
                topLink.innerText = 'Top';
                topLink.addEventListener('click', () => renderTable(1));
                paginationDiv.appendChild(topLink);

                const prevLink = document.createElement('a');
                prevLink.href = '#';
                prevLink.innerText = 'Prev';
                prevLink.addEventListener('click', () => renderTable(currentPage - 1));
                paginationDiv.appendChild(prevLink);
            }

            for (let i = startPage; i <= endPage; i++) {
                const a = document.createElement('a');
                a.href = '#';
                a.innerText = i;
                a.classList.toggle('active', i === currentPage);
                a.addEventListener('click', () => renderTable(i));
                paginationDiv.appendChild(a);
            }

            if (currentPage < totalPages) {
                const nextLink = document.createElement('a');
                nextLink.href = '#';
                nextLink.innerText = 'Next';
                nextLink.addEventListener('click', () => renderTable(currentPage + 1));
                paginationDiv.appendChild(nextLink);

                const lastLink = document.createElement('a');
                lastLink.href = '#';
                lastLink.innerText = 'Last';
                lastLink.addEventListener('click', () => renderTable(totalPages));
                paginationDiv.appendChild(lastLink);
            }
        }

        function filterRows(searchValue) {
            filteredRows = rows.filter(row => row[0].toLowerCase().includes(searchValue.toLowerCase()));
            renderTable(1);
        }

        document.getElementById('search').addEventListener('input', function () {
            filterRows(this.value);
        });

        document.getElementById('rows_per_page').addEventListener('change', function () {
            rowsPerPage = parseInt(this.value);
            renderTable(1);
        });

        renderTable();
    </script>
</body>
</html>
