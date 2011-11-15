<?php

	/*************************************************
	*	showcreate.php - Author: Samnan ur Rehman     *
	*	This file is a part of MyWebSQL package       *
	*	PHP5 compatible                               *
	*************************************************/

	function processRequest(&$db) {
		Session::del('select', 'result');
		Session::del('select', 'pkey');
		Session::del('select', 'ukey');
		Session::del('select', 'mkey');
		Session::del('select', 'unique_table');
		
		Session::set('select', 'result', array());
		$extraMsg = '';

		$type = $_REQUEST["id"];
		$name = $_REQUEST["name"];
		
		$cmd = $db->getCreateCommand($type, $name);
		$cmd = sanitizeCreateCommand($type, $cmd);
		$tm = $db->getQueryTime();
		$sql = $db->getLastQuery();
		$sql = preg_replace("/[\n\r]/", "<br/>", htmlspecialchars($sql));

		$replace = array('TYPE' => $type,
								'NAME' => $name,
								'COMMAND' => $cmd,
								'TIME' => $tm,
								'SQL' => $sql,
								'MESSAGE' => $extraMsg
							);

		echo view('showcreate', $replace);
	}

?>