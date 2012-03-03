<?php

/**
 * @copyright (C) 2012 KANekT
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package nya_smiles
 */

if (!defined('FORUM'))
	die();

class iSmilies {
	public $Smilies;

	//
	public function __construct() {
		$this->Smilies = array();
	}

    public function add_Smilie($smilie, $src, $title = NULL, $width = NULL, $height = NULL) {
        if (is_null($smilie))// || !is_array($Smile)
        {
            return false;
        }
        if($title != NULL)
            $this->Smiles[$smilie]['title'] = $title;
        else
            $this->Smiles[$smilie]['title'] = $smilie;
        $this->Smiles[$smilie]['src'] = $src;
        $this->Smiles[$smilie]['width'] = $width;
        $this->Smiles[$smilie]['height'] = $height;
    }
}

//$ext_Smilies     = new iSmilies();
$ext_Smilie     = new iSmilies();
?>