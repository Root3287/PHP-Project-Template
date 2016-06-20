<?php 
function escape($string){
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function getIPAddress(){
    if($_SERVER['SERVER_ADDR'] !== "127.0.0.1"){
        if($_SERVER['SERVER_PORT'] == 80){
            return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        }else{
            return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
        }
    }else{
        return false;
    }
}