<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class MoveNewsCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class MoveNewsCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$number		= intval($request->getProperty('number'));
		$delta		= intval($request->getProperty('delta'));
		
		if (!is_numeric($number) || !is_numeric($delta))
		{
			$this->setError('Неверно заданы параметры.');
			$this->forward();
		}
		
		$news = \mer\base\ApplicationFactory::instance()->getDomain('News');
		$res = $news->selectObject(array('number' => $number));
		
		if (is_null($res))
			$this->setError('Новость не найдена.');
		
		$news_next = \mer\base\ApplicationFactory::instance()->getDomain('News');
		
		if ($delta > 0)
		{
			if (is_null($news_next->getPrevious('number', $number)))
				$this->setError('Предыдущая новость не найдена.');
		}
		else if ($delta < 0)
		{
			if (is_null($news_next->getNext('number', $number)))
				$this->setError('Следующая новость не найдена.');
		}
		
		
		$news->set('number', $news_next->get('number'));
		$news_next->set('number', $number);
		
		$news->update(array('id'), array('number'));
		$news_next->update(array('id'), array('number'));
		
		//\mer\base\SessionRegistry::instance()->pushMessage('Элемент перемещен.');
	
		$this->forward();
	}
}