<?php
//includes the SMTP.php, PHPMailer.php and Exception.php files for email sending
require('SMTP.php');
require('PHPMailer.php');
require('Exception.php');
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

//so that if the user has entered an invalid email, they are not constantly shown an error message
unset($_SESSION['error']);

include('config.php'); //for database connections
//defining the database connection parameters.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'projectUser';
$DATABASE_PASS = '5Iix/r1PyO7sixqf';
$DATABASE_NAME = 'myProject';

//defines the database connection
$mail=new PHPMailer(true);

//defines the database connection
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if(isset($_POST['submit'])){ //if the login button is clicked
            
            $email = $_POST['email']; //store the user's input
            $randNum =  mt_rand(10000000,99999999); //generate a random 8 digit number
            $datetime = date("Y-m-d H:i:s"); //get today's date and time

            //selects the accounts records that have a matching email to the email address inputted
            $stmt=$con->prepare('Select UserId from accounts where email = ?'); 
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result(); //stores the results of the query
            $stmt->bind_result($uid); //stores the userid obtained from the results of the query
            $stmt->fetch();//stores the userid obtained from the results of the query 

            //deletes any authentication records, relating to the user id obtained, that may be stored in auth
            $stmt=$con->prepare('Delete from auth where UserId = ?');  
            $stmt->bind_param('s', $uid);
            $stmt->execute();
            
            $randNum .= $uid; //appends the userid to the end of the 8 digit number to make the number unique
            $hashedRandNum = password_hash($randNum, PASSWORD_BCRYPT); //hashes the unique number

            //stores the unique number and the user's id in the auth table for authentication purposes
            $stmt=$con->prepare('Insert into auth(UserId, AuthCode, DateTime) Values(?, ?, ?)');
            $stmt->bind_param('sss', $uid, $hashedRandNum, $datetime);
            $stmt->execute();

//gets the user's first name where the email in the record matches the user input
if($stmt = $con->prepare('SELECT Fname FROM accounts WHERE Email = ?')) {
	$stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result(); //stores the results of the query
    $stmt->bind_result($name); 
    $stmt->fetch(); //stores the first name obtained in the name variable
    
    //if the query returns rows, the email is valid, so send an email to that email address
	if($stmt->num_rows>0){ 

        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host='smtp-mail.outlook.com'; //sets the mail host to smtp outlook
        $mail->SMTPAuth= True; // Enable SMTP authentication
        $mail->Password='HelloWorld1'; //smtp password
        $mail->Username='amyloumullins1414@hotmail.com'; // SMTP username
        $mail->SMTPSecure='TLS'; //sets the secure TLS connection
        $mail->Port=587; //sets the port number to use
         
        //set sender details
        $mail->setFrom('amyloumullins1414@hotmail.com', 'TechKNOW'); 
    
        //set recipient details
        $mail->addAddress($email); 

        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject='Password Reset'; //set email subject
      
        //set email body using HTML
        $mail->Body='<p>Dear '.$name.', </p><br><p>Please click the link below to reset your password: </p><br>
        <a href="http://localhost/resetPass.php?id='.$randNum.'">Reset Password</a><br>
        <p>The TechKNOW Team</p>';
             
        $mail->send(); //send the email.
        Header('Location: login.php'); //relocate the user to the login page
    }
    //else the email does not exist so display an error message informing the user this
    else{
        $_SESSION['error'] = "Email does not exist!";
    
	
}}}

?>
<!--HTML code for page layout-->
<link rel="stylesheet" href="login.css">
<link rel="stylesheet" href="homepage.css">
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>TechKnow | Forgot Password</title>
        <link rel="icon" type="image/x-icon" href="/Logo/icon.png">
    </head>
    <header class="mainHeader" style="margin: 0px;">
        <nav>
            <ul>
            <div id = "menu" class="menu">
                <div id="logo" class="logoImage"><li><a href="index.php" ><img class="logoImage" src="Logo/horizontalCover.png"></a></li></div>
                <div class="menuText">
                    <li><a href="index.php">HOME</a></li>
                <li><a href="about.php">ABOUT</a></li>
                <li><a href="signUp.php">SIGN UP</a></li>
                </div>
            </div>
            </ul>
        </nav>
</header>
	<body>
		<div style="background-color: #FFFFFF;
                    width: 400px;
                    height: 350px;
                    margin: 7em auto;
                    border-radius: 1.5em;
                    box-shadow: 0px 11px 35px 2px rgba(0, 0, 0, 0.14);">
			<form class="loginForm" action="" method="post">
            <h1 class="login">Forgotten Password</h1>
            <p style=" margin-left: 1em;
                font-family: 'Source Sans Pro', sans-serif;
                font-size: 15px;
                color: #55939f;
                font-weight: 600;
                padding-top: 1px;">Enter your email below and we will email <br>you a link to reset your password:</p><br>
				<input class="fields" type="text" name="email" placeholder="Email" required>
                <input class="submit" type="submit" value="Submit" name='submit'>
                </form>
                <?php
                //if the error session variable is set, the email does not exist so display an error message.
                    if(isset($_SESSION['error'])){
                        $error = $_SESSION["error"];
                        ?>
                            <span style="color: red;
                        display: inline-block;
                        margin-left: 33%;
                        font-family: 'Source Sans Pro', sans-serif;" class='error'><?php echo "$error";?></span>
                            <?php
                    }
                ?>  
            </div>
		
	</body>
</html>
<?php
    //unset the error session variable
    unset($_SESSION["error"]);
?>
