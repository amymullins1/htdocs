<?php
include('config.php');
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
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
            //creates a record in results 
            $stmt->bind_param('ss', $uid, $emailID);
            $stmt->execute();

        }
    }
}
?>
