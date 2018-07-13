<?php
require_once 'header.php';
if (isset($_POST['process_name'])) {
    if ($_POST['process_name'] == "set_status") {
        if (!empty($_POST['ckb'])) {
            $sql = "update " . tbl_name("user") . " set status = '".$_POST["statusVal"] . "' where id  in (" . join(",", $_POST['ckb']) . ") ";
            //echo $sql;
            $db->query($sql)->execute();
        }
    }
     if ($_POST['process_name'] == "delete") {
        if (!empty($_POST['ckb'])) {

            $sqlDelete = " DELETE FROM " . tbl_name("user") . " WHERE usertype='trainer' and id in (" . join(",", $_POST['ckb']) . ")";
            $rows = $db->query($sqlDelete)->execute();
            $sqlDelete = " DELETE FROM " . tbl_name("trainer_profile") . " WHERE  user_id in (" . join(",", $_POST['ckb']) . ")";
            $rows = $db->query($sqlDelete)->execute();
            if ($db->query($row) == TRUE) {
                echo "<script>alert('Record Successfully Deleted');</script>";
            } else {
                echo "<script>alert('Something Went wrong');</script>";
            }
            
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

                    <!-- menu profile quick info -->
                    <!--            <div class="profile">
                                  <div class="profile_pic">
                                    <img src="images/img.jpg" alt="..." class="img-circle profile_img">
                                  </div>
                                  <div class="profile_info">
                                    <span><?php echo get_value('welcome'); ?>,</span>
                                    <h2>John Doe</h2>
                                  </div>
                                </div>-->
                    <!-- /menu profile quick info -->

                    <br />

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
                    <div class="page-title">
                        <div class="title_left">
                            <h3><?php echo get_value("view_coach"); ?> </h3>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                
                                <div class="x_content">


                                    <form name="frmTrainee"  id="frmTrainee" method="post" >
                                        <a href="add_trainer.php" id="english" class="btn btn-success"><?php echo get_value('add_coach'); ?></a>
                                        <!-- <input type="submit" name="process_delete" class="btn btn-warning" value="Delete"> -->
                                        <a href="#" id="btnDelete" class="btn btn-warning"><?php echo get_value('delete'); ?></a>
                                        <br>
                                        <!-- <?php echo get_value("set_status"); ?>
                                        <?php
                                        $opList = array("0" => "Pending", "1" => "Active", "2" => "Deactive");
                                        ?>
                                        <select name="statusVal" id="statusVal" style="padding:8px;margin:5px;">
                                            <?php
                                            foreach ($opList as $key => $value) {
                                                echo "<option value='" . $key . "'>" . $value . "</option>";
                                            }
                                            ?>
                                        </select> -->
                                        <input type="hidden" name="process_name" id="process_name" value="set_status">
                                        <input type="hidden" name="process_delete" id="process_delete" value="delete">
                                        <!-- <input type="button" id="btnStatus_trainer" name="btnStatus_trainer" class="btn btn-success" value="<?php echo get_value('apply'); ?>"></button> -->
                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="select_all">
                                                        S.No</th>
                                                    <th><?php echo get_value("name"); ?></th>
                                                    <th><?php
                                            echo get_value("email");
                                            echo "<br>" . get_value("password");
                                            ?></th>
                                                    <th><?php echo get_value("gender"); ?></th>
                                                    <th><?php echo get_value("age"); ?></th>
                                                    <th><?php echo get_value("training_level"); ?></th>
                                                    <th><?php echo get_value("section"); ?></th>
                                                    <th><?php echo get_value("action"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="notranslate">
                                                <?php
                                                $sql = "select tu.*, tp.age, tp.level, tc.category_name from tbl_user tu inner join tbl_trainer_profile tp on tp.user_id = tu.id"
                                                        . " inner join tbl_category tc on tc.id=tp.sport_id where usertype='trainer'";

                                                $rows = $db->query($sql)->fetch();
                                                if ($db->affected_rows > 0) {
                                                    $counter = 1;
                                                    foreach ($rows as $record) {


                                                        $setStatus = "<span style='color:#808000;font-weight:bold'>Pending</span>";
                                                        if ($record['status'] == 1) {
                                                            $setStatus = "<span style='color:#00f;font-weight:bold'>Active</span>";
                                                        } else if ($record['status'] == 2) {
                                                            $setStatus = "<span style='color:#f00;font-weight:bold'>Deactive</span>";
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td> <input type="checkbox" class="ckbox" id="ckb<?php echo $record['id']; ?>" name="ckb[]" value="<?php echo $record['id']; ?>">
                                                                <?php echo $record['id']; ?></td>
                                                            <td><?php echo ucwords($record['user_name']); ?></td>
                                                            <td><?php
                                                        echo ucwords($record['email']);
                                                        echo "<br><b>Pass: </b>" . ucwords($record['user_password']);
                                                                ?></td>
                                                            <td><?php echo ucwords($record['gender']); ?></td>
                                                            <td><?php echo ucwords($record['age']); ?></td>
                                                            <td><?php echo ucwords($record['level']); ?></td>
                                                            <td><?php echo ucwords($record['category_name']); ?></td>
                                                            <td class="translate">
                                                                <a style="color:#fff" class="btn btn-primary btn-xs" href="add_trainer.php?edit=<?php echo $record['id']; ?>"><i class="fa fa-edit"></i> <?php echo get_value("edit"); ?></a>&nbsp;&nbsp;
                                                                <br>
                                                                <a style="color:#fff" class="btn btn-info btn-xs" href="add_trainer_profile.php?edit=<?php echo $record['id']; ?>"><i class="fa fa-edit"></i> <?php echo get_value("edit_profile"); ?></a>
                                                                <br><a style="color:#fff" class="btn btn-danger btn-xs" href="add_gallery.php?edit=<?php echo $record['id']; ?>"><i class="fa fa-edit"></i> <?php echo get_value("update_gallery"); ?></a>
                                                                <br><a style="color:#fff" class="btn btn-success btn-xs sendNotificationTrainer" href="#pushNotify" id="<?php echo $record['id']; ?>"><i class=""></i> <?php echo get_value("send"); ?></a>
                                                            </td>
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
                        <div class="modal fade" tabindex="-1" role="dialog" id="pushNotify">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><?php echo get_value('notification'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmSend"  id="frmSend" method="post" action="">
                                <div class="row">
                                    <input type="hidden" id="trainee_id" name="trainee_id">
                                    <label for="course_detail" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo get_value('notification'); ?> <span class="required">*</span></label>
                                    <div class="col-md-12 col-sm-6 col-xs-12">
                                        <textarea id="notification_text" type="text" name="notification_text"  class="form-control col-md-12 col-xs-12" required="required"></textarea>
                                    </div>
                                </div>

                            </form>

                        </div>
                        <div class="modal-footer">
<!--                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                            <button type="button"  id="sendNotificationTrainer" class="btn btn-primary"><?php echo get_value('send'); ?></button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <?php
            require_once 'footer.php';
            ?>