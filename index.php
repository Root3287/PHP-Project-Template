<?php
require 'inc/init.php';

$router = new Router();

$router->add('/', function (){
	if(file_exists('pages/install/install.php') || !isset($GLOBALS['config']['install'])){
		Redirect::to('/install/');
	}else{
		$user = new User();
		$userAdm = new AdminUser();
		if($user->isLoggedIn()){
			echo "Logged in";
		}
		echo "<Br>";
		if($userAdm->isLoggedIn()){
			echo "Admin Logged In";
		}
	}
	return true;
});
$router->add('/install/', function(){
	Redirect::to('/install/intro/');
	return true;
});
$router->add('/install/(.*)/(.*)', function($step){
	$step = escape($step);
	if(file_exists('pages/install/install.php') && !isset($GLOBALS['config']['install'])){
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
$router->add('/test', function(){
	echo gethostbyname("127.0.0.1");
return true;
});

if(!$router->run()){
	Redirect::to(404);
}