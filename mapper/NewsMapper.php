<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/mapper/Mapper.php';

class NewsMapper extends Mapper
{
	protected $table = 'news';
	protected $count_stmt;
	protected $select_stmt;

	function __construct()
	{
		parent::__construct();
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class SectionMapper: constructed.");
		$this->count_stmt = self::$dbh->prepare("SELECT COUNT(*) AS COUNTER FROM `$this->table`");
		$this->select_stmt = self::$dbh->prepare("SELECT * FROM `$this->table` ORDER BY `number` DESC LIMIT :x, :y");
	}
	
		function sizeof()
	{
		$this->count_stmt->execute();
		$data = $this->count_stmt->fetch(\PDO::FETCH_OBJ)->COUNTER;

		return ceil($data / MER_NEWS_PER_PAGE);
	}
	
	function selectPage($page = 1)
	{
		$page = intval($page-1);
		
		$this->select_stmt->bindValue(':x', (int) $page*MER_NEWS_PER_PAGE, \PDO::PARAM_INT);
		$this->select_stmt->bindValue(':y', (int) MER_NEWS_PER_PAGE, \PDO::PARAM_INT);
		$this->select_stmt->execute();
		$data = $this->select_stmt->fetchAll();
		return $data;
	}
	
	function getPath()
	{
		return MER_NEWS_FOLDER;
	}
	
	
}
