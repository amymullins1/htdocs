<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'myProject';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if(isset($_POST['submit'])){
    if($stmt = $con->prepare('UPDATE emailTrack SET Next_email_num = ? WHERE UserId = ?')){
    $nextEmail = 1;
    $stmt->bind_param('ii', $nextEmail, $_SESSION['uid']);
    $stmt->execute();
    header('Location: profile.php');
                     
}}

?>
<link rel="stylesheet" href="homepage.css"/>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>TechKnow | Home</title>
    <link rel="stylesheet" href="homepage.css"/>
</head>
<body>
<header class="mainHeader">
        <nav>
            <ul>
            <div id = "menu" class="menu">
                <div id="logo" class="logoImage"><li><a href="index.php" ><img class="logoImage" src="Logo/horizontalCover.png"></a></li></div>
                <div class="menuText">
                    <li><a href="index.php">HOME</a></li>
                </div>
            </div>
            </ul>
        </nav>
        <hr>
    
        <?php if(!isset($_SESSION['loggedin'])){
                        Header('Location: index.php');
            }else{
                $name = $_SESSION['fname'];
                echo "<h1><p class = 'menuHeader1'>Welcome, $name!</p></h1></header>";  
                $uid = $_SESSION['uid'];
                $stmt = $con->prepare('SELECT Next_email_num, Score, ScoreOutOf from emailTrack WHERE UserId = ?');
                $stmt->bind_param('i', $uid);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($emailNum, $score, $outof);
                $stmt->fetch();
                
                if($emailNum !=0){
                    echo "<h2>Simulation in progress...</h2>";
                    echo "<h2>Score: $score/$outof</h2>";
                }
                else{
                    echo "<h2>You have not begun your Simulation yet! Start now?</h2>";
                    echo "<form action='' method='post'><input type='submit' value='Start!' name='submit'></form>";
                }
                
            }
    ?>
    
    </body>
    </html>