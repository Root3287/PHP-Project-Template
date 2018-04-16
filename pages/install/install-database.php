<?php
$db = DB::getInstance();
$data = [];
$data["tableCreate"][] = $db->createTable("groups", [
	"id"=>[
		"INT"=>11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"group_name"=>[
		"TEXT",
		"NOT NULL",
	],
	"permissions"=>[
		"TEXT",
		"NOT NULL",
	],
	"PRIMARY KEY"=>"id",
]);
$data["tableCreate"][] = $db->createTable("users", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT"
	],
	"username"=> [
		"VARCHAR" => 50,
		"NOT NULL",
	],
	"password" => [
		"LONGTEXT",
		"NOT NULL",
	],
	"salt" => [
		"LONGTEXT",
		"NOT NULL",
	],
	"name" => [
		"VARCHAR" => 50,
		"NOT NULL",
	],
	"email" => [
		"TEXT",
		"NOT NULL",
	],
	"joined" => [
		"DATETIME",
	],
	"last_online" => [
		"DATETIME",
	],
	"active" => [
		"INT" => 11,
		"DEFAULT `1`"
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][]= $db->createTable("user_session", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"user_id" => [
		"INT" => 11,
		"NOT NULL",
	],
	"hash" => [
		"LONGTEXT",
		"NOT NULL"
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][] = $db->createTable("adm_user_session", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"user_id" => [
		"INT" => 11,
		"NOT NULL",
	],
	"hash" => [
		"LONGTEXT",
		"NOT NULL"
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][]= $db->createTable("settings", [
	"id" => [
		"INT"=> 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"name" => [
		"TEXT",
		"NOT NULL",
	],
	"value"=>[
		"LONGTEXT",
	],
	"PRIMARY KEY" => "id",
]);

$data['insert'][] = $db->insert("settings", [
	"name" => "title",
	"value" => "Template",
]);
$data['insert'][] = $db->insert("settings", [
	"name" => "theme",
	"value" => "bootstrap",
]);
$data['insert'][] = $db->insert("settings", [
	"name" => "debug",
	"value" => "Off",
]);
include_once 'inc/classes/Hash.class.php';
$data['insert'][] = $db->insert("settings", [
	"name" => "unique_id",
	"value" => Hash::unique_id(32),
]);
$data['insert'][] = $db->insert("groups", [
	"group_name" => "Standard",
	"permissions" => "{}",
]);
$data['insert'][] = $db->insert("groups", [
	"group_name"=>"Admin",
	"permissions"=> "{\"Admin\":1}",
]);
?>