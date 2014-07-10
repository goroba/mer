<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class LoginCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class LoginCommand->doExecute(): executed.");
		
		$login = \mer\base\RequestRegistry::instance()->getRequest()->getProperty('login');
		$password = \mer\base\RequestRegistry::instance()->getRequest()->getProperty('password');
		$memorize = \mer\base\RequestRegistry::instance()->getRequest()->getProperty('memorize');

		\mer\base\AccessManager::instance()->doLogin($login, $password, $memorize);

		return MER_CMD_RESP_DEFAULT;
	}
}