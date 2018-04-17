<?php
class Module{
	private static $_instance = [];
	private $_db;
	private $_uri = [], $_callback =[];

	public static function Register($module){
		return self::getModules($module);
	}

	public static function getModules($module = null){
		if($module){
			if(!isset(self::$_instance[$module])){
				self::$_instance[$module] = new Module();
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
}
?>