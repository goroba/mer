<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class AdminDecorator extends CommandLoaderDecorator
{
	function doProcess()
	{
		//\mer\base\SessionRegistry::instance()->setValue('lang', MER_DEFAULT_LANGUAGE);
		\mer\base\SessionRegistry::instance()->pushSysMessage("class AdminDecorator->doProcess(): executed");
		
	}
}