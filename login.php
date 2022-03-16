<?php
require('SMTP.php');
require('PHPMailer.php');
require('Exception.php');
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

session_start(); //starts the session or continues it if the user is already in one.
include('config.php'); //includes the config.php file for database connections.
unset($_SESSION['error']);

//defines the connection parameters
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'projectUser';
$DATABASE_PASS = '5Iix/r1PyO7sixqf';
$DATABASE_NAME = 'myProject';

// Try and connect using the information above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if(isset($_POST['login'])){ //if the login button is clicked
    $uname = $_POST['username']; //store the username input

    //select all the records from accounts where the username matches the username the user has inputted.
if($stmt = $con->prepare('SELECT UserId, Pwd, Email FROM accounts WHERE Username = ?')) {
	$stmt->bind_param('s', $uname);
    $stmt->execute();
    $stmt->store_result();
	// Store the result so we can check if the account exists in the database.
    
    //if the query returns more than 0 rows, the username is valid, so check the password.
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($uid, $password, $email);
        $stmt->fetch();
        if (password_verify($_POST['password'], $password)) { //if the password matches the hashed password, the credentials are valid.
            // User has logged-in successfully.

            // Create sessions variables, so we know the user is logged in, they act like cookies 
            $_SESSION['validUser'] = TRUE;
            $_SESSION['uid'] = $uid;
            $_SESSION['authCount'] = 0; //for two-factor authentication
            
            //generate a random 4 digit number
            $randNum =  mt_rand(1000,9999);

            //hash the random number
            $hashedRandNum = password_hash($randNum, PASSWORD_BCRYPT);

            //delete any existing authentication records with the user's userid.
            $stmt=$con->prepare('Delete from auth where UserId = ?');
            $stmt->bind_param('s', $uid);
            $stmt->execute();
            
            //create a new record in the authentication table with the userid and hashed random number.
            $stmt=$con->prepare('Insert into auth(UserId, AuthCode) Values(?, ?)');
            $stmt->bind_param('ss', $uid, $hashedRandNum);
            $stmt->execute();

            //get the user's first name
            $stmt = $con->prepare('SELECT Fname FROM accounts WHERE Email = ?');
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($name);
                $stmt->fetch();

            //create a new PHPMailer object
            $mail=new PHPMailer(true);
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host='smtp-mail.outlook.com'; //set host to smtp outlook
            $mail->SMTPAuth= True; // Enable smtp authentication
            $mail->Password='HelloWorld1'; //set smtp password
            $mail->Username='amyloumullins1414@hotmail.com'; // set smtp username
            $mail->SMTPSecure='TLS'; //set a secure tls communication
            $mail->Port=587; //set the port number
            
            //set sender details
            $mail->setFrom('amyloumullins1414@hotmail.com', 'TechKNOW');
        
            //set recipient details
            $mail->addAddress($email);     // Add a recipient
        
            //set email content
            $mail->isHTML(true); // set email format to HTML
            $mail->Subject='Authentication Code'; //set email subject
          
            //set email body
            $mail->Body='<p>Dear '.$name.',</p><br><p>Below is your 4 digit authentication code:</p><br><b>'.$randNum.'</b><br><p>
            Please DO NOT share this code with anyone.</p><br>
            <p>The TechKNOW Team</p>';    //sends the user the random 4 digit code.
            $mail->send(); //send the email
            Header('Location: twoFactorAuth.php'); //redirect the user to two factor authentication.

            
        } else {
            // Incorrect password
            $_SESSION['error'] = "Incorrect Username/Password";
        }
    } else {
        // Incorrect username
        $_SESSION['error']="Incorrect Username/Password";
    }

	$stmt->close();
}}
?>
<!--HTML code for page appearance-->
<link rel="stylesheet" href="login.css">
<link rel="stylesheet" href="homepage.css">
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>TechKnow | Login</title>
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
		<div class="box">
			<form class="loginForm" action="" method="post">
            <h1 class="login">Login</h1>
				<input class="fields" type="text" name="username" placeholder="Username" id="username" required>
                <input class="fields" type="password" name="password" placeholder="Password" id="password" required>
                <input class="submit" type="submit" value="Login" name='login'>
                </form>
                <?php
                //display the error message to the user if the error session variable is set.
                    if(isset($_SESSION['error'])){
                        $error = $_SESSION["error"];
                        echo "<span class='error'>$error</span>";
                    }
                ?>  
                <a style=" margin-left: 35%;
                    font-family: 'Source Sans Pro', sans-serif;
                    color: #55939f;
                    font-weight: 600;" href="forgotPassword.php">Forgot Password?</a><br><br>
                <a href="signUp.php"><button class="signUp">Sign Up</button></a>
            </div>
		
	</body>
</html>
<?php

    unset($_SESSION["error"]);
?>

