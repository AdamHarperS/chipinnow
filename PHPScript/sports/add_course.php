<?php
require_once 'header.php';

/* * Field List* */

$txtid = "";
$currentPagename = "course";
$pageaction = "add";
$pagetitle = "Add " . ucwords($currentPagename);
$action_url = "process_course.php";
$errorMessage = "Record is already exist";
$row = array();
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
if (isset($_GET["edit"])) {
    $record_id = strip_tags($_GET["edit"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {
        $pageaction = "modify";
        $pagetitle = "Edit " . ucwords($currentPagename);

        $sql = "select * from " . tbl_name("course") . " where id = " . $record_id;
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
                    sidebar('Trainee');
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
                                    <h2><?php echo get_value('add_course'); ?></h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <form class="form-horizontal form-label-left" novalidate method="post" action="process_course.php" enctype="multipart/form-data">
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
                                                                echo "<option value='" . $record['id'] . "' selected>" . ucwords(str_replace("%20", " ", $record['user_name'])) . "</option>";
                                                            } else {
                                                                echo "<option value='" . $record['id'] . "'>" . ucwords(str_replace("%20", " ", $record['user_name'])) . "</option>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "course_name");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="course_name"><?php echo get_value('course_name'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="course_name" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditName; ?>"  name="course_name" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "course_location");
                                            ?>
                                            <label for="course_detail" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('course_address'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="course_location" type="text" name="course_location"  class="form-control col-md-7 col-xs-12" required="required" value="<?php echo $controlEditName; ?>">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "course_city");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="course_location"><?php echo get_value('course_city'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">

                                                <input id="course_city" class="form-control col-md-7 col-xs-12 notranslate" value="<?php echo $controlEditName; ?>" name="course_city" placeholder="" required="required" type="text">

                                            </div>
                                        </div> 
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "course_detail");
                                            ?>
                                            <label for="course_detail" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('course_detail'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea id="course_detail" type="text" name="course_detail"  class="form-control col-md-7 col-xs-12" required="required"><?php echo $controlEditName; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "course_price");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="course_price"><?php echo get_value('course_price'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="course_price" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditName; ?>"  name="course_price" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "course_time");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="course_time"><?php echo get_value('course_time'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="course_time" class="form-control col-md-7 col-xs-12 form_datetime"  value="<?php echo $controlEditName; ?>"  name="course_time" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "hours_number");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number_of_hours"><?php echo get_value('number_of_hours'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="number_of_hours" class="form-control col-md-7 col-xs-12 "  value="<?php echo $controlEditName; ?>"  name="number_of_hours" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "hours_number");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="total_trainee_allowed"><?php echo get_value('total_trainee_allowed'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="total_trainee_allowed" class="form-control col-md-7 col-xs-12 "  value="<?php echo $controlEditName; ?>"  name="total_trainee_allowed" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "image");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image"><?php echo get_value('image'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="file" name="image" class="form-control">
                                                <img src="images/course/<?php echo $controlEditName; ?>" width="50px" height="50px">
                                            </div>
                                        </div>

                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                                <button id="send" type="submit" class="btn btn-success"><?php echo get_value('save'); ?></button>
                                                <a href="view_course.php" class="btn btn-primary"><?php echo get_value('cancel'); ?></a>

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