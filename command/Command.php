<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

abstract class Command
{

	final function __construct()
	{
	}
	
	function execute()
	{		
		return $this->doExecute();
	}
	
	abstract protected function doExecute();
	
	protected function forward(\mer\base\RequestConstructor $request = null)
	{
		if (!isset($request))
		{
			$request = \mer\base\ApplicationFactory::instance()->getRequestConstructor();
			$request->getForward();
		}
				
		$str = $request->getString();
			
		header ("Location: index.php?" . $str);
		exit;
	}

	protected function setError($message)
	{
		\mer\base\SessionRegistry::instance()->pushError($message);
		return MER_CMD_RESP_ERROR;
	}
	
	protected function pushMultilangError($key)
	{
		\mer\base\SessionRegistry::instance()->pushMultilangError($key);
		return MER_CMD_RESP_ERROR;
	}
}