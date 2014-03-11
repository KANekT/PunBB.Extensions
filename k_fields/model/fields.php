<?php
class K_Fields_Model_Fields
{
	function count_fields()
	{
		$query = array(
			'SELECT'	=> 'count(id)',
			'FROM'		=> 'fields'
		);

		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		list($count) = App::$forum_db->fetch_row($result);

		return $count;
	}
	
	public function get_fields($from, $to)
	{
		$query = array(
			'SELECT'	=> 'f.*',
			'FROM'		=> 'fields AS f',
			'ORDER BY'	=> 'f.id',
			'LIMIT'		=> $from.','.$to
		);	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
	
		$records = array();
		while ($row = App::$forum_db->fetch_assoc($result))
		{
			$records[] = $row;
		}

		return $records;
	}

	public function get_fields_by_uid($uid)
	{
		$query = array(
			'SELECT'	=> '*',
			'FROM'		=> 'fields',
			'WHERE'		=> 'id='.$uid
		);
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		return App::$forum_db->fetch_assoc($result);
	}

	public function set_fields_by_uid($field, $uid)
	{
		$query = array(
			'UPDATE'	=> 'fields',
			'SET'		=> 'fields_name='.$field['name'].', fields_url='.$field['url'].', fields_desc='.$field['desc'].', fields_in_vt='.$field['vt'],
			'WHERE'		=> 'id='.$uid
		);

		App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
	}

	public function add_field($field)
	{
		$query = array(
			'INSERT'	=> 'fields_name, fields_url, fields_in_vt, fields_desc',
			'INTO'		=> 'fields',
			'VALUES'	=> $field['name'].', '.$field['url'].', '.$field['vt'].', '.$field['desc']
		);
		App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
		$insert_id = App::$forum_db->insert_id();

		App::$forum_db->add_field('users', 'f_'.$field['field'], 'VARCHAR(100)', false, NULL);

		return $insert_id;
	}

	public function delete_field($uid)
	{
		$query_f = array(
			'SELECT'	=> 'f.fields_name',
			'FROM'		=> 'fields AS f',
			'WHERE'		=> 'f.id='.$uid
		);

		$result = App::$forum_db->query_build($query_f) or error(__FILE__, __LINE__);
		list($field) = App::$forum_db->fetch_row($result);

		App::$forum_db->drop_field('users', 'f_'.$field);

		$query = array(
			'DELETE'	=> 'fields',
			'WHERE'		=> 'id='.$uid
		);

		App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
	}

	public function change_field($table_name, $field_name, $field_name_new)
	{
		App::$forum_db->query('ALTER TABLE '.App::$forum_db->prefix.$table_name.' CHANGE f_'.$field_name.' f_'.$field_name_new.' VARCHAR(50)') or error(__FILE__, __LINE__);
	}
}