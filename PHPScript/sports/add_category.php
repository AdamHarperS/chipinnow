<?php
require_once 'header.php';

/* * Field List* */

$txtid = "";
$currentPagename = "category";
$pageaction = "add";
$pagetitle = "Add " . ucwords($currentPagename);
$action_url = "process_category.php";
$errorMessage = "Record is already exist";
$row = array();
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
if (isset($_GET["edit"])) {
    $record_id = strip_tags($_GET["edit"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {
        $pageaction = "modify";
        $pagetitle = "Edit " . ucwords($currentPagename);

        $sql = "select * from " . tbl_name("category")." where id = ".$record_id;
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
                    sidebar('Category');
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
                                    <h2><?php echo get_value('add_category'); ?></h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <form class="form-horizontal form-label-left" novalidate method="post" action="<?php echo $action_url; ?>" enctype="multipart/form-data" >
                                        <input type="hidden" name="txtid" value="<?php echo $txtid ?>">
                                        <input type="hidden" name="process_name" value="<?php echo $pageaction; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "category_name");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo get_value('name'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditName; ?>"   name="name" placeholder="" required="required" type="text">
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "description");
                                            ?>
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description"><?php echo get_value('description'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea class="form-control col-md-7 col-xs-12" name="description" id="description" required="required"><?php echo $controlEditName; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <?php
                                            $controlEditName = DefaultFieldValue($row, "image");
                                            ?>
                                            <label for="photo" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('image'); ?></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php
                                                $required= ' required';
                                                if ($pageaction == "modify") {
                                                    $required='';
                                                }
                                                ?>
                                                <input id="photo" type="file" name="photo"  class=" col-md-7 col-xs-12" <?php echo $required;?>>
                                                <input id="oldPhoto" type="hidden" name="oldPhoto" value="<?php echo $controlEditName;?>"  class=" col-md-7 col-xs-12">
                                                <?php
                                                if ($pageaction == "modify") {
                                                    if (!empty($controlEditName)) {
                                                        echo "<br><br><img width='150px' src='images/" . $controlEditName . "'>";
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                                <button id="send" type="submit" class="btn btn-success"><?php echo get_value('save'); ?></button>
                                                <a href="view_category.php" class="btn btn-primary"><?php echo get_value('cancel'); ?></a>

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