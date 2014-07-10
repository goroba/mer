<?php

namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once 'Registry.php';

class RequestConstructor
{
	private $params = array();
	
	function __construct()
	{
	}
	
	function bindParam($key, $val)
	{
		$this->params[$key] = $val;
	}
	
	function getParam($key)
	{
		if ( isset($this->params[$key]) )
		{
			return $this->params[$key];
		}
		return null;
	}
	
	function getForward()
	{
		$cmd = \mer\base\RequestRegistry::instance()->getRequest()->getProperty('cmd');
		if (!isset($cmd))
		{
			$this->bindParam('cmd', 'default');
			return null;
		}
		
		$forward = @\mer\settings\ControllerMap::$CmdMap[$cmd]['forward'];
		if (!isset($forward))
		{
			$this->bindParam('cmd', 'default');
			return null;
		}
		
		$this->bindParam('cmd', $forward);
		
		$params_needed = @\mer\settings\ControllerMap::$CmdMap[$forward]['params_needed'];
		if (!isset($params_needed))
			return true;
		
		$params = explode(",", $params_needed);
		
		foreach ($params as $param)
		{
			$param = trim($param);
			
			$check = \mer\base\RequestRegistry::getRequest()->getProperty($param);
			if ( !isset($check) )
			{
				return null;
			}
			else
			{
				$this->bindParam($param, $check);
			}
		}		
			
		return true;
	}
	
	function getString()
	{
		$params_str = array();
		foreach ($this->params as $key => $val)
		{
			$params_str[] = "$key=$val";
		}
		$str = implode("&", $params_str);
		return $str;
	}

}