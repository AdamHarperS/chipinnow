<?php
require_once 'header.php';
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
                                    <span><?php #echo get_value('welcome'); ?>,</span>
                                    <h2>John Doe</h2>
                                  </div>
                                </div>-->
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <?php
                    sidebar('Reponse Order');
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
                            <h3><?php echo get_value("response_order"); ?> </h3>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <?php
                               
                                if (isset($_POST["actionName"]) && isset($_POST["recid"])) {
                                    $sqlAction = " UPDATE " . tbl_name("orders") . " SET status = '".$_POST["actionName"]."' WHERE id in (" . $_POST["recid"] . ")";
                                    $rows = $db->query($sqlAction)->execute();
                                }
//                                if (isset($_GET["approve"])) {
//                                    $record_id = strip_tags($_GET["approve"]);
//                                    if (preg_match('/^[0-9]*$/', $record_id)) {
//                                        $sqlAction = " UPDATE " . tbl_name("orders") . " SET status = 'Approve' WHERE id in (" . $record_id . ")";
////                                        $sqlAction = " UPDATE " . tbl_name("orders") . " SET status = 'Under Process' WHERE id in (" . $record_id . ")";
//                                        //  echo $sqlAction;
//                                        $rows = $db->query($sqlAction)->execute();
//                                    }
//                                }
                                ?>
                                <div class="x_content">

                                    <form name="frmTrainee"  id="frmTrainee" method="post" action="response_order.php">
<!--                                         <a href="add_course.php" id="english" class="btn btn-success"><?php echo get_value('add_course'); ?></a>-->


<!--                                        <a href="#" id="btnDelete" class="btn btn-warning"><?php echo get_value('delete'); ?></a>-->
                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>  S.No</th>
                                                    <th><?php echo get_value("name"); ?></th>
                                                    <th><?php echo get_value("coach_name"); ?></th>
                                                    <th><?php echo get_value("course_name"); ?></th>
                                                    <th><?php echo get_value("status"); ?></th>
                                                    <th><?php echo get_value("action"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="notranslate">
                                                <?php
                                                $sql = "select tor.*, tu.user_name as trainer_name, tuu.user_name  as trainee_name, tc.course_name from " . tbl_name("orders") . " tor inner join " . tbl_name("course") . " tc on tc.id = tor.course_id inner join " . tbl_name("user") . " tu "
                                                        . "on tu.id = tor.trainer_id  inner join " . tbl_name("user") . " tuu  on tuu.id = tor.user_id";
//                                                $sql = "select tor.*, tu.user_name as trainer_name, tuu.user_name  as trainee_name, tc.course_name from " . tbl_name("orders") . " tor inner join " . tbl_name("course") . " tc on tc.id = tor.course_id inner join " . tbl_name("user") . " tu "
//                                                        . "on tu.id = tor.trainer_id  inner join " . tbl_name("user") . " tuu  on tuu.id = tor.user_id and tor.status = 'Under Process'";
                                                // echo $sql;
                                                $rows = $db->query($sql)->fetch();
                                                if ($db->affected_rows > 0) {
                                                    $counter = 1;
                                                    foreach ($rows as $record) {
                                                        ?>
                                                        <tr>
                                                            <td> 
                                                                <?php echo $counter; ?></td>
                                                            <td><?php echo ucwords($record['trainee_name']); ?></td>
                                                            <td><?php echo ucwords(str_replace("%20", " ", $record['trainer_name'])); ?></td>
                                                            <td><?php echo ucwords($record['course_name']); ?></td>
                                                            <td><?php echo ucwords($record['status']); ?></td>
                                                            <td width="25%" class="translate">
                                                                <form name="frmAction" method="post" action="response_order.php">
                                                                    <!-- <select name="actionName">
                                                                        <?php
                                                                        $arrList = array("Under Process",
                                                                            "Approve",
                                                                            "Disapprove",
                                                                            "Complete",
                                                                            "Cancel");
                                                                        foreach ($arrList as $value) {
                                                                            echo "<option value='" . $value . "'>" . $value . "</option>";
                                                                        }
                                                                        ?>
                                                                    </select> -->
                                                                    <input type="hidden" name="recid" value="<?php echo $record['id']; ?>">
                                                                    &nbsp;<button type="submit" style="color:#fff" class="btn btn-primary btn-success btn-xs"><i class="fa fa-check"></i> <?php echo get_value("send"); ?></button>
                                                                </form>
                                                                        <!-- <a style="color:#fff" class="btn btn-primary btn-success btn-xs" href="response_order.php?approve=<?php echo $record['id']; ?>"><i class="fa fa-check"></i> <?php echo get_value("approve"); ?></a>
                                                                <a style="color:#fff" class="btn btn-primary btn-danger btn-xs red" href="response_order.php?cancel=<?php #echo $record['id']; ?>"><i class="fa fa-close"></i> <?php #echo get_value("cancel_request"); ?></a> -->
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
            <?php
            require_once 'footer.php';
            ?>