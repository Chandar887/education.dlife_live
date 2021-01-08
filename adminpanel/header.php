<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!(isset($_SESSION['mlmadmin']) && $_SESSION['mlmadmin']['role'] == 'admin' || $_SESSION['mlmadmin']['role'] == 'quizadmin')) {
    header("Location: index.php");
}
if (!isset($db)) {
    include_once '../database.php';
}
global $title;
global $apage;
if (!isset($apage))
    $apage = '';
if (isset($title) && $title != '') {
    $title = $title;
} else {
    $title = "Adminpanel";
}
$currenturl = 'http://' . $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0];
?>

<?php

function pagination($totaldata, $limit, $offset, $page, $q, $currenturl) {
    ?>
    <div class="row">
        <div class="col-sm-5">
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
                    <ul class="pagination pagination-sm justify-content-end my-2">
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

<?php } ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="assets/favicon.png">

        <title><?php echo $title; ?></title>
        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap-4-sandstone.min.css" rel="stylesheet">
        <!-- Dashboard Style -->
        <link href="assets/css/dashboard.css" rel="stylesheet">
        <!-- Editor -->
        <link href="assets/css/editor.css" rel="stylesheet">
        <!-- input tag -->
        <link href="assets/css/choices.min.css" rel="stylesheet">
        <!-- Icons -->
        <link rel="stylesheet" href="assets/css/font-awesome-4.min.css">
        <link rel="stylesheet" href="assets/css/all.min.css">
        <!-- Confirm -->
        <link rel="stylesheet" href="assets/css/jquery-confirm.min.css">
        <!-- Color Picker -->
        <link rel="stylesheet" href="assets/css/spectrum.css">

        <!-- Tree Org Chart -->
        <link rel="stylesheet" href="assets/jstree/themes/default/style.min.css" />
        <link rel="stylesheet" href="assets/css/org-chart.css">

        <!-- Upload -->
        <link rel="stylesheet" href="assets/bootstrap-fileinput/css/fileinput.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap-select.min.css">

        <!-- My Custom code -->
        <link rel="stylesheet" href="assets/css/custom.css">
        
    </head>

    <body>
        <nav class="navbar navbar-dark sticky-top navbar-expand-md bg-primary flex-md-nowrap py-0">
            <a class="navbar-brand px-3 mr-0" href="index.php">Adminpanel</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $apage == 'dashboard' ? 'active' : ''; ?>" href="index.php">
                            <i class="pr-2 fas fa-tachometer-alt"></i>Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo $apage == 'users' ? 'active' : ''; ?>" href="page-userlist.php">
                            <i class="pr-2 fa fa-users"></i>Users
                        </a>
                    </li>
                 
                    <li class="nav-item">
                        <a class="nav-link <?php echo $apage == 'coinslist' ? 'active' : ''; ?>" href="page-coins-list.php">
                            <i class="pr-2 fa fa-coins"></i>Coins History
                        </a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link <?php echo $apage == 'slider' ? 'active' : ''; ?>" href="page-slider.php">
                            <i class="pr-2 far fa-images"></i>Slider
                        </a>
                    </li>
                    
                     <li class="nav-item">
                         <a class="nav-link <?php echo $apage == 'payments' ? 'active' : ''; ?>" href="course-payments-list.php">
                            <i class="pr-2 fa fa-rupee-sign"></i>Course Payments
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo $apage == 'viewstudymaterial' || $apage == 'addcategory' || $apage == 'addliveclass' || $apage == 'addstudymaterial' || $apage == 'viewliveclasses' ? 'active' : ''; ?>" id="navDropDownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="pr-1 fa fa-book-open"></i>Education
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navDropDownLink">
                            <a class="dropdown-item" href="add_edu_category.php">Add Category</a>
                            <a class="dropdown-item" href="add_sub_material.php">Add Study Material</a>
                            <a class="dropdown-item" href="add-live-class.php">Add Live Class</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="view-study-material.php">View Study Material</a>
                            <a class="dropdown-item" href="view-live-classes.php">View Live Classes</a>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="../" target="_blank" title="Front End"><i class="px-1 fas fa-home"></i></a></li>
                    <!--<li class="nav-item"><button class="nav-link btn btn-outline-warning text-light mx-2 dayend" title="Day End">Day End</button></li>-->
                    <li class="nav-item dropdown" >
                        <a href="#" class="nav-link btn btn-danger text-light dropdown-toggle" id="navDropDownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target="#userprofile">
                            <?php echo $_SESSION['mlmadmin']['firstName']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navDropDownLink" id="userprofile">
                            <!--<a class="dropdown-item" href="page-profile.php">Profile</a>-->
                            <a class="dropdown-item" href="page-password.php">Change Password</a>
                            <a class="dropdown-item" href="page-tpassword.php">Change Transection Password</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
