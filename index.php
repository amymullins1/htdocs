<?php
session_start();
if(isset($_SESSION['validUser']) && !isset($_SESSION['loggedIn'])){
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php if(isset($_SESSION['loggedin'])){ 
        ?>
<script type="text/javascript">
        var secsCounter = 0;
        var timer = null;
        var timeOutSecs = 10; //10 seconds
        
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
    <?php }?>
<head>
    <meta charset="UTF-8"/>
    <title>TechKnow | Home</title>
    <link rel="icon" type="image/x-icon" href="/Logo/icon.png">
    <link rel="stylesheet" href="homepage.css"/>
</head>
<body>
<header class="bannerHeader">
    <?php if(!isset($_SESSION['loggedin'])){
                        echo '<ul>
                        <a href="login.php">Login</a>
                        <a href="signUp.php">Sign Up</a>
                    </ul>';
                    
            }else{
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
    <div id="body" class="body">
    <div class="header1">
        <h1>What is Phishing?</h1>
    </div>
    <div class="text">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quam adipiscing vitae proin sagittis. Sagittis orci a scelerisque purus semper. Ac orci phasellus egestas tellus rutrum tellus pellentesque eu tincidunt. Elit duis tristique sollicitudin nibh sit amet commodo. In hac habitasse platea dictumst. Eu ultrices vitae auctor eu augue. Tempus egestas sed sed risus pretium quam vulputate. Est lorem ipsum dolor sit amet consectetur. Congue mauris rhoncus aenean vel elit scelerisque mauris pellentesque pulvinar. Volutpat consequat mauris nunc congue nisi vitae. Eget lorem dolor sed viverra ipsum. Eu consequat ac felis donec. A diam maecenas sed enim ut.</p>
    </div>
    <div class ="header2">
        <h2>Types Of Phishing</h2>
    </div>
    <div class="text">
        <ul>
            <li>Lorem ipsum dolor sit amet, </li>
               <li>consectetur adipiscing elit,</li>
            <li>sed do eiusmod tempor incididunt </li>

        </ul>
        </div>
        <div class ="header2">
            <h2>History of Phishing</h2>
        </div>
        <div class="text">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quam adipiscing vitae proin sagittis. Sagittis orci a scelerisque purus semper. Ac orci phasellus egestas tellus rutrum tellus pellentesque eu tincidunt. Elit duis tristique sollicitudin nibh sit amet commodo. In hac habitasse platea dictumst. Eu ultrices vitae auctor eu augue. Tempus egestas sed sed risus pretium quam vulputate. Est lorem ipsum dolor sit amet consectetur. Congue mauris rhoncus aenean vel elit scelerisque mauris pellentesque pulvinar. Volutpat consequat mauris nunc congue nisi vitae. Eget lorem dolor sed viverra ipsum. Eu consequat ac felis donec. A diam maecenas sed enim ut.</p>

        </div>
    </div>
    <footer class="footer">
        <nav>
            <ul>
                <li>Contact Us</li>
            </ul>
        </nav>
    </footer>

</body>
</html>