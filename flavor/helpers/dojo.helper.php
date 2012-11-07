<?php

class dojo extends singleton {

	protected $registry;
	protected $validateErrors;
	protected $path;
	public $type = "views";
	public $readOnly = false;

	public function __construct() {
		$this->registry = registry::getInstance();
		$this->path = $this->registry["path"];
	}

	public static function getInstance() {
		return parent::getInstance(get_class());
	}
	
	public function includeDojo() {		
		$js = "<script type=\"text/javascript\" src=\"".parentPath."/app/libs/js/dojo/dojo.js\" djConfig=\"parseOnLoad:true, isDebug:true, preventBackButtonFix: false\"></script>\n";
		return $js;
	}
	
	public function includeTheme($theme) {
		$css = "<link rel=\"stylesheet\" href=\"".parentPath."/app/libs/js/dijit/themes/".$theme."/".$theme.".css\" type=\"text/css\" />\n";
		return $css;
	}
		
	public function validationTextBox($name, $value, $placeHolder = "", $required = true, $invalidMessage = "",  $html_attributes="", $trim = true) {
		if (!$this->readOnly) {
			$html = "<input type=\"text\" value=\"".$value."\" name=\"".$name."\" id=\"".$name."\" dojoType=\"dijit.form.ValidationTextBox\" trim=\"".$trim."\" required=\"".$required."\" invalidMessage=\"".$invalidMessage."\" placeHolder=\"".$placeHolder."\" ";
			$html .= $html_attributes;
			$html .= " />";
		} else {
			if (strlen($value) > 0) {
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>".nl2br($value)."</div>";
			} else {
				$html = "<div style='background-color:#efefef; border:1px solid #FCF; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>&nbsp;</div>";
			}			
		}
		return $html;
	}
	

	public function textBox($name, $value, $placeHolder = "", $html_attributes="") {
		if (!$this->readOnly) {
			$html = "<input type=\"text\" value=\"".$value."\" name=\"".$name."\" id=\"".$name."\" dojoType=\"dijit.form.TextBox\" placeHolder=\"".$placeHolder."\" trim=\"true\" ";
			$html .= $html_attributes;
			$html .= " />";
		} else {			
			if (strlen($value) > 0) {
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>".nl2br($value)."</div>";
			} else {
				$html = "<div style='background-color:#efefef; border:1px solid #FCF; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>&nbsp;</div>";
			}
		}
		return $html;
	}
	
	public function textArea($name, $value="", $html_attributes="") {
		if (!$this->readOnly) {
			$html = "<textarea id=\"".$name."\" name=\"".$name."\" dojoType=\"dijit.form.Textarea\" ";
			$html .= $html_attributes;
			$html .= ">";
			$html .= $value;
			$html .= "</textarea>";
		} else {
			if (strlen($value) > 0) {
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>".nl2br($value)."</div>";
			} else {
				$html = "<div style='background-color:#efefef; border:1px solid #FCF; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>&nbsp;</div>";
			}
			
		}
		return $html;
	}
	
	public function editor($name, $value="", $html_attributes="") {
		if (!$this->readOnly) {
			$html = "<textarea id=\"".$name."\" name=\"".$name."\" dojoType=\"dijit.Editor\" ";
			$html .= $html_attributes;
			$html .= ">";
			$html .= $value;
			$html .= "</textarea>";
		} else {
			if (strlen($value) > 0) {
				$html = "<div style='background-color:#efefef; border:1px solid #999; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>".nl2br($value)."</div>";
			} else {
				$html = "<div style='background-color:#efefef; border:1px solid #FCF; font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 5px 5px 5px;'>&nbsp;</div>";
			}
			
		}
		return $html;
	}
	
	
}