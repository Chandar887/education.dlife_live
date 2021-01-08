<?php

global $db;
if (!isset($db)) {
    include_once '../../database.php';
}

//Check Login
if (isset($request['submit']) && $request['submit'] == "login") {
    $user = $request['r_username'];
    $pass = $request['r_password'];
    if ($data = $db->selectRows("w_admin_users", "ID,firstName,lastName,city,postcode,email,role", "email='" . $user . "' and password=md5('" . $pass . "') and (role='admin' or role='quizadmin')")) {
//    if ($data = $db->selectRows("w_admin_users", "ID,firstName,lastName,city,postcode,email,role", "email='" . $user . "'")) {
        echo "1";
        $_SESSION['mlmadmin'] = $data[0];
        $db->redirect("../dashboard.php");
//        header('location: ../dashboard.php');
    } else {
        echo "0";
        $_SESSION['status'] = 0;
        $db->redirect("../index.php");
//        header('location: ../index.php');
    }
} else if (isset($request['submit']) && $request['submit'] == 'user_updaterole') {
    if (isset($request['uRole']) && $request['uRole'] != '') {
        $franchiseID = $request['uID'];
        if ($request['uRole'] == "user") {
            $spID = $db->selectRow("w_users", "uSponsor", "ID={$request['uID']}")["uSponsor"];
            $franchiseID = $db->selectRow("w_users", "franchiseID", "ID=$spID")["franchiseID"];
        }
        if ($db->updateData("w_users", array("uRole" => $request['uRole'], "franchiseID" => $franchiseID), "ID={$request['uID']}")) {
            $_SESSION['status'] = 1;
        }
    }
} else if (isset($request['submit']) && $request['submit'] == 'updatepassword') {
    if ($request['opass'] != '' && $request['npass'] != '' && $request['cpass'] != '') {
//            print_r($request);
        $request['opass'] = md5($request['opass']);
        $request['npass'] = md5($request['npass']);
        $request['cpass'] = md5($request['cpass']);

//            print_r($request);die;
        if ($request['npass'] != $request['cpass']) {
            $_SESSION['status'] = 2;
        } else if ($db->countRows("w_admin_users", "password='" . $request['opass'] . "' and ID={$request['userID']}")) {
            if ($db->updateData("w_admin_users", array("password" => $request['npass']), "ID={$request['userID']}")) {
                $_SESSION['status'] = 1;
            }
        } else {
            $_SESSION['status'] = 3;
        }
    }
} else if (isset($request['submit']) && $request['submit'] == 'updatetpassword') {
    if ($request['opass'] != '' && $request['npass'] != '' && $request['cpass'] != '') {
//            print_r($request);
        $request['opass'] = md5($request['opass']);

//            print_r($request);die;
        if ($request['npass'] != $request['cpass']) {
            $_SESSION['status'] = 2;
        } else if ($db->countRows("w_admin_users", "password='" . $request['opass'] . "' and ID={$request['userID']}")) {
            if ($db->updateData("w_admin_users", array("transaction_password" => $request['npass']), "ID={$request['userID']}")) {
                $_SESSION['status'] = 1;
            }
        } else {
            $_SESSION['status'] = 3;
        }
    }
} else if (isset($request['submit']) && $request['submit'] == "updateuserbt") {
    $_SESSION['status'] = 0;
    if ($request['firstname'] != '' && $request['email'] != '' && $request['contact'] != '') {
        if (isset($request['password']) && isset($request['cpassword']) && $request['password'] != $request['cpassword']) {
            $_SESSION['status'] = 3;
        } else {
            if ($request['password'] == '' || $request['cpassword'] == '') {
                unset($request['password']);
            } else {
                $request['show_password'] = $request['password'];
                $request['password'] = md5($request['password']);
            }
            if (($count = $db->countRows("w_users", "email='{$request['email']}' || contact='{$request['contact']}'")) > 1) {
                $_SESSION['status'] = 2;
            } else {
                $id = $request['rid'];
                unset($request['rid']);
                unset($request['cpassword']);
                unset($request['submit']);
                if ($db->updateData("w_users", $request, "ID=$id")) {
                    $_SESSION['status'] = 1;
                }
            }
        }
    } else {
        $_SESSION['status'] = -1;
    }
} else if (isset($request['submit']) && $request['submit'] == "update_user_info") {
    $_SESSION['status'] = 0;
    if ($request['uName'] != '' && $request['uEmail'] != '' && $request['uMobile'] != '') {
        if (($count = $db->countRows("w_users", "uEmail='{$request['uEmail']}' || uMobile='{$request['uMobile']}'")) > 1) {
            $_SESSION['status'] = 3;
        } else {
            $id = $request['rid'];
            unset($request['rid']);
            unset($request['r_link']);
            unset($request['submit']);
//            print_r($request);
//            die;
//            $img = 'uploads/omprofile.jpg';
//            if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != '') {
//                $ftemppath = $_FILES['image']['tmp_name'];
//                $sourcepath = $db->root . 'uploads' . $db->slash . 'profile' . $db->slash;
//                if ($filename = $db->fileUpload($ftemppath, $sourcepath, $db->str_clean($request['fullName']), pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION))) {
//                    $img = "uploads/profile/" . $filename;
//                }
//            }
//            $request['image'] = $img;

            if ($db->updateData("w_users", $request, "ID=$id")) {
                $_SESSION['status'] = 1;
            }
        }
    } else {
        $_SESSION['status'] = -1;
    }
}

//user bank detail
else if (isset($request['submit']) && $request['submit'] == "savebankbt") {
    $_SESSION['status'] = 0;
//   print_r($request);die;
    if ($request['bank_name'] != '' && $request['bank_ifsc'] != '' && $request['account_number'] != '' && $request['account_name'] != '') {

        $id = $request['uID'];
        unset($request['uID']);
        unset($request['r_link']);
        unset($request['submit']);
// print_r($request);die;
        if (($count = $db->countRows("w_users_bank", "uID='{$id}'")) > 0) {
            if ($db->updateData("w_users_bank", $request, "uID=$id")) {
                $_SESSION['status'] = 1;
            }
        } else {
            $request['uID'] = $id;
            if ($db->insertData("w_users_bank", $request)) {
                $_SESSION['status'] = 1;
            }
        }
    } else {
        $_SESSION['status'] = -1;
    }
}
//update user password
else if (isset($request['submit']) && $request['submit'] == 'user_updatepassword') {

    if ($request['npass'] != '' && $request['cpass'] != '') {
        $request['npass'] = md5($request['npass']);
        $request['cpass'] = md5($request['cpass']);
        if ($request['npass'] != $request['cpass']) {
            $_SESSION['status'] = 3;
        } else {
            if ($db->updateData("w_users", array("uPassword" => $request['npass']), "ID={$request['uID']}")) {
                $_SESSION['status'] = 1;
            } else {
                $_SESSION['status'] = 3;
            }
        }
    }
} else if (isset($request['submit']) && $request['submit'] == 'addCoins') {
//    print_r($request);die;
    $_SESSION['status'] = 0;

    if ($request['uid'] != '' && $request['uCoin'] != '' && $request['uMobile'] != '') {
        $reqID = null;
        if (isset($request['reqID'])) {
            $reqID = $request['reqID'];
            unset($request['reqID']);
        }
        unset($request['submit']);
        unset($request['r_link']);
        $request['review'] = 'send';
        $request['isCredit'] = 1;

        if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$request['uid']}'")) {
            $request['beforeCoin'] = $coData['uCoin'];
            $request['afterCoin'] = $coData['uCoin'] + $request['uCoin'];
        }
        if ($id = $db->insertData("w_user_coins", $request)) {
            $db->con->query("update w_users set uCoin=uCoin+{$request['uCoin']} where ID='{$request['uid']}'");
            if ($reqID != null) {
                $db->updateData("w_coins_request", array("status" => 1), "ID='{$reqID}'");
            }
            $_SESSION['status'] = 1;
        }
    }
} else if (isset($request['submit']) && $request['submit'] == 'withdrawCoins') {
//    print_r($request);die;
    $_SESSION['status'] = 0;

    if ($request['uid'] != '' && $request['uCoin'] != '' && $request['uMobile'] != '') {
        $reqID = null;
        if (isset($request['reqID'])) {
            $reqID = $request['reqID'];
            unset($request['reqID']);
        }
        unset($request['submit']);
        unset($request['r_link']);
        $request['isCredit'] = 0;

        if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$request['uid']}'")) {
            $request['beforeCoin'] = $coData['uCoin'];
            $request['afterCoin'] = $coData['uCoin'] - $request['uCoin'];
        }
        if ($id = $db->insertData("w_user_coins", $request)) {
            $db->con->query("update w_users set uCoin=uCoin-{$request['uCoin']} where ID='{$request['uid']}'");
            if ($reqID != null) {
                $db->updateData("w_coins_request", array("status" => 1), "ID='{$reqID}'");
            }
            $_SESSION['status'] = 1;
        }
    }
} else if (isset($request['submit']) && $request['submit'] == 'activateUser') {
    $_SESSION['status'] = 0;

    if ($request['uid'] != '' && $request['uCoin'] != '' && $request['uMobile'] != '') {
        unset($request['submit']);
        unset($request['r_link']);
        $request['review'] = 'activate';
        $request['isCredit'] = 1;
        if ($uData = $db->selectRow("w_users", "", "ID='{$request['uid']}' and isActive='0'")) {
            /*             * **Matrx** */
            $uid = $uData['ID'];
            $_SESSION['status'] = activateUser($uid, 0, $db);
//            $uSponsor = $uData['uSponsor'];
//            $settings = $db->selectRow("w_settings", "value", "name='activateAmount'");
//            $amount = $settings['value'];
//
//            if ($spData = $db->selectRow("w_sponsor_downline", "downline,level", "userID='{$uSponsor}'")) {
//                $downline = $spData['downline'] . $uSponsor . "-";
//                $level = $spData['level'] + 1;
//                $newSp = array("userID" => $uid, "uSponsor" => $uSponsor, "downline" => $downline, "level" => $level);
//                if ($db->insertData("w_sponsor_downline", $newSp)) {
//                    $db->updateData("w_users", array("underplaceID" => $uSponsor, "isActive" => 1, "activateDate" => date("Y-m-d H:i:s")), "ID='$uid'");
//                    $cData = array("uID" => $uid, "uMobile" => $uData['uMobile'], "uCoin" => $amount, "review" => "activate", 'isCredit' => 1);
//                    if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$uid}'")) {
//                        $cData['beforeCoin'] = $coData['uCoin'];
//                        $cData['afterCoin'] = $coData['uCoin'] + $amount;
//                    }
//                    $db->insertData("w_user_coins", $cData);
//                    $db->con->query("update w_users set uCoin=uCoin+$amount where ID='$uid'");
//                    /**                     * * LevelIncome *** */
//                    $levelIncome = array(1 => 5, 2 => 2.5, 3 => 1.9, 4 => 1.3, 5 => 0.9, 6 => 0.7, 7 => 0.7);
//                    $spUsers = array_reverse(array_filter(explode("-", $downline))); //explode('-', $downline);
//                    if (count($spUsers) > 0) {
//                        $i = 1;
//                        foreach ($spUsers as $suser) {
//                            if (isset($levelIncome[$i])) {
//                                $am = $levelIncome[$i];
//                                $cData = array("uID" => $suser, "uCoin" => $am, "review" => "levelincome", "description" => "user $uid activated", "isCredit" => 1, "fromID" => $uid, "level" => $i);
//                                if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$suser}'")) {
//                                    $cData['beforeCoin'] = $coData['uCoin'];
//                                    $cData['afterCoin'] = $coData['uCoin'] + $am;
//                                }
//                                $db->insertData("w_user_coins", $cData);
//                                $db->con->query("update w_users set uCoin=uCoin+$am where ID='$suser'");
//                            }
//                            $i++;
//                        }
//                    }
//                    /**                     * ************ */
//                }
//            }
//            $_SESSION['status'] = 1;
        }
    }
} else if (isset($request['submit']) && $request['submit'] == 'slider') {
    unset($request['submit']);
    unset($request['r_link']);
    for ($i = 1; $i < 5; $i++) {
        if (isset($_FILES["img$i"]['tmp_name']) && $_FILES["img$i"]['tmp_name'] != '') {
            $ftemppath = $_FILES["img$i"]['tmp_name'];
            $sourcepath = $db->root . 'uploads' . $db->slash . 'slider' . $db->slash . $request['type'] . $db->slash;
            if ($filename = $db->fileUpload($ftemppath, $sourcepath, $request['type'] . "$i", pathinfo($_FILES["img$i"]['name'], PATHINFO_EXTENSION))) {
                $request["img$i"] = "uploads/slider/" . $request['type'] . "/" . $filename;
            }
        }
    }

    if ($db->countRows("w_slider_images", "type='{$request['type']}'")) {
        if ($db->updateData("w_slider_images", $request, "type='{$request['type']}'")) {
            $_SESSION['status'] = 1;
        }
    } else {
        if ($db->insertData("w_slider_images", $request)) {
            $_SESSION['status'] = 1;
        }
    }
}

if (isset($_POST['r_link'])) {
    $db->redirect($_POST['r_link']);
}


//upload excel file into database***********

if (isset($_REQUEST['upload_file']) && ($_REQUEST['upload_file']) == "upload_question_file") {
    
    if (isset($_FILES['question_file']['tmp_name']) && $_FILES['question_file']['tmp_name'] != "") {
        importExcel($db,$_FILES['question_file']['tmp_name']);
//       
//        $ftemppath = $_FILES['question_file']['tmp_name'];
//        $sourcepath = $db->root . 'uploads' . $db->slash . 'questions' . $db->slash;
//        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "quiz_questions", pathinfo($_FILES['question_file']['name'], PATHINFO_EXTENSION))) {
//            $question_file = 'uploads/questions/' . $filename;
//        }
//        $_REQUEST['question_file'] = $question_file;
       
    }
    unset($_REQUEST['upload_file']);
    
}


//upload exam contest questions excel file
if (isset($_REQUEST['upload_exam_ques']) && ($_REQUEST['upload_exam_ques']) == "upload_exam_questions") {
    
    if (isset($_FILES['exam_questions']['tmp_name']) && $_FILES['exam_questions']['tmp_name'] != "") {
//        echo  "<pre>";
//        print_r($_REQUEST);
//        die;
        importExcel($db,$_FILES['exam_questions']['tmp_name'],$_REQUEST['contest_id']);
//       
//        $ftemppath = $_FILES['question_file']['tmp_name'];
//        $sourcepath = $db->root . 'uploads' . $db->slash . 'questions' . $db->slash;
//        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "quiz_questions", pathinfo($_FILES['question_file']['name'], PATHINFO_EXTENSION))) {
//            $question_file = 'uploads/questions/' . $filename;
//        }
//        $_REQUEST['question_file'] = $question_file;
       
    }
    unset($_REQUEST['upload_exam_ques']);
    
}


