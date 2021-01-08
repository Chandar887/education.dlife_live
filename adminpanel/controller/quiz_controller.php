<?php

session_start();
include_once('../../database.php');
//require_once("../../../database/db.php");
// admin add quiz contest 
if (isset($_POST['submit']) && ($_POST['submit']) == 'add_contest') {
//    echo "<pre>";
//    print_r($_POST);die;
    if (isset($_POST['category_name']) && $_POST['category_name'] != '' AND isset($_POST['quiz_time']) && $_POST['quiz_time'] != '') {

        $contest_name = mysqli_real_escape_string($db->con, $_POST['contest_name']);

        $category_name = mysqli_real_escape_string($db->con, $_POST['category_name']);
        $play_time = mysqli_real_escape_string($db->con, $_POST['play_time']);
        $end_time = mysqli_real_escape_string($db->con, $_POST['end_time']);
        $quiz_time = mysqli_real_escape_string($db->con, $_POST['quiz_time']);
        $type = mysqli_real_escape_string($db->con, $_POST['type']);
        // $video_time = mysqli_real_escape_string($db->con, $_POST['video_time']);
        $amount = mysqli_real_escape_string($db->con, $_POST['amount']);
        $total_member = mysqli_real_escape_string($db->con, $_POST['total_member']);
        $no_of_que = mysqli_real_escape_string($db->con, $_POST['no_of_que']);
        $winning_amount = $total_member * $amount;

//       echo "INSERT INTO `quiz_contest`(contest_name, category_id, play_time, end_time, quiz_time, amount, total_member,status,winning_amount) VALUES ('$contest_name','$category_id','$play_time','$end_time','$quiz_time','$amount','$total_member',0,'$winning_amount')";die;
        // echo "INSERT INTO quiz_contest(contest_name, category_name, play_time, quiz_time, video_url, amount, total_member) VALUES ('$contest_name','$category','$play_time','$quiz_time','$video_url','$amount','$total_member')";die;

        $result = mysqli_query($db->con, "INSERT INTO `quiz_contest`(contest_name, category_name, play_time, end_time, quiz_time, type, amount, total_member, no_of_que, status,winning_amount,checked,winner_id,winner_amount) VALUES ('$contest_name','$category_name','$play_time','$end_time','$quiz_time','$type','$amount','$total_member','$no_of_que',0,'$winning_amount',0,'','')");
        // echo $result;die;
        if ($result) {
            $_SESSION['AddMoreContest'] = 'Contest Added Successfully!';
            header('location:../view_all_contest.php');
        }
    } else {
        $_SESSION['err_msg'] = 'All fields are required!';
        header('location:../add_contest.php');
    }
}



// add contest questions
if (isset($_POST['submit']) && ($_POST['submit']) == 'add_contest_question') {
//       echo "<pre>";
//    print_r($_POST);
//    die;
    $contest_id = mysqli_real_escape_string($db->con, $_POST['contest_id']);
    $category_name = mysqli_real_escape_string($db->con, $_POST['category_name']);

    $i = 1;
    while (isset($_POST["question{$i}"]) && $_POST["question{$i}"] != "") {
        $question_no = mysqli_real_escape_string($db->con, $_POST["question_no{$i}"]);
        $question = mysqli_real_escape_string($db->con, $_POST["question{$i}"]);
        $mytext = json_encode($_POST["mytext{$i}"]);

        $suggestion = mysqli_real_escape_string($db->con, $mytext);
        $answer = mysqli_real_escape_string($db->con, $_POST["answer{$i}"]);

        $con_q = mysqli_query($db->con, "INSERT INTO `contest_que`(`contest_id`, `category_name`, `question_no`, `questions`, `suggestions`, `answer`) VALUES ('$contest_id','$category_name','$question_no','$question','$suggestion','$answer')");
        $i++;
    }

    if ($con_q) {
//        $_SESSION['contest_id'] = $contest_id;
        header("location:../view_contest.php?contest_id=$contest_id");
    }
}



//    update contest by admin
if (isset($_POST['update']) && ($_POST['update']) == 'update_contest') {
    if (isset($_POST['category_name']) && $_POST['category_name'] != '' AND isset($_POST['quiz_time']) && $_POST['quiz_time'] != '') {

        $contest_id = mysqli_real_escape_string($db->con, $_POST['contest_id']);
        $contest_name = mysqli_real_escape_string($db->con, $_POST['contest_name']);

        $category_name = mysqli_real_escape_string($db->con, $_POST['category_name']);
        $play_time = mysqli_real_escape_string($db->con, $_POST['play_time']);
        $end_time = mysqli_real_escape_string($db->con, $_POST['end_time']);
        $quiz_time = mysqli_real_escape_string($db->con, $_POST['quiz_time']);

        $amount = mysqli_real_escape_string($db->con, $_POST['amount']);
        $total_member = mysqli_real_escape_string($db->con, $_POST['total_member']);
        $no_of_que = mysqli_real_escape_string($db->con, $_POST['no_of_que']);
        $winning_amount = $total_member * $amount;

//       echo "INSERT INTO `quiz_contest`(contest_name, category_id, play_time, end_time, quiz_time, amount, total_member,status,winning_amount) VALUES ('$contest_name','$category_id','$play_time','$end_time','$quiz_time','$amount','$total_member',0,'$winning_amount')";die;
        // echo "INSERT INTO quiz_contest(contest_name, category_name, play_time, quiz_time, video_url, amount, total_member) VALUES ('$contest_name','$category','$play_time','$quiz_time','$video_url','$amount','$total_member')";die;

        $result = mysqli_query($db->con, "UPDATE `quiz_contest` SET `contest_name`='$contest_name',`category_name`='$category_name',`play_time`='$play_time',`end_time`='$end_time',`quiz_time`='$quiz_time',`amount`=$amount,`total_member`=$total_member,`no_of_que`='$no_of_que', `winning_amount`='$winning_amount' WHERE id = '$contest_id'");
        // echo $result;die;
        if ($result) {
            $_SESSION['updatecon'] = 'Contest Updated Successfuly!';
            header('location:../view_all_contest.php');
        }
    } else {
        $contest_id = mysqli_real_escape_string($db->con, $_POST['contest_id']);
        $_SESSION['err_msg'] = 'All fields are required!';
        header("location:../add_contest.php?update_id=$contest_id");
    }
}



// update contest questions
if (isset($_POST['update']) && ($_POST['update']) == 'update_contest_question') {



    $i = 1;
    while (isset($_POST["question{$i}"]) && $_POST["question{$i}"] != "") {
        $que_id = mysqli_real_escape_string($db->con, $_POST["que_id{$i}"]);
        $question = mysqli_real_escape_string($db->con, $_POST["question{$i}"]);
        $mytext = json_encode($_POST["mytext{$i}"]);

        $suggestion = mysqli_real_escape_string($db->con, $mytext);
        $answer = mysqli_real_escape_string($db->con, $_POST["answer{$i}"]);

        $con_q = mysqli_query($db->con, "UPDATE `contest_que` SET `questions`='$question',`suggestions`='$suggestion',`answer`='$answer' WHERE id = '$que_id'");
        $i++;
    }

    if ($con_q) {
        $_SESSION['update_ques'] = 'Contest Questions Updated Successfuly!';
        header("location:../view_all_contest.php");
    }
}



//add questions one by one*******************
// add contest questions
if (isset($_POST['submit']) && ($_POST['submit']) == 'add_questions') {
//    echo "<pre>";
//    print_r($_POST);die;
    $category_name = mysqli_real_escape_string($db->con, $_POST['category_name']);

    $question_no = $_POST["question_no"];
    $question = mysqli_real_escape_string($db->con, $_POST["questions"]);
    $mytext = json_encode($_POST["suggestions"]);

    $suggestion = mysqli_real_escape_string($db->con, $mytext);
    $answer = $_POST["answer"];

    $con_q = mysqli_query($db->con, "INSERT INTO `contest_que`(`category_name`, `question_no`, `questions`, `suggestions`, `answer`) VALUES ('$category_name','$question_no','$question','$suggestion','$answer')");


    if ($con_q) {
        $_SESSION['add_more'] = 'Add More Questions...';
        header("location:../add_questions.php");
    }
}



// Update contest questions
if (isset($_REQUEST['update']) && ($_REQUEST['update']) == 'update_questions') {

    $mytext = $_REQUEST['suggestions'];
    $json = json_encode($mytext);
    
    $suggestion = mysqli_real_escape_string($db->con, $json);

    unset($_REQUEST['update']);


    $updatedata = $db->updateData("contest_que", array("category_name" => $_REQUEST['category_name'], "questions" => $_REQUEST['questions'], "suggestions" => $suggestion, "answer" => $_REQUEST['answer']), "id=" . $_REQUEST['question_id']);


    if ($updatedata) {
        $_SESSION['update_question'] = "Question Updated Successfully!";
        header("location:../view_all_questions.php");
    }
}



//model exam paper controller
// add new exam contest
if (isset($_REQUEST['submit']) && ($_REQUEST['submit']) == 'add_exam_contest') {
//    echo "<pre>";
//    print_r($_REQUEST);die;
    unset($_REQUEST['submit']);

    if ($db->insertData("exam_contest", $_REQUEST)) {
        $_SESSION['exam_contest_added'] = 'Exam Contest Added Successfully!';
        header('location:../view_exam_contest.php');
    }
}



// update exam contest
if (isset($_REQUEST['update']) && ($_REQUEST['update']) == 'update_exam_contest') {
//    echo "<pre>";
//    print_r($_REQUEST);die;
    unset($_REQUEST['update']);

    if ($db->updateData("exam_contest", $_REQUEST, "id={$_REQUEST['id']}")) {
        $_SESSION['updateexamcon'] = 'Exam Contest Updated Successfully!';
        header('location:../view_exam_contest.php');
    }
}


//add new exam category
if (isset($_REQUEST['submit']) && ($_REQUEST['submit']) == 'add_exam_category') {
//    echo "<pre>";
//    print_r($_REQUEST);
//    die;
    $_REQUEST['cat_image'] = $db->site . 'uploads/exam_quiz/quiz_back.jpg';
    if (isset($_FILES['cat_image']['tmp_name']) && $_FILES['cat_image']['tmp_name'] != '') {
        $ftemppath = $_FILES['cat_image']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'exam_quiz' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "cat_image", pathinfo($_FILES['cat_image']['name'], PATHINFO_EXTENSION))) {
            $img = $db->site . 'uploads/exam_quiz/' . $filename;
        }
        $_REQUEST['cat_image'] = $img;
    }

    unset($_REQUEST['submit']);

//    echo "<pre>";
//    print_r($_REQUEST);die;


    if ($db->insertData("quiz_category", $_REQUEST)) {
        $_SESSION['cat_added'] = 'New Category Added Successfully!';
        header('location:../add_exam_contest.php');
    }
}


//update exam contest category in quiz category table
if (isset($_REQUEST['update']) && $_REQUEST['update'] == "update_exam_category") {
    $update_id = $_REQUEST['id'];

    $_REQUEST['cat_image'] = $db->site . 'uploads/exam_quiz/quiz_back.jpg';
    if (isset($_FILES['cat_image']['tmp_name']) && $_FILES['cat_image']['tmp_name'] != '') {
        $ftemppath = $_FILES['cat_image']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'exam_quiz' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "cat_image", pathinfo($_FILES['cat_image']['name'], PATHINFO_EXTENSION))) {
            $img = $db->site . 'uploads/exam_quiz/' . $filename;
        }
        $_REQUEST['cat_image'] = $img;
    }

    unset($_REQUEST['update']);

    if ($db->updateData("quiz_category", $_REQUEST, "id=$update_id")) {
        $_SESSION['cat_updated'] = "Category Updated Successfully!";
        header('location:../add_exam_cat.php');
    }
}


if (isset($_REQUEST['submit']) && ($_REQUEST['submit']) == 'add_exam_questions') {
//
//    echo "<pre>";
//    print_r($_FILES);die; 
    $contest_id = $_REQUEST['contest_id'];
    if (isset($_FILES['que_image']['tmp_name']) && $_FILES['que_image']['tmp_name'] != '') {
        $ftemppath = $_FILES['que_image']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'exam_quiz' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "que_image", pathinfo($_FILES['que_image']['name'], PATHINFO_EXTENSION))) {
            echo $img = $db->site . 'uploads/exam_quiz/' . $filename;
        }
        $_REQUEST['que_image'] = $img;
    }

    if (isset($_FILES['suggestions']['tmp_name'][0]) && $_FILES['suggestions']['tmp_name'][0] != '') {
        for ($i = 0; $i < count($_FILES['suggestions']['tmp_name']); $i++) {
            $ftemppath = $_FILES['suggestions']['tmp_name'][$i];
            $sourcepath = $db->root . 'uploads' . $db->slash . 'exam_quiz' . $db->slash;
            if ($filename = $db->fileUpload($ftemppath, $sourcepath, "suggestion" . '_' . $i , pathinfo($_FILES['suggestions']['name'][$i], PATHINFO_EXTENSION))) {
                $images['suggestions'][] = $db->site . "uploads/exam_quiz/" . $filename;
            }
            $img = $images['suggestions'];
        }
        $_REQUEST['suggestions'] = json_encode($img);
    } else {
        $sugg = json_encode($_REQUEST['suggestions']); 
        $_REQUEST['suggestions'] = mysqli_real_escape_string($db->con, $sugg);
    }
    
//    echo "<pre>";
//    print_r($_REQUEST['suggestions']);die;
    

    unset($_REQUEST['change']);
    unset($_REQUEST['submit']);
//    echo "<pre>";
//    print_r($_REQUEST);
//    die;
    if ($db->insertData("contest_que", $_REQUEST)) {
        $_SESSION['add_examque'] = 'Add More Questions...';

        header("location:../add_questions.php?contest_id=$contest_id");
    }
}
?>		

