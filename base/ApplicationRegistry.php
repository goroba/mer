<?php

namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once 'AppException.php';

class ApplicationRegistry
{
	private static $instance;
	private $config = "../settings/settings.xml";
	private $freezedir = "cache_data";
	private $values = array();
	private $mtimes = array();
	
	private function __construct() {}
	
	static function instance()
	{
		if (!self::$instance)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	function init()
	{
		//$loader = new \mer\command\AuthenticateDecorator(new \mer\command\LanguageDecorator(new \mer\command\CommandLoader));
		//$loader->process();
	}
	
	private function getOptions()
	{
	}
	
	protected function get($key)
	{
		$path = $this->freezedir . "/" . $key;
		if (file_exists($path))
		{
			clearstatcache();
			$mtime = filemtime($path);
			if (!isset($this->mtimes[$key]))
			{
				$this->mtimes[$key] = 0;
			}
			if ($mtime > $this->mtimes[$key])
			{
				$data = file_get_contents($path);
				$this->mtimes[$key] = $mtime;
				return ($this->values[$key] = unserialize($data));
			}
		}
		if (isset($this->values[$key]))
		{
				return $this->values[$key];
		}
		
		return null;		
	}
	
	protected function set($key, $val)
	{
		$this->values[$key] = $val;
		$path = $this->freezedir . "/" . $key;
		file_put_contents($path, serialize($val));
		$this->mtimes[$key] = time();
	}
	
	static function getDSN()
	{
		return self::instance()->get('dsn');
	}
	
	static function setDSN($dsn)
	{
		return self::instance()->set('dsn', $dsn);
	}
	
	private function ensure($expr, $message)
	{
		if (!$expr)
		{
			throw new AppException($message);
		}
	}
}