<?php
require 'inc/init.php';

$router = new Router();

#echo '<!DOCTYPE HTML>';
$router->add('/', function (){
	if(file_exists('pages/install/install.php') || !isset($GLOBALS['config'])){
		Redirect::to('/install');
	}else{
		$user = new User();
		//Do something
		//if(!$user->isLoggedIn()){
		//	require 'pages/index.php';
		//}else{
		//	Redirect::to('/timeline');
		//}
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
$router->add('/login', function(){
	require 'pages/login.php';
	return true;
});
$router->add('/register', function(){
	require 'pages/register.php';
	return true;
});
$router->add('/404', function(){
	require 'pages/errors/404.php';
	return true;
});
$router->add('/u/(.*)', function($profile_user){
	require 'pages/profile.php';
	return true;
});

$router->add('/test',function(){
	//require 'pages/test.php';
	return false;
});
$router->add('/logout', function(){
	require 'pages/logout.php';
	return true;
});
/*
Admin Stuff
*/
$router->add('/admin/', function(){
	require 'pages/admin/index.php';
	return true;
});
$router->add('/admin/', function(){
	require 'pages/admin/reports.php';
	return true;
});
$router->add('/admin/update/', function(){
	require 'pages/admin/update.php';
	return true;
});
$router->add('/admin/logout/', function(){
	require 'pages/admin/logout.php';
	return true;
});
$router->add('/admin/settings/', function(){
	require 'pages/admin/settings.php';
	return true;
});
$router->add('/admin/login/', function(){
	require 'pages/admin/login.php';
	return true;
});
$router->add('/admin/user(.*)', function(){
	require 'pages/admin/users.php';
	return true;
});
$router->add('/admin/users/delete/', function(){
return false;
});
$router->add('/admin/users/edit/', function(){
return false;
});
$router->add('/admin', function(){
	Redirect::to('/admin/');
	return true;
});
$router->add('/admin/notification/', function(){
	require 'pages/admin/notification.php';	
	return true;
});

/*
User
*/
$router->add('/user/',function(){
	require 'pages/user/index.php';
	return true;
});
$router->add('/user/profile/(.*)',function(){
	require 'pages/user/profile.php';
	return true;
});
$router->add('/user/notification/(.*)',function(){
	require 'pages/user/notification.php';
	return true;
});
$router->add('/user/update/(.*)',function(){
	require 'pages/user/update.php';
	return true;
});
$router->add('/user',function(){
	Redirect::to('/user/');
	return true;
});
$router->add('/user/profile',function(){
	Redirect::to('/user/profile/');
	return true;
});
$router->add('/user/notification',function(){
	Redirect::to('/user/notification/');
	return true;
});
$router->add('/user/update',function(){
	Redirect::to('/user/update/');
	return true;
});

if(!$router->run()){
	Redirect::to(404);
}