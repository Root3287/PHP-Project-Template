<?php
$GLOBALS['config'] = [
	'mysql' => [
		'host' => '127.0.0.1',
		'port' => '3306',
		'db' => 'template',
		'prefix' => '_',
		'user' => 'root',
		'password' => '',
	],
	'session' => [
		'session_name' => 'session',
		'token_name' => 'token',
		'cookie_name' => 'cookie',
	],
	'remember' => [
		'expiry' => 604800,
	],
];
$GLOBALS['config']['installed']=true; 