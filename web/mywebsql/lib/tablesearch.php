<?php

	/*************************************************************
	*	tablesearch.php - Author: Samnan ur Rehman               *
	*	This file is a part of MyWebSQL package                  *
	*	This class searches table for given text                 *
	*	PHP5 compatible                                          *
	*************************************************************/

class tableSearch {
	var $db;
	
	var $text;
	var $tables;
	var $operator;
	var $fieldTypes;
	
	var $results;
	var $_queries; // list of queries that fetched results with 1 or more matches
	
	function __construct(&$db) {
		$this->db = $db;
	}

	function setText($keyword) {
		$this->text = $keyword;
	}
	
	function setTables($tables) {
		$this->tables = $tables;
	}

	function setOperator($op) {
		$this->operator = $op;
	}
	
	function setFieldTypes($types) {
		$this->fieldTypes = $types;
	}

	function search() {
		$opFunc = 'op_' . $this->operator;
		if (!method_exists($this, $opFunc))
			return false;
		$post_fix = $this->$opFunc();
		$this->_queries = array();
		$this->results = array();
		foreach($this->tables as $table) {
			$this->results[$table] = array('matches' => 0, 'link' => '');
			$sql = 'select count(*) as matches from `' . $this->db->escape($table) . '` where ';
			$fields = $this->getFields($table);
			if (count($fields) == 0)
				continue;
			for($i=0; $i<(count($fields)-1); $i++)
				$sql .= '`' . $fields[$i] . '`' . $post_fix . ' OR ';
			$sql .= '`' . $fields[$i] . '`' . $post_fix; 
			
			if (!$this->db->query($sql, '_search'))
				return false;
				
			$row = $this->db->fetchRow('_search');
			$this->results[$table]['matches'] = $row[0];
			if ($this->results[$table]['matches'] > 0)
				$this->_queries[$table] = str_replace('count(*) as matches', '*', $sql);
		}
		return true;
	}
	
	function getFields($table) {
		require('config/datatypes.php');
		$sql = "show fields from `".$this->db->escape($table)."`";
		if (!$this->db->query($sql, "_search"))
			return FALSE;
		
		$fields = array();
		while($row = $this->db->fetchRow("_search")) {
			preg_match('/(.*)\(.*\).*$/', $row['Type'], $matches);
			$fieldType = count($matches) > 1 ? $matches[1] : $row['Type'];
			$dataType = isset($dataTypes[$fieldType]) ? $dataTypes[$fieldType]['type'] : '';
			if (isset($this->fieldTypes[$dataType]) && $this->fieldTypes[$dataType])
				$fields[] = $row['Field'];
		}
		return $fields;
	}
	
	function getResults() {
		return $this->results;
	}
	
	function getQueries() {
		return $this->_queries;
	}
	
	private function op_equal() {
		// for numeric fields, the equality operator must not use quoted text
		if ($this->fieldTypes['numeric'])
			return '='.$this->text;
		else	
			return '=\''.$this->db->escape($this->text).'\'';	
	}
	
	private function op_like() {
		return ' like \''.$this->db->escape($this->text).'\'';	
	}
	
	private function op_wildcard() {
		return ' like \'%'.$this->db->escape($this->text).'%\'';	
	}
	
	private function op_greater() {
		return '>\''.$this->db->escape($this->text).'\'';	
	}
	
	private function op_lesser() {
		return '<\''.$this->db->escape($this->text).'\'';	
	}

}
?>