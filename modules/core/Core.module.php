<?php
$core = Module::Register("core");
$core->description =  "Some Module";
$core->author = "Timothy Gibbons";
$core->version = "1.0.0.";
$core->required = true;

$core->addPage('/', function(){
	require 'pages/index.php';
	return true;
});
$core->addPage('/test', function(){
	echo "text";
	return true;
});

?>