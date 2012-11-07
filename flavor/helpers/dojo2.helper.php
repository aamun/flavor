<?php
class dojo2 extends singleton {
	
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
	
	public function includeDojoJs($js) {		
		$js = "<script type=\"text/javascript\" src=\"".$this->path."app/libs/js/dojo/".$js.".js\" djConfig=\"parseOnLoad:true, isDebug:true, preventBackButtonFix: false,dojoIframeHistoryUrl:'".$this->path."app/views/elements/iframe_history.html'\"></script>\n";
		return $js;
	}
	
	public function requireDijit($widget){
		return "<script type=\"text/javascript\"> dojo.require(\"dijit.".$widget."\"); </script>\n";
	}
	
	public function requireDojox($widget){
		return "<script type=\"text/javascript\"> dojo.require(\"dojox.".$widget."\"); </script>\n";
	}
	
	public function requireDojo($widget){
		return "<script type=\"text/javascript\"> dojo.require(\"dojo.".$widget."\"); </script>\n";
	}
	
	public function dojoFormPost($id, $action=""){
		$html = $this->dojoAlert("Por favor llene el formulario correctamente.", "Formulario","alert".$id);
		$html.= "<div dojoType=\"dijit.form.Form\" id=\"".$id."\" jsId=\"".$id."\" encType=\"multipart/form-data\"";
        $html.= "action=\"".$action."\" method=\"post\">\n";
        $html.= "<script type=\"dojo/method\" event=\"onReset\">\n";
        $html.= "\treturn confirm('Press OK to reset widget values');\n";
        $html.= "</script>\n";
        $html.= "<script type=\"dojo/method\" event=\"onSubmit\">\n";
        $html.= "\tif (this.validate()) {\n";
        $html.= "\t\treturn true\n";
        $html.= "\t} else {\n";
        $html.= "\t\t dijit.byId('alert".$id."').show();\n";
        $html.= "\t\treturn false;\n";
        $html.= "\t}\n";
        $html.= "\treturn true;\n";
        $html.= "</script>\n";
        
        return $html;
	}
	
	public function dojoAjaxForm($id, $action=""){		
		$html = $this->dojoAlert("Por favor llene el formulario correctamente.", "Formulario","alert".$id);
		$html.= "<div dojoType=\"dijit.form.Form\" id=\"".$id."\" jsId=\"".$id."\" method=\"post\">\n";
        $html.= "<script type=\"dojo/method\" event=\"onSubmit\">\n";
        $html.= "\t  \n";
        $html.= "\tif (this.validate() && validTextArea()) {\n";
        $html.= "\t\t dojoAjaxRequest('".$id."','".$action."'); return false;\n";
        $html.= "\t} else {\n";
        $html.= "\t\t dijit.byId('alert".$id."').show();\n";
        $html.= "\t\treturn false;\n";
        $html.= "\t}\n";
        $html.= "\treturn true;\n";
        $html.= "</script>\n";
        
        return $html;
	}

	public function dojoButton($value, $html_attributes="", $onclick=""){
		$html =	"<button dojoType=\"dijit.form.Button\" ".$html_attributes."";
		if($this->readOnly)
			$html.= "disabled=\"true\"";
  		$html.=	" iconClass=\"dijitEditorIcon dijitEditorIconSave\" value=\"".$value."\"> \n";
  		$html.=	"\t ".$value." \n";
  		$html.= "<script type=\"dojo/method\" event=\"onClick\" args=\"evt\">";
  		$html.= $onclick;
  		$html.= "</script>";
		$html.=	" </button>\n";
		
		return $html;
	}
	
	public function dojoPrint($value, $html_attributes=""){
		$html =	"<button dojoType=\"dijit.form.Button\" ".$html_attributes."";
  		$html.=	"iconClass=\"dijitEditorIcon dijitEditorIconPrint\" value=\"".$value."\"> \n";
  		$html.=	"\t ".$value." \n";
		$html.=	" </button>\n";
		
		return $html;
	}
	
	public function dojoSubmit($value="submit",$id="sumbmit"){
		return $this->dojoButton($value,"type=\"submit\" id=\"".$id."\"");
	}
	
	public function dojoReset($value="reset"){		
		return $this->dojoButton($value,"type=\"reset\"");
	}
	
	public function linkTo($text, $url="", $html_attributes="") {		
		$html = "<a href=\"".$this->path.$url;
		$html .= "\"";		
		$html .= " $html_attributes ";		
		$html .= ">".$text."</a>\n";		
		return $html;
	}
	
	public function dojoLinkToConfirm($text, $url="", $id="dialog1"){
		$html = $this->linkTo($text, $url, "onclick=\"dijit.byId('".$id."').show(); return false;\"\n");
		$html.= "\n<div style=\"display:none;\" dojoType=\"dijit.Dialog\" id=\"".$id."\" title=\"Confirm\"";
		$html.= "execute=\"document.location.href='".$this->path.$url."'\">\n";
		$html.= "\t<table>\n";
		$html.= "\t\t<tr>\n";
		$html.= "\t\t\t<td align=\"center\" colspan=\"2\" width=\"200px\">\n";
		$html.= "\t\t\t\tEst&aacute; seguro?\n";
		$html.= "\t\t\t</td>\n";
		$html.= "\t\t</tr>\n";
		$html.= "\t\t<tr>\n";
		$html.= "\t\t\t<td align=\"center\" >\n";
		$html.= $this->dojoSubmit("Aceptar");
		$html.= "\t\t\t</td>\n";
		$html.= "\t\t\t<td align=\"center\" >\n";
		$html.= $this->dojoButton("Cancelar","onClick=\"dijit.byId('".$id."').hide()\"");
		$html.= "\t\t\t</td>\n";
		$html.= "\t\t</tr>\n";
		$html.= "\t</table>\n";		
		$html.= "</div>\n";
		
		return $html;
	}
	
	public function dojoAjaxToConfirm($text, $script="", $id="dialog2"){
		$html = $this->linkTo($text, "#", "onclick=\"dijit.byId('".$id."').show(); return false;\"\n");
		$html.= "\n<div style=\"display:none;\" dojoType=\"dijit.Dialog\" id=\"".$id."\" title=\"Confirmar\"";
		$html.= "execute=\"".$script."\">\n";
		$html.= "\t<table>\n";
		$html.= "\t\t<tr>\n";
		$html.= "\t\t\t<td align=\"center\" colspan=\"2\" width=\"200px\">\n";
		$html.= "\t\t\t\tEst&aacute; seguro?\n";
		$html.= "\t\t\t</td>\n";
		$html.= "\t\t</tr>\n";
		$html.= "\t\t<tr>\n";
		$html.= "\t\t\t<td align=\"center\" >\n";
		$html.= $this->dojoSubmit("Aceptar",$id.strtotime("now"));
		$html.= "\t\t\t</td>\n";
		$html.= "\t\t\t<td align=\"center\" >\n";
		$html.= $this->dojoButton("Cancelar","onClick=\"dijit.byId('".$id."').hide(); return false;\"");
		$html.= "\t\t\t</td>\n";
		$html.= "\t\t</tr>\n";
		$html.= "\t</table>\n";		
		$html.= "</div>\n";
		
		return $html;
	}

	public function dojoCheckBox($name, $label, $html_attributes=""){
		$html ="<input type=\"checkbox\" dojoType=\"dijit.form.CheckBox\" name=\"".$name."\" id=\"".$name."\"";
		$html.= $html_attributes; 
  		$html.=">";
		$html.="<label for=\"".$name."\">".$label."</label>\n";
		
		return $html;
	}
	
	public function dojoSelect($name, $values, $selected="", $html_attributes=""){
		$html = " <select dojoType=\"dijit.form.FilteringSelect\"";
		$html.= " name=\"".$name."\"";
		$html.= " autoComplete=\"false\"";
		$html.= " invalidMessage=\"Invalid Item\"";
		$html.= $html_attributes.">\n";
				
		foreach ($values as $key=>$value){
			$html .= "\t<option ";
			if (is_numeric($key)){
				$key = $value;
			}
			$html .= " value=\"$key\"";
			if($selected==$key){
				$html .= " selected=\"selected\"";
			}
			$html .= ">$value</option>\n";
		}		
		$html .= "</select>\n";		
		return $html;
	}
	
	public function dojoTextField($name, $required="true", $value="", $message="Este campo es requerido.", $html_attributes=""){		
		$html = "<input type=\"text\" name=\"".$name."\" value=\"".$value."\" ";
		$html.= "dojoType=\"dijit.form.ValidationTextBox\" class=\"dojoTextField\" required=\"".$required."\" ucfirst=\"true\"";
		$html.= "\t invalidMessage	=\"".$message."\" \n";
		if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
		$html.= "/>";
		
		return $html;
	}
	
	public function dojoPassField($name, $required="true", $value="", $message="Este campo es requerido", $html_attributes=""){		
		$html = "<input type=\"password\" name=\"".$name."\" value=\"".$value."\" ";
		$html.= " class=\"dojoPassField\" dojoType=\"dijit.form.ValidationTextBox\" required=\"".$required."\" ";
		$html.= $html_attributes;
		$html.= "\t trim=\"true\" \n";
		$html.= "\t invalidMessage	=\"".$message."\" \n";
		if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
		$html.= "/>";
		
		return $html;
	}
	
	public function dojoEmailField($name, $required="true", $value="", $message="Este campo es requerido", $html_attributes=""){
		$html = " <input type=\"text\" id=\"".$name."\" name=\"".$name."\" class=\"dojoEmailField\" value=\"".$value."\"\n";
  		$html.= $html_attributes;
    	$html.= "\t dojoType=\"dijit.form.ValidationTextBox\" \n";
    	$html.= "\t regExp=\"(^[0-9a-zA-Z]+(?:[._][0-9a-zA-Z]+)*)@([0-9a-zA-Z]+(?:[._-][0-9a-zA-Z]+)*\.[0-9a-zA-Z]{2,3})$\" \n";
    	$html.= "\t trim=\"true\" \n";
    	$html.= "\t required=\"".$required."\" \n";
    	$html.= "\t invalidMessage=\"".$message."\" /> \n";
    	
		
		return $html;
	}
	
	public function dojoIntegerField($name, $required="true", $value="", $message="No es un n&uacute;mero entero", $html_attributes=""){
		$html = "<input type=\"text\" id=\"".$name."\" name=\"".$name."\" class=\"dojoIntegerField\" value=\"".$value."\"\n";
  		$html.= $html_attributes;
    	$html.= "\t dojoType=\"dijit.form.ValidationTextBox\" \n";
    	$html.= "\t regExp=\"^(?:\+|-)?\d+$\" \n";
    	$html.= "\t trim=\"true\" \n";
    	$html.= "\t required=\"".$required."\" \n";
    	if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
    	$html.= "\t invalidMessage=\"".$message."\" /> \n";
		
		return $html;
		
	}
	
	public function dojoRealField($name, $required="true", $value="", $message="No es un n&uacute;mero real", $html_attributes=""){
		$html = "<input type=\"text\" id=\"".$name."\" name=\"".$name."\" class=\"dojoRealField\" value=\"".$value."\"\n";
  		$html.= $html_attributes;
    	$html.= "\t dojoType=\"dijit.form.ValidationTextBox\" \n";
    	$html.= "\t regExp=\"^(?:\+|-)?\d+\.\d*$\" \n";
    	$html.= "\t trim=\"true\" \n";
    	$html.= "\t required=\"".$required."\" \n";
    	if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
    	$html.= "\t invalidMessage=\"".$message."\" /> \n";
		
		return $html;
		
	}
	
	public function dojoZipField($name, $required="true", $value="", $message="C&oacute;digo postal de 5 digitos (ej: 23245).", $html_attributes=""){
  		$html = "<input type=\"text\" id=\"".$name."\" name=\"".$name."\" class=\"dojoZipField\" value=\"".$value."\"\n";
  		$html.= $html_attributes;
    	$html.= "\t dojoType=\"dijit.form.ValidationTextBox\" \n";
    	$html.= "\t regExp=\"[0-9][0-9][0-9][0-9][0-9]\" \n";
    	$html.= "\t trim=\"true\" \n";
    	$html.= "\t required=\"".$required."\" \n";
    	if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
    	$html.= "\t invalidMessage=\"".$message."\" /> \n";
		
		return $html;
		
	}
	
	public function dojoUrlField($name, $required="true", $value="", $message="Url no V&aacute;lida. Usar formato , http://....", $html_attributes=""){
		$html = "<input type=\"text\" id=\"".$name."\" name=\"".$name."\" class=\"long\" \n";
  		$html.= $html_attributes;
    	$html.= "\t dojoType=\"dijit.form.ValidationTextBox\" \n";
    	$html.= "\t regExp=\"^(ht|f)tp(s?)\:\/\/[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*(:(0-9)*)*(\/?)( [a-zA-Z0-9\-\.\?\,\'\/\\\+&%\$#_]*)?$\" \n";
    	$html.= "\t trim=\"true\" \n";
    	$html.= "\t required=\"".$required."\" \n";
    	if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
    	$html.= "\t invalidMessage=\"".$message."\" /> \n";
		
		return $html;
		
	}
	
	public function dojoDateField($name, $required="true", $format="mm/dd/yyyy", $min="2004-01-01", $max="2006-12-31"){
		$html =	"<input type=\"text\" name=\"".$name."\" value=\"2005-12-30\"\n";
      	$html.=	"\t dojoType=\"dijit.form.DateTextBox\"\n";
      	$html.=	"\t constraints=\"{min:'".$min."',max:'".$max."',formatLength:'short'}\"\n";
      	$html.=	"\t required=\"".$required."\"\n";
      	$html.=	"\t trim=\"true\"\n";
      	$html.=	"\t promptMessage=".$format;
      	if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
        $html.=	"\t invalidMessage=\"Fecha no V&aacute;lida. Usar formato ".$format.".\" />";    

		return $html;
	}
	
	public function dojoTextArea($name, $required="true", $value="", $message="Este campo es requerido.", $html_attributes=""){
		$html = "<textarea id=\"".$name."\" name=\"".$name."\" dojoType=\"dijit.form.Textarea\"";
		$html.= "class=\"dojoTextArea\"\n";
		$html.= "required=\"".$required."\" \n";
		$html.= "\t trim=\"true\" \n";
		$html.= $html_attributes;
		if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
    	$html.= "invalidMessage=\"".$message."\" > \n";
		$html.= $value;
		$html.= "</textarea>\n";
		
		return $html;
	}
	
	
	public function dojoEditor($name, $width=500, $height=200){
		$html = "<div style=\"border: 1px solid #ccc; width:".$width."px; height:".$height."px;\">";
  		$html.= "<textarea name=\"".$name."\" dojoType=\"dijit.Editor\">";     
  		$html.= "</textarea>";
		$html.= "</div>";
		
		return $html;
		
	}
	
	public function dojoRadioButton($name, $values, $selected){

		foreach ($values as $key=>$value){
			$html.= "<input dojoType=\"dijit.form.RadioButton\" type=\"radio\"";
			$html.= "\t name=\"".$name."\" id=\"".$name."rb".$key."\" value=\"".$value."\"";
			if($this->readOnly)
				$html.= "\t disabled=\"true\" \n";
			if($value == $selected)
				$html.= "checked=\"checked\"";
			$html.="/>";
			$html.= "<label for=\"".$name."rb".$key."\">".$value."</label>";
			$html.= "&nbsp&nbsp";
		}
		
		return $html;
	}
	
	public function dojoLightbox($name, $image, $title, $group=false){
		$html = "\t <a href=\"".$this->path.'app/'.$this->type."/images/".$image."\" dojoType=\"dojox.image.Lightbox\"\n";
  		$html.= "title=\"".$title."\"\n";
  		if($group != false)
  			$html .= "group=\"".$group;
  		$html .="\">".$name."</a>";
		
  		return $html;
	}
			
	public function dojoTimeBox($name, $required="true", $value="00:00:00"){
  		$html ="<input id=\"q4\" type=\"text\" name=\"".$name."\" class=\"medium\" value=\"T".$value."\"";
      	$html.="dojoType=\"dijit.form.TimeTextBox\"";
      	$html.="constraints=\"{timePattern:'HH:mm:ss'}\"";
      	$html.="required=\"".$required."\";";
      	if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
      	$html.="invalidMessage=\"Invalid time. Use HH:mm:ss where HH is 00 - 23 hours.\">";
		
		
		return $html;
	
	}
	
	public function dojoNumberSpinner($name){
		$html ="\t<input dojoType=\"dijit.form.NumberSpinner\"";
    	$html.="value=\"900\"";
    	$html.="class=\"medium\"";
    	$html.="constraints=\"{max:1550,places:0}\"";
    	$html.="name=\"".$name."\"";
    	if($this->readOnly)
			$html.= "\t disabled=\"true\" \n";
    	$html.="id=\"".$name."\">\n";
		
		
		return $html;
	}
	
	public function dojoPane($class="box"){
		return "<div dojoType=\"dijit.layout.ContentPane\" class=\"".$class."\" hasShadow=\"true\">";
		
		
	}
	
	public function dojoTabContainer($tabPosition="top", $region="center"){
		$html = "<div dojoType=\"dijit.layout.TabContainer\" style=\"width:100%; height:100%;\" region=\"".$region."\"";
        $html.= " tabPosition=\"".$tabPosition."\" tabStrip=\"true\">\n";

        return $html;
	}
	
	public function dojoContentPane($title="", $selected="false"){
		return "<div style=\"display:none\" dojoType=\"dijit.layout.ContentPane\" title=\"".$title."\" selected=\"".$selected."\"> \n";
	}
	
	public function dojoSplitContentPane($region="top", $splitter="false"){
		return "<div dojoType=\"dijit.layout.ContentPane\" region=\"".$region."\" splitter=\"".$splitter."\"> \n";
	}
	
	public function dojoAlert($text, $title="no title", $id="alert1"){
		$html = "<div id=\"".$id."\" dojoType=\"dijit.Dialog\" title=\"".$title."\">\n";
		$html.= "\t<table>\n";
		$html.= "\t\t<tr><td>\n";
		$html.= $text;
		$html.= "\t\t</td></tr>\n";
		$html.= "\t\t<tr><td align=\"center\">\n";
		$html.= $this->dojoSubmit('Ok','id'.strtotime("now"));
		$html.= "\t\t</td></tr>\n";
		$html.= "\t</table>\n";
		$html.= "</div>\n";
		return $html;
	}
	
	public function dojoUrlDialog($id, $url="", $title="no title"){
		$html = "<div id=\"".$id."\" dojoType=\"dijit.Dialog\"";
		$html.= "title=\"".$title."\" style=\"display:none; width: 400px;\"";
		$html.= "href=\"".$this->path.$url."\" refreshOnShow=\"true\"></div>";
		
		return $html;
	}
	
	public function dojoRemoteContentPane($title, $selected="false", $url){
		$html = "<div style=\"display:none\" dojoType=\"dijit.layout.ContentPane\" title=\"".$title."\" refreshOnShow=\"true\"";
  		$html.= " selected=\"".$selected."\" href=\"".$url."\">";
  		$html.= "</div>";
  		
  		return $html;
	}
	
	public function dojoBorderContainer($id="bc1"){
		return "<div dojoType=\"dijit.layout.BorderContainer\" gutters=\"true\" id=\"".$id."\">\n";
	}
	
	public function dojoSplitContainer($id="split1", $region="center", $orientation="horizontal"){
		$html = "<div dojoType=\"dijit.layout.BorderContainer\" liveSplitters=\"false\" design=\"sidebar\"";
        $html.= " orientation=\"".$orientation."\" region=\"".$region."\" id=\"".$id."\">\n";
        
        return $html;
	}
	
	public function dojoAccordionContainer($width="300px", $id="ac1"){
		$html = "<div dojoType=\"dijit.layout.AccordionContainer\" minSize=\"20\" style=\"width:".$width."\"";
		$html.= " id=\"".$id."\" region=\"leading\" splitter=\"true\">\n";
		
		return $html;
	}
	
	public function dojoAccordionPane($title){
		return "<div style=\"display:none;\" dojoType=\"dijit.layout.AccordionPane\" title=\"".$title."\"> \n";
	}
	
	Public function dojoRemotePane($url, $id="rp1", $extra=""){
		$html = "<div dojoType=\"dijit.layout.ContentPane\" doLayout=\"false\";  hasShadow=\"true\" id=\"".$id."\"";
  		$html.= " href=\"".$this->path.$url."\" ".$extra." >";
		$html.= "</div>\n";
		
		return $html;
	}
	
}