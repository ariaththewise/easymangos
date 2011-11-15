<?php

	/***********************************************
	*	download.php - Author: Samnan ur Rehman     *
	*	This file is a part of MyWebSQL package     *
	*	PHP5 compatible                             *
	***********************************************/

	function handleDownload(&$db) {
		if ($_REQUEST["id"] == "exportres")
			downloadResults($db);
		if ($_REQUEST["id"] == "exporttbl")
			downloadTable($db, $_REQUEST["name"]);
		if ($_REQUEST["id"] == "export")
			downloadDatabase($db);
	}

	function downloadResults(&$db) {
		$type = 'insert';
		$exptype = v($_REQUEST['exptype']);
		if(in_array($exptype, array('insert', 'xml', 'xhtml', 'text')))
			$type = $exptype;
		$options = array('type' => $type,
								'table' => '',
								'fieldnames' => v($_REQUEST['fieldnames']) == 'on' ? TRUE : FALSE,
								'separator' => v($_REQUEST['separator'], "\t"),
								'fieldnames' => v($_REQUEST['fieldnames'])  == 'on' ? TRUE : FALSE
							);

		$options['auto_field'] = -1;
		//if (substr($_SESSION["query"], 0, 6) == "select" && $_REQUEST["auto_null"] == "on" && Session::get('select', 'unique_table') != "")
		//	$options['auto_field'] = getAutoIncField($db, Session::get('select', 'unique_table'));

		$table = (Session::get('select', 'unique_table') != "") ? Session::get('select', 'unique_table') . "-results" : "results";
		$options['table'] = (Session::get('select', 'unique_table') != "") ? Session::get('select', 'unique_table') : '<<table>>';

		sendDownloadHeader($table, v($_REQUEST['exptype']));

		exportTable($db, Session::get('select', 'query'), $options);
	}

	function downloadTable(&$db, $table) {
		if ($table == "")
			return false;

		$type = 'insert';
		$exptype = v($_REQUEST['exptype']);
		if(in_array($exptype, array('insert', 'xml', 'xhtml', 'text')))
			$type = $exptype;
		$options = array('type' => $type,
								'table' => $table,
								'fieldnames' => v($_REQUEST['fieldnames']) == 'on' ? TRUE : FALSE,
								'separator' => v($_REQUEST['separator'], "\t")
							);

		$options['auto_field'] = -1; //($_REQUEST["auto_null"] == "on") ? getAutoIncField($db, $table) : -1;

		$sql = "select * from `".$table."`";
		sendDownloadHeader($table, v($_REQUEST['exptype']));
		exportTable($db, $sql, $options);
	}


	function downloadDatabase(&$db) {
		// dont make POST as REQUEST here. it won't work :P
		if ( !( is_array(v($_POST["tables"])) || is_array(v($_POST["views"])) || is_array(v($_POST["procs"]))
			  ||is_array(v($_POST["funcs"])) || is_array(v($_POST["triggers"])) ||is_array(v($_POST["events"])) ) )
			return false;

		sendDownloadHeader(Session::get('db', 'name'), "sql");
		print "/* Database export results for db ".Session::get('db', 'name')."*/\n";
		addExportHeader();

		$export_type = v($_REQUEST["exptype"]);
		if (is_array($_POST["tables"]) && count($_POST["tables"]) > 0)	{
			$tables = $db->getTables();

			$options = array(
				'type' => 'insert',
				'fieldnames' => v($_REQUEST['fieldnames']) == 'on' ? TRUE : FALSE
			);
			foreach($tables as $table_name) {
				// is this table required in export?
				$key = array_search($table_name, $_POST["tables"]);
				if ($key === FALSE)
					continue;

				// -- -drop command --
				if (v($_REQUEST["dropcmd"]) == "on") {
					print "\ndrop table if exists `$table_name`;\n";
				}

				// -- -structure --
				$type = "table";
				if ($export_type == "all" || $export_type == "struct") {
					print "\n/* table structure for $table_name */\n";
					$sql = "show create $type `$table_name`";
					if (!$db->query($sql, "_create") || $db->numRows("_create") == 0)
						print "/* Failed to retrieve $type structure for $table_name */";
					else {
						$row = $db->fetchRow("_create");
						if (v($_REQUEST["auto_null"]) == "on")	// strip out auto_increment value from create table statement
							$create_table = stripAutoIncrement($row[1]);
						else
							$create_table = $row[1];
						print $create_table . ";\n";
					}
				}

				// -- -table data --
				if ($export_type == "all" || $export_type == "data") {
					$options['auto_field'] = v($_REQUEST["auto_null"]) == "on" ? getAutoIncField($db, $table_name) : -1;
					$options['table'] = $table_name;

					$sql = "select * from `".$table_name."`";
					print "\n/* data for table $table_name */\n";

					exportTable($db, $sql, $options);
				}
			}
		}

		if ($export_type == "all" || $export_type == "struct") {		// views, procedures etc do not have any data
			if (is_array(v($_POST["views"])) && count($_POST["views"]) > 0)
				exportObject($db, 'view', $_POST["views"], $db->getViews());
			if (is_array(v($_POST["procs"])) && count($_POST["procs"]) > 0)
				exportObject($db, 'procedure', $_POST["procs"], $db->getProcedures());
			if (is_array(v($_POST["funcs"])) && count($_POST["funcs"]) > 0)
				exportObject($db, 'function', $_POST["funcs"], $db->getFunctions());
			if (is_array(v($_POST["triggers"])) && count($_POST["triggers"]) > 0)
				exportObject($db, 'trigger', $_POST["triggers"], $db->getTriggers());
			if (is_array(v($_POST["events"])) && count($_POST["events"]) > 0)
				exportObject($db, 'event', $_POST["events"], $db->getEvents());
		}
		
		addExportFooter();
	}

	// =====================================
	function getAutoIncField(&$db, $table) {
		$sql = "show full fields from `".$table."`";
			if (!$db->query($sql, "_temp"))
				return false;

		$i = 0;
		while($row = $db->fetchRow("_temp")) {
			if (strpos($row["Extra"], "auto_increment") !== false) {
				return $i;
			}
			$i++;
		}

		return -1;
	}

	// common download function for all types of exports
	function exportTable(&$db, $sql, $options) {
		$applyLimit = strpos($sql, "limit ") || ("select" != strtolower(substr($sql, 0, 6)));

		$id = 0;
		$field_info = NULL;

		while(1) {
			$tempSql = $sql;
			if ($applyLimit == false)
				$tempSql .= " limit $id, 100";

			if (!$db->query($tempSql, "_temp"))
				return false;

			$res = "";
			if ($field_info == NULL) {
				$field_info = $db->getFieldInfo("_temp");
				printHeader($options['type'], $field_info);
			}

			$numRows = $db->numRows("_temp");

			while($row = $db->fetchRow("_temp", MYSQL_NUM)) {
				if ($options['type'] == 'insert')
					createInsertLine($db, $row, $res, $field_info, $options['auto_field'], $options['table'], $options['fieldnames']);
				else if ($options['type'] == 'xml')
					createXML($db, $row, $res, $field_info);
				else if ($options['type'] == 'xhtml')
					createXHTML($db, $row, $res, $field_info);
				if ($options['type'] == 'text')
					createSimpleLine($db, $row, $res, $field_info, $options['separator']);
			}

			print $res;
			unset($res);

			if ($numRows == 0 || $applyLimit)
				break;

			$id += 100;
		}

		printFooter($options['type'], $field_info);
	}

	function exportObject(&$db, $name, $list, $tables) {
		foreach($tables as $table_name) {
			$key = array_search($table_name, $list);
			if ($key === FALSE)
				continue;

			if (v($_REQUEST["dropcmd"]) == "on")
				print "\ndrop $name if exists `$table_name`;\n";

			print "\n/* create command for $table_name */\n";
			print "\nDELIMITER $$\n";
			print $db->getCreateCommand($name, $table_name) . "$$\n";
			print "\nDELIMITER ;\n";
		}
	}

	function createInsertLine(&$db, &$row, &$res, $field_info, $autof, $table, $fieldNames) {
		$x = count($row);
		$res .= "insert into `".$table."`";
		if ($fieldNames) {
			$res .= " (";
			for($i=0; $i<count($field_info)-1; $i++)
				$res .= "`".$field_info[$i]->name."`,";
			$res .= "`".$field_info[$i]->name."`)";
		}
		$res .= " values (";
		for($i=0; $i<$x; $i++) {
			if ($autof == $i)
				$res .= "NULL";
			else if ($row[$i] === NULL)
				$res .= "NULL";
			else if ($field_info[$i]->numeric == 1 && $field_info[$i]->type != 'binary')  // needed for certain timestamp fields
				$res .= $row[$i];
			else
				$res .= "\"". $db->escape($row[$i]) . "\"";

			if ($i+1 == $x)
				$res .= ");\r\n";
			else
				$res .= ",";
		}
	}

	function createXML(&$db, &$row, &$res, $field_info) {
		$x = count($row);
		$res .= "<row>\n";
		for($i=0; $i<$x; $i++) {
			$res .= "\t<" . $field_info[$i]->name . ">";
			if ($row[$i] === NULL)
				$res .= "NULL";
			else if ($field_info[$i]->numeric == 1)
				$res .= $row[$i];
			else
				$res .= "<![CDATA[" .  $row[$i] . "]]>";

			$res .= "</" . $field_info[$i]->name . ">\n";
		}
		$res .= "</row>\n";
	}

	function createXHTML(&$db, &$row, &$res, $field_info) {
		$x = count($row);
		$res .= "\t\t<div class=\"row\">\n\t\t\t";
		for($i=0; $i<$x; $i++) {
			$res .= "<div>";
			if ($row[$i] === NULL)
				$res .= "NULL";
			else if ($field_info[$i]->numeric == 1)
				$res .= $row[$i];
			else
				$res .= htmlspecialchars($row[$i]);

			$res .= "</div>";
		}
		$res .= "\n\t\t</div>\n";
	}

	function createSimpleLine(&$db, &$row, &$res, $field_info, $separator) {
		if ($separator == '\t')
			$separator = "\t";
		$fieldwrap = "\"";
		$x = count($row);
		for($i=0; $i<$x; $i++) {
			if ($row[$i] === NULL)
				$res .= "NULL";
			else if ($field_info[$i]->numeric == 1)
				$res .= $row[$i];
			else
				$res .= $fieldwrap .  $row[$i] . $fieldwrap;

			if ($i+1 == $x)
				$res .= "\r\n";
			else
				$res .= $separator;
		}
	}

	function sendDownloadHeader($name, $type) {
		if ($type == "xml") $type = ".xml";
		else if ($type == "xhtml") $type = ".html";
		else if ($type == "text") $type = ".txt";
		else $type = ".sql";
		header("Content-disposition: attachment;filename=".$name.$type);
	}

	function printHeader($type, $field_info) {
		switch($type) {
			case 'xml':
				print "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n<data>\n";
				break;
			case 'xhtml':
				print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
				print "<head><title>Export Data</title>\n";
				print "<style>
div { float:left;padding:5px }
div.field { background-color:#efefef;width:100px }
div.data { clear:both }
div.row { clear:both }
div.row div { width:100px;overflow:hidden }
</style>\n";
				print "</head>\n<body>\n\t<div class=\"header\">\n\t\t";
				for($i=0; $i<count($field_info); $i++)
					print "<div class=\"field\">" . htmlspecialchars($field_info[$i]->name) . "</div>";
				print "\n\t</div>\n\t<div class=\"data\">\n";
				break;
		}
	}

	function printFooter($type, $field_info) {
		switch($type) {
			case 'xml':
				print "</data>";
				break;
			case 'xhtml':
				print "</div>\n</body>\n</html>";
				break;
		}
	}
	
	function stripAutoIncrement($statement) {
		//return preg_replace("/AUTO_INCREMENT=[0-9]+\s/", "", $statement); // a chance of bug with a crazy field name like `AUTO_INCREMENT=1000 ` :P
		preg_match("/.*\).*(AUTO_INCREMENT=[0-9]+ )/", $statement, $matches);
		if (isset($matches[1]))
			$statement = str_replace($matches[1], "", $statement);
		return $statement;
	}

	function addExportHeader() {
		print "\n/* Preserve session variables */\nSET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS;\nSET FOREIGN_KEY_CHECKS=0;\n\n/* Export data */\n";
	}
	
	function addExportFooter() {
		print "\n/* Restore session variables to original values */\nSET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;\n";
	}
?>