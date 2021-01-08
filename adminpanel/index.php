<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['mlmadmin']) && $_SESSION['mlmadmin']['role'] == 'admin') {
    header("Location: dashboard.php");
}
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="assets/favicon.png">

        <title>Adminpanel Login</title>
        <!-- Bootstrap core CSS -->
        <!--<link href="assets/css/bootstrap-3.min.css" rel="stylesheet">-->
        <link href="assets/css/bootstrap-4-sandstone.min.css" rel="stylesheet">
        <!-- Dashboard Style -->
        <link href="assets/css/dashboard.css" rel="stylesheet">
        <!-- Editor -->
        <link href="assets/css/editor.css" rel="stylesheet">
        <!-- input tag -->
        <!--<link href="assets/css/tagsinput.css" rel="stylesheet">-->
        <!-- Icons -->
        <link rel="stylesheet" href="assets/css/font-awesome-4.min.css">

        <!-- Upload -->
        <link rel="stylesheet" href="assets/bootstrap-fileinput/css/fileinput.min.css">
        <link rel="stylesheet" href="assets/bootstrap-fileinput/themes/explorer-fas/theme.css">

        <!-- My Custom code -->
        <link rel="stylesheet" href="assets/css/custom.css">
        <style type="text/css">
            body {
                background-image: url(assets/images/bg1.jpg);
                background-repeat: repeat;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid h-100">
            <div class="row align-items-center h-100">
                <div class="col-lg-4 col-md-6 col-10 mx-auto">
                    <form action="controller/controller.php" method="post" class="border col-12 p-4 mt-5 bg-white shadow" id="form-login">
                        <!--<input type="hidden" name="r_link" value="index.php">-->
                        <h2 class="text-center">Login to Adminpanel</h2>
                        <?php
                        if (isset($_SESSION['status'])) {
                            $msg = '';
                            $css_class = '';
                            if ($_SESSION['status'] == 0) {
                                $css_class = 'alert-danger';
                                $msg = '<strong>Error!</strong> Username or Password not Correct';
                            } else if ($_SESSION['status'] == 1) {
                                $css_class = 'alert-success';
                                $msg = '<strong>Success!</strong> Login Success';
                            }
                            echo'<div class="alert ' . $css_class . ' alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $msg . '</div>';
                            unset($_SESSION['status']);
                        }
                        ?>
                        <div class="form-group">
                            <label for="r_username">User Email</label>
                            <input type="email" name="r_username" class="form-control" placeholder="User Email" required="">
                        </div>
                        <div class="form-group">
                            <label for="r_password">Password</label>
                            <input type="password" name="r_password" class="form-control" placeholder="Password" required="">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-primary" value="login"><i class="fa fa-sign-in"></i> Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="assets/js/jquery-2.0.0.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <!-- Icons -->
        <script src="assets/js/feather.min.js"></script>
        <!-- Editor -->
        <script src="assets/js/editor.js"></script>
        <!-- Input tags -->
        <!--<script src="assets/js/tagsinput.js"></script>-->
        <!-- Upload -->
        <script src="assets/bootstrap-fileinput/js/fileinput.min.js"></script>
        <!-- Custom Script -->
        <script src="assets/js/jquery.validate.min.js"></script>
        <script src="assets/js/custom.js"></script>
        <script>
            feather.replace();
            $(document).ready(function () {
                $('#form-login').validate({
                    rules: {
                        r_username: {
                            required: true,
                            email: true
                        },
                        r_password: {
                            required: true,
                        }
                    },
                    messages: {
                        r_username: {
                            required: "Please Enter Email",
                            email: "Please Enter Valid Email"
                        },
                        r_password: {
                            required: "Please Enter Password",
                        }
                    }
                });
            });
        </script>
    </body>
</html>
