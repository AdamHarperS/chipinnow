<?php
require_once 'config.inc.php';

function sidebar($selected) {
    $menuList = array(
        "Dashboard" => "dashboard.php",
        "Course" => array("Add Course" => "add_course.php", "View Course" => "view_course.php"),
        "Category" => array("Add Category" => "add_category.php", "View Category" => "view_category.php"),
        //"Album" => array("Add Album" => "album.php", "Album list" => "view_album.php", "View Images" => "add_images.php"),
        "Coach" => array("Add Coach" => "add_trainer.php", "View Coach" => "view_trainer.php"),
        "Trainee" => array("Add Trainee" => "add_trainee.php", "View Trainee" => "view_trainee.php"),
        "View Rating" => "view_rating.php",
        "Request Order" => "request_order.php",
        "Response Order" => "response_order.php",
        "Notification" => array("Send Notification" => "send_notification.php", "FireBase Key" => "add_firebasekey.php"),
        "Complaint" => "complaint.php",
        //"Bank Info" => array("Add Bank Info" => "add_bank_info.php", "View Bank Info" => "view_bank_info.php"),
//        "Change Password" => "change_pwd.php",
        "About us" => "aboutus.php",
        "Logout" => "logout.php",
    );
    $menuListIcon = array(
        "Dashboard" => "fa-home",
        "Coach" => "fa-user",
        "Trainee" => "fa-user",
        "Category" => "fa-folder",
        "Add Coach" => "fa-user",
        "View Coach" => "fa-user",
        "Add Trainee" => "fa-user",
        "View Trainee" => "fa-user",
        "Add Category" => "fa-user",
        "View Category" => "fa-user",
        "View Rating" => "fa-star",
        "Course" => "fa-book",
        "Add Course" => "fa-book",
        "View Course" => "fa-table",
        "Request Order" => "fa-table",
        "Response Order" => "fa-table",
        "Notification" => "fa-bell",
        "Complaint" => "fa-comments",
        // "Bank Info" => "fa-user",
        // "Add Bank Info" => "fa-user",
        // "View Bank Info" => "fa-user",
//        "Change Password" => "change_pwd.php",
        "Logout" => "fa-sign-out",
        "About us" => "fa-info-circle",
        //"Album" => "fa-cube",
    );
    ?>
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
            <!--                <h3>General</h3>-->
            <h3>&nbsp;</h3>
            <ul class="nav side-menu">
                <?php
                foreach ($menuList as $key => $value) {
                    $active = "";

                    $menuText = $key;
                    if (is_array($value)) {
                        $menuText = $key;
                        // var_dump($value);
                        if (array_key_exists($selected, $value)) {
                            $active = "";
                            ?>
                            <li class="<?php echo $active; ?>">
                                <a><i class="fa fa-desktop"></i> <?php echo get_value(strtolower($menuText)); ?> <span class="fa fa-chevron-down"></span></a>

                                <ul class="nav child_menu">
                                    <?php
                                    foreach ($value as $keyInner => $valueInner) {
                                        if ($selected == $keyInner) {
                                            echo "<li class=''>";
                                        } else {
                                            echo "<li>";
                                        }
                                        echo "<a href='$valueInner'>
                
                                        " . $keyInner . "</a>
                                </li>";
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        } else {
                            ?>
                            <li class="<?php echo $active; ?>">
                                <a><i class="fa <?php echo $menuListIcon[$key]; ?>"></i> <?php echo get_value(strtolower($menuText)); ?> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <?php
                                    foreach ($value as $keyInner => $valueInner) {
                                        if ($selected == $keyInner) {
                                            echo "<li>";
                                        } else {
                                            echo "<li>";
                                        }
                                        echo "<a href='$valueInner'>
                            
                                        " . $keyInner . "</a>
                                </li>";
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        }
                    } else {
                        $active = "";
                        if ($selected == $key) {
                            $active = " active ";
                        }

                        $keyword = str_replace(" ", "_", strtolower($key));
                        ?>
                        <li class="<?php echo $active; ?>">
                            <a href="<?php echo $value; ?>"><i class='fa <?php echo $menuListIcon[$key]; ?>'></i>

                                <span class="title"><?php echo get_value($keyword); ?></span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>


        </div>
    </div>
    <center>
        <div class="row" style="margin-right: 20%;margin-top: -15%;">
            <div id="google_translate_element"></div>

            <script type="text/javascript">
                function googleTranslateElementInit() {
                new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
                }
            </script>

            <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        </div>
    </center>

    </div>
    <?php
}
?>