<?php

	/*************************************************
	*	infodb.php - Author: Samnan ur Rehman        *
	*	This file is a part of MyWebSQL package      *
	*	PHP5 compatible                              *
	*************************************************/

	function processRequest(&$db) {
		if (getDbName() == '') {
			echo view('invalid_request');
			return;
		}
		
		if ($db->queryTableStatus())
			createSimpleGrid($db, __('Database summary').': ['.htmlspecialchars(getDbName()).']');
	}
	
?>