<?php
session_start();
require_once 'function.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sports Application | </title>

        <!-- Bootstrap -->
        <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
        <!-- Animate.css -->
        <!--        <link href="https://colorlib.com/polygon/gentelella/css/animate.min.css" rel="stylesheet">-->

        <!-- Custom Theme Style -->
        <link href="build/css/custom.min.css" rel="stylesheet">
    </head>

    <body class="login">
        <div>
            <a class="hiddenanchor" id="signup"></a>
            <a class="hiddenanchor" id="signin"></a>
            <?php
            // Create a new CSRF token.
            csrf_token();
            ?>
            <div class="login_wrapper">
                <div class="animate form login_form">
                    <section class="login_content">
                        <form method="post" action="process_login.php" id="frmLogin">
                            <input type="hidden" name="process_name" value="login">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                            <h1>Login Form</h1>
                            <div>
                                <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Username" required="" />
                            </div>
                            <div>
                                <input type="password"  name="user_pwd" id="user_pwd" class="form-control" placeholder="Password" required="" />
                            </div>
                            <div>
                                <input type="submit" class="btn btn-default submit" style='margin-left: 0px' value="Log in">

                            </div>
                            <div class="clearfix"></div>
                            <?php
                            if (isset($_GET["status"])) {
                                if ($_GET['status'] == "fail") {
                                    ?>
                                    <div style='color:#f00;'>
                                        Incorrect User name/Password
                                    </div>

                                    <?php
                                } else {
                                    ?>
                                    <div style='color:#f00;'>
                                        Incorrect User name/Password
                                    </div>

                                    <?php
                                }
                            }
                            ?>

                            <div class="clearfix"></div>

                            <!--              <div class="separator">
                                            <p class="change_link">New to site?
                                              <a href="#signup" class="to_register"> Create Account </a>
                                            </p>
                            
                                            <div class="clearfix"></div>
                                            <br />
                            
                                            <div>
                                              <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                                              <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                                            </div>
                                          </div>-->
                        </form>
                    </section>
                </div>


            </div>
        </div>
    </body>
</html>