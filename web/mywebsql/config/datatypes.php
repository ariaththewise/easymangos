<?php

	/*******************************************
	 *  datatypes.php                          *
	 *  mysql database field configuration     *
	 *******************************************/

	$dataTypes = array(
		'bigint' => array('type' => 'numeric'),
		'binary' => array('type' => 'binary'),
		'bit' => array('type' => 'special'),
		'blob' => array('type' => 'binary'),
		'bool' => array('type' => 'special'),
		'boolean' => array('type' => 'special'),
		'char' => array('type' => 'char'),
		'date' => array('type' => 'date'),
		'datetime' => array('type' => 'date'),
		'decimal' => array('type' => 'numeric'),
		'double' => array('type' => 'numeric'),
		'enum' => array('type' => 'char'),
		'float' => array('type' => 'numeric'),
		'int' => array('type' => 'numeric'),
		'longblob'=> array('type' => 'binary'),
		'longtext'=> array('type' => 'text'),
		'mediumblob'=> array('type' => 'binary'),
		'mediumint' => array('type' => 'numeric'),
		'mediumtext'=> array('type' => 'text'),
		'numeric' => array('type' => 'numeric'),
		'real' => array('type' => 'numeric'),
		'set'=> array('type' => 'char'),
		'smallint' => array('type' => 'numeric'),
		'text'=> array('type' => 'text'),
		'time' => array('type' => 'date'),
		'timestamp' => array('type' => 'date'),
		'tinyblob'=> array('type' => 'binary'),
		'tinyint' => array('type' => 'numeric'),
		'tinytext'=> array('type' => 'text'),
		'varbinary'=> array('type' => 'binary'),
		'varchar'=> array('type' => 'char'),
		'year' => array('type' => 'date')
	);

?>