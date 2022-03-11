<?php
session_start();
?>
 <link rel="stylesheet" href="homepage.css"/>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TechKnow | About</title>
    <link rel="icon" type="image/x-icon" href="/Logo/icon.png">
</head>
<body>
<header class="bannerHeader">
<?php if(!isset($_SESSION['loggedin'])){
                        echo '<ul>
                        <a href="login.php">Login</a>
                    </ul>';
            }else{
                $name = $_SESSION['fname'];
                ?>
                <ul><a class='welcome' href="profile.php">Welcome, <?php echo htmlspecialchars($name); ?></span><a href='logOut.php'>Log Out</a></ul>";
               <?php     
            }
    ?>
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
    <h1 class="menuHeader1">Your Phishing Education, Right Away!</h1>
</header>
<div id="body" class="body">
<div class="header"><h2 style="color: white;">What We Do </h2></div>
<div class = "text">
<p >Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quam adipiscing vitae proin sagittis. Sagittis orci a scelerisque purus semper. Ac orci phasellus egestas tellus rutrum tellus pellentesque eu tincidunt. Elit duis tristique sollicitudin nibh sit amet commodo. In hac habitasse platea dictumst. Eu ultrices vitae auctor eu augue. Tempus egestas sed sed risus pretium quam vulputate. Est lorem ipsum dolor sit amet consectetur. Congue mauris rhoncus aenean vel elit scelerisque mauris pellentesque pulvinar. Volutpat consequat mauris nunc congue nisi vitae. Eget lorem dolor sed viverra ipsum. Eu consequat ac felis donec. A diam maecenas sed enim ut.</p>
</div>
    <div class="header"><h2 style="color: white;">Benefits</h2></div>
    <div class = "text">
        <p >Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quam adipiscing vitae proin sagittis. Sagittis orci a scelerisque purus semper. Ac orci phasellus egestas tellus rutrum tellus pellentesque eu tincidunt. Elit duis tristique sollicitudin nibh sit amet commodo. In hac habitasse platea dictumst. Eu ultrices vitae auctor eu augue. Tempus egestas sed sed risus pretium quam vulputate. Est lorem ipsum dolor sit amet consectetur. Congue mauris rhoncus aenean vel elit scelerisque mauris pellentesque pulvinar. Volutpat consequat mauris nunc congue nisi vitae. Eget lorem dolor sed viverra ipsum. Eu consequat ac felis donec. A diam maecenas sed enim ut.</p>
    </div>

    </div>
    <footer>
    <nav>
        <ul>
            <li>Contact Us</li>
        </ul>
    </nav>
</footer>
</body>
</html>