<?php
class DB{
	private static $_instance = array();
	private $_pdo, $_query, $_error = false, $_results, $_count = 0;
	
	public function __construct($host, $port, $db, $user, $pass) {
		try {
			$this->_pdo = new PDO('mysql:host='.$host.';dbname='.$db,$user,$pass);
		} catch(PDOException $e) {
			die($e->getMessage());
		}
		self::$_instance[$db] = $this;
	}
	public static function getInstance($db = null) {
		if(!isset($db)){
			if(count(self::$_instance) > 0){
				reset(self::$_instance);
				$tes = self::$_instance[key(self::$_instance)];
				return $tes;
			}else{
				$db = new DB(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/user'), Config::get('mysql/password'));
				return $db;
			}
		}
		return self::$_instance[$db];
	}
	public function query($sql, $params = array()) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			$i = 1;
			if(count($params)) {
			   	foreach($params as $param) {
			 		$this->_query->bindValue($i, $param);
			 		$i++;
			 	}
			}	 
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {$this->_error = true;}
		}
		return $this;
	}
	public function action($action, $table, $where = array()) {
		if (count($where) == 3) {
			$operators = array('=','>','<','>=','<=',);
			$field    = $where[0]; 
			$operator = $where[1];
			$value    = $where[2];
			//check that operator is valid then contstruct the query
			if (in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				//bind data if there is no errors with the query
				if (!$this->query($sql, array($value))->error()) {return $this;}
			}
		}
		return false;
	}
	public function get($table, $where){
		return $this->action('SELECT *', $table, $where);
	}
	public function delete($table, $where){
		return $this->action('DELETE', $table, $where);
	}
	public function insert($table, $fields = array()) {
		$keys = array_keys($fields);
		$values = '';
		$i = 1;
		foreach ($fields as $field) {
			$values .= "?";
			if ($i < count($fields)) {$values .= ", ";}
			$i++;
		}
		$sql = "INSERT INTO {$table} (`".implode('`, `', $keys)."`) VALUES ({$values}) ";
		if (!$this->query($sql, $fields)->error()) {return true;}
	
		return false;
	}
	public function update($table, $id, $fields) {
		$set = '';
		$i = 1;
		foreach ($fields as $name => $value) {
			$set .= "{$name} = ?";
			if ($i < count($fields)) {$set .= ', ';}
			$i++;
		}
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		if(!$this->query($sql, $fields)->_error) {return true;}
		return false;
	}
	public function results(){
		return $this->_results;
	}
	public function count() {
		return $this->_count;
	}
	public function first() {
		$results = $this->results();
		return $results[0];
	}
	public function error() {
		return $this->_error;
	}
}