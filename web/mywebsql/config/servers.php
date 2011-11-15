<?php

	/************************************************************************
	 *  servers.php                                                         *
	 *  server configuration file                                           *
	 *  defining more that one server here will give user the option to     *
	 *     select a server at login time                                    *
	 *  Notes:                                                              *
	 *   server list is used only when authentication type is LOGIN         *
	 ************************************************************************/

	// add or remove list of servers below
	// if there are more than one servers defined, the user will be given choice
	// at the time of login to select a server
	
	// please make sure you have the proper extensions enabled in your php config
	// to successfully connect to servers other than MySQL
	
	// valid drivers types are:
	// mysql4, mysql5, mysqli, sqlite
	// for sqlite driver:
	//   'host' should be the folder name where sqlite databases are saved,
	//   'user' and 'password' options should be added for additional security

	$SERVER_LIST = array(
		'Localhost'           => array(
		                             'host'     => 'localhost',
		                             'driver'   => extension_loaded('mysqli') ? 'mysqli' : 'mysql5'
		                         ),
                                 
        // Portable MySQL Server for EasyMANGOS                         
        'MySQL Portable'      => array(
                                     'host'     => 'localhost:3307',
                                     'driver'   => 'mysql5'
                                 ),
                                 
		/*'MySQL Proxy Server'  => array(
		                             'host'     => 'localhost:4040',
		                             'driver'   => 'mysql5'
		                         ),
		'MySQL 4'             => array(
		                             'host'     => 'localhost',
		                             'driver'   => 'mysql4'
		                         ),
		'SQLite Databases'    => array(
		                             'host'     => 'c:/sqlitedb/',
		                             'driver'   => 'sqlite',
		                             'user'     => 'root',   // set this yourself
		                             'password' => 'sqlite'  // set this yourself
		                         )
		 */
	);
?>