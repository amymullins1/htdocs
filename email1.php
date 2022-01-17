<?php
require('SMTP.php');
require('PHPMailer.php');
require('Exception.php');
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'myProject';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$mail=new PHPMailer(true); // Passing `true` enables exceptions

if($stmt = $con->prepare('SELECT accounts.Email FROM emailTrack INNER JOIN accounts ON emailTrack.UserId = accounts.UserId WHERE emailTrack.UserId = ? LIMIT 50;')){
    $emailNum = 1;
    
    $stmt->bind_param('i', $emailNum);
    $stmt->execute();
    
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $mail->SMTPDebug=2; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host='smtp-mail.outlook.com';
        $mail->SMTPAuth= True; // Enable SMTP authentication
        $mail->Password='HelloWorld1';
        $mail->Username='amyloumullins1414@hotmail.com'; // SMTP username
        // SMTP password
        $mail->SMTPSecure='SSL';
        $mail->Port=587;
       $email = $row['Email'];
       try {
        //settings
    
        $mail->setFrom('amyloumullins1414@hotmail.com', 'NHS');
    
        //recipient
        $mail->addAddress($email);     // Add a recipient
    
        //content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject='You are elegible to book your vaccine';
      
        $mail->Body='<img width="150" height = "100" src="http://localhost/Logo/nhs_logo.png">
                    <p>Dear Sir/Madam</p>
                    <br>You have been selected to receeve your corona vacine.</br>
                    <br>Click the link below to book the vaccine.</br>
                    <a href="http://localhost/linkClicked.php?email='.$email.'&emailId=1">Click Here</a>
                    <br>NHS</br>';    
        $mail->send();
        echo "email sent.";
    } 
    catch(Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: '.$mail->ErrorInfo;
    }
      }

}


?>