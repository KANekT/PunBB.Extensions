<?php 
/**
 * Installer
 *
 */

defined('FIRST_POST_INSTALL') or die('Direct access not allowed');

	if (!$forum_db->field_exists('groups', 'g_fp_enable'))
		$forum_db->add_field('groups', 'g_fp_enable', 'TINYINT(1)', true, '1');
    if (!$forum_db->field_exists('topics', 'post_show_first_post'))
        $forum_db->add_field('topics', 'post_show_first_post', 'TINYINT(1)', false, 0);
