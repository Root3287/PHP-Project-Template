<?php
require 'inc/init.php';

$router = new Router();

$router->add('/', function (){
	if(file_exists('pages/install/install.php') || !isset($GLOBALS['config'])){
		Redirect::to('/install');
	}else{
		$user = new User();
	}
	return true;
});
$router->add('/install(.*)', function(){
	if(file_exists('pages/install/install.php')){
		require 'pages/install/install.php';
	}else{
		Redirect::to('/');
	}
	return true;
});
$router->add('/404', function(){
	require 'pages/errors/404.php';
	return true;
});
$router->add('/logout', function(){
	require 'pages/logout.php';
	return true;
});

if(!$router->run()){
	Redirect::to(404);
}