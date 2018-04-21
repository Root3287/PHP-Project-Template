<?php
if(file_exists('inc/config.php')){
	require 'inc/config.php';
}

if(file_exists('inc/install.php')){
	require 'inc/install.php';
}

session_start();

spl_autoload_register(function($class){
	if (file_exists('inc/classes/'.$class.'.class.php')){
		require 'inc/classes/'.$class.'.class.php';
		return true;
	}
	return false;
});

$directories = scandir("modules");
foreach ($directories as $dir) {
	if(file_exists("modules/{$dir}/{$dir}.module.php")){
		require "modules/{$dir}/{$dir}.module.php";
	}
}

foreach (Module::getModules() as $instance) {
	if($instance->getClassAutoload()){
		spl_autoload_register($instance->getClassAutoload());
	}
}

PageTimer::start();

require_once 'functions.php';

if(isset($GLOBALS['config']['install']) && Config::get('config/install')){
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

		//ACTIVE CODES:
		// 0 - Not Confirm
		// 1 - Active
		// 2 - Temp Ban
		// 3 - Locked
		// 4 - Banned
		if($user->data()->active == 3){
			$user->logout();
		} 

		// Update online status
		$user->update([
			'last_online'=> date('Y-m-d H:i:s'),
		]);
	}
}