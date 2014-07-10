<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/mapper/Mapper.php';

class StatusMapper extends Mapper
{
	protected $table = 'statuses';

	function __construct()
	{
		parent::__construct();
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class StatusMapper: constructed.");
	}
}
