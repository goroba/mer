<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/DomainObject.php';

class Language extends DomainObject
{
	protected static $mapper;
		
	protected $columns = array(
		'id' => null,
		'desc' => null,
		'language' => null,
		'text' => null
	);
	
	function __construct($array = null)
	{
		parent::__construct($array);
	}
	
}