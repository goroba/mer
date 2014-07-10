<?php

namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class FileRegistry extends Registry 
{
	private static $instance;
	
	private static $files;
	private $backups = array();
	
	private function __construct()
	{
		$this->init();
	}
	
	private function init()
	{
		$this->files = $_FILES;
	}
	
	protected function get($key)
	{
	}
	
	protected function set($key, $val)
	{
	}
	
	static function instance()
	{
		if ( !isset(self::$instance) )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	function checkFile($file)
	{
		return $_FILES[$file]['tmp_name'];
	}
	
	function checkFileName($file)
	{
		return $_FILES[$file]['name'];
	}
	
	function createDir($path)
	{
		$tmp = pathinfo($path,  PATHINFO_DIRNAME);
		$dir_arr = explode('/', $tmp);
	
		$directory = '';
		foreach ($dir_arr as $dir)
		{
			$directory .= $dir . "/";
			if (!file_exists($directory))
				mkdir($directory);
		}
	}
	
	// $path includes filename
	function write($file, $path, $backup = null)
	{
		if (is_null($this->checkErrors($file)))
			return null;
		
		if (file_exists($path))
			return $this->setError("Файл уже существует.");
			
		$this->createDir($path);
						
		if (!move_uploaded_file($_FILES[$file]['tmp_name'], $path))
			return $this->setError("Ошибка при загрузке файла на сервер.");
			
		return $path;
	}
	
	function writeAnyway($file, $path, $backup = null)
	{
		if (is_null($this->checkErrors($file)))
			return null;
			
		if (file_exists($path))
		{
			$path_array = $this->explodePath($path);
			$c = 1;
			do
			{
				$path = $this->implodePath($path_array, "($c)");
				$c++;
			} 
			while (file_exists($path));
		}	

		$this->createDir($path);
		
		if (!move_uploaded_file($_FILES[$file]['tmp_name'], $path))
			return $this->setError("Ошибка при загрузке файла на сервер.");
			
		return $path;
	}
	
	function rewrite($file, $path, $backup = null)
	{
		if (is_null($this->checkErrors($file)))
			return null;
		
		if (file_exists($path))
			if (!unlink($path))
				return $this->setError("Ошибка при удалении заменяемого файла.");
				
		$this->createDir($path);

		if (!move_uploaded_file($_FILES[$file]['tmp_name'], $path))
			$this->setError("Ошибка при загрузке файла на сервер.");
		
		return $path;
	}
	
	function change($file, $path, $old_path = null, $backup = null)
	{
		if (is_null($this->checkErrors($file)))
			return null;
		
		if (file_exists($path))
			return $this->setError("Загружаемый файл уже существует.");
		
		if (isset($old_path) && !empty($old_path) && file_exists($old_path))
			if (!unlink($old_path))
				return $this->setError("Ошибка при удалении заменяемого файла.");
		
		$this->createDir($path);

		if (!move_uploaded_file($_FILES[$file]['tmp_name'], $path))
			$this->setError("Ошибка при загрузке файла на сервер.");
		
		return $path;
	}
	
	function changeAnyway($file, $path, $old_path, $backup = null)
	{
		if (empty($path))
		{
			$this->setError("Новый файл не указан.");
			return true;
		}
		
		if (is_null($this->checkErrors($file)))
			return null;
		
		if (isset($old_path) && !empty($old_path) && file_exists($old_path))
			if (!unlink($old_path))
				return $this->setError("Ошибка при удалении заменяемого файла.");
		
		if (file_exists($path))
		{
			$path_array = $this->explodePath($path);
			$c = 1;
			do
			{
				$path = implodePath($path_array, "($c)");
			} 
			while (file_exists($path));
		}
		
		$this->createDir($path);

		if (!move_uploaded_file($_FILES[$file]['tmp_name'], $path))
			$this->setError("Ошибка при загрузке файла на сервер.");
		
		return $path;
	}
	
	function delete($path)
	{
		if (file_exists($path))
			if (!unlink($old_path))
				return $this->setError("Ошибка при удалении заменяемого файла.");			
	}
	
	function backup($path)
	{
	}
	
	function rollback($file)
	{
	}
	
	function rollbackAll()
	{
	}
		
	function checkExtension($file, $ext)
	{
		$e = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);
			
		if ($e !== $ext)
			return null;
					
		return true;
	}
	
	private function explodePath($path)
	{
		$dir = pathinfo($path, PATHINFO_DIRNAME) . '/';
		$filename = pathinfo($path, PATHINFO_FILENAME);
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		
		return array('dir' => $dir, 'name' => $filename, 'ext' => $ext);
	}
	
	private function implodePath($path_arr, $suffix = null)
	{
		if (!is_array($path_arr) || !isset($path_arr['dir']) || !isset($path_arr['name']) || !isset($path_arr['ext']))
			return null;
			
		if (is_null($suffix))
			$suffix = '';
		
		if (!empty($path_arr['ext']))
			return $path_arr['dir'] . $path_arr['name'] . $suffix . "." . $path_arr['ext'];
		else
			return $path_arr['dir'] . $path_arr['name'] . $suffix;
	}

	private function checkErrors($file)
	{
		if ($_FILES[$file]['error'] !== 0)
			return $this->setError("Ошибка при загрузке файла на сервер.");
				
		if (!is_uploaded_file($_FILES[$file]['tmp_name']))
			return $this->setError("Некорректная загрузка файла!");
		
		return true;
	}
	private function setError($message)
	{
		\mer\base\SessionRegistry::instance()->pushSysMessage($message);
		return null;
	}
}