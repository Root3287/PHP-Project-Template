<?php
class Output{
	public static function clean($text){
		return htmlspecialchars($text);
	}
}
?>