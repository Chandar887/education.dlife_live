<?php
session_start();
$title = "View Contest";
$apage = "viewcontest";
include_once('header.php');

if (isset($_GET['update_id']) && $_GET['update_id'] != "") {
    $contest_id = $_GET['update_id'];

    $updateq = mysqli_query($db->con, "SELECT * FROM `contest_que` where contest_id = '$contest_id'");
}
?>



<div class="container-fluid">
    <div class="row">

        <main role="main" class="col-md-12 pt-3">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fa fa-"></i>Update Contest Questions</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                              <!--   <a class="btn btn-primary ml-1" href="page-userlist.php"><i class="fa fa-list pr-2"></i>Users List</a> -->
                </div>
            </div>
            <div class="col-12 col-md-8 mx-auto">
                <form class="border rounded p-4 submitform" action="controller/quiz_controller.php" method="post">

                    <?php
                    $x = 0;

                    while ($data = mysqli_fetch_array($updateq)) {
                        $x++;
                        $answer = $data['answer'];
                        $json = json_decode($data['suggestions']);
                        ?>
                    <input type="hidden" name="que_id<?php echo $x; ?>" value="<?php echo $data['id']; ?>">
                    
                        <p class="h5 m-0" style="border-bottom:solid 1px gray;">Question <?php echo $x; ?></p>

                        <div class="row">
                            <div class="col-md-8 col-12">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">Question</span>
                                    </div>

                                    <input type="text" name="question<?php echo $x; ?>" class="form-control" value="<?php echo $data['questions']; ?>" required>



                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">Answer</span>
                                    </div>

                                    <select type="text" name="answer<?php echo $x; ?>" class="form-control" required>


                                        <option value="A" <?php echo isset($answer) && $answer == 'A' ? "selected" : '' ?> > <?php echo isset($update_id) && $update_id != '' ? $answer : "A" ?> </option>
                                        <option value="B" <?php echo isset($answer) && $answer == 'B' ? "selected" : '' ?> > <?php echo isset($update_id) && $update_id != '' ? $answer : "B" ?> </option>
                                        <option value="C" <?php echo isset($answer) && $answer == 'C' ? "selected" : '' ?> > <?php echo isset($update_id) && $update_id != '' ? $answer : "C" ?> </option>
                                        <option value="D" <?php echo isset($answer) && $answer == 'D' ? "selected" : '' ?> > <?php echo isset($update_id) && $update_id != '' ? $answer : "D" ?> </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">A</span>
                                    </div>

                                    <input type="text" name="mytext<?php echo $x; ?>[]" class="form-control" value="<?php echo $json['0']; ?>" required>


                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">B</span>
                                    </div>

                                    <input type="text" name="mytext<?php echo $x; ?>[]" class="form-control" value="<?php echo $json['1']; ?>" required>


                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="" required>C</span>
                                    </div>

                                    <input type="text" name="mytext<?php echo $x; ?>[]" class="form-control" value="<?php echo $json['2']; ?>" required>


                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">D</span>
                                    </div>

                                    <input type="text" class="form-control" name="mytext<?php echo $x; ?>[]" value="<?php echo $json['3']; ?>" required>


                                </div>
                            </div>
                        </div>

                        <?php
                    }
                    ?>



                    <div class="form-group">

                        <button type="submit" name="update" value="update_contest_question" class="btn btn-primary btn-block button_submit">Update</button>           

                    </div>
                </form>
            </div>
        </main>

    </div>
</div>


<?php
include_once('footer.php');
?>


