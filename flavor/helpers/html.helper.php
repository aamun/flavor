<?php
 /* ===========================

  FlavorPHP - because php should have a better taste
  homepage: http://www.flavorphp.com/
  git repository: https://github.com/Axloters/FlavorPHP

  FlavorPHP is a free software licensed under the MIT license
  Copyright (C) 2008 by Pedro Santana <contacto at pedrosantana dot mx>
  
  Team:
  	Pedro Santana
	Victor Bracco
	Victor de la Rocha
	Jorge Condomí
	Aaron Munguia

  =========================== */
?>
<?php

class html extends singleton {

	protected $registry;
	protected $validateErrors;
	protected $path;
	public $type = "views";
	public $readOnly = false;

	public function __construct() {
		$this->registry = registry::getInstance();
		$this->path = $this->registry["path"];
	}

	public function useTheme($name) {
		$this->type = $name;
		$this->type= "themes/".$this->type;
	}

	public static function getInstance() {
		return parent::getInstance(get_class());
	}

	public function includeCanonical($url = ""){
		$canonical = "<link rel=\"canonical\" href=\"".$this->path.$url."\" />";
		return $canonical;
	}
	
	public function charsetTag($charSet) {
		$charSet = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$charSet."\"/>\n";
		return $charSet;
	}

	public function includeCss($css) {
		$css = "<link rel=\"stylesheet\" href=\"".$this->path.APPDIR."/".$this->type."/css/".$css.".css\" type=\"text/css\" />\n";
		return $css;
	}

	public function includeCssAbsolute($css) {
		$css = "<link rel=\"stylesheet\" href=\"".$this->path.APPDIR."/libs/".$css.".css\" type=\"text/css\" />\n";
		return $css;
	}

	public function includeJs($js) {
		if($this->type == "views"){
			$js = "<script type=\"text/javascript\" src=\"" . $this->path . APPDIR."/libs/js/" . $js . ".js\"></script>\n";
		}else{
			$js = "<script type=\"text/javascript\" src=\"" . $this->path . APPDIR."/" . $this->type . "/js/" . $js . ".js\"></script>\n";
		}
		return $js;
	}

	public function includeJsAbsolute($js) {
		$js = "<script type=\"text/javascript\" src=\"".$this->path.APPDIR."/libs/js/".$js.".js\"></script>\n";
		return $js;
	}

	public function includePluginFacebox() {
		$js = $this->includeCss("facebox");
		$js .= "\t<script type=\"text/javascript\">\n";
		$js .= "\t	var path = '".$this->path."';\n";
	  	$js .= "\t</script>\n";
		$js .= $this->includeJs("facebox");
		$js .= "\t<script type=\"text/javascript\">\n";
		$js .= "\t	jQuery(document).ready(function($) {\n";
		$js .= "\t	  $('a[rel*=facebox]').facebox() \n";
		$js .= "\t	})\n";
	  	$js .= "\t</script>\n";
		return $js;
	}

	public function includeFavicon($icon="favicon.ico") {
		$favicon = "<link rel=\"shortcut icon\" href=\"".$this->path.APPDIR.'/'.$this->type."/images/".$icon."\" />\n";
		return $favicon;
	}

	public function includeRSS($rssUrl="feed/rss/") {
		$rss = "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS 2.0\" href=\"".$this->path.$rssUrl."\" />\n";
		return $rss;
	}

	public function includeATOM($atomUrl="feed/atom/") {
		$atom = "<link rel=\"alternate\" type=\"application/atom+xml\" title=\"Atom 1.0\" href=\"".$this->path.$atomUrl."\" />\n";
		return $atom;
	}

	public function validateError($field) {		
		$html = "";
		$this->validateErrors = $this->registry->validateErrors;
		if (!is_null($this->validateErrors)) {
			if ($val = $this->findInArray($this->validateErrors, $field) ) {
				$html = "<div class=\"error\">".$val."</div>";
				$this->unsetError($field);
			}
		}		
		return $html;
	}

	/* Esta función es para ser utilizada por validateError($field){...} */
	private function unsetError($field){
		if(is_array($this->registry->validateErrors)){
			foreach($this->registry->validateErrors as $k => $v){
				if(is_array($v)){
					foreach($v as $kk => $vv){
						if($kk == $field){
							$this->registry->offsetUnset("validateErrors[$k][$kk]");
						}
					}
				}
			}
		}
		return false;
	}
	
	private function findInArray($arr, $str) {
		$response = "";
		foreach ($arr as $key=>$element){
			foreach ($element as $name=>$value){
				if ($name == $str) {
					$response = $value['message'];
				}
			}    
		}
		return $response;
	}

	public function form($url, $method="POST" , $html_attributes = ""){
		$url = (substr($url,-1,1)!="/")?$url."/":$url;
		return "<form action=\"".$this->path.$url."\" method=\"" . $method. "\" " . $html_attributes .">";
	}
	
	public function formAbsolute($url, $method="POST" , $html_attributes = ""){
		$url = (substr($url,-1,1)!="/")?$url."/":$url;
		return "<form action=\"".$url."\" method=\"" . $method. "\" " . $html_attributes .">";
	}

	public function formFiles($url){
		$url = (substr($url,-1,1)!="/")?$url."/":$url;
		return "<form action=\"".$this->path.$url."\" method=\"post\" enctype=\"multipart/form-data\">";
	}

	public function linkTo($text, $url="", $html_attributes="", $absolute = false) {
		if (!is_file($url)) {		
			$url = (substr($url,-1,1)!="/")?$url."/":$url;
		}
		$html = "<a href=\"".(!$absolute?$this->path:'').$url;
		$html .= "\"";
		$html .= " $html_attributes ";
		$html .= ">".$text."</a>";
		return $html;
	}

	public function linkToConfirm($text, $url="", $html_attributes=""){
		$url = (substr($url,-1,1)!="/")?$url."/":$url;
		$html = $this->linkTo($text, $url, " onclick=\"return confirm('\u00BFConfirma eliminar?');\" $html_attributes ");
		return $html;
	}

	public function image($name, $alt=""){
		return "<img src=\"".$this->path.APPDIR.'/'.$this->type."/images/".$name."\" alt=\"".$alt."\" title=\"".$alt."\" />";
	}


	public function imagePars($name, $extra=""){
		return "<img src=\"".$this->path.APPDIR.'/'.$this->type."/images/".$name."\" ".$extra." />";
	}
	
	public function acceptCancelButtons($text, $url="#", $wrapper="div") {
		$html = "<".$wrapper." class=\"buttons\">";		
		$html .= $this->cancelButton($text[1], $url);
		$html .= $this->acceptButton($text[0]);
		$html .= "</".$wrapper.">";
		return $html;
	}
	
	public function acceptButton($text) {
		if (!$this->readOnly) {
			$html = "<button type=\"submit\" class=\"positive\">";
			$html .= $this->image("tick.png");
			$html .= $text;
			$html .= "</button>";
			return $html;
		}
	}
	
	public function cancelButton($text, $url="#") {
		$url = (substr($url,-1,1)!="/")?$url."/":$url;
		$html = $this->imageLink($text, $url, "class=\"negative\"", "cross.png");
		return $html;
	}
	
	public function editRemoveButtons($text, $urls, $id, $wrapper="div") {
		$html = "<".$wrapper." class=\"buttons\">";
		$html .=  $this->createImageButton($text[0], "page_edit.png", $urls[0]);
		$html .= $this->createImageButtonConfirm($text[1], "delete.png", $urls[1]);
		$html .= "</".$wrapper.">";
		return $html;
	}

	public function createImageButton($text, $image, $url="#", $wrapper=NULL, $html_attributes="") {
		$html = "";
		if (isset($wrapper)) {
			$html .= "<".$wrapper." class=\"buttons\">";
		}
		$html .= $this->imageLink($text, $url, $html_attributes, $image);
		if (isset($wrapper)) {
			$html .= "</".$wrapper.">";
		}
		return $html;
	}
	
	public function createImageButtonConfirm($text, $image, $url="#", $wrapper=NULL) {
		$html = "";
		if (isset($wrapper)) {
			$html .= "<".$wrapper." class=\"buttons\">";
		}
		$html .= $this->imageLinkConfirm($text, $url, $image);
		if (isset($wrapper)) {
			$html .= "</".$wrapper.">";
		}
		return $html;
	}	

	public function imageLink($text, $url="#", $html_attributes="", $name, $alt=""){
		if (!is_file($url)) {
			$url = (substr($url,-1,1)!="/")?$url."/":$url;
		}
		$html = "<a href=\"".$this->path.$url;
		$html .= "\"";
		$html .= " $html_attributes ";
		$html .= ">";
		$html .= "<img src=\"".$this->path.APPDIR.'/'.$this->type."/images/".$name."\" alt=\"".$alt."\" title=\"".$alt."\" />".$text;
		$html .= "</a>";
		return $html;
	}

	public function imageLinkConfirm($text, $url="", $name, $alt=""){
		$html = $this->imageLink($text,$url,"onclick=\"return confirm('\u00BFConfirma eliminar?');\"",$name,$alt);
		return $html;
	}

	public function checkBox($name, $html_attributes="") {
		if (!$this->readOnly) {
			$html = "<input type=\"checkbox\" name=\"".$name."\"";
			$html .= $html_attributes;
			$html .= " />\n";
		} else {			
			try {
				$html_attributes = str_replace("&", "&amp;", $html_attributes);
				$html_attributes = str_replace(">", "&gt;", $html_attributes);
				$html_attributes = str_replace("<", "&lt;", $html_attributes);
				$x = new SimpleXMLElement("<input $html_attributes />");
				$val = ($x["checked"]) ? "&nbsp;X&nbsp;" : "&nbsp;&nbsp;&nbsp;";
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:1px 1px 1px 1px; width:auto; display:inline;'>".$val."</div>&nbsp;";
				$html .= "<input type=\"hidden\" value=\"".$x["checked"]."\" name=\"".$name."\" />\n";
			} catch (Exception $e) {
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:1px 1px 1px 1px; width:auto; display:inline;'>&nbsp;&nbsp;&nbsp;</div>";
				$html = "";
			}
		}
		return $html;
	}
		
	public function radioButton($name, $value, $html_attributes=""){
		if (!$this->readOnly) {
			$html = "<input type=\"radio\" value=\"".$value."\" name=\"".$name."\" ";
			$html .= $html_attributes;
			$html .= " />";
		} else {
			try {
				$html_attributes = str_replace("&", "&amp;", $html_attributes);
				$html_attributes = str_replace(">", "&gt;", $html_attributes);
				$html_attributes = str_replace("<", "&lt;", $html_attributes);
				$x = new SimpleXMLElement("<input $html_attributes />");
				$val = ($x["checked"]) ? "&nbsp;X&nbsp;" : "&nbsp;&nbsp;&nbsp;";
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:1px 1px 1px 1px; width:auto; display:inline;'>".$val."</div>&nbsp;";
				$html .= "<input type=\"hidden\" value=\"".$x["checked"]."\" name=\"".$name."\" />\n";
			} catch (Exception $e) {
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:1px 1px 1px 1px; width:auto; display:inline;'>&nbsp;&nbsp;&nbsp;</div>";
				$html = "";
			}
		}
		return $html;
	}
	
	public function textField($name, $html_attributes="") {
		if (!$this->readOnly) {
			$html = "<input type=\"text\" name=\"".$name."\" id=\"".$name."\" ";
			$html .= $html_attributes;
			$html .= " />";
		} else {			
			if (empty($html_attributes)) {
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>&nbsp;</div>";
			} else {
				try {
					$html_attributes = str_replace("&", "&amp;", $html_attributes);
					$html_attributes = str_replace(">", "&gt;", $html_attributes);
					$html_attributes = str_replace("<", "&lt;", $html_attributes);
					$x = new SimpleXMLElement("<input $html_attributes />");
					$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>".$x["value"]."</div>";
					$html .= "<input type=\"hidden\" value=\"".$x["value"]."\" name=\"".$name."\" />\n";
				} catch (Exception $e) {
					$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>&nbsp;</div>";
				}
			}
		}
		return $html;
	}
	
	public function textArea($name, $value="", $html_attributes="") {
		if (!$this->readOnly) {
			$html = "<textarea id=\"".$name."\" name=\"".$name."\" ";
			$html .= $html_attributes;
			$html .= ">";
			$html .= $value;
			$html .= "</textarea>";
		} else {
			if (strlen($value) > 0) {
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>".nl2br($value)."</div>";
				$html .= "<input type=\"hidden\" value=\"".$value."\" name=\"".$name."\" />\n";
			} else {
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>&nbsp;</div>";
			}
			
		}
		return $html;
	}
	
	public function hiddenField($name, $value, $html_attributes=""){
		$html = "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\"";
		$html .= $html_attributes;
		$html .= " />";
		return $html;
	}
	
	public function passwordField($name, $html_attributes=""){
		$html = "<input type=\"password\" name=\"".$name."\" ";
		$html .= $html_attributes;
		$html .= " />";
		return $html;
	}
	
	public function select($name, $values, $selected="", $numericKey=false, $html_attributes="") {
		if (!$this->readOnly) {
			$html = "<select class=\"element\" name=\"".$name."\" ".$html_attributes.">\n";
			foreach ($values as $key=>$value){
				$html .= "\t<option ";
				if (!$numericKey) {
					if (is_numeric($key)){
						$key = $value;
					}
				}
				$html .= " value=\"$key\"";
				if($selected==$key){
					$html .= " selected=\"selected\"";
				}
				$html .= ">$value</option>\n";
			}		
			$html .= "</select>\n";
		} else {
			foreach ($values as $key=>$value) {				
				$val = $value;
				if($selected==$key){
					break;
				}				
			}
			$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>".$val."</div>";
			$html .= "<input type=\"hidden\" value=\"".$val."\" name=\"".$name."\" />\n";
		}
		return $html;
	}
}