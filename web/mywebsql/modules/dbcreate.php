<?php

	/**********************************************
	*	dbcreate.php - Author: Samnan ur Rehman   *
	*	This file is a part of MyWebSQL package   *
	*	PHP5 compatible                           *
	**********************************************/

	function processRequest(&$db) {
		Session::del('select', 'result');
		Session::del('select', 'pkey');
		Session::del('select', 'ukey');
		Session::del('select', 'mkey');
		Session::del('select', 'unique_table');
		
		Session::set('select', 'result', array());

		$dbName = $_REQUEST["name"];
		$dbSelect = $_REQUEST["query"];

		$sql = '';
		if (!$db->createDatabase($dbName))
			createErrorGrid($db);
		else {
			$redirect = '0';
			if ($dbSelect) {
				Session::set('db', 'changed', true);
				Session::set('db', 'name', $dbName);
				$redirect = '1';
			}
			$replace = array(
				'DB_NAME' => htmlspecialchars($dbName),
				'SQL' => preg_replace("/[\n\r]/", "<br/>", htmlspecialchars($sql)),
				'TIME' => $db->getQueryTime(),
				'REDIRECT' => $redirect
			);
			echo view( 'dbcreate', $replace );
		}
	}
?>