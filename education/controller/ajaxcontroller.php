<?php

include_once('../../database.php');

//echo "SELECT * FROM `live_class_data` where id = '$live_class_id'";die;
// play live video coin deduction****************************
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "playlivevideo") {

    $live_class_id = $_REQUEST['live_class_id'];
    $user_id = $_REQUEST['user_id'];
//    echo $live_class_id;die;
//    
    $checkvideouser = $db->countRows("w_user_coins", "roomID='$live_class_id' and type='livevideoquiz' and uID='$user_id'");
    if ($checkvideouser == 0) {

        $getcoin = $db->selectQuery("SELECT * FROM `live_class_data` where id = '$live_class_id'");

        $video_amount = $getcoin[0]['amount'];
//    echo $video_amount;die;
        $get_user_coin = $db->selectQuery("SELECT * FROM `w_users` where ID = '$user_id'");

        $umobile = $get_user_coin[0]['uMobile'];
        $beforeCoin = $get_user_coin[0]['uCoin'];
        $franchiseID = $get_user_coin[0]['franchiseID'];

        if ($video_amount < $beforeCoin) {

            $aftercoin = $beforeCoin - $video_amount;
            $insrtw_coins = mysqli_query($db->con, "INSERT INTO `w_user_coins`(`uID`, `franchiseID`, `uMobile`, `uCoin`, `review`, `roomID`, `type`, `beforeCoin`, `afterCoin`) VALUES ('$user_id','$franchiseID','$umobile','$video_amount','livevideoplay','$live_class_id','livevideoquiz','$beforeCoin','$aftercoin')");
            if ($insrtw_coins) {
                mysqli_query($db->con, "update w_users set uCoin = '$aftercoin' where ID = '$user_id'");
            }

            /*             * ***send amount to franchise id and sponser** */
//            $adminCharge = 38;
//            $profit_percent_fr = 20;
//            $chargeAmount = ($video_amount * $adminCharge) / 100;



            $profit_fr = ($video_amount * 5) / 100;

            if ($get_user_coin[0]['franchiseID'] != "" && $get_user_coin[0]['franchiseID'] != 0 && $user_id != $get_user_coin[0]['franchiseID']) {
                $coinsData = array("uID" => $get_user_coin[0]['franchiseID'], "uCoin" => $profit_fr, "review" => "livevideoprofit_fr", "isCredit" => 1, "fromID" => $user_id, "roomID" => $live_class_id, "type" => "livevideoquiz");
                if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$get_user_coin[0]['franchiseID']}'")) {
                    $coinsData['beforeCoin'] = $coData['uCoin'];
                    $coinsData['afterCoin'] = $coData['uCoin'] + $profit_fr;
                    $db->con->query("update w_users set uCoin=uCoin+{$profit_fr} where ID='{$get_user_coin[0]['franchiseID']}'");
                }
                $db->insertData("w_user_coins", $coinsData);
            }

            $response['data'] = 1;
        } else {
            $response['data'] = 0;
        }
    } else {
        $response['data'] = 1;
    }

    echo json_encode($response);
}




//check that the course is purchased by user or not********************
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "courseamount") {

    $parent_category = $_REQUEST['parent_category'];
    $user_id = $_REQUEST['user_id'];
//    echo $parent_category;die;
//    
//    echo "SELECT * FROM `edu_category` where id = '$parent_category'";die;
    $getcoin = $db->selectQuery("SELECT * FROM `edu_category` where id = '$parent_category'");

    $course_amount = $getcoin[0]['course_amount'];
    $get_user_coin = $db->selectQuery("SELECT * FROM `w_users` where ID = '$user_id'");

    $umobile = $get_user_coin[0]['uMobile'];
    $beforeCoin = $get_user_coin[0]['uCoin'];
    $franchiseID = $get_user_coin[0]['franchiseID'];

    if ($course_amount < $beforeCoin) {

        $aftercoin = $beforeCoin - $course_amount;
        $insrtw_coins = mysqli_query($db->con, "INSERT INTO `w_user_coins`(`uID`, `franchiseID`, `uMobile`, `uCoin`, `review`, `roomID`, `type`, `beforeCoin`, `afterCoin`) VALUES ('$user_id','$franchiseID','$umobile','$course_amount','purchaseCourse','$parent_category','coursepurchase','$beforeCoin','$aftercoin')");
        if ($insrtw_coins) {
            mysqli_query($db->con, "update w_users set uCoin = $aftercoin where ID = '$user_id'");
        }

        /*         * ***send amount to franchise id and sponser** */
//        $adminCharge = 50;
//        $profit_percent_fr = 20;
//        $chargeAmount = ($course_amount * $adminCharge) / 100;
        $profit_fr = ($course_amount * 5) / 100;

        /*         * CourseProfit* */
        /*         * Level Income */
        $uid = $user_id;
        $roomID = $parent_category;
        if ($db->countRows("w_user_coins", "fromID='{$uid}' and roomID='{$roomID}' and review='courseprofit' and type='coursepurchase'") == 0) {
            ////////////
//            $chargeAmount = ($course_amount * $adminCharge) / 100;
            $quizprofit = array(1 => 25, 2 => 10, 3 => 7, 4 => 5, 5 => 3);
            if ($downline = $db->selectRow("w_sponsor_downline", "downline", "userID='{$uid}'")) {
                $spUsers = array_reverse(array_filter(explode("-", $downline['downline'])));
                if (count($spUsers) > 0) {
                    $i = 1;
                    foreach ($spUsers as $suser) {
                        if (isset($quizprofit[$i])) {
                            $am = ($course_amount * $quizprofit[$i]) / 100;
                            $coinsData = array("uID" => $suser, "uCoin" => $am, "review" => "courseprofit", "type" => "coursepurchase", "description" => "{$quizprofit[$i]}% of totalbet={$course_amount}", "isCredit" => 1, "fromID" => $uid, "roomID" => $roomID);
                            if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$suser}'")) {
                                $coinsData['beforeCoin'] = $coData['uCoin'];
                                $coinsData['afterCoin'] = $coData['uCoin'] + $am;
                            }
                            $db->insertData("w_user_coins", $coinsData);
                            $db->con->query("update w_users set uCoin=uCoin+$am where ID='$suser'");
                        }
                        $i++;
                    }
                }
            }
        }



        if ($get_user_coin[0]['franchiseID'] != "" && $get_user_coin[0]['franchiseID'] != 0 && $user_id != $get_user_coin[0]['franchiseID']) {
            $coinsData = array("uID" => $get_user_coin[0]['franchiseID'], "uCoin" => $profit_fr, "review" => "courseprofit_fr", "isCredit" => 1, "fromID" => $user_id, "roomID" => $parent_category, "type" => 'coursepurchase');
            if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$get_user_coin[0]['franchiseID']}'")) {
                $coinsData['beforeCoin'] = $coData['uCoin'];
                $coinsData['afterCoin'] = $coData['uCoin'] + $profit_fr;
                $db->con->query("update w_users set uCoin=uCoin+{$profit_fr} where ID='{$get_user_coin[0]['franchiseID']}'");
            }
            $db->insertData("w_user_coins", $coinsData);
        }

        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }

    echo json_encode($response);
}


//play quiz after seen live video************
// print_r($_POST);die();
if (isset($_POST['req_type']) && $_POST['req_type'] == "quiz_play_detail") {

    $live_class_id = mysqli_real_escape_string($db->con, $_POST['live_class_id']);
    $user_id = mysqli_real_escape_string($db->con, $_POST['user_id']);

    $checkVideoStatus = $db->selectQuery("select * from live_class_data where id = '$live_class_id'");

    if ($checkVideoStatus[0]['status'] == 0) {
        $db->updateData("live_class_data", array("status" => 1), "id={$live_class_id}");
    }

//    $curnt_time = mysqli_real_escape_string($db->con, $_POST['curnt_time']);
//        check the user played quiz before or not
    $checkvideouser = $db->countRows("w_user_coins", "roomID='$live_class_id' and type='livevideoquiz' and uID='$user_id'");
    if ($checkvideouser == 0) {

        $getcoin = $db->selectQuery("SELECT * FROM `live_class_data` where id = '$live_class_id'");

        $video_amount = $getcoin[0]['amount'];
//    echo $video_amount;die;
        $get_user_coin = $db->selectQuery("SELECT * FROM `w_users` where ID = '$user_id'");

        $umobile = $get_user_coin[0]['uMobile'];
        $beforeCoin = $get_user_coin[0]['uCoin'];
        $franchiseID = $get_user_coin[0]['franchiseID'];

        if ($video_amount < $beforeCoin) {

            $aftercoin = $beforeCoin - $video_amount;
            $insrtw_coins = mysqli_query($db->con, "INSERT INTO `w_user_coins`(`uID`, `franchiseID`, `uMobile`, `uCoin`, `review`, `roomID`, `type`, `beforeCoin`, `afterCoin`) VALUES ('$user_id','$franchiseID','$umobile','$video_amount','livevideoplay','$live_class_id','livevideoquiz','$beforeCoin','$aftercoin')");
            if ($insrtw_coins) {
                mysqli_query($db->con, "update w_users set uCoin = $aftercoin where ID = '$user_id'");
            }

            /*             * ***send amount to franchise id and sponser** */
//            $adminCharge = 38;
//            $profit_percent_fr = 20;
//            $chargeAmount = ($video_amount * $adminCharge) / 100;
            $profit_fr = ($video_amount * 5) / 100;

            if ($get_user_coin[0]['franchiseID'] != "" && $get_user_coin[0]['franchiseID'] != 0 && $user_id != $get_user_coin[0]['franchiseID']) {
                $coinsData = array("uID" => $get_user_coin[0]['franchiseID'], "uCoin" => $profit_fr, "review" => "livevideoprofit_fr", "isCredit" => 1, "fromID" => $user_id, "roomID" => $live_class_id, "type" => 'livevideoquiz');
                if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$get_user_coin[0]['franchiseID']}'")) {
                    $coinsData['beforeCoin'] = $coData['uCoin'];
                    $coinsData['afterCoin'] = $coData['uCoin'] + $profit_fr;
                    $db->con->query("update w_users set uCoin=uCoin+{$profit_fr} where ID='{$get_user_coin[0]['franchiseID']}'");
                }
                $db->insertData("w_user_coins", $coinsData);
            }
        }
        $q = "INSERT INTO `quiz_play_detail`(`live_class_id`, `user_id`) VALUES ('$live_class_id','$user_id')";
        $reslt = mysqli_query($db->con, $q);
        $response['data'] = 1;
    } else {
        $checkuser = $db->countRows("quiz_play_detail", "live_class_id='$live_class_id' and user_id='$user_id'");
        if ($checkuser == 0) {
            $q = "INSERT INTO `quiz_play_detail`(`live_class_id`, `user_id`) VALUES ('$live_class_id','$user_id')";
            $reslt = mysqli_query($db->con, $q);
            // echo $q;
            $response['data'] = 1;
        } else {
            $response['data'] = 0;
        }
    }
    echo json_encode($response);
}



//check that the school course 6th To 12th is purchased by user or not********************
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "schoolCourse") {

    $class_id = $_REQUEST['class_id'];
    $user_id = $_REQUEST['user_id'];
//    echo $parent_category;die;
//    
//    echo "SELECT * FROM `edu_category` where id = '$parent_category'";die;
    $getcoin = $db->selectQuery("SELECT * FROM `edu_category` where id = '$class_id'");

    $course_amount = $getcoin[0]['course_amount'];
    $get_user_coin = $db->selectQuery("SELECT * FROM `w_users` where ID = '$user_id'");

    $umobile = $get_user_coin[0]['uMobile'];
    $beforeCoin = $get_user_coin[0]['uCoin'];
    $franchiseID = $get_user_coin[0]['franchiseID'];

    if ($course_amount < $beforeCoin) {

        $aftercoin = $beforeCoin - $course_amount;
        $insrtw_coins = mysqli_query($db->con, "INSERT INTO `w_user_coins`(`uID`, `franchiseID`, `uMobile`, `uCoin`, `review`, `roomID`, `type`, `beforeCoin`, `afterCoin`) VALUES ('$user_id','$franchiseID','$umobile','$course_amount','purchaseCourse','$class_id','coursepurchase','$beforeCoin','$aftercoin')");
        if ($insrtw_coins) {
            mysqli_query($db->con, "update w_users set uCoin = $aftercoin where ID = '$user_id'");
        }

        $profit_fr = ($course_amount * 5) / 100;


        /*         * CourseProfit* */
        /*         * Level Income */
        $uid = $user_id;
        $roomID = $class_id;
        if ($db->countRows("w_user_coins", "fromID='{$uid}' and roomID='{$roomID}' and review='courseprofit' and type='coursepurchase'") == 0) {
            ////////////
//            $chargeAmount = ($course_amount * $adminCharge) / 100;
            $quizprofit = array(1 => 25, 2 => 10, 3 => 7, 4 => 5, 5 => 3);
            if ($downline = $db->selectRow("w_sponsor_downline", "downline", "userID='{$uid}'")) {
                $spUsers = array_reverse(array_filter(explode("-", $downline['downline'])));
                if (count($spUsers) > 0) {
                    $i = 1;
                    foreach ($spUsers as $suser) {
                        if (isset($quizprofit[$i])) {
                            $am = ($course_amount * $quizprofit[$i]) / 100;
                            $coinsData = array("uID" => $suser, "uCoin" => $am, "review" => "courseprofit", "type" => "coursepurchase", "description" => "{$quizprofit[$i]}% of totalbet={$course_amount}", "isCredit" => 1, "fromID" => $uid, "roomID" => $roomID);
                            if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$suser}'")) {
                                $coinsData['beforeCoin'] = $coData['uCoin'];
                                $coinsData['afterCoin'] = $coData['uCoin'] + $am;
                            }
                            $db->insertData("w_user_coins", $coinsData);
                            $db->con->query("update w_users set uCoin=uCoin+$am where ID='$suser'");
                        }
                        $i++;
                    }
                }
            }
        }



        if ($get_user_coin[0]['franchiseID'] != "" && $get_user_coin[0]['franchiseID'] != 0 && $user_id != $get_user_coin[0]['franchiseID']) {
            $coinsData = array("uID" => $get_user_coin[0]['franchiseID'], "uCoin" => $profit_fr, "review" => "courseprofit_fr", "isCredit" => 1, "fromID" => $user_id, "roomID" => $class_id, "type" => 'coursepurchase');
            if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$get_user_coin[0]['franchiseID']}'")) {
                $coinsData['beforeCoin'] = $coData['uCoin'];
                $coinsData['afterCoin'] = $coData['uCoin'] + $profit_fr;
                $db->con->query("update w_users set uCoin=uCoin+{$profit_fr} where ID='{$get_user_coin[0]['franchiseID']}'");
            }
            $db->insertData("w_user_coins", $coinsData);
        }

        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }

    echo json_encode($response);
}


//logout user
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "logout") {
    if (isset($_SESSION['uToken'])) {
        unset($_SESSION['uToken']);
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }
    echo json_encode($response);
}
?>