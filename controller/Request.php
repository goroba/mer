<?php

namespace mer\controller;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/base/RequestRegistry.php';

class Request
{
	private $properties;
	private $feedback = array();
	
	function __construct()
	{
		$this->init();
		\mer\base\RequestRegistry::setRequest($this);
	}

	function init()
	{
		if (isset($_SERVER['REQUEST_METHOD']))
		{
			$this->properties = $_REQUEST;
			return;
		}
		
		foreach ($_SERVER['argv'] as $arg)
		{
			if (strpos($arg, '='))
			{
				list($key, $val) = explode ("=", $arg);
				$this->setProperty($key, $val);
			}
		}
	}
	
	function getProperty($key)
	{
		if (isset($this->properties[$key]))
		{
			return $this->properties[$key];
		}
		return null;
	}
	
	function setProperty($key, $val)
	{
		$this->properties[$key] = $val;
	}
	
	function setFeedback($msg)
	{
		array_push($this->feedback, $msg);
	}
	
	function getFeedback()
	{
		return $this->feedback;
	}
	
	function getProperties()
	{
		return $this->properties;
	}
	
	function getFullRequest()
	{
		$commands = '';
		foreach ($this->properties as $key => $val)
		{
			$commands .= $key . '=' . $val . '&';
		}
		return $commands;
	}
	
	function getLangRequest($lang)
	{
		$properties = $this->properties;
		$properties['lang'] = $lang;
		$commands = '';
		foreach ($properties as $key => $val)
		{
			$commands .= $key . '=' . $val . '&';
		}
		return $commands;	
	}

}
