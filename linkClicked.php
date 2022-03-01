<?php
include('config.php');
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'projectUser';
$DATABASE_PASS = '5Iix/r1PyO7sixqf';
$DATABASE_NAME = 'myProject';

$id = $_GET['id'];
$emailID = $_GET['emailId'];

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

//checks the authentication code in the URL is valid and if it is valid, gets the userId
$stmt = $con->prepare('SELECT UserId, AuthCode from results where EmailNum = ?');
$stmt->bind_param('i', $emailID);
$stmt->execute();
$results = $stmt->get_result();
$count = 0;
while($rowData = $results->fetch_assoc()){
    echo($rowData['AuthCode']);
    if(password_verify($id, $rowData['AuthCode'])){
        $uid = $rowData['UserId'];
        $count +=1;
    }
}
//gets the email address and email ID from the url and stores in variables

if($count==1){
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

?>
<link rel="stylesheet" href="homepage.css">
<!DOCTYPE html>
<html style="background-color: #edf2f3; margin:0px;"> 
<head><meta charset="utf-8"><title>Phishing Email!</title></head>
<header class="mainHeader" style="margin: 0px;">
        <nav>
            <ul>
            <div id = "menu" class="menu">
                <div id="logo" class="logoImage"><li><a href="index.php" ><img class="logoImage" src="Logo/horizontalCover.png"></a></li></div>
                <div class="menuText">
                    <li><a href="index.php">HOME</a></li>
                <li><a href="login.php">LOG IN</a></li>
                </div>
            </div>
            </ul>
        </nav>
</header>
<body style="background-color: rgba(255, 0, 0, .65); margin: 5px;">
<h1 style="text-align: center; font-size: 40px;">Oops!</h1>
<h2>You clicked on a simulated phishing email!</h2>
<h3>Please read the guidance below in order to avoid being fooled by phishing scams:<h3>
<p class="tabAcross">1. Check for spelling mistakes</p>
<p class="tabAcross">2. Check how you have been addressed in the email. If general terms, such as 'customer' or 'sir/madam' have been used, it is likely to be a scam.</p>
<p class="tabAcross">3. Ensure the sender email address is a legit email. If the email address is masked by a name, click on the name in order to view the actual email address.</p>

</body>
</html>

<?php }else{
    ?>
    <script>alert("FAILED AUTH");</script>
    <?php
}
?>