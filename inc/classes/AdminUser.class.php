<?php
class AdminUser{
	private $_user, $_db, $_loggedIn = false;

	public __construct($user){
		$this->_user = $user;
		$this->_db = DB::getInstance();
		if(!$user){
			if(Session::exists(Config::get('session/admin_session_name'))){
				$user = Session::get(Config::get('session/admin_session_name'));
				
				if($this->user()->find($user)){
					$this->_admLoggedIn = true;
				}else{
					//Log out
				}
			}
		}else{
			$this->_user->find($user);
		}
	}

	public function login($username = null, $pass = null, $remember = false){
		if(!$username && !$pass && $this->user()->exists()){
			Session::put(Config::get('session/admin_session_name'), $this->user()->data()->id);
		}else{
			$user = $this->user()->find($username);
			if($user){
				if($this->user->_data->password === Hash::make($pass, $this->user()->_data->salt)){
					Session::put(Config::get('session/admin_session_name'), $this->user()->_data->id);
					
					if($remember){
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('adm_user_session', array('user_id','=',$this->user()->data()->id));
						if(!$hashCheck->count()){
							$this->_db->insert('adm_user_session',array(
									'user_id' => $this->_data->id,
									'hash' => $hash
							));
						}else{
							$hash = $hashCheck->first()->hash;
						}
						Cookies::put(Config::get('session/admin_cookie_name'), $hash, config::get('remember/expiry'));
					}
					return true;
				}
			}
		}
		return false;	
	}
	public function logout(){
		$this->_db->delete('adm_user_session', ['user_id', '=', $this->data()->id]);
		Session::delete(Config::get('session/admin_session_name'));
		Cookies::delete(Config::get('session/admin_cookie_name'));
	}
	public function hasPermission($key = null) {
		if(isset($key)){
			$group = $this->_db->get('groups', array('id', '=', $this->user()->data()->group));
			if ($group->count()); {
				$permissions = json_decode($group->first()->permissions, true);
				if ($permissions[$key] == true) {
					return true;
				}
			}
		}else{
			return hasPermission("Admin");
		}
		return false;
	}
	public function isLoggedIn(){
		return $this->_loggedIn;
	}
	public function user(){
		return $this->_user;
	}
}
?>