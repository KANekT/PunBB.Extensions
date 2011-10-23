<?php

/**
 * nya_jquery_ui
 *
 * @copyright (C) 2011 KANekT
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package nya_jquery_ui
 */

if (!defined('FORUM'))
	die();

class jQueryUI {
	public $UI_js;
	public $UI_css;
	public $UI_style;
	public $UI_style_url;


	//
	public function __construct() {
		$this->UI_js = array();
		$this->UI_css = array();
		$this->UI_style = array();
		$this->UI_style_url = array();
	}
	
	//
	public function add_jQuery_UI_style($UI_style = NULL, $exp) {
		if (is_null($UI_style))// || !is_array($UI_js)
		{
			return false;
		}
		
		$this->UI_style[$exp] = $UI_style;
	}
	

	//
	public function add_jQuery_UI_style_url($UI_style_url = NULL, $exp) {
		if (is_null($UI_style_url))// || !is_array($UI_js)
		{
			return false;
		}
		
		$this->UI_style_url[$exp] = $UI_style_url;
	}


	//
	public function add_jQuery_UI($UI_js = NULL) {
		if (is_null($UI_js))// || !is_array($UI_js)
		{
			return false;
		}

		$this->UI_css["Core"] = "jquery.ui.core";
		$this->UI_css["Theme"] = "jquery.ui.theme";
		
		switch ($UI_js)
		{
			case "Draggable":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Mouse"] = "jquery.ui.mouse.min";
				$this->UI_js["Draggable"] = "jquery.ui.draggable.min";
				break;

			case "Droppable":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Mouse"] = "jquery.ui.mouse.min";
				$this->UI_js["Draggable"] = "jquery.ui.draggable.min";
				$this->UI_js["Droppable"] = "jquery.ui.droppable.min";
				break;

			case "Resizable":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Mouse"] = "jquery.ui.mouse.min";
				$this->UI_js["Resizable"] = "jquery.ui.resizable.min";
				break;

			case "Selectable":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Mouse"] = "jquery.ui.mouse.min";
				$this->UI_js["Selectable"] = "jquery.ui.selectable.min";
				break;
 
			case "Sortable":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Mouse"] = "jquery.ui.mouse.min";
				$this->UI_js["Sortable"] = "jquery.ui.sortable.min";
				break;
 
			case "Accordion":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Accordion"] = "jquery.ui.accordion.min";
				$this->UI_css["Accordion"] = "jquery.ui.accordion";
				break;
 
			case "Autocomplete":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Position"] = "jquery.ui.position.min";
				$this->UI_js["Autocomplete"] = "jquery.ui.autocomplete.min";
				$this->UI_css["Autocomplete"] = "jquery.ui.autocomplete";
				break;
 
			case "Button":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Button"] = "jquery.ui.button.min";
				$this->UI_css["Button"] = "jquery.ui.button";
				break;
 
			case "Dialog":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Position"] = "jquery.ui.position.min";
				$this->UI_js["Dialog"] = "jquery.ui.dialog.min";
				$this->UI_css["Dialog"] = "jquery.ui.dialog";
				break;
 
			case "Slider":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Mouse"] = "jquery.ui.mouse.min";
				$this->UI_js["Position"] = "jquery.ui.position.min";
				$this->UI_js["Slider"] = "jquery.ui.slider.min";
				$this->UI_css["Slider"] = "jquery.ui.slider";
				break;
 
			case "Tabs":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Tabs"] = "jquery.ui.tabs.min";
				$this->UI_css["Tabs"] = "jquery.ui.tabs";
				break;
 
			case "Datepicker":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Datepicker"] = "jquery.ui.datepicker.min";
				$this->UI_css["Datepicker"] = "jquery.ui.datepicker";
				break;
 
			case "Progressbar":
				$this->UI_js["Core"] = "jquery.ui.core.min";
				$this->UI_js["Widget"] = "jquery.ui.widget.min";
				$this->UI_js["Progressbar"] = "jquery.ui.progressbar.min";
				$this->UI_css["Progressbar"] = "jquery.ui.progressbar";
				break;

			case "Blind":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Blind"] = "jquery.effects.blind.min";
				break;

			case "Bounce":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Bounce"] = "jquery.effects.bounce.min";
				break;

			case "Clip":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Clip"] = "jquery.effects.clip.min";
				break;

			case "Drop":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Drop"] = "jquery.effects.drop.min";
				break;

			case "Explode":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Explode"] = "jquery.effects.explode.min";
				break;

			case "Fade":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Fade"] = "jquery.effects.fade.min";
				break;

			case "Fold":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Fold"] = "jquery.effects.fold.min";
				break;

			case "Highlight":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Highlight"] = "jquery.effects.highlight.min";
				break;

			case "Pulsate":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Pulsate"] = "jquery.effects.pulsate.min";
				break;

			case "Scale":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Scale"] = "jquery.effects.scale.min";
				break;

			case "Shake":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Shake"] = "jquery.effects.shake.min";
				break;

			case "Slide":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Slide"] = "jquery.effects.slide.min";
				break;

			case "Transfer":
				$this->UI_js["Core"] = "jquery.effects.core.min";
				$this->UI_js["Transfer"] = "jquery.effects.transfer.min";
				break;

			default:
				break;
		}
	}
}

$ext_jQuery_UI = new jQueryUI();
