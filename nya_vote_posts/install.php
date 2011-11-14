<?php 
/**
 * Vote Posts installer
 * 
 * @copyright (C) 2011 KANekT like post extension for PunBB (C)
 * @based on 2011 hcs nya_like extension for PunBB (C)
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Vote Posts
 */

defined('VOTE_POSTS_INSTALL') or die('Direct access not allowed');

	if (!defined('EXT_CUR_VERSION')){
		if (!$forum_db->table_exists('vote_posts')) {
			$schema = array(
				'FIELDS' => array(
					'id'		=> array(
						'datatype'		=> 'SERIAL',
						'allow_null'	=> false
					),
					'user_id'	=> array(
						'datatype'		=> 'INT(10) UNSIGNED',
						'allow_null'	=> false,
						'default'		=> '0'
					),
					'from_user_id'	=> array(
						'datatype'		=> 'INT(10) UNSIGNED',
						'allow_null'	=> false,
						'default'		=> '0'
					),					
					'time'		=> array(
						'datatype'		=> 'INT(10) UNSIGNED',
						'allow_null'	=> false
					),					
					'post_id'	=> array(
						'datatype'		=> 'INT(10) UNSIGNED',
						'allow_null'	=> false
					),
					'topic_id'		=> array(
						'datatype'		=> 'INT(10) UNSIGNED',
						'allow_null'	=> false
					),
					'reason'		=> array(
						'datatype'		=> 'TEXT',
						'allow_null'	=> false
					),
					'vote_up'			=> array(
						'datatype'		=> 'TINYINT(1)',
						'allow_null'	=> false,
						'default'		=> '0'
					),
					'vote_down'		=> array(
						'datatype'		=> 'TINYINT(1)',
						'allow_null'	=> false,
						'default'		=> '0'
					)
				),
				'PRIMARY KEY'	=> array('id'),
				'INDEXES'		=> array(
					'vote_post_id_idx'	=> array('post_id'),
					'vote_time_idx'	=> array('time'),
					'vote_multi_user_id_idx'		=> array( 'from_user_id', 'topic_id')
				)				
			);
			$forum_db->create_table('vote_posts', $schema);
		}
		
		if (!$forum_db->field_exists('users', 'vote_enable'))
			$forum_db->add_field('users', 'vote_enable', 'TINYINT(1)', true, '1');
		if (!$forum_db->field_exists('users', 'vote_disable_adm'))
			$forum_db->add_field('users', 'vote_disable_adm', 'TINYINT(1)', true, '0');
			
		if (!$forum_db->field_exists('users', 'vote_down'))
			$forum_db->add_field('users', 'vote_down', 'INT(10)', true, '0');
		if (!$forum_db->field_exists('users', 'vote_up'))
			$forum_db->add_field('users', 'vote_up', 'INT(10)', true, '0');
			
		if (!$forum_db->field_exists('posts', 'vote_down'))
			$forum_db->add_field('posts', 'vote_down', 'INT(10)', true, '0');
		if (!$forum_db->field_exists('posts', 'vote_up'))
			$forum_db->add_field('posts', 'vote_up', 'INT(10)', true, '0');

		if (!$forum_db->field_exists('groups', 'g_vote_down_min'))
			$forum_db->add_field('groups', 'g_vote_down_min', 'INT(10)', true, '0');
		if (!$forum_db->field_exists('groups', 'g_vote_up_min'))
			$forum_db->add_field('groups', 'g_vote_up_min', 'INT(10)', true, '0');
		if (!$forum_db->field_exists('groups', 'g_vote_enable'))
			$forum_db->add_field('groups', 'g_vote_enable', 'TINYINT(1)', true, '1');
		$vote_posts_config = array(
			'o_vote_posts_enabled'			=> '1',
			'o_vote_posts_timeout'			=> '300',
			'o_vote_posts_maxmessage'		=> '400',
			'o_vote_posts_show_full'		=>	'1'
		);
		foreach ($vote_posts_config as $key => $value) {
			if(!array_key_exists($key, $forum_config)) {
				$query_vote_posts = array(
				'INSERT'	=> 'conf_name, conf_value',
					'INTO'		=> 'config',
					'VALUES'	=> '\''.$key.'\', \''.$forum_db->escape($value).'\''
				);
				$forum_db->query_build($query_vote_posts) or error(__FILE__, __LINE__);
			}
		}
		unset($query_vote_posts);		
		require_once FORUM_ROOT.'include/cache.php';
		generate_config_cache();
	}
