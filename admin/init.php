<?php

include "connect.php";

$template = "includes/templates/";  // Templates Directory
$language = "includes/languages/"; // Languages Directory
$functions = "includes/functions/";
$css = "layout/css/";         // CSS Directory
$js = "layout/js/";           // JS Directory


include $language. "english.php";
include $functions . "functions.php";
include $template . "header.php";

// if we type $navbar = true  ->  Then Include Navbar File
// Else then We don't need navbar in this Page

if($navbar === true){
    include  $template . "navbar.php";
}
