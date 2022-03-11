<?php
session_start();
//session timeout after x minutes
if((isset($_SESSION['inactive']))&& (time() - $_SESSION['inactive'] > 300)) {
    session_unset(); 
    session_destroy(); 
}
$_SESSION['inactive'] = time();
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
//the code for starting the simulation if the user wishes to do so
if(isset($_POST['start'])){ //if the submit button is pressed
    //update their next email number in emailTrack so their simulation is viewed as begun
    if($stmt = $con->prepare('UPDATE emailTrack SET Next_email_num = ? WHERE UserId = ?')){
    $nextEmail = 1;
    $stmt->bind_param('ii', $nextEmail, $_SESSION['uid']);
    $stmt->execute();
    header('Location: profile.php'); //reload the page to ensure database updates are shown on the webpage.
                     
}}

//this is the code which handles the edit details form
//it updates the field in the user's record in the 'accounts' table for each field in the form that is not null:
if(isset($_POST['editSubmit'])){ //if the submit button is pressed, do the following
            
    if($_POST['uname']!= ""){  //if the username field is not null then do the following    
        //checking if the new username already exists   
        $stmt = $con->prepare('SELECT * from accounts where Username = ?');
        $stmt->bind_param('s', $_POST['uname']);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){ //if the username already exists, alert the user of this and do not store the new username
            ?> 
            <script>alert("Username already exists!");</script>
            <?php
        }else{ //if the username does not exist, update the user's account record with the new username
                $stmt=$con->prepare('Update accounts set Username = ? where UserId = ?');
                $stmt->bind_param('ss', $_POST['uname'], $uid);
                $stmt->execute();  
            
        }
    }
    if($_POST['fname']!= ""){ //if the forename field is not null then do the following
        //no need to check if the forename already exists, since names are not always unique
        //update the user's record with the new forename
            $stmt=$con->prepare('Update accounts set Fname = ? where UserId = ?'); 
            $stmt->bind_param('ss', $_POST['fname'], $uid);
            $stmt->execute();
        }
        
        if($_POST['lname']!= ""){ //if the last name field is not null, then do the following
        //no need to check if the last name already exists, since names are not always unique
        //update the user's record with the new last name
            $stmt=$con->prepare('Update accounts set Lname = ? where UserId = ?');
            $stmt->bind_param('ss', $_POST['lname'], $uid);
            $stmt->execute();
        }
        if($_POST['email']!= ""){ //if the email field is not null, then do the following
            //checking if the new email already exists in the database
            $stmt = $con->prepare('SELECT * from accounts where Email = ?');
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows>0){ //if the email already exists, alert the user of this and do not store the new email
                ?>                 
            <script>alert("Email already exists!");</script> 
            <?php
            }else{ //if the email does not exist, update the user's record with the new email
                $stmt=$con->prepare('Update accounts set Email = ? where UserId = ?');
                $stmt->bind_param('ss', $_POST['email'], $uid);
                $stmt->execute();
        }
    }
    //reload the page after every field has been checked to ensure any database updates are also updated on the webpage
   header('Location: profile.php');
   
}
//deletes a user's account from the database and all records associated with the account
if(isset($_POST['deleteAccount'])){
    $stmt = $con->prepare('Delete from results where UserId = ?;'); 
    $stmt->bind_param('s', $uid);
    $stmt->execute();

    $stmt = $con->prepare('Delete from emailTrack where UserId = ?');
    $stmt->bind_param('s', $uid);
    $stmt->execute();

    $stmt = $con->prepare('Delete from accounts where UserId = ?');
    $stmt->bind_param('s', $uid);
    $stmt->execute();

    session_unset();
    session_destroy(); //destroys all session cookies (logs them out of their account)
    Header('Location: index.php'); //Redirects the user back to the homepage
}
if(isset($_POST['endSim'])){
    $stmt=$con->prepare('Select Score from emailTrack where UserId = ?');
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($score);
    $stmt->fetch();

    $stmt=$con->prepare("Insert into pastScores(UserId, Score, SavedDate) Values(?, ?, ?);");
    $date= date("d.m.y");
    $stmt->bind_param('sss', $uid, $score, $date);
    $stmt->execute();
    
    $stmt=$con->prepare('delete from results where UserId = ?;');
    $stmt->bind_param('i', $uid);
    $stmt->execute();

    $stmt=$con->prepare('update emailTrack set Next_email_num = 0, Score=5');
    $stmt->execute();
}
?>

<link rel="stylesheet" href="homepage.css"/>
<!DOCTYPE html>
<!-- webpage formatting-->
<html lang="en">
    <style>
        html, body{
           height: 100%;
        }
        </style>
        <script type="text/javascript">
        var secsCounter = 0;
        var timer = null;
        var timeOutSecs = 300; //5 minutes

        //if the mouse is moved, clicked or a key is pressed, the counter is set back to 0
        document.onclick = function () { secsCounter = 0; };
        document.onmousemove = function () { secsCounter = 0; };
        document.onkeypress = function () { secsCounter = 0; };

        //calls the checkCount() function at 1 second intervals
        timer = window.setInterval(checkCount, 1000);
        
        function checkCount() {
            secsCounter++;
            //if the seconds counter is greater or equal to the timeout seconds, alert the user that the session has timed out and redirect.
            if (secsCounter >= timeOutSecs) {
                window.clearInterval(timer);
                alert('Session Timed Out!\n Please Log In');
                window.location = "logOut.php";
            }
        }
    </script>
    <head>
    <meta charset="UTF-8"/>
    <title>TechKnow | Profile</title>
    <link rel="icon" type="image/x-icon" href="/Logo/icon.png">
    </head>
<body>
    <header class="bannerHeader">
        <ul>
        <a href='logOut.php'>Log Out</a> <!--Log out button -->
</ul>
</header>
<header class="mainHeader"> <!--Layout of standard header which is at the top of all pages for navigation -->
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
       ?>
<h1><p class = 'menuHeader1'>Welcome Back, <?php echo htmlspecialchars($name);?>!</p></h1></header>  
<!--welcome back message with user's name at the top of the screen-->
<!--htmlspecialchars() function converts any inputted html code to string to prevent XXS.-->

<!-- Styling for the tabs within the profile-->
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@200;300;400;800&family=Source+Sans+Pro:wght@300;400;600&display=swap');

   
  .tab {
    float: left;
    background-color: #edf2f3;
    width: 20%;
    height: 85%;
  }
  
   /*Styling of the tab buttons */
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
  
  /* Styling for when the user hovers over a button, change the background colour */
  .tab button:hover {
    background-color: rgba(65, 135, 148, .2);
  }
  
  /* Styling the background colour of the button which is being clicked on */
  .tab button.active {
    background-color: rgba(65, 135, 148, .4);
  }
  
  /* Styling for the contents of each tab */
  .insideTab {
    float: left;
    padding: 0px 12px;
    font-size: 25px;
    padding-left: 20px;
    color: white;
    margin: 0;
    width: 80%;

  }
  .insideTab span{
    font-family: 'Source Sans Pro', sans-serif;
    font-weight: 300px;
   }
   /* styling for tab menu bar*/
  body{
      height: 80%;
    background-color: rgb(49, 86, 94);
   
  }
    </style>

</head>
<body>

<!-- Creates the links between the html buttons and the java code.-->
<div class="tab">
  <button class="tabClass" onclick="tabEvent(event, 'Profile')" id="defaultTab">Account</button>
  <button class="tabClass" onclick="tabEvent(event, 'Edit')">Edit Account</button>
  <?php
   $stmt=$con->prepare('Select * from pastScores where UserId = ?;');
   $stmt->bind_param('s', $uid);
   $stmt->execute();
   $stmt->store_result();
   
   if($stmt->num_rows>0){
       ?><button class="tabClass" onclick="tabEvent(event, 'Scores')">Your Scores</button> <?php
   }
 
  ?>
  <button class="tabClass" onclick="tabEvent(event, 'Simulation')">Simulation</button>
 
 <?php
  $stmt = $con->prepare('SELECT Next_email_num from emailTrack WHERE UserId = ?'); //gets the next email number of the current user
  $stmt->bind_param('i', $uid);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($emailNum);
  $stmt->fetch();
  //the 'feedback' tab is only displayed if the user has finished simulation.
  if($emailNum ==6): ?>
<button class="tabClass" onclick="tabEvent(event, 'Feedback')">Feedback</button>
  <?php endif;

  ?>

</div>

<div id="Profile" class="insideTab">
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
    $stmt=$con->prepare('SELECT Username, Fname, Lname, Email, DOB FROM accounts where UserId = ?');
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($uname, $fname, $lname, $email, $dob);
        $stmt->fetch();
    ?>
    <span class='detailsText'>Name:</span>
    <span class='fieldText'><?php echo htmlspecialchars($fname)." ".htmlspecialchars($lname);?></span><br>
    <br><span class='detailsText'>Username:</span>
    <span class='fieldText'><?php echo htmlspecialchars($uname);?></span>
    <br><br><span class='detailsText'>Email:</span>
    <span class='fieldText'><?php echo htmlspecialchars($email);?></span><br>
    <br><span class='detailsText'>Date of Birth:</span>
    <span class='fieldText'><?php echo htmlspecialchars($dob);?></span>
   
    
    
</div>

<div id="Edit" class="insideTab"> <!--This is the tab for editing details-->
    <h3>Edit your personal details below:</h3>
    <style>input[name="editSubmit"]{
        background-color: #7DCC8C;
                    font-family: 'IBM Plex Mono', monospace;
                    font-size: 15px;
                    border: 2px solid black;
                    color: black;
                    width: 150px;
                    padding: 8px;
                    border-radius: 12px;
                    cursor: pointer;
                    font-weight: bolder;
                    margin-left: 15%;
    }
    input[name="editSubmit"]:hover{
        filter: brightness(90%);
    }</style>
<form onSubmit= "return confirm('Are you sure you would like to make these changes?')" action="" method="post"> <!--Confirm() checks the user wants to proceed before submitting the form -->
                <span style="margin-left: 3%; font-size: 22px;">Username: </span>
                <input style="border-radius: 12px; border: 2px solid #7DCC8C; font-family: 'IBM Plex Mono', monospace;
                font-size: 16px; padding: 1.5px;" type="text" name="uname" placeholder=" <?php echo htmlspecialchars($uname); ?>" id="uname"><br><br>
                <span style="margin-left: 3%; font-size: 22px;">First name: </span>
                <input style="border-radius: 12px; border: 2px solid #7DCC8C; font-family: 'IBM Plex Mono', monospace;
                font-size: 16px; padding: 1.5px;" type="text" name="fname" placeholder= " <?php echo htmlspecialchars($fname); ?>" id="fname"><br><br>
                <span style="margin-left: 3%; font-size: 22px;" >Last name: </span>
                <input style="border-radius: 12px; border: 2px solid #7DCC8C; font-family: 'IBM Plex Mono', monospace;
                font-size: 16px; padding: 1.5px;" type="text" name="lname" placeholder= " <?php echo htmlspecialchars($lname); ?>" id="lname"><br><br>
                <span style="margin-left: 3%; font-size: 22px;">Email: </span>
                <input style="width: 40%; border-radius: 12px; border: 2px solid #7DCC8C; font-family: 'IBM Plex Mono', monospace;
                font-size: 16px; padding: 1.5px;" type="text" name="email" placeholder=" <?php echo htmlspecialchars($email); ?>" id="email"><br><br>
                <input type="submit" value="Save Changes" name="editSubmit">
</form>
<style>
    input[name='deleteAccount']{
    background-color: red; 
                padding: 8px; 
                border-radius: 8px;
                border: 2px solid black;
                color: white; 
                font-family: 'IBM Plex Mono', monospace;
                font-weight: bolder;
                font-size: 12px;
                width: 20%;
                cursor: pointer;
    }
    input[name="deleteAccount"]:hover{
       filter: brightness(90%);
    }
</style>
<form onSubmit= "return confirm('Are you sure you want to permanently delete your account and all data associated with it?')" action="" method="post">
    <input name="deleteAccount" type="submit" value="Delete Account">
    </div>
    </form>
    
<div id="Simulation" class="insideTab">

<style>
    html, body{
    margin: 0;
    padding: 0;
    max-width: 100%;
}
    </style>
<?php 
$stmt = $con->prepare('SELECT Next_email_num, Score from emailTrack WHERE UserId = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($emailNum, $score);
$stmt->fetch();

if($emailNum ==6){
    ?>
    <h2 style="text-align: center;">Simulation finished!</h2>
    <h3>Results:</h3>
    <p>You scored: <?php echo htmlspecialchars($score);?>/5!</p>
    <?php
    $stmt=$con->prepare('SELECT EmailNum from results where UserId = ? and HasClicked = 1;');
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $results = $stmt->get_result();
    if($results->num_rows!=0){
      echo "<p>You clicked the link in the following simulation emails:</p>";
    while($rowData = $results->fetch_assoc()){
        if($rowData['EmailNum']==1){
            ?>
            <li style="margin-left: 50px;">NHS COVID vaccination email</li>
            <?php
        }elseif($rowData['EmailNum']==2){
            ?>
            <li style="margin-left: 50px;">PayPal account locked email</li>
            <?php
        }elseif($rowData['EmailNum']==3){
            ?>
            <li style="margin-left: 50px;">Chosen to win $1000 email</li>
            <?php
        }elseif($rowData['EmailNum']==4){
            ?>
            <li style="margin-left:50px;">Microsoft account hacked email</li>
            <?php
        }elseif($rowData['EmailNum']==5){
            ?>
            <li style="margin-left:50px;">Netflix payment failed email</li>
            <?php
        }
    }
}else{
    ?>
    <p>Well done you have a perfect score!</p>
    <?php
}
?>
    <style> 
    input[name="endSim"]{
        background-color: #7DCC8C;
                    font-family: 'IBM Plex Mono', monospace;
                    width: 25%;
                    font-size: 14px;
                    border: 2px solid black;
                    color: black;
                    padding: 8px;
                    border-radius: 12px;
                    cursor: pointer;
                    font-weight: bolder;
                    -webkit-appearance: none;
                    white-space: normal;
    }
    input[name="endSim"]:hover{
        filter: brightness(90%);
    }</style>
    <form onSubmit='return confirm("Are you sure you would like to end simulation? \nYour score will be saved.")' action='' method='post'><input type="submit" value="End Simulation and Save?" name="endSim"></form>
<?php
   
}
elseif($emailNum == 0){
    ?>
    <br><h3 style="text-align: center;">You have not begun your Simulation yet! <br>Start now?<h3>
        <style>
            input[name="start"]{
                background-color: #7DCC8C;
                    font-family: 'IBM Plex Mono', monospace;
                    font-size: 15px;
                    border: none;
                    color: white;
                    width: 150px;
                    padding: 8px;
                    border-radius: 12px;
                    cursor: pointer;
                    font-weight: bolder;
                    margin-left:45%;}
                    
            }
            input[name="start"]:hover{
                filter: brightness(90%);
            }
        </style>
    <form action='' method='post'><input type='submit' value='Start!' name='start'></form>
    <?php
    
}
else{
   
    echo "<p>Simulation in progress...</p><br>";
    echo "<span>You will be able to view your score and feedback at the end of simulation. </span>";
   
}

?>
</div>

<div id = "Feedback" class = "insideTab">
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
            <br> <span style="font-family: 'IBM Plex Mono', monospace; font-size: 15px;">URLs can be easily masked to say they are directing you to a legitimate place, when in reality they are not. The link may be masked to say “sign in to your Apple account” or it may be made a little more difficult by being masked to a legitimate website link. In the Netflix simulation email, you clicked on a masked link, which you may have thought was taking you to your Netflix billing information page but in fact it was not. To check the real identity of the link, hover over the URL in the email and the actual link will be shown to you. </span><br><br>
<?php
        }
    }
}
?>
</div>
<div id="Scores" class="insideTab">
    <h3>Your Past Simulation Scores:</h3>
    <table style="background-color: #edf2f3; text-align: center; border: 2px solid white; width: 25%; height: 20%;"><tr style="font-size: 20px;">
        <th style="border: 2px solid white; background-color: rgba(65, 135, 148, .5);">Score</th>
        <th style="border: 2px solid white; background-color: rgba(65, 135, 148, .5);">Date</th>
</tr>
<?php
    $stmt=$con->prepare('Select Score, SavedDate from pastScores where UserId = ?');
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $results = $stmt->get_result();
    while($rowData = $results->fetch_assoc()){
        ?>
        <tr>
        <td style='border: 2px solid white; background-color: rgba(65, 135, 148, .5); '><?php echo htmlspecialchars($rowData['Score']); ?>/5</td>
        <td style='border: 2px solid white; background-color: rgba(65, 135, 148, .5);'><?php echo htmlspecialchars($rowData['SavedDate']); ?>/5</td>
        </tr>
    
    <?php
    }  

?>
</table>
</div>
<script>
function tabEvent(evt, eventName) {
  var i, insideTab, tabClass;
  
  //hides the contents of all of the tabs
  insideTab = document.getElementsByClassName("insideTab");
  for (i = 0; i < insideTab.length; i++) {
    insideTab[i].style.display = "none";
  }
  //removes the active class from all the tabs
  tabClass = document.getElementsByClassName("tabClass");
  for (i = 0; i < tabClass.length; i++) {
    tabClass[i].className = tabClass[i].className.replace(" active", "");
  }
  //shows the contents of the current tab and adds the active class to the tab button
  document.getElementById(eventName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Gets the tab with the ID "defaultTab" and opens this tab everytime the page is loaded
document.getElementById("defaultTab").click();
</script> 
    </body>
    </html>