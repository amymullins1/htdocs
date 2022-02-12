<?php
require('SMTP.php');
require('PHPMailer.php');
require('Exception.php');
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
unset($_SESSION['error']);
include('config.php');
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'myProject';
$mail=new PHPMailer(true);
$email = $_POST['email'];
// Try and connect using the information above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if(isset($_POST['submit'])){ //if the login button is clicked
if($stmt = $con->prepare('SELECT Fname FROM accounts WHERE Email = ?')) {
	$stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name);
    $stmt->fetch();
	if($stmt->num_rows>0){
        $mail->SMTPDebug=2; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host='smtp-mail.outlook.com';
        $mail->SMTPAuth= True; // Enable SMTP authentication
        $mail->Password='HelloWorld1';
        $mail->Username='amyloumullins1414@hotmail.com'; // SMTP username
        // SMTP password
        $mail->SMTPSecure='SSL';
        $mail->Port=587;
         
        $mail->setFrom('amyloumullins1414@hotmail.com', 'TechKNOW');
    
        //recipient
        $mail->addAddress($email);   
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject='Password Reset';
      
        $mail->Body='<p>Dear '.$name.', </p><br><p>Please click the link below to reset your password: </p><br>
        <a href="http://localhost/resetPass.php?email='.htmlspecialchars($email).'">Reset Password</a><br><p>The TechKNOW Team</p>';
             
        $mail->send();
    }else{
        $_SESSION['error'] = "Email does not exist!";
    
	
}}}

?>

<link rel="stylesheet" href="login.css">
<link rel="stylesheet" href="homepage.css">
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>TechKnow | Forgot Password</title>
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
    unset($_SESSION["error"]);
?>