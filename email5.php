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
    $emailNum = 5;
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
        
        $mail->SMTPSecure='TLS';
        $mail->Port=587;
        $uid = $row['UserId'];
        $email = $row['Email'];

       $randNum =  mt_rand(10000000,99999999);
       $randNum .= $uid;
       $hashedRandNum = password_hash($randNum, PASSWORD_BCRYPT);
       try {
        //settings
    
        $mail->setFrom('amyloumullins1414@hotmail.com', 'Netflix');
    
        //recipient
        $mail->addAddress($email);     // Add a recipient
    
        //content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject='Update your billing information';
      
        $mail->Body='<img width="190" height = "100" src="http://localhost/Logo/netflix_logo.png">
                    <span>Hi,</span><br>
                    <h3>The payment for your next bill has been declind</h3>
                    <p>Update your billing imformation to prevent youre account being terminated</p>
                    <a href="http://localhost/linkClicked.php?id='.$randNum.'&emailId=5">Pay Again</a>
                    <br><p>Netflix</p>
                    
                    ';
                       
        $mail->send();

    } 
    catch(Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: '.$mail->ErrorInfo;
    }
    if($stmt = $con->prepare('UPDATE emailTrack SET Next_email_num = ? WHERE UserId = ?')){
        $nextEmail = 6;
        $stmt->bind_param('ii', $nextEmail, $uid);
        $stmt->execute();
      }
      if($stmt = $con->prepare('INSERT INTO results(UserID, EmailNum, HasClicked, AuthCode) VALUES(?, 5, 0, ?)')){
        $stmt->bind_param('is', $uid, $hashedRandNum);
        $stmt->execute();
      }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
}
}
?>
