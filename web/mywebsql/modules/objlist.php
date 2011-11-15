<?php

	/*************************************************
	*	objlist.php - Author: Samnan ur Rehman        *
	*	This file is a part of MyWebSQL package       *
	*	PHP5 compatible                               *
	*************************************************/

	function processRequest(&$db) {
		include("lib/html.php");
		include("lib/interface.php");
		echo '<div id="objlist">';
		createDatabaseTree($db);
		echo '</div>';
	}

?>