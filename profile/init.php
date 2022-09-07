<?php

ini_set('display_errors', 'On');
error_reporting();

include '../admin/connect.php';


$session_user = '';

if(isset($_SESSION['shop_user'])){
    $session_user = $_SESSION['shop_user'];
}
// Routes

$tpl  = "../includes/templates/";       // templates directory
$lang = "../includes/lang/";           // languages directory
$func = "../includes/functions/";      // functions directory
$css  = "../layout/css/";               // css directory
$js   = "../layout/js/";                // js directory


include $func . "function.php";
include $tpl . "header.php";
include $tpl .  "navbar.php";

?>