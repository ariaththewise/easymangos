<?php

	/************************************************************************
	 *  database.php                                                        *
	 *  database access configuration file                                  *
	 *  defines per server database access list to restrict connection      *
	 *      to a list of databases                                          * 
	 ************************************************************************/

	// match the keys of this array to those defined in servers.php in the same folder
	// keys that do not exist in servers list will be simply ignored
	// if a server's database access is not defined here, then it will show all databases
	//     (given that the logged in user has access to all those databases)
	$DB_LIST = array(
		'Test Server' =>      array('test', 'wordpress'),
		//'Test Server 2'' =>    array('test')
	);
?>