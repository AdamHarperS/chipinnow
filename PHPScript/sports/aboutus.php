<?php
    require_once 'header.php';

    $txtid = "";
    $currentPagename = "contact";
    $pageaction = "add";
    $pagetitle = "Add " . ucwords($currentPagename);
    $action_url = "add_contact.php";
    $errorMessage = "Record is already exist";
    $row = array();
    $db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

    $sql = "select * from " . tbl_name("contact")." where id = 1";

    if (count($db->query($sql)->fetch()) > 0) {
        $pageaction = "modify";
        $pagetitle = "Edit " . ucwords($currentPagename);
        $sql1 = "select * from " . tbl_name("contact")." where id = 1";
        $rows = $db->query($sql1)->fetch();
        if ($db->affected_rows > 0) {
            $row = $rows[0];
            $txtid = $row['id'];
        }
    }
?>

<body class="nav-md">

<script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
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
                                    <h2><?php echo get_value('about_us'); ?></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <form class="form-horizontal form-label-left" novalidate method="post" action="<?php echo $action_url; ?>" enctype="multipart/form-data" >
                                        <input type="hidden" name="txtid" value="<?php echo $txtid ?>">
                                        <input type="hidden" name="process_name" value="<?php echo $pageaction; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                        <div class="item form-group">
                                            <div class="col-md-10 col-sm-10 col-xs-12 notranslate">
                                               <textarea class="form-control" name="content"><?= isset($row['contact_us'])?$row['contact_us']:''?></textarea>
                                            </div>
                                        </div>
                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                                <button id="send" type="submit" class="btn btn-success"><?php echo get_value('save'); ?></button>
                                                <a href="dashboard.php" class="btn btn-primary"><?php echo get_value('cancel'); ?></a>

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
<script>
    CKEDITOR.replace( 'content');
</script>