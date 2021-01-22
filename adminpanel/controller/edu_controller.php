<?php

session_start();
include_once('../../database.php');

//******************add education categories**************
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "add_edu_category") {


    $checkCatName = $db->countRows("edu_category", "category_name='{$_REQUEST['category_name']}' and parent_category='{$_REQUEST['parent_category']}'");
    if ($checkCatName == 0) {
        $checkparent = $db->countRows("edu_category", "parent_category='{$_REQUEST['category_name']}'");
        if ($checkparent == 0) {
            $_REQUEST['cat_image'] = $db->site . 'uploads/edu_quiz/video_thumb.png';
            if (isset($_FILES['cat_image']['tmp_name']) && $_FILES['cat_image']['tmp_name'] != '') {
                $ftemppath = $_FILES['cat_image']['tmp_name'];
                $sourcepath = $db->root . 'uploads' . $db->slash . 'edu_quiz' . $db->slash;
                if ($filename = $db->fileUpload($ftemppath, $sourcepath, "cat_image", pathinfo($_FILES['cat_image']['name'], PATHINFO_EXTENSION))) {
                    $img = $db->site . 'uploads/edu_quiz/' . $filename;
                }
                $_REQUEST['cat_image'] = $img;
            }
//        print_r($_REQUEST['cat_image']);die;
            unset($_REQUEST['submit']);
//    echo "<pre>";
//    print_r($_REQUEST);die;

            if ($db->insertData("edu_category", $_REQUEST)) {
                $_SESSION['cat_inserted'] = "Category Added Successfully!";
                header('location:../add_edu_category.php');
            }
        } else {
            $_SESSION['checkparentt'] = "This category name is already given to a parent category!";
            header('location:../add_edu_category.php');
        }
    } else {
        $_SESSION['checkCat'] = "Category Already Exist!";
        header('location:../add_edu_category.php');
    }
}


////******************add education subjects**************
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "add_subjects") {
//    echo "<pre>";
//    print_r($_REQUEST);
    unset($_REQUEST['submit']);
    if ($db->insertData("edu_subjects", $_REQUEST)) {
        $_SESSION['sub_inserted'] = "Subject Added Successfully!";
        header('location:../add_subjects.php');
    }
}


////******************add education study material**************
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "add_study_material") {
//    echo "<pre>";
//    print_r($_REQUEST);die;

    if (isset($_FILES['ebook']['tmp_name']) && $_FILES['ebook']['tmp_name'] != '') {
        $ftemppath = $_FILES['ebook']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'ebook' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "ebook", pathinfo($_FILES['ebook']['name'], PATHINFO_EXTENSION))) {
            $ebook = $db->site . 'uploads/ebook/' . $filename;
        }
        $_REQUEST['ebook'] = $ebook;
    } else if (isset($_FILES['url']['tmp_name']) && $_FILES['url']['tmp_name'] != '') {
        $ftemppath = $_FILES['url']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'vbook' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "vbook", pathinfo($_FILES['url']['name'], PATHINFO_EXTENSION))) {
            $vbook = $db->site . 'uploads/vbook/' . $filename;
        }
        $_REQUEST['url'] = $vbook;
    }

    unset($_REQUEST['submit']);
//    echo "<pre>";
//    print_r($_FILES['url']);
//    die;
    if ($db->insertData("study_material", $_REQUEST)) {
        $_SESSION['study_added'] = "Study Material Added Successfully!";
        header('location:../view-study-material.php');
    }
}


////******************add education study material**************
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "add_live_class") {
//
// echo "<pre>";
//    print_r($_REQUEST);
//    die;

    if (isset($_FILES['thumbnail']['tmp_name']) && $_FILES['thumbnail']['tmp_name'] != '') {
        $ftemppath = $_FILES['thumbnail']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'live_class' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "live_class", pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION))) {
            $video_thumb = $db->site . 'uploads/live_class/' . $filename;
        }
        $_REQUEST['thumbnail'] = $video_thumb;
    } if (isset($_FILES['url']['tmp_name']) && $_FILES['url']['tmp_name'] != '') {
        $ftemppath = $_FILES['url']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'vbook' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "vbook", pathinfo($_FILES['url']['name'], PATHINFO_EXTENSION))) {
            $url = $db->site . 'uploads/vbook/' . $filename;
        }
        $_REQUEST['url'] = $url;
    }
    unset($_REQUEST['checkstudy']);
    unset($_REQUEST['submit']);
//    
//    echo "<pre>";
//    print_r($_REQUEST);
//    die;

    if ($db->insertData("live_class_data", $_REQUEST)) {
//        $_SESSION['live_class_added'] = "Live Class Added Successfully!";
        $_SESSION['category_name'] = $_REQUEST['category_name'];
        header('location:../live-class-que.php');
    }
}



////******************update education study material**************
if (isset($_REQUEST['update']) && $_REQUEST['update'] == "update_study_material") {

    $update_id = $_REQUEST['id'];
//    echo "<pre>";
//    print_r($_REQUEST);die;

    if (isset($_FILES['ebook']['tmp_name']) && $_FILES['ebook']['tmp_name'] != '') {
        $ftemppath = $_FILES['ebook']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'ebook' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "ebook", pathinfo($_FILES['ebook']['name'], PATHINFO_EXTENSION))) {
            $ebook = $db->site . 'uploads/ebook/' . $filename;
        }
        $_REQUEST['ebook'] = $ebook;
    } else if (isset($_FILES['url']['tmp_name']) && $_FILES['url']['tmp_name'] != '') {
        $ftemppath = $_FILES['url']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'vbook' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "vbook", pathinfo($_FILES['url']['name'], PATHINFO_EXTENSION))) {
            $vbook = $db->site . 'uploads/vbook/' . $filename;
        }
        $_REQUEST['url'] = $vbook;
    }

    unset($_REQUEST['update']);
//    echo "<pre>";
//    print_r($_REQUEST);
//    die;

    if ($db->updateData("study_material", $_REQUEST, "id=$update_id")) {
        $_SESSION['success'] = "Study Material Updated Successfully!";
        header('location:../view-study-material.php');
    }
}



//add live class questions
if (isset($_POST['submit']) && ($_POST['submit']) == 'add_liveclass_question') {
//       echo "<pre>";
//    print_r($_POST);
//    die;
    $live_class_id = mysqli_real_escape_string($db->con, $_POST['live_class_id']);
    $category_name = mysqli_real_escape_string($db->con, $_POST['category_name']);

    $i = 1;
    while (isset($_POST["question{$i}"]) && $_POST["question{$i}"] != "") {
        $question_no = mysqli_real_escape_string($db->con, $_POST["question_no{$i}"]);
        $question = mysqli_real_escape_string($db->con, $_POST["question{$i}"]);
        $mytext = json_encode($_POST["mytext{$i}"]);

        $suggestion = mysqli_real_escape_string($db->con, $mytext);
        $answer = mysqli_real_escape_string($db->con, $_POST["answer{$i}"]);

        $con_q = mysqli_query($db->con, "INSERT INTO `live_class_que`(`live_class_id`, `category_name`, `question_no`, `questions`, `suggestions`, `answer`) VALUES ('$live_class_id','$category_name','$question_no','$question','$suggestion','$answer')");
        $i++;
    }

    if ($con_q) {
        $_SESSION['live_class_added'] = "Live Class Added Successfully!";
        header("location:../add-live-class.php");
    }
}



////******************update live class data**************
if (isset($_REQUEST['update']) && $_REQUEST['update'] == "update_live_class") {

// echo "<pre>";
//    print_r($_REQUEST);
//    die;
    $live_class_id = $_REQUEST['id'];

    if (isset($_FILES['thumbnail']['tmp_name']) && $_FILES['thumbnail']['tmp_name'] != '') {
        $ftemppath = $_FILES['thumbnail']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'live_class' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "live_class", pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION))) {
            $video_thumb = $db->site . 'uploads/live_class/' . $filename;
        }
        $_REQUEST['thumbnail'] = $video_thumb;
    } if (isset($_FILES['url']['tmp_name']) && $_FILES['url']['tmp_name'] != '') {
        $ftemppath = $_FILES['url']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'vbook' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "vbook", pathinfo($_FILES['url']['name'], PATHINFO_EXTENSION))) {
            $url = $db->site . 'uploads/vbook/' . $filename;
        }
        $_REQUEST['url'] = $url;
    }
    unset($_REQUEST['checkstudy']);
    unset($_REQUEST['update']);
//    
//    echo "<pre>";
//    print_r($_REQUEST);
//    die;

    if ($db->updateData("live_class_data", $_REQUEST, "id=$live_class_id")) {
        $_SESSION['live_class_update'] = "Live Class Updated Successfully!";
//        $_SESSION['success'] = $_REQUEST['category_name'];
        header('location:../view-live-classes.php');
    }
}


// Update live class questions
if (isset($_REQUEST['update']) && ($_REQUEST['update']) == 'update_live_que') {

    $mytext = $_REQUEST['mytext'];
    $suggestion = json_encode($mytext);

    unset($_REQUEST['update']);

//        echo "<pre>";
//        print_r($_REQUEST);die;

    $updatedata = $db->updateData("live_class_que", array("questions" => $_REQUEST['questions'], "suggestions" => $suggestion, "answer" => $_REQUEST['answer']), "id=" . $_REQUEST['id']);


    if ($updatedata) {
        $_SESSION['update_question'] = "Question Updated Successfully!";
        header("location:../view-live-classes.php");
    }
}



//******************update education categories**************
if (isset($_REQUEST['update']) && $_REQUEST['update'] == "update_edu_category") {
//    echo "<pre>";
//    print_r($_REQUEST);die;
    $update_id = $_REQUEST['id'];

    $getCatdt = $db->selectQuery("select * from edu_category where id = '$update_id'");
    $catName = $getCatdt[0]['category_name'];

    if ($_REQUEST['category_name'] != $_REQUEST['old_cat']) {
        if ($db->countRows("edu_category", "category_name='{$_REQUEST['category_name']}' and parent_category = '{$_REQUEST['parent_category']}'")) {
            $_SESSION['cat_exist'] = "Category Already Exists!";
            header('location: ../add_edu_category.php?update_cat=' . $update_id);
            die;
        }
    }

    $db->updateData("edu_category", array("parent_category" => $_REQUEST['category_name']), "parent_category='{$_REQUEST['old_cat']}'");
//    $db->updateData("edu_category", array("parent_category"=>$_REQUEST['category_name']), "parent_category={$_REQUEST['old_cat']}");


    $_REQUEST['cat_image'] = $db->site . 'uploads/edu_quiz/video_thumb.png';
    if (isset($_FILES['cat_image']['tmp_name']) && $_FILES['cat_image']['tmp_name'] != '') {
        $ftemppath = $_FILES['cat_image']['tmp_name'];
        $sourcepath = $db->root . 'uploads' . $db->slash . 'edu_quiz' . $db->slash;
        if ($filename = $db->fileUpload($ftemppath, $sourcepath, "cat_image", pathinfo($_FILES['cat_image']['name'], PATHINFO_EXTENSION))) {
            $img = $db->site . 'uploads/edu_quiz/' . $filename;
        }
        $_REQUEST['cat_image'] = $img;
    }
//        print_r($_REQUEST['cat_image']);die;
    unset($_REQUEST['update']);
    unset($_REQUEST['old_cat']);
//    echo "<pre>";
//    print_r($_REQUEST);die;

    if ($db->updateData("edu_category", $_REQUEST, "id=$update_id")) {
//        $getsubcats=$db->selectQuery("select * from edu_category where ");
//        update category in study material
        if ($_REQUEST['parent_category'] == "") {
            $db->updateData("study_material", array("parent_category" => $_REQUEST['category_name']), "parent_category='$catName'");
        } else {
            $db->updateData("study_material", array("sub_category" => $_REQUEST['category_name']), "sub_category='$catName' and parent_category = '{$_REQUEST['parent_category']}'");
        }

        $_SESSION['cat_updated'] = "Category Updated Successfully!";
        header('location:../add_edu_category.php');
    }
}



//activate course for user
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "activate_course") {

    unset($_REQUEST['submit']);
    $curntdate = date("y-m-d");
    // echo $db->deleteData("w_active_courses", "expiryTime < $curntdate");die;
    
    foreach($_REQUEST['active_courses'] as $courceID){
        $courceID;
        $db->deleteData("w_active_courses", "active_courses=$courceID and uID={$_REQUEST['uID']}");
        $expTime = $_REQUEST['expiryTime'][$courceID];//expirte time
        $activeCourse = array("uID" => $_REQUEST['uID'], "active_courses" => $courceID, "expiryTime" => $expTime);
//        echo "<pre>";
//        print_r($activeCourse);
        $db->insertData("w_active_courses", $activeCourse);
    }
   
    $_SESSION['activate_course'] = "Courses Activated Successfully!";
    header('location:../page-userlist.php');
}


//activate class 6th To 12th for user
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "activate_classes") {
//    echo "<pre>";
//    print_r($_REQUEST);
//    die;
    unset($_REQUEST['submit']);
    
    foreach($_REQUEST['active_courses'] as $courceID){
        $courceID;//
        $db->deleteData("w_active_courses", "active_courses=$courceID and uID={$_REQUEST['uID']}");
        $expTime = $_REQUEST['expiryTime'][$courceID];//expirte time
        $activeCourse = array("uID" => $_REQUEST['uID'], "active_courses" => $courceID, "expiryTime" => $expTime);
//        echo "<pre>";
//        print_r($activeCourse);
        $db->insertData("w_active_courses", $activeCourse);
    }
   
    $_SESSION['activate_course'] = "Courses Activated Successfully!";
    header('location:../page-userlist.php');
}
?>		

