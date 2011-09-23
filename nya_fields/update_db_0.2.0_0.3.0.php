<?php
if (!defined('FORUM_ROOT'))	define('FORUM_ROOT', '../../');
if (!defined('FORUM'))	define('FORUM', 1);
require FORUM_ROOT.'include/common.php';

		$schema_lang = array(
			'FIELDS'  => array(
				'fields_id'  => array(
					'datatype'  => 'VARCHAR(10)',
					'allow_null'  => false
				),
				'fields_lang'  => array(
					'datatype'  => 'VARCHAR(50)',
					'allow_null'  => false
				),
				'fields_value'  => array(
					'datatype'  => 'VARCHAR(100)',
					'allow_null'  => true
				)
			)
		);
		$forum_db->create_table('fields_lang', $schema_lang);

	$query = array(
		'SELECT'	=> 'f.fields_desc, f.id',
		'FROM'		=> 'fields AS f',
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	while ($cur_rank = $forum_db->fetch_assoc($result))
	{
		$query_lang = array(
			'INSERT'	=> 'fields_id, fields_lang, fields_value',
			'INTO'		=> 'fields_lang',
			'VALUES'	=> $cur_rank['id'].', \''.$forum_config['o_default_lang'].'\', \''.$cur_rank['fields_desc'].'\''
		);
		$forum_db->query_build($query_lang) or error(__FILE__, __LINE__);
	}

$forum_db->drop_field('fields', 'fields_desc');

echo "Update Complete";
