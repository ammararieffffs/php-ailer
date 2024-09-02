<?php
include "db_conn.php";
include "functions.php";
include "email_template.php";

// URL of the CSV file
$csv_url = 'https://docs.google.com/spreadsheets/d/19q86Qia2R48v_nvR6xzbtBS9Nw1h6O0LfbZtYKW7Eds/pub?output=csv';

// Fetch the CSV data
$csv_data = file_get_contents($csv_url);
$rows = array_map('str_getcsv', explode("\n", $csv_data));

// Skip the header row
$header = array_shift($rows);

$current_date = date('Y-m-d');
$sent_emails = [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Data Display</title>
    <style>
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
    </style>
</head>
<body>
    <h1>CSV Data</h1>
    <table>
        <thead>
            <tr>
                <?php foreach ($header as $col_name): ?>
                    <th><?php echo htmlspecialchars($col_name); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
    <?php
    $index = 0; // Initialize the index
    foreach ($rows as $row) {
        $index++;
        if (count($row) >= 3) {
            $company_email = $row[0];
            $end_date = date('Y-m-d', strtotime($row[2]));
    
            $remaining_seconds = strtotime($end_date) - strtotime($current_date);
            $remaining_days = floor($remaining_seconds / (60 * 60 * 24));
            $email_message = emailTemplateOTP();
    
            // Check if remaining days are 30 or less
            if (($remaining_days <= 30) && ($remaining_days >= 0)) {
                sendEmail($company_email, "Notification for you [" . $index . "]", $email_message);
                echo "<tr>
                        <td>" . htmlspecialchars($row[0]) . "</td>
                        <td>" . htmlspecialchars($row[1]) . "</td>
                        <td>" . htmlspecialchars($row[2]) . "</td>
                        <td>Days left: " . $remaining_days . "</td>
                      </tr>";
            } else {
                echo "<tr>
                        <td>" . htmlspecialchars($row[0]) . "</td>
                        <td>" . htmlspecialchars($row[1]) . "</td>
                        <td>" . htmlspecialchars($row[2]) . "</td>
                        <td>";
                if ($remaining_days < 0) { 
                    echo "Not available.";
                } else {
                    echo "Days left: " . $remaining_days;
                }
                echo "</td>
                      </tr>";
            }
        }
    }    
    ?>
</tbody>
    </table>
</body>

</html>
