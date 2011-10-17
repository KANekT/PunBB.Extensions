<?php

/**
 * nya_jqueryui
 *
 * @copyright (C) 2011 KANekT
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package nya_jqueryui
 */

if (!defined('FORUM'))
	die();

class jQueryUI {
	public $jsUI;
	public $cssUI;


	//
	public function __construct() {
		$this->jsUI = array();
		$this->cssUI = array();
	}


	//
	public function add_jQuery_UI($jsUI = NULL) {
		if (is_null($jsUI))// || !is_array($jsUI)
		{
			return false;
		}

		$this->cssUI["Core"] = "jquery.ui.core";
		$this->cssUI["Theme"] = "jquery.ui.theme";
		
		switch ($jsUI)
		{
			case "Draggable":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Mouse"] = "jquery.ui.mouse.min";
				$this->jsUI["Draggable"] = "jquery.ui.draggable.min";
				break;

			case "Droppable":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Mouse"] = "jquery.ui.mouse.min";
				$this->jsUI["Draggable"] = "jquery.ui.draggable.min";
				$this->jsUI["Droppable"] = "jquery.ui.droppable.min";
				break;

			case "Resizable":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Mouse"] = "jquery.ui.mouse.min";
				$this->jsUI["Resizable"] = "jquery.ui.resizable.min";
				break;

			case "Selectable":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Mouse"] = "jquery.ui.mouse.min";
				$this->jsUI["Selectable"] = "jquery.ui.selectable.min";
				break;
 
			case "Sortable":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Mouse"] = "jquery.ui.mouse.min";
				$this->jsUI["Sortable"] = "jquery.ui.sortable.min";
				break;
 
			case "Accordion":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Accordion"] = "jquery.ui.accordion.min";
				$this->cssUI["Accordion"] = "jquery.ui.accordion";
				break;
 
			case "Autocomplete":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Position"] = "jquery.ui.position.min";
				$this->jsUI["Autocomplete"] = "jquery.ui.autocomplete.min";
				$this->cssUI["Autocomplete"] = "jquery.ui.autocomplete";
				break;
 
			case "Button":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Button"] = "jquery.ui.button.min";
				$this->cssUI["Button"] = "jquery.ui.button";
				break;
 
			case "Dialog":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Position"] = "jquery.ui.position.min";
				$this->jsUI["Dialog"] = "jquery.ui.dialog.min";
				$this->cssUI["Dialog"] = "jquery.ui.dialog";
				break;
 
			case "Slider":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Mouse"] = "jquery.ui.mouse.min";
				$this->jsUI["Position"] = "jquery.ui.position.min";
				$this->jsUI["Slider"] = "jquery.ui.slider.min";
				$this->cssUI["Slider"] = "jquery.ui.slider";
				break;
 
			case "Tabs":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Tabs"] = "jquery.ui.tabs.min";
				$this->cssUI["Tabs"] = "jquery.ui.tabs";
				break;
 
			case "Datepicker":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Datepicker"] = "jquery.ui.datepicker.min";
				$this->cssUI["Datepicker"] = "jquery.ui.datepicker";
				break;
 
			case "Progressbar":
				$this->jsUI["Core"] = "jquery.ui.core.min";
				$this->jsUI["Widget"] = "jquery.ui.widget.min";
				$this->jsUI["Progressbar"] = "jquery.ui.progressbar.min";
				$this->cssUI["Progressbar"] = "jquery.ui.progressbar";
				break;

			case "Blind":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Blind"] = "jquery.effects.blind.min";
				break;

			case "Bounce":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Bounce"] = "jquery.effects.bounce.min";
				break;

			case "Clip":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Clip"] = "jquery.effects.clip.min";
				break;

			case "Drop":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Drop"] = "jquery.effects.drop.min";
				break;

			case "Explode":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Explode"] = "jquery.effects.explode.min";
				break;

			case "Fade":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Fade"] = "jquery.effects.fade.min";
				break;

			case "Fold":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Fold"] = "jquery.effects.fold.min";
				break;

			case "Highlight":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Highlight"] = "jquery.effects.highlight.min";
				break;

			case "Pulsate":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Pulsate"] = "jquery.effects.pulsate.min";
				break;

			case "Scale":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Scale"] = "jquery.effects.scale.min";
				break;

			case "Shake":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Shake"] = "jquery.effects.shake.min";
				break;

			case "Slide":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Slide"] = "jquery.effects.slide.min";
				break;

			case "Transfer":
				$this->jsUI["Core"] = "jquery.effects.core.min";
				$this->jsUI["Transfer"] = "jquery.effects.transfer.min";
				break;

			default:
				break;
		}

		return $this->jsUI;
	}
}

$ext_jQuery_UI = new jQueryUI();
?>