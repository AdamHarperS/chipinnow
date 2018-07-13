    <?php
require_once 'header.php';

/* * Field List* */

$txtid = "";
$currentPagename = "notification";
$pageaction = "add";
$pagetitle = "Add " . ucwords($currentPagename);
$action_url = "process_notification.php";
$errorMessage = "Record is already exist";
$row = array();
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
if (isset($_GET["edit"])) {
    $record_id = strip_tags($_GET["edit"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {
        $pageaction = "modify";
        $pagetitle = "Edit " . ucwords($currentPagename);

        $sql = "select * from " . tbl_name("notifications") . " where noti_id = " . $record_id;
        //       echo $sql;
        $rows = $db->query($sql)->fetch();
        if ($db->affected_rows > 0) {
            $row = $rows[0];
            $txtid = $record_id;
        }
    }
}
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
                                    <h2><?php echo get_value('add_notification'); ?></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <form class="form-horizontal form-label-left" novalidate method="post" action="<?php echo $action_url; ?>">
                                        <input type="hidden" name="txtid" value="<?php echo $txtid ?>">
                                        <input type="hidden" name="process_name" value="<?php echo $pageaction; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                        <div class="item form-group">
                                            <?php
                                            $controlEditkey = DefaultFieldValue($row, "content");
                                            ?>
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12" for="content"><?php echo get_value('content');?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <textarea id="content" class="form-control col-md-10 col-xs-12" data-validate-length-range="6" data-validate-words="1" name="content" placeholder="" required="required" type="text"><?php echo $controlEditkey; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-8">
                                                <a href="send_notification.php" class="btn btn-danger"><?php echo get_value('cancel'); ?></a>
                                                <button id="send" type="submit" class="btn btn-success"><?php echo get_value('save'); ?></button>

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