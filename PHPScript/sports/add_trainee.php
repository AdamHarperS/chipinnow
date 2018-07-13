<?php
require_once 'header.php';

/* * Field List* */

$txtid = "";
$currentPagename = "trainee";
$pageaction = "add";
$pagetitle = "Add " . ucwords($currentPagename);
$action_url = "process_trainee.php";
$errorMessage = "Record is already exist";
$row = array();
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
if (isset($_GET["edit"])) {
    $record_id = strip_tags($_GET["edit"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {
        $pageaction = "modify";
        $pagetitle = "Edit " . ucwords($currentPagename);

        $sql = "select * from " . tbl_name("user") . " where usertype='trainee' and id = " . $record_id;
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
                                    <h2><?php echo get_value('add_trainee'); ?></h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <form class="form-horizontal form-label-left" novalidate method="post" action="<?php echo $action_url; ?>">
                                        <input type="hidden" name="txtid" value="<?php echo $txtid ?>">
                                        <input type="hidden" name="process_name" value="<?php echo $pageaction; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "user_name");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo get_value('name');?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditName; ?>"  data-validate-length-range="6" data-validate-words="2" name="name" placeholder="" required="required" type="text">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "email");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?php echo get_value('email');?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="email" id="email" name="email" required="required"  value="<?php echo $controlEditName; ?>" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "user_password");

                                            ?>
                                            <label for="password" class="control-label col-md-3"><?php echo get_value('password');?></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="password" type="password" name="password"  value="<?php echo $controlEditName; ?>" data-validate-length="6,8" class="form-control col-md-7 col-xs-12" required="required">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "user_password");
                                            ?>
                                            <label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('confirm_password');?></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="password2" type="password" name="password2" value="<?php echo $controlEditName; ?>"  data-validate-linked="password" class="form-control col-md-7 col-xs-12" required="required">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "gender");
                                            $controlName = "gender";
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('gender');?></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control notranslate" name="gender" id="gender">
                                                    <option value=""><?php echo get_value('select_gender');?></option>
                                                    <?php
                                                    $arrList = array("Male", "Female");
                                                    foreach ($arrList as $value) {
                                                        if ($controlEditName == $value) {
                                                            echo "<option value='" . $value . "' selected>" . $value . "</option>";
                                                        } else {
                                                            echo "<option value='" . $value . "'>" . $value . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="item form-group">
<?php
$controlEditName = DefaultFieldValue($row, "user_mobile");

?>
                                            <label for="mobile" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('mobile');?></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="mobile" type="text" name="mobile"  value="<?php echo $controlEditName; ?>"  class="form-control col-md-7 col-xs-12" required="required">
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