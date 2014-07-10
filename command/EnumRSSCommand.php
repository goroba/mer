<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class EnumRSSCommand extends Command
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class EnumRSSCommand->doExecute(): executed.");
		
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$page = $request->getProperty('page');
		if (!isset($page) || $page < 1) $page = 1;
		$page = intval($page);
		
		$mapper = \mer\base\ApplicationFactory::instance()->getMapper('RSS');
		$sizeof = $mapper->sizeof();
		$data = $mapper->selectPage($page);
				
		$rss = \mer\base\ApplicationFactory::instance()->getCollection('RSS');
		$rss->addRaw($data);
		$rss->sortContent();
		
		
		$tpl = \mer\base\Templater::instance();
		$tpl->set('sizeof', $sizeof);
		$tpl->set('rss', $rss);
				
		return MER_CMD_RESP_DEFAULT;
	}
}