<?php
$title = "Slider Setup";
$apage = "slider";
?>
<?php include_once("header.php"); ?>

<main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fa fa-tags"></i> <?php echo $title; ?></h1>
        <!--        <div class="btn-toolbar mb-2 mb-md-0">
        <?php if (isset($request["rid"]) && $request["rid"] != '') { ?>
                                                                        <a class="btn btn-primary" href="page-useradd.php"><i class="fa fa-plus pr-2"></i>New User</a>
        <?php } ?>
                    <a class="btn btn-primary ml-1" href="page-userlist.php"><i class="fa fa-list pr-2"></i>Users List</a>
                </div>-->
    </div>
   
    
    <div class="row">
        <!--select type of slider-->
        
        
        <div class="col-12 col-md-6">
            <form class="border rounded p-4 submitform" action="controller/controller.php" method="post" enctype="multipart/form-data">
                <p class="h4">Slider</p>
                <?php
                if (isset($_SESSION['status'])) {
                    //echo $_SESSION['status'];
                    $msg = '';
                    $css_class = '';
                    if ($_SESSION['status'] == -1) {
                        $css_class = 'alert-success';
                        $msg = '<strong>Success!</strong> Enter All Required Values';
                    } else if ($_SESSION['status'] == 0) {
                        $css_class = 'alert-danger';
                        $msg = '<strong>Error!</strong> Something Went Wrong';
                    } else if ($_SESSION['status'] == 1) {
                        $css_class = 'alert-success';
                        $msg = '<strong>Success!</strong> Data Saved Successfuly';
                    } else if ($_SESSION['status'] == 2) {
                        $css_class = 'alert-warning';
                        $msg = '<strong>Warning!</strong> Data Already Exist';
                    } else if ($_SESSION['status'] == 3) {
                        $css_class = 'alert-warning';
                        $msg = '<strong>Warning!</strong> Password Not Match';
                    } else if ($_SESSION['status'] == 4) {
                        $css_class = 'alert-warning';
                        $msg = '<strong>Warning!</strong> Sponsor UID Not Found';
                    }
                    echo'<div class="alert ' . $css_class . ' alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $msg . '</div>';
                    unset($_SESSION['status']);
                }
                ?>
                <label for="type">Slider Type</label>
               
                <select class="form-control mb-4" name="type" id="type" required>
                    <option value="">- Type -</option>
                    <option value="large">Home</option>
                    <option value="small">Ludo</option>
                    <option value="education">Online Classes</option>
                    <option value="quizpanel">Quizpanel</option>
<!--                    <option value="large">Large (1:2)</option>
                    <option value="small">Small (1:4)</option>-->
                </select>
                <div class="form-group mb-4">
                    <label for="img1">Image 1</label>
                    <input type="file" class="form-control" id="img1" name="img1">
                </div>
                <div class="form-group mb-4">
                    <label for="img2">Image 2</label>
                    <input type="file" class="form-control" id="img2" name="img2">
                </div>
                <div class="form-group mb-4">
                    <label for="img3">Image 2</label>
                    <input type="file" class="form-control" id="img3" name="img3">
                </div>
                <div class="form-group mb-4">
                    <label for="img4">Image 4</label>
                    <input type="file" class="form-control" id="img4" name="img4">
                </div>
                
               
                
                <input type="hidden" id="r_link" name="r_link" value="<?php echo $currenturl; ?>">
                <div class="form-group">
                    <button type="submit" name="submit" value="slider" class="btn btn-primary btn-block">Save</button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-6">
            <?php
            $sliderData = array();
            if ($data = $db->selectRows("w_slider_images"))
                $sliderData = $data;
            ?>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Image1</th>
                        <th>Image2</th>
                        <th>Image3</th>
                        <th>Image4</th>
                    </tr>
                </thead>
                <tbody id="tabledata">
                    <?php
                    foreach ($sliderData as $d) {
                        ?>
                        <tr id="datarow-<?php echo $d['ID'] ?>">
                            <td class='align-middle'><?php if($d['type']=='large'){echo 'Home';}else if($d['type']=='small'){echo 'Ludo';}else{echo $d['type']; } ?></td>
                            <?php for ($i = 1; $i < 5; $i++) { ?>
                                <td class='align-middle'><?php echo $d["img$i"] != '' ? "<a target='_blank' href='{$db->site}{$d["img$i"]}'><img src='{$db->site}{$d["img$i"]}' width='100%'/></a>" : ""; ?></td>
                            <?php } ?>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include_once("footer.php"); ?>