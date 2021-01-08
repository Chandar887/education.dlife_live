<?php

if (!isset($db)) {
    include_once '../../database.php';
}

if (isset($request['req_type'])) {
    $response = array('status' => 0, 'data' => array());
    //remove Category
    if ($request['req_type'] == "get_username") {
        if ($data = $db->selectRows("w_users", "", "uMobile='{$request['r_mob']}'")) {
            $response["data"] = $data[0];
            $response["status"] = 1;
        }
    } else if ($request['req_type'] == "storelogin") {
        if ($data = $db->selectRows("w_stores", "", "ID='{$request['r_id']}'")) {
            $_SESSION['mlmstore'] = $data[0];
            $response["status"] = 1;
        }
    } else if ($request['req_type'] == "cancelCoins_req") {
        if ($data = $db->updateData("w_coins_request", array("status" => -1), "ID='{$request['r_id']}'")) {
            $response["status"] = 1;
        }
    } else if ($request['req_type'] == 'withdraw_accept') {
        if ($request['r_ID'] != '' && $request['r_amount'] != '') {
            if ($db->updateData("w_user_withdraw", array("status" => 1), "ID={$request['r_ID']}")) {
                $response['status'] = 1;
            } else {
                $response['status'] = 0;
            }
        }
    } else if ($request['req_type'] == 'withdraw_reject') {
        if ($request['r_ID'] != '' && $request['r_amount'] != '') {
            if ($db->updateData("w_user_withdraw", array("status" => -1), "ID={$request['r_ID']}")) {

                $db->con->query("update w_users set uCoin=uCoin+{$request['r_amount']} where ID='{$request['r_userID']}'");
                $cData = array("uID" => $request['r_userID'], "uCoin" => $request['r_amount'], "review" => "withdrawReject", "description" => "withdraw rejected, amount get back", "isCredit" => 1);
                if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$request['r_userID']}'")) {
                    $cData['beforeCoin'] = $coData['uCoin'];
                    $cData['afterCoin'] = $coData['uCoin'] + $request['r_amount'];
                }
                $db->insertData("w_user_coins", $cData);
                $response['status'] = 1;
            } else {
                $response['status'] = 0;
            }
        }
    } else if ($request['req_type'] == "userstatus_update") {
//        $response['data']=$request;
        if ($db->updateData("w_users", array("isDisabled" => $request['r_status']), "ID={$request['r_id']}")) {
            $response["status"] = 1;
        }
    }
    echo json_encode($response);
} else {
    echo json_encode(array('status' => 0, 'data' => 'Exist Request Type Not Exist'));
}


//delete contest
if (isset($_POST['contest_id']) && $_POST['contest_id'] != "") {
//    print_r($_POST);die;
    $contest_id = mysqli_real_escape_string($db->con, $_POST['contest_id']);

    $delq = mysqli_query($db->con, "DELETE FROM `quiz_contest` WHERE id = '$contest_id'");

    $response['data'] = 1;
}

//delete question
if (isset($_POST['question_id']) && $_POST['question_id'] != "delete_question") {
    $question_id = $_POST['question_id'];

    $getPdfs = $db->selectQuery("select que_image from contest_que where id = '$question_id'");
    
    if (isset($getPdfs[0]['que_image']) && $getPdfs[0]['que_image'] != '') {
        $url = parse_url($getPdfs[0]['que_image']);
        $img = str_replace('/ludonew/', '', $url['path']);
        $db->imageRemove('../../' . $img);
    }

    $delqq = mysqli_query($db->con, "DELETE FROM `contest_que` WHERE id = $question_id");

    $response['data'] = 1;
}

