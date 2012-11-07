<?php

class session extends singleton implements ArrayAccess {
	
	public function __construct() {
		if (!isset($_SESSION)) {
			session_start();
		}
		
		if (!defined('App_id')){
			define('App_id', "flavor_app");
		}		
	}
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}

	public function __set($key, $value){
		$_SESSION["flavor_fwk_session"][App_id][$key] = $value;
	}
	
	public function __get($key){
		return $_SESSION["flavor_fwk_session"][App_id][$key];
	}
	
	public function destroy($key){
		unset($_SESSION["flavor_fwk_session"][App_id][$key]);
	}
	
	public function check($key){
		return isset($_SESSION["flavor_fwk_session"][App_id][$key]);
	}
	
	function flash($value){
		$_SESSION["flavor_fwk_session"][App_id]["flash"] = $value;
	}
	
	function issetFlash(){
		if (!isset($_SESSION["flavor_fwk_session"][App_id]["flash"]) or $_SESSION["flavor_fwk_session"][App_id]["flash"] == ""){
			return false;
		}
		return true;
	}	
	
	function getFlash(){
		if (!@is_null($_SESSION["flavor_fwk_session"][App_id]["flash"])){
			$flash = $_SESSION["flavor_fwk_session"][App_id]["flash"];
			$_SESSION["flavor_fwk_session"][App_id]["flash"] = "";
			return $flash;
		}
		return "";
	}

	public function offsetExists($offset) {
		return isset($_SESSION["flavor_fwk_session"][App_id][$offset]);
	}	

	public function offsetSet($offset, $value) {
		$_SESSION["flavor_fwk_session"][App_id][$offset] = $value;
	}
	
	public function offsetGet($offset) {
		return $_SESSION["flavor_fwk_session"][App_id][$offset];
	}

	public function offsetUnset($offset) {
		unset($_SESSION["flavor_fwk_session"][App_id][$offset]);
	}
	
}
?>
