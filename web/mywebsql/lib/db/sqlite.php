<?php

	/*********************************************************
	*    sqlite.php - Author: Samnan ur Rehman               *
	*    This file is a part of MyWebSQL package             *
	*    A simple and easy to debug sqlite wrapper class     *
	*    PHP5 compatible                                     *
	*********************************************************/

if (defined("CLASS_DB_SQLITE_INCLUDED"))
	return true;

define("CLASS_DB_SQLITE_INCLUDED", "1");

define("NOT_NULL_FLAG",         1);         /* Field can't be NULL */
define("PRI_KEY_FLAG",           2);         /* Field is part of a primary key */
define("UNIQUE_KEY_FLAG",        4);         /* Field is part of a unique key */
define("MULTIPLE_KEY_FLAG",      8);         /* Field is part of a key */
define("BLOB_FLAG",            16);         /* Field is a blob */
define("UNSIGNED_FLAG",         32);         /* Field is unsigned */
define("ZEROFILL_FLAG",         64);        /* Field is zerofill */
define("BINARY_FLAG",          128);         /* Field is binary   */
define("ENUM_FLAG",            256);         /* field is an enum */
define("AUTO_INCREMENT_FLAG",  512);         /* field is a autoincrement field */
define("TIMESTAMP_FLAG",      1024);         /* Field is a timestamp */ 
define("SET_FLAG",            2048);         /* Field is a set */ 

class DB_Sqlite {
	var $ip, $user, $password, $db;
	var $conn;
	var $result;		// array
	var $errMsg;
	var $escapeData;
	var $lastQuery;
	var $queryTime;
	var $authOptions;   // used for additional login security

	function DB_Sqlite() {
		$this->conn = null;
		$this->errMsg = null;
		$this->escapeData = true;
		$this->result = array();
		
		$this->authOptions = array();
	}
	
	function hasServer() {
		return false;
	}
	
	function hasObject($type) {
		switch($type) {
			case 'table':
			case 'view':
			case 'trigger':
				return true;
				break;
		}
		return false;
	}

	function getBackQuotes() {
		return '';
	}
	
	function setAuthOptions($options) {
		$this->authOptions = $options;
	}
	
	function connect($ip, $user, $password, $db="") {
		if (substr($ip, -1) != '/')
			$ip .= '/';
		// must be a directory and writable
		if (!is_dir($ip) || !is_writable($ip))
			return $this->error("Sqlite database folder inaccessible");

		// this helps authenticate first time with user defined login information
		if (isset($this->authOptions['user']) && $user != $this->authOptions['user'])
			return $this->error("Invalid Credentials");
		
		if (isset($this->authOptions['password']) && $password != $this->authOptions['password'])
			return $this->error("Invalid Credentials");

		if ($db && !($this->conn = sqlite_open($ip . $db, 0666)) )
			return $this->error(sqlite_error_string());

		$this->ip = $ip;
		$this->user = $user;
		$this->password = $password;
		$this->db = $db;
		
		$this->selectVersion();
		//$this->query("SET CHARACTER SET 'utf8'");
		//$this->query("SET collation_connection = 'utf8_general_ci'");
		
		return true;
	}

	function disconnect() {
		@sqlite_close($this->conn);
		$this->conn = false;
		return true;
	}
	
	function getCurrentUser() {
		return $this->user;
	}
	
	function selectDb($db) {
		$this->db = $db;
		if ( ! ($this->conn = sqlite_open($this->ip . $db, 0666)) )
			return $this->error(sqlite_error_string(sqlite_last_error()));
		$this->selectVersion();
		return true;
	}
	
	function createDatabase( $name ) {
		if ( empty($name) || is_file($this->ip.$name) ) {
            return false;
		}
		
		// concat .db at the end of name if not already given
		if ( !preg_match('/.db$/', $name) )
			$name .= '.db';
        touch( $this->ip.$name );
        chmod( $this->ip.$name, 0666 );
		return true;
	}
	
	function query($sql, $stack=0) {		// call with query($sql, 1) to store multiple results
		if (!$this->conn) {
			log_message("DB: Connection has been closed");
			return false;
		}

		$this->result[$stack] = "";
		
		//traceMessage("Query: $sql");
		$this->lastQuery = $sql;
		$this->queryTime = $this->getMicroTime();
		$this->result[$stack] = @sqlite_query($sql, $this->conn);
		$this->queryTime = $this->getMicroTime() - $this->queryTime;

		if (!$this->result[$stack]) {
			$this->errMsg = sqlite_error_string(sqlite_last_error($this->conn));
			log_message("DB: $sql ::: ".$this->errMsg);
			return false;
		}
		
		return true;
	}

	function getWarnings() {
		$ret = array();
		/*$res = sqlite_query("SHOW WARNINGS", $this->conn);
		if ($res !== FALSE) {
			while($row = sqlite_fetch_array($res))
				$ret[$row['Code']] = $row['Message'];
		}*/
		return $ret;
	}
	
	function getQueryTime($time=false) {  // returns formatted given value or internal query time
		return sprintf("%.2f", ($time ? $time : $this->queryTime) * 1000) . " ms";
	}
	
	function hasAffectedRows() {
		return ($this->getAffectedRows() > 0);
	}
	
	function insert($table, $values) {
		if (!is_array($values))
			return false;
		
		$sql = "insert into $table (";
		
		foreach($values as $field=>$value)
			$sql .= " $field,";
		
		$sql = substr($sql, 0, strlen($sql) - 1);
		
		$sql .= ") values (";
		
		foreach($values as $field=>$value) {
			if ($this->escapeData)
				$sql .= "'" . $this->escape($value) . "',";
			else
				$sql .= "'$value',";
		}
		
		$sql = substr($sql, 0, strlen($sql) - 1);
		
		$sql .= ")";
		
		$this->query($sql);
	}
	
	function update($table, $values, $condition="") {
		if (!is_array($values))
			return false;
		
		$sql = "update $table set ";
		
		foreach($values as $field=>$value) {
			if ($this->escapeData)
				$sql .= "$field = '" . $this->escape($field) . "',";
			else
				$sql .= "$field = '$value',";
		}
		
		$sql = substr($sql, 0, strlen($sql) - 1);
		
		if ($condition != "")
			$sql .= "$condition";
		
		$this->query($sql);
	}
	
	function getInsertID() {
		return sqlite_last_insert_rowid($this->conn);
	}
	
	function getResult($stack=0) {
		return $this->result[$stack];
	}
	
	function hasResult($stack=0) {
		return ($this->result[$stack] !== TRUE && $this->result[$stack] !== FALSE);
	}
	
	function fetchRow($stack=0, $type="") {
		if($type == "")
			$type = SQLITE_BOTH;

		if (!$this->result[$stack]) {
			log_message("DB: called fetchRow[$stack] but result is false");
			return;
		}
		return @sqlite_fetch_array($this->result[$stack], $type);
	}
	
	function fetchSpecificRow($num, $type="", $stack=0) {
		if($type == "")
			$type = SQLITE_BOTH;
		
		if (!$this->result[$stack]) {
			log_message("DB: called fetchSpecificRow[$stack] but result is false");
			return;
		}

		sqlite_seek($this->result[$stack], $num);
		return @sqlite_fetch_array($this->result[$stack], $type);
	}
	
	function numRows($stack=0) {
		return sqlite_num_rows($this->result[$stack]);
	}
	
	function error($str) {
		log_message("DB: " . $str);
		$this->errMsg = $str;
		return false;
	}
	
	function getError() {
		return $this->errMsg;
	}
	
	function escape($str) {
		return sqlite_escape_string($str);
	}
	
	function setEscape($escape=true) {
		$this->escapeData = $escape;
	}

	function getAffectedRows() {
		return sqlite_changes($this->conn);
	}
	
	/**************************************/
	function getDatabases() {
		$ret = array();
		$d = opendir($this->ip);
        while(($entry = readdir($d)) != false) {
            if ($entry!="." && $entry!=".." && is_file($this->ip.$entry) &&
            		( preg_match('/.db$/', $entry) || preg_match('/.sqlite$/', $entry) ) ) {
				$ret[] = $entry;
            }
        }
		closedir($d);
		return $ret;
	}
	
	function getTables() {
		if (!$this->db)
			return array();
		$res = sqlite_query("select name from SQLITE_MASTER where type = 'table' order by 1", $this->conn);
		$ret = array();
		while($row = sqlite_fetch_array($res))
			$ret[] = $row[0];
		return $ret;
	}
	
	function getViews() {
		if (!$this->db)
			return array();
		$res = sqlite_query("select name from SQLITE_MASTER where type = 'view' order by 1", $this->conn);
		$ret = array();
		while($row = sqlite_fetch_array($res))
			$ret[] = $row[0];
		return $ret;
	}
	
	function getProcedures() {
		return array();
	}
	
	function getFunctions() {
		return array();
	}
	
	function getTriggers() {
		if (!$this->db)
			return array();
		$res = sqlite_query("select name from SQLITE_MASTER where type = 'trigger' order by 1", $this->conn);
		$ret = array();
		while($row = sqlite_fetch_array($res))
			$ret[] = $row[0];
		return $ret;
	}
	
	function getEvents() {
		return array();
	}
	
	/**************************************/
	function getFieldInfo($stack=0) {
		$fields = array();
		$i = 0;
		while ($i < sqlite_num_fields($this->result[$stack])) {
			$meta = false;//sqlite_fetch_field($this->result[$stack], $i);
			//@todo: properly fill structure here like dbex class
			if ($meta) {
				$f = new StdClass;
				$type = sqlite_field_type($this->result[$stack], $i);
				$f->name = $meta->name;
				$f->table = $meta->table;
				$f->not_null = $meta->not_null;
				$f->blob = $meta->blob;
				$f->pkey = $meta->primary_key;
				$f->ukey = $meta->unique_key;
				$f->mkey = $meta->multiple_key;
				$f->zerofill = $meta->zerofill;
				$f->unsigned = $meta->unsigned;
				$f->autoinc = 0;//($meta->flags & AUTO_INCREMENT_FLAG) ? 1 : 0;
				$f->numeric = $meta->numeric;

				$f->type = ($type == 'string' ? 'char' : 'binary');
				$fields[] = $f;
			} else {
				$f = new StdClass;
				$type = 'string';
				$f->name = sqlite_field_name($this->result[$stack], $i);
				$f->table = '';
				$f->not_null = 0;
				$f->blob = 0;
				$f->pkey = 0;
				$f->ukey = 0;
				$f->mkey = 0;
				$f->zerofill = 0;
				$f->unsigned = 0;
				$f->autoinc = 0;
				$f->numeric = 0;

				$f->type = ($type == 'string' ? 'char' : 'binary');
				$fields[] = $f;
			}
			$i++;
		}
		return $fields;
	}
	
	function getMicroTime() {
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}
	
	function selectVersion() {
		//if ($this->conn) {
			Session::set('db', 'version', 0);
			Session::set('db', 'version_full', 'SQLite');
			Session::set('db', 'version_comment', '');
		//} else {
		//}
	}
	
	function getCreateCommand($type, $name) {
		$cmd = '';
		$type = $this->escape($type);
		$name = $this->escape($name);
		
		$sql = "select sql from SQLITE_MASTER where type = '$type' and name = '".$name."'";
		if (!$this->query($sql) || $this->numRows() == 0)
			return '';
		
		$row = $this->fetchRow();
		$cmd = $row[0];
		
		return $cmd;
	}
	
	function getFieldValues($table, $name) {
		$sql = 'show full fields from `'.$table.'` where `Field` = \''.$this->escape($name).'\'';
		$res = sqlite_query($sql, $this->conn);
		if (sqlite_num_rows($res) == 0)
			return ( (object) array('list' => array()) );
		$row = sqlite_fetch_array($res);
		$type = $row['Type'];
		preg_match('/enum\((.*)\)$/', $type, $matches);
		if (!isset($matches[1]))
			preg_match('/set\((.*)\)$/', $type, $matches);
		if (isset($matches[1])) {
			$list = explode(',', $matches[1]);
			foreach($list as $k => $v)
				$list[$k] = str_replace("\\'", "'", trim($v, " '"));
			return $list;
		}
		return ( (object) array('list' => array()) );
	}
	
	function getEngines() {
		$arr = array();
		return $arr;
	}
	
	function getCharsets() {
		$arr = array();
		return $arr;
	}
	
	function getCollations() {
		$arr = array();
		return $arr;
	}
	
	function getTableProperties($table) {
		$sql = "show table status where `Name` like '".$this->escape($table)."'";
		if (!$this->query($sql, "_tmp_query"))
			return FALSE;
		return $this->fetchRow("_tmp_query");
	}
	
	function queryTableStatus() {
		$sql = 'select * from SQLITE_MASTER';
		return $this->query($sql);
	}
	
	function flush($option = '', $skiplog=false) {
		return true;
	}
	
	function getLastQuery() {
		return $this->lastQuery;
	}
	
	
	function getInsertStatement($tbl) {
		$sql = "select sql from SQLITE_MASTER where type = 'table' and name = '".$this->escape($tbl)."'";
		if (!$this->query($sql, '_insert'))
			return false;
		
		$row = $this->fetchRow('_insert');
		$table_info = $this->parseCreateStatement($row[0]);
		$fields = $table_info[0];
		
		$str = "insert into ".$tbl." (";
		$str .= $fields[0];
		
		$str2 = '';

		//if ($row["Extra"] == "auto_increment")
		//	$str2 = " values (NULL";
		//else
			$str2 = " values (\"\"";

		for($i=1; $i<count($fields); $i++) {
			$str .= "," . $fields[$i];
			//if ($row["Extra"] == "auto_increment")
			//	$str2 .= ",NULL";
			//else
				$str2 .= ",\"\"";
		}

		$str .= ")";
		$str2 .= ")";
		
		return $str.$str2;
	}
	
	function getUpdateStatement($tbl) {
		$sql = "select sql from SQLITE_MASTER where type = 'table' and name = '".$this->escape($tbl)."'";
		if (!$this->query($sql, '_update'))
			return false;
		
		$row = $this->fetchRow('_update');
		$table_info = $this->parseCreateStatement($row[0]);
		$fields = $table_info[0];
		$pKey = $table_info[1];

		$str = "update ".$tbl." set ";
		$str .= $fields[0] . "=\"\"";
		
		$str2 = '';
		
		for($i=1; $i<count($fields); $i++) {
			$str .= "," . $fields[$i] . "=\"\"";
			if ($pKey == "") {
				if ($str2 != "")
					$str2 .= " and ";
				$str2 .= "$fields[$i]=\"\"";
			}
		}

		// if we found a primary key, then use it only for where clause and discard other fields
		if ($pKey != '')
			$str2 = "$pKey=\"\"";
		if ($str2 != "")
			$str2 = " where " . $str2;

		return $str . $str2;
	}
	
	function truncateTable($tbl) {
		return $this->query('delete from '.$this->escape($tbl));
	}
	
	function renameObject($name, $type, $new_name) {
		$result = false;
		if($type == 'table') {
			$query = 'ALTER TABLE '.$this->escape($name).' RENAME TO '.$this->escape($new_name);
			$result = $this->query($query);
		} else {
			//@@TODO: fix logic according to sqlite
			$command = $this->getCreateCommand($type, $name);
			$search = '/(create.*'.$type. ' )('.$name.'|\`'.$name.'\`)/i';
			$replace = '${1} `'.$new_name.'`';
			$query = preg_replace($search, $replace, $command, 1);
			if ($this->query($query)) {
				$query = 'drop '.$this->escape($type).' `'.$this->escape($name).'`';
				$result = $this->query($query);
			}
		}
		
		return $result;
	}
	
	function dropObject($name, $type) {
		$result = false;
		$query = 'drop '.$this->escape($type).' '.$this->escape($name);
		$result = $this->query($query);
		return $result;
	}
	
	function copyObject($name, $type, $new_name) {
		$result = false;
		if($type == 'table') {
			$query = 'create '.$this->escape($type).' ' . $this->escape($new_name) . ' as select * from ' . $this->escape($name);
			$result = $this->query($query);
		} else {
			//@@TODO: fix logic according to sqlite
			$command = $this->getCreateCommand($type, $name);
			$search = '/(create.*'.$type. ' )('.$name.'|\`'.$name.'\`)/i';
			$replace = '${1} `'.$new_name.'`';
			$query = preg_replace($search, $replace, $command, 1);
			$result = $this->query($query);
		}
		return $result;
	}
	
	/***** private functions ******/
	private function parseCreateStatement($str) {
		$extra = strtok( $str, "(" );
		$primary = '';
		while( $fieldnames[] = strtok(",") ) {};
		array_pop( $fieldnames );
		foreach( $fieldnames as $no => $field ) {
			if ( strpos($field, "PRIMARY KEY") ) {
				strtok( $field, "(" );
				$primary = trim(strtok( ")" ));
				unset($fieldnames[$no]);
			} else
				$fieldnames[$no] = trim(strtok( $field, " " ));
		}
		return array($fieldnames, $primary);
	}
}
?>