<?php
//starts a new session or resumes the current one.
session_start();
?>
<!--start of the html code-->
 <link rel="stylesheet" href="homepage.css"/>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--sets the title in the browser toolbar-->
    <title>TechKnow | About</title>
    <!--adds an icon to the browser toolbar-->
    <link rel="icon" type="image/x-icon" href="/Logo/icon.png">
</head>
<body>
 <!--defines the top navigation bar-->
<header class="bannerHeader">

 <!--if the user is not logged in, display the login and signup buttons-->
<?php if(!isset($_SESSION['loggedin'])){
                        echo '<ul>
                        <a href="login.php">Login</a>
                        <a href="signUp.php">Sign Up</a>
                    </ul>';
            }
//else, the user is logged in, so display a welcome button which redirects the user to their profile when clicked.
            else{
                $name = $_SESSION['fname'];
                ?>
                <ul><a class='Welcome' href="profile.php">Welcome, <?php echo htmlspecialchars($name); ?>!</span><a href='logOut.php'>Log Out</a></ul>";
               <?php     
            }
    ?>
</header>
<!--defines the second navigation bar-->
<header class="mainHeader">
    <nav>
        <ul>
            <div id = "menu" class="menu">
<!--displays the logo in the navigation bar which redirects the user to the homepage when clicked-->
                <div id="logo" class="logoImage"><li><a href="index.php" ><img class="logoImage" src="Logo/horizontalCover.png"></a></li></div>
                <div class="menuText">
<!--displays the home and about buttons which redirects the user to the appropriate pages when clicked-->
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="about.php">ABOUT</a></li>
                </div>
            </div>
        </ul>
    </nav>
    <hr>
<!--defines the header-->
    <h1 class="menuHeader1">Your Phishing Education, Right Away!</h1>
</header>
<div id="body" class="body">
<!--outputs the text to be displayed in this page.-->
<div class="header"><h2 style="color: white; font-size: 28px; margin-left: 20px;">What We Do </h2></div>
<div class = "text">
<p style="font-size: 24px; margin-left: 8%; color: pink;"><b>YOUR BIGGEST RISK IS YOUR EMAIL!</b></p>
<p style="font-size: 22px; margin-left: 10px;">TechKNOW is a phishing education website, where users can utilize the website in order to improve their knowledge on phishing attacks - the most common social engineering attack. Basic information about phishing can be found on the homepage, plus you can <span style="color: pink;"><b>take part in our email phishing simulation test</b></span> to see how vulnerable you are to a phishing scam. Simply sign up (if you do not have an account already) to use the simulation service. Once you have logged into your profile, you can begin a simulation <span style="color: pink;">whenever best suits you</span>. You can also save your score at the end of the simulation and restart the simulation to try and beat your previous score! </p>
</div>
    <div class="header"><h2 style="color: white; font-size: 28px; margin-left: 20px;">Benefits</h2></div>
    <div class = "text">
        <p style="font-size: 22px; margin-left: 10px;">One amazing benefit of this service is anyone can sign up for an account. <span style="color: pink;">We do not discriminate on age, or occupation</span> and we also offer the service <span style="color: pink;"><b>100% free</b></span> because we believe that everyone should have access to a phishing education. Another great benefit of this service is you will have access to feedback at the end of the simulation, which is tailored to your performance during the email simulation. We provide you with this so you can have an insight into the types of emails that you fell victim to and therefore you have points to work on in the future. This service is a great way to expose yourself to "phishing" scams in a safe environment to see how at risk you really are. <span style="color: pink; font-size: 23px;"><b>Sign up now!!</b></p>
    </div>

    </div>
</body>
</html>