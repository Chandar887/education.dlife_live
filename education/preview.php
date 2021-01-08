<?php
include_once("include/quiz_header.php");

if (isset($_GET['live_class_id'])) {
    $id = $_GET['live_class_id'];

    $liveData = $db->selectQuery("select * from live_class_data where id = '$id'");
//    print_r($liveData);die;
} 
?>

<section>
    <div class="col-12 mt-4">
        <?php
        if (isset($_GET['live_class_id'])) {
            foreach ($liveData as $data) {

                if ($data['url'] != "") {
                    ?>
                    <!--<a href="preview.php"></a>-->
                    <video width="100%" height="100%" controls>
                        <source src="<?php echo $data['url']; ?>" type="video/mp4"> 
                    </video>
                    <?php
                } else if ($data['embed_link'] != "") {
                    ?>
                    <div>
                       <?php echo $data['embed_link']; ?>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</section>

<?php
include_once("include/quiz_footer.php");
?>
<!--<script>PDFObject.embed("<?php echo $studyData[0]['ebook']; ?>", "#showpdf",{forceIframe:true});</script>-->

<script>
    $(document).ready(function ()
    {
        $('iframe').css("width", "100%");
    });
</script>

<!--&modestbranding=1&autohide=1&showinfo=0&controls=0-->