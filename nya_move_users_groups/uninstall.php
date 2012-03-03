<?php
/**
 * Uninstaller extension
 *
 * @copyright Copyright (C) KANekT 2008-2012 @ http://blog.kanekt.ru
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * Donate Web Money Z104136428007 R346491122688
 * @package Move user to group
 */

defined('MUG_UNINSTALL') or die('Direct access not allowed');

$forum_db->drop_field('groups', 'g_mug_count');
$forum_db->drop_field('groups', 'g_mug_enable');
