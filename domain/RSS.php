<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/DomainObject.php';

class RSS extends DomainObject
{
	protected static $mapper;
	
	protected $columns = array(
		'id' => null,
		'link' => null,
		'file' => null,
		'number' => null,
		'header_ua' => null,
		'text_ua' => null,		
		'header_en' => null,
		'text_en' => null,		
		'header_ru' => null,
		'text_ru' => null
	);
	
	function __construct($array = null)
	{
		parent::__construct($array);
	}
	
}