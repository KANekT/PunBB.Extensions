<?php
/**
 * Uninstaller extension
 *
 * @author KANekT
 * @copyright (C) 2011-2012 KANekT extension for PunBB
 * @copyright Copyright (C) 2011-2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Moder - Author
 */

defined('MTA_UNINSTALL') or die('Direct access not allowed');

$forum_db->drop_field('groups', 'g_mta_enable');