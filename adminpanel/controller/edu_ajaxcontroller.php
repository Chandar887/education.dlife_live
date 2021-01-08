<?php

if (!isset($db)) {
    include_once '../../database.php';
}


//delete study material category
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "delete_category") {
    $cat_id = $_REQUEST['cat_id'];
    $subcat = $db->selectQuery("select category_name,parent_category from edu_category where id = '$cat_id'");
    $subcatName = $subcat[0]['category_name'];
    $parent = $subcat[0]['parent_category'];
//    echo $parent;
    if ($db->deleteData("study_material", "sub_category='{$subcatName}' and parent_category='{$parent}'")) {
        $db->deleteData("edu_category", "id='{$cat_id}'");
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }
    echo json_encode($response);
}

//delete whole parent category with sub categories
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "deleteparent") {
    $cat_id = $_REQUEST['cat_id'];

    $parentName = $db->selectQuery("select category_name from edu_category where id = '$cat_id'");

    $parent = $parentName[0]['category_name'];
    
    if ($db->deleteData("edu_category", "category_name='{$parent}'")) {
        $db->deleteData("edu_category", "parent_category='{$parent}'");
        $db->deleteData("study_material", "parent_category='{$parent}'");
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }
    echo json_encode($response);
}       



//delete study material subject
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "delete_subject") {
    $subject_id = $_REQUEST['subject_id'];

    if ($db->deleteData("edu_subjects", 'id' . '=' . $subject_id)) {
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }

    echo json_encode($response);
}



//delete study material table row data
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "deletestudy") {
    $study_id = $_REQUEST['study_id'];

    if ($db->deleteData("study_material", 'id' . '=' . $study_id)) {
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }

    echo json_encode($response);
}


//delete live class table row data
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "delliveclass") {
    $class_id = $_REQUEST['class_id'];

    if ($db->deleteData("live_class_data", 'id' . '=' . $class_id)) {
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }

    echo json_encode($response);
}

//delete question
if (isset($_REQUEST['req_type']) && $_POST['req_type'] == "live_que_deletion") {
    $question_id = $_REQUEST['question_id'];
//    echo $question_id;die;
    if ($db->deleteData("live_class_que", "id=" . $question_id)) {
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }
}



//delete exam contest from quizpanel
if (isset($_REQUEST['req_type']) && $_POST['req_type'] == "delete_exam_contest") {
    $contest_id = $_REQUEST['contest_id'];
//    echo $question_id;die;
    if ($db->deleteData("exam_contest", "id=" . $contest_id)) {
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }
}


//delete exam contest category
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "delete_exam_category") {
    $cat_id = $_REQUEST['cat_id'];

    if ($db->deleteData("quiz_category", 'id' . '=' . $cat_id)) {
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }
    echo json_encode($response);
}



//delete orders data
if (isset($_REQUEST['req_type']) && $_REQUEST['req_type'] == "delete_record") {
    $orderID = $_REQUEST['orderID'];
//    echo $orderID;die;
    if ($db->deleteData("w_orders", 'ID' . '=' . $orderID)) {
        $response['data'] = 1;
    } else {
        $response['data'] = 0;
    }

    echo json_encode($response);
}

