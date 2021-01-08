<?php

class userClass {

     public function moveImage($db, $userid, $image, $type, $profile = '') {
        if ($profile == '') {
            $dir = "slip/";
        } else {
            $dir = $profile.'/';
        }
        $target_dir = '../' . $dir;
        $temp = explode(".", $image["name"]);
        $newfilename = round(microtime(true)) . $type . $userid . '.' . end($temp);
        $path = $dir. $newfilename;
        $target_file = $target_dir . $newfilename;
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        if ($image["tmp_name"] != '') {
//            echo $target_file;die;
            $this->compressImage($image["tmp_name"], $target_file, 25);
//     if (move_uploaded_file($image["tmp_name"], $target_file)) {
            return $path;
        } else {
            return false;
        }
    }

    public function compressImage($source, $destination, $quality) {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);
        return true;
    }

    public function getEpinNEw($db) {

        $epiRand = rand(100000000, 999999999);

        $epiHash = crypt($epiRand, 32);

        $while1 = true;

        while ($while1 == true) {



            $query = mysqli_query($db, "select `epin` from `epin` where `epin` = '$epiHash'") or die(mysqli_error($db));

            $userIdcheck = mysqli_num_rows($query);

            if ($userIdcheck == 0) {

                break;
            }

            $epiRand = rand(100000000, 999999999);

            $epiHash = crypt($epiRand, 32);
        }

        return $epiHash;
    }

    public function getUserUserName($db, $id, $param = null) {

        if (isset($param)) {

            $user = mysqli_fetch_array(mysqli_query($db, "SELECT '$param' FROM `user` WHERE user_id='$id'"));

            return $user;

            exit;
        }

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM `user` WHERE user_id='$id'"));

        return $user;

        exit;
    }

    public function getSponserId($db, $id) {

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT sponser_by FROM `user` WHERE id='$id'"));

        $sponser = mysqli_fetch_array(mysqli_query($db, "SELECT `id`,`user_id`,`package`,`paid_date`,`rank` FROM `user` WHERE user_id='" . $user['sponser_by'] . "'"));

        return $sponser;
        exit;
    }

    public function getallUserToday($db) {

        $date = date("Y-m-d") . ' 00:00:01';

        $user = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `user_green` where `date`>'$date'"));

        return $user;

        exit;
    }

    public function getallUser($db) {



        $user = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `user` where `package`!='InActive'"));

        return $user;

        exit;
    }

    public function silverClub($db) {



        $user = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `user` where `pool1`='1'"));

        return $user;

        exit;
    }

    public function goldClub($db) {



        $user = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `user` where `pool2`='1'"));

        return $user;

        exit;
    }

    public function DiamondClub($db) {



        $user = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `user` where `pool3`='1'"));

        return $user;

        exit;
    }

    public function totalIncome($db, $user_id) {

        $walletquery = mysqli_query($db, "SELECT SUM(amount) as totalCredit FROM  `wallet` where `userId`='$user_id' and walletType='coin' and `type`='credit'");

        $wallet = mysqli_fetch_array($walletquery);

        if (isset($wallet['totalCredit']))
            return $wallet['totalCredit'];
        else
            return 0;
    }

    public function totalTds($db, $user_id) {

        $walletquery = mysqli_query($db, "SELECT SUM(amount) as totalCredit FROM  `wallet` where `userId`='$user_id' and transaction_type='tds' and `type`='debit'");

        $wallet = mysqli_fetch_array($walletquery);

        return $wallet['totalCredit'];
    }

    public function totalAdminFee($db, $user_id) {

        $walletquery = mysqli_query($db, "SELECT SUM(amount) as totalCredit FROM  `wallet` where `userId`='$user_id' and transaction_type='admin_fee' and `type`='debit'");

        $wallet = mysqli_fetch_array($walletquery);

        return $wallet['totalCredit'];
    }

    public function totalwithdraPending($db, $user_id) {

        $walletquery = mysqli_query($db, "SELECT SUM(amount) as totalCredit FROM  `withdraw` where `userId`='$user_id' and status='pending'");

        $wallet = mysqli_fetch_array($walletquery);

        return $wallet['totalCredit'];
    }

    public function totalwithdraPaid($db, $user_id) {



        $walletquery = mysqli_query($db, "SELECT SUM(amount) as totalCredit FROM  `withdraw` where `userId`='$user_id' and status='done'");

        $wallet = mysqli_fetch_array($walletquery);

        return $wallet['totalCredit'];
    }

    public function totalDirectIncome($db, $user_id) {

        $walletquery = mysqli_query($db, "SELECT SUM(amount) as totalCredit FROM  `wallet` where `userId`='$user_id' and walletType='coin' and transaction_type='direct'");

        $wallet = mysqli_fetch_array($walletquery);

        if (isset($wallet['totalCredit']))
            return $wallet['totalCredit'];
        else
            return 0;
    }

    public function totalSingleIncome($db, $user_id) {

        $walletquery = mysqli_query($db, "SELECT SUM(amount) as totalCredit FROM  `wallet` where `userId`='$user_id' and walletType='coin' and transaction_type='team_income'");

        $wallet = mysqli_fetch_array($walletquery);

        if (isset($wallet['totalCredit']))
            return $wallet['totalCredit'];
        else
            return 0;
    }

    public function totalRoyaltyIncome($db, $user_id) {

        $walletquery = mysqli_query($db, "SELECT SUM(amount) as totalCredit FROM  `wallet` where `userId`='$user_id' and walletType='coin' and transaction_type='royalty'");

        $wallet = mysqli_fetch_array($walletquery);

        return $wallet['totalCredit'];
    }

    public function getUserName($db, $id) {

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT user_id FROM `user` WHERE id='$id'"));

        return $user['user_id'];

        exit;
    }

    public function getSponsor($db, $userId) {

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT sponser_by FROM `user` WHERE id='$userId'"));

        return $user['sponser_by'];

        exit;
    }

    public function getRewardsAchive($db, $user_id, $userreward) {



        $user = mysqli_num_rows(mysqli_query($db, "SELECT * FROM `rewards_acheiver` WHERE user_id='$user_id' and rewards='$userreward' and `eligible`=1"));

        if ($user > 0) {

            return 1;
        }

        return 0;

        exit;
    }

    public function getUserFromId($db, $id, $param = null) {

        if (isset($param)) {

            $user = mysqli_fetch_array(mysqli_query($db, "SELECT '$param' FROM `user` WHERE id='$id'"));

            return $user;

            exit;
        }

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM `user` WHERE id='$id'"));

        return $user;

        exit;
    }

    public function getMasterKey($db, $id) {



        $user = mysqli_fetch_array(mysqli_query($db, "SELECT master_key FROM `user` WHERE id='$id'"));

        return $user['master_key'];

        exit;
    }

    public function getPassword($db, $id) {



        $user = mysqli_fetch_array(mysqli_query($db, "SELECT password FROM `user` WHERE id='$id'"));

        return $user['password'];

        exit;
    }

    public function getPackage($db, $id) {



        $user = mysqli_fetch_array(mysqli_query($db, "SELECT package FROM `user` WHERE id='$id'"));

        return $user['id'];

        exit;
    }

    public function getUserId($db, $user_id) {

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT id FROM `user` WHERE user_id='$user_id'"));

        return $user['id'];

        exit;
    }

    public function getUserIdFromId($db, $id) {

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT user_id FROM `user` WHERE id='$id'"));

        return $user['user_id'];

        exit;
    }

    public function getSponser($db, $id) {

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT sponser_by FROM `user` WHERE user_id='$id'"));

        return $user['sponser_by'];

        exit;
    }

    public function getUserPhone($db, $id) {

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT phone FROM `user` WHERE id='$id'"));

        return $user['phone'];

        exit;
    }

    public function userSessionUpdate($db, $userId) {

        $data = mysqli_query($db, "SELECT * FROM `user` WHERE id = '$userId'");

        $_SESSION["user"] = mysqli_fetch_array($data);

        return true;
    }

    public function getUserFromUserId($db, $id, $param = null) {

        if (isset($param)) {

            $user = mysqli_fetch_array(mysqli_query($db, "SELECT " . $param . " FROM `user` WHERE user_id='$id'"));

            return $user;

            exit;
        }

        $user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM `user` WHERE user_id='$id'"));

        return $user;

        exit;
    }

    public function getPack($db, $pack) {

        $pack = mysqli_fetch_array(mysqli_query($db, "SELECT color FROM `package` WHERE id='$pack'"));

        return $pack['color'];
    }

    public function getWallet($db, $type, $id) {
     
        $Creditquery = mysqli_query($db, "SELECT SUM(amount) as totalCredit FROM  `wallet` where `userId`='$id' && `type`='credit' && `walletType`='$type'");
        $creditREsult = mysqli_fetch_array($Creditquery);
        $Debitquery = mysqli_query($db, "SELECT SUM(amount) as totalDebit FROM  `wallet` where `userId`='$id' && `type`='debit'  && `walletType`='$type'");
        $debitResult = mysqli_fetch_array($Debitquery);
        return $total = $creditREsult['totalCredit'] - $debitResult['totalDebit'];
    }

    public function getEpin($db, $type, $id) {
        $Creditquery = mysqli_query($db, "SELECT * FROM  `epin` where `user_id`='$id' && `status`='$type'");
        return mysqli_num_rows($Creditquery);
    }

    public function pvCountnumRows($db, $position, $id) {

        $Creditquery = mysqli_query($db, "SELECT `id` FROM  `point_matching` where `user_id`='$id' && `type`='credit' && `position`='$position'");
        $creditREsult1 = mysqli_num_rows($Creditquery);
        if (isset($creditREsult1)) {
            return $total = $creditREsult1;
        } else {
            return 0;
        }
    }

    public function pvCount($db, $position, $id) {



        $Creditquery = mysqli_query($db, "SELECT SUM(pv) as totalCredit FROM  `point_matching` where `user_id`='$id' && `type`='credit' && `position`='$position'");

        $creditREsult = mysqli_fetch_array($Creditquery);

        $Debitquery = mysqli_query($db, "SELECT SUM(pv) as totalDebit FROM  `point_matching` where `user_id`='$id' && `type`='debit'  && `position`='$position'");

        $debitResult = mysqli_fetch_array($Debitquery);

        if (isset($creditREsult['totalCredit'])) {

            return $total = $creditREsult['totalCredit'] - $debitResult['totalDebit'];
        } else {

            return 0;
        }
    }

    public function pvCounttotal($db, $position, $id) {



        $Creditquery = mysqli_query($db, "SELECT SUM(pv) as totalCredit FROM  `point_matching` where `user_id`='$id' && `type`='credit' && `position`='$position'");

        $creditREsult = mysqli_fetch_array($Creditquery);

        $Debitquery = mysqli_query($db, "SELECT SUM(pv) as totalDebit FROM  `point_matching` where `user_id`='$id' && `type`='debit'  && `position`='$position'");

        $debitResult = mysqli_fetch_array($Debitquery);

        if (isset($creditREsult['totalCredit'])) {

            return $total = $creditREsult['totalCredit'];
        } else {

            return 0;
        }
    }

    public function totalpoints($db, $position, $id) {



        $Creditquery = mysqli_query($db, "SELECT SUM(pv) as totalCredit FROM  `point_matching` where `user_id`='$id' && `type`='credit' && `position`='$position'");

        $creditREsult = mysqli_fetch_array($Creditquery);



        return $total = (isset($creditREsult['totalCredit'])) ? $creditREsult['totalCredit'] : 0;
    }

    public function totalBv($db, $uid) {

        $pr = mysqli_fetch_array(mysqli_query($db, "SELECT SUM(PV) totalbv FROM `sale` where userId='$uid' and type='user'"));

        $balance = $pr['totalbv'];

        return $balance;
    }

    public function extract_post($db, $post) {

        $var = array();

        foreach ($post as $key => $val) {

            $var[$key] = mysqli_real_escape_string($db, $val);
        }

        return $var;
    }

    public function getPackageId($db, $pack) {

        $pack = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM `package` WHERE id='$pack'"));

        return $pack;
    }

    public function directCount($db, $userID) {

        $num_rows = mysqli_num_rows(mysqli_query($db, "SELECT `user_id` FROM  `user` where `sponser_by`='$userID'"));

        return $num_rows;

        exit;
    }

    public function directTeam($db, $userID) {

        $num_rows = mysqli_num_rows(mysqli_query($db, "SELECT `id` FROM  `user` where `id`>'$userID'"));

        return $num_rows;

        exit;
    }

    public function countSponserUser($db, $user_id) {

        $while = 'true';

        $user = [];

        $data = [];

        $i = 0;

        $level = 1;

        $loop = 1;

        $countData = 0;

        $count1 = 0;

        $j = 0;

        $m = 0;

        $response = [];

        $userData = [];

        while ($while == 'true') {

            if ($i > 5) {

                $while == 'false';

                break;
            }

            $query = mysqli_query($db, "SELECT `user_id` FROM  `user` where `sponser`= '$user_id'  && enable ='1'");

            $user_id = null;

            $count = mysqli_num_rows($query);



            $count1 += $count;

            while ($dataQuery = mysqli_fetch_array($query)) {

                $userData[] = $dataQuery['user_id'];
            }

            if ($i == 0) {

                $loop = $count;

                $response[] = array('level' => $level, 'count' => $count1);

                $count1 = 0;

                $user = $userData;

                $userData = [];

                $user_id = $user[$j];

                $j++;

                $level++;

                $i++;

                continue;
            }

            if ($loop >= $m) {

                $m++;

                //$user_id = $user[$j];

                $j++;

                $i++;

                continue;
            } else {



                $loop = $count1;

                $response[] = array('level' => $level, 'count' => count($userData));

                $count1 = 0;

                $m = 0;

                if (count($userData) == 0) {

                    $while = 'false';

                    continue;
                }

                $user = $userData;

                $j = 0;

                $user_id = $user[$j];

                $j++;

                $level++;

                $userData = [];

                continue;
            }

            if ($count == 0 && $user_id == null) {

                $while = 'false';

                continue;
            }

            $i++;
        }



        return $response;
    }

    public function arraySum($db, $user_id) {

        $array = $this->countSponserUser($db, $user_id);

        $data = 0;

        foreach ($array as $d) {

            $data += $d['count'];
        }

        return $data;
    }

    public function stock($db, $pid) {

        $pr = mysqli_fetch_array(mysqli_query($db, "select sum(stock) as sp from franchise where product_id='$pid'"));

        $pd = mysqli_fetch_array(mysqli_query($db, "select sum(stock) as nmp from user_sale where product_id='$pid'"));

        $balance = $pr['sp'] - $pd['nmp'];

        return $balance;
    }

    public function totalBill($db, $franch) {



        $user = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `sale` where franchise='$franch' "));

        return $user;

        exit;
    }

    public function matchinBonus($db, $i, $user_id) {

        $queryLevel = mysqli_query($db, "SELECT * FROM `level_downline` WHERE sponser_by='$user_id' && `level`='$i'");

        $total = 0;

        while ($dataLevel = mysqli_fetch_array($queryLevel)) {

            $total1 = '';

            $walletMtching = mysqli_fetch_array(mysqli_query($db, "SELECT sum(amount) as walletAmount FROM `wallet` WHERE username='" . $dataLevel['user_id'] . "' and transaction_type='point_matching' group by username"));

            $total1 = $walletMtching['walletAmount'];

            $total = $total + $total1;
        }

        return $total;

        exit;
    }

    public function fastMoveUp($db, $i, $user_id) {



        $total1 = 0;

        $walletMtching = mysqli_fetch_array(mysqli_query($db, "SELECT sum(amount) as walletAmount FROM `wallet` WHERE username='$user_id' and level='$i' and transaction_type='point_generation' group by username"));

        $total1 = $walletMtching['walletAmount'];



        return $total1;

        exit;
    }

}
