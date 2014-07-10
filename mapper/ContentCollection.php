<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/mapper/Collection.php';
require_once MER_SITE . '/domain/Content.php';
require_once MER_SITE . '/domain/Article.php';
require_once MER_SITE . '/domain/Section.php';

class ContentCollection extends Collection
{
	private $issue_id = null;
	
	function domainClass()
	{
		return "Content";
	}
		
	function getContent($issue_id)
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("ContentCollection->getContent().");
		
		$issue_mapper = \mer\base\ApplicationFactory::instance()->getMapper('Issue');
		$section_mapper = \mer\base\ApplicationFactory::instance()->getMapper('Section');
		$article_mapper = \mer\base\ApplicationFactory::instance()->getMapper('Article');

		$issue = \mer\base\ApplicationFactory::instance()->getDomain('Issue');
		$issue->set('id',$issue_id);
				
		if (!$issue_mapper->exists($issue)) 
		{
			\mer\base\SessionRegistry::instance()->pushError('Номер журнала не существует.');
			return null;
		}
		
		$this->issue_id = $issue_id;
		
		$sections = $section_mapper->select(array('issue_id' => $issue_id));
		$articles = $article_mapper->select(array('issue_id' => $issue_id));
		
		if (!is_null($sections))
			foreach ($sections as $section) 
				$this->add(new \mer\domain\Section($section));

		if (!is_null($articles))
			foreach ($articles as $article)
				$this->add(new \mer\domain\Article($article));
		
		$this->sortContent();
		
		return true;
	}
	
	function sortContent()
	{
		usort($this->objects, array($this, "cmpr"));
		
		for ($i = 0; $i < $this->total; $i++)
		{
			$obj = $this->objects[$i];
			if (is_null($obj->get('number')) || $obj->get('number') != $i)
			{
				$obj->set('number', $i);
				
				$obj->getMapper()->update($obj, array('id'), array('number'));
			}
		}
	}
	
	private function cmpr($a, $b)
	{
		$a_num = $a->get('number');
		$b_num = $b->get('number');
		
		
		if ($a_num == $b_num)
			return 0;
			
		if (is_null($b_num))
			return -1;
			
		if (is_null($a_num))
			return 1;
			
		return ($a_num < $b_num) ? -1 : 1;
	}
	
	function swap($i, $j)
	{
		if ($i < 0 || $i >= $this->total || $j < 0 || $j >= $this->total)
			return null;
		
		$this->objects[$i]->set('number', $j);
		$this->objects[$j]->set('number', $i);
				
		$this->objects[$i]->getMapper()->update($this->objects[$i], array('id'), array('number'));
		$this->objects[$j]->getMapper()->update($this->objects[$j], array('id'), array('number'));
	}
	
}