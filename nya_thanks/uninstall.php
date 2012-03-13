<?php
/**
 * Habr uninstaller
 *
 * @author KANekT
 * @copyright (C) 2011 KANekT Habr effect extension for PunBB (C)
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

defined('THANKS_UNINSTALL') or die('Direct access not allowed');

$forum_db->drop_table('thanks');

$forum_db->drop_field('users', 'thanks_disable_adm');
$forum_db->drop_field('users', 'thanks_enable');
$forum_db->drop_field('users', 'thanks');

$forum_db->drop_field('posts', 'thanks');

$forum_db->drop_field('groups', 'g_thanks_enable');
$forum_db->drop_field('groups', 'g_thanks_min');