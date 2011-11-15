<?php

	/********************************************
	* altertbl.php - Author: Samnan ur Rehman   *
	* This file is a part of MyWebSQL package   *
	* PHP5 compatible                           *
	*********************************************/

	function processRequest(&$db) {
		$action = v($_REQUEST["id"]);
		include("lib/tableeditor.php");
		$editor = new tableEditor($db);
		if ($action == "alter") {
			$result = alterDatabaseTable($db, v($_REQUEST["query"]), $editor);
			$formatted_query = preg_replace("/[\\n|\\r]?[\\n]+/", "<br>", htmlspecialchars($editor->getSql()));
			if ($result) {
				print
					'<div id="result">1</div><div id="message">'
					.'<div class="success">'.__('The command executed successfully').'.</div>'
					.'<div class="sql_text">'.$formatted_query.'</div>'
					.'</div>';
			} else {
				print
					'<div id="result">0</div><div id="message">'
					.'<div class="warning">'.__('Error occurred while executing the query').':</div>'
					.'<div class="sql_error">'.$formatted_query.'</div><div class="message">'.htmlspecialchars($db->getError()).'</div>'
					.'</div>';
			}
		} else {
			$editor->setName(v($_REQUEST["name"]));
			$editor->loadTable();
			displayTableEditorForm($db, $editor);
		}
	}
	
	function displayTableEditorForm(&$db, &$editor) {
		$rows = $editor->getFields();

		$props = $editor->getProperties();
		$sel_engine = $props->engine;
		$sel_charset = $props->charset;
		$sel_collation = $props->collation;
		$comment = $props->comment;
			
		include('lib/html.php');
		$engines = html::arrayToOptions($db->getEngines(), $sel_engine, false);
		$charsets = html::arrayToOptions($db->getCharsets(), $sel_charset, false);
		$collations = html::arrayToOptions($db->getCollations(), $sel_collation, false);
	
		$replace = array(
						'ID' => v($_REQUEST["id"]) ? htmlspecialchars($_REQUEST["id"]) : '',
						'MESSAGE' => '',
						'ROWINFO' => json_encode($rows),
						'ALTER_TABLE' => 'true',
						'TABLE_NAME' => htmlspecialchars($editor->getName()),
						'ENGINE' => $engines,
						'CHARSET' => $charsets,
						'COLLATION' => $collations,
						'COMMENT' => htmlspecialchars($comment)
						);
		echo view('editable', $replace);
	}
	
	function alterDatabaseTable(&$db, $info, &$editor) {
		$info = json_decode($info);
		
		if (!is_object($info))
			return false;
		
		if (v($info->name))
			$editor->setName($info->name);
		if (v($info->delfields))
			$editor->deleteFields($info->delfields);
		if (v($info->fields))
			$editor->setFields($info->fields);
		if (v($info->props))
			$editor->setProperties($info->props);
		
		$sql = $editor->getAlterStatement();
		
		if (!$db->query($sql))
			return false;
	
		return true;
	}
?>