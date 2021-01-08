<?php
$title = "Add Live Class";
$apage = "addliveclass";
include_once('header.php');


if(isset($_GET['live_class_id']) && $_GET['live_class_id']!="")
{
    $live_class_id = $_GET['live_class_id'];
    
    $updateLiveClass = $db->selectQuery("select * from live_class_data where id = '$live_class_id'");
}


?>
<div class="container-fluid">
    <main role="main" class="col-md-12 pt-3">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">

            <h1 class="h2"><i class="fa fa-"></i><?php echo isset($live_class_id) ? 'Update' : 'Add'; ?> Live Class</h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <a class="btn btn-primary ml-1" href="view-live-classes.php"><i class="fa fa-list pr-2"></i>Live Classes List</a> 
            </div>
        </div>



        <div class="row">

            <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                <?php
                if (isset($_SESSION['live_class_added'])) {
                    ?>
                    <p class="alert alert-success"><?php echo $_SESSION['live_class_added']; ?></p>
                <?php }
                ?>



                <form class="border rounded p-4 submitform" action="controller/edu_controller.php" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-2">
                        <label for="Category Name">Category Name</label>
                        <select class="form-control" name="category_name"  required="">
                            <option value="">Select Category</option>
                            <?php
                            $get_parent_cat = $db->selectQuery("SELECT DISTINCT category_name FROM `edu_category`");

                            foreach ($get_parent_cat as $parent_cat_name) {
                                if ($parent_cat_name['category_name'] != "") {
                                    ?>
                            <option value="<?php echo $parent_cat_name['category_name']; ?>" <?php echo isset($updateLiveClass[0]['category_name']) && $updateLiveClass[0]['category_name'] == $parent_cat_name['category_name'] ? 'selected' : $parent_cat_name['category_name']; ; ?>><?php echo $parent_cat_name['category_name']; ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select> 
                    </div>

                    <div class="form-group mb-2">
                        <label for="Title">Title<span class="required">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?php echo isset($updateLiveClass[0]['title']) ? $updateLiveClass[0]['title'] : ''; ?>"  required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="Description">Description<span class="required">*</span></label>
                        <textarea name="description" rows="4" class="form-control" cols="50" required><?php echo isset($updateLiveClass[0]['description']) ? $updateLiveClass[0]['description'] : ''; ?></textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label for="start_time">Video Start TIme<span class="required">*</span></label>
                        <input type="datetime-local" name="start_time" class="form-control" value=""  required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="end_time">Quiz Start Time<span class="required">*</span></label>
                        <input type="datetime-local" name="end_time" class="form-control" value="" required>
                    </div>

<!--                    <div class="form-group mb-2">
                        <label for="embeded"><input type="radio" id="embeded" name="checkstudy" class="ml-2"  value="vbook" <?php echo isset($updateLiveClass[0]['embed_link'])&& $updateLiveClass[0]['embed_link']!="" ? 'checked' : ''; ?> required><span class="ml-1">Embeded</span></label>
                        <label for="V-Book"><input type="radio" id="vbook" name="checkstudy" class="ml-2" value="vbook" <?php echo isset($updateLiveClass[0]['url'])&& $updateLiveClass[0]['url']!="" ? 'checked' : ''; ?> required ><span class="ml-1">Video URL </span></label>
                    </div>-->


                    <div class="form-group mb-2 showembed">
                        <label for="embed_link">Video Embed Link</label>
                        <textarea name="embed_link" rows="4" class="form-control" cols="50" ><?php echo isset($updateLiveClass[0]['embed_link']) ? $updateLiveClass[0]['embed_link'] : ''; ?></textarea>
                    </div>
                    
<!--                     <div class="form-group mb-2 showvbook" style="<?php echo isset($updateLiveClass[0]['url']) ? 'display: block' : 'display: none'; ?>">
                        <label for="Video URL">Upload Video</label>
                        <input type="file" name="url" class="form-control" value="<?php echo isset($updateLiveClass[0]['url']) ? $updateLiveClass[0]['url'] : ''; ?>">
                    </div>-->


                    <div class="form-group mb-2">
                        <label for="Thumbnail">Video Poster</label>
                        <input type="file" name="thumbnail" class="form-control" value="<?php echo isset($updateLiveClass[0]['thumbnail']) ? $updateLiveClass[0]['thumbnail'] : ''; ?>" required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="Amount">Video Amount<span class="required">*</span></label>
                        <input type="number" name="amount" class="form-control" value="<?php echo isset($updateLiveClass[0]['amount']) ? $updateLiveClass[0]['amount'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group mb-2">
                        <label for="Quiz Time">Quiz Time(In Seconds)<span class="required">*</span></label>
                        <input type="text" name="quiz_time" class="form-control" value="<?php echo isset($updateLiveClass[0]['quiz_time']) ? $updateLiveClass[0]['quiz_time'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group mb-2">
                        <label for="Total Questions">Total Questions<span class="required">*</span></label>
                        <input type="number" name="no_of_que" class="form-control" value="<?php echo isset($updateLiveClass[0]['no_of_que']) ? $updateLiveClass[0]['no_of_que'] : ''; ?>" required>
                    </div>
                    
                    <?php
                       if(isset($live_class_id)){
                           ?>
                    <input type="hidden" name="id" class="form-control" value="<?php echo $live_class_id; ?>" required>
                    <?php
                       } 
                    ?>

                    <div class="form-group mt-3">    
                        <button type="submit" name="<?php echo isset($live_class_id) ? 'update' : 'submit'; ?>" value="<?php echo isset($live_class_id) ? 'update_live_class' : 'add_live_class'; ?>" class="btn btn-primary btn-block button_submit"><?php echo isset($live_class_id) ? 'Update' : 'Submit'; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php
if (isset($_SESSION['live_class_added'])) {
    unset($_SESSION['live_class_added']);
}

include_once('footer.php');
?>

<script>
    $(document).ready(function () {

        $('#embeded').click(function ()
        {
            $('.showvbook').hide();
            $('.showembed').css("display", "block");
        });

        $('#vbook').click(function ()
        {
            $('.showembed').hide();
            $('.showvbook').css("display", "block");
        });
    });
</script>