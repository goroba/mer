<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/Content.php';

class Article extends Content
{
	protected static $mapper;
	
	protected $columns = array(
		'id' => null,
		'number' => null,
		'issue_id' => null,
		
		'authors_ru' => null,
		'authors_ua' => null,
		'authors_en' => null,
		
		'caption_ru' => null,
		'caption_ua' => null,
		'caption_en' => null,
		
		'language' => null,
		
		'affiliations_ru' => null,
		'affiliations_ua' => null,
		'affiliations_en' => null,
		
		'annotation_ru' => null,
		'annotation_ua' => null,
		'annotation_en' => null,
		
		'link_ru' => null,
		'link_ua' => null,
		'link_en' => null, 
		
		'link' => null,
		
		'keywords_ua' => null,
		'keywords_en' => null,
		'keywords_ru' => null,
		
		'page_from' => null,
		'page_to' => null
	);
	
	function __construct($array = null)
	{
		parent::__construct($array);
	}
}