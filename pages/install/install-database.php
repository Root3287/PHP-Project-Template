<?php
$db = DB::getInstance();

$db->createTable("groups", [
	"id"=>[
		"INT"=>11,
		"NOT NULL",
		"AUTO_INCEMENT",
	],
	"group_name"=>[
		"TEXT"
		"NOT NULL",
	],
	"permissions"=>[
		"TEXT"
		"NOT NULL",
	],
	"PRIMARY KEY"=>"id",
]);
?>