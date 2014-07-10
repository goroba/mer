<?php

namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class Templater
{
	private static $instance;
	private $values = array ();
	private $variable = '';
	
	private function __construct()
	{
		$this->loadDefaultValues();
	}
	
	static function instance()
	{
		if ( !isset(self::$instance) )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	function get($key)
	{
		if ( isset($this->values[$key]) )
		{
			return $this->values[$key];
		}
		return null;
	}
	
	function set($key, $val)
	{
		$this->values[$key] = $val;
	}
	
	function setVar($var)
	{
		$this->variable = $var;
	}
	
	function replace($text)
	{
		return preg_replace("/\\$\\$([_a-zA-Z0-9]+)\\$\\$/", "\$".$this->variable."->get('\$1')", $text);		
	}
	
	function display($text)
	{
	}
	
	function loadDefaultValues()
	{
		$sess = \mer\base\SessionRegistry::instance();
		$this->set('__lang', $sess->getValue('lang'));
		
		$login = \mer\base\SessionRegistry::instance()->getValue(MER_SESS_ACCESS_MANAGER);
		$this->set('__is_logged', $login['is_logged']);
		if (!isset($login['is_logged']) || $login['is_logged'] != true)
		{
			return;
		}
		else
		{
			//$this->set('__user_id', $login['user']['user_id']);
			$this->set('__login', $login['user']['login']);
			$this->set('__status', $login['user']['status']);
		}
	}
	
	function setArray(array $array)
	{
		if (!isset($array) || !is_array($array))
			return null;
			
		foreach ($array as $key => $val)
			$this->set($key, $val);

		return true;
	}
}