<?php

if (!defined('FORUM'))
	exit;

function install()
{
	global $forum_db;
		$schema = array(
			'FIELDS'  => array(
				'id'  => array(
					'datatype'  => 'SERIAL',
					'allow_null'  => false
				),
				'uid'  => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				),
				'from_uid'  => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				),
				'reason'  => array(
					'datatype'  => 'VARCHAR(300)',
					'allow_null'  => true
				),
				'method'  => array(
					'datatype'  => 'TINYINT(1)',
					'allow_null'  => false
				),
				'expire'  => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				),
				'pid'  => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				),
				'tid'  => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				),
				'warn_popup_show'  => array(
					'datatype'  => 'TINYINT(1)',
					'allow_null'  => false
				),
				'time'  => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				)
			),
			'PRIMARY KEY'  	=> array('id'),
			'INDEXES'		=> array(
				'warn_post_id_idx'	=> array('pid')
			)
		);
		$forum_db->create_table('warnings', $schema);
		if (!$forum_db->field_exists('users', 'warn_expiries'))
			$forum_db->add_field('users', 'warn_expiries', 'INT(10)', false, 0);
		if (!$forum_db->field_exists('users', 'warn_count'))
			$forum_db->add_field('users', 'warn_count', 'INT(10)', false, 0);
		if (!$forum_db->field_exists('users', 'warn_group_id'))
			$forum_db->add_field('users', 'warn_group_id', 'INT(10)', false, 0);
		if (!$forum_db->field_exists('users', 'warn_edit'))
			$forum_db->add_field('users', 'warn_edit', 'INT(10)', false, 0);
		$query = array(
		   'INSERT'  => 'conf_name, conf_value',
		   'INTO'    => 'config',
		   'VALUES'  => '\'o_warn_enabled\', \'1\'' 
		);
		$forum_db->query_build($query);
		$query = array(
		   'INSERT'  => 'conf_name, conf_value',
		   'INTO'    => 'config',
		   'VALUES'  => '\'o_warn_maxmessage\', \'100\'' 
		);
		$forum_db->query_build($query);

		$query = array(
		   'INSERT'  => 'g_title, g_user_title',
		   'INTO'    => 'groups',
		   'VALUES'  => '\'WarnGrSig\', \'Warn Signature\'' 
		);
		$forum_db->query_build($query);
		$query = array(
		   'INSERT'  => 'conf_name, conf_value',
		   'INTO'    => 'config',
		   'VALUES'  => '\'o_warn_group_sig\', '.$forum_db->insert_id()
		);
		$forum_db->query_build($query);

		$query = array(
		   'INSERT'  => 'g_title, g_user_title',
		   'INTO'    => 'groups',
		   'VALUES'  => '\'WarnGrAva\', \'Warn Avatar\'' 
		);
		$forum_db->query_build($query);
		$query = array(
		   'INSERT'  => 'conf_name, conf_value',
		   'INTO'    => 'config',
		   'VALUES'  => '\'o_warn_group_ava\', '.$forum_db->insert_id()
		);
		$forum_db->query_build($query);

				$query = array(
		   'INSERT'  => 'g_title, g_user_title, g_post_replies, g_post_topics, g_edit_posts, g_delete_posts, g_delete_topics',
		   'INTO'    => 'groups',
		   'VALUES'  => '\'WarnGrRead\', \'Warn Read Only\', 0, 0, 0, 0, 0' 
		);
		$forum_db->query_build($query);
		$query = array(
		   'INSERT'  => 'conf_name, conf_value',
		   'INTO'    => 'config',
		   'VALUES'  => '\'o_warn_group_read\', '.$forum_db->insert_id()
		);
		$forum_db->query_build($query);
}


function uninstall()
{
	global $forum_db, $forum_config;
	$query = array(
		'UPDATE'	=> 'users',
		'SET'		=> 'group_id=warn_group_id',
		'WHERE'		=> 'warn_group_id<>0'
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_db->drop_table('warnings');
	$forum_db->drop_field('users', 'warn_expiries');
	$forum_db->drop_field('users', 'warn_count');
	$forum_db->drop_field('users', 'warn_edit');
	$forum_db->drop_field('users', 'warn_group_id');
	$query = array(
		'DELETE' => 'groups',
		'WHERE'		=> 'g_id in ('.$forum_config['o_warn_group_sig'].','.$forum_config['o_warn_group_ava'].','.$forum_config['o_warn_group_read'].')',
	);
	$forum_db->query_build($query);
		$query = array(
		'DELETE' => 'config',
		'WHERE'		=> 'conf_name in (\'o_warn_enabled\', \'o_warn_maxmessage\', \'o_warn_group_sig\', \'o_warn_group_ava\', \'o_warn_group_read\')',
	);
	$forum_db->query_build($query);
}
