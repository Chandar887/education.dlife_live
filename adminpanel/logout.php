<?php
    session_start();
    if(isset($_SESSION['mlmadmin'])) {
        unset($_SESSION["mlmadmin"]); 
    }
    if(isset($_SESSION['status'])) {
        unset($_SESSION["status"]); 
    }
    //session_destroy();
    header("Location: index.php");
?>