<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class DeleteNewsCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class DeleteNewsCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$news_id	= $request->getProperty('id');
		
		if (!isset($news_id) || !is_numeric($news_id))
		{
			$this->setError('Неверно заданы параметры.');
			$this->forward();
		}
		
		$news_id = intval($news_id);
		
		$news = \mer\base\ApplicationFactory::instance()->getDomain('News');
		$res = $news->selectId($news_id);
						
		if (is_null($res))
		{
			$this->setError('Новость не найдена.');
			$this->forward();
		}

		$news->deleteFile('image');
		$news->deleteId();
		
		\mer\base\SessionRegistry::instance()->pushMessage('Новость удалена.');
	
		$this->forward();
	}
}