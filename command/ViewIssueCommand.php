<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class ViewIssueCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class ViewIssueCommand->doExecute(): executed.");
		
		$issue_id = \mer\base\RequestRegistry::instance()->getRequest()->getProperty('issue_id');

		if (!isset($issue_id))
		{
			\mer\base\SessionRegistry::instance()->pushError("Ошибка запроса, номер не указан.");
			return MER_CMD_RESP_ERROR;
		}
		
		$issue = \mer\base\ApplicationFactory::instance()->getDomain('Issue');
		if (is_null($issue->selectId($issue_id)))
			$this->pushMultilangError(MER_ERROR_ISSUE_NOT_FOUND);
		
		$content = \mer\base\ApplicationFactory::instance()->getCollection('Content');
		$res = $content->getContent($issue_id);
		
		if (is_null($res))
			return MER_CMD_RESP_ERROR;
		
		$tpl = \mer\base\Templater::instance();
		
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
		$tpl->set('content', $content);
		
		return MER_CMD_RESP_DEFAULT;
	}
}