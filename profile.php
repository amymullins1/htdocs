<?php
session_start();
$uid = $_SESSION['uid'];
if(!isset($_SESSION['loggedin'])){
    Header('Location: index.php');
}
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
    <style>
        html, body{
            height: 100%;
        }
        </style>
    <head>
    <meta charset="UTF-8"/>
    <title>TechKnow | Profile</title>
   
</head>
<body>
    <header class="bannerHeader">
        <ul>
        <a href='logOut.php'>Log Out</a>
</ul>
</header>
<header class="mainHeader">
        <nav>
            <ul>
            <div id = "menu" class="menu">
                <div id="logo" class="logoImage"><li><a href="homepage.html" ><img class="logoImage" src="Logo/horizontalCover.png"></a></li></div>
                <div class="menuText">
                    <li><a href="index.php">HOME</a></li>
                <li><a href="about.php">ABOUT</a></li>
                <li><a href="modules.html">MODULES</a></li>
                </div>
            </div>
            </ul>
        </nav>
        <hr>
        <?php $name = strtoupper($_SESSION['fname']);
echo "<h1><p class = 'menuHeader1'>Welcome Back, $name!</p></h1></header>";  
 ?>
    
    
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@200;300;400;800&family=Source+Sans+Pro:wght@300;400;600&display=swap');

   
  .tab {
    float: left;
    background-color: #edf2f3;
    width: 20%;
    height: 85%;
  }
  
  /* Style the buttons inside the tab */
  .tab button {
    display: block;
    background-color: inherit;
    color: #122729;
    padding: 22px 16px;
    width: 100%;
    border: none;
    outline: none;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
    font-size: 17px;
    font-family: 'IBM Plex Mono', monospace;
   
  }
  
  /* Change background color of buttons on hover */
  .tab button:hover {
    background-color: rgba(65, 135, 148, .2);
  }
  
  /* Create an active/current "tab button" class */
  .tab button.active {
    background-color: rgba(65, 135, 148, .4);
  }
  
  /* Style the tab content */
  .tabcontent {
    float: left;
    padding: 0px 12px;
    font-size: 25px;
    padding-left: 20px;
    color: white;
    margin: 0;
    width: 80%;

  }
  .tabcontent span{
    font-family: 'Source Sans Pro', sans-serif;
    font-weight: 300px;
   }
   
  body{
      height: 80%;
    background-color: rgb(49, 86, 94);
   
  }
    </style>

</head>
<body>

<div class="tab">
  <button class="tablinks" onclick="tabEvent(event, 'Profile')" id="defaultOpen">Account</button>
  <button class="tablinks" onclick="tabEvent(event, 'Simulation')">Simulation</button>
  <?php
  $stmt = $con->prepare('SELECT Next_email_num from emailTrack WHERE UserId = ?');
  $stmt->bind_param('i', $uid);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($emailNum);
  $stmt->fetch();
  if($emailNum ==5): ?>
<button class="tablinks" onclick="tabEvent(event, 'Feedback')">Feedback</button>
  <?php endif;?>
  
</div>

<div id="Profile" class="tabcontent">
    <style>
        .detailsText{
            color: white;
            font-size:22px;
        }
       
        .fieldText{
            font-weight: bolder;
            color: white;
            margin-left:10px;
        }
        </style>
       <br>
    <?php
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
$uname = $_SESSION['uname'];
$email = $_SESSION['email'];    
$dob = $_SESSION['dob'];
    echo "<span class='detailsText'>Name:</span>";
    echo "<span class='fieldText'>$fname $lname</span><br>";
    echo "<br><span class='detailsText'>Username:</span>";
    echo "<span class='fieldText'>$uname</span>";
    echo "<br><br><span class='detailsText'>Email:</span>";
    echo"<span class='fieldText'>$email</span><br>";
    echo "<br><span class='detailsText'>Date of Birth:</span>";
    echo "<span class='fieldText'>$dob</span>";
   
    ?>
    
</div>

<div id="Simulation" class="tabcontent">
<br>
<style>
    html, body{
    margin: 0;
    padding: 0;
    max-width: 100%;
}

    input[type=submit]{
        background-color: #7DCC8C;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 15px;
    margin-left: 150px;
  border: none;
  color: white;
  width: 150px;
  padding: 8px;
  border-radius: 12px;
  text-decoration: none;
  cursor: pointer;
  font-weight: bolder;
    }
    </style>
<?php 
$stmt = $con->prepare('SELECT Next_email_num, Score, ScoreOutOf from emailTrack WHERE UserId = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($emailNum, $score, $outof);
$stmt->fetch();

if($emailNum ==5){
    echo "<h2>Simulation finished!</h2>";
    echo "<h3>Results:</h3>";
    echo "<p>Score: $score/$outof</p>";
    $stmt=$con->prepare('SELECT EmailNum from results where UserId = ? and HasClicked = 1;');
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $results = $stmt->get_result();
    if($results->num_rows!=0){
      echo "<p>You clicked the link in the following simulation emails:</p>";
    while($rowData = $results->fetch_assoc()){
        if($rowData['EmailNum']==1){
            echo "<li>NHS COVID Vaccination email</li>";
        }elseif($rowData['EmailNum']==2){
            echo "<li>PayPal account locked email</li>";
        }elseif($rowData['EmailNum']==3){
            echo "<li>Email 3</li>";
        }elseif($rowData['EmailNum']==4){
            echo "<li>Email 4</li>";
        }
    }
}
   
}
elseif($emailNum == 0){
    echo "<span>You have not begun your Simulation yet! <br>Start now?</span><br>";
    echo "<form action='' method='post'><input type='submit' value='Start!' name='submit'></form>";
    
}
else{
   
    echo "<p>Simulation in progress...</p><br>";
    echo "<span>You will be able to view your score and feedback at the end of simulation. </span>";
   
}

?>
</div>

<div id = "Feedback" class = "tabcontent">
    <h3>Based on your performance, here are some detection methods you should use to help identify a phishing email you may receive in the future:</h3>
    <?php
$stmt=$con->prepare('SELECT EmailNum from results where UserId = ? and HasClicked = 1;');
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $results = $stmt->get_result();
    if($results->num_rows!=0){
        $feedbackCount = 0;
    while($rowData = $results->fetch_assoc()){
        if($rowData['EmailNum']==1){
            if($feedbackCount == 0){
                ?>
                <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">Examine the email for spelling and grammar mistakes</span>
            <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">There are multiple reasons as to why scammers use poor spelling and grammar in their phishing emails. One reason may be that the scammer sending the phishing email is not a native English speaker. However, the most common reason is that poor spelling and grammar is used as a tactic by attackers to dodge spam filters and sieve out all the users clever enough to notice the mistakes in the email. Vulnerable users who do not notice the mistakes are more likely to interact with the email and will be gullible enough to hand over sensitive information. </span><br><br>

                <?php
        $feedbackCount++;    
        }
            ?>
          <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">Check the email address of the sender</span>
           <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">Attackers who send phishing emails tend to add a display name to their emails to hide their real identity. Going back to the NHS simulation email, we can see that the email has the display name "NHS". In order to check the email address of a sender, you should simply click on the display name. Legitimate businesses are likely to have their own domain name, so emails sent from domains such as @hotmail.com or @gmail.com are likely to be malicious.</span>
           <br><br>
           <?php
            
        }elseif($rowData['EmailNum']==2){
            if($feedbackCount == 0){
                ?>
                 <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">Examine the email for spelling and grammar mistakes</span>
            <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">There are multiple reasons as to why scammers use poor spelling and grammar in their phishing emails. One reason may be that the scammer sending the phishing email is not a native English speaker. However, the most common reason is that poor spelling and grammar is used as a tactic by attackers to dodge spam filters and sieve out all the users clever enough to notice the mistakes in the email. Vulnerable users who do not notice the mistakes are more likely to interact with the email and will be gullible enough to hand over sensitive information. </span><br><br>
<?php
            $feedbackCount++;
            }
            ?>
            <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">Pay attention to how you have been addressed in the email</span>
           <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">Malicious emails tend to use general terms to address their recipients, such as “Dear Customer” or “Dear Sir/Madam”. This is because the attackers do not have access to any of your personal information. We can observe from the PayPal simulation email that you have been addressed as "Customer". If you observe that you have not been addressed by your name, it is likely that the email you have received is a scam. </span><br><br>
    
            <?php
        }elseif($rowData['EmailNum']==3){
            if($feedbackCount==0){
                ?>
                <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">Examine the email for spelling and grammar mistakes</span>
                <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">There are multiple reasons as to why scammers use poor spelling and grammar in their phishing emails. One reason may be that the scammer sending the phishing email is not a native English speaker. However, the most common reason is that poor spelling and grammar is used as a tactic by attackers to dodge spam filters and sieve out all the users clever enough to notice the mistakes in the email. Vulnerable users who do not notice the mistakes are more likely to interact with the email and will be gullible enough to hand over sensitive information. </span><br><br>
    <?php
            $feedbackCount++;
            }
            ?>
            <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">Does the email seem too good to be true?</span>
            <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">A scammer’s goal is to get you to interact with the email they have sent you. They can achieve this by giving you an incentive to clicking on the link in the email. If the email you receive is offering a reward of some kind, whether it’s money or a holiday, it is highly unlikely that this email is legit. </span><br><br>

          <?php
        }elseif($rowData['EmailNum']==4){
            if($feedbackCount==0){
                ?>
                <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">Examine the email for spelling and grammar mistakes</span>
                <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">There are multiple reasons as to why scammers use poor spelling and grammar in their phishing emails. One reason may be that the scammer sending the phishing email is not a native English speaker. However, the most common reason is that poor spelling and grammar is used as a tactic by attackers to dodge spam filters and sieve out all the users clever enough to notice the mistakes in the email. Vulnerable users who do not notice the mistakes are more likely to interact with the email and will be gullible enough to hand over sensitive information. </span><br><br>
    <?php
        $feedbackCount++;
            }
            ?>
            <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">The email comes across as being urgent</span>
            <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">Lots of phishing emails are created in a way to make the user feel like they must act immediately or else it will be too late. By doing this, it gives the user less time to notice inconsistencies in the email and therefore they are more likely to interact with the email, thus falling victim to the scam. </span><br><br>
<?php
        }
        elseif($rowData['EmailNum']==5){
            if($feedbackCount==0){
                ?>
                 <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">Examine the email for spelling and grammar mistakes</span>
            <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">There are multiple reasons as to why scammers use poor spelling and grammar in their phishing emails. One reason may be that the scammer sending the phishing email is not a native English speaker. However, the most common reason is that poor spelling and grammar is used as a tactic by attackers to dodge spam filters and sieve out all the users clever enough to notice the mistakes in the email. Vulnerable users who do not notice the mistakes are more likely to interact with the email and will be gullible enough to hand over sensitive information. </span><br><br>
<?php
           $feedbackCount++;
            }
            ?>
            <span style="margin-left: 30px; font-size: 22px; font-weight: bolder;">Double check the URLs</span>
            <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">URLs can be easily masked to say they are directing you to a legitimate place, when in reality they are not. The link may be masked to say “sign in to your Apple account” or it may be made a little more difficult by being masked to a legitimate website link. In the xxx simulation email, you clicked on a masked link, which you may have thought was taking you to the iCloud sign in page but in fact it was not. To check the real identity of the link, hover over the URL in the email and the actual link will be shown to you. </span><br><br>
<?php
        }
    }
}
?>
</div>
<script>
function tabEvent(evt, eventName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(eventName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script> 
    </body>
    </html>