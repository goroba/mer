<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/mapper/Collection.php';

class RSSCollection extends Collection
{
	
	function domainClass()
	{
		return "RSS";
	}
	
	function sortContent($field = 'number')
	{
		usort($this->objects, array($this, "cmpr"));
	}
	
	private function cmpr($a, $b, $field = 'number')
	{
		$a_num = $a->get($field);
		$b_num = $b->get($field);
		
		
		if ($a_num == $b_num)
			return 0;
			
		if (is_null($b_num))
			return 1;
			
		if (is_null($a_num))
			return -1;
			
		return ($a_num < $b_num) ? 1 : -1;
	}
	
	function swap($i, $j, $field = 'number')
	{
		$rss_i = \mer\base\ApplicationFactory::instance()->getDomain('RSS');
		if (!$rss_i->selectObj(array($field => $i)))
			return null;
		$rss_j = \mer\base\ApplicationFactory::instance()->getDomain('RSS');
		if (!$rss_j->selectObj(array($field => $j)))
			return null;
		
		$rss_i->set($field, $j);
		$rss_j->set($field, $i);
		
		$rss_i->update(array('id'), array($field));
		$rss_j->update(array('id'), array($field));		
	}	
}