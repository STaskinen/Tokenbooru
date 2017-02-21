<?php
include("PHP/iheader.php");
        session_destroy();
        $_POST = array();
        $_SESSION['message'] = "Logged out successfully";
        //unset($_SESSION['logged']);
        redirect("index.php");
    ?>