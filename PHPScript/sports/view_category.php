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
                                    <span><?php echo get_value('welcome'); ?>,</span>
                                    <h2>John Doe</h2>
                                  </div>
                                </div>-->
                    <!-- /menu profile quick info -->

                    <br />

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
                    <div class="page-title">
                        <div class="title_left">
                            <h3><?php echo get_value("view_category"); ?> </h3>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <?php
                                if (isset($_POST['ckb'])) {
                                    if (!empty($_POST['ckb'])) {

                                        $sqlSelect = " SELECT *  FROM " . tbl_name("category") . " WHERE id in (" . join(",", $_POST['ckb']) . ")";
                                        $rows = $db->query($sqlSelect)->fetch();
                                        if ($db->affected_rows > 0) {
                                            foreach ($rows as $record) {
                                                if (file_exists('images/' . $record['image'])) {
                                                    unlink('images/' . $record['image']);
                                                }
                                            }
                                        }
                                        $sqlDelete = " DELETE FROM " . tbl_name("category") . " WHERE id in (" . join(",", $_POST['ckb']) . ")";

                                        $rows = $db->query($sqlDelete)->execute();
                                    }
                                }
                                ?>
                                <div class="x_content">
                                    <form name="frmTrainee" id="frmTrainee" method="post" action="view_category.php">
                                        <a href="add_category.php" id="english" class="btn btn-success"><?php echo get_value('add_category'); ?></a>
                                        <a href="#" id="btnDelete" class="btn btn-warning"><?php echo get_value('delete'); ?></a>

                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th> <input type="checkbox" id="select_all"> S.No</th>
                                                    <th><?php echo get_value("category_name"); ?></th>
                                                    <th><?php echo get_value("description"); ?></th>
                                                    <th><?php echo get_value("image"); ?></th>

                                                    <th><?php echo get_value("action"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="notranslate">
                                                <?php
                                                $sql = "select * from tbl_category";
                                                $rows = $db->query($sql)->fetch();
                                                if ($db->affected_rows > 0) {
                                                    $counter = 1;
                                                    foreach ($rows as $record) {
                                                        ?>
                                                        <tr>
                                                            <td> <input type="checkbox" class="ckbox" id="ckb<?php echo $record['id']; ?>" name="ckb[]" value="<?php echo $record['id']; ?>">
                                                                <?php echo $counter; ?></td>
                                                            <td style="width: 10%"><?php echo ucwords($record['category_name']); ?></td>
                                                            <td style="width: 40%"><?php echo wordwrap($record['description'], 65, "<br />\n"); ?></td>
                                                            <td style="text-align: center;"><img width='100px' src='images/<?php echo ucwords($record['image']); ?>'></td>

                                                            <td class="translate"><a style="color:#fff" class="btn btn-primary btn-xs" href="add_category.php?edit=<?php echo $record['id']; ?>"><i class="fa fa-edit"></i> <?php echo get_value("edit"); ?></a></td>
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