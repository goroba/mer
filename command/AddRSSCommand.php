<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class AddRSSCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class AddRSSCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();

		$values = $request->getProperties();
		
		$values['header_ua'] = trim(strip_tags($values['header_ua']));
		$values['header_en'] = trim(strip_tags($values['header_en']));
		$values['header_ru'] = trim(strip_tags($values['header_ru']));
		
		$values['text_ua'] = trim(strip_tags($values['text_ua']));
		$values['text_en'] = trim(strip_tags($values['text_en']));
		$values['text_ru'] = trim(strip_tags($values['text_ru']));
		
		$rss = \mer\base\ApplicationFactory::instance()->getDomain('RSS');
				
		if ($values['action'] == 'create') // insert
		{
			$rss->setArraySoft($values);
			
			if ($values['file_or_link'] == 'link')
			{
				if (empty($values['_link']))
					$this->setError('Не указана ссылка на новость.');
				$rss->set('link', $values['_link']);
				$rss->set('file', '');
			}
			else if ($values['file_or_link'] == 'file')
			{
				$file_name = preg_replace("/[\W]+/", "_", $values['header_en']);
				if (strlen($file_name) > 124)
					$file_name = substr($file_name, 0, 124);
				$file_name .= '.pdf';
				
				if (is_null($rss->setFileAnyway('file', $file_name)))
					$this->setError("Ошибка при загрузке файла на сервер.");
				
				$rss->set('link', '');
			}
			
			$rss->set('number', ((int) $rss->getMax('number')) + 1);
			$rss->insert();
			\mer\base\SessionRegistry::instance()->pushMessage('Новость добалена.');
		}
		else if ($values['action'] == 'update') // update
		{
			if (is_null($rss->selectId($values['id'])))
				$this->setError('Редактируемая новость не существует.');
			$rss->setArraySoft($values);
			
			if ($values['file_or_link'] == 'link')
			{
				if (empty($values['_link']))
					$this->setError('Не указана ссылка на новость.');
				
				$rss->set('link', $values['_link']);
				$rss->deleteFile('file');
			}
			else if ($values['file_or_link'] == 'file')
			{
				$file_name = preg_replace("/[\W]+/", "_", $values['header_en']);
				if (strlen($file_name) > 124)
					$file_name = substr($file_name, 0, 124);
				$file_name .= '.pdf';
				
				if (is_null($rss->resetFileAnyway('file', $file_name)))
					$this->setError("Ошибка при загрузке нового файла на сервер.");
				
				$rss->set('link', '');
			}
			$rss->updateId();
		}
			
		
		$this->forward();
	}
}