<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class EditNewsCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class EditNewsCommand->doExecute(): executed.");
		
		$tpl = \mer\base\Templater::instance();
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$id     = $request->getProperty('id');
		$action = $request->getProperty('action');

		if (!isset($action))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class EditNewsCommand->doExecute(): parameter 'action' not found.");
			return $this->setError("Неверно заданы параметы.");
		}
		
		$news = \mer\base\ApplicationFactory::instance()->getDomain('News');
			
		if ($action == 'create') // create
		{
		}
		else if (isset($id) && $action == 'update') // update
		{
			if (is_null($news->selectId($id)))
				return $this->setError("Новость не найдена.");
		}
		else
		{
			return $this->setError("Неверно заданы параметы.");
		}

		$tpl->setArray($news->getArray());
		$tpl->set('action', $action);
		
		return MER_CMD_RESP_DEFAULT;
	}
}