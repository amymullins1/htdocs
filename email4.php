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
    $emailNum = 4;
    $stmt->bind_param('i', $emailNum);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($uid, $email);
    $stmt->fetch();
    $randNum =  mt_rand(10000000,99999999);
    $randNum .= $uid;
    $hashedRandNum = password_hash($randNum, PASSWORD_BCRYPT);

        $mail->SMTPDebug=2; 
        $mail->isSMTP(); // Sets the mailer to use SMTP
        $mail->Host='smtp-mail.outlook.com';
        $mail->SMTPAuth= True; // Enable SMTP authentication
        $mail->Password='HelloWorld1'; //SMTP Password
        $mail->Username='amyloumullins1414@hotmail.com'; // SMTP username
        
        $mail->SMTPSecure='TLS';
        $mail->Port=587;
        
       try {
        //settings
    
        $mail->setFrom('amyloumullins1414@hotmail.com', 'Microsoft');
    
        //recipient
        $mail->addAddress($email);     // Add a recipient
    
        //content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject='YOU MUST ACT NOW';
      
        $mail->Body='Hi '.$email.'<br>
                    <p>YOUR MICROSOFT ACCOUNT HAS BEEN HACKED</p>
                    <br><p>YOU MUST LOG IN NOW TO SAVE YOUR ACCOUNT BEFOR ITS TOO LAIT!</p>
                    <a href="http://localhost/linkClicked.php?id='.$randNum.'&emailId=4">LOG IN</a>
                    <br><p>Microsoft</p>
                    <img width="190" height = "100" src="http://localhost/Logo/microsoft_logo.png">
                    ';
                       
        $mail->send();

    } 
    catch(Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: '.$mail->ErrorInfo;
    }
    if($stmt = $con->prepare('UPDATE emailTrack SET Next_email_num = ? WHERE UserId = ?')){
        $nextEmail = 5;
        $stmt->bind_param('ii', $nextEmail, $uid);
        $stmt->execute();
      }
      
      if($stmt = $con->prepare('INSERT INTO results(UserID, EmailNum, HasClicked, AuthCode) VALUES(?, 4, 0, ?)')){
        $stmt->bind_param('is', $uid, $hashedRandNum);
        $stmt->execute();
      } 

}

?>
