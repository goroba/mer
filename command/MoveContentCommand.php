<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class MoveContentCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class MoveContentCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$issue_id	= intval($request->getProperty('issue_id'));
		$number		= intval($request->getProperty('number'));
		$delta		= intval($request->getProperty('delta'));
		
		if (!is_numeric($issue_id) || !is_numeric($number) || !is_numeric($delta))
		{
			$this->setError('Неверно заданы параметры.');
			$this->forward();
		}
		
		$content = \mer\base\ApplicationFactory::instance()->getCollection('Content');
		$res = $content->getContent($issue_id);
		
		if (is_null($res))
			$this->forward();

		$i = $number;
		$j = $i + $delta;
		
		$content->swap($i, $j);
		
		//\mer\base\SessionRegistry::instance()->pushMessage('Элемент перемещен.');
	
		$this->forward();
	}
}