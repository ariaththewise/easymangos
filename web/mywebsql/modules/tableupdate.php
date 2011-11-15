<?php

	/***********************************************
	*  tableupdate.php - Author: Samnan ur Rehman  *
	*  This file is a part of MyWebSQL package     *
	*  PHP5 compatible                             *
	***********************************************/

	function processRequest(&$db) {
		$tbl = $_REQUEST["name"];

		$str = $db->getUpdateStatement($tbl);
			
		if ($str === false)
			createErrorGrid($db, $db->getLastQuery());
		else {
			print "<div id='results'>".htmlspecialchars($str)."</div>";
			print "<script type=\"text/javascript\" language='javascript'> parent.transferQuery(); </script>\n";
		}
	}
?>