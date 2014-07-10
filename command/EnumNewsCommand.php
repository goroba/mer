<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class EnumNewsCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class EnumNewsCommand->doExecute(): executed.");
		
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$page = $request->getProperty('page');
		if (!isset($page) || $page < 1) $page = 1;
		$page = intval($page);
		
		$mapper = \mer\base\ApplicationFactory::instance()->getMapper('News');
		$sizeof = $mapper->sizeof();
		$data = $mapper->selectPage($page);
				
		$news = \mer\base\ApplicationFactory::instance()->getCollection('News');
		$news->addRaw($data);
		$news->sortContent();
		
		
		$tpl = \mer\base\Templater::instance();
		$tpl->set('sizeof', $sizeof);
		$tpl->set('news', $news);
				
		return MER_CMD_RESP_DEFAULT;
	}
}