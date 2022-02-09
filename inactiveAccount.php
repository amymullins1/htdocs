<?php
//this code deletes any user who has been inactive for a year. This file will be run everyday from crontabs.

//define the sql connection
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'myProject';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$date = date("d-m-y", strtotime('-1 year')); //gets today's date and subtracts a year
$stmt=$con->prepare('Select UserId from accounts where lastActive < ?;'); 

//gets the user ids of all the users who have been inactive for a year.
$stmt->bind_param('s', $date);
$stmt->execute();
$results = $stmt->get_result();

while($rowData = $results->fetch_assoc()){ //loops through each result from the query

    //deletes all the records associated with the user from all tables
    $stmt= $con->prepare('Delete from pastScores where UserId = ?');
    $stmt->bind_param('s', $rowData['UserId']);
    $stmt->execute();

    $stmt= $con->prepare('Delete from results where UserId = ?');
    $stmt->bind_param('s', $rowData['UserId']);
    $stmt->execute();

    $stmt= $con->prepare('Delete from emailTrack where UserId = ?');
    $stmt->bind_param('s', $rowData['UserId']);
    $stmt->execute();

    $stmt= $con->prepare('Delete from accounts where UserId = ?');
    $stmt->bind_param('s', $rowData['UserId']);
    $stmt->execute();
}
?>