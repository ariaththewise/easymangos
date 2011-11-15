<?php

	/*********************************************
	*  infovars.php - Author: Samnan ur Rehman   *
	*  This file is a part of MyWebSQL package   *
	*  PHP5 compatible                           *
	*********************************************/

	function processRequest(&$db) {
		displayVariableList($db);
	}
	
	function displayVariableList(&$db) {
		if ($db->query("show variables")) {
			createSimpleGrid($db, __('Server Variables'));		}
	}
	
?>