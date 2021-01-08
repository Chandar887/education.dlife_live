<?php
//$title = "New Question";
//$apage = "addquestion";
include_once('header.php');



if (isset($_SESSION['category_name'])) {
    $category_name = $_SESSION['category_name'];
}

$q = "SELECT * FROM `live_class_data` ORDER BY id DESC";
$result = mysqli_query($db->con, $q);
// print_r($id);
$data = mysqli_fetch_array($result);
?> 


<div class="container-fluid">
    <div class="row">

        <main role="main" class="col-md-12 pt-3">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fa fa-"></i>Add Contest Questions</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                              <!--   <a class="btn btn-primary ml-1" href="page-userlist.php"><i class="fa fa-list pr-2"></i>Users List</a> -->
                </div>
            </div>
            <div class="col-12 col-md-8 mx-auto">

                <?php
//                $catq = mysqli_fetch_array(mysqli_query($db->con, "SELECT * FROM `quiz_category` where id = $category_name"));
//                print_r($catq);die;
                $x = 0;
                ?>
                <div style="display: flex;">
                    <h4 class="add_que ">Max Questions : <?php echo $data['no_of_que']; ?></h4>
                    <h4 class="add_que ml-auto">Category : <?php echo $category_name; ?></h4>
                </div>



                <form class="border rounded p-4 submitform" action="controller/edu_controller.php" method="post">


                    <div class="form-group">
                        <input type="hidden" name="live_class_id" value="<?php echo $data['id']; ?>">
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="category_name" value="<?php echo $category_name; ?>">                            
                    </div>

                    <?php
                    $datt = $data['no_of_que'];

                    for ($i = 1; $i <= $datt; $i++) {
                        $x++;
                        ?>
                        <p class="h5 m-0" style="border-bottom:solid 1px gray">Question <?php echo $i; ?></p>



                        <div class="form-group">
                            <input type="hidden" name="question_no<?php echo $i; ?>" value="<?php echo rand(1000, 9999); ?>">                            
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-12">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">Question</span>
                                    </div>

                                    <input type="text" name="question<?php echo $x; ?>" class="form-control" value="" required>
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

                                    <input type="text" name="mytext<?php echo $i; ?>[]" class="form-control" value="" required>


                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">B</span>
                                    </div>

                                    <input type="text" name="mytext<?php echo $i; ?>[]" class="form-control" value="" required>


                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="" required>C</span>
                                    </div>

                                    <input type="text" name="mytext<?php echo $i; ?>[]" class="form-control" value="" required>


                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">D</span>
                                    </div>

                                    <input type="text" class="form-control" name="mytext<?php echo $i; ?>[]" value="" required>


                                </div>
                            </div>
                        </div>

                        <?php
                    }
                    ?>



                    <div class="form-group">

                        <button type="submit" name="submit" value="add_liveclass_question" class="btn btn-primary btn-block button_submit">Submit</button>           

                    </div>
                </form>
            </div>
        </main>

    </div>
</div>


<?php
include_once('footer.php');


if (isset($_SESSION['quizq'])) {
    unset($_SESSION['quizq']);
}
?>



