<?php
session_start();
include('config.php');
unset($_SESSION['error']);
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'myProject';
// Try and connect using the information above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if(isset($_POST['login'])){ //if the login button is clicked
    $uname = $_POST['username'];
if($stmt = $con->prepare('SELECT UserId, Pwd, Fname, Lname, Email, DOB FROM accounts WHERE Username = ?')) {
	$stmt->bind_param('s', $uname);
    $stmt->execute();
    $stmt->store_result();
	// Store the result so we can check if the account exists in the database.
    if ($stmt->num_rows > 0) {
        echo "done";
        $stmt->bind_result($uid, $password, $fname, $lname, $email, $dob);
        $stmt->fetch();
        if (password_verify($_POST['password'], $password)) {
            // User has logged-in successfully.
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['uid'] = $uid;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['email'] = $email;
            $_SESSION['dob'] = $dob;
            $_SESSION['id'] = $id;
            header("Location: profile.php");
            
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

<link rel="stylesheet" href="login.css">
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
	</head>
	<body>
		<div class="box">
			<form class="loginForm" action="" method="post">
            <h1 class="login">Login</h1>
				<input class="fields" type="text" name="username" placeholder="Username" id="username" required>
                <input class="fields" type="password" name="password" placeholder="Password" id="password" required>
                <input class="submit" type="submit" value="Login" name='login'>
                </form>
                <?php
                    if(isset($_SESSION['error'])){
                        $error = $_SESSION["error"];
                        echo "<span class='error'>$error</span>";
                    }
                ?>  
                <p class="forgotPassword">Forgot Password?</p>
                <a href="signUp.php"><button class="signUp">Sign Up</button></a>
            </div>
		
	</body>
</html>
<?php
    unset($_SESSION["error"]);
?>

