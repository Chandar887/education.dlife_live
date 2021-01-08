<?php
$title = "Change Password";
$apage = "change_password";
?>
<?php include_once("header.php"); ?>
<?php
$condtion = array();
if (count($condtion) > 0)
    $condtion = 'where ' . implode(" and ", $condtion);
else
    $condtion = '';

//////////
/* Array for Tree */
?>
<main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h2 class="h2"><i class="fa fa-key"></i> Change Password</h2>
    </div>

    <div class="row">
        <div class="col-md-6 col-12 mx-auto">
            <div class="p-4 border">
                <form action="controller/controller.php" method="post" enctype="multipart/form-data" id="form-category">
                    <input type="hidden" name="r_link" value="<?php echo $currenturl; ?>">
                    <?php
                    if (isset($_SESSION['status'])) {
                        $msg = '';
                        $css_class = '';
                        if ($_SESSION['status'] == 0) {
                            $css_class = 'alert-danger';
                            $msg = '<strong>Error!</strong> Something Went Wrong';
                        } else if ($_SESSION['status'] == 1) {
                            $css_class = 'alert-success';
                            $msg = '<strong>Success!</strong> Data Saved Successfuly';
                        } else if ($_SESSION['status'] == 2) {
                            $css_class = 'alert-warning';
                            $msg = '<strong>Warning!</strong> New Password and Confirm Passowrd Not Match';
                        } else if ($_SESSION['status'] == 3) {
                            $css_class = 'alert-danger';
                            $msg = '<strong>Error!</strong> Old Password Not Match';
                        }
                        echo'<div class="alert ' . $css_class . ' alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $msg . '</div>';
                        unset($_SESSION['status']);
                    }
                    ?>

                    <div class="form-group">
                        <label for="opass" class="h6">Old Password</label>
                        <input type="password" class="form-control" id="opass" name="opass" required>
                    </div>

                    <div class="form-group">
                        <label for="npass" class="h6">New Password</label>
                        <input type="password" class="form-control" id="npass" name="npass" required>
                    </div>

                    <div class="form-group">
                        <label for="cpass" class="h6">Confirm Password</label>
                        <input type="password" class="form-control" id="cpass" name="cpass" required>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="userID" value="<?php echo $_SESSION['mlmadmin']['ID']; ?>">
                        <button type="submit" class="btn btn-primary btn-block" name="submit" value="updatepassword">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include_once("footer.php");
?>