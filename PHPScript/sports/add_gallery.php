<?php
require_once 'header.php';

/* * Field List* */

$txtid = "";
$currentPagename = "gallery";
$pageaction = "add";
$pagetitle = "Add " . ucwords($currentPagename);
$action_url = "process_gallery.php";
$errorMessage = "Record is already exist";
$row = array();
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
if (isset($_GET["edit"])) {
    $record_id = strip_tags($_GET["edit"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {
        $pageaction = "modify";
        $pagetitle = "Edit " . ucwords($currentPagename);

        $sql = "select * from " . tbl_name("user") . " where id = " . $record_id;
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
                    sidebar('Trainer');
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
                                    <h2><?php echo get_value('update_gallery'); ?></h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <?php
                                    if (isset($_POST['ckb'])) {
                                        if (!empty($_POST['ckb'])) {

                                            $sqlSelect = " SELECT * FROM " . tbl_name("gallery") . " WHERE  id in (" . join(",", $_POST['ckb']) . ")";
                                            
                                            $rows = $db->query($sqlSelect)->fetch();
                                            $imgpath = 'images/gallery/';
                                            $fieldname = 'image';
                                            if ($db->affected_rows > 0) {
                                                foreach ($rows as $rowselect) {
                                                    if (!empty($rowselect[$fieldname])) {
                                                        if (file_exists($imgpath . $rowselect[$fieldname])) {
                                                            unlink($imgpath . $rowselect[$fieldname]);
                                                        }
                                                    }
                                                    $sqlDelete = " DELETE FROM " . tbl_name("gallery") . " WHERE  id in (" . join(",", $_POST['ckb']) . ")";
                                                    $resultDelete = $db->query($sqlDelete)->execute();
                                                }
                                            }
                                        }
                                    }
                                    ?>

                                    <form class="form-horizontal form-label-left" novalidate method="post" action="<?php echo $action_url; ?>" enctype="multipart/form-data" >
                                        <input type="hidden" name="txtid" value="<?php echo $txtid ?>">
                                        <input type="hidden" name="process_name" value="<?php echo $pageaction; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                        <div class="item form-group">
<?php
$controlEditName = DefaultFieldValue($row, "trainer_id");
?>

                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="trainer_id" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditName; ?>"   name="trainer_id"  type="hidden">
                                            </div>
                                        </div>

                                        <div class="item form-group">
<?php
$controlEditName = DefaultFieldValue($row, "image");
?>
                                            <label for="photo" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('image'); ?></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="photo" type="file" name="photo"  class=" col-md-7 col-xs-12" required="required">
                                                <input id="oldPhoto" type="hidden" name="oldPhoto" value="<?php echo $controlEditName; ?>"  class=" col-md-7 col-xs-12">

                                            </div>
                                        </div>
                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                                <button id="send" type="submit" class="btn btn-success"><?php echo get_value('save'); ?></button>

                                            </div>
                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2><?php echo get_value('view_gallery'); ?></h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <form name="frmTrainee"  id="frmTrainee" method="post" action="add_gallery.php?edit=<?php echo $record_id?>">
                                        <a href="#" id="btnDelete" class="btn btn-warning"><?php echo get_value('delete'); ?></a>
                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="select_all"> 
                                                        S.No</th>
                                                    <th><?php echo get_value("name"); ?></th>
                                                    <th><?php echo get_value("photos"); ?></th>

                                                </tr>
                                            </thead>
                                            <tbody>
<?php
$sql = "select tg.*,tu.user_name  from tbl_user tu inner join tbl_gallery tg on tg.trainer_id = tu.id"
        . " where usertype='trainer' and trainer_id=" . $record_id;

$rows = $db->query($sql)->fetch();
if ($db->affected_rows > 0) {
    $counter = 1;
    foreach ($rows as $record) {
        ?>
                                                        <tr>
                                                            <td> <input type="checkbox" class="ckbox" id="ckb<?php echo $record['id']; ?>" name="ckb[]" value="<?php echo $record['id']; ?>">
                                                        <?php echo $counter; ?></td>
                                                            <td><?php echo ucwords($record['user_name']); ?></td>
                                                            <td><img width='200px' src="images/gallery/<?php echo ucwords($record['image']); ?>"></td>


                                                        </tr>
        <?php
        $counter++;
    }
} else {
    
}
?>
                                            </tbody>
                                        </table>
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