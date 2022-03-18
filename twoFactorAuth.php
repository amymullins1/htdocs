<?php 
session_start();
include('config.php');

//database parameters defined
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'projectUser';
$DATABASE_PASS = '5Iix/r1PyO7sixqf';
$DATABASE_NAME = 'myProject';
// Connect using the parameters above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
//if the user has completed login validation successfully, display the two-factor authentication page.
//This stops people from being able to skip the login page
if(isset($_SESSION['validUser'])){
    if(isset($_POST['authBtn'])){ //if the authenticate button is clicked, do the following

        //checks if the authentication code entered matches the authentication code stored in the user's auth record.
        $stmt=$con->prepare('Select AuthCode from auth where UserId = ?');
        $stmt->bind_param('s', $_SESSION['uid']);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($authcode);
        $stmt->fetch();
       
        if(password_verify($_POST['auth'], $authcode)){
            //if the authentication code entered is valid, log the user into their profile.
            $stmt=$con->prepare('Select UserId, Fname, Lname, Email, DOB from accounts where UserId = ?');
            $stmt->bind_param('s', $_SESSION['uid']);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($uid, $fname, $lname, $email, $dob); //gets the user's account details
            $stmt->fetch();
            $stmt=$con->prepare('Update accounts set lastActive = ? where UserId = ?'); //updates the lastActive field of the account record.
            //This is to ensure the account does not get deleted by the inactiveAccount script.
            $stmt->bind_param('ss', date("d-m-y"), $uid);
            $stmt->execute();

            //regenerate the session ID and store the users details in session variables. 
            //Set the logged in session variable to true.
            session_regenerate_id();
            $_SESSION['uid'] = $uid;
            $_SESSION['uname']= $uname;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['email'] = $email;
            $_SESSION['dob'] = $dob;
            $_SESSION['id'] = $id;
            $_SESSION['loggedin'] = TRUE;
            //delete the authentication code that has just been used since it is no longer needed.
            $stmt=$con->prepare('Delete from auth where userid = ?');
            $stmt->bind_param('s', $uid);
            $stmt->execute();

            Header('Location: profile.php'); //redirect the user to their profile.
        }else{ //else, the code the user has entered is invalid
            $_SESSION['authCount'] ++; 
            if($_SESSION['authCount'] == 3){ //the user has reached the code input limit
                session_destroy(); //destroy the session so the user is not authenticated.
                Header('Location: index.php'); //redirect to the homepage
                
            }else{ //the limit has not been reached so display an invalid authentication code alert message.
                ?><script>
            alert('Incorrect authentication code! \n'+(3-<?php echo $_SESSION['authCount']?>)+' attempts left.');
            </script> <?php
            }
        }
    }
    
    



?>

<link rel="stylesheet" href="login.css">
<link rel="stylesheet" href="homepage.css">
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>TechKnow | Authenticate</title>
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
            <h1 class="login">Two-Factor Authentication</h1>
            <label class="text">An email has been sent to you containing a one-time code. Please enter this code below: </label><br>
				<input class="fields" type="text" name="auth" placeholder="4-Digit Code" required>
                <input style="cursor: pointer;
                    border-radius: 5em;
                    color: #fff;
                    background: linear-gradient(to right, #3e717a, #89bbc5);
                    border: 0;
                    padding-left: 40px;
                    padding-right: 40px;
                    padding-bottom: 10px;
                    padding-top: 10px;
                    font-family: 'Source Sans Pro', sans-serif;
                    font-weight: 600;
                    margin-left: 30%;
                    font-size: 15px;" type="submit" value="Authenticate!" name='authBtn'>
                </form>
                <?php
                    if(isset($_SESSION['error'])){
                        $error = $_SESSION["error"];
                        echo "<span class='error'>$error</span>";
                    }
                ?>  
            </div>
		
	</body>
</html>
<?php
}else{
    ?>
    <script>alert("Permission denied.");</script>
    <?php
}
?>