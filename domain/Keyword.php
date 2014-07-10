<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/DomainObject.php';

class Keyword extends DomainObject
{
	protected static $mapper;
		
	protected $columns = array(
		'id' => null,
		'article_id' => null,
		'word' => null,
		'language' => null
	);
	
	function __construct($array = null)
	{
		parent::__construct($array);
	}
	
}