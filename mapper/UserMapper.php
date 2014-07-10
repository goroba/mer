<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/mapper/Mapper.php';

class UserMapper extends Mapper
{
	protected $findAuthDataStmt = null;
	protected $table = 'users';
	
	function __construct()
	{
		parent::__construct();
		$this->findAuthDataStmt = self::$dbh->prepare("SELECT * FROM `$this->table` WHERE `login` = ?");		
		
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class UserMapper: constructed.");
		
	}

	function findAuthData($login)
	{
		
		$this->findAuthDataStmt->execute(array($login));
		$data = $this->findAuthDataStmt->fetchAll();
		
		if (!is_array($data) || count($data) != 1)
		{
			return null;
		}
		
		return $this->createObject($data[0]);
	}
	
	protected function createObject($array)
	{
		$user = new \mer\domain\User($array);
		return $user;		
	}
	
	
}
