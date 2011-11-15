<?php

	/***********************************************
	*  queryall.php - Author: Samnan ur Rehman     *
	*  This file is a part of MyWebSQL package     *
	*  PHP5 compatible                             *
	***********************************************/

	function processRequest(&$db) {
		$query = v($_REQUEST["query"]);
		
		if (!$query) {
			createErrorGrid($db, $query);
			return;
		}
		
		//Session::set('select', 'query', $query);

		$temp = tmpfile();
		fwrite($temp, $query);
		fseek($temp, 0);

		include("lib/sqlparser.php");
		$parser = new sqlParser($db);
		$parser->collectStats();
		if (!$parser->parseFile($temp))
			createErrorGrid($db, $parser->getLastQuery(), $parser->getExecutedQueries(), $parser->getRowsAffected());
		else {
			$stats = $parser->getStats();
			if ($stats->dbAltered)
				Session::set('db', 'altered', true);
			createInfoGrid($db, $query, $parser->getExecutedQueries(), $parser->getRowsAffected(), false, $parser->getExecutedTime());
		}
	}
?>