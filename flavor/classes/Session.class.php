<?php 

class Session extends Singleton implements ArrayAccess {
	
	public function __construct() {
		if (!isset($_SESSION)) {
			session_start();
		}
		$_SESSION["flavor_fwk_session"] = isset($_SESSION["flavor_fwk_session"]) ? $_SESSION["flavor_fwk_session"] : array();
	}
	
	public static function getInstance($class = null) {
		return parent::getInstance(get_class());
	}

	public function __set($key, $value){
		$_SESSION["flavor_fwk_session"][FlavorAppId][$key] = $value;
	}
	
	public function __get($key){
		return $_SESSION["flavor_fwk_session"][FlavorAppId][$key];
	}
	
	public function destroy($key){
		unset($_SESSION["flavor_fwk_session"][FlavorAppId][$key]);
	}
	
	public function check($key){
		return isset($_SESSION["flavor_fwk_session"][FlavorAppId][$key]);
	}
	
	function flash($value){
		$_SESSION["flavor_fwk_session"][FlavorAppId]["flash"] = $value;
	}
	
	function issetFlash(){
		if (!isset($_SESSION["flavor_fwk_session"][FlavorAppId]["flash"]) or $_SESSION["flavor_fwk_session"]["flash"] == ""){
			return false;
		}
		return true;
	}	
	
	function getFlash(){
		if (!@is_null($_SESSION["flavor_fwk_session"][FlavorAppId]["flash"])){
			$flash = $_SESSION["flavor_fwk_session"][FlavorAppId]["flash"];
			$_SESSION["flavor_fwk_session"][FlavorAppId]["flash"] = "";
			return $flash;
		}
		return "";
	}

	public function offsetExists($offset) {
		return isset($_SESSION["flavor_fwk_session"][FlavorAppId][$offset]);
	}	

	public function offsetSet($offset, $value) {
		$_SESSION["flavor_fwk_session"][FlavorAppId][$offset] = $value;
	}
	
	public function offsetGet($offset) {
		return $_SESSION["flavor_fwk_session"][FlavorAppId][$offset];
	}

	public function offsetUnset($offset) {
		unset($_SESSION["flavor_fwk_session"][FlavorAppId][$offset]);
	}
	
}