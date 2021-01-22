<?php
include_once('include/quiz_header.php');

if (isset($_GET['sub_category']) && $_GET['subject_name']) {
    $sub_category = $_GET['sub_category'];
    $subject_name = $_GET['subject_name'];

    if (isset($_GET['board'])) {
        $board = $_GET['board'];
        $studyTypeData = $db->selectQuery("select * from study_material where sub_category = '$sub_category' and subject = '$subject_name' and board = '$board'");
    } else {
        $studyTypeData = $db->selectQuery("select * from study_material where sub_category = '$sub_category' and subject = '$subject_name'");
    }

    //    $parentcattt = $studyTypeData[0]['parent_category']; 

    $parentCat = $db->selectQuery("select * from edu_category where category_name = '$sub_category'");
    //    print_r($parentCat);die;
    //    print_r($studyTypeData);die;
} else if (isset($_GET['subjct_name']) && $_GET['parent_category']) {
    $subject_name = $_GET['subjct_name'];
    $parent_category = $_GET['parent_category'];
    $parentCat = $db->selectQuery("select * from edu_category where category_name = '$subject_name' and parent_category = '$parent_category'");
    //    $parent = $parentCat[0]['parent_category'];
    $studyTypeData = $db->selectQuery("select * from study_material where parent_category = '$parent_category' and sub_category = '$subject_name' and subject = '$subject_name'");
}
?>

<section>
    <div class="col-12 bg-primary text-white p-2">
        <div class="container">
            <div class="row d-flex justify-content-around">
                <a href="index.php" style="font-size: 6vw;" class="ml-2 text-white loading"><b><?php echo isset($parent_category) ? $parent_category : $parentCat[0]['parent_category']; ?></b><span class="ml-1"><i class="fa fa-edit"></i></span></a>
                <?php
                if (!isset($_GET['subjct_name'])) {
                ?>
                    <a href="sub-cat-selected.php?sub_category=<?php echo $sub_category; ?>&board=<?php echo $board; ?>" class="ml-2 text-white" style="font-size: 6vw;"><b><?php echo $sub_category; ?></b><span class="ml-1"><i class="fa fa-edit"></i></span></a>
                <?php
                }
                ?>
            </div>

        </div>

    </div>
</section>
<div class="col-12">
    <div class="rounded mt-3 bg-light" style="border-left: 10px solid #007bff;">
        <h5 class="text-center my-auto p-1 text-uppercase"><b><?php echo $subject_name; ?></b></h5>
    </div>

</div>

<section>
    <div class="col-12 mt-3">
        <div class="text-white  ebook rounded" ebook="ebook" style="background-color: #007bff;
             outline: solid 8px #005bbd;
             outline-offset: -15px;">

            <h4 class="text-center p-4"><span style="font-size: 20vw;"><i class="fa fa-book"></i></span><br> E-Book</h4>
        </div>
        <div class="text-white vbook rounded" vbook="vbook" style="background-color: #007bff;
             outline: solid 8px #005bbd;
             outline-offset: -15px;">

            <h4 class="text-center p-4"><span style="font-size: 20vw;"><i class="fa fa-play-circle"></i></span><br>V-Book</h4>
        </div>
    </div>


</section>

<?php
include_once('include/quiz_footer.php');
?>


<script type="text/javascript">
    $(document).ready(function() {
        <?php
        if (!isset($_GET['subjct_name'])) {
        ?>
            var sub_category = '<?php echo $sub_category; ?>';
            var parent = '<?php echo $parentCat[0]['parent_category']; ?>';
            //            console.log(parent);
        <?php
        } else {
        ?>
            var parent = '<?php echo $parent_category; ?>';
        <?php
        }
        ?>

        var subject_name = '<?php echo $subject_name; ?>';
        $('.ebook').click(function() {
            var ele = $(this);
            var type = ele.attr('ebook');
            <?php
            if (!isset($_GET['subjct_name'])) {
            ?>
                $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
                window.location = "study_material.php?sub_category=" + sub_category + "& subject_name=" + subject_name + "& type=" + type + "& parent=" + parent;
            <?php
            } else {
            ?>
                $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
                window.location = "study_material.php?sub_category=" + subject_name + "& subject_name=" + subject_name + "& type=" + type + "& parent=" + parent;
            <?php
            }
            ?>

        });


        $('.vbook').click(function() {
            var ele = $(this);
            var type = ele.attr('vbook');

            <?php
            if (!isset($_GET['subjct_name'])) {
            ?>
                $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
                window.location = "study_material.php?sub_category=" + sub_category + "& subject_name=" + subject_name + "& type=" + type + "& parent=" + parent;
            <?php
            } else {
            ?>
                $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
                window.location = "study_material.php?sub_category=" + subject_name + "& subject_name=" + subject_name + "& type=" + type + "& parent=" + parent;
            <?php
            }
            ?>

        });
    });
</script>