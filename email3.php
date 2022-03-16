<?php
//includes the SMTP.php, PHPMailer.php and Exception.php files for email sending
require('SMTP.php');
require('PHPMailer.php');
require('Exception.php');
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

//defining the database connection parameters
$DATABASE_HOST = '127.0.0.1';
$DATABASE_USER = 'projectUser';
$DATABASE_PASS = '5Iix/r1PyO7sixqf';
$DATABASE_NAME = 'myProject';

//defines the database connection
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

//creates a PHP object called $mail
$mail=new PHPMailer(true); // Passing `true` enables exceptions

//sends the SQL statement to the DBMS
if($stmt = $con->prepare('SELECT accounts.UserId, accounts.Email FROM emailTrack INNER JOIN accounts ON emailTrack.UserId = accounts.UserId WHERE emailTrack.Next_email_num = ? LIMIT 50;')){
    $emailNum = 3; 
    //binds 3 to the '?' parameter
    $stmt->bind_param('i', $emailNum);
    //the statement selects all the users' userIDs and emails where their next_email_num is 3.
    $stmt->execute(); //executes the SQL query
    
    $result = $stmt->get_result();//stores all the results from the query
    
    //stores each row from the results in 'row'
    //the system continues looping until the end of the results is reached.
    while($row = $result->fetch_assoc()) {
     
        $mail->isSMTP(); // Sets the mailer to use SMTP
        $mail->Host='smtp-mail.outlook.com'; //sets the mail host to smtp outlook 
        $mail->SMTPAuth= True; // Enable SMTP authentication
        $mail->Password='HelloWorld1'; //SMTP password
        $mail->Username='amyloumullins1414@hotmail.com'; // SMTP username
        $mail->SMTPSecure='TLS'; //defines the secure TLS connection
        $mail->Port=587; //defines the port number to use
        $uid = $row['UserId']; //stores the userID from the current row.
       $email = $row['Email']; //stores the email address from the current row.

       $randNum =  mt_rand(10000000,99999999); //generates a random 8 digit number
       $randNum .= $uid; //appends the user's userID to the end of the random number so every random number is unique
       $hashedRandNum = password_hash($randNum, PASSWORD_BCRYPT); //hashes the unique number
       
       try {

       //setting the sender address and name
        $mail->setFrom('amyloumullins1414@hotmail.com', 'WINNER');
    
        //setting the recipient email address
        $mail->addAddress($email);
        
        //set email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject='CONGRATULATIONS!'; //set email subject
      
        //set email body
        $mail->Body='<p style="color: red;font-weight:bolder;">EXCLUSIVE</p>
                    <br><h1 style="color: red; font-weight:bolder;">YOU HAVE BEEN CHOSAN TO WIN $1000</h1>
                    <p style="color:red;font-weight:bolder;">CLICK THE LINK BELOW TO CLAIM</p><br>
                    <a style="color: red;font-weight:bolder;" href="http://localhost/linkClicked.php?id='.$randNum.'&emailId=3">CLICK ME</a>';
                       
        $mail->send(); //sends the email

    } 
    catch(Exception $e) {
      //for testing purposes
        echo 'Message could not be sent.';
        echo 'Mailer Error: '.$mail->ErrorInfo;
    }
    //sets the user's next_email_num to 4, so the user can receieve the next email in the simulation and does not receive this email again.
    if($stmt = $con->prepare('UPDATE emailTrack SET Next_email_num = ? WHERE UserId = ?')){
        $nextEmail = 4;
        $stmt->bind_param('ii', $nextEmail, $uid);
        $stmt->execute(); //executes the query
      }
      //inserts a new record into the 'results' table which will be used to track the user's performance for this email.
      if($stmt = $con->prepare('INSERT INTO results(UserID, EmailNum, HasClicked, AuthCode) VALUES(?, 3, 0, ?)')){
        $stmt->bind_param('is', $uid, $hashedRandNum);
        $stmt->execute(); //executes the query
      }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
}
}
?>
