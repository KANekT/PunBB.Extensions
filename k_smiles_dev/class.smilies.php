<?php

/**
 * @copyright (C) 2012 KANekT
 * @package k_smiles
 */

if (!defined('FORUM'))
	die();

class iSmiles {
	public $Smiles;

	//
	public function __construct() {
		$this->Smiles = array();
	}

	public function add_Smile($smile) {
		if (is_null($smile))// || !is_array($Smile)
			return false;
		$this->Smiles = array_merge($this->Smiles, $smile);
	}

    public function add_Smilie($smile, $src, $title = NULL, $width = NULL, $height = NULL, $view = NULL) {
		if (is_null($smile))// || !is_array($Smile)
			return false;

			if($title != NULL)
				$this->Smiles[$smile]['title'] = $title;
			else
				$this->Smiles[$smile]['title'] = $smile;

			$this->Smiles[$smile]['src'] = $src;
			$this->Smiles[$smile]['width'] = $width;
			$this->Smiles[$smile]['height'] = $height;
			$this->Smiles[$smile]['view'] = $view;
	}
}

$ext_Smiles = $ext_Smilie = new iSmiles();