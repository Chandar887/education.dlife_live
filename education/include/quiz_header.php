<?php
include_once('../database.php');

include_once("controller/userClass.php");

if (isset($_SESSION['uToken'])) {
    $token = $_SESSION['uToken'];
    //    echo "select * from w_users where uToken = '$token' and isDisabled='0'";
    $data = mysqli_query($db->con, "select * from w_users where uToken = '$token' and isDisabled='0'");
    $userData = mysqli_fetch_assoc($data);
    $_SESSION['ludouser'] = $userData;
} else {
    echo "<script type='text/javascript'> document.location = '../index.php'; </script>";
    exit;
    //    echo"<p style='color:white;background-color:#D32F2F;padding:12px;text-align:center;margin:40px;font-size:26px;font-weight:bold;'>Invalid Request</p>";
    //    die;
}

//lse if (!isset($_SESSION['ludouser']) && basename($_SERVER['PHP_SELF']) != 'page-payment-response.php') {
//    echo"<p style='color:white;background-color:#D32F2F;padding:12px;text-align:center;margin:40px;font-size:26px;font-weight:bold;'>Invalid Request</p>";
//    die;
//}



$userClass = new userClass();
$id = $_SESSION["ludouser"]["ID"];
$query = mysqli_query($db->con, "SELECT * FROM `w_users` WHERE ID='$id'");
$userData = mysqli_fetch_assoc($query);
?>


<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>D-life</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/logo/favicon.png">

    <!-- Google Fonts
                    ============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
                    ============================================ -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- main CSS
                    ============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/jquery-confirm.min.css">
    <!-- style CSS
                    ============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
                    ============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
                    ============================================ -->
    <!--<script src="assets/js/modernizr-2.8.3.min.js"></script>-->

    <meta property="og:url" content="https://200wishes.com/ludonew/quizpanel/" />
    <meta property="og:type" content="dlife" />
    <meta property="og:title" content="Dlife" />
    <meta property="og:description" content="Dlife Worlds Best quiz app. Provides you best services." />
    <meta property="og:image" content="http://wearewinner.in/demo/Dashboard/img/logo.png" />
</head>
<style>
    .fa-sign-out:before {
        content: "\f08b";
        font-size: 16px;
    }

    .dropdown-menu show {
        position: absolute;
        transform: translate3d(22px, 38px, 0px);
        top: 0px;
        left: -14px !important;
        will-change: transform;
    }

    .show {
        display: inline !important;
    }

    .dropdown-item {
        font-size: 14px;
    }


    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: -103px;

        z-index: 1000;
        display: none;
        float: left;
        min-width: 8.2rem !important;
        padding: .5rem 0;
        margin: 0.125rem 0 0;
        font-size: 1rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: .25rem;
    }

    .dropdown-menu show {
        left: -20px;
    }

    .loader {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        background: #3d2f2f5c;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
        margin: 80% 45%;
    }
</style>
<body>
    <!-- page loader -->
    <!-- <div class="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only"></span>
        </div>
    </div> -->

    <!-- Start Header Top Area -->
    <div class="header-top-area">
        <div class="container-fluid">
            <div class="row p-1">
                <div class="col-6 col-sm-8">

                    <a href="index.php" class="loading"><img src="img/logo/logo.png" class="mt-1" alt="" width="100vw" height="auto" /></a>
                </div>

                <div class="col-6 col-sm-4 text-right">

                    <div class="btn-group">
                        <a href="live-class.php" class="text-success mt-1 loading">
                            LIVE
                        </a>
                        <div class="spinner-grow" style="color:#02c20a!important;" role="status">
                            <span class="sr-only"></span>
                        </div>
                    </div>


                    <div class="btn-group ml-2">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item loading" href="profile.php"><i class="fa fa-user mr-2"></i> Profile</a>
                            <a class="dropdown-item loading" href="changepass.php"><i class="fa fa-key mr-1"></i> Change Password</a>
                            <a class="dropdown-item" id="logout" href="#"><i class="fa fa-sign-out mr-1"></i> Sign Out</a>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- End Header Top Area -->


    <!-- Main Menu area start-->
    <div class="main-menu-area mg-tb-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">
                        <li class="active"><a href="index.php"><i class="fa fa-home icons"></i> Home</a>
                        </li>
                        <li><a data-toggle="tab" href="#Forms"><i class="fa fa-history icons"></i> Quiz History</a>
                        </li>

                    </ul>
                    <div class="tab-content custom-menu-content">
                        <div id="Home" class="tab-pane in active notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">

                            </ul>
                        </div>
                        <div id="mailbox" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="user_create_quiz.php">Add New Quiz</a>
                                </li>
                                <!--<li><a href="user_show_quiz.php">Play Quiz</a>-->
                                </li>
                                <li><a href="user_view_quiz.php">View Quiz</a>
                                </li>
                            </ul>
                        </div>

                        <div id="Forms" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="user_quiz_history.php">View Quiz History</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Menu area End-->