<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/mapper/Mapper.php';

class ArticleMapper extends Mapper
{
	protected $table = 'articles';

	function __construct()
	{
		parent::__construct();
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class ArticleMapper: constructed.");

	}
	
	function getPath()
	{
		return MER_ARTICLE_FOLDER;
	}
	
}
