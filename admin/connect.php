<?php
    $dsn    = 'mysql:host=localhost;dbname=shop'; //- Data Sourse Name
    $user   = 'root'; //- User To Connect
    $pass   = '';    //- Password Of This User
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );

    try {
        $con = new PDO($dsn, $user, $pass, $option); //- Start A New Connction With PDO Class 
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo 'Failed connect' . $e->getMessage();
    }
?>