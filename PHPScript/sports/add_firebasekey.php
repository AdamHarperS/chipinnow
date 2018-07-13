    <?php
    
require_once 'header.php';

/* * Field List* */

$txtid = "";
$currentPagename = "firebase Keys";
$pagetitle = "Add " . ucwords($currentPagename);
$action_url = "process_keys.php";
$errorMessage = "Record is already exist";
$row = array();
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
// if (isset($_GET["edit"])) {
//     $record_id = strip_tags($_GET["edit"]);
//     if (preg_match('/^[0-9]*$/', $record_id)) {
        $pagetitle = "Edit " . ucwords($currentPagename);

        $sql = "select * from " . tbl_name("firebase_keys");
        //       echo $sql;
        $rows = $db->query($sql)->fetch();
        if ($db->affected_rows > 0) {
            $pageaction = "modify";
            $row = $rows[0];
            //$txtid = $record_id;
        }else{
             $pageaction = "add";
        }
//     }
// }
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
                                    <h2><?php echo get_value('notification_setting'); ?></h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <form class="form-horizontal form-label-left" novalidate method="post" action="<?php echo $action_url; ?>">
                                        <input type="hidden" name="txtid" value="<?php echo DefaultFieldValue($row, "f_id"); ?>">
                                        <input type="hidden" name="process_name" value="<?php echo $pageaction; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                        <div class="item form-group">
                                            <?php
                                            $controlEditkey = DefaultFieldValue($row, "androidkey");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Android FirebaseKey">Android FirebaseKey <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditkey; ?>"  data-validate-length-range="6" data-validate-words="1" name="androidkey" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditkey = DefaultFieldValue($row, "ioskey");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ioskey">Ios FirebaseKey <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditkey; ?>"  data-validate-length-range="6" data-validate-words="1" name="ioskey" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                                <button id="send" type="submit" class="btn btn-success"><?php echo get_value('save'); ?></button>
                                                <a href="view_trainee.php" class="btn btn-primary"><?php echo get_value('cancel'); ?></a>

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
<?php
require_once 'footer.php';
?>