<?php
include_once('include/quiz_header.php');

if (isset($_GET['sub_category']) && $_GET['sub_category'] != "") {
    $sub_category = $_GET['sub_category'];

//    $getboard = $db->selectQuery("select * from study_board");

    $getparent = $db->selectQuery("select * from edu_category where category_name = '$sub_category'");
//    print_r($getboard);die;
}
?>

<section>
    <div class="col-12 bg-primary text-white p-2">
        <div class="container">
            <div class="row d-flex justify-content-around">
                <a href="index.php" class="ml-2 text-white" style="font-size: 6vw;"><b><?php echo $getparent[0]['parent_category']; ?></b><span class="ml-1"><i class="fa fa-edit"></i></span></a>
                <a href="sub-category.php?parent_category=<?php echo $getparent[0]['parent_category'];
;
?>" class="ml-2 text-white" style="font-size: 6vw;"><b><?php echo $sub_category; ?></b><span class="ml-1"><i class="fa fa-edit"></i></span></a>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="col-12 p-2">
        <h5 class="text-center my-2">Select Board</h5>
        <div class="container-fluid">

            <select id="board" name="board" class="form-control">
                <option value="">-select-</option>

                <option value="PSEB">PSEB</option>
                <option value="CBSE">CBSE</option>
                <option value="Rajasthan">Rajasthan</option>
                <option value="Haryana">Haryana</option>

                <?php
//                foreach ($getboard as $boardName) {
                ?>
    <!--<option value="<?php // echo $boardName['board']; ?>"><?php // echo $boardName['board']; ?></option>-->   
                <?php
//                }
                ?>

            </select>   

        </div>

    </div>
</section>


<?php
include_once('include/quiz_footer.php');
?>

<script>
    $(document).ready(function ()
    {
        $("#board").change(function () {
            var sub_category = '<?php echo $sub_category; ?>';
            console.log(sub_category);
            var board = $(this).children("option:selected").val();
            window.location = "sub-cat-selected.php?sub_category=" + sub_category + "&& board=" + board;
        });
    });
</script>