<?php

/***********************************************
*	usermanager.php - Author: Ovais Tariq		  *
*  Author website: http://ovaistariq.net       *
*	This file is a part of MyWebSQL package     *
*	user and permissions management library     *
*	PHP5 compatible                             *
***********************************************/

include_once 'privileges.php';
include_once 'user.php';

class UserManager {
	protected $db;
	protected $legacy;

	public function __construct( $db, $legacy = false ) {
		$this->db     = $db;
		$this->legacy = (bool)$legacy;

		User::setDb( $this->db );
	}

	public function getUser($userName, $host = '%') {
		$user = User::factory( $this->legacy );
		$user->userName = $userName;
		$user->host     = $host;

		return $user;
	}

	public function add($userName, $host = '%', $password = '') {
		$user = User::factory( $this->legacy );
		
		$user->userName = $userName;
		$user->host     = $host;
		$user->password = $password;
		
		return $user->add();
	}

	public function update($currUsername, $currHost, $newUsername, $newHost) {
		$user = User::factory( $this->legacy );

		$user->userName = $currUsername;
		$user->host     = $currHost;
		
		return $user->update( $newUsername, $newHost );
	}

	public function updatePassword($userName, $host, $newPassword) {
		$user = User::factory( $this->legacy );

		$user->userName = $userName;
		$user->host     = $host;
		
		return $user->updatePassword( $newPassword );
	}

	public function delete($userName, $host) {
		$user = User::factory( $this->legacy );

		$user->userName = $userName;
		$user->host     = $host;
		
		return $user->delete();
	}

	public function getUsersList() {
		$tblName = Privileges::$privilegesTable;

		$sql = "SELECT `User`, `Host`, `Password` FROM $tblName ORDER BY `User`, `Host`";

		if( false == $this->db->query( $sql ) )
			return array();

		$users = array();
		while( $row = $this->db->fetchRow() ) {
			$user = User::factory( $this->legacy );

			$user->userName = $row['User'];
			$user->host     = $row['Host'];
			$user->password = $row['Password'];

			$users[] = $user;
		}

		return $users;
	}
}