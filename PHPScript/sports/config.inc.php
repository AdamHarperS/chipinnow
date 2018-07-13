<?php
session_start();
//database server
define('DB_SERVER', "localhost");

//database login name
define('DB_USER', "root");
//database login password
define('DB_PASS', "");

//database name
define('DB_DATABASE', "sports");

//table name prefix
define('TB_PREFIX', "tbl_");


define("PAGESIZE",8);
define("PAGERSIZE",8);
define("DEBUG_MODE", 0);

define('SITEURL', 'http://localhost/sports/');
define('IMAGEURL',SITEURL);
define('SEO_URL',TRUE);
//define('SEO_URL',false);
define("MAILUSER", 'freak7vi@sports.freaktemplate.com');	
define("FROMMAIL", 'freak7vi@sports.freaktemplate.com');	
define("MAILUSERPWD", 'SU.dWm99bRUF');	
define("MAILPORT", '465');	
define('HOST', 'cp-in-9.webhostbox.net');

define('SITE_TITLE',' red Coller Services');

/*
    Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);
require_once("function.php");
//require_once 'SecureSessionHandler.php';
require_once 'class.database.php';

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

function ShowQuery()
{
    if(DEBUGMODE)
    {
        echo func_get_arg(0);
    }
}
$conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_DATABASE);


?>