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
            <div class="profile">
              <div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span><?php echo get_value("welcome");?>,</span>
                <h2>John Doe</h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
             <?php
                    sidebar('Dashboard');
                    ?>
                </div>
            </div>

            <!-- top navigation -->
            <?php top_right_bar(); ?>
            <!-- /top navigation -->

            <?php
            $sqlTotal = "select * from " . tbl_name("user") . " where usertype='trainer'";
            $rows = $db->query($sqlTotal)->fetch();
            $totalTrainer = $db->affected_rows;

            $sqlTotal = "select * from " . tbl_name("user") . " where usertype='trainee'";
            $rows = $db->query($sqlTotal)->fetch();
            $totalTrainee = $db->affected_rows;

            $sqlTotal = "select * from " . tbl_name("category");
            $rows = $db->query($sqlTotal)->fetch();
            $totalSports = $db->affected_rows;
            ?>
            <!-- page content -->
            <div class="right_col" role="main">
                <div class="row tile_count">
                    <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> <?php echo get_value('total_coach');?> </span>
                        <div class="count"><?php echo $totalTrainer; ?></div>

                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> <?php echo get_value('total_trainee');?></span>
                        <div class="count"><?php echo $totalTrainee; ?></div>

                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-clock-o"></i> <?php echo get_value('total_sports');?></span>
                        <div class="count"><?php echo $totalSports; ?></div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <style>
                                    .control-label
                                    {
                                        text-align:left!important;
                                    }
                                    .pagination .active a
                                    {
                                        color:#f00!important;   
                                        font-weight: bold;
                                    }
                                </style>
                                <h2><?php echo get_value('search'); ?></h2>
                                <div class="clearfix"></div>
                                <form class="form-horizontal" novalidate method="post" action="">
                                    <div class="item form-group">
                                        <?php
                                        $controlEditName = DefaultFieldValue($row, "user_name");
                                       // var_dump($_POST);

                                        $category = '';
                                        $trainer_no = '';
                                        $trainer_name = '';
                                        if (isset($_REQUEST['category'])) {
                                            $category = $_REQUEST['category'];
                                        }
                                        if (isset($_REQUEST['trainer_name'])) {
                                            $trainer_name = $_REQUEST['trainer_name'];
                                        }
                                        if (isset($_REQUEST['trainer_no'])) {
                                            $trainer_no = $_REQUEST['trainer_no'];
                                        }
                                        ?>

                                        <div class="col-md-3 col-sm-6 col-xs-12" style="padding-left: 0px;">
                                            <input placeholder="<?php echo get_value('coach_name'); ?>" name="trainer_name" id="trainer_name" class="form-control col-md-7 col-xs-12"  value="<?php echo $controlEditName; ?>"  type="text">
                                        </div>

                                        <?php
                                        $controlEditName = DefaultFieldValue($row, "email");
                                        ?>

                                        <div class="col-md-3 col-sm-6 col-xs-12" style="padding-left: 0px;">
                                            <input  placeholder="<?php echo get_value('coach_no'); ?>"  type="text" id="trainer_no" name="trainer_no"  value="<?php echo $controlEditName; ?>" class="form-control col-md-7 col-xs-12">
                                        </div>

                                        <?php
                                        $controlEditName = DefaultFieldValue($row, "gender");
                                        $controlName = "";
                                        ?>

                                        <div class="col-md-3 col-sm-6 col-xs-12" style="padding-left: 0px;">
                                            <select class="form-control notranslate" name="category" id="category">
                                                <option value=""><?php echo get_value('all'); ?></option>
                                                <?php
                                                $sqlSearch = "SELECT * FROM " . tbl_name("category") . "";
                                                $rows = $db->query($sqlSearch)->fetch();
                                                if ($db->affected_rows > 0) {
                                                    foreach ($rows as $record) {
                                                        if ($category == $record['id']) {
                                                            echo "<option value='" . $record['id'] . "' selected>" . $record['category_name'] . "</option>";
                                                        } else {
                                                            echo "<option value='" . $record['id'] . "' >" . $record['category_name'] . "</option>";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3" style="padding-left: 0px;">
                                            <button id="send" type="submit" class="btn btn-success"><?php echo get_value('search'); ?></button>

                                        </div>
                                    </div>
                                </form>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <div class="row">
                                    <?php
                                    $results_per_page = 10;
                                    if (isset($_GET["page"])) {
                                        $page = $_GET["page"];
                                    } else {
                                        $page = 1;
                                    };
                                    $start_from = ($page - 1) * $results_per_page;
                                    $where = '';
                                    $sqlSearch = "SELECT * FROM " . tbl_name("user") . " tu INNER JOIN " . tbl_name("trainer_profile") . " tp ON (tp.user_id = tu.id AND tu.usertype='trainer') WHERE 1 LIMIT $start_from, " . $results_per_page;

                                    if (!empty($category)) {
                                        if (strlen($where) > 0) {
                                            $where .= " and sport_id = " . $db->escape($category);
                                        } else {
                                            $where .= " sport_id = " . $db->escape($category);
                                        }
                                    }
                                    if (!empty($trainer_name)) {
                                        if (strlen($where) > 0) {
                                            $where .= " and user_name = '" . $db->escape($trainer_name) . "'";
                                        } else {
                                            $where .= "  user_name = '" . $db->escape($trainer_name) . "'";
                                        }
                                    }
                                    if (!empty($trainer_id)) {
                                        if (strlen($where) > 0) {
                                            $where .= " and tu.id = " . $db->escape($trainer_id);
                                        } else {
                                            $where .= " tu.id = " . $db->escape($trainer_id);
                                        }
                                    }
                                    if (strlen($where) > 0) {
                                        $sqlSearch = "SELECT * FROM " . tbl_name("user") . " tu INNER JOIN " . tbl_name("trainer_profile") . " tp ON (tp.user_id = tu.id AND tu.usertype='trainer') WHERE " . $where . " LIMIT $start_from, " . $results_per_page;
                                    }
//echo $sqlSearch;
                                    $rows = $db->query($sqlSearch)->fetch();
                                    if ($db->affected_rows > 0) {
//                                        var_dump($rows);
                                        
                                       
                                        
                                        foreach ($rows as $record) {
                                             $image = "bgimage.png";
                                        if(strlen($record['photo'])>0)
                                        {
                                            $image = "images/".$record['photo'];
                                        }
                                            ?>
                                            <div class="col-md-55">
                                                <div class="thumbnail">
                                                    <div class="image view view-first">
                                                        <img style="width: 100%; display: block;" src="<?php echo $image; ?>" alt="image" />
                                                        <div class="mask">

                                                        <!-- <div class="tools tools-bottom">
                                                            <a href="#"><i class="fa fa-link"></i></a>
                                                            <a href="#"><i class="fa fa-pencil"></i></a>
                                                            <a href="#"><i class="fa fa-times"></i></a>
                                                        </div> -->
                                                        </div>
                                                    </div>
                                                    <div class="caption">
                                                        <p class="notranslate"><?php echo $record['user_name']; ?></p>
                                                        <p class="notranslate"><?php echo $record['email']; ?></p>
                                                        <a href="#viewdetail" data-toggle="modal" onclick="viewdetail('<?php echo $record['id']; ?>')" class="btn btn-success btn-xs sendNotification">View Details</a>
                                                        <div class="row">

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12" style="padding-left:20px;">
                                                No record found    
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="clearfix"></div>
                                    <?php
                                    //$sql = "SELECT COUNT(id) AS total FROM " . tbl_name("user") . " where usertype='trainer' ";
                                    $sql = "SELECT COUNT(tu.id) AS total FROM " . tbl_name("user") . " tu INNER JOIN " . tbl_name("trainer_profile") . " tp ON (tp.user_id = tu.id AND tu.usertype='trainer') WHERE 1";
                                    if (!empty($where)) {
                                        $sql = "SELECT COUNT(tu.id) AS total FROM " . tbl_name("user") . " tu INNER JOIN " . tbl_name("trainer_profile") . " tp ON (tp.user_id = tu.id AND tu.usertype='trainer') WHERE " . $where;
                                    }

                                    $rows = $db->query($sql)->fetch();

                                    $total_pages = ceil($rows[0]['total'] / $results_per_page);
                                    //echo $total_pages;
                                    ?>

                                    <div class="dataTables_paginate paging_simple_numbers" id="datatable-checkbox_paginate">
                                        <ul class="pagination">
                                            <?php
                                            $append = '';
                                            if (!empty($category)) {
                                                if (strlen($append) > 0) {
                                                    $append .= "&category=" . $db->escape($category);
                                                } else {
                                                    $append .= "category=" . $db->escape($category);
                                                }
                                            }
                                            if (!empty($trainer_name)) {
                                                if (strlen($append) > 0) {
                                                    $append .= "&trainer_name=" . $db->escape($trainer_name);
                                                } else {
                                                    $append .= "trainer_name=" . $db->escape($trainer_name);
                                                }
                                            }
                                            if (!empty($trainer_id)) {
                                                if (strlen($append) > 0) {
                                                    $append .= "&trainer_no=" . $db->escape($trainer_id);
                                                } else {
                                                    $append .= "trainer_no=" . $db->escape($trainer_id);
                                                }
                                            }
                                            if (strlen($append) > 0) {
                                                $append = "&" . $append;
                                            }
                                            for ($index = 1; $index <= $total_pages; $index++) {
                                                if ($index == $page) {
                                                    ?>
                                                    <li class="paginate_button active">
                                                        <a style="margin:0px 5px;" href="#" aria-controls="datatable-checkbox" data-dt-idx="<?php echo $index; ?>" tabindex="0"><?php echo $index; ?></a></li>
        <?php
    } else {
        ?>
                                                    <li class="paginate_button "><a  style="margin:0px 5px;" href="dashboard.php?page=<?php echo $index . $append; ?>" aria-controls="datatable-checkbox" data-dt-idx="<?php echo $index; ?>" tabindex="0"><?php echo $index; ?></a></li>
                                                    <?php
                                                }
                                            }
                                            ?>


                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->

            <div id="viewdetail" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                    
                        <form action="#" class="form-horizontal" id="validation-form" method="post">
                            
                            <div class="modal-body" id="moredetail">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                function viewdetail(id)
                {
                    $.ajax({
                        type: "POST",
                        url: "ajax/moredetail.php",
                        data: {querystring: id},
                        cache: false,
                        success: function (data)
                        {
                            if (data)
                            {
                                $('#moredetail').replaceWith($('#moredetail').html(data));
                            }
                            else
                            {

                            }
                        }
                    });
                }
            </script>
<?php
require_once 'footer.php';
?>