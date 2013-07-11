<?php defined('WTHR') OR die('No direct script access.');

class Core
{
	public static $configs = array();
	
	public static function getConfigs($fields = array())
	{
		$toReturn = NULL;
		
		if (empty($fields)) $toReturn = self::$configs;
		else if (is_array($fields))
			foreach ($fields as $field)
				if (array_key_exists((string)$field, self::$configs)) $toReturn[(string)$field] = self::$configs[(string)$field];
				else {}
		else if (array_key_exists((string)$fields)) $toReturn[$fields] = self::$configs[(string)$fields];
		
		return $toReturn;
	}
	
	public static function loadConfig($dir, $cfg)
	{
		if (file_exists($dir.$cfg) && dirname($dir.$cfg).DIRECTORY_SEPARATOR === $dir)
			if (!empty(self::$configs))
				foreach(include($dir.$cfg) as $key => $config)
					self::$configs[$key] = $config;
					
			else self::$configs = include_once($dir.$cfg);
		else {}
		
		return (!empty(self::$configs)) ? TRUE : FALSE;
	}
	
	public static function autoLoader($class)
	{
		$file = str_replace('_', DIRECTORY_SEPARATOR, $class);

		if (file_exists(CLDIR.$file.EXT))
		{
			require_once($file.EXT);
			return TRUE;
		}
		
		else return FALSE;
	}
}
