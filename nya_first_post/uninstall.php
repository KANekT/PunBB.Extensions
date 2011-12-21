<?php
/**
 * Reputation uninstaller
 * 
 * @author hcs
 * @copyright (C) 2011 hcs reputation extension for PunBB
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package reputation
 */

defined('FIRST_POST_UNINSTALL') or die('Direct access not allowed');

$forum_db->drop_field('groups', 'g_fp_enable');
$forum_db->drop_field('topics', 'post_show_first_post');