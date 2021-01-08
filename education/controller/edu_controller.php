<?php

require_once("../../database.php");


// update user profile
if(isset($_REQUEST['update']) && $_REQUEST['update']=="update profile"){
    echo "<pre>";
    print_r($_REQUEST);
    unset($_REQUEST['update']);

    if($db->updateData("w_users", array("uName"=>$_REQUEST['uName'], "uEmail"=>$_REQUEST['uEmail'], "uMobile"=>$_REQUEST['uMobile'], "uPostcode"=>$_REQUEST['uPostcode']) ,"ID={$_REQUEST['ID']}")){
        $_SESSION['updated'] = "Profile Updated Successfully!";
        header('location: ../profile.php');
    }
}


// update user password
if(isset($_REQUEST['update']) && $_REQUEST['update']=="update password"){
    // echo "<pre>";
    // print_r($_REQUEST);die;
    unset($_REQUEST['update']);

    if($_REQUEST['newpass']===$_REQUEST['conpass']){
        $_REQUEST['conpass'] = md5($_REQUEST['conpass']);
        $db->updateData("w_users", array("uPassword"=>$_REQUEST['conpass']), "ID={$_REQUEST['ID']}");
        $_SESSION['updatepass'] = "Password Updated Successfully!";
        header('location: ../changepass.php');
    } else {
        $_SESSION['failed'] = "New and confirm password not match!";
        header('location: ../changepass.php');
    }
    
    
}
