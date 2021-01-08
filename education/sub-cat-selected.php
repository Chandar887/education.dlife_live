<?php
include_once('include/quiz_header.php');

if (isset($_GET['sub_category'])) {
    $sub_category = $_GET['sub_category'];
    if (isset($_GET['board'])) {
        $board = $_GET['board'];
    }
//    echo $sub_category;
    $categoryData = $db->selectQuery("select * from edu_category where category_name = '$sub_category'");
}
?>

<section>
    <div class="col-12 bg-primary text-white p-2">
        <div class="container">
            <div class="row d-flex justify-content-around">
                <a href="index.php" class="ml-2 text-white" style="font-size: 6vw;"><b><?php echo $categoryData[0]['parent_category']; ?></b><span class="ml-1"><i class="fa fa-edit"></i></span></a>
                <a href="sub-category.php?parent_category=<?php echo $categoryData[0]['parent_category']; ?>" class="ml-2 text-white" style="font-size: 6vw;"><b><?php echo $sub_category; ?></b><span class="ml-1"><i class="fa fa-edit"></i></span></a>
            </div>
        </div>

    </div>
</section>

<!-- <div class="col-4 my-2 mx-auto bg-primary rounded-pill"> -->

<!-- </div> -->

<section>
    <div class="col-12">
        <div class="container-fluid">
            <?php
            if (isset($board)) {
                if ($subjectData = $db->selectQuery("select DISTINCT subject from study_material where sub_category = '$sub_category' and board = '$board'")) {
                    ?>
                    <h5 class="text-center mt-3 p-2 rounded bg-light" style="border-left: 10px solid #007bff;"><b>SUBJECTS</b></h5>
                    <div class="row mt-3">
                        <?php
                        $x = 0;
                        foreach ($subjectData as $subName) {
                            $x++;
                            ?>
                            <div class="col-6 subCategory <?php echo ($x > 2) ? 'mt-3' : ''; ?>" sub-cat="<?php echo $sub_category; ?>" subject-name="<?php echo $subName['subject']; ?>">
                                <div class="p-2 border bg-primary rounded text-center d-flex justify-content-around">
                                    <h6 class="my-auto text-white"><?php echo $subName['subject']; ?></h6>  
                                </div>
                            </div>


                            <?php
                        }
                    } else {
                        ?>
                        <h5 class="text-center text-danger mt-3">No Subjects Available!</h5> 
                        <?php
                    }
                } else {
                    if ($subjectData = $db->selectQuery("select * from study_material where sub_category = '$sub_category'")) {
                        ?>
                        <h5 class="text-center pt-2"><b>SUBJECTS</b></h5>
                        <div class="row mt-3">
                            <?php
                            foreach ($subjectData as $subName) {
                                ?>
                                <div class="col-5 mx-auto border bg-primary rounded-pill p-2 text-center d-flex justify-content-around subCategory" sub-cat="<?php echo $sub_category; ?>" subject-name="<?php echo $subName['subject']; ?>">
                                    <h6 class="my-auto text-white"><?php echo $subName['subject']; ?></h6>  
                                </div> 
                                <?php
                            }
                        } else {
                            ?>
                            <h5 class="text-center text-danger mt-3">No Subjects Available!</h5> 
                            <?php
                        }
                    }
                    ?> 



                </div>
            </div>

        </div>
    </div>
</section>


<?php
include_once('include/quiz_footer.php');
?>


<script type="text/javascript">
    $(document).ready(function ()
    {
        $('body').on('click', '.subCategory', function ()
        {

            var ele = $(this);  
            var sub_category = ele.attr('sub-cat');
            var subject_name = ele.attr('subject-name');
            var board = '<?php echo $board; ?>';
//            if (board == "undefined")
//            {
//                window.location = "study_type.php?sub_category=" + sub_category + "& subject_name=" + subject_name;
//            } else {
                window.location = "study_type.php?sub_category=" + sub_category + "& subject_name=" + subject_name + "& board=" + board;
//            }
//                    window.location = ;

        });
    });
</script>


<!--//if (isset($board)) {
//    
                var board = '//p echo $board; ?>';
    
//} else {
//    
                var board = "undefined";
    //
//}
//-->