<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class AddSectionCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class AddSectionCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$section_id	= $request->getProperty('section_id');
		$number		= $request->getProperty('number');
		$issue_id	= $request->getProperty('issue_id');
		$level		= $request->getProperty('level');
		$caption_ua	= trim($request->getProperty('caption_ua'));
		$caption_en	= trim($request->getProperty('caption_en'));
		$caption_ru	= trim($request->getProperty('caption_ru'));
		
		//---todo Checking data
		
		if (empty($section_id)) // insert
		{
			//\mer\base\SessionRegistry::instance()->pushSysMessage("class AddSectionCommand->insert(): executed.");
			$section_mapper	= \mer\base\ApplicationFactory::instance()->getMapper('Section');
			
			$section		= \mer\base\ApplicationFactory::instance()->getDomain('Section');
			$section->setArray(array(
				'id' => null,
				'number' => null,
				'issue_id' => $issue_id,
				'caption_ru' => $caption_ru,
				'caption_ua' => $caption_ua,
				'caption_en' => $caption_en,
				'level' => $level
			));
						
			$section_mapper->insert($section);
			\mer\base\SessionRegistry::instance()->pushMessage('Новый раздел (часть) добавлен(а) в номер журнала.');
		}
		else if (empty($issue_id) && empty($level)) // update
		{
			//\mer\base\SessionRegistry::instance()->pushSysMessage("class AddSectionCommand->update(): executed.");
			
			$section = \mer\base\ApplicationFactory::instance()->getDomain('Section');
			
			$section->setArray($section->getMapper()->select_obj(array('id' => $section_id)));
			
			$section->set('caption_ua', $caption_ua);
			$section->set('caption_ru', $caption_ru);
			$section->set('caption_en', $caption_en);
			
			$section->getMapper()->update($section, array('id'), array('caption_ua', 'caption_en', 'caption_ru'));
			
			\mer\base\RequestRegistry::instance()->getRequest()->setProperty('issue_id', $section->get('issue_id'));
		}
		else 
		{
			$this->setError("Парамерты для редактирования не заданы или заданы неверно.");
		}
		
		$this->forward();
	}
	
}