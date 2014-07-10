<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

abstract class DomainObject
{
	function __construct($array = null)
	{
		if (!is_null($array) && is_array($array))
			$this->setArray($array);
	}
	
	function getArray()
	{
		return $this->columns;
	}	
	
	function setArray($array)
	{
		if (!isset($array) && !is_array($array))
			return null;
		
		foreach ($array as $key => $val)
			if (is_null($this->set($key, $val)))
				return null;
			
		return true;
	}
	
	function setArraySoft($array)
	{
		if (!isset($array) && !is_array($array))
			return null;
		
		foreach ($array as $key => $val)
			$this->set($key, $val);
			
		return true;
	}
	
	function set($key, $val)
	{
		if (!array_key_exists($key, $this->columns))
			return null;

		$this->columns[$key] = $val;
		return true;
	}
	
	function get($key)
	{
		if ( isset($this->columns[$key]) )
		{
			return $this->columns[$key];
		}
		return null;
	}
	
	static function getCollection($type)
	{
		return array();
	}
	
	function collection()
	{
		return self::getCollection(get_class($this));
	}
	
	function getMapper()
	{
		if (!isset($this->mapper))
		{
			$class = str_replace("mer\\domain\\", "", get_class($this));
			$this->mapper = \mer\base\ApplicationFactory::instance()->getMapper($class);
		}
		return $this->mapper;	
	}

	function exists(array $fields = null)
	{
		return $this->getMapper()->exists($this, $fields);
	}
	
	function insert($checkId = null)
	{
		if ($checkId == true)
		{
			$id = $this->getMapper()->insert($this, $checkId);
			if (!is_null($id))
				$this->set('id', $id);
			return $id;
		}
		else
			return $this->getMapper()->insert($this, $checkId);
		
	}
	
	function select(array $arr, $orderby = null, $desc = false)
	{
		return $this->getMapper()->select($arr, $orderby, $desc);		
	}
	
	function selectObject(array $arr)
	{
		$res = $this->getMapper()->select_obj($arr);
		
		if (is_null($res))
			return null;
		
		return $this->setArray($res);
	}
	
	function selectId($id)
	{
		return $this->selectObject(array('id' => $id));
	}
	
	function update(array $where_fields, array $set_fields = null)
	{
		return $this->getMapper()->update($this, $where_fields, $set_fields);
	}
	
	function updateId()
	{
		//$array = clone $this->columns;
		//unset($array['id']);
		return $this->getMapper()->update($this, array('id'));
	}
	
	function deleteObject(array $fields = null)
	{
		return $this->getMapper()->delete_obj($this, $fields);
	}
	
	function deleteId()
	{
		return $this->deleteObject(array('id'));
	}

	function getMax($field)
	{
		return $this->getMapper()->getMax($this, $field);
	}
	
	function getMin($field)
	{
		return $this->getMapper()->getMin($this, $field);
	}
	
	function getPrevious($field, $val)
	{
		$res = $this->getMapper()->getPrevious($this, $field, $val);
		
		if (is_null($res))
			return null;
		
		return $this->setArray($res);
	}
	
	function getNext($field, $val)
	{
		$res = $this->getMapper()->getNext($this, $field, $val);
		
		if (is_null($res))
			return null;
		
		return $this->setArray($res);
	}
	
	function setFileAnyway($field, $filename)
	{
		if (!isset($field) || !isset($filename))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->writeAnyway(): Неверно заданы параметры.");
			return null;
		}

		if (!array_key_exists($field, $this->columns))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->writeAnyway(): Неверно указано поле для файла.");
			return null;
		}
			
		$file = '_' . $field;
		
		$f_reg = \mer\base\ApplicationFactory::instance()->getFileRegistry();
		
		$tmp = $f_reg->checkFile($file);
		if (empty($tmp))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->setFileAnyway(): Загружаемый файл поля '$field' не указан.");
			return true;
		}
		
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if (!$f_reg->checkExtension($file, $ext))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->writeAnyway(): Файл должен быть в формате $ext.");
			return null;
		}
				
		$path = $this->getMapper()->getPath() . $filename;
				
		if (is_null($path = $f_reg->writeAnyway($file, $path)))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->writeAnyway(): Произошла ошибка во время загрузки файла на сервер.");
			return null;
		}
		
		$path = str_replace(MER_SITE, '', $path);
		$this->set($field, $path);
		return true;
	}
	
	function resetFileAnyway($field, $filename)
	{
		if (!isset($field) || !isset($filename))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->resetFileAnyway(): Неверно заданы параметры.");
			return null;
		}
		
		if (!array_key_exists($field, $this->columns))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->resetFileAnyway(): Неверно указано поле объекта для файла.");
			return null;
		}
			
		$file = '_' . $field;
		$old_path = $this->get($field);
		
		$f_reg = \mer\base\ApplicationFactory::instance()->getFileRegistry();
		
		$tmp = $f_reg->checkFile($file);
		if (empty($tmp))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->resetFileAnyway(): Новый файл новости не указан. Остается старый.");
			return true;
		}
		
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if (!$f_reg->checkExtension($file, $ext))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->resetFileAnyway(): Файл должен быть в формате $ext.");
			return null;
		}
				
		$path = $this->getMapper()->getPath() . $filename;
				
		if (is_null($path = $f_reg->changeAnyway($file, $path, $old_path)))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->resetFileAnyway(): Произошла ошибка во время загрузки файла на сервер.");
			return null;
		}
		
		$path = str_replace(MER_SITE, '', $path);
		$this->set($field, $path);
		return true;		
	}
	
	function deleteFile($field)
	{
		if (!array_key_exists($field, $this->columns))
		{
			\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->deleteFile(): Неверно указано поле объекта.");
			return null;
		}	
		
		$path = $this->get($field);
		if (file_exists($path))
			if (!unlink($path))
			{
				\mer\base\SessionRegistry::instance()->pushSysMessage("class DomainObject->deleteFile(): Ошибка при удалении файла.");
				return null;
			}
		$this->set($field, '');
		return true;
	}
}