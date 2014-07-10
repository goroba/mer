<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/DomainObject.php';

class News extends DomainObject
{
	protected static $mapper;
	
	protected $columns = array(
		'id' => null,
		'image' => null,
		'title_ua' => null,
		'title_en' => null,
		'title_ru' => null,
		'text_ua' => null,
		'text_en' => null,
		'text_ru' => null,
		'sign_ua' => null,
		'sign_en' => null,
		'sign_ru' => null,
		'date' => null,
		'number' => null
	);
	
	function __construct($array = null)
	{
		parent::__construct($array);
	}
	
}