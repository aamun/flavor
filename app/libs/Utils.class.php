<?php

class Utils{
	public static function pre($arr,$exit = true){
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
		if($exit){
			exit;
		}
	}
}
