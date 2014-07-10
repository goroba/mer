<?php
namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class ApplicationFactory
{
	private static $instance;
	private $values = array ();
	
	private function __construct() {}
	
	static function instance()
	{
		if (!self::$instance)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	protected function get($key)
	{
		if ( isset($this->values[$key]) )
		{
			return $this->values[$key];
		}
		return null;
	}
	
	protected function set($key, $val)
	{
		$this->values[$key] = $val;
	}

	function getDBH()
	{	
		$dbh = $this->get('dbh');
		
		if (is_null($dbh))
		{
			$opt = array(
				\PDO::ATTR_ERRMODE				=> \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE	=> \PDO::FETCH_ASSOC
			);
		
			if (!($dbh = new \PDO("mysql:host=" . MER_DB_HOST . ";dbname=" . MER_DB_NAME, MER_DB_USER, MER_DB_PASSWORD, $opt)))
			{
				\mer\base\SessionRegistry::instance()->pushError("Problems while connecting to database.");
				\mer\base\SessionRegistry::instance()->pushSysMessage("class SessionRegistry->getDBH(): Can't connect to database");
				return null;
			};
			$this->set('dbh', $dbh);
		}

		return $dbh;
	}
	
	function getMapper($class)
	{
		if ( preg_match('/\W/', $class) )
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class ApplicationFactory->getMapper(\$class): inadmissible character(s) in mappers class name");
			return null;
		}
		
		$class = ucfirst($class . 'Mapper');
		
		$file = MER_MAPPER_FOLDER . "/{$class}.php";
		
		$class = "\\mer\\mapper\\" . $class;
		
		if ( !file_exists( $file ) )
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class ApplicationFactory->getMapper(\$class): mapper file \"$file\"doesn't exist");
			return null;
		}
		
		require_once ($file);
		
		if ( !class_exists( $class ) )
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class ApplicationFactory->getMapper(\$class): mapper class \"$class\"doesn't exist");
			return null;
		}
				
		$mapper = new $class;
		return $mapper;
	}
	
	function getDomain($class)
	{
		if ( preg_match('/\W/', $class) )
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class ApplicationFactory->getDomain(\$class): inadmissible character(s) in domain class name.");
			return null;
		}
		
		$class = ucfirst($class);
		
		$file = MER_DOMAIN_FOLDER . "/{$class}.php";
		
		$class = "\\mer\\domain\\" . $class;
		
		if ( !file_exists( $file ) )
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class ApplicationFactory->getDomain(\$class): domain file \"$file\"doesn't exist.");
			return null;
		}
		
		require_once ($file);
		
		if ( !class_exists( $class ) )
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class ApplicationFactory->getDomain(\$class): domain class \"$class\"doesn't exist.");
			return null;
		}
				
		$domain = new $class;
		return $domain;
	}

	function getCollection($class)
	{
		if ( preg_match('/\W/', $class) )
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class ApplicationFactory->getCollection(\$class): inadmissible character(s) in collection class name");
			return null;
		}
		
		$class = ucfirst($class . 'Collection');
		
		$file = MER_MAPPER_FOLDER . "/{$class}.php";
		
		$class = "\\mer\\mapper\\" . $class;
		
		if ( !file_exists( $file ) )
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class ApplicationFactory->getCollection(\$class): collection file \"$file\"doesn't exist");
			return null;
		}
		
		require_once ($file);
		
		if ( !class_exists( $class ) )
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class ApplicationFactory->getCollection(\$class): collection class \"$class\"doesn't exist");
			return null;
		}
				
		$collection = new $class;
		return $collection;
	}	
	
	function getRequestConstructor()
	{
		require_once (MER_SITE . '/base/RequestConstructor.php');
		return new \mer\base\RequestConstructor;
	}
	
	function getFileRegistry()
	{
		require_once (MER_SITE. '/base/FileRegistry.php');
		return \mer\base\FileRegistry::instance();
	}
}
