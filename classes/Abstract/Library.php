<?php defined('WTHR') OR die('No direct script access.');

class Abstract_Library
{
	public static $__instance;
	
	public static function __getInstance()
	{
		if (!self::$__instance instanceof self) self::$__instance = new static();
		return self::$__instance;
	}
}
