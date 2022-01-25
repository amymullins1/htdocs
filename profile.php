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
                    <li><a href="index.php">HOME</a><a href='logOut.php'>Log Out</a></li>
                </div>
            </div>
            </ul>
        </nav>
</header>
<style>
  .tab {
    float: left;
    border-top: 1px solid #ccc;
    background-color: #edf2f3;
    width: 10%;
    height: 300px;
  }
  
  /* Style the buttons inside the tab */
  .tab button {
    display: block;
    background-color: inherit;
    color: black;
    padding: 22px 16px;
    width: 100%;
    border: none;
    outline: none;
    text-align: left;
    cursor: pointer;
    transition: 0.3s;
    font-size: 17px;
  }
  
  /* Change background color of buttons on hover */
  .tab button:hover {
    background-color: #ddd;
  }
  
  /* Create an active/current "tab button" class */
  .tab button.active {
    background-color: #ccc;
  }
  
  /* Style the tab content */
  .tabcontent {
    float: left;
    padding: 0px 12px;
    width: 70%;
    height: 300px;
  }
    </style>

</head>
<body>

<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen">London</button>
  <button class="tablinks" onclick="openCity(event, 'Paris')">Paris</button>
  <button class="tablinks" onclick="openCity(event, 'Tokyo')">Tokyo</button>
</div>

<div id="London" class="tabcontent">
  <h3>London</h3>
  <p>London is the capital city of England.</p>
</div>

<div id="Paris" class="tabcontent">
  <h3>Paris</h3>
  <p>Paris is the capital of France.</p> 
</div>

<div id="Tokyo" class="tabcontent">
  <h3>Tokyo</h3>
  <p>Tokyo is the capital of Japan.</p>
</div>

<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
    
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
                
                if($emailNum ==5){
                    echo "Simulation finished!";
                    echo "<h2>Score: $score/$outof</h2>";
                    echo "<h2>Feedback: </h2>";
                }
                elseif($emailNum == 0){
                    echo "<h2>You have not begun your Simulation yet! Start now?</h2>";
                    echo "<form action='' method='post'><input type='submit' value='Start!' name='submit'></form>";
                }
                else{
                    echo "<h2>Simulation in progress...</h2>";
                    echo "<h2>Score: $score/$outof</h2>";
                }
                
            }
    ?>
    
    </body>
    </html>