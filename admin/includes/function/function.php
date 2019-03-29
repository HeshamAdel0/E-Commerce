<?php
    /*
         *** Page Title Function V1.0
        *** Title Function That Echo The Page Title In Case the Page
        *** Has The Variable $PageTitle And Echo Defult Title For Other Page
    */
    function getTitlePage() {
        global $PageTitle;

        if (isset($PageTitle)) {
            echo $PageTitle; 
        } else {
            echo 'Default';
        }
    }

    /*
        *** Redirect Function V2.0
        *** This Function Accept Parameters
        *** $errorMsg = Echo The Message [ Error | Succsess | Warning]
        *** $url = The Link You Want Redirect to
        *** $second = Second Befor Redirecting
    */

    function redirectHome($theMsg, $url = null, $second = 5) {
        if ($url === null) {
            $url = 'index.php';
            $link = 'Home Page';
        } else {

            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                $url = $_SERVER['HTTP_REFERER'];
                $link = 'Previous Page';
            } else {
                $url = 'index.php';
                $link = 'Home Page';
            }
        }
        echo $theMsg;
        echo "<div class='alert alert-info'>You Will Redirect To $link After $second Seconds.</div>";

        header("refresh:$second;url=$url");

        exit();
    }

    /*
        *** Check Item Function V1.0
        *** Function To Check Item In Databases
        *** Function Accept Parameters
        *** $select = The Item To Select [ Example: Item , Usre]
        *** $from = The Table To Select From [ Example: Users, Item]
        *** $value = The Value Of Select [Example: hesham , box , phone]
    */
    function checkItem($select, $from, $value) {
        global $con;

        $statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statment->execute(array($value));
        $count = $statment->rowCount();

        return $count;
    }

    /*
        *** Count Number Of Item Function V1.0
        *** Function To Count Number Of Rows
        *** Function Accept Parameters
        *** $item = The Item To Count
        *** $table = The Table To Choose From
    */
    function countItems($item, $table) {
        global $con;

        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        
        $stmt2->execute();
        
        return $stmt2->fetchColumn();
    }

    /*
        *** Get Latest Records Function v1.0
        *** Function To Get Latest Items From Database [ Users, Items, Comments ]
        *** $select = Field To Select
        *** $table = The Table To Choose From
        *** $order = The Desc Ordering
        *** $limit = Number Of Records To Get
    */
    function getLatest($select, $table, $order, $limit = 4) {
        
        global $con;

		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

		$getStmt->execute();

		$rows = $getStmt->fetchAll();

		return $rows;;
    }

    /*
	** Get All Function v2.0
	** Function To Get All Records From Any Database Table
	*/

	function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {

		global $con;

		$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

		$getAll->execute();

		$all = $getAll->fetchAll();

		return $all;

	}

?>