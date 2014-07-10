<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class DeleteRSSCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class DeleteRSSCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$rss_id	= $request->getProperty('id');
		
		if (!isset($rss_id) || !is_numeric($rss_id))
		{
			$this->setError('Неверно заданы параметры.');
			$this->forward();
		}
		
		$rss_id = intval($rss_id);
		
		$rss = \mer\base\ApplicationFactory::instance()->getDomain('RSS');
		$res = $rss->selectId($rss_id);
						
		if (is_null($res))
		{
			$this->setError('Новость не найдена.');
			$this->forward();
		}

		$rss->deleteFile('file');
		$rss->deleteId();
		
		\mer\base\SessionRegistry::instance()->pushMessage('Новость удалена.');
	
		$this->forward();
	}
}