<?php

include_once(__DIR__ . "/../database.php");

//$contestq = mysqli_query($db->con, "SELECT * FROM `quiz_contest` where checked = 0");
$liveClassData = $db->selectQuery("select * from live_class_data where status = 1");
//echo "<pre>";
//print_r($liveClassData);die;

foreach ($liveClassData as $data) {

    $live_class_id = $data['id'];
//    echo "select * from quiz_play_detail where live_class_id = '$live_class_id' order by score DESC,complete_time ASC";die;
    $quizData = $db->selectQuery("select * from quiz_play_detail where live_class_id = '$live_class_id' order by score DESC,complete_time ASC");
    $user_id = $quizData[0]['user_id'];
//    echo $user_id;die;
//    print_r($quizData);die;
    if ($participantUsers = $db->countRows("quiz_play_detail", "live_class_id='$live_class_id'")) {

        $total_amount = $participantUsers * $data['amount'];

        $adminCharge = 50;
        $chargeAmount = ($total_amount * $adminCharge) / 100;


        /*         * CourseProfit* */
        /*         * Level Income */
        $winning_amount = $total_amount - $chargeAmount;

        $winnerData = $db->selectQuery("select * from w_users where ID = '$user_id'");
        $umobile = $winnerData[0]['uMobile'];
        $beforeCoin = $winnerData[0]['uCoin'];
        $franchiseID = $winnerData[0]['franchiseID'];
        
        $aftercoin = $beforeCoin + $winning_amount;
        $insrtw_coins = mysqli_query($db->con, "INSERT INTO `w_user_coins`(`uID`, `franchiseID`, `uMobile`, `uCoin`, `review`, `roomID`, `type`, `beforeCoin`, `afterCoin`, `isCredit`) VALUES ('$user_id','$franchiseID','$umobile','$winning_amount','VideoQuizWinner','$live_class_id','livevideoquiz','$beforeCoin','$aftercoin',1)");
        if ($insrtw_coins) {
            mysqli_query($db->con, "update w_users set uCoin = '$aftercoin' where ID = '$user_id'");
        }

        $uid = $user_id;
        $roomID = $live_class_id;
        if ($db->countRows("w_user_coins", "fromID='{$uid}' and roomID='{$roomID}' and type='livevideoquiz' and review='VideoQuizProfit'") == 0) {
////////////
            $chargeAmount = ($total_amount * $adminCharge) / 100;
            $quizprofit = array(1 => 10, 2 => 7, 3 => 5, 4 => 3, 5 => 2, 6 => 2, 7 => 1);
            if ($downline = $db->selectRow("w_sponsor_downline", "downline", "userID='{$uid}'")) {
                $spUsers = array_reverse(array_filter(explode("-", $downline['downline'])));
                if (count($spUsers) > 0) {
                    $i = 1;
                    foreach ($spUsers as $suser) {
                        if (isset($quizprofit[$i])) {
                            $am = ($chargeAmount * $quizprofit[$i]) / 100;
                            $coinsData = array("uID" => $suser, "uCoin" => $am, "review" => "VideoQuizProfit", "description" => "{$quizprofit[$i]}% of totalbet={$total_amount}", "isCredit" => 1, "fromID" => $uid, "roomID" => $roomID, "type" => 'livevideoquiz');
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
    }
        $db->updateData("live_class_data", array("status" => 2), "id={$live_class_id}");
//    echo "hitt success";
}
?>