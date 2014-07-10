<?php

namespace mer\command;

require_once MER_SITE . '/command/Command.php';

defined ('MER_EXISTS') or die('Illegal Access!!!');

class DefaultCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class DefaultCommand->doExecute(): executed.");
		
		//$loader = new AuthenticateDecorator(new LanguageDecorator(new CommandLoader));
		//$loader->process();
		
		return MER_CMD_RESP_DEFAULT;
	}
}