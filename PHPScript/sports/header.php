<?php
require_once 'common.php';

if(!isset($_SESSION['user_id']))
{
    header("Location:index.php");
}


$available_langs = array('en', 'ar');
// Set our default language session
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}
if (isset($_GET['lang']) && $_GET['lang'] != '') {
    // check if the language is one we support
    if (in_array($_GET['lang'], $available_langs)) {
        $_SESSION['lang'] = $_GET['lang']; // Set session
    }
}

$sql = "SELECT * FROM tbl_lang";
$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {

    foreach ($rows as $row) {
        if ($_SESSION['lang'] == "ar") {
            $lang[$row["keyword"]] = $row["arabic"];
        }
        if ($_SESSION['lang'] == "en") {
            $lang[$row["keyword"]] = $row["english"];
        }
    }
    $_SESSION["lang_keyword"] = $lang;
}
?>
<style type="text/css">
    .goog-logo-link {
   display:none !important;
}
.goog-te-gadget{
   color: transparent !important;
}
.goog-te-gadget .goog-te-combo{
   color: black !important;
}
</style>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sports Application | </title>

        <!-- Bootstrap -->
        <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="build/css/custom.min.css" rel="stylesheet">
        
        <link rel="stylesheet" type="text/css" href="vendors/datatables/dataTables.bootstrap.min.css"/>
                <link rel="stylesheet" type="text/css" href="vendors/datatables/plugins/buttons.dataTables.min.css"/>
                <link rel="stylesheet" type="text/css" href="wickedpicker.min.css"/>
                <link href="css/bootstrap-datetimepicker.css" rel="stylesheet" media="screen">
    </head>
