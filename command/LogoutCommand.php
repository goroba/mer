<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class LogoutCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class LogoutCommand->doExecute(): executed.");
		
		\mer\base\AccessManager::instance()->doLogout();
		
		return MER_CMD_RESP_DEFAULT;
	}
}