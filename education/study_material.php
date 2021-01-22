<?php
include_once("include/quiz_header.php");

$studyMaterialData = array();
$limit = 5;
$offset = 0;
$page = 1;
$totaldata = 0;
if (isset($_REQUEST['page']) && $_REQUEST['page'] > 1) {
    $page = $_REQUEST['page'];
    $offset = $limit * ($page - 1);
}

if (isset($_GET['sub_category']) && $_GET['subject_name'] && $_GET['type']) {
    $sub_category = $_GET['sub_category'];
    $subject_name = $_GET['subject_name'];

    //   *************** check type of study material
    if ($_GET['type'] == "ebook") {
        $type = $_GET['type'];
    } else if ($_GET['type'] == "vbook") {
        $type = $_GET['type'];
    }

    if (isset($_GET['parent'])) {
        $parent = $_GET['parent'];
        if ($parent == '6th To 12th') {
            $studyMaterialData = $db->selectQuery("select * from study_material where sub_category = '$sub_category' and study_type = '$type' and subject = '$subject_name' and parent_category = '$parent' ORDER BY id DESC LIMIT $limit OFFSET $offset");
            $totaldata = $db->countRows('study_material', "sub_category = '$sub_category' and study_type = '$type' and subject = '$subject_name' and parent_category = '$parent'");
        } else {
            $studyMaterialData = $db->selectQuery("select * from study_material where sub_category = '$sub_category' and study_type = '$type' and parent_category = '$parent' ORDER BY id DESC LIMIT $limit OFFSET $offset");
            $totaldata = $db->countRows('study_material', "sub_category = '$sub_category' and study_type = '$type' and parent_category = '$parent'");
        }
    }
}

?>

<style>
    body {
        background: url('img/educationpanel/body-back.jpg');
        background-size: cover;
    }
</style>

<section>
    <div class="col-12 bg-primary text-white p-2">
        <div class="container">
            <div class="row d-flex justify-content-around">
                <a href="index.php" class="ml-2 text-white loading" style="font-size:6vw;"><b><?php echo $parent; ?></b><span class="ml-1"><i class="fa fa-edit"></i></span></a>
                <!--<h5 class=""><?php echo $sub_category; ?><a href="study_type.php?sub_category=<?php echo $sub_category; ?> && subject_name=<?php echo $subject_name; ?> && board=<?php echo ($studyMaterialData[0]['board']) ? $studyMaterialData[0]['board'] : 'PSEB'; ?>" class="ml-2 text-white"><i class="fa fa-edit"></i></a></h5>-->
            </div>
        </div>
    </div>
</section>

<section>
    <div class="col-12">
        <div class="content">

            <?php
            //            get data of study material**************************

            foreach ($studyMaterialData as $subData) {
                if ($subData['study_type'] == 'ebook' && $subData['ebook'] != "") {
            ?>
                    <div class="card mt-4" style="width: 100%;">
                        <a href="https://docs.google.com/gview?embedded=true&url=<?php echo $subData['ebook']; ?>" class="loading" target="">
                            <div class="card-body text-center openPdf">
                                <img src="img/educationpanel/pdfimage.jpg" width="100vw" height="100vw">
                            </div>
                        </a>
                        <div class="card-footer text-center"><?php echo ($subData['subject'] == '') ? $subData['sub_category'] : $subData['subject']; ?> - Lesson <?php echo $subData['lesson']; ?></div>
                    </div>
                <?php
                } else if ($subData['study_type'] == 'vbook' && ($subData['url'] || $subData['embed_link']) != "") {
                ?>
                    <div class="card mt-4" style="width: 100%;">
                        <div class="card-body text-center">

                            <?php
                            if ($subData['url'] != "") {
                            ?>
                                <!--<a href="preview.php"></a>-->
                                <video width="100%" height="100%" controls>
                                    <source src="<?php echo $subData['url']; ?>" type="video/mp4">
                                </video>
                            <?php
                            } else if ($subData['embed_link'] != "") {
                            ?>
                                <div>
                                    <?php echo $subData['embed_link']; ?>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="card-footer text-center"><?php echo ($subData['subject'] == '') ? $subData['sub_category'] : $subData['subject']; ?> - Lesson <?php echo $subData['lesson']; ?></div>
                    </div>
            <?php
                }
            }
            ?>
        </div>

        <!-- pagination -->

        <div class="mt-3 showpages">
            <?php
            pagination($totaldata, $limit, $offset, $page, $currenturl);
            ?>
        </div>

</section>
<?php
include_once("include/quiz_footer.php");
?>

<script>
    $(document).ready(function() {
        $('.showpages').removeClass('d-none');
        if ($('.content *').length === 0) {
            $('.showpages').addClass('d-none');
            $('.content').append('<div class="my-3"><p class="alert text-center" style="color: white; background-color: #e03c3c;;border-color: #e03c3c;">No Content Available!</p></div>');
        }

        $('iframe').css("width", "100%");

    });
</script>