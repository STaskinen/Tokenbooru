<?php
require_once("config.php");
//SSLon();?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
        <title>TOKENBOORU, FOR ALL YOUR TOKENING NEEDS</title>
    </head>
  <body class="iheader">  
<h2 class="uphead">
            <a href="./index.php">Mainpage</a>
            <a href="./posts.php">Posts</a>
    <?php
        if($_SESSION['logged'] == true){
            echo('<a href="./profile.php">'.$_SESSION['uname'].'</a>');
            echo('<a href="logout.php">Logout</a>');
    }else{
            echo('<a href="./login.php">Login/Sign up</a>');
        }
    ?>
            <!--<a href="https://users.metropolia.fi/~santtuta/TokenBooru/mobile/mobile.html">Mobile Site</a>-->
</h2>
    </body>
    <?php
    if(isset($_SESSION['message'])){
    include('message.php');
    }?>
</html>