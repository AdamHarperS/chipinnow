<?php
require_once 'header.php';
$sql = "SELECT tc.*,tu.user_name,tu.email FROM ".tbl_name("complaint")." tc 
INNER JOIN tbl_user tu ON tu.id = tc.user_id where tc.id='".$_REQUEST['id']."'";
$res = mysqli_query($conn,$sql);
$result = mysqli_fetch_array($res);
$action_url = "process_reply.php";
?>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="dashboard.php" class="site_title"><i class="fa fa-paw"></i> <span><?php echo get_value('admin_panel') ?></span></a>
                    </div>

                    <div class="clearfix"></div>
                    <!-- sidebar menu -->
                    <?php
                    sidebar('Notification');
                    ?>
                    <!-- /sidebar menu -->

                </div>
            </div>

            <!-- top navigation -->
            <?php
            top_right_bar();
            ?>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <?php
                    // Create a new CSRF token.
                    csrf_token();
                    ?>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2><?php echo get_value('send_reply'); ?></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <form class="form-horizontal form-label-left" novalidate method="post" action="<?php echo $action_url; ?>">
                                        <input type="hidden" name="txtid" value="<?php echo $txtid ?>">
                                        <div class="item form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12"><?php echo get_value('name');?>
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" name="username" class="form-control" readonly="" style="cursor: no-drop;" value="<?php echo $result ['user_name']; ?>">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12"><?php echo get_value('email');?>
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" name="email" class="form-control" readonly="" style="cursor: no-drop;" value="<?php echo $result ['email']; ?>">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12"><?php echo get_value('message');?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <textarea id="message" class="form-control col-md-10 col-xs-12" data-validate-length-range="6" data-validate-words="1" name="message" placeholder="" required="required"></textarea>
                                            </div>
                                        </div>
                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-8">
                                                <a href="complaint.php" class="btn btn-danger"><?php echo get_value('cancel'); ?></a>
                                                <input type="submit" name="send_reply" class="btn btn-success" value="Send">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->
        </div>
    </div>
</body>
<?php
require_once 'footer.php';
?>