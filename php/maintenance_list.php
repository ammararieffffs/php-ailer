<?php
session_start();

if (!isset($_SESSION['notified'])) {
    $_SESSION['notified'] = 0;
}

include "mail_function.php";
include "email_template.php";

$csv_url = 'https://docs.google.com/spreadsheets/d/19q86Qia2R48v_nvR6xzbtBS9Nw1h6O0LfbZtYKW7Eds/pub?output=csv';
$csv_data = file_get_contents($csv_url);
$rows = array_map('str_getcsv', explode("\n", $csv_data));
$header = array_shift($rows);   //this is to skip the header row

$current_date = date('Y-m-d');   //get today's date

// Pagination settings
$rows_per_page = isset($_GET['rows_per_page']) ? (int)$_GET['rows_per_page'] : 10; // Rows per page, default to 10
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page, default to 1
$total_rows = count($rows);
$total_pages = ceil($total_rows / $rows_per_page);
$start_index = ($current_page - 1) * $rows_per_page;

// Extract the subset of rows for the current page
$paginated_rows = array_slice($rows, $start_index, $rows_per_page);
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

    <form method="get">
        <label for="rows_per_page">Rows per page:</label>
        <select name="rows_per_page" id="rows_per_page" onchange="this.form.submit()">
            <option value="10" <?php if ($rows_per_page == 10) echo 'selected'; ?>>10</option>
            <option value="20" <?php if ($rows_per_page == 20) echo 'selected'; ?>>20</option>
            <option value="30" <?php if ($rows_per_page == 30) echo 'selected'; ?>>30</option>
        </select>
    </form>

    <table>
        <thead>
            <tr>
                <?php foreach ($header as $col_name): ?>
                    <th><?php echo htmlspecialchars($col_name); ?></th>
                <?php endforeach; ?>
                <th>Days left until end date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($paginated_rows as $row) {
                $company_email = $row[0] ?? '';
                $second_column = $row[1] ?? '';
                $end_date_str = $row[2] ?? '';
                $end_date = isset($row[2]) ? date('Y-m-d', strtotime($row[2])) : '';
                
                $remaining_days = null;
                if ($end_date) {
                    $remaining_seconds = strtotime($end_date) - strtotime($current_date);
                    $remaining_days = floor($remaining_seconds / (60 * 60 * 24));
                }
            
                if (($remaining_days !== null) && ($remaining_days <= 30) && ($remaining_days >= 0)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($company_email) . "</td>
                            <td>" . htmlspecialchars($second_column) . "</td>
                            <td>" . htmlspecialchars($end_date_str) . "</td>
                            <td>Days left: " . $remaining_days . "</td>
                        </tr>";
                } else {
                    echo "<tr>
                            <td>" . htmlspecialchars($company_email) . "</td>
                            <td>" . htmlspecialchars($second_column) . "</td>
                            <td>" . htmlspecialchars($end_date_str) . "</td>
                            <td>";
                    if ($remaining_days < 0) { 
                        echo "No need liao hehe.";
                    } else {
                        echo $remaining_days !== null ? "Days left: " . $remaining_days : "Invalid date.";
                    }
                    echo "</td>
                        </tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?php echo $current_page - 1; ?>&rows_per_page=<?php echo $rows_per_page; ?>">&laquo; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&rows_per_page=<?php echo $rows_per_page; ?>" class="<?php if ($i == $current_page) echo 'active'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?php echo $current_page + 1; ?>&rows_per_page=<?php echo $rows_per_page; ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
</body>
</html>
