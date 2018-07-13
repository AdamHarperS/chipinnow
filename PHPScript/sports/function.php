<?php

function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = uniqid();
    }
}
function showArray() {
    if (isset($_POST)) {
        foreach ($_POST as $key => $value) {
            if ($key == "process_name" || $key == "csrf_token")
                continue;
            echo "<br>\$data['" . trim($key) . "'] = \$db->escape(\$_POST[\"" . trim($key) . "\"]);";
        }
    }
}
function get_value($name) {
    $name = str_replace(" ","_",$name);
    return ucwords($_SESSION["lang_keyword"][$name]);
}

function top_right_bar()
{
    ?>
          <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
<!--                    <img src="images/img.jpg" alt="">-->
                    <?php echo ucwords($_SESSION['user_name']);?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
<!--                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>-->
                    <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                
              </ul>
            </nav>
          </div>
        </div>
        <?php
}

function show_error($errorMessage) {

    if (count($errorMessage) > 0) {
        $color = "red";
        if (isset($errorMessage["status"]) && $errorMessage["status"] == "success") {
            $color = "blue";
        }
        if (isset($errorMessage["message"])) {
            ?>
            <div id="status_msg" class="portlet box <?php echo $color; ?>">
                <div class="portlet-title">
                    <div class="caption">
            <?php echo $errorMessage["message"]; ?>
                    </div>
                </div>
            </div>        
            <?php
        }
    }
}

function create_slug($string) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    $slug = strtolower($slug);
    return $slug;
}


function showValidation() {
    if (isset($_POST)) {
        echo "rules: {";


        foreach ($_POST as $key => $value) {
            if ($key == "process_name" || $key == "csrf_token" || $key == "txtid")
                continue;
            if (strstr($key, 'email')) {
                
            }
            echo "<br>" . $key . ": {<br>
                     required: true";
            if (strstr($key, 'email')) {
                echo ",<br>email: true";
            }
            echo "<br> },";
        }
        echo "<br>}, ";
    }
}

//Default Field Value
function DefaultFieldValue($row, $fieldName) {
    if (isset($row[$fieldName])) {
        return $row[$fieldName];
    }
    return "";
}

function getfilelist($name, $path, $extension) {
    $list = glob($path . $extension);
    var_dump($list);
    foreach ($list as $key => $value) {
        echo "<br>case '" . pathinfo($value, PATHINFO_FILENAME) . "':";
    }
}

function shorten_string($string, $wordsreturned) {
    $retval = $string;  //    Just in case of a problem
    $array = explode(" ", $string);
    if (count($array) <= $wordsreturned)
    /*  Already short enough, return the whole thing
     */ {
        $retval = $string;
    } else
    /*  Need to chop of some words
     */ {
        array_splice($array, $wordsreturned);
        $retval = implode(" ", $array) . " ...";
    }
    return $retval;
}

function deleteMethod($tablename, &$db) {

    if (isset($_POST["del"])) {

        $record_id = strip_tags($_POST["del"]);
        if (preg_match('/^[0-9,]*$/', $record_id)) {

            $sql = "delete from " . $tablename . " where id in (" . $record_id . ")";
            //           echo $sql;

            $resultDelete = $db->query($sql)->execute();
            if ($db->error == '') {
                echo "1";
            } else {
                echo "0";
            }
        }
    }
}

function deleteMethod_Image($tablename, $imgpath, $fieldname, &$db, $list_newsize = array()) {

    if (isset($_POST["del"])) {

        $record_id = strip_tags($_POST["del"]);

        if (preg_match('/^[,0-9]*$/', $record_id)) {


            $sql = "select " . $fieldname . " from " . $tablename . " where id in (" . $record_id . ")";
            //echo $sql;

            $rows = $db->query($sql)->fetch();
            //echo $rowselect["photo"];
            if ($db->affected_rows > 0) {
                foreach ($rows as $rowselect) {
                    if (!empty($rowselect[$fieldname])) {
                        if (file_exists($imgpath . $rowselect[$fieldname])) {
                            unlink($imgpath . $rowselect[$fieldname]);
                        }
                        //Multiple Sizes
                        if (count($list_newsize) > 0) {
                            foreach ($list_newsize as $key => $value) {
                                list($filename, $extension) = explode(".", $rowselect[$fieldname]);
                                $newName = $filename . "-" . $key . "x" . $value . "." . $extension;
                                if (file_exists($imgpath . $newName)) {
                                    unlink($imgpath . $newName);
                                }
                            }
                        }
                    }
                    $sql = "delete from " . $tablename . " where id in (" . $record_id . ")";

                    $resultDelete = $db->query($sql)->execute();

                    if ($db->error == '') {
                        echo "1";
                    } else {
                        echo "0";
                    }
                }
            } else {
                echo "0";
            }
        }
    }
    //header("location:show_data.php");
}

function check_duplicate($tablename, $data, $db) {

    $where = "";

    if (count($data) == 0) {
        return -1;
    }
    $sql = "select * from " . $tablename;
    foreach ($data as $key => $value) {
        $clause[] = "$key = '" . $value . "'";
    }
    if (count($clause) > 0) {
        $where = implode(" and ", $clause);
        $sql = $sql . " where " . $where;
    }
    //echo "<br>" . $sql."<br>";
    $rows = $db->query($sql)->execute();

//    echo $db->affected_rows;
//    exit();
    return $db->affected_rows;
    //return $db->affected_rows;
}

function get_session(&$action_url, &$row) {
    $errorMessage = "";
    //var_dump( $_SESSION );
    //echo "action url " . pathinfo( $action_url, PATHINFO_BASENAME );

    if (isset($_SESSION["last_data"])) {
        $pageurl = '';

        $url_parts = parse_url($action_url);
        if (isset($url_parts["path"])) {
            $pageurl = $url_parts["path"];
        }
        if (trim($_SESSION["last_data"]["page"]) == trim($pageurl)) {
            $row = $_SESSION["last_data"]["data"];
            //$errorMessage	 = "Record is already exist";
            $errorMessage = array("status" => $_SESSION["last_data"]["status"], "message" => $_SESSION["last_data"]["message"]);
            if( $_SESSION["last_data"]["status"]=="success")
            {
                $row = array();
            }
        } else {
            unset($_SESSION["last_data"]);
        }
    }
    return $errorMessage;
}

function tbl_name($tablename) {
    return TB_PREFIX . $tablename;
}

/* takes the input, scrubs bad characters */

function generate_seo_link($input, $replace = '-', $remove_words = true, $words_array = array()) {

    $words_array = array('a', 'and', 'the', 'an', 'it', 'is', 'with', 'can', 'of', 'why', 'not');

    //make it lowercase, remove punctuation, remove multiple/leading/ending spaces
    $return = trim(ereg_replace(' +', ' ', preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($input))));

    //remove words, if not helpful to seo
    //i like my defaults list in remove_words(), so I wont pass that array
    if ($remove_words) {
        $return = remove_words($return, $replace, $words_array);
    }

    //convert the spaces to whatever the user wants
    //usually a dash or underscore..
    //...then return the value.
    return str_replace(' ', $replace, $return);
}

/* takes an input, scrubs unnecessary words */

function remove_words($input, $replace, $words_array = array(), $unique_words = true) {
    //separate all words based on spaces
    $input_array = explode(' ', $input);

    //create the return array
    $return = array();

    //loops through words, remove bad words, keep good ones
    foreach ($input_array as $word) {
        //if it's a word we should add...
        if (!in_array($word, $words_array) && ($unique_words ? !in_array($word, $return) : true)) {
            $return[] = $word;
        }
    }

    //return good words separated by dashes
    return implode($replace, $return);
}
?>
