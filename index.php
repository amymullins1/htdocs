<?php
session_start(); //start the session or continue it if user is already in a session.

?>
<!DOCTYPE html>
<html lang="en">
    <?php if(isset($_SESSION['loggedin'])){ 
        ?>
<script type="text/javascript">
        var secsCounter = 0;
        var timer = null;
        var timeOutSecs = 1800; //30 minutes
        
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
                window.location = "logOut.php"; //log the user out of their account
            }
        }
    </script>
    <?php }?>
    <!-- HTML code for page layout-->
<head>
    <meta charset="UTF-8"/>
    <title>TechKnow | Home</title>
    <link rel="icon" type="image/x-icon" href="/Logo/icon.png">
    <link rel="stylesheet" href="homepage.css"/>
</head>
<body>
<header class="bannerHeader">
<!--if the user is not logged in, display the login and signup buttons in the nav bar.-->
    <?php if(!isset($_SESSION['loggedin'])){
                        echo '<ul>
                        <a href="login.php">Login</a>
                        <a href="signUp.php">Sign Up</a>
                    </ul>';
                    
            }
            //else the user is logged in so display a welcome message with their name, which redirects them to their profile when clicked.
            else{
                $name = $_SESSION['fname'];
                ?>
                <ul><a href='profile.php'><span class='welcome'>Welcome, <?php echo htmlspecialchars($name);?>!</span></a><a href='logOut.php'>Log Out</a></ul>";           
            <?php
            } 
    ?>
    
</header>
    <header class="mainHeader">
        <nav>
            <ul>
            <!--second nav bar -->
            <div id = "menu" class="menu">
                <div id="logo" class="logoImage"><li><a href="index.php" ><img class="logoImage" src="Logo/horizontalCover.png"></a></li></div>
                <div class="menuText">
                    <li><a href="index.php">HOME</a></li>
                <li><a href="about.php">ABOUT</a></li>
                </div>
            </div>
            </ul>
        </nav>
        <hr>
        <h1 class="menuHeader1">Your Phishing Education, Right Away!</h1>
    </header>
    <!--Homepage text content: -->
    <div id="body" class="body">
    <div class="header1">
        <h1 style="font-size: 28px; margin-left: 20px;">What is Phishing?</h1>
    </div>
    <div class="text">
        <p style="font-size: 22px; margin-left: 10px;">Phishing is a type of social engineering attack that is <span style="color: pink;"><b>aimed directly at you</b></span>, the internet user. These attacks work by tricking the user into handing over sensitive information, such as credit card details, or login credentials. Attackers can achieve this by encouraging the user to click on a malicious link. This could be delivered to the user in an email or text message, <span style="color: pink;">pretending to be from a trusted source</span>. These attacks can lead to disastrous results for the victim, such as loss of bank funds, or unauthorised purchases. In most cases, the victim of a phishing attack suffers from a financial loss.</p>
    </div>
    <div class ="header2">
        <h2 style="font-size: 28px; margin-left: 20px;">Most Common Types Of Phishing:</h2>
    </div>
    <div class="text">
        <ul style="font-size: 22px; margin-left: 10px;">
            <li><b>Email scams</b> - <span style="color: pink;">the most common type of phishing attack</span>. Attackers send emails, impersonating a trusted brand and entice the victim into clicking the malicious link.</li>
            <br><li><b>Spear phishing</b> - similar to email scams, however the attacker does research on the company they wish to attack, so they can use real names of other individuals within the company to make the victim believe the email is from another individual within the business. </li>
            <br><li><b>Vishing</b> - voice phishing. The attacker calls the victim's phone and urges them to take immediate action on something, which may involve handing over bank card details.</li>
            <br><li><b>Whaling</b> - an attack where individuals of high importance, such as business CEOs are specifcally targetted in order to steal sensitive information about the business.</li>
            <br><li><b>Smishing</b> - sending text messages to individuals, urging them to take action, which will likely involve clicking on a malicious link.</li>
        </ul>
        </div>
        <div class ="header2">
            <h2 style="font-size: 28px; margin-left: 20px;">History of Phishing</h2>
        </div>
        <div class="text">
            <p style="font-size: 22px; margin-left: 10px;">The first phishing email is thought to have been sent around 1995. However, one of the first major phishing scams recorded was in May 2000, the Love Bug. This was a scam which originated in the Philippines, where internet users all over the world receieved emails with "ILOVEYOU" as the subject. The contents of the email contained an attached file and a message which said "Kindly check the attached LOVELETTER coming from me". When the victims opened the attachment, a worm was unleashed that did damage to the computer. </p>

        </div>
    </div>

</body>
</html>