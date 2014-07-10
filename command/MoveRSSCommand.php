<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class MoveRSSCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class MoveRSSCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$number		= intval($request->getProperty('number'));
		$delta		= intval($request->getProperty('delta'));
		
		if (!is_numeric($number) || !is_numeric($delta))
		{
			$this->setError('Неверно заданы параметры.');
			$this->forward();
		}
		
		$rss = \mer\base\ApplicationFactory::instance()->getDomain('RSS');
		$res = $rss->selectObject(array('number' => $number));
		
		if (is_null($res))
			$this->setError('Новость не найдена.');
		
		$rss_next = \mer\base\ApplicationFactory::instance()->getDomain('RSS');
		
		if ($delta > 0)
		{
			if (is_null($rss_next->getPrevious('number', $number)))
				$this->setError('Предыдущая новость не найдена.');
		}
		else if ($delta < 0)
		{
			if (is_null($rss_next->getNext('number', $number)))
				$this->setError('Следующая новость не найдена.');
		}
		
		
		$rss->set('number', $rss_next->get('number'));
		$rss_next->set('number', $number);
		
		$rss->update(array('id'), array('number'));
		$rss_next->update(array('id'), array('number'));
		
		//\mer\base\SessionRegistry::instance()->pushMessage('Элемент перемещен.');
	
		$this->forward();
	}
}