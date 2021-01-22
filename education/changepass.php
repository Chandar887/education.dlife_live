<?php
include_once('include/quiz_header.php');

?>
<style>
    body {
        height: 100vh;
        overflow-y: hidden;
    }

    a {
        text-decoration: none !important;
    }

    h1,
    h2,
    h3 {
        font-family: Roboto;
    }

    label {
        display: inline-block;
        margin-bottom: 0;
        font-size: 15px;
    }

    .alert-danger {
        color: #ffffff;
        background-color: #da3443;
        border-color: #da3443;
        padding: 8px;
    }

    .alert-success {
        color: white;
        background-color: #328e47;
        border-color: #c3e6cb;
        padding: 8px;
    }

    .loader {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #3d2f2f5c;

    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
        margin: 80% 45%;
    }
</style>
<div class="container-fluid h-100">
    <div class="row align-items-center h-100">
        <div class="col-lg-6 col-md-6 col-sm-12 mx-auto">
            <form action="controller/edu_controller.php" method="post" class="p-4 border bg-white rounded" id="form-login">
                <h3 class="text-center mb-3">Update Password</h3>

                <?php
                if (isset($_SESSION['updatepass'])) {
                ?>
                    <p class="alert alert-success mb-3"><?php echo $_SESSION['updatepass']; ?></p>
                <?php
                } else if (isset($_SESSION['failed'])) {
                ?>
                    <p class="alert alert-danger mb-3"><?php echo $_SESSION['failed']; ?></p>
                <?php
                }
                ?>

                <div class="form-group">
                    <label for="exampleInputEmail1">New Password</label>
                    <input type="password" name="newpass" class="form-control" id="newpass" value="" placeholder="New Password" required>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Confirm Password</label>
                    <input type="password" name="conpass" class="form-control" id="conpass" value="" placeholder="Confirm Password" required>
                </div>

                <input type="hidden" name="ID" value="<?php echo $userData['ID']; ?>">
                <div class="form-group">
                    <button type="submit" name="update" class="loaderrr btn btn-block mybtn btn-primary" value="update password">Update</button>
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

<script>
    $(document).ready(function() {
        $('.loaderrr').click(function() {
            var newpass = $('#newpass').val();
            var conpass = $('#conpass').val();

            if (newpass == "" || conpass == "") {

            } else {
                $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
            }

        });

    });
</script>