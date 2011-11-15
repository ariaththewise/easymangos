<?php

	/*************************************************
	*	info.php - Author: Samnan ur Rehman           *
	*	This file is a part of MyWebSQL package       *
	*	PHP5 compatible                               *
	*************************************************/

	if (getDbName() != '')
		include('infodb.php');
	else
		include('infoserver.php');

?>