<?php
    /*
    ===============================================
    === File Categories =>
                Manage | Edit | Update | Add | Insert | Delete | Stats

    ===============================================
    */
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; //- IF Condition ? True : False

    if ($do == 'Manage') {
        echo 'welcome you are in manage page';
        echo '<a href="?do=Insert">Add New Category +</a>';

    } elseif ($do == 'Add') {
        echo 'welcome you are in Add page';

    } elseif ($do == 'Insert') {
        echo 'welcome you are in Insert page';

    } else {
        echo 'Error There/s No page With this name';
    }
    
    
?>