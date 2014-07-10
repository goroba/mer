<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class DisableMobileCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class DisableMobileCommand->doExecute(): executed.");
				
		\mer\base\SessionRegistry::instance()->setValue('mobile_enable', false);
		
		return MER_CMD_RESP_DEFAULT;
	}
}