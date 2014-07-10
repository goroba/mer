<?php

namespace mer\command;

require_once MER_SITE . '/command/ActionCommand.php';

defined ('MER_EXISTS') or die('Illegal Access!!!');

class DeleteArticleCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class DeleteArticleCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$issue_id	= $request->getProperty('issue_id');
		$article_id	= $request->getProperty('article_id');
		
		if (!is_numeric($issue_id) || !is_numeric($article_id))
		{
			$this->setError('Неверно заданы параметры.');
			$this->forward();
		}
		
		$article = \mer\base\ApplicationFactory::instance()->getDomain('Article');
		$res = $article->selectId($article_id);
						
		if (is_null($res))
		{
			$this->setError('Статья не найдена.');
			$this->forward();
		}

		//delete keywords
		$keyword = \mer\base\ApplicationFactory::instance()->getDomain('Keyword');
		$keyword->set('article_id', $article_id);
		$keyword->deleteObject(array('article_id'));
		
		$article->deleteFile('link');
		$article->deleteFile('link_ua');
		$article->deleteFile('link_en');
		$article->deleteFile('link_ru');

		$article->deleteId();
		
		\mer\base\SessionRegistry::instance()->pushMessage('Статья удалена из номера журнала.');
	
		$this->forward();
	}
}