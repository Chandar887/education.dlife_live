<?php
$title = "Users";
$apage = "users";
?>
<?php include_once("header.php"); ?>
<?php
$data = array("UID" => "", "uSponsor" => "", "b_leg" => "", "firstname" => "", "lastname" => "", "email" => "", "contact" => "", "img" => "", "address" => "", "city" => "", "state" => "", "country" => "", "postcode" => "", "uRole" => "user");
$bank = array("bank_name" => "", "bank_ifsc" => "", "account_name" => "", "account_number" => "", "pan_card" => "", "upi" => "");
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
        if ($datad = $db->selectRows("w_users", "", "ID={$post["rid"]}")) {
            $data = $datad[0];
        }
        if ($bankd = $db->selectRows("w_users_bank", "", "uID={$post["rid"]}")) {
            $bank = $bankd[0];
        }
    }
}
?>
<main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fa fa-"></i> <?php echo $title; ?></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
         <!--<a class="btn btn-primary" href="page-useradd.php"><i class="fa fa-plus pr-2"></i>New User</a>-->
        </div>
    </div>
    <div class="col-12 mx-auto">

        <?php
        if (isset($_SESSION['status'])) {
            //echo $_SESSION['status'];
            $msg = '';
            $css_class = '';
            if ($_SESSION['status'] == -1) {
                $css_class = 'alert-danger';
                $msg = '<strong>Required!</strong> Enter All Required Values';
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
                $msg = '<strong>Warning!</strong>Old Password Not Match';
            }
            echo'<div class="alert ' . $css_class . ' alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $msg . '</div>';
            unset($_SESSION['status']);
        }
        ?>
        <div class="row">
            <div class="col-md-10 col-12 mx-md-auto">
                <div class="row">
                    <div class="col-md-6 col-12 mb-4">
                        <div class="card">
                            <h5 class="card-header">User Details</h5>
                            <div class="card-body">
                                <form class="submitform" action="controller/controller.php" method="post" enctype="multipart/form-data" id="form-user">
                                    <div class="form-group sponsor-box">
                                        <label for="sponsorUID">Sponsor UID</label>
                                        <input type="text" id="sponsorUID" name="uSponsor" class="form-control" placeholder="Sponsor UID" value="<?php echo $data['uSponsor']; ?>" <?php echo(isset($post["rid"]) && $post["rid"] != '') != '' ? "disabled" : "" ?> required>
                                    </div>
                                    <!--                            <div class="form-group sponsor-box">
                                                                    <label for="b_leg">Leg (Left/Right)</label>
                                                                    <select id="b_leg" name="b_leg" class="form-control" placeholder="Leg (Left/Right)" value="<?php echo $data['b_leg']; ?>" <?php echo(isset($post["rid"]) && $post["rid"] != '') != '' ? "disabled" : "" ?> required>
                                                                        <option value="" <?php echo $data['b_leg'] === "" ? "Selected" : ""; ?>>- Select Leg -</option>
                                                                        <option value="0" <?php echo $data['b_leg'] === "0" ? "Selected" : ""; ?>>Left</option>
                                                                        <option value="1" <?php echo $data['b_leg'] === "1" ? "Selected" : ""; ?>>Right</option>
                                                                    </select>
                                                                </div>-->
                                    <div class="form-group">
                                        <label for="b_leg">Name</label>
                                        <input type="text" id="firstname" name="uName" class="form-control" placeholder="Name *" value="<?php echo $data['uName']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="uEmail" class="form-control" placeholder="Email *" value="<?php echo $data['uEmail']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="contact">Contact</label>
                                        <input type="text" id="contact" name="uMobile" class="form-control" placeholder="Contact *" value="<?php echo $data['uMobile']; ?>" required>
                                    </div>

                                    <!--                            <div class="form-group">
                                                                    <label for="address">Address</label>
                                                                    <textarea id="contact" name="address" class="form-control" placeholder="Address"><?php echo $data['address']; ?></textarea>
                                                                </div>
                                    
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6 pr-1">
                                                                            <label for="city">City</label>
                                                                            <input type="text" id="city" name="city" class="form-control" placeholder="City" value="<?php echo $data['city']; ?>">
                                                                        </div>
                                                                        <div class="col-md-6 pl-1">
                                                                            <label for="state">State</label>
                                                                            <input type="text" id="state" name="state" class="form-control" placeholder="State" value="<?php echo $data['state']; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                    
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6 pr-1">
                                                                            <label for="city">Country</label>
                                                                            <input type="text" id="country" name="country" class="form-control" placeholder="Country" value="<?php echo $data['country']; ?>">
                                                                        </div>
                                                                        <div class="col-md-6 pl-1">
                                                                            <label for="postcode">Postcode</label>
                                                                            <input type="text" id="postcode" name="postcode" class="form-control" placeholder="State" value="<?php echo $data['postcode']; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                    <!--                            <div class="form-group">
                                                                    <label for="img">User Image</label>
                                                                    <div class="row">
                                    <?php if (isset($data["uImg"]) && $data["uImg"] != '') { ?>
                                                                                    <div class="col-md-2"><img src="<?php echo $db->site . $data["uImg"]; ?>" width="32px" alt=""/></div>
                                    <?php } ?>
                                                                        <div class="<?php echo isset($data["uImg"]) && $data["uImg"] != '' ? "col-md-10" : "col-md-12"; ?>">
                                                                            <input type="file" class="form-control" id="img" name="uImg" placeholder="User Image">
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                    <?php if (isset($post['rid']) && $post['rid'] != '') { ?>
                                        <input type="hidden" id="rid" name="rid" value="<?php echo $post["rid"]; ?>">
                                        <input type="hidden" name="r_link" value="../user-profile.php?rid=<?php echo $post['rid']; ?>" />
                                    <?php } else { ?>
                                        <input type="hidden" name="r_link" value="<?php echo $currenturl; ?>" />
                                    <?php } ?>

                                    <div class="form-group">
                                        <button type="submit" name="submit" value="update_user_info" class="btn btn-primary btn-block">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--            <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <h5 class="card-header">Bank Details</h5>
                                        <div class="card-body">
                                            <form class="submitform" action="controller/controller.php" method="post" enctype="multipart/form-data" id="form-bank">
                                                <div class="form-group">
                                                    <label for="bank_ifsc">Bank IFSC Code</label>
                                                    <input type="text" name="bank_ifsc" class="form-control" placeholder="Bank IFSC Code *" value="<?php echo $bank['bank_ifsc']; ?>" required>
                                                </div>
                                                <div class="form-group sponsor-box">
                                                    <label for="bank_name">Bank Name</label>
                                                    <input type="text" name="bank_name" class="form-control" placeholder="Bank Name *" value="<?php echo $bank['bank_name']; ?>" required>
                                                </div>
                    
                                                <div class="form-group">
                                                    <label for="account_name">Account Holder Name</label>
                                                    <input type="text" name="account_name" class="form-control" placeholder="Account Name *" value="<?php echo $bank['account_name']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="account_number">Account Number</label>
                                                    <input type="text" name="account_number" class="form-control" placeholder="Account Number *" value="<?php echo $bank['account_number']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="pan_card">Pan Number</label>
                                                    <input type="text" name="pan_card" class="form-control" placeholder="Pan Number *" value="<?php echo $bank['pan_card']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="upi">UPI ID</label>
                                                    <input type="text" id="contact" name="upi" class="form-control" placeholder="UPI ID" value="<?php echo $bank['upi']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="pan_card_img">Select Pan Card</label>
                                                    <div class="row">
                    <?php if (isset($bank["pan_card_img"]) && $bank["pan_card_img"] != '') { ?>
                                                                    <div class="col-md-2"><img src="<?php echo $db->site . $bank["pan_card_img"]; ?>" width="32px" alt=""/></div>
                    <?php } ?>
                                                        <div class="<?php echo isset($bank["pan_card_img"]) && $bank["pan_card_img"] != '' ? "col-md-10" : "col-md-12"; ?>">
                                                            <input type="file" class="form-control" id="pan_card_img" name="pan_card_img" placeholder="Pancard Image">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cancel_cheque_img">Select Cancel Cheque</label>
                                                    <div class="row">
                    <?php if (isset($bank["cancel_cheque_img"]) && $bank["cancel_cheque_img"] != '') { ?>
                                                                    <div class="col-md-2"><img src="<?php echo $db->site . $bank["cancel_cheque_img"]; ?>" width="32px" alt=""/></div>
                    <?php } ?>
                                                        <div class="<?php echo isset($bank["cancel_cheque_img"]) && $bank["cancel_cheque_img"] != '' ? "col-md-10" : "col-md-12"; ?>">
                                                            <input type="file" class="form-control" id="cancel_cheque_img" name="cancel_cheque_img" placeholder="Cancel Cheque">
                                                        </div>
                                                    </div>
                                                </div>
                    
                    
                    <?php if (isset($post['rid']) && $post['rid'] != '') { ?>
                                                            <input type="hidden" name="uID" value="<?php echo $post["rid"]; ?>">
                                                            <input type="hidden" name="r_link" value="../user-profile.php?rid=<?php echo $post['rid']; ?>" />
                    <?php } else { ?>
                                                            <input type="hidden" name="r_link" value="<?php echo $currenturl; ?>" />
                    <?php } ?>
                    
                                                <div class="form-group">
                                                    <button type="submit" name="submit" value="savebankbt" class="btn btn-primary btn-block">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>-->
                    <div class="col-md-6 col-12 mb-4">
                        <!--                <div class="card">
                                            <h5 class="card-header">Change Transaction Password</h5>
                                            <div class="card-body">
                                                <form class="submitform" action="controller/controller.php" method="post" enctype="multipart/form-data" id="form-tpassword">
                                                    <div class="form-group">
                                                        <label for="otpass" class="h6">Login Password</label>
                                                        <input type="password" class="form-control" id="otpass" name="opass" required>
                                                    </div>
                        
                                                    <div class="form-group">
                                                        <label for="ntpass" class="h6">New Transaction Password</label>
                                                        <input type="password" class="form-control" id="ntpass" name="npass" required>
                                                    </div>
                        
                                                    <div class="form-group">
                                                        <label for="ctpass" class="h6">Confirm Transaction Password</label>
                                                        <input type="password" class="form-control" id="ctpass" name="cpass" required>
                                                    </div>
                        
                                                    <div class="form-group">
                        <?php if (isset($post['rid']) && $post['rid'] != '') { ?>
                                                                    <input type="hidden" name="userID" value="<?php echo $post["rid"]; ?>">
                                                                    <input type="hidden" name="r_link" value="../user-profile.php?rid=<?php echo $post['rid']; ?>" />
                        <?php } else { ?>
                                                                    <input type="hidden" name="r_link" value="<?php echo $currenturl; ?>" />
                        <?php } ?>
                        
                                                        <button type="submit" class="btn btn-primary btn-block" name="submit" value="user_updatetpassword">Update Password</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>-->
                        <!--<div class="mx-2">&nbsp;</div>-->
                        <?php if ($data['uSponsor'] == 1) { ?>
                            <div class="card mb-3">
                                <h5 class="card-header">Change Role</h5>
                                <div class="card-body">
                                    <form class="submitform" action="controller/controller.php" method="post" enctype="multipart/form-data" id="form-password">
                                        <div class="form-group">
                                            <label for="npass" class="h6">Role</label>
                                            <select class="form-control" id="npass" name="uRole" required>
                                                <option value="user" <?php echo $data['uRole'] == "user" ? "selected" : ""; ?>>User</option>
                                                <option value="franchise" <?php echo $data['uRole'] == "franchise" ? "selected" : ""; ?>>Franchise</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="uID" value="<?php echo $post["rid"]; ?>">
                                            <input type="hidden" name="r_link" value="../user-profile.php?rid=<?php echo $post['rid']; ?>" />
                                            <button type="submit" class="btn btn-primary btn-block" name="submit" value="user_updaterole">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="card">
                            <h5 class="card-header">Change Password</h5>
                            <div class="card-body">
                                <form class="submitform" action="controller/controller.php" method="post" enctype="multipart/form-data" id="form-password">

                                    <div class="form-group">
                                        <label for="npass" class="h6">New Password</label>
                                        <input type="password" class="form-control" id="npass" name="npass" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="cpass" class="h6">Confirm Password</label>
                                        <input type="password" class="form-control" id="cpass" name="cpass" required>
                                    </div>

                                    <div class="form-group">

                                        <?php if (isset($post['rid']) && $post['rid'] != '') { ?>
                                            <input type="hidden" name="uID" value="<?php echo $post["rid"]; ?>">
                                            <input type="hidden" name="r_link" value="../user-profile.php?rid=<?php echo $post['rid']; ?>" />
                                        <?php } else { ?>
                                            <input type="hidden" name="r_link" value="<?php echo $currenturl; ?>" />
                                        <?php } ?>
                                        <button type="submit" class="btn btn-primary btn-block" name="submit" value="user_updatepassword">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once("footer.php"); ?>