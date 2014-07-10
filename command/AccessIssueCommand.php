<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class AccessIssueCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class LoginCommand->AccessIssueCommand(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$issue_id = $request->getProperty('issue_id');
		$access   = $request->getProperty('access');

		$issue = \mer\base\ApplicationFactory::instance()->getDomain('Issue');
		if (is_null($issue->selectId($issue_id)))
			$this->pushMultilangError(MER_ERROR_ISSUE_NOT_FOUND);
			
		if ($access == 'open')
			$issue->set('access', 1);
		else if ($access == 'close')
			$issue->set('access', 0);
		else
			$this->setError('Параметр доступа задан неверно.');
		
		$issue->updateId();
				
		\mer\base\SessionRegistry::instance()->pushMessage('Новый номер журнала добален.');
		
		$req = \mer\base\ApplicationFactory::instance()->getRequestConstructor();
		$req->bindParam('cmd', 'edit_issue');
		$req->bindParam('issue_id', $issue_id);
		
		$this->forward($req);
	}
}