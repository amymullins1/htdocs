<?php
require_once('SMTP.php');
require_once('PHPMailer.php');
require_once('Exception.php');

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

$mail=new PHPMailer(true); // Passing `true` enables exceptions

try {
    //settings
    $mail->SMTPDebug=2; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host='smtp-mail.outlook.com';
    $mail->SMTPAuth= True; // Enable SMTP authentication
    $mail->Password='helloWorld1!';
    $mail->Username='amyloumullins1414@hotmail.com'; // SMTP username
    // SMTP password

    $mail->SMTPSecure='SSL';
    $mail->Port=587;

    $mail->setFrom('amyloumullins1414@hotmail.com', 'Amy');

    //recipient
    $mail->addAddress('amyloumullins@hotmail.co.uk', 'Mullins');     // Add a recipient

    //content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject='Here is the subject';
    $mail->Body='This is the HTML message body <b>in bold!</b>';
    $mail->AltBody='This is the body in plain text for non-HTML mail clients';

    $mail->send();

    echo 'Message has been sent';
} 
catch(Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: '.$mail->ErrorInfo;
}

?>