<?php
$title = "New Question";
$apage = "addquestion";
include_once('header.php');

if (isset($_GET['question_id']) && $_GET['question_id'] != "") {
    $update_id = $_GET['question_id'];
    $getData = $db->selectQuery("select * from live_class_que where id = $update_id");
//    print_r($getData);
//    die;
}
?> 


<div class="container-fluid">
    <div class="row">

        <main role="main" class="col-md-12 pt-3">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fa fa-"></i>Update Question</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                              <!--   <a class="btn btn-primary ml-1" href="page-userlist.php"><i class="fa fa-list pr-2"></i>Users List</a> -->
                </div>
            </div>



            <div class="col-12 col-md-8 mx-auto">
         
                

                <form class="border rounded p-4 submitform" action="controller/edu_controller.php" method="post">
                  
<!--                     <input type="hidden" name="live_class_id" value="<?php echo $getData[0]['live_class_id']; ?>">
                      <input type="hidden" name="category_name" value="<?php echo $getData[0]['category_name']; ?>">-->
                    
                    <p class="m-0 mt-2 mb-1" style="border-bottom:solid 1px gray">Question</p>
                    <div class="form-group">
                       
                            <input type="hidden" name="id" value="<?php echo $getData[0]['id']; ?>">
                           
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="">Question</span>
                                </div>

                                <input type="text" name="questions" class="form-control" value="<?php echo isset($update_id) ? $getData[0]['questions'] : ''; ?>" required>

                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="">Answer</span>
                                </div>

                                <select type="text" name="answer" class="form-control" required>
                                    <option value="A" <?php echo isset($update_id) && $getData[0]['answer'] == 'A' ? "selected" : '' ?> >A</option>
                                    <option value="B" <?php echo isset($update_id) && $getData[0]['answer'] == 'B' ? "selected" : '' ?> >B</option>
                                    <option value="C" <?php echo isset($update_id) && $getData[0]['answer'] == 'C' ? "selected" : '' ?> >C</option>
                                    <option value="D" <?php echo isset($update_id) && $getData[0]['answer'] == 'D' ? "selected" : '' ?> >D</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php
                    
                        $sugg = $getData[0]['suggestions'];
                        $suggestion = json_decode($sugg, true);
                   
                    ?>

                    <div class="row">
                        <div class="col-6 col-md-3">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="">A</span>
                                </div>

                                <input type="text" name="mytext[]" class="form-control" value="<?php echo $suggestion[0]; ?>" required>


                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="">B</span>
                                </div>

                                <input type="text" name="mytext[]" class="form-control" value="<?php echo $suggestion[1]; ?>" required>


                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="" required>C</span>
                                </div>

                                <input type="text" name="mytext[]" class="form-control" value="<?php echo $suggestion[2]; ?>" required>


                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="">D</span>
                                </div>

                                <input type="text" class="form-control" name="mytext[]" value="<?php echo $suggestion[3]; ?>" required>


                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                       
                            <button type="submit" name="update" value="update_live_que" class="btn btn-primary btn-block button_submit">Update</button>   
                           
                    </div>
                </form>


                <!--                **************upload excel spreadsheet**************
                                <form class="border rounded p-4 submitform" action="controller/quiz_controller.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="">A</span>
                                        </div>
                                        <input type="text" name="[" class="form-control" value="<?php echo isset($update_id) ? $suggestion['0'] : ''; ?>" required>
                                    </div>
                                </form>-->
            </div>  
        </main>

        </div>

    </div>
</div>


<?php
include_once('footer.php');


if (isset($_SESSION['quizq']) || isset($_SESSION['add_more'])) {
    unset($_SESSION['quizq']);
    unset($_SESSION['add_more']);
}
?>


