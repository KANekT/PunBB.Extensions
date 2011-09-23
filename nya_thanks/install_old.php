<?php
/***********************************************************************

	FluxBB extension
	Portal
	Daris <daris91@gmail.com>

************************************************************************/


// Make sure no one attempts to run this script "directly"
if (!defined('FORUM'))
	exit;

function install()
{
	global $forum_db;
	
	if (defined('EXT_CUR_VERSION'))
	{
		$k = substr(EXT_CUR_VERSION,0,1);
		$v = substr(EXT_CUR_VERSION,0,3);
	}
	if (defined('EXT_CUR_VERSION') && ($k == 'k' || $v == '0.1'))
	{
		$forum_db->add_field('users', 'thanks', 'INT(10)', false, 0);
		$forum_db->add_field('posts', 'thanks', 'INT(10)', false, 0);
		$query = array(
			'SELECT'	=> 'id',
			'FROM'		=> 'users',
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		
		while($row = $forum_db->fetch_assoc($result))
		{
			if (file_exists($ext_info['path'].'/cache/thanks_cache_'.$row['id'].'.php'))
			{
				include($ext_info['path'].'/cache/thanks_cache_'.$row['id'].'.php');
				$queryU = array(
					'UPDATE'	=> 'users',
					'SET'		=> 'thanks='.$thanks_cache,
					'WHERE'		=> 'id='.$row['id']
				);
				$forum_db->query_build($queryU);
			}
		}
		$query = array(
			'SELECT'	=> 'post_id, id',
			'FROM'		=> 'thanks',
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		while($row = $forum_db->fetch_assoc($result))
		{
			$queryU = array(
				'UPDATE'	=> 'posts',
				'SET'		=> 'thanks=thanks+1',
				'WHERE'		=> 'id='.$row['post_id']
			);
			$forum_db->query_build($queryU);
		}
				
		unlink($ext_info['path'].'/cache.php');
		if ($k != '0') unlink($ext_info['path'].'/index.php');
		function full_del_dir($directory)
		{
			$dir = opendir($directory);
			while(($file = readdir($dir)))
			{
				if (is_file($directory.'/'.$file))
			    {
					unlink ($directory.'/'.$file);
			    }
			    else if (is_dir($directory.'/'.$file) && ($file != '.') && ($file != '..'))
			    {
					full_del_dir($directory.'/'.$file);  
			    }
			}
			closedir ($dir);
			rmdir ($directory);
		}
		full_del_dir($ext_info['path'].'/include/');
		full_del_dir($ext_info['path'].'/cache/');
		if (!isset($forum_config['o_thanks_view']))
		{
			$query = array(
			   'INSERT'  => 'conf_name, conf_value',
			   'INTO'    => 'config',
			   'VALUES'  => '\'o_thanks_view\', \'0\'' 
			);
			$forum_db->query_build($query);
		}
		$forum_db->add_index('thanks', 'user_tid', array('user_thanked_id'), false, false);
		$forum_db->add_index('thanks', 'po_id', array('post_id'), false, false);
	}
	else if (defined('EXT_CUR_VERSION') && $v == '0.2')
	{
		$result = $forum_db->query('ALTER TABLE '.$forum_db->prefix.'users CHANGE thanks thanks INT(10) DEFAULT \'0\'');
		$query = array(
		   'INSERT'  => 'conf_name, conf_value',
		   'INTO'    => 'config',
		   'VALUES'  => '\'o_thanks_view\', \'0\'' 
		);
		$forum_db->query_build($query);
		$forum_db->add_index('thanks', 'user_tid', array('user_thanked_id'), false, false);
		$forum_db->add_index('thanks', 'po_id', array('post_id'), false, false);
	}
	else if (defined('EXT_CUR_VERSION') && $v == '0.3')
	{
		$query = array(
		   'INSERT'  => 'conf_name, conf_value',
		   'INTO'    => 'config',
		   'VALUES'  => '\'o_thanks_view\', \'0\'' 
		);
		$forum_db->query_build($query);
		$forum_db->add_index('thanks', 'user_tid', array('user_thanked_id'), false, false);
		$forum_db->add_index('thanks', 'po_id', array('post_id'), false, false);
	}
	else if (defined('EXT_CUR_VERSION') && $v == '0.4')
	{
		$forum_db->add_index('thanks', 'user_tid', array('user_thanked_id'), false, false);
		$forum_db->add_index('thanks', 'po_id', array('post_id'), false, false);
	}
	else
	{ // it's a fresh install
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
