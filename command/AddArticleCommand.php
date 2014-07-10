<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class AddArticleCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class LoginCommand->AddissueCommand(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();

		$values = $request->getProperties();
		
		$values['authors_ua'] = trim(strip_tags($values['authors_ua']));
		$values['authors_en'] = trim(strip_tags($values['authors_en']));
		$values['authors_ru'] = trim(strip_tags($values['authors_ru']));
		
		$values['caption_ua'] = trim(strip_tags($values['caption_ua']));
		$values['caption_en'] = trim(strip_tags($values['caption_en']));
		$values['caption_ru'] = trim(strip_tags($values['caption_ru']));
		
		$values['affiliations_ua'] = trim(strip_tags($values['affiliations_ua']));
		$values['affiliations_en'] = trim(strip_tags($values['affiliations_en']));
		$values['affiliations_ru'] = trim(strip_tags($values['affiliations_ru']));
		
		$values['annotation_ua'] = trim(strip_tags($values['annotation_ua']));
		$values['annotation_en'] = trim(strip_tags($values['annotation_en']));
		$values['annotation_ru'] = trim(strip_tags($values['annotation_ru']));
		
		$values['keywords_ua'] = trim(strip_tags($values['keywords_ua']));
		$values['keywords_en'] = trim(strip_tags($values['keywords_en']));
		$values['keywords_ru'] = trim(strip_tags($values['keywords_ru']));
		
		if (!is_numeric($values['page_from'])) return $this->setError('Номер страницы должен быть числом.');
		$values['page_from'] = intval($values['page_from']);
		
		if (!is_numeric($values['page_to'])) return $this->setError('Номер страницы должен быть числом.');
		$values['page_to'] = intval($values['page_to']);
		
		if ($values['page_from'] > $values['page_to']) return $this->setError('Страница начала статьи не может быть больше страницы окончания.');
				
		$article = \mer\base\ApplicationFactory::instance()->getDomain('Article');
		
		//keywords
		foreach (array('ua', 'en', 'ru') as $lang)
		{
			if (strpos($values['keywords_'.$lang], ':') !== false)
				if (preg_match("/^[^:]*:(.*)\.\s*/", $values['keywords_'.$lang], $keywords)) 
					$values['keywords_'.$lang] = $keywords[1];
				else
					$values['keywords_'.$lang] = '';
			
			$keywords_array = explode(',', $values['keywords_'.$lang]);
			$keywords_array = array_map('trim', $keywords_array);
			
			$values['keywords_'.$lang] = implode(', ', $keywords_array);				
		}
		
		//language
		if ($values['_language'] == 1)
			$article->set('language', 'ua');
		else if ($values['_language'] == 2)
			$article->set('language', 'en');
		else if ($values['_language'] == 3)
			$article->set('language', 'ru');
		else
			$article->set('language', 'ua');
		
		$article->setArraySoft($values);
				
		//filenames
		$dir = 'issue_' . $values['issue_id'] . '/';
		$file_name = preg_replace("/[\W]+/", "_", $values['authors_en']) . preg_replace("/[\W]+/", "_", $values['caption_en']);
		if (strlen($file_name) > 121)
			$file_name = substr($file_name, 0, 121);

		$file_name_ua = $dir . $file_name . '_ua.pdf';
		$file_name_en = $dir . $file_name . '_en.pdf';
		$file_name_ru = $dir . $file_name . '_ru.pdf';
		$file_name    = $dir . $file_name . '.pdf';
			
		if ($values['action'] == 'create' && isset($values['issue_id'])) // insert
		{
			if (is_null($article->setFileAnyway('link', $file_name)))
				$this->setError("Ошибка при загрузке основного файла статьи на сервер.");
			if (is_null($article->setFileAnyway('link_ua', $file_name_ua)))
				$this->setError("Ошибка при загрузке украинской версии статьи на сервер.");
			if (is_null($article->setFileAnyway('link_en', $file_name_en)))
				$this->setError("Ошибка при загрузке английской версии статьи на сервер.");
			if (is_null($article->setFileAnyway('link_ru', $file_name_ru)))
				$this->setError("Ошибка при загрузке русской версии статьи на сервер.");
				
			$article->set('number', PHP_INT_MAX);
			$id = $article->insert(true);
		}
		else if ($values['action'] == 'update' && isset($values['id'])) // update
		{
			
			if (is_null($article->resetFileAnyway('link', $file_name)))
				$this->setError("Ошибка при загрузке нового файла на сервер.");
			if (is_null($article->resetFileAnyway('link_ua', $file_name_ua)))
				$this->setError("Ошибка при загрузке нового файла на сервер.");
			if (is_null($article->resetFileAnyway('link_en', $file_name_en)))
				$this->setError("Ошибка при загрузке нового файла на сервер.");
			if (is_null($article->resetFileAnyway('link_ru', $file_name_ru)))
				$this->setError("Ошибка при загрузке нового файла на сервер.");
			
			$article->update(array('id'), array('number', 'issue_id', 'authors_ru', 'authors_ua', 'authors_en', 'caption_ru', 'caption_ua'));
			$article->update(array('id'), array('caption_en', 'language', 'affiliations_ru', 'affiliations_ua', 'affiliations_en', 'annotation_ru', 'annotation_ua', 'annotation_en'));
			$article->update(array('id'), array('link_ru', 'link_ua', 'link_en', 'link', 'keywords_ua', 'keywords_en', 'keywords_ru'));
			$article->update(array('id'), array('page_from', 'page_to'));
		}
			
		
		

		\mer\base\SessionRegistry::instance()->pushMessage('Статья добалена.');
	
		$this->forward();
	}
}