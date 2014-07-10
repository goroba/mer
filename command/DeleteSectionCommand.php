<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class DeleteSectionCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class DeleteSectionCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$issue_id	= $request->getProperty('issue_id');
		$section_id	= $request->getProperty('section_id');
		
		if (!is_numeric($issue_id) || !is_numeric($section_id))
		{
			$this->setError('Неверно заданы параметры.');
			$this->forward();
		}
		
		$section = \mer\base\ApplicationFactory::instance()->getDomain('Section');
		$res = $section->selectId($section_id);
						
		if (is_null($res))
		{
			$this->setError('Раздел не найден.');
			$this->forward();
		}

		$section->deleteId();
		
		\mer\base\SessionRegistry::instance()->pushMessage('Раздел (часть) удален(а) из номера журнала.');
	
		$this->forward();
	}
}