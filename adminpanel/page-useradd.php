<?php
$title = "Users";
$apage = "users";
?>
<?php include_once("header.php"); ?>
<?php
$data = array("UID" => "", "sponsorUID" => "", "b_leg"=>"", "firstname" => "", "lastname" => "", "email" => "", "contact" => "");
if (isset($_REQUEST)) {
    $post = array();
    foreach ($_REQUEST as $k => $v) {
        if (is_array($v)) {
            foreach ($v as $k2 => $v2) {
                $post[$k][$k2] = mysqli_real_escape_string($db->con, $v2);
            }
        } else {
            $post[$k] = mysqli_real_escape_string($db->con, $v);
        }
    }

    if (isset($post["rid"]) && $post["rid"] != '') {
        if ($data = $db->selectRows("w_users", "", "ID={$post["rid"]}")) {
            $data = $data[0];
        }
    }
}
?>
<main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fa fa-tags"></i> <?php echo $title; ?></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <?php if (isset($post["rid"]) && $post["rid"] != '') { ?>
                <a class="btn btn-primary" href="page-useradd.php"><i class="fa fa-plus pr-2"></i>New User</a>
            <?php } ?>
            <a class="btn btn-primary ml-1" href="page-userlist.php"><i class="fa fa-list pr-2"></i>Users List</a>
        </div>
    </div>
    <div class="col-12 col-md-8 mx-auto">
        <form class="border rounded p-4 submitform" action="controller/controller.php" method="post" enctype="multipart/form-data">
            <p class="h4">Enter User Details</p>
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
            
            <div class="input-group form-group mb-4 sponsor-box">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user-tie pr-1"></i>Sponsor UID</span>
                </div>
                <input type="text" id="sponsorUID" name="sponsorUID" class="form-control" placeholder="Sponsor UID" value="<?php echo $data['sponsorUID']; ?>" <?php echo(isset($post["rid"]) && $post["rid"] != '') != '' ? "disabled" : "" ?> required>
            </div>
            <div class="input-group form-group mb-4 sponsor-box">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-sitemap pr-1"></i>Left/Right</span>
                </div>
                <select id="b_leg" name="b_leg" class="form-control" placeholder="Leg (Left/Right)" value="<?php echo $data['b_leg']; ?>" <?php echo(isset($post["rid"]) && $post["rid"] != '') != '' ? "disabled" : "" ?> required>
                    <option value="" <?php echo $data['b_leg']===""?"Selected":""; ?>>- Select Leg -</option>
                    <option value="0" <?php echo $data['b_leg']==="0"?"Selected":""; ?>>Left</option>
                    <option value="1" <?php echo $data['b_leg']==="1"?"Selected":""; ?>>Right</option>
                </select>
            </div>
            <div class="input-group form-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" id="firstname" name="firstname" class="form-control" placeholder="First Name *" value="<?php echo $data['firstname']; ?>" required>
                <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Last Name" value="<?php echo $data['lastname']; ?>">
            </div>
            <div class="input-group form-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email *" value="<?php echo $data['email']; ?>" required>
            </div>

            <div class="input-group form-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-mobile"></i></span>
                </div>
                <input type="text" id="contact" name="contact" class="form-control" placeholder="Contact *" value="<?php echo $data['contact']; ?>" required>
            </div>

            <div class="input-group form-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input type="password" id="password" name="password" class="form-control" placeholder="password *" <?php echo (!isset($post['rid'])) ? "required" : "" ?>>
            </div>
            <div class="input-group form-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input type="password" id="cpassword" name="cpassword" class="form-control" placeholder="confirm password *" <?php echo (!isset($post['rid'])) ? "required" : "" ?>>
            </div>
            <?php
            if (isset($post["rid"]) && $post["rid"] != '') {
                ?>
                <input type="hidden" id="rid" name="rid" value="<?php echo $post["rid"]; ?>">
                <input type="hidden" name="r_link" value="../page-userlist.php" />
                <div class="row">
                    <div class="form-group col-sm-6">
                        <button type="submit" name="submit" value="updateuserbt" class="btn btn-primary btn-block">Update</button>
                    </div>
                    <div class="form-group col-sm-6">
                        <a href="page-userlist.php" class="btn btn-danger btn-block">Cancel</a>
                    </div>
                </div>
            <?php } else { ?>
                <input type="hidden" id="r_link" name="r_link" value="<?php echo $currenturl; ?>">
                <div class="form-group">
                    <button type="submit" name="submit" value="registerbt" class="btn btn-primary btn-block">Register</button>
                </div>
            <?php } ?>
        </form>
    </div>
</main>
<?php include_once("footer.php"); ?>