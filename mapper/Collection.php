<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/DomainObject.php';

abstract class Collection implements \Iterator
{
	protected $mapper;
	protected $total = 0;
	
	protected $result;
	protected $pointer;
	protected $objects = array();
	
	function __construct()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("Collection created");
	}
	
	abstract function domainClass();
	
	function add(\mer\domain\DomainObject $object)
	{
		$class = "\\mer\\domain\\" . $this->domainClass();
		if ( !($object instanceof $class) )
			return null;
			
		$this->objects[$this->total] = $object;
		$this->total++;
	}
	
	function addRaw(array $raw)
	{
		if (!isset($raw) || !is_array($raw))
			return null;
			
		$ret = true;
		$factory = \mer\base\ApplicationFactory::instance();
		foreach ($raw as $obj)
		{
			$object = $factory->getDomain($this->domainClass());
			if ( !is_null( $object->setArray($obj) ) )
				$this->add($object);
			else
				$ret = null;
		}

		return $ret;
	}
	
	protected function getRow($num)
	{
		if ($num >= $this->total || $num < 0)
			return null;
			
		if (isset($this->objects[$num]))
			return $this->objects[$num];
		
		return null;
	}
	
	function rewind()
	{
		$this->pointer = 0;
	}
	
	function current()
	{
		return $this->getRow($this->pointer);
	}
	
	function key()
	{
		return $this->pointer;
	}
	
	function next()
	{
		$row = $this->getRow($this->pointer);
		if ($row) $this->pointer++;
		return $row;
	}
	
	function valid()
	{
		return ( !is_null($this->current()) );
	}
	
	function sizeof()
	{
		return $this->total;
	}
}