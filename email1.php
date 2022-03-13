<?php
require('SMTP.php');
require('PHPMailer.php');
require('Exception.php');
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
$DATABASE_HOST = '127.0.0.1';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'myProject';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$mail=new PHPMailer(true); // Passing `true` enables exceptions

if($stmt = $con->prepare('SELECT accounts.UserId, accounts.Email FROM emailTrack INNER JOIN accounts ON emailTrack.UserId = accounts.UserId WHERE emailTrack.Next_email_num = ? LIMIT 50;')){
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
        $mail->SMTPSecure='TLS';
        $mail->Port=587;

        $uid = $row['UserId'];
        $email = $row['Email'];
        $randNum =  mt_rand(10000000,99999999);
        $randNum .= $uid;
        $hashedRandNum = password_hash($randNum, PASSWORD_BCRYPT);

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
                    <a href="http://localhost/linkClicked.php?id='.$randNum.'&emailId=1">Click Here</a>
                    <br>NHS</br>';    
        $mail->send();

    } 
    catch(Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: '.$mail->ErrorInfo;
    }
    if($stmt = $con->prepare('UPDATE emailTrack SET Next_email_num = ? WHERE UserId = ?')){
        $nextEmail = 2;
        $stmt->bind_param('ii', $nextEmail, $uid);
        $stmt->execute();
      }
      if($stmt = $con->prepare('INSERT INTO results(UserID, EmailNum, HasClicked, AuthCode) VALUES(?, 1, 0, ?)')){
        $stmt->bind_param('is', $uid, $hashedRandNum);
        $stmt->execute();
      }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
}
}
?>
