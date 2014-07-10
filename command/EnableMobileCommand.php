<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class EnableMobileCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class EnableMobileCommand->doExecute(): executed.");
		
		\mer\base\SessionRegistry::instance()->setValue('mobile_enable', true);
		
		return MER_CMD_RESP_DEFAULT;
	}
}