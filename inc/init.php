<?php
<<<<<<< HEAD
$GLOBALS['config'] = array(
		"config"=>array("name" => "Social-Media"),
		"mysql" => array(
		"host" => "127.0.0.1", //127.0.0.1.
		"user" => "root", //root
		"password" => "", //password
		"db" => "template", //social-media
		"port" => "3306", //3306
	),
	"remember" => array(
		"expiry" => 604800,
	),
	"session" => array (
		"token_name" => "token",
		"cookie_name"=>"cookie",
		"session_name"=>"session"
	),
);
=======
require 'config.php';
>>>>>>> 191494837eecde0be8f36d3a2ff15517860230b6

session_start();

spl_autoload_register(function($class){
	if ((include_once 'inc/classes/'.$class.'.class.php') == FALSE){
		return;
	}
});
require_once 'functions.php';

if(!empty($GLOBALS['config']) && !file_exists('/pages/install/install.php')){
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