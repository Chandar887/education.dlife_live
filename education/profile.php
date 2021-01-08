<?php
include_once('include/quiz_header.php');

$getUserData = $db->selectQuery("select * from w_users where ID={$userData['ID']}");
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
            if (isset($_SESSION['updated'])) {
            ?>
                <p class="alert alert-success <?php echo isset($_SESSION['updated']) ? 'mt-3' : ''; ?>"><?php echo $_SESSION['updated']; ?></p>
            <?php
            }
            ?>
            <form action="controller/edu_controller.php" method="post" class="p-4 border bg-white rounded <?php echo isset($_SESSION['updated']) ? '' : 'mt-3'; ?>" id="form-login">
                <h2 class="text-center mb-3">Update Profile</h2>

                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="uName" class="form-control" value="<?php echo $getUserData[0]['uName']; ?>" placeholder="Name">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" name="uEmail" class="form-control" value="<?php echo $getUserData[0]['uEmail']; ?>" placeholder="Email">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Mobile</label>
                    <input type="number" name="uMobile" class="form-control" value="<?php echo $getUserData[0]['uMobile']; ?>" placeholder="Mobile">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Postcode</label>
                    <input type="number" name="uPostcode" class="form-control" value="<?php echo $getUserData[0]['uPostcode']; ?>" placeholder="Postcode">
                </div>
               
                <input type="hidden" name="ID" value="<?php echo $userData['ID']; ?>">
                <div class="form-group">
                    <button type="submit" name="update" class=" btn btn-block mybtn btn-primary" value="update profile">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_SESSION['updated'])) {
    unset($_SESSION['updated']);
}

include_once('include/quiz_footer.php');
?>