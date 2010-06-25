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
				'user_id'  => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				),
				'user_thanked_id'  => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				),
				'post_id'  => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				),
				'thank_date' => array(
					'datatype'  => 'INT(10)',
					'allow_null'  => false
				)
			),
			'PRIMARY KEY'  	=> array('id'),
			'INDEXES'		=> array(
				'user_tid'	=> array('user_thanked_id'),
				'po_id'		=> array('post_id')
			)
		);
		$forum_db->create_table('thanks', $schema);
		if (!$forum_db->field_exists('users', 'thanks'))
			$forum_db->add_field('users', 'chat_enable', 'INT(10)', false, 0);
		if (!$forum_db->field_exists('posts', 'thanks'))
			$forum_db->add_field('users', 'chat_enable', 'INT(10)', false, 0);

		$query = array(
		   'INSERT'  => 'conf_name, conf_value',
		   'INTO'    => 'config',
		   'VALUES'  => '\'o_thanks_view\', \'0\'' 
		);
		$forum_db->query_build($query);
}


function uninstall()
{
	global $forum_db;
	
	$forum_db->drop_table('thanks');
	$forum_db->drop_field('users', 'thanks');
	$forum_db->drop_field('posts', 'thanks');
	$query = array(
		'DELETE' => 'config',
		'WHERE'		=> 'conf_name in (\'o_thanks_view\')',
	);
	$forum_db->query_build($query);
}
