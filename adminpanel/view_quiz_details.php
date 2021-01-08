<?php
include_once('header.php');

if (isset($_GET['user_id']) && isset($_GET['contest_id'])) {
    $user_id = $_GET['user_id'];
    $contest_id = $_GET['contest_id'];

    $d = mysqli_query($db->con, "SELECT * FROM `quiz_play_detail` where user_id = '$user_id' && contest_id = '$contest_id'");


//    $d = $db->selectQuery("SELECT * FROM `quiz_play_detail` where user_id = '$user_id' && contest_id = '$contest_id'");
} else if (isset($_GET['user_id']) && isset($_GET['live_class_id'])) {
    $user_id = $_GET['user_id'];
    $contest_id = $_GET['live_class_id'];

    $d = mysqli_query($db->con, "SELECT * FROM `quiz_play_detail` where user_id = '$user_id' && live_class_id = '$contest_id'");
} else if (isset($_GET['user_id']) && isset($_GET['exam_id'])) {
    $user_id = $_GET['user_id'];
    $contest_id = $_GET['exam_id'];

    $d = mysqli_query($db->con, "SELECT * FROM `quiz_play_detail` where user_id = '$user_id' && exam_id = '$contest_id'");
}
?>
<div class="container-fluid">
    <main role="main" class="col-md-12 pt-3">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h2 class="h2"><i class="fa fa-trophy pr-2"></i> User's Play Details</h2>
        </div>
        <div class="row">
            <?php
            if (mysqli_num_rows($d) > 0) {


                $dt = mysqli_fetch_assoc($d);
                $json = $dt['answer'];
                $jsondecoded = json_decode($json);

                if ($jsondecoded != "") {

                    foreach ($jsondecoded as $key => $data) {
                        if (isset($_GET['user_id']) && isset($_GET['live_class_id'])) {
                            $q = "SELECT * FROM `live_class_que` WHERE id = '$key'";
                        } else {
                            $q = "SELECT * FROM `contest_que` WHERE id = '$key'";
                        }

                        $result = mysqli_query($db->con, $q);


                        if (mysqli_num_rows($result) > 0) {

                            $josn = mysqli_fetch_assoc($result);
                            $realAns = $josn['answer'];
                            $json = $josn['suggestions'];
                            $contestqueDt = json_decode($json);
//                        $test = false;
                            ?>
                            <?php // echo"<br>x=$x ". ($x != 1 ? "d-none" : ""); ?>
                            <div class="col-lg-4 col-md-4 col-sm-12">

                                <div class="card mt-3" style="max-width: 100%; height: auto;">
                                    <h5 class="card-header <?php echo ($realAns == $data) ? 'bg-success' : 'bg-danger'; ?>" style="color: white;">
                                        <?php echo $josn['questions']; ?>
                                    </h5> 
                                    <ul class="list-group list-group-flushs">
                                        <?php
                                        if (isset($josn['que_image']) && $josn['que_image'] != "") {
                                            ?>
                                            <li class="list-group-item"><label>Question Image<br><img src='<?php echo $josn["que_image"]; ?>' style='height:100px!important;'></label>
                                            </li>
                                            <?php
                                        }
                                        ?>

                                        <li class="list-group-item"><label class="<?php echo ($realAns == "A") ? "text-success" : "text-muted"; ?> "><?php echo $data == "A" ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-circle"></i>'; ?><?php
                                                if (filter_var($contestqueDt['0'], FILTER_VALIDATE_URL)) {
                                                    echo "<img src='$contestqueDt[0]' style='height:100px!important;'>";
                                                } else {
                                                    echo $contestqueDt['0'];
                                                }
                                                ?></label>
                                        </li>

                                        <li class="list-group-item"><label class="<?php echo ($realAns == "B") ? "text-success" : "text-muted"; ?> "><?php echo $data == "B" ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-circle"></i>'; ?> <?php
                                                if (filter_var($contestqueDt['1'], FILTER_VALIDATE_URL)) {
                                                    echo "<img src='$contestqueDt[1]' style='height:100px!important;'>";
                                                } else {
                                                    echo $contestqueDt['1'];
                                                }
                                                ?></label></li>

                                        <li class="list-group-item"><label class="<?php echo ($realAns == "C") ? "text-success" : "text-muted"; ?> "><?php echo $data == "C" ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-circle"></i>'; ?> <?php
                                                if (filter_var($contestqueDt['2'], FILTER_VALIDATE_URL)) {
                                                    echo "<img src='$contestqueDt[2]' style='height:100px!important;'>";
                                                } else {
                                                    echo $contestqueDt['2'];
                                                }
                                                ?></label></li>

                                        <li class="list-group-item"><label class="<?php echo ($realAns == "D") ? "text-success" : "text-muted"; ?> "><?php echo $data == "D" ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-circle"></i>'; ?> <?php
                                                if (filter_var($contestqueDt['3'], FILTER_VALIDATE_URL)) {
                                                    echo "<img src='$contestqueDt[3]' style='height:100px!important;'>";
                                                } else {
                                                    echo $contestqueDt['3'];
                                                }
                                                ?></label></li>
                                    </ul>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <div class="m-4 p-5">
                        <div class="alert alert-danger">
                            Questions are not attempted by user.
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="m-4 p-5">
                    <div class="alert alert-danger">
                        <strong>Missing!</strong> Record not Found.
                    </div>
                </div>
                <?php
            }
// echo "quiz contest";
// }
            ?>

        </div>



    </main> 
</div>
<?php
include_once('footer.php');
?>