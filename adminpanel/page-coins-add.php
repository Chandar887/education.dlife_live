<?php
$title = "Coins";
$apage = "coins";
include_once("header.php");
//print_r($_SESSION['f_admin']['ID']);
$uData = array("uMobile"=>"", "review" => "");
if (isset($request["uid"]) && $request["uid"] != '') {
    if ($userData = $db->selectRows("w_users", "", "ID={$request["uid"]}")) {
        $uData = $userData[0];
    }
}

?>
<main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fa fa-"></i> <?php echo $title; ?></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <?php if (isset($request["rid"]) && $request["rid"] != '') { ?>
                                <!--<a class="btn btn-primary" href="page-useradd.php"><i class="fa fa-plus pr-2"></i>New User</a>-->
            <?php } ?>
            <!--<a class="btn btn-primary ml-1" href="page-userlist.php"><i class="fa fa-list pr-2"></i>Users List</a>-->
        </div>
    </div>
    <div class="col-12 col-md-8 mx-auto">
        <form class="border rounded p-4 submitform" action="controller/controller.php" method="post" enctype="multipart/form-data">
            <p class="h4">Enter Coins Amount</p>
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

            <div class="form-group mb-4">
                <label for="contact">Mobile<span class="required">*</span></label>
                <input type="text" id="mobile" name="uMobile" class="form-control U_mobileNumber" data-id='<?php echo $uData['uMobile'] ?>' value="<?php echo isset($uData) ? $uData['uMobile'] : ''; ?>" <?php echo isset($request["reqID"]) ?"readonly":"";?> required>
            </div>
            <div class="form-group mb-4">
                <label for="contact">Amount<span class="required">*</span></label>
                <input type="number" id="coins" name="uCoin" min="10" step="10" class="form-control U_coin" data-id='<?php echo $uData['uMobile'] ?>' placeholder="Coin Amount *" value="<?php echo isset($request["coin"]) ? number_format($request["coin"]) : '10'; ?>" <?php echo isset($request["coin"]) ?"readonly":"";?> required>
            </div>
            <div class="form-group mb-4">
                <div class="userName"> </div>
            </div>

            <input type="hidden" name="uid" class="form-control uid" value="<?php echo isset($request['uid'])? $request['uid']:""; ?>">
            <?php if(isset($request["reqID"])) { ?>
            <input type="hidden" name="reqID" class="form-control" value="<?php echo isset($request['reqID'])? $request['reqID']:""; ?>">
            <?php } ?>
            <input type="hidden" name="r_link" value="<?php echo $currenturl; ?>" />
            <div class="form-group">
                <button type="submit" name="submit" value="addCoins" class="btn btn-primary btn-block button_submit">Send</button>
            </div>
        </form>
    </div>
</main>
<?php include_once("footer.php"); ?>