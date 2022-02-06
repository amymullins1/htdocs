<?php
include('config.php');
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'projectUser';
$DATABASE_PASS = '5Iix/r1PyO7sixqf';
$DATABASE_NAME = 'myProject';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
//gets the email address and email ID from the url and stores in variables
$email = $_GET['email'];
$emailID = $_GET['emailId'];
if ($stmt = $con->prepare('SELECT UserId FROM accounts WHERE Email = ?')) {
    //gets the user id corresponding to the user with the email address in the url
	$stmt->bind_param('s', $email);  
    $stmt->execute(); //executes the query
    $stmt->store_result(); //stores the results the query returns
    if($stmt->num_rows()>0){ //checks if the user exists
        $stmt->bind_result($uid);
        $stmt->fetch();
        if($stmt = $con->prepare('UPDATE results set HasClicked = 1 where UserId = ? and EmailNum = ?')){
            //updates the results record for the corresponding email and user
            $stmt->bind_param('ss', $uid, $emailID); 
            $stmt->execute();
        }
        if($stmt = $con->prepare('UPDATE emailTrack set Score = Score - 1 where UserId = ? and Score>0')){
            //updates the user's score in emailTrack. WHERE Score>0 is used to ensure the score never goes below 0, since there should be no negative scores.
            $stmt->bind_param('s', $uid); 
            $stmt->execute();
        }
    }
}
?>
<link rel="stylesheet" href="linkClicked.css">
<!DOCTYPE html>
<html> 
<head><meta charset="utf-8"><title>Phishing Email!</title></head>
<body>
<h1>Oops!</h1>
<h2>You clicked on a simulated phishing email!</h2>
<h3>Please read the guidance below in order to avoid being fooled by phishing scams:<h3>
<p class="tab">1. Check for spelling mistakes</p>
<p class="tab">2. Check how you have been addressed in the email. If general terms, such as 'customer' or 'sir/madam' have been used, it is likely to be a scam.</p>
<p class="tab">3. Ensure the sender email address is a legit email. If the email address is masked by a name, click on the name in order to view the actual email address.</p>

</body>
</html>

