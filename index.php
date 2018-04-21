<?php
require 'inc/init.php';

$router = new Router();

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

foreach (Module::getModules() as $instance) {
	foreach($instance->getURI() as $page => $page_val){
		$router->add($page_val, $instance->getCallback()[$page]);
	}
}

if(!$router->run()){
	Redirect::to(404);
}