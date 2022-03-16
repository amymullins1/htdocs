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
    $emailNum = 2; //binds 2 to the '?' parameter
    $stmt->bind_param('i', $emailNum);
//the statement selects all the users' userIDs and emails where their next_email_num is 2.
    $stmt->execute(); //executes the query
    
    $result = $stmt->get_result(); //stores all the results from the query
    
//stores each row from the results in 'row'
//the system continues looping until the end of the results is reached.
    while($row = $result->fetch_assoc()) {
      
        $mail->isSMTP(); // Sets the mailer to use SMTP
        $mail->Host='smtp-mail.outlook.com'; //sets the mail host to smtp outlook 
        $mail->SMTPAuth= True; // enables SMTP authentication
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
        $mail->setFrom('amyloumullins1414@hotmail.com', 'PayPal'); //
     
        //setting the recipient
        $mail->addAddress($email);     // Add a recipient
    
        //set email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject='Your acount has been bloked!'; //set email subject
      
        //set email body using html code
        $mail->Body='<img width="150" height = "100" src="http://localhost/Logo/paypal_logo.png">
                    <p>Dear Customer</p>
                    <br>Your PayPal account has been limited due to susspected susspicious activty </br>
                    <br>Sign into yore acount to unlock the acount</br>
                    <a href="http://localhost/linkClicked.php?email='.$randNum.'&emailId=2">Log In</a>
                    <br>PayPal</br>';    
        $mail->send(); //sends the email

    } 
    catch(Exception $e) {
      //for testing purposes to see any errors.
        echo 'Message could not be sent.';
        echo 'Mailer Error: '.$mail->ErrorInfo;
    }
    //sets the user's next_email_num to 3, so the user can receieve the next email in the simulation and does not receive this email again.
    if($stmt = $con->prepare('UPDATE emailTrack SET Next_email_num = ? WHERE UserId = ?')){
        $nextEmail = 3;
        $stmt->bind_param('ii', $nextEmail, $uid);
        //executes the query
        $stmt->execute();
      }
      //inserts a new record into the 'results' table which will be used to track the user's performance for this email.
      if($stmt = $con->prepare('INSERT INTO results(UserID, EmailNum, HasClicked, AuthCode) VALUES(?, 2, 0, ?)')){
        $stmt->bind_param('is', $uid, $hashedRandNum); //the hashed random number is stored in this record for authentication purposes if the user clicks on the link in the email.
        $stmt->execute(); //executes the query
      }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
}
}
?>
