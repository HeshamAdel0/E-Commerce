<?php

    include 'admin/connect.php'; //- Connction To DataBases

    ini_set('display_errors', 'On');
	error_reporting(E_ALL);

    $sessionUser = '';
	
	if (isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
	}

    // Routes
    $tpl  = 'includes/template/';  //- Teamplet Directory
    $func = 'includes/function/'; //- Function Directory
    $css  = 'layout/css/';  //- Css Directory
    $js   = 'layout/js/';  //- JS Directory

    //- Include The Importat File
    include $func . 'function.php';
    include $tpl . 'header.php';
?>