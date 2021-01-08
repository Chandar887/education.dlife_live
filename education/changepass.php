<?php
include_once('include/quiz_header.php');

?>
<style>
    a {
        text-decoration: none !important;
    }

    h1,
    h2,
    h3 {
        font-family: monospace;
    }

    .myform {
        /*position: relative;*/
        display: -ms-flexbox;
        display: flex;
        padding: 1rem;
        -ms-flex-direction: column;
        flex-direction: column;
        width: 100%;
        pointer-events: auto;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, .2);
        border-radius: 1.1rem;
        outline: 0;
        max-width: 500px;
    }

    label {
        display: inline-block;
        margin-bottom: 0;
        font-size: 15px;
    }
</style>
<div class="container-fluid h-100">
    <div class="row align-items-center h-100">
        <div class="col-lg-6 col-md-6 col-sm-12 mx-auto">

            <?php
            if (isset($_SESSION['updatepass'])) {
            ?>
                <p class="alert alert-success <?php echo isset($_SESSION['updatepass']) ? 'mt-3' : ''; ?>"><?php echo $_SESSION['updatepass']; ?></p>
            <?php
            } else if(isset($_SESSION['failed'])){
            ?>
            <p class="alert alert-danger <?php echo isset($_SESSION['failed']) ? 'mt-3' : ''; ?>"><?php echo $_SESSION['failed']; ?></p>
        <?php
            }
            ?>
            <form action="controller/edu_controller.php" method="post" class="p-4 border bg-white rounded <?php echo isset($_SESSION['updatepass']) || isset($_SESSION['failed']) ? '' : 'mt-3'; ?>" id="form-login">
                <h2 class="text-center mb-3">Update Password</h2>

                <div class="form-group">
                    <label for="exampleInputEmail1">New Password</label>
                    <input type="password" name="newpass" class="form-control" value="" placeholder="New Password" required>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Confirm Password</label>
                    <input type="password" name="conpass" class="form-control" value="" placeholder="Confirm Password" required>
                </div>
               
                <input type="hidden" name="ID" value="<?php echo $userData['ID']; ?>">
                <div class="form-group">
                    <button type="submit" name="update" class=" btn btn-block mybtn btn-primary" value="update password">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_SESSION['updatepass']) || isset($_SESSION['failed'])) {
    unset($_SESSION['updatepass']);
    unset($_SESSION['failed']);
}

include_once('include/quiz_footer.php');
?>