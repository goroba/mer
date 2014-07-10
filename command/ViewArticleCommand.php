<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class ViewArticleCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class ViewArticleCommand->doExecute(): executed.");
		
		$tpl = \mer\base\Templater::instance();
				
		$issue_id = \mer\base\RequestRegistry::instance()->getRequest()->getProperty('issue_id');
		$article_id = \mer\base\RequestRegistry::instance()->getRequest()->getProperty('article_id');
		
		if (!isset($issue_id) || !isset($article_id))
		{
			\mer\base\SessionRegistry::instance()->pushError("Ошибка запроса, неверно указанные параметры.");
			return MER_CMD_RESP_ERROR;
		}
		
		$issue = \mer\base\ApplicationFactory::instance()->getDomain('Issue');
		if (is_null($issue->selectId($issue_id)))
			$this->pushMultilangError(MER_ERROR_ISSUE_NOT_FOUND);
		
		$article = \mer\base\ApplicationFactory::instance()->getDomain('Article');
		if (is_null($article->selectId($article_id)))
			$this->pushMultilangError(MER_ERROR_ARTICLE_NOT_FOUND);

		$language = $tpl->get('__lang');
		$original_lang = \mer\base\ApplicationFactory::instance()->getDomain('language');
		
		foreach (array('ua', 'en', 'ru') as $l)
		{
			$original_lang->selectObject(array('language' => $language,  'desc' => 'original_language_' . $l));
			$tpl->set('original_language_' . $l, $original_lang->get('text'));
		}
		
		$tpl->set('issue_number', $issue->getString());
		$tpl->set('access', $issue->get('access'));
		$tpl->set('issue_id', $issue_id);
		$tpl->setArray($article->getArray());
		
		return MER_CMD_RESP_DEFAULT;
	}
}