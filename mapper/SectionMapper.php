<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/mapper/Mapper.php';

class SectionMapper extends Mapper
{
	protected $table = 'sections';

	function __construct()
	{
		parent::__construct();
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class SectionMapper: constructed.");
	}
}
