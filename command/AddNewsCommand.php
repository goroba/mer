<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class AddNewsCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class AddNewsCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();

		$values = $request->getProperties();
		
		//var_dump($values);
		
		$values['title_ua'] = trim(strip_tags($values['title_ua']));
		$values['title_en'] = trim(strip_tags($values['title_en']));
		$values['title_ru'] = trim(strip_tags($values['title_ru']));
		
		$values['text_ua'] = nl2br(trim(strip_tags($values['text_ua'])));
		$values['text_en'] = nl2br(trim(strip_tags($values['text_en'])));
		$values['text_ru'] = nl2br(trim(strip_tags($values['text_ru'])));
		
		$values['sign_ua'] = trim(strip_tags($values['sign_ua']));
		$values['sign_en'] = trim(strip_tags($values['sign_en']));
		$values['sign_ru'] = trim(strip_tags($values['sign_ru']));
		
		
		//filename
		$file_name = preg_replace("/[\W]+/", "_", $values['title_en']);
		if (strlen($file_name) > 121)
			$file_name = substr($file_name, 0, 121);

		$file_name = $file_name . '.png';
		
		$news = \mer\base\ApplicationFactory::instance()->getDomain('News');

		if ($values['action'] == 'create') // insert
		{
			$news->setArraySoft($values);
			
			if (is_null($news->setFileAnyway('image', $file_name)))
				$this->setError("Ошибка при загрузке изображения.");
			
			$news_number = ((int) $news->getMax('number')) + 1;
			$news->set('number', $news_number);
			$news->set('id', $news->insert());
			
			
			\mer\base\SessionRegistry::instance()->pushMessage('Новость добавлена.');
		}
		else if ($values['action'] == 'update') // update
		{
			if (is_null($news->selectId($values['id'])))
				$this->setError('Редактируемая новость не существует.');
			$news->setArraySoft($values);
			
			if (is_null($news->resetFileAnyway('image', $file_name)))
				$this->setError("Ошибка при загрузке изображения.");
			
			$news->update(array('id'), array('image', 'title_ua', 'title_en', 'title_ru'));
			$news->update(array('id'), array('text_ua', 'text_en', 'text_ru', 'sign_ua', 'sign_en', 'sign_ru'));
			
			\mer\base\SessionRegistry::instance()->pushMessage('Новость изменена.');
		}
			
		
		$this->forward();
	}
}