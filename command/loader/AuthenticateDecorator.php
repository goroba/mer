<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class AuthenticateDecorator extends CommandLoaderDecorator
{

	function doProcess()
	{
		//\mer\base\SessionRegistry::instance()->setSysMessage('class AuthenticateDecorator->doProcess(): executed.');
		
		\mer\base\AccessManager::instance()->doAuth();
	}
	

}