<?php

	/*************************************************
	*	showinfo.php - Author: Samnan ur Rehman       *
	*	This file is a part of MyWebSQL package       *
	*	PHP5 compatible                               *
	*************************************************/

	$type = $_REQUEST["id"];
	if ($type == 'table' || $type == 'view') {
		$_REQUEST["id"] = 'table';
		$_REQUEST["query"] = $_REQUEST["name"];
		unset($_REQUEST['name']);
		Session::del('select');
		include('query.php');
	} else {
		function processRequest(&$db) {
			$extraMsg = '';

			$type = $_REQUEST["id"];
			$name = $_REQUEST["name"];
			
			//$sql = ($type == "trigger") ? $sql = "show triggers where `trigger` = '$name'" : "show create $type `$name`";
			//$sql = preg_replace("/[\n\r]/", "<br/>", htmlspecialchars($sql));
			$cmd = $db->getCreateCommand($type, $name);
			$cmd = sanitizeCreateCommand($type, $cmd);
			//$tm = $db->getQueryTime();

			$replace = array('TYPE' => $type,
								'NAME' => $name,
								'COMMAND' => $cmd,
								//'TIME' => $tm,
								//'SQL' => $sql
								//'MESSAGE' => $extraMsg
							);

			echo view('showinfo', $replace);
		}
	}
?>