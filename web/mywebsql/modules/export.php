<?php

	/**********************************************
	*	export.php - Author: Samnan ur Rehman      *
	*	This file is a part of MyWebSQL package    *
	*	PHP5 compatible                            *
	***********************************************/

	function processRequest(&$db) {
		$db_tables = $db->getTables();
		$db_views = $db->getViews();
		$db_procedures = $db->getProcedures();
		$db_functions = $db->getFunctions();
		$db_triggers = $db->getTriggers();
		$db_events = $db->getEvents();
		/*foreach($tbl as $t)
			$tableList .= "<option selected=\"selected\" value='$t'>$t</option>\n";
		*/
		$replace = array('TABLELIST' => json_encode($db_tables),
						'VIEWLIST' => json_encode($db_views),
						'PROCLIST' => json_encode($db_procedures),
						'FUNCLIST' => json_encode($db_functions),
						'TRIGGERLIST' => json_encode($db_triggers),
						'EVENTLIST' => json_encode($db_events)
						);
		echo view('export', $replace);
	}

?>