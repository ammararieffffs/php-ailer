<?php
include "mail_function.php";
include "email_template.php";

$csv_url = 'https://docs.google.com/spreadsheets/d/19q86Qia2R48v_nvR6xzbtBS9Nw1h6O0LfbZtYKW7Eds/pub?output=csv';
$csv_data = file_get_contents($csv_url);
$rows = array_map('str_getcsv', explode("\n", $csv_data));
$header = array_shift($rows);   //this is to skip header row

$current_date = date('Y-m-d');   //get today's date
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
    <h1>Maintenance List</h1>
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
            $confirm_send = false;
            $data = '';
            foreach ($rows as $row) {
                $company_email = $row[0] ?? '';
                $second_column = $row[1] ?? '';
                $end_date = isset($row[2]) ? date('Y-m-d', strtotime($row[2])) : '';
                
                $remaining_days = null;
                if ($end_date) {
                    $remaining_seconds = strtotime($end_date) - strtotime($current_date);
                    $remaining_days = floor($remaining_seconds / (60 * 60 * 24));
                }
            
                if (($remaining_days !== null) && ($remaining_days <= 30) && ($remaining_days >= 0)) {
                    $confirm_send = true;
                    $data .= "<tr>
                            <td>" . htmlspecialchars($company_email) . "</td>
                            <td>" . htmlspecialchars($second_column) . "</td>
                            <td>" . htmlspecialchars($end_date) . "</td>
                            <td>Days left: " . $remaining_days . "</td>
                        </tr>";
                    echo "<tr>
                            <td>" . htmlspecialchars($company_email) . "</td>
                            <td>" . htmlspecialchars($second_column) . "</td>
                            <td>" . htmlspecialchars($end_date) . "</td>
                            <td>Days left: " . $remaining_days . "</td>
                        </tr>";
                } else {
                    echo "<tr>
                            <td>" . htmlspecialchars($company_email) . "</td>
                            <td>" . htmlspecialchars($second_column) . "</td>
                            <td>" . htmlspecialchars($end_date) . "</td>
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

            if ($confirm_send == true) {
                $email_message = emailTemplateOTP($data);
                sendEmail("opy7654321@gmail.com", "Reminder for you", $email_message);
            }
            ?>
        </tbody>
    </table>
</body>
</html>
