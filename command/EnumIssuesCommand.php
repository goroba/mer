<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class EnumIssuesCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class EnumissuesCommand->doExecute(): executed.");
		
		$mapper = \mer\base\ApplicationFactory::instance()->getMapper('Issue');
						
		$issues = $mapper->select(array(), array('year', 'issue', 'suffix'), array('year'));
		
		\mer\base\Templater::instance()->set('issues', $issues);
		
		return MER_CMD_RESP_DEFAULT;
	}
}