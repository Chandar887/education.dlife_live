<?php
include_once('header.php');

if (isset($_GET['contest_id']) && $_GET['contest_id'] != "") {
    $contest_id = $_GET['contest_id'];
}

$contstq = mysqli_query($db->con, "SELECT * FROM `quiz_contest` where id = '$contest_id'");
?>
<div class="container-fluid">
    <main role="main" class="col-md-12 pt-3">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h2 class="h2"><i class="fa fa-trophy pr-2"></i> Contests Details</h2>
            <div class="btn-toolbar mb-2 mb-md-0">
<!--                    <form method='get' action='<?php echo $currenturl; ?>' class="form-inline mr-4">
                    <div class="input-group mr-1">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class='fa fa-search'></i></div>
                        </div>
                        <select class="form-control" id="category" name='category'>
                            <option value=''>All Categories</option>
                <?php
                foreach ($categories as $catid => $catname) {
                    $selected = $request['category'] == $catid ? "selected" : "";
                    echo "<option value='$catid' $selected>$catname</option>";
                }
                ?>
                        </select>
                        <input type="search" class="form-control" id="q" name='q' value='<?php echo $q; ?>' placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>-->
            </div>
        </div>
        <?php
        if (isset($request['contest_id']) && $cData = $db->selectRow("quiz_contest", "", "id = '{$request['contest_id']}'")) {
            $cData['category'] = "";
            if ($ddata = $db->selectRow("quiz_category", "category_name", "id='{$cData['category_id']}'")) {
                $cData['category'] = $ddata['category_name'];
            }
            ?>
            <div class="row">
                <div class="col-md-4 col-12">
                    <h5 class="h3"><?php echo $cData['contest_name']; ?></h5>
                    <h6 class="h5 mb-2 text-muted"><?php echo $cData['category']; ?></h6>
                    <div class="list-group">
                        <div class="list-group-item flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="mb-1">Start Time</div>
                                <small><?php echo $cData['play_time']; ?></small>
                            </div>
                        </div>
                        <div class="list-group-item flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="mb-1">End Time</div>
                                <small><?php echo $cData['end_time']; ?></small>
                            </div>
                        </div>
                        <div class="list-group-item flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="mb-1">Duration</div>
                                <small><?php echo $cData['quiz_time']; ?> Minute</small>
                            </div>
                        </div>
                        <div class="list-group-item flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="mb-1">Max Members</div>
                                <small><?php echo $cData['total_member']; ?></small>
                            </div>
                        </div>
                        <div class="list-group-item flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="mb-1">Total Questions</div>
                                <small><?php echo $cData['no_of_que']; ?></small>
                            </div>
                        </div>
                        <div class="list-group-item flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="mb-1">Join Amount</div>
                                <small>₹ <?php echo $cData['amount']; ?></small>
                            </div>
                        </div>

                        <?php
                        if ($cData['winner_amount'] != "") {
                            ?>
                            <div class="list-group-item flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <div class="mb-1">Winning Amount</div>
                                    <small>₹ <?php echo $cData['winner_amount']; ?></small>
                                </div>
                            </div>
                            <?php }
                            ?>

                        </div>
                    </div>
                    <div class="col-md-8 col-12">

                        <?php
//      $created_at = date("Y-m-d H:i:s");
// echo "SELECT * FROM `quiz_score` WHERE contest_id = '$contest_id' AND created_at = '$created_at' ORDER BY quiz_score DESC,complete_time ASC";die;

                        $rank_q = mysqli_query($db->con, "SELECT * FROM `quiz_play_detail` WHERE contest_id = '$contest_id' ORDER BY score DESC,complete_time ASC");
//                    $quiz_data = mysqli_fetch_array($rank_q);
//                    print_r($quiz_data);die;
                        if (mysqli_num_rows($rank_q) > 0) {
                            ?>
                            <table class="table table-sm table-hover">
                                <thead>
                                <th>Rank</th>                  
                                <th>Username</th>
                                <th>Score</th>
                                <!--<th>Time</th>-->
                                <th>Played Time</th>
                                <th class="text-right">Action</th>
                                </thead>
                                <?php
                                $x = 0;
                                while ($quiz_data = mysqli_fetch_array($rank_q)) {

                                    $x++;

                                    $user_id = $quiz_data['user_id'];

                                    $userq = mysqli_query($db->con, "select * from w_users where ID = '$user_id'");
                                    $user_data = mysqli_fetch_array($userq);
                                    ?>

                                    <tbody>
                                        <tr>
                                            <td><?php echo $x; ?></td>
                                            <td><?php echo $user_data['uName']; ?></td>
                                            <td><?php echo ($quiz_data['score']) ? $quiz_data['score'] : '0'; ?></td>
                                            <!--<td><?php echo $quiz_data['complete_time']; ?></td>-->
                                            <td><?php echo $quiz_data['created_at']; ?></td>
                                            <!--<td><a href="#" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>-->
                                            <td class="text-right"><a href="view_quiz_details.php?user_id=<?php echo $user_id; ?>&&contest_id=<?php echo $contest_id; ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a></td>

                                        </tr>   
                                    </tbody>
                                    <?php
                                }
                            } else {                                ?>

                                <div class="mt-5">
                                    <div class="alert alert-danger">
                                        Not Played By Users Yet!
                                    </div>
                                </div>
                            <?php }
                            ?>

                        </table>
                    </div>
                </div>
            <?php } else {
                ?>
                <div class="m-4 p-5">
                    <div class="alert alert-danger">
                        <strong>Missing!</strong> Record not Found.
                    </div>
                </div>
            <?php } ?>



    </div>
    </div>
    </div>

    </main>   
    </div>

    <?php
    if (isset($_SESSION['update_que'])) {
        unset($_SESSION['update_que']);
    }


    include_once('footer.php');
    ?>

