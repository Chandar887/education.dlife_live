<?php
include_once('../database.php');


if(!preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo 
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i" 
, $_SERVER["HTTP_USER_AGENT"])){ 
    die('Invalid Device!');
} 


$userData = array();
if (isset($_SESSION['uToken'])) {
    $token = $_SESSION['uToken'];
    if ($userData = ($db->selectQuery("select * from w_users where uToken = '$token'"))) {
        $userData = $userData[0];
    } else {
        unset($_SESSION['uToken']);
        echo "<script type='text/javascript'> document.location = '../index.php'; </script>";
        exit;
    }
} else {
    echo "<script type='text/javascript'> document.location = '../index.php'; </script>";
    exit;
}

$currenturl = 'http://' . $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0];

// function for pagiation
function pagination($totaldata, $limit, $offset, $page, $currenturl)
{
?>
    <div class="row">
        <div class="col-sm-5 text-center">
            <div class="dataTables_info my-2" id="propertytable_info" role="status" aria-live="polite">Showing <?php echo (($totaldata > 0) ? ($offset + 1) : '0') . ' to ' . (($page * $limit) > $totaldata ? $totaldata : ($page * $limit)) . ' of ' . $totaldata; ?> entries</div>
        </div>
        <div class="col-sm-7">
            <div class="dataTables_paginate paging_simple_numbers" id="propertytable_paginate">
                <?php
                //Pagination
                $getData = $_GET;
                $currenturl = explode("?", $currenturl)[0];
                if ($totaldata > $limit) {
                ?>
                    <ul class="pagination pagination-sm justify-content-center my-2">
                        <?php
                        $total_pages = ceil($totaldata / $limit);
                        $getData['page'] = 1;
                        $pagLink = '';
                        $pagLink .= '<li class="page-item" id="propertytable_next"><a class="page-link" href="' . $currenturl . '?' . http_build_query($getData) . '"><<</a></li>';
                        if ($page > 1) {
                            $getData['page'] = ($page - 1);
                            $pagLink .= '<li class="page-item previous" id="propertytable_previous"><a class="page-link" href="' . $currenturl . '?' . http_build_query($getData) . '"><</a></li>';
                        } else {
                            $pagLink .= '<li class="page-item previous disabled" id="propertytable_previous"><a class="page-link"><</a></li>';
                        }

                        $start = 1;
                        $end = $total_pages;
                        if ($total_pages >= 5) {
                            $start = $page - 2;
                            $start = $start <= 0 ? 1 : $start;
                            $end = $page + 2;
                            $end = $end > $total_pages ? $total_pages : $end;
                        }

                        $offsetcount = ($start - 1) * $limit;
                        for ($i = $start; $i <= $end; $i++) {
                            if ($offsetcount == $offset) {
                                $pagLink .= '<li class="page-item active"><a class="page-link">' . $i . '</a></li>';
                                //$pagLink .= '<a href="" class="active">'.$i.'</a>'; 
                            } else {
                                $getData['page'] = $i;
                                $pagLink .= '<li class="page-item"><a class="page-link" href="' . $currenturl . '?' . http_build_query($getData) . '">' . $i . '</a></li>';
                            }
                            $offsetcount = $offsetcount + $limit;
                        }


                        if ($page < $total_pages) {
                            $getData['page'] = ($page + 1);
                            $pagLink .= '<li class="page-item next" id="propertytable_next"><a class="page-link" href="' . $currenturl . '?' . http_build_query($getData) . '">></a></li>';
                        } else {
                            $pagLink .= '<li class="page-item next disabled" id="propertytable_next"><a class="page-link">></a></li>';
                        }
                        $getData['page'] = $total_pages;
                        $pagLink .= '<li class="page-item" id="propertytable_next"><a class="page-link" href="' . $currenturl . '?' . http_build_query($getData) . '">>></a></li>';
                        echo $pagLink;
                        ?>
                    </ul>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
<?php }
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
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/jquery-confirm.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <meta property="og:url" content="https://200wishes.com/ludonew/quizpanel/" />
    <meta property="og:type" content="dlife" />
    <meta property="og:title" content="Dlife" />
    <meta property="og:description" content="Dlife Worlds Best quiz app. Provides you best services." />
    <meta property="og:image" content="http://wearewinner.in/demo/Dashboard/img/logo.png" />
</head>

<script>
    document.addEventListener('contextmenu', event => event.preventDefault());

    // To disable F12 options
    document.onkeypress = function(event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;
        }
    }
    document.onmousedown = function(event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;
        }
    }
    document.onkeydown = function(event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;
        }
    }
</script> 

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
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #3d2f2f5c;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
        margin: 80% 45%;
    }
</style>

<body>

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