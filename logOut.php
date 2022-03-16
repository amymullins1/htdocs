<?php
session_start(); //start or continue the session if the user already has one
session_destroy(); //destroy the current session
header("Location: login.php"); //redirect the user to the login page.
?>