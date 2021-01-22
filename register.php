<?php
include_once('database.php');

if (isset($_SESSION['uToken'])) {
    header('location:education/index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from ngetemplates.com/mshop/mshop/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 17 Dec 2020 04:47:10 GMT -->

<head>
    <meta charset="UTF-8">
    <title>Educationpanel | Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1  maximum-scale=1 user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="HandheldFriendly" content="True">

    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900" rel="stylesheet">
    <!--<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i" rel="stylesheet">-->
    <!-- CSS only -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->

</head>
<style>
    body {
        /*padding-top: 1.7rem;*/
        /* padding-bottom: 4.2rem; */
        background: url(img/bg1.jpg);
    }

    a {
        text-decoration: none !important;
    }

    h1,
    h2,
    h3 {
        font-family: monospace;
    }

    .myform {
        position: relative;
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

    .alert-danger {
        color: #ffffff;
        background-color: #da3443;
        border-color: #da3443;
        padding: 8px;
    }

    .login-or {
        position: relative;
        color: #aaa;
        margin-top: 10px;
        margin-bottom: 10px;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .span-or {
        display: block;
        position: absolute;
        left: 50%;
        top: -2px;
        margin-left: -25px;
        background-color: #fff;
        width: 50px;
        text-align: center;
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

<body style="height:100vh;">
    <div class="container-fluid h-100">
        <div class="row align-items-center h-100">
            <div class="col-lg-6 col-md-6 col-sm-12 mx-auto">
                <form action="controller/controller.php" class="border p-4 bg-white rounded mt-5" method="post" name="login">
                    <div class="text-center"><img src="img/logo.png" class="text-center" style="width: 30%; margin-bottom: 14px;">
                    </div>
                    <h2 class="text-center mb-3">Register</h2>

                    <?php
                    if (isset($_SESSION['err'])) {
                    ?>
                        <p class="alert alert-danger my-3"><?php echo $_SESSION['err']; ?></p>
                    <?php
                    }
                    ?>

                    <div class="form-group">
                        <!--<label for="exampleInputEmail1">Name*</label>-->
                        <input type="text" name="uName" class="form-control" id="name" aria-describedby="emailHelp" placeholder="Name*" required>
                    </div>
                    <div class="form-group">
                        <!--<label for="exampleInputEmail1">Email*</label>-->
                        <input type="email" name="uEmail" value="" id="email" class="form-control" placeholder="Email*" required>
                    </div>
                    <div class="form-group">
                        <!--<label for="exampleInputEmail1">Mobile*</label>-->
                        <input type="text" name="uMobile" value="" class="form-control" placeholder="Mobile*" required>
                    </div>
                    <div class="form-group">
                        <!--                            <label for="exampleInputEmail1">Postcode*</label>-->
                        <input type="text" name="uPostcode" value="" id="postcode" class="form-control" placeholder="Post Code*" required>
                    </div>
                    <div class="form-group">
                        <!--<label for="exampleInputEmail1">Password*</label>-->
                        <input type="password" class="form-control" name="uPassword" id="password" placeholder="Password*" required="">
                    </div>

                    <div class="form-group">
                        <button type="submit" name="submit" class="loaderrr btn btn-block mybtn btn-primary tx-tfm" value="register">Register</button>
                    </div>
                    <a href="index.php" class="mb-3 loading"><span style="color: #007bff;">Have an Account</span> Login!</a>
                </form>

            </div>
        </div>

    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.loaderrr').click(function() {
                var email = $('#email').val();
                var pass = $('#password').val();
                var name = $('#name').val();
                var postcode = $('#postcode').val();
                if (email == "" || pass == "" || postcode == "" || name == "") {

                } else {
                    $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
                }

            });

            $('.loading').click(function() {
                $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
                var path = $(this).attr('href');
                window.location.href = path;
            });
        });
    </script> 


</body>

<!-- Mirrored from ngetemplates.com/mshop/mshop/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 17 Dec 2020 04:47:10 GMT -->

</html>

<?php
if (isset($_SESSION['err'])) {
    unset($_SESSION['err']);
}
?>