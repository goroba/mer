<?php

namespace mer\mapper;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/DomainObject.php';

class Mapper
{
	protected static $dbh;


	function __construct()
	{
		self::$dbh = \mer\base\ApplicationFactory::instance()->getDBH();
	}
	
	function exists(\mer\domain\DomainObject $obj, array $fields = null)
	{
		$stmt = "SELECT COUNT(*) AS COUNTER FROM `$this->table` WHERE ";
		
		$arr = $obj->getArray();
		
		if (is_null($fields))
			$fields = array_keys($arr);
		
		$where = array();
		$values = array();
		foreach ($arr as $key => $val)
		{
			if (in_array($key, $fields))
				if (!is_null($val))
				{
					$where[] = "`$key` = ?";
					$values[] = $val;
				}
		}
		$stmt .= implode(" AND ", $where);
		
		$this->stmt = self::$dbh->prepare($stmt);
		$this->stmt->execute($values);
		$data = $this->stmt->fetch(\PDO::FETCH_OBJ)->COUNTER;

		return $data;
	}
	
	function insert(\mer\domain\DomainObject $obj, $checkId = null)
	{
		$stmt = "INSERT INTO `$this->table` ";

		$arr = $obj->getArray();
		$columns = array();	
		$values = array();
		$ask = array();
		foreach ($arr as $key => $val)
		{
			if (!is_null($val))
			{
				$columns[] = "`$key`";
				$values[] = $val;
				$ask[] = "?";
			}			
		}
		
		$stmt .= "(" . implode(", ", $columns) . ") VALUES (" . implode(", ", $ask) . ")";
		$this->stmt = self::$dbh->prepare($stmt);
		$this->stmt->execute($values);
		
		if ($checkId == true)
		{
			return self::$dbh->lastInsertId();
		}
		
		return true;
	}
	
	//function select(\mer\domain\DomainObject $obj, $orderby = null, $desc = false)
	function select(array $arr, $orderby = null, $desc = false)
	{
		$stmt = "SELECT * FROM `$this->table`";
		
		if (!is_array($arr))
			return null;
			
		$where = array();
		$values = array();
		foreach ($arr as $key => $val)
		{
			if (!is_null($val))
			{
				$where[] = "`$key` = ?";
				$values[] = $val;
			}
		}
		
		if (count($values) > 0)
			$stmt .= " WHERE " . implode(" AND ", $where);

		if (is_array($orderby))
		{
			$orders = array();
			foreach ($orderby as $order)
			{
				//if (array_key_exists($order, $arr))
				if (is_array($desc) && array_search($order, $desc) !== false)
					$orders[] = "`$order` DESC";
				else
					$orders[] = "`$order`";
			}
			
			if (count($orders) > 0)
				$stmt .= " ORDER BY " . implode(", ", $orders);
		}
			
		if ($desc && !is_array($desc))
			$stmt .= " DESC";
		
		$this->stmt = self::$dbh->prepare($stmt);
		$this->stmt->execute($values);
		$data = $this->stmt->fetchAll();
		
		//$tpl = \mer\base\Templater::instance();
		//$tpl->set('query', $stmt);
		
		if (count($data) > 0)
			return $data;
		
		return null;
	}
	
	function select_obj(array $arr, $orderby = null, $desc = false)
	{
		$data = $this->select($arr, $orderby, $desc);
		
		if (is_null($data))
			return null;

		return $data[0];
	}
	
	function update(\mer\domain\DomainObject $obj, array $where_fields, array $set_fields = null)
	{
		$stmt = "UPDATE `$this->table`";

		if (!isset($where_fields) || !is_array($where_fields))
			return null;
		
		$arr = $obj->getArray();

		if (!isset($set_fields) || !is_array($set_fields))
			$set_fields = array_keys($arr);
		
		$values = array();
		$set = array();
		foreach ($set_fields as $field)
		{
			if (array_key_exists($field, $arr))
			{
				$set[] = "`$field` = ?";
				$values[] = $arr[$field];
			}
		}
		
		if (count($set) > 0)
		{
			$stmt .= " SET " . implode(", ", $set);
		}
		else
		{
			return null;
		}
		
		$where = array();
		foreach ($where_fields as $field)
		{
			if (array_key_exists($field, $arr))
			{
				$where[] = "`$field` = ?";
				$values[] = $arr[$field];
			}			
		}
		
		if (count($where) > 0)
		{
			$stmt .= " WHERE " . implode(", ", $where);
		}
		else
		{
			return null;
		}
		
		$this->stmt = self::$dbh->prepare($stmt, $values);
		$this->stmt->execute($values);	

		return true;
	}
	
	function delete_obj(\mer\domain\DomainObject $obj, array $fields = null)
	{
		$stmt = "DELETE FROM `$this->table` WHERE ";
		
		$arr = $obj->getArray();

		if (is_null($fields))
			$fields = array_keys($arr);
		
		$where = array();
		$values = array();
		foreach ($arr as $key => $val)
		{
			if (!is_null($val) && array_search($key, $fields) !== false)
			{
				$where[] = "`$key` = ?";
				$values[] = $val;
			}
		}
		$stmt .= implode(" AND ", $where);
		
		
		$this->stmt = self::$dbh->prepare($stmt);
		$this->stmt->execute($values);
		
		return true;
	}
	
	function deleteObj(\mer\domain\DomainObject $obj, array $fields = null)
	{
		$stmt = "DELETE FROM `$this->table` WHERE ";
		
		$arr = $obj->getArray();

		if (is_null($fields))
			$fields = array_keys($arr);
		
		$where = array();
		$values = array();
		foreach ($arr as $key => $val)
		{
			if (!is_null($val) && array_search($key, $fields) !== false)
			{
				$where[] = "`$key` = ?";
				$values[] = $val;
			}
		}
		$stmt .= implode(" AND ", $where);
		
		
		$this->stmt = self::$dbh->prepare($stmt);
		$this->stmt->execute($values);
		
		return true;
	}
	
	function getMax(\mer\domain\DomainObject $obj, $field)
	{
		$arr = $obj->getArray();
		
		if (!array_key_exists($field, $arr))
			return null;		
		
		$stmt = "SELECT MAX($field) as MAXI FROM `$this->table`";
		$this->stmt = self::$dbh->prepare($stmt);
		$this->stmt->execute();
		$data = $this->stmt->fetch(\PDO::FETCH_OBJ)->MAXI;		
		
		return $data;
	}
	
	function getMin(\mer\domain\DomainObject $obj, $field)
	{
		$arr = $obj->getArray();
		
		if (!array_key_exists($field, $arr))
			return null;		
		
		$stmt = "SELECT MIN($field) as MAXI FROM `$this->table`";
		$this->stmt = self::$dbh->prepare($stmt);
		$this->stmt->execute();
		$data = $this->stmt->fetch(\PDO::FETCH_OBJ)->MAXI;		

		return $data;	
	}
	
	function getPrevious(\mer\domain\DomainObject $obj, $field, $val)
	{
		$arr = $obj->getArray();
		
		if (!array_key_exists($field, $arr) || !is_numeric($val))
			return null;		
			
		$stmt = "SELECT * FROM `$this->table` WHERE `$field` < :number ORDER BY `$field` DESC LIMIT 1";
		
		$this->stmt = self::$dbh->prepare($stmt);
		$this->stmt->bindValue(':number', $val, \PDO::PARAM_INT);
		$this->stmt->execute();
		$data = $this->stmt->fetchAll();
		
		if (isset($data[0]))
			return $data[0];
			
		return null;
	}

	function getNext(\mer\domain\DomainObject $obj, $field, $val)
	{
		$arr = $obj->getArray();
		
		if (!array_key_exists($field, $arr) || !is_numeric($val))
			return null;		
			
		$stmt = "SELECT * FROM `$this->table` WHERE `$field` > :number ORDER BY `$field` ASC LIMIT 1";
		
		$this->stmt = self::$dbh->prepare($stmt);
		$this->stmt->bindValue(':number', $val, \PDO::PARAM_INT);
		$this->stmt->execute();
		$data = $this->stmt->fetchAll();
		
		if (isset($data[0]))
			return $data[0];
			
		return null;
	}

	
}