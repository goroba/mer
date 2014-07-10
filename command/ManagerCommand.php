<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/Command.php';

class ManagerCommand extends Command
{

	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class ManagerCommand->doExecute(): executed.");

		//$loader = new AuthenticateDecorator(new LanguageDecorator(new CommandLoader));
		//$loader->process();
		
		$status = \mer\base\AccessManager::instance()->checkStatus(3);
		if (!$status) 
		{
			\mer\base\SessionRegistry::instance()->pushMultilangError('MER_ERROR_ACCESS_DENIED');
			return MER_CMD_RESP_ERROR;
		}
		
		return MER_CMD_RESP_DEFAULT;
	}
}