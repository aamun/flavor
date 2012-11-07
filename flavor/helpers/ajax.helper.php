<?php

class ajax extends pquery {
	
	protected $registry;
	protected $path;
	public $jquery;
	
	public function __construct() {
		$this->registry = registry::getInstance();
		$this->path = $this->registry["path"];
		$this->jquery = new pquery();		
	}
	
	public function linkToBox($text, $url="", $html_attributes="", $absolute=false) {
		$var = ($absolute) ? "" : $this->path;
		$html = "<a href=\"".$var.$url;
		$html .= "\" rel=\"facebox\"";		
		$html .= " $html_attributes ";		
		$html .= ">".$text."</a>";		
		return $html;
	}
	
	public function imageLinkToBox($text, $url="#", $html_attributes="", $name, $alt=""){
		if (!is_file($url)) {
			$url = (substr($url,-1,1)!="/")?$url."/":$url;
		}
		$html = "<a href=\"".$this->path.$url;
		$html .= "\" rel=\"facebox\"";
		$html .= " $html_attributes ";
		$html .= ">";
		$html .= "<img src=\"".$this->path.APPDIR."/views/images/".$name."\" alt=\"".$alt."\" title=\"".$alt."\" />".$text;
		$html .= "</a>";
		return $html;
	}
	
}
?>