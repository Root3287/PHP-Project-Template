<?php
$GLOBALS['config'] = array(
		"config"=>array("name" => "Social-Media"),
		"mysql" => array(
		"host" => "127.0.0.1", //127.0.0.1.
		"user" => "root", //root
		"password" => "password", //password
		"db" => "template", //social-media
		"port" => "3306", //3306
	),
	"remember" => array(
		"expiry" => 604800,
	),
	"session" => array (
		"token_name" => "token",
		"cookie_name"=>"cookie",
		"session_name"=>"session",
		"admin_session_name" => "adm_session",
		"admin_cookie_name" => "adm_session",
	),
);
?>