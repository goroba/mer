<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/mapper/Mapper.php';

class RSSMapper extends Mapper
{
	protected $table = 'rss';
	protected $count_stmt;
	protected $select_stmt;

	function __construct()
	{
		parent::__construct();
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class StatusMapper: constructed.");
		
		$this->count_stmt = self::$dbh->prepare("SELECT COUNT(*) AS COUNTER FROM `$this->table`");
		$this->select_stmt = self::$dbh->prepare("SELECT * FROM `$this->table` ORDER BY `number` DESC LIMIT :x, :y");
	}
	
	function sizeof()
	{
		$this->count_stmt->execute();
		$data = $this->count_stmt->fetch(\PDO::FETCH_OBJ)->COUNTER;

		return ceil($data / MER_RSS_PER_PAGE);
	}
	
	function selectPage($page)
	{
		$page = intval($page-1);
		
		$this->select_stmt->bindValue(':x', (int) $page*MER_RSS_PER_PAGE, \PDO::PARAM_INT);
		$this->select_stmt->bindValue(':y', (int) MER_RSS_PER_PAGE, \PDO::PARAM_INT);
		$this->select_stmt->execute();
		$data = $this->select_stmt->fetchAll();
		return $data;
	}
	
	function getPath()
	{
		return MER_RSS_FOLDER;
	}
}
