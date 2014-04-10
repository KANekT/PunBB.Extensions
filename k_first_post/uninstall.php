<?php
/**
 * Uninstaller
 * 
 */

defined('FIRST_POST_UNINSTALL') or die('Direct access not allowed');

$forum_db->drop_field('groups', 'g_fp_enable');
$forum_db->drop_field('topics', 'post_show_first_post');