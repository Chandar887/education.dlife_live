<?php
$title = "Activate course";
$apage = "activatecourse";
include_once("header.php");
//print_r($_SESSION['f_admin']['ID']);
$uData = array("uMobile" => "", "review" => "");
if (isset($request["uid"]) && $request["uid"] != '') {
    if ($userData = $db->selectRows("w_users", "", "ID={$request["uid"]}")) {
        $uData = $userData[0];
    }
}
?>
<main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fa fa-"></i> <?php echo $title; ?></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <?php if (isset($request["rid"]) && $request["rid"] != '') { ?>
                                                                                                                                                                                                                                                    <!--<a class="btn btn-primary" href="page-useradd.php"><i class="fa fa-plus pr-2"></i>New User</a>-->
            <?php } ?>
            <!--<a class="btn btn-primary ml-1" href="page-userlist.php"><i class="fa fa-list pr-2"></i>Users List</a>-->
        </div>
    </div>
    <div>
        <h4 class="alert alert-primary text-center"><span class="float-left">#<?php echo $request["uid"]; ?></span> <?php echo $uData['uName']; ?></h4>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <form class="border rounded p-4 submitform" action="controller/edu_controller.php" method="post" enctype="multipart/form-data">
                <!--<p class="h4">Activate</p>-->

                <input type="hidden" name="uID" value="<?php echo $request['uid']; ?>">

                <div style="display:flex;"><h4>Select Courses</h4> <h4 style="margin-left:200px;">Expiry Date</h4></div><br>

                <?php
//            echo "<pre>";
//            print_r($check);die;
                $getcourses = $db->selectQuery("select * from edu_category where parent_category=''");
                $active_courses = array();
                if ($check = $db->selectQuery("select * from w_active_courses where uID={$request['uid']}")) {
                    foreach ($check as $ccat)
                        $active_courses[$ccat['active_courses']] = $ccat;
                }
                foreach ($getcourses as $course) {
                    if ($course['category_name'] != '6th To 12th') {
                        $x = $course['id'];
                        ?>
                        <div style="display:flex;">
                            <div class="form-check mt-3 col-3">
                                <input type="checkbox" <?php echo (in_array($x, array_keys($active_courses))) ? 'checked' : ''; ?> class="form-check-input" name="active_courses[<?php echo $x; ?>]" id="materialUnchecked<?php echo $x; ?>" value="<?php echo $x; ?>">
                                <label class="form-check-label" for="materialUnchecked<?php echo $x; ?>"><?php echo ($course['category_name'] == '6th To 12th') ? 'subcategories' : $course['category_name']; ?></label>
                            </div>

                            <div class="form-check my-2 col-9">
                                <input type="date" name="expiryTime[<?php echo $x; ?>]" class="form-control" value="<?php echo (in_array($x, array_keys($active_courses))) ? $active_courses[$x]['expiryTime'] : ''; ?>">
                            </div>
                        </div>  
                        <?php
                    }
                }
                ?>

                <div class="form-group">
                    <button type="submit" name="submit" value="activate_course" class="btn btn-primary btn-block button_submit">Activate</button>
                </div>
            </form>
        </div>


<!--parent caregory 6th to 12th-->
        <div class="col-12 col-md-6">
            <form class="border rounded p-4 submitform" action="controller/edu_controller.php" method="post" enctype="multipart/form-data">
                <!--<p class="h4">Activate</p>-->

                <input type="hidden" name="uID" value="<?php echo $request['uid']; ?>">

                <div style="display:flex;"><h4>Select Courses</h4> <h4 style="margin-left:200px;">Expiry Date</h4></div><br>

                <?php
//            echo "<pre>";
//            print_r($check);die;
                $getclasses = $db->selectQuery("select * from edu_category where parent_category='6th To 12th'");
                $active_classes = array();
                if ($checkcls = $db->selectQuery("select * from w_active_courses where uID={$request['uid']}")) {
                    foreach ($checkcls as $ccatt)
                        $active_classes[$ccatt['active_courses']] = $ccatt;
                }
                foreach ($getclasses as $class) {
                    $x = $class['id'];
                    ?>
                    <div style="display:flex;">
                        <div class="form-check mt-3 col-3">
                            <input type="checkbox" <?php echo (in_array($x, array_keys($active_classes))) ? 'checked' : ''; ?> class="form-check-input" name="active_courses[<?php echo $x; ?>]" id="materialUnchecked<?php echo $x; ?>" value="<?php echo $x; ?>">
                            <label class="form-check-label" for="materialUnchecked<?php echo $x; ?>"><?php echo $class['category_name']; ?></label>
                        </div>

                        <div class="form-check my-2 col-9">
                            <input type="date" name="expiryTime[<?php echo $x; ?>]" class="form-control" value="<?php echo (in_array($x, array_keys($active_classes))) ? $active_classes[$x]['expiryTime'] : ''; ?>">
                        </div>
                    </div>  
                    <?php
                }
                ?>

                <div class="form-group">
                    <button type="submit" name="submit" value="activate_classes" class="btn btn-primary btn-block button_submit">Activate</button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include_once("footer.php"); ?>

