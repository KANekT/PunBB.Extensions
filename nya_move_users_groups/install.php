<?php 
/**
 * Installer extension
 *
 * @copyright Copyright (C) KANekT 2008-2012 @ http://blog.kanekt.ru
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * Donate Web Money Z104136428007 R346491122688
 * @package Move user to group
 */

defined('MUG_INSTALL') or die('Direct access not allowed');
if (!$forum_db->field_exists('groups', 'g_mug_count'))
    $forum_db->add_field('groups', 'g_mug_count', 'SMALLINT(8)', true, '0');
if (!$forum_db->field_exists('groups', 'g_mug_enable'))
    $forum_db->add_field('groups', 'g_mug_enable', 'TINYINT(1)', true, '0');
