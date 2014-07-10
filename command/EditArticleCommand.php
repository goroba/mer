<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class EditArticleCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class EditArticleCommand->doExecute(): executed.");
		
		$tpl = \mer\base\Templater::instance();
		$request    = \mer\base\RequestRegistry::instance()->getRequest();
		
		$issue_id   = $request->getProperty('issue_id');
		$article_id = $request->getProperty('article_id');
		$action     = $request->getProperty('action');

		if (!isset($action))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class EditArticleCommand->doExecute(): parameter 'action' not found.");
			return $this->setError("Неверно заданы параметы.");
		}
		
		$article = \mer\base\ApplicationFactory::instance()->getDomain('Article');
			
		if (isset($issue_id) && $action == 'create') // create
		{
			$article->set('issue_id', $issue_id);
			$article->set('language', 'ua');
		}
		else if (isset($article_id) && $action == 'update') // update
		{
			$res = $article->selectId($article_id);
			if (is_null($res))
				return $this->setError("Статья не найдена.");
				
			// key_words
		}
		else
		{
			return $this->setError("Неверно заданы параметы.");
		}

		$tpl->setArray($article->getArray());
		
		$tpl->set('action', $action);
		
		return MER_CMD_RESP_DEFAULT;
	}
}