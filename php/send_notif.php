<?php
include "mail_function.php";
include "email_template.php";

$csv_url = 'https://docs.google.com/spreadsheets/d/19q86Qia2R48v_nvR6xzbtBS9Nw1h6O0LfbZtYKW7Eds/pub?output=csv';
$csv_data = file_get_contents($csv_url);
$rows = array_map('str_getcsv', explode("\n", $csv_data));
$header = array_shift($rows);   //this is to skip the header row

$current_date = date('Y-m-d');   //get today's date

$confirm_send = false;
$email_content = '';
foreach ($rows as $row) {
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
        $confirm_send = true;
        $email_content .= "<tr>
                <td>" . htmlspecialchars($company_email) . "</td>
                <td>" . htmlspecialchars($second_column) . "</td>
                <td>" . htmlspecialchars($end_date_str) . "</td>
                <td>" . $remaining_days . "</td>
            </tr>";
        // aaaa
    }
}

if ($confirm_send == true) {
    $email_message = emailTemplate($email_content);
    sendEmail("opy7654321@gmail.com", "Reminder for you", $email_message);    //replace the first argument with your own receiver email
}

?>
