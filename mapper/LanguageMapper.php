<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/mapper/Mapper.php';

class LanguageMapper extends Mapper
{
	protected $table = 'language';

	function __construct()
	{
		parent::__construct();
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class LanguageMapper: constructed.");
	}
}
