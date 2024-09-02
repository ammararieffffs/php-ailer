<?php
session_start();
include "db_conn.php";
include "functions.php";
if (isset($_POST['btnRegister'])) {

    // Prepare and bind
    $stmt = $connection->prepare("INSERT INTO `company-list` (`company_name`, `start_date`, `end_date`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $company_name, $start_date, $end_date);

    // Set parameters and execute
    $company_name = $_POST['company_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $stmt->execute();

    echo "New record created successfully!";

    // $query = "SELECT `start_date`, `end_date` FROM `company-list` WHERE `company_name` = '$company_name'";
    // $result = mysqli_query($connection, $query);
    // $row = mysqli_fetch_assoc($result);
    // $end_date = $row['end_date'];


    $end_date = date('Y-m-d', strtotime($end_date));
    $current_date = date('Y-m-d');

    $remaining_seconds = strtotime($end_date) - strtotime($current_date);
    $remaining_days = floor($remaining_seconds / (60 * 60 * 24));

    if ($remaining_days <= 30) {
        sendEmail($company_name, "Free gifts for you", "haha u suck");
        echo "<br> This company has " . $remaining_days . " days left.";
    } else {
        echo "<br> This company has " . $remaining_days . " days left.";
    }

    // Close statement and connection
    $stmt->close();
    $connection->close();
}
?>


<?php

// session_start();

// //set empty array if variable is not set or null
// $_SESSION['correctInput'] ??= [];

// include "functions.php";

// $_SESSION['userAccData'] = createUserAccount();

// if (!empty($_SESSION['userAccData'])){

//     //insert data into email_verify table
//     include "db_conn.php";
//     $_SESSION['user_email'] = mysqli_real_escape_string($connection, $_POST["txtEmail"]);
//     $query = "INSERT INTO `email_verify` (`id`, `email`, `otp`, `otp_time`) VALUES (NULL, '{$_SESSION['user_email']}', '@@@', '@@@')";
//     mysqli_query($connection, $query);
//     mysqli_close($connection);

//     //generate new otp & send email
//     $otp = generateOTP(6, 'email_verify');

//     //write email content
//     $email_subject = "Verify Email";
//     include "email_template.php";
//     $email_message = emailTemplateOTP("Thanks for your registration!", "KwazeEdu account registration", $otp);
    
//     //send email & store output (error) if any
//     $hasError = sendEmail($_SESSION['user_email'], $email_subject, $email_message);

//     if (!empty($hasError)) {
//         $code_error = '<i class="bx bx-error-circle"></i>' . "Could not send email. Please try again.";
//     }

//     header("Location: ../php/verify_email.php");
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>

    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Unbounded:wght@200..900&display=swap"
        rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chivo+Mono:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Icon -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="../css/register.css" />

    <!-- JS -->
    <script src="../js/show_hide_password.js"></script>
    <script>
    // function clearInput() {

    // }
    </script>
</head>

<body>
    <h1>Enter your company email here PLEASE</h1>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <table>
                    <tr>
                        <td>
                            <input type="text" name="company_name" placeholder="Company Name" required>
                        </td>
                        <td>
                            <input type="date" name="start_date" required>
                        </td>
                        <td>
                            <input type="date" name="end_date" required>
                        </td>
                    </tr>
                </table>
                <input type="submit" value="Enter" name="btnRegister" />
            </form>
        </div>
    </div>
</body>

</html>