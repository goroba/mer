<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/DomainObject.php';

class Issue extends DomainObject
{
	protected static $mapper;
		
	protected $columns = array(
		'id' => null,
		'year' => null,
		'issue' => null,
		'suffix' => null,
		'access' => null
	);
	
	function __construct()
	{
		parent::__construct();
	}
	
	function getString()
	{
		$str = '№' . $this->columns['issue'] . ', ' . $this->columns['year'];
		
		if (!empty($this->columns['suffix']))
			$str .= "'" . $this->columns['suffix'];
		
		return $str;
	}
	
}