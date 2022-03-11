<?php
unset($_SESSION['error']);
include('config.php');
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'myProject';

$id = $_GET['id'];
// Try and connect using the information above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$stmt = $con->prepare('SELECT UserId, AuthCode from auth');
$stmt->execute();
$results = $stmt->get_result();
$count = 0;
while($rowData = $results->fetch_assoc()){
    if(password_verify($id, $rowData['AuthCode'])){
        $uid = $rowData['UserId'];
        $count +=1;
    }
}
if($count == 1){
if(isset($_POST['submit'])){ //if the login button is clicked
        
    if($_POST['password1'] == $_POST['password2']){
    if(preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,60}$/', $_POST['password1'])) {
    $hashedPassword = password_hash($_POST['password1'], PASSWORD_BCRYPT);
    $stmt = $con->prepare('Update accounts set Pwd = ? where UserId = ?');
    $stmt->bind_param('ss', $hashedPassword, $uid);
    $stmt->execute();
    $stmt = $con->prepare('Delete from auth where UserId = ?');
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $_SESSION['success'] = "Password updated!";
    }else{
        $_SESSION['error']="Passwords do not match the requirements!";
    }
    }else{
        $_SESSION['error'] = "Passwords do not match! <br> Please Try Again.";	
}
}
?>

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
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="signUp.php">SIGN UP</a></li>
                </div>
            </div>
            </ul>
        </nav>
</header>
	<body>
		<div style="background-color: #FFFFFF;
                    width: 400px;
                    height: 530px;
                    margin: 5em auto;
                    border-radius: 1.5em;
                    box-shadow: 0px 11px 35px 2px rgba(0, 0, 0, 0.14);">
			<form class="loginForm" action="" method="post">
            <h1 class="login">Reset Password</h1>
                <label style="margin-left: 1em;
                            font-family: 'Source Sans Pro', sans-serif;
                            font-weight: bold;
                            font-size: 17px;
                            color: #3e717a;">New Password</label>
                <input class="fields" type="password" name="password1" required>
                <label style="margin-left: 1em;
                            font-family: 'Source Sans Pro', sans-serif;
                            font-weight: bold;
                            font-size: 17px;
                            color: #3e717a;">Confirm the New Password</label>
                <input class="fields" type="password" name="password2" required>
                <label style= "margin-left: 1em;
                        font-family: 'Source Sans Pro', sans-serif;
                        font-size: 15px;
                        color: #55939f;
                        font-weight: 600;
                        padding-top:1px;">Password must contain:</label><br>
                <li style="margin-left: 3em;
                        font-family: 'Source Sans Pro', sans-serif;
                        font-size: 15px;
                        color: #55939f;
                        font-weight: 600;">A <b>lowercase</b> letter</li>
                <li style="margin-left: 3em;
                        font-family: 'Source Sans Pro', sans-serif;
                        font-size: 15px;
                        color: #55939f;
                        font-weight: 600;">A <b>uppercase</b> letter</li>
                <li style="margin-left: 3em;
                        font-family: 'Source Sans Pro', sans-serif;
                        font-size: 15px;
                        color: #55939f;
                        font-weight: 600;">A <b>number</b></li>
                <li style="margin-left: 3em;
                        font-family: 'Source Sans Pro', sans-serif;
                        font-size: 15px;
                        color: #55939f;
                        font-weight: 600;">A <b>special character</b>(@#-_$%^&+=§!?)</li>
                <li style="margin-left: 3em;
                        font-family: 'Source Sans Pro', sans-serif;
                        font-size: 15px;
                        color: #55939f;
                        font-weight: 600;">Minimum of <b>8 characters</b></li>
                <li style="margin-left: 3em;
                        font-family: 'Source Sans Pro', sans-serif;
                        font-size: 15px;
                        color: #55939f;
                        font-weight: 600;">Maxmimum of <b>60 characters</b></li><br>
                <input class="submit" type="submit" value="Submit" name='submit'>
                </form>
                <?php
                    if(isset($_SESSION['error'])){
                        $error = $_SESSION["error"];
                        if($error=="Passwords do not match! <br> Please Try Again."){
                        ?>
                            <span style="text-align: center;
                        color: red;
                        display: inline-block;
                        margin-left: 28%;
                        font-family: 'Source Sans Pro', sans-serif;" class='error'><?php echo "$error";?></span>
                            <?php
                    }else{
                        ?>
                            <span style="text-align: center;
                        color: red;
                        display: inline-block;
                        margin-left: 15%;
                        font-family: 'Source Sans Pro', sans-serif;" class='error'><?php echo "$error";?></span>
                            <?php
                    }}
                    if(isset($_SESSION['success'])){
                        ?>
                        <span style="text-align: center;
                    color: black;
                    display: inline-block;
                    margin-left: 28%;
                    font-family: 'Source Sans Pro', sans-serif;" class='error'><?php echo $_SESSION['success'];?></span>
                        <?php
                        
                    }
                ?>  
            </div>
		
	</body>
</html>
<?php
    unset($_SESSION["error"]);
    unset($_SESSION["success"]);
    }else{
         ?>
             <script> alert("Invalid auth");</script>
                    <?php
                }            

?>