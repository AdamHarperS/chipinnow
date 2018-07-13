<?php
require_once 'header.php';

/* * Field List* */

$txtid = "";
$currentPagename = "order";
$pageaction = "add";
$pagetitle = "Request " . ucwords($currentPagename);
$action_url = "process_order.php";
$errorMessage = "Record is already exist";
$row = array();
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
if (isset($_GET["edit"])) {
    $record_id = strip_tags($_GET["edit"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {
        $pageaction = "modify";
        $pagetitle = "Edit " . ucwords($currentPagename);

        $sql = "select * from " . tbl_name("order") . " where id = " . $record_id;
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
                    sidebar('Request Order');
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
                                    <h2><?php echo get_value('request_order'); ?></h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <form class="form-horizontal form-label-left" novalidate method="post" action="<?php echo $action_url; ?>">
                                        <input type="hidden" name="txtid" value="<?php echo $txtid ?>">
                                        <input type="hidden" name="process_name" value="<?php echo $pageaction; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "trainer_id");
                                            $controlName = "trainer_id";
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('select_coach'); ?></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control notranslate" name="trainer_id" id="trainer_id">
                                                    <option value=""><?php echo get_value('select_coach'); ?></option>
                                                    <?php
                                                    $sql = "select * from " . tbl_name("user") . " where usertype='trainer'";
                                                    $rows = $db->query($sql)->fetch();
                                                    if ($db->affected_rows > 0) {
                                                        foreach ($rows as $record) {
                                                            if ($controlEditName == $record['id']) {
                                                                echo "<option value='" . $record['id'] . "' selected>" . ucwords(str_replace("%20"," ",$record['user_name'])) . "</option>";
                                                            } else {
                                                                echo "<option value='" . $record['id'] . "'>" . ucwords(str_replace("%20"," ",$record['user_name'])) . "</option>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "user_id");
                                            $controlName = "trainee_id";
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('select_trainee'); ?></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control notranslate" name="trainee_id" id="trainer_id">
                                                    <option value=""><?php echo get_value('select_trainee'); ?></option>
                                                    <?php
                                                    $sql = "select * from " . tbl_name("user") . " where usertype='trainee'";
                                                    $rows = $db->query($sql)->fetch();
                                                    if ($db->affected_rows > 0) {
                                                        foreach ($rows as $record) {
                                                            if ($controlEditName == $record['id']) {
                                                                echo "<option value='" . $record['id'] . "' selected>" . ucwords(str_replace("%20"," ",$record['user_name'])) . "</option>";
                                                            } else {
                                                                echo "<option value='" . $record['id'] . "'>" . ucwords(str_replace("%20"," ",$record['user_name'])) . "</option>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "course_id");
                                            $controlName = "course_id";
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('course_name'); ?></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control notranslate" name="course_id" id="trainer_id">
                                                    <option value=""><?php echo get_value('course_name'); ?></option>
                                                    <?php
                                                    $sql = "select * from " . tbl_name("course") . "";
                                                    $rows = $db->query($sql)->fetch();
                                                    if ($db->affected_rows > 0) {
                                                        foreach ($rows as $record) {
                                                            if ($controlEditName == $record['id']) {
                                                                echo "<option value='" . $record['id'] . "' selected>" . ucwords(str_replace("%20"," ",$record['course_name'])) . "</option>";
                                                            } else {
                                                                echo "<option value='" . $record['id'] . "'>" . ucwords(str_replace("%20"," ",$record['course_name'])) . "</option>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "height");
                                            ?>
                                            <label for="order_detail" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('height'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="height" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditName; ?>"  name="height" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                         <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "age");
                                            ?>
                                            <label for="age" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('age'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                
                                                <input id="age" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditName; ?>"  name="age" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "weight");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight"><?php echo get_value('weight'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="weight" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditName; ?>"  name="weight" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                       
                                        
                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                                <button id="send" type="submit" class="btn btn-success"><?php echo get_value('save'); ?></button>
                                                <a href="view_order.php" class="btn btn-primary"><?php echo get_value('cancel'); ?></a>

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