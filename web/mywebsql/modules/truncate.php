<?php

	/***********************************************
	*  truncate.php - Author: Samnan ur Rehman     *
	*  This file is a part of MyWebSQL package     *
	*  PHP5 compatible                             *
	***********************************************/

	function processRequest(&$db) {
		$type = v($_REQUEST["id"]);
		$name = v($_REQUEST["name"]);
		
		if (!$name) {
			createErrorGrid($db, '');
			return;
		}
		
		if ($db->truncateTable($name)) {
			createInfoGrid($db, $db->getLastQuery());
		}
		else
			createErrorGrid($db, $db->getLastQuery());
	}
?>