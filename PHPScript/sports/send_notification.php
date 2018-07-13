<?php
require_once 'header.php';
?>
<style>
    .no-js #loader {  display: none;  }
    .js #loader { display: block; position: absolute; left: 100px; top: 0; }
    .se-pre-con
    {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url(images/gifloading.gif) center no-repeat #fff;
        opacity: 0.7;
    }
</style>
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
                    sidebar('Notification');
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
                            <h3><?php echo get_value("Notification"); ?> </h3>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <?php
                                if (isset($_POST['ckb'])) {
                                    if (!empty($_POST['ckb'])) {

                                        $sqlDelete = " DELETE FROM " . tbl_name("notifications") . " WHERE noti_id in (" . join(",", $_POST['ckb']) . ")";
                                        $rows = $db->query($sqlDelete)->execute();
                                    }
                                }
                                ?>
                                <div class="x_content">
                                  
                                    <form name="frmTrainee"  id="frmTrainee" method="post" action="send_notification.php">
                                        <a href="add_notification.php" id="english" class="btn btn-success pull-right"><?php echo get_value('add_notification'); ?></a>
                                        <a href="#" id="btnDelete" class="btn btn-warning pull-right"><?php echo get_value('delete'); ?></a>
                                        <!-- <a href="#" id="btnDelete" class="btn btn-warning"><?php echo get_value('delete'); ?></a> -->
                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="50"> <input type="checkbox" id="select_all"> S.No</th>
                                                    <th><?php echo get_value("content"); ?></th>
                                                    <th width="100"><?php echo get_value("date"); ?></th>
                                                    <th width="20"><?php echo get_value("send"); ?></th>
                                                    <th width="100"><?php echo get_value("action"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="notranslate">
                                                <?php
                                                $sql = "select * from tbl_notifications order by `noti_id` DESC";
                                                $rows = $db->query($sql)->fetch();
                                                if ($db->affected_rows > 0) {
                                                    $counter = 1;
                                                    foreach ($rows as $record) {
                                                        ?>
                                                        <tr>
                                                            <td><input type="checkbox" class="ckbox" id="ckb<?php echo $record['noti_id']; ?>" name="ckb[]" value="<?php echo $record['noti_id']; ?>">
                                                                <?php echo $counter; ?></td>
                                                            <td><?php echo ucwords($record['content']); ?></td>
                                                            <td><?php echo date("d-m-Y",$record['updated_at']); ?></td>
                                                            <td style="text-align: center;"><a href="javascript:Send_notification(<?php echo $record['noti_id'];?>);" style="color:#2A3F54;"><i class="fa fa-bell fa-lg"></i></a></td>
                                                            <td class="translate"><a style="color:#fff" class="btn btn-primary btn-xs" href="add_notification.php?edit=<?php echo $record['noti_id']; ?>"><i class="fa fa-edit"></i> <?php echo get_value("edit"); ?></a>
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
            <div class="se-pre-con" hidden id="loading"></div>

            <script type="text/javascript">
                function Send_notification(id){
                    $.ajax({
                            url : "Ajaxnotification.php",
                            type : "post",
                            data : {"id":id},
                            ajaxStart : startAjax(),
                            cache: false,
                            success: function(html) {
                                var json=$.parseJSON(html);
                                if (json['android']==0 && json['ios']==0) 
                                {
                                    $("#loading").hide();
                                    alert("!!! Something went wrong !");
                                } else {
                                    $("#loading").hide();
                                    
                                    alert("!!! Notification Sent \n Android" + json ['android']+" \n IOS" + json ['ios']);
                                }
                            //alert(html);

                            }
                            // success : function(res){
                            //     console.log(res);
                            //     if(res == true){
                            //         alert("Notification Sent successfully.");
                            //     }else{
                            //         alert("Notification Not sent.!");
                            //     }
                            // ajaxStop : stopAjax();
                            // }
                    });
                }

                function startAjax(){
                    $("#loading").show();
                }
                function stopAjax(){
                   $("#loading").hide();
                }
            </script>