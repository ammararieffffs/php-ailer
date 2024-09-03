<?php
//import PHPMailer classes to enable mail sending
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail($user_email, $subject, $message) {

    //load composer autoload.php (refer to ur own autoload.php location)
    require dirname(__FILE__) . '\..\vendor\autoload.php';

    //email information
    $senderName = "BIKEBEAR";
    $senderEmail = 'kwazeedu@gmail.com';  //put ur own email here


    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                                    //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                               //Set the SMTP server
        $mail->SMTPAuth   = true;                                           //Enable SMTP authentication
        $mail->Username   = $senderEmail;                                   //SMTP username
        $mail->Password   = 'skxbxphlwxwtmeyp';                             //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                 //Enable implicit TLS encryption
        $mail->Port       = 587;                                            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //this line is to test for error/exception handling, so don't remove yet
        // $mail->addAttachment('/var/tmp/file.tar.gz');

        //Recipients
        $mail->setFrom($senderEmail, $senderName);
        $mail->addAddress($user_email);
        // $mail->addAddress('ooipeiying732@gmail.com', 'Joe User');        //add a recipient, name is optional
        // $mail->addReplyTo('kwazeedu@gmail.com', 'Information');
        // $mail->addCC('$user_email');                                     //carbon copy
        // $mail->addBCC('$user_email');                                    //blind carbon copy

        //Content
        $mail->isHTML(true);                                                //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        //send email
        $mail->send();

    } catch (Exception $e) {
        return "email can't be send, error occured";
    }

    //close smtp connection
    $mail->smtpClose();
}
?>