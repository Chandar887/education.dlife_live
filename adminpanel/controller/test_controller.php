<?php

session_start();
include_once('../../database.php');


////******************add practice test**************
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "add_practice_test") {
//    echo "<pre>";
//    print_r($_REQUEST);die;

    if ($db->countRows("test_category", "cat_sku='{$_REQUEST['cat_sku']}' and cat_parent='{$_REQUEST['cat_parent']}'")) {
        $_SESSION['checksku'] = "Category Already Exists!";
        header('location:../test_add_category.php');
    } else {
        unset($_REQUEST['submit']);

        $_REQUEST['cat_img'] = $db->site . 'uploads/practice_test/practice.jpeg';
        if (isset($_FILES['cat_img']['tmp_name']) && $_FILES['cat_img']['tmp_name'] != '') {
            $ftemppath = $_FILES['cat_img']['tmp_name'];
            $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
            if ($filename = $db->fileUpload($ftemppath, $sourcepath, "cat_img", pathinfo($_FILES['cat_img']['name'], PATHINFO_EXTENSION))) {
                $img = $db->site . 'uploads/practice_test/' . $filename;
            }
            $_REQUEST['cat_img'] = $img;
        }


        if ($db->insertData("test_category", $_REQUEST)) {
            $_SESSION['cat_inserted'] = "Category Added Successfully!";
            header('location:../test_add_category.php');
        }
    }
}


//******************add new practice test**************
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "addtest") {

 
    $_REQUEST['cat_que'] = json_encode($_REQUEST['cat_que']);
    
    unset($_REQUEST['submit']);
    if ($db->insertData("practice_test", $_REQUEST)) {
        $_SESSION['test_created'] = "Test Created Successfully!";
        header('location:../add_new_test.php');
    }
}



//test add questions
if (isset($_REQUEST['submit']) && ($_REQUEST['submit']) == 'test_add_questions') {
    
//    echo "<pre>";
//    print_r($_REQUEST);die;
//    
    $i = 1;
    $submitdata = array();
    $submitdata['cat_1'] = $_REQUEST['cat_1'];
    $submitdata['cat_2'] = $_REQUEST['cat_2'];
    $submitdata['cat_3'] = $_REQUEST['cat_3'];
    $submitdata['cat_4'] = $_REQUEST['cat_4'];


    foreach ($_REQUEST['questions'] as $question) {
        $submitdata['questions'] = $question['questions'];
        $submitdata['answer'] = $question['answer'];
        $submitdata['suggestions'] = isset($question['suggestions']) ? $question['suggestions'] : array();
//        $_REQUEST['que_image'] = isset($question['que_image']) ? $question['que_image'] : array();

        /* Qustion Image */
        
        if (isset($_FILES["questions"]['name'][$i]['que_image'])) {
            $file = $_FILES["questions"];
            $ftemppath = $file['tmp_name'][$i]['que_image'];
            $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
            if ($filename = $db->fileUpload($ftemppath, $sourcepath, "que_image", pathinfo($file['name'][$i]['que_image'], PATHINFO_EXTENSION))) {
                $img = $db->site . 'uploads/practice_test/' . $filename;
            }
            $submitdata['que_image'] = $img;
        }    
        

        /* Suggestion Images */
        if (isset($_FILES["questions"]['name'][$i]['suggestions']['A'])) {
            $file = $_FILES["questions"];
            $ftemppath = $file['tmp_name'][$i]['suggestions']['A'];
            $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
            if ($filename = $db->fileUpload($ftemppath, $sourcepath, "que_image_A", pathinfo($file['name'][$i]['suggestions']['A'], PATHINFO_EXTENSION))) {
                $img = $db->site . 'uploads/practice_test/' . $filename;
            }
            $submitdata['suggestions']['A'] = $img;
        }

        if (isset($_FILES["questions"]['name'][$i]['suggestions']['B'])) {
            $file = $_FILES["questions"];
            $ftemppath = $file['tmp_name'][$i]['suggestions']['B'];
            $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
            if ($filename = $db->fileUpload($ftemppath, $sourcepath, "que_image_B", pathinfo($file['name'][$i]['suggestions']['B'], PATHINFO_EXTENSION))) {
                $img = $db->site . 'uploads/practice_test/' . $filename;
            }
            $submitdata['suggestions']['B'] = $img;
        }

        if (isset($_FILES["questions"]['name'][$i]['suggestions']['C'])) {
            $file = $_FILES["questions"];
            $ftemppath = $file['tmp_name'][$i]['suggestions']['C'];
            $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
            if ($filename = $db->fileUpload($ftemppath, $sourcepath, "que_image_C", pathinfo($file['name'][$i]['suggestions']['C'], PATHINFO_EXTENSION))) {
                $img = $db->site . 'uploads/practice_test/' . $filename;
            }
            $submitdata['suggestions']['C'] = $img;
        }

        if (isset($_FILES["questions"]['name'][$i]['suggestions']['D'])) {
            $file = $_FILES["questions"];
            $ftemppath = $file['tmp_name'][$i]['suggestions']['D'];
            $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
            if ($filename = $db->fileUpload($ftemppath, $sourcepath, "que_image_D", pathinfo($file['name'][$i]['suggestions']['D'], PATHINFO_EXTENSION))) {
                $img = $db->site . 'uploads/practice_test/' . $filename;
            }
            $submitdata['suggestions']['D'] = $img;
        }   

//        echo "<pre>";
//        print_r($submitdata);
//        die;

        $submitdata['suggestions'] = json_encode($submitdata['suggestions']);
//            echo "<pre>";
//            print_r($submitdata);
//            die;
        if ($db->insertData("test_questions", $submitdata)) {
            echo "SUCCESS";
        }
        $i++;
    }

    $_SESSION['que_added'] = 'Questions Added Successfully!';
    header("location:../test_view_questions.php");
}



//    update test question
if (isset($_REQUEST['update']) && ($_REQUEST['update']) == 'update_test_que') {

//    $_REQUEST['suggestions'] = isset($question['suggestions']) ? $question['suggestions'] : array();

    if (isset($_FILES['que_image']['tmp_name']) && $_FILES['que_image']['tmp_name'] != '') {
        $ftemppath = $_FILES['que_image']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "que_image", pathinfo($_FILES['que_image']['name'], PATHINFO_EXTENSION))) {
            $img = $db->site . 'uploads/practice_test/' . $filename;
        }
        $_REQUEST['que_image'] = $img;
    }

    if (isset($_FILES["suggestions"]['name']['A'])) {
        $file = $_FILES["suggestions"];
        $ftemppath = $file['tmp_name']['A'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "suggestions_A", pathinfo($ftemppath, PATHINFO_EXTENSION))) {
            $img = $db->site . 'uploads/practice_test/' . $filename;
        }
        $_REQUEST['suggestions']['A'] = $img;
    }
    
    if (isset($_FILES["suggestions"]['name']['B'])) {
        $file = $_FILES["suggestions"];
        $ftemppath = $file['tmp_name']['B'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "suggestions_B", pathinfo($ftemppath, PATHINFO_EXTENSION))) {
            $img = $db->site . 'uploads/practice_test/' . $filename;
        }
        $_REQUEST['suggestions']['B'] = $img;
    }
    
    if (isset($_FILES["suggestions"]['name']['C'])) {
        $file = $_FILES["suggestions"];
        $ftemppath = $file['tmp_name']['C'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "suggestions_C", pathinfo($ftemppath, PATHINFO_EXTENSION))) {
            $img = $db->site . 'uploads/practice_test/' . $filename;
        }
        $_REQUEST['suggestions']['C'] = $img;
    }
    
    if (isset($_FILES["suggestions"]['name']['D'])) {
        $file = $_FILES["suggestions"];
        $ftemppath = $file['tmp_name']['D'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'practice_test' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "suggestions_D", pathinfo($ftemppath, PATHINFO_EXTENSION))) {
            $img = $db->site . 'uploads/practice_test/' . $filename;
        }
        $_REQUEST['suggestions']['D'] = $img;
    }


    $_REQUEST['suggestions'] = json_encode($_REQUEST['suggestions']);
    $que_id = $_REQUEST['ID'];

    unset($_REQUEST['update']);
    if ($db->updateData("test_questions", $_REQUEST, "ID=$que_id")) {
        $_SESSION['que_updated'] = "Question Updated Successfully!";
        header('location:../test_view_questions.php');
    }
}
?>
