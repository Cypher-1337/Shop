<?php

include('connect.php');
// Routes

$tpl = "includes/templates/";       // templates directory
$lang = "includes/lang/";           // languages directory
$func = "includes/functions/";      // functions directory
$css = "layout/css/";               // css directory
$js  = "layout/js/";                // js directory



include $lang . "en.php";
include $func . "function.php";
include $tpl . "header.php";

if(!isset($noNavBar)){

    include $tpl . "navbar.php";

}

?>