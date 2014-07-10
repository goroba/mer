<?php

namespace mer\command;

require_once MER_SITE . '/command/ActionCommand.php';

defined ('MER_EXISTS') or die('Illegal Access!!!');

class DeleteIssueCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class DeleteIssueCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$issue_id	= $request->getProperty('issue_id');
		
		if (!is_numeric($issue_id))
			$this->setError('Неверно заданы параметры.');
		
		$issue = \mer\base\ApplicationFactory::instance()->getDomain('Issue');
		if (is_null($issue->selectId($issue_id)))
			$this->pushMultilangError(MER_ERROR_ISSUE_NOT_FOUND);

		$content = \mer\base\ApplicationFactory::instance()->getCollection('Content');
		$res = $content->getContent($issue_id);
		
		if (is_null($res))
			return MER_CMD_RESP_ERROR;

		foreach ($content as $item)
		{
			if ($item instanceof \mer\domain\Section)
			{
				$section = $item;
				$section->deleteId();
			}
			
			if ($item instanceof \mer\domain\Article)
			{
				$article = $item;
				
				$keyword = \mer\base\ApplicationFactory::instance()->getDomain('Keyword');
				$keyword->set('article_id', $article->get('id'));
				$keyword->deleteObject(array('article_id'));
		
				$article->deleteFile('link');
				$article->deleteFile('link_ua');
				$article->deleteFile('link_en');
				$article->deleteFile('link_ru');

				$article->deleteId();
				
			}
		}
			
		$issue->deleteId();
		
		\mer\base\SessionRegistry::instance()->pushMessage('Номер журнала удален.');
	
		$this->forward();
	}
}