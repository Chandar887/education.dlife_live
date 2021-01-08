<?php

include_once('../database.php');

//login user
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "login") {

    //    echo "<pre>";
    //    print_r($_REQUEST);die;
    unset($_REQUEST['submit']);
    $_REQUEST['uPassword'] = md5($_REQUEST['uPassword']);

    if ($getdata = $db->selectQuery("select * from w_users where uEmail='{$_REQUEST['uEmail']}' and uPassword='{$_REQUEST['uPassword']}' and uRole='user'")) {

        $uToken = $getdata[0]['uToken'];
        $_SESSION['uToken'] = $uToken;
        header('location: ../education/index.php');
    } else {
        $_SESSION['loginfail'] = "Something Went Wrong!";
        header('location:../index.php');
    }
}


//    register user
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "register") {
    unset($_REQUEST['submit']);

    if ($db->countRows("w_users", "uEmail='{$_REQUEST['uEmail']}' OR uMobile='{$_REQUEST['uMobile']}'")) {
        $_SESSION['err'] = "Email Or Mobile Already Exists!";
        header('location: ../register.php');
    } else {
        //        $_REQUEST['uniqueID'] = $_REQUEST['uMobile'];
        $_REQUEST['uPassword'] = md5($_REQUEST['uPassword']);
        $_REQUEST['uToken'] = md5(uniqid($_REQUEST['uMobile'], true));
        $userData = array("uName" => "{$_REQUEST['uName']}", "uMobile" => "{$_REQUEST['uMobile']}", "uEmail" => "{$_REQUEST['uEmail']}", "uPostcode" => "{$_REQUEST['uPostcode']}", "uPassword" => "{$_REQUEST['uPassword']}", "uToken" => "{$_REQUEST['uToken']}");
        //         echo "<pre>";
        //         print_r($userData);die;
        if ($db->insertData("w_users", $userData)) {
            $_SESSION['uToken'] = $_REQUEST['uToken'];
            header('location: ../education/index.php');
        }
    }
}
