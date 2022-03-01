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
    $emailNum = 3;
    $stmt->bind_param('i', $emailNum);
    $stmt->execute();
    
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
      $randNum =  mt_rand(10000000,99999999);
      $hashedRandNum = password_hash($randNum, PASSWORD_BCRYPT);
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
       try {
        //settings
    
        $mail->setFrom('amyloumullins1414@hotmail.com', 'WINNER');
    
        //recipient
        $mail->addAddress($email);     // Add a recipient
    
        //content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject='CONGRATULATIONS!';
      
        $mail->Body='<p style="color: red;font-weight:bolder;">EXCLUSIVE</p>
                    <br><h1 style="color: red; font-weight:bolder;">YOU HAVE BEEN CHOSAN TO WIN $1000</h1>
                    <p style="color:red;font-weight:bolder;">CLICK THE LINK BELOW TO CLAIM</p><br>
                    <a style="color: red;font-weight:bolder;" href="http://localhost/linkClicked.php?id='.$randNum.'&emailId=3">CLICK ME</a>';
                       
        $mail->send();

    } 
    catch(Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: '.$mail->ErrorInfo;
    }
    if($stmt = $con->prepare('UPDATE emailTrack SET Next_email_num = ? WHERE UserId = ?')){
        $nextEmail = 4;
        $stmt->bind_param('ii', $nextEmail, $uid);
        $stmt->execute();
      }
      if($stmt = $con->prepare('INSERT INTO results(UserID, EmailNum, HasClicked, AuthCode) VALUES(?, 3, 0, ?)')){
        $stmt->bind_param('is', $uid, $hashedRandNum);
        $stmt->execute();
      }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
}
}
?>
