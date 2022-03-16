<?php
session_start();
include('config.php');
unset($_SESSION["error"]);
if(isset($_POST['submit'])){
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'projectUser';
$DATABASE_PASS = '5Iix/r1PyO7sixqf';
$DATABASE_NAME = 'myProject';
// Try and connect using the info above.
$hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$password = $_POST['password'];
if($stmt=$con->prepare('SELECT UserId FROM accounts WHERE Username = ?')){
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows>0){
        $_SESSION['error']="Username already exists!";
        
    }elseif($stmt=$con->prepare('SELECT UserId FROM accounts WHERE Email = ?')){
        $stmt->bind_param('s',$_POST['email']);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
            $_SESSION['error']="Email already exists!";
         
        }elseif(!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,60}$/', $password)) {
            $_SESSION['error']="Password does not match the requirements!";
       }elseif($stmt = $con->prepare('INSERT INTO accounts(Username, Pwd, Fname, Lname, Email, DOB, lastActive) VALUES (?, ?, ?, ?, ?, ?, ?)')) {
       
        $stmt->bind_param('sssssss', $_POST['username'], $hashedPassword, $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['dob'], date("d-m-y"));
        $stmt->execute();
        //Get the user id of the account that has just been created, in order to add records to other tables
        if($stmt = $con->prepare('SELECT UserId FROM accounts WHERE Username = ?')){
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($uid);
        $stmt->fetch();
        }
        //Add a record to the emailTrack table
        if($stmt = $con->prepare('INSERT INTO emailTrack(UserId, Next_email_num, Score) VALUES (?, ?, ?)')){
        $next_email = 0;
        $Score = 5;
        $stmt->bind_param('iii', $uid, $next_email, $Score);
        $stmt->execute();
        }
        
         //navigate to the login screen.
         header('Location: login.php');
        }
    }
              
}


    $stmt->close();
}
?>

<link rel="stylesheet" href="signUp.css">
<link rel="stylesheet" href="homepage.css"/>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>TechKnow | Sign Up</title>
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
                </div>
            </div>
            </ul>
        </nav>
</header>
	<body>
		<div class="box">
            <h1 class="signUp">Sign Up</h1>
            <p class="text">Fill in this form to create a free account.</p>
			<form class="signUpForm" action="" method="post">
				<input class="fields" type="text" name="username" placeholder="Username" id="username" required>
                <input class="fields" type="password" name="password" placeholder="Password" id="password" required>
                <label class="passwordMust">Password must contain:</label><br>
                <li class="bullet">A <b>lowercase</b> letter</li>
                <li class="bullet">A <b>uppercase</b> letter</li>
                <li class="bullet">A <b>number</b></li>
                <li class="bullet">A <b>special character</b>(@#-_$%^&+=§!?)</li>
                <li class="bullet">Minimum of <b>8 characters</b></li>
                <li class="bullet">Maxmimum of <b>60 characters</b></li><br>
                <input class="fields" type="text" name="fname" placeholder="First Name" id="fname" required>
                <input class="fields" type="text" name="lname" placeholder="Last Name" id="lname" required>
                <input class="fields" type="text" name="email" placeholder="Email" id="email" required>
                <br><label class="passwordMust">Date of Birth:</label>
                <input class="fields" type="date" name="dob" placeholder="Date of Birth" id="dob" required>
                <input class="submit" type="submit" value="Submit" name='submit'>
            </form>
            <?php
                    if(isset($_SESSION['error'])){
                        $error = $_SESSION['error'];
                        echo "<span class='error'>$error</span>";
                    }
                ?>  
            <a href="login.php"><button class="login">Login</button></a>
		</div>
	</body>
</html>
<?php
    unset($_SESSION["error"]);
?>
