<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/Content.php';

class Section extends Content
{
	protected static $mapper;
	
	protected $columns = array(
		'id' => null,
		'number' => null,
		'issue_id' => null,
		'caption_ru' => null,
		'caption_ua' => null,
		'caption_en' => null,
		'level' => null
	);
	
	function __construct($array = null)
	{
		parent::__construct($array);
	}
	
}