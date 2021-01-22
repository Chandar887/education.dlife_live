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