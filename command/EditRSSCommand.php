<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class EditRSSCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class EditRSSCommand->doExecute(): executed.");
		
		$tpl = \mer\base\Templater::instance();
		$request    = \mer\base\RequestRegistry::instance()->getRequest();
		
		$id     = $request->getProperty('id');
		$action = $request->getProperty('action');

		if (!isset($action))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class EditRSSCommand->doExecute(): parameter 'action' not found.");
			return $this->setError("Неверно заданы параметы.");
		}
		
		$rss = \mer\base\ApplicationFactory::instance()->getDomain('RSS');
			
		if ($action == 'create') // create
		{
		}
		else if (isset($id) && $action == 'update') // update
		{
			if (is_null($rss->selectId($id)))
				return $this->setError("Новость не найдена.");
		}
		else
		{
			return $this->setError("Неверно заданы параметы.");
		}

		$tpl->setArray($rss->getArray());
		$tpl->set('action', $action);
		
		return MER_CMD_RESP_DEFAULT;
	}
}