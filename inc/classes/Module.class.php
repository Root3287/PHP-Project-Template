<?php
class Module{
	private static $_instance = [];
	private $_db;
	private $_uri = [], $_callback =[];
	private $_classReg;
	private $_name;

	public function __construct($module){
		$this->_name = $module;
	}

	public static function Register($module){
		return self::getModules($module);
	}

	public static function getModules($module = null){
		if($module){
			if(!isset(self::$_instance[$module])){
				self::$_instance[$module] = new Module($module);
			}
			return self::$_instance[$module];
		}else{
			return self::$_instance;
		}
	}

	public function addPage($uri, $callback){
		$this->_uri[] = $uri;
		$this->_callback[] = $callback;
	}

	public function getURI(){
		return $this->_uri;
	}

	public function getCallback(){
		return $this->_callback;
	}
	public function getNavItem(){
		return $this->nav;
	}
	public function classAutoload($autoload){
		$this->_classReg = $autoload;
	}
	public function getClassAutoload(){
		return $this->_classReg;
	}
	public function getName(){
		return $this->_name;
	}
}
?>