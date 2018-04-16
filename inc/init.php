<?php
require 'config.php';

session_start();

spl_autoload_register(function($class){
	if (file_exists('inc/classes/'.$class.'.class.php')){
		require 'inc/classes/'.$class.'.class.php';
	}
});

require_once 'functions.php';

if(isset($GLOBALS['config']['install']) && !file_exists('/pages/install/install.php')){
	$db = DB::getInstance();
	if(Cookies::exists(Config::get('session/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
		$hash = Cookies::get(Config::get('session/cookie_name'));
		$hashCheck= $db->get('user_session', array('hash','=',$hash));
		if($hashCheck->count()){
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}

	if(Cookies::exists(Config::get('session/admin_cookie_name')) && !Session::exists(Config::get('session/admin_session_name'))){
		$hash = Cookies::get(Config::get('session/admin_cookie_name'));
		$hashCheck= $db->get('adm_user_session', array('hash','=',$hash));
		if($hashCheck->count()){
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}

	//Error Reporting
	ini_set('diplay_errors', Setting::get('debug'));
	$error_reporting =(Setting::get('debug') == 'Off')? '0':'-1';
	error_reporting($error_reporting);

	//if we didnt set a unique id then lets make one.
	if(Setting::get('unique_id') == null || Setting::get('unique_id') == ""){
		Setting::update('unique_id', substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,62));
	}

	$user = new User();
	if($user->isLoggedIn()){

		if($user->data()->banned == 1){
			$user->logout();
		} 

		// Update online status
		$user->update([
			'last_online'=> date('Y-m-d H:i:s'),
		]);
	}
}