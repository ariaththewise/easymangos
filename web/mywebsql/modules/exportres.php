<?php

	/**********************************************
	*	exportres.php - Author: Samnan ur Rehman   *
	*	This file is a part of MyWebSQL package    *
	*	PHP5 compatible                            *
	***********************************************/

	function processRequest(&$db) {
		$replace = array();
		echo view('exportres', $replace);
	}

?>