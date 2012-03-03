<?php 
/**
 * Installer extension
 *
 * @author KANekT
 * @copyright (C) 2011-2012 KANekT extension for PunBB
 * @copyright Copyright (C) 2011-2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Moder - Author
 */

defined('MTA_INSTALL') or die('Direct access not allowed');

	if (!$forum_db->field_exists('groups', 'g_mta_enable'))
		$forum_db->add_field('groups', 'g_mta_enable', 'TINYINT(1)', true, '0');
