<?php
$core = Module::Register("core");
$core->description =  "Some Module";
$core->author = "Timothy Gibbons";
$core->version = "1.0.0";
$core->required = true;

$core->addPage('/', function(){
	require 'pages/index.php';
	return true;
});
$core->addPage('/login', function(){
	$user= new User();
	if($user->isLoggedIn()){
		Session::flash('alert-warning', "You are already logged in!");
		Redirect::to('/');
	}
	require 'pages/login.php';
	return true;
});
$core->addPage('/admin', function(){
	Redirect::to('/admin/');
	return true;
});
$core->addPage('/admin/', function(){
	$user = new User();
	$userAdm = new AdminUser();
	if($userAdm->isLoggedIn()){
		require "pages/admin/index.php";
	}else{
		if($user->isLoggedIn()){
			Redirect::to('/admin/auth/');
		}else{
			Redirect::to('/');
		}
	}
	return true;
});
$core->addPage('/test', function(){
});
$core->addPage('/logout', function(){
	$user = new User();
	if(!$user->isLoggedIn()){
		Redirect::to('/');
	}
	$userAdm = new AdminUser();
	if($user->hasPermission("Admin") && $userAdm->isLoggedIn()){
		$userAdm->logout();
	}
	$user->logout();
	Redirect::to('/');
	return true;
});
$core->addPage('/register', function(){
	$user= new User();
	if($user->isLoggedIn()){
		Session::flash('alert-warning', "You are already logged in!");
		Redirect::to('/');
	}
	require 'pages/register.php';
	return true;
});
$core->addPage('/admin/(.*)/(.*)', function($page){
	$user = new User();
	$userAdm = new AdminUser();

	if($user->isLoggedIn()){
		if($userAdm->isLoggedIn()){
			switch ($page) {
				case "logout":
					$userAdm->logout();
					Redirect::to('/admin/');
					break;
				default:
					Redirect::to('/admin/');
					break;
			}
		}else{
			if($page == "auth"){
				require "pages/admin/auth.php";
			}else{
				Redirect::to('/admin/auth');
			}
		}
	}else{
		Redirect::to('/');
	}
	return true;
});
$core->addPage('/u/(.*)/(.*)', function($username){
	$user = new User();
	$userQuery = new User();
	if(!$userQuery->find($username)){
		return false;
	}
	require "pages/users.php";
	return true;
});
$core->addPage('/user/', function(){
	return true;
});
$core->addPage('/user/(.*)/(.*)', function($page){
	
	return true;
})
?>