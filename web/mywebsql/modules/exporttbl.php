<?php

	/**********************************************
	*	exporttbl.php - Author: Samnan ur Rehman   *
	*	This file is a part of MyWebSQL package    *
	*	PHP5 compatible                            *
	***********************************************/

	function processRequest(&$db) {
		$tableName = v($_REQUEST['table']);
		if ($tableName) {
			$replace = array('TABLENAME' => $tableName);
			echo view('exporttbl', $replace);
		}
		else
			echo view('invalid_request', array());
	}

?>