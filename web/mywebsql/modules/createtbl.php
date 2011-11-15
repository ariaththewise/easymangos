<?php

	/*********************************************
	* createtbl.php - Author: Samnan ur Rehman   *
	* This file is a part of MyWebSQL package    *
	* PHP5 compatible                            *
	*********************************************/

	function processRequest(&$db) {
		$action = v($_REQUEST["id"]);
		if ($action == "create" || $action == "alter") {
			include("lib/tableeditor.php");
			$editor = new tableEditor($db);
			$result = createDatabaseTable($db, v($_REQUEST["query"]), $editor);
			$formatted_query = preg_replace("/[\\n|\\r]?[\\n]+/", "<br>", htmlspecialchars($editor->getSql()));
			if ($result)
				print
					'<div id="result">1</div><div id="message">'
					.'<div class="success">'.__('The command executed successfully').'.</div>'
					.'<div class="sql_text">'.$formatted_query.'</div>'
					.'</div>';
			else				
				print
					'<div id="result">0</div><div id="message">'
					.'<div class="warning">'.__('Error occurred while executing the query').':</div>'
					.'<div class="sql_error">'.$formatted_query.'</div><div class="message">'.htmlspecialchars($db->getError()).'</div>'
					.'</div>';
		}
		else
			displayCreateTableForm($db);
	}
	
	function displayCreateTableForm(&$db) {
		$rows = array();

		include('lib/html.php');
		$engines = html::arrayToOptions($db->getEngines(), '', true);
		$charsets = html::arrayToOptions($db->getCharsets(), '', true);
		$collations = html::arrayToOptions($db->getCollations(), '', true);
		$comment = '';
	
		$replace = array(
						'ID' => v($_REQUEST["id"]) ? htmlspecialchars($_REQUEST["id"]) : '',
						'MESSAGE' => '',
						'ROWINFO' => json_encode($rows),
						'ALTER_TABLE' => 'false',
						'TABLE_NAME' => '',
						'ENGINE' => $engines,
						'CHARSET' => $charsets,
						'COLLATION' => $collations,
						'COMMENT' => htmlspecialchars($comment)
						);
		echo view('editable', $replace);
	}
	
	function createDatabaseTable(&$db, $info, &$editor) {
		$info = json_decode($info);
		
		if (!is_object($info))
			return false;
		
		if (v($info->name))
			$editor->setName($info->name);
		if (v($info->fields))
			$editor->setFields($info->fields);
		if (v($info->props))
			$editor->setProperties($info->props);
		
		$sql = $editor->getCreateStatement();
		
		if (!$db->query($sql))
			return false;
	
		return true;
	}
?>