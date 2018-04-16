<?php
class Module{
	private static $_instance = [];
	private $_db;

	public static function getModule($module = ''){
		if(!isset(self::$_instance[$module])){
			self::$_instance[$module] = new Module();
		}
		return self::$_instance[$module];
	}
}
?>