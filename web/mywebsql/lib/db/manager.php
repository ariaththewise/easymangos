<?php

	/*********************************************************************
	*  manager.php - Authors: Samnan ur Rehman                           *
	*  This file is a part of MyWebSQL package                           *
	*  Provides a generic wrapper for database connection functionality  *
	*  PHP5 compatible                                                   *
	**********************************************************************/

if (defined("CLASS_COMMON_DB_INCLUDED"))
	return true;

define("CLASS_COMMON_DB_INCLUDED", "1");

class DbManager {
	var $conn;
	var $errMsg;

	function DbManager() {
	}

	function connect($server, $user, $password, $db="") {
		$host   = $server['host'];
		$driver = $server['driver'];
		
		traceMessage('connecting to ['.$host.'] driver = ['.$driver.']');
		
		$lib = 'lib/db/'.$driver.'.php';
		include_once($lib);
		$driver = 'DB_'.ucfirst($driver);
		$db = new $driver();
		$db->setAuthOptions($server);
		
		$result = $db->connect($host, $user, $password);
		if (!$result) {
			$this->errMsg = $db->getError();
			return false;
		}
		$db->disconnect();
		return true;
	}
	
	// required for proper functionality
	function disconnect() {
	}
	
	function getError() {
		return $this->errMsg;
	}
}
?>