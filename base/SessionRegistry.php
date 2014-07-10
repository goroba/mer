<?php

namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once 'Registry.php';

class SessionRegistry extends Registry 
{
	private static $instance;
	
	private function __construct()
	{
		//session_start();
	}
	
	static function instance()
	{
		if ( !isset(self::$instance) )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function set($key, $val)
	{
		$_SESSION[__CLASS__][$key] = $val;
	}
	
	protected function get($key)
	{
		if ( isset($_SESSION[__CLASS__][$key]) )
		{
			return $_SESSION[__CLASS__][$key];
		}
		return null;
	}
	
	protected function push($key, $val)
	{
		$a = self::instance()->get($key);
		if (is_null($a) || !isset($a))
		{
			$a = '';
		}
		self::instance()->set($key, $a . "\n" . $val);
	}

	protected function _unset($key)
	{
		unset($_SESSION[__CLASS__][$key]);
	}
	
	protected function pop($key)
	{
		$res = self::instance()->get($key);
		self::instance()->_unset($key);
		return $res;
	}
	
	function getValue($key)
	{
		return self::instance()->get($key);
	}

	function setValue($key, $val)
	{
		self::instance()->set($key, $val);
	}
	
	function pushValue($key, $val)
	{
		self::instance()->push($key, $val);
	}
	
	function unsetValue($key)
	{
		self::instance()->_unset($key);
	}
	
	function popValue($key)
	{
		return self::instance()->pop($key);
	}
	
	function setError($message)
	{
		self::instance()->set('error', '<p>' . $message . '</p>');
	}
	
	function pushError($message)
	{
		self::instance()->push('error', '<p>' . $message . '</p>');
	}
	
	function pushMultilangError($key)
	{
		$message = \mer\settings\Settings::$Errors[$key][\mer\base\SessionRegistry::instance()->getValue('lang')];
		if (!isset($message) || is_null($message))
		{
			$message = \mer\settings\Settings::$Errors[$key][MER_DEFAULT_LANGUAGE];
			if (!isset($message) || is_null($message))
			{
				return null;
			}
		}
		self::instance()->pushError($message);
	}
	
	function getError()
	{
		return self::instance()->get('error');
	}
	
	function unsetError()
	{
		self::instance()->_unset('error');
	}
	
	function popError()
	{
		return self::instance()->pop('error');
	}
	
	function setMessage($message)
	{
		self::instance()->set('message', '<p>' . $message . '</p>');
	}
	
	function pushMessage($message)
	{
		self::instance()->push('message', '<p>' . $message . '</p>');
	}
	
	function pushMultilangMessage($key)
	{
		$message = \mer\settings\Settings::$Messages[$key][\mer\base\SessionRegistry::instance()->getValue('lang')];
		if (!isset($message) || is_null($message))
		{
			$message = \mer\settings\Settings::$Messages[$key][MER_DEFAULT_LANGUAGE];
			if (!isset($message) || is_null($message))
			{
				return null;
			}
		}
		self::instance()->pushMessage($message);
	}
	
	function getMessage()
	{
		return self::instance()->get('message');
	}	
	
	function unsetMessage()
	{
		self::instance()->_unset('message');
	}
	
	function popMessage()
	{
		return self::instance()->pop('message');
	}
	
	function setSysMessage($message)
	{
		self::instance()->set('sys_message', '<p>' . $message . '</p>');
	}
	
	function pushSysMessage($message)
	{
		self::instance()->push('sys_message', '<p>' . $message . '</p>');
	}
	
	function getSysMessage()
	{
		return self::instance()->get('sys_message');
	}		
	
	function unsetSysMessage()
	{
		self::instance()->_unset('sys_message');
	}
	
	function popSysMessage()
	{
		return self::instance()->pop('sys_message');
	}
}