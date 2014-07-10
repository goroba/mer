<?php

namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class AccessManager
{
	private static $instance;

	private $user = null;
	private $is_logged = false;
	
	private function __construct()
	{
	}
	
	static function instance()
	{
		if ( !isset(self::$instance) )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	function isLogged()
	{
		return $this->is_logged;
	}
	
	function getUser()
	{
		return $this->user;
	}
	
	private function setUser($user)
	{
		$this->user = $user;
	}
	
	function getArray()
	{
		if (!isset($this->user) || is_null($this->user))
		{
			return array(
				'is_logged' => $this->is_logged,
				'user' => null
			);
		}
		else
		{
			return array(
				'is_logged' => $this->is_logged,
				'user' => $this->user->getArray()			
			);
		}
	}
	
	protected function crypt($pass)
	{
		return md5($pass);
	}
	
	function checkStatus($status)
	{
		$a = \mer\base\SessionRegistry::instance()->getValue(MER_SESS_ACCESS_MANAGER);
		$user_status = $a['user']['status'];
		if ($user_status < $status)
		{
			return false;
		}
			
		return true;
	}
	
	function doAuth()
	{
		$login = \mer\base\SessionRegistry::instance()->getValue(MER_SESS_ACCESS_MANAGER);
		if (!isset($login) || is_null($login))
		{
		
			//\mer\base\SessionRegistry::instance()->pushSysMessage("class AccessManager->doAuth(): access_manager doesn't exists. Creating new...");
			
			if (isset($_COOKIE['login']) && isset($_COOKIE['password']))
			{
				$this->tryLogin($_COOKIE['login'], $_COOKIE['password'], true);
			}
			\mer\base\SessionRegistry::instance()->setValue(MER_SESS_ACCESS_MANAGER, $this->getArray());
		}
		/*
		else
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class AccessManager->doAuth(): access_manager already exists.");
		}
		*/
	}
	
	protected function tryLogin($login, $password, $memorize=false)
	{
		$this->is_logged = false;
		$this->user = null;
		
		setcookie('login', '');
		setcookie('password', '');	
		
		$mapper = \mer\base\ApplicationFactory::instance()->getMapper('User');
		if (!isset($mapper))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class LoginCommand->doExecute(): usermapper doesn't exist.");
			return null;
		}
		
		$user = $mapper->findAuthData($login);
		
		if (!isset($user) || is_null($user))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class LoginCommand->doExecute(): user object isn't set.");
			\mer\base\SessionRegistry::instance()->pushMultilangError('MER_ERROR_LOGIN_INCORRECT_USER');
			return null;
		}
		
		if ($user->getLogin() != $login)
		{
			\mer\base\SessionRegistry::instance()->pushMultilangError('MER_ERROR_LOGIN_INCORRECT_USER');
			return null;
		}

		if ($user->getPassword() != $password)
		{
			\mer\base\SessionRegistry::instance()->pushMultilangError('MER_ERROR_LOGIN_INCORRECT_PASSWORD');
			return null;
		}

		$this->is_logged = true;		
		$this->setUser($user);
		
		if ($memorize)
		{
			setcookie('login', $login, time()+3600*24*365);
			setcookie('password', $password, time()+3600*24*365);
		}
			
		return true;
	}
	
	function doLogin($login, $password, $memorize=false)
	{
		$password = $this->crypt($password);
		$success = $this->tryLogin($login, $password, $memorize);
		
		if ($success)
		{
			\mer\base\SessionRegistry::instance()->pushMultilangMessage('MER_MESSAGE_LOGIN_SUCCESS');

		}
		\mer\base\SessionRegistry::instance()->setValue(MER_SESS_ACCESS_MANAGER, $this->getArray());
	}
	
	function doLogout()
	{
		$this->is_logged = false;
		unset ($this->user);
		
		setcookie('login', '');
		setcookie('password', '');	
		
		\mer\base\SessionRegistry::instance()->setValue(MER_SESS_ACCESS_MANAGER, $this->getArray());
	}
}