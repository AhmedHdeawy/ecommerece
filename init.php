<?php

// Error Reporting

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include "admin/connect.php";

$sessionUser = '';
if(isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}

$template = "includes/templates/";  // Templates Directory
$language = "includes/languages/"; // Languages Directory
$functions = "includes/functions/";
$css = "layout/css/";         // CSS Directory
$js = "layout/js/";           // JS Directory


include $language. "english.php";
include $functions . "functions.php";
include $template . "header.php";
