<?php
    include 'connect.php'; //- Connction To DataBases

    // Routes
    $tpl  = 'includes/template/';  //- Teamplet Directory
    $func = 'includes/function/'; //- Function Directory
    $css  = 'layout/css/';  //- Css Directory
    $js   = 'layout/js/';  //- JS Directory

    //- Include The Importat File
    include $func . 'function.php';
    include $tpl . 'header.php';

    //- Include The Navbar On All Page Expect The One With $noNavbar Vairable
    if (!isset($noNavbar)) {include $tpl . 'navbar.php';}
    

?>