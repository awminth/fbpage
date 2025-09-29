<?php 

include('config.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BRIGHTWARE IT SOLUTIONS</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/fontawesome-free/css/all.min.css' ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/icheck-bootstrap/icheck-bootstrap.min.css' ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/dist/css/adminlte.min.css' ?>">
    <link rel="shortcut icon" href="<?php echo roothtml.'lib/images/icon.png' ?>" />
    <!-- Sweet Alarm -->
    <link href="<?php echo roothtml.'lib/sweet/sweetalert.css' ?>" rel="stylesheet" />
    <script src="<?php echo roothtml.'lib/sweet/sweetalert.min.js' ?>"></script>
    <script src="<?php echo roothtml.'lib/sweet/sweetalert.js' ?>"></script>
    <style>
    #logo {
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo roothtml.'lib/images/header2.jpg' ?>');
        /* Used if the image is unavailable */
        height: 550px;
        /* You must set a specified height */
        background-position: center;
        /* Center the image */
        background-repeat: no-repeat;
        /* Do not repeat the image */
        background-size: cover;
        /* Resize the background image to cover the entire container */

    }

    .loader {
        position: fixed;
        z-index: 999;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        background-color: Black;
        filter: alpha(opacity=60);
        opacity: 0.7;
        -moz-opacity: 0.8;
    }

    .center-load {
        z-index: 1000;
        margin: 300px auto;
        padding: 10px;
        width: 130px;
        background-color: black;
        border-radius: 10px;
        filter: 1;
        -moz-opacity: 1;
    }

    .center-load img {
        height: 128px;
        width: 128px;
    }
    </style>

</head>

<body id="logo" class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b class="text-white">Please Login </b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card ">
            <div class="card-body login-card-body">

                <form id="frm" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="admin" name="username" placeholder="UserName">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" value="1" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" id="btnlogin" class="btn btn-primary btn-block">Sign
                                In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <div class="loader" style="display:none;">
        <div class="center-load">
            <img src="<?php echo roothtml.'lib/images/ajax-loader1.gif'; ?>" />
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo roothtml.'lib/plugins/jquery/jquery.min.js' ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo roothtml.'lib/plugins/bootstrap/js/bootstrap.bundle.min.js' ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo roothtml.'lib/dist/js/adminlte.min.js' ?>"></script>
    <script>
    $(document).ready(function() {

        $(document).on("click", "#btnlogin", function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'index_action.php' ?>",
                data: $("#frm").serialize() + "&action=login",
                beforeSend: function() {
                    $(".loader").show();
                },
                success: function(data) {
                    $(".loader").hide();
                    if (data == 1) {
                        swal("Successful!",
                            "Login Successful.",
                            "success");
                        location.href =
                            "<?php echo roothtml.'home/home.php' ?>";
                    } else {
                        swal("Error!",
                            "User Name or Password incorrect.",
                            "error");
                    }
                }
            });
        });



    });
    </script>

</body>

</html>