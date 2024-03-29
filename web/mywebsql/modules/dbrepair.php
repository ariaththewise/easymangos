<?php

	/**********************************************
	*	dbrepair.php - Author: Samnan ur Rehman    *
	*	This file is a part of MyWebSQL package    *
	*	PHP5 compatible                            *
	***********************************************/

	function processRequest(&$db) {
	
		if (isset($_POST['optype']) && is_array(v($_POST['tables'])) ) {
			checkTables($db);
		}
		else {
			$db_tables = $db->getTables();
			$replace = array('TABLELIST' => json_encode($db_tables)
							);
			echo view('dbrepair', $replace);
		}
	}
	
	function checkTables(&$db) {
		$type = v($_POST['optype']);

		$options = array('skiplog' => v($_POST['skiplog']) == 'on' ? TRUE : FALSE);
		$options['checktype'] = v($_POST['checktype']);
		$options['repairtype'] = is_array(v($_POST['repairtype'])) ? v($_POST['repairtype']) : array();
		
		include("lib/tablechecker.php");
		$checker = new tableChecker($db);
		$checker->setOperation($type);
		$checker->setTables(v($_POST['tables']));
		$checker->setOptions($options);
		
		$results = array();
		if ($checker->runCheck())
			$results = $checker->getResults();
		
		$replace = array('RESULTS' => json_encode($results)
							);
		
		echo view('dbrepair_results', $replace);
	}
	
?>