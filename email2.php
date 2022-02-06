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

if($stmt = $con->prepare('SELECT accounts.UserId, accounts.Email FROM emailTrack INNER JOIN accounts ON emailTrack.UserId = accounts.UserId WHERE emailTrack.Next_email_num = ? LIMIT 50;')){
    $emailNum = 2;
    $stmt->bind_param('i', $emailNum);
    $stmt->execute();
    
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $mail->SMTPDebug=2; 
        $mail->isSMTP(); // Sets the mailer to use SMTP
        $mail->Host='smtp-mail.outlook.com';
        $mail->SMTPAuth= True; // Enable SMTP authentication
        $mail->Password='HelloWorld1'; //SMTP Password
        $mail->Username='amyloumullins1414@hotmail.com'; // SMTP username
        
        $mail->SMTPSecure='SSL';
        $mail->Port=587;
        $uid = $row['UserId'];
       $email = $row['Email'];
       try {
        //settings
    
        $mail->setFrom('amyloumullins1414@hotmail.com', 'PayPal');
    
        //recipient
        $mail->addAddress($email);     // Add a recipient
    
        //content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject='Your acount has been locked!';
      
        $mail->Body='<img width="150" height = "100" src="http://localhost/Logo/paypal_logo.png">
                    <p>Dear Customer</p>
                    <br>Your PayPal account has been limited due to susspected susspicious activty </br>
                    <br>Sign into yore acount to unlock the acount</br>
                    <a href="http://localhost/linkClicked.php?email='.$email.'&emailId=2">Log In</a>
                    <br>PayPal</br>';    
        $mail->send();

    } 
    catch(Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: '.$mail->ErrorInfo;
    }
    if($stmt = $con->prepare('UPDATE emailTrack SET Next_email_num = ? WHERE UserId = ?')){
        $nextEmail = 3;
        $stmt->bind_param('ii', $nextEmail, $uid);
        $stmt->execute();
      }
      if($stmt = $con->prepare('INSERT INTO results(UserID, EmailNum, HasClicked) VALUES(?, 2, 0)')){
        $stmt->bind_param('i', $uid);
        $stmt->execute();
      }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
}
}
?>