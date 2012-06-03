<?php
/**
 * Pun_store installer
 *
 * @author PunBB team
 * @copyright Copyright (C) 2008-2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package pun_store
 */

defined('FORUM_ROOT') or die('Direct access not allowed');

class Nya_Fields_Module_Installer
{
	static function install()
	{
		$schema = array(
			'FIELDS'  => array(
				'id'  => array(
					'datatype'  => 'SERIAL',
					'allow_null'  => false
				),
				'fields_name'  => array(
					'datatype'  => 'VARCHAR(50)',
					'allow_null'  => false
				),
				'fields_desc'  => array(
					'datatype'  => 'VARCHAR(100)',
					'allow_null'  => true
				),
				'fields_url'  => array(
					'datatype'  => 'VARCHAR(100)',
					'allow_null'  => true
				),
				'fields_in_vt'  => array(
					'datatype'  => 'VARCHAR(1)',
					'allow_null'  => true
				)
			),
			'PRIMARY KEY'  	=> array('id'),
		);
		App::$forum_db->create_table('fields', $schema);
	}

	static function uninstall()
	{
		$query = array(
			'SELECT'	=> 'f.fields_name',
			'FROM'		=> 'fields AS f',
			'ORDER BY'	=> 'f.id'
		);
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		while ($row = App::$forum_db->fetch_assoc($result))
		{
			App::$forum_db->drop_field('users', 'f_'.$row['fields_name']);
		}

		App::$forum_db->drop_table('fields');
	}
}