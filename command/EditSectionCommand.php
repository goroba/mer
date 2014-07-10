<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class EditSectionCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class EditissueCommand->doExecute(): executed.");
		
		$tpl = \mer\base\Templater::instance();
		
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$issue_id 		= $request->getProperty('issue_id');
		$level 			= $request->getProperty('level');
		
		$section_id 	= $request->getProperty('section_id');
				
		if (isset($issue_id) && isset($level))
		{
			if (!is_numeric($issue_id) || !is_numeric($level))
				return $this->setError("Парамерты для редактирования заданы неверно: памареты не целые числа.");
			
			$issue_id = intval($issue_id);
			$level = intval($level);
			
			$tpl->set('section_id', '');
			$tpl->set('number', '');
			$tpl->set('issue_id', $issue_id);
			$tpl->set('level', $level);		

			$tpl->set('caption_ua', '');
			$tpl->set('caption_en', '');
			$tpl->set('caption_ru', '');
		}
		else if (isset($section_id))
		{
			if (!is_numeric($section_id))
				return $this->setError("Парамерты для редактирования заданы неверно: памареты не целые числа.");
			
			$section_id = intval($section_id);
			
			$tpl->set('section_id', $section_id);
			$tpl->set('number', '');
			$tpl->set('issue_id', '');
			$tpl->set('level', '');	
			
			$section = \mer\base\ApplicationFactory::instance()->getDomain('Section');
			$data = $section->getMapper()->select(array('id' => $section_id));
			$tpl->set('caption_ua', $data[0]['caption_ua']);
			$tpl->set('caption_en', $data[0]['caption_en']);
			$tpl->set('caption_ru', $data[0]['caption_ru']);			
		}
		else
		{
			return $this->setError("Парамерты для редактирования не заданы или заданы неверно.");
		}
		
		return MER_CMD_RESP_DEFAULT;
	}
}