<?php
/**
 * Like Post uninstaller
 * 
 * @copyright (C) 2011 KANekT like post extension for PunBB (C)
 * @based on 2011 hcs nya_like extension for PunBB (C)
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Like Post
 */

defined('VOTE_POSTS_UNINSTALL') or die('Direct access not allowed');

$forum_db->drop_table('vote_posts');
$forum_db->drop_field('users', 'vote_enable');
$forum_db->drop_field('users', 'vote_disable_adm');
$forum_db->drop_field('users', 'vote_down');
$forum_db->drop_field('users', 'vote_up');
$forum_db->drop_field('posts', 'vote_down');
$forum_db->drop_field('posts', 'vote_up');
$forum_db->drop_field('groups', 'g_vote_down_min');
$forum_db->drop_field('groups', 'g_vote_up_min');
$forum_db->drop_field('groups', 'g_vote_enable');

$config_names  =  array('o_vote_posts_enabled', 'o_vote_posts_timeout','o_vote_posts_maxmessage', 'o_vote_posts_show_full');
$query = array(
	'DELETE'	=> 'config',
	'WHERE'		=> 'conf_name IN (\''.implode('\', \'', $config_names).'\')'
);
$forum_db->query_build($query) or error(__FILE__, __LINE__);
