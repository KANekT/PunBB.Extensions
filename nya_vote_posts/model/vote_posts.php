<?php
/**
 * Vote Posts model class
 * 
 * @copyright (C) 2011 KANekT like post extension for PunBB (C)
 * @based on 2011 hcs nya_like extension for PunBB (C)
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Vote Posts
 */
class nya_Vote_Posts_Model_Vote_Posts
{
	
	public function get_user($user_id)
	{
		$query = array(
			'SELECT'	=> 'u.username, u.vote_up AS count_vote_up, u.vote_down AS count_vote_down',
			'FROM'		=> 'users AS u',
			'WHERE'		=> 'u.id='.$user_id
		);	
		
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);	
		
		return App::$forum_db->fetch_assoc($result);
	}
	
	public function get_post($post_id)
	{
		$query = array(
			'SELECT'	=> 'p.vote_up AS count_vote_up, p.vote_down AS count_vote_down',
			'FROM'		=> 'posts AS p',
			'WHERE'		=> 'p.id='.$post_id
		);	
		
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);	
		
		return App::$forum_db->fetch_assoc($result);
	}

	public function get_by_id($id)
	{
		$query = array(
			'SELECT'	=> 'v.*, u.username',
			'FROM'		=> 'vote_posts AS v',
			'JOINS'		=> array(
				array(
					'LEFT JOIN'	=> 'users AS u',
					'ON'			=> 'v.from_user_id = u.id'
				),
			),	
			'WHERE'		=> 'v.id='.$id
		);	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		return App::$forum_db->fetch_assoc($result);
	}
	
	
	function count_by_user_id($user_id) 
	{
		$query = array(
			'SELECT'	=> 'count(id)',
			'FROM'		=> 'vote_posts',
			'WHERE'		=> 'user_id = '.$user_id
		);

		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		list($count) = App::$forum_db->fetch_row($result);

		return $count;
	}	
	
	function count_by_post_id($post_id) 
	{
		$query = array(
			'SELECT'	=> 'count(id)',
			'FROM'		=> 'vote_posts',
			'WHERE'		=> 'post_id = '.$post_id
		);

		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		list($count) = App::$forum_db->fetch_row($result);

		return $count;
	}	

	public function get_view_post($post_id, $group_id, $from, $to)
	{
		$query = array(
			'SELECT'	=> 'v.id, v.time, v.reason, v.post_id, v.vote_up, v.vote_down, v.user_id, t.subject, u.username as from_user_name, u.id as from_user_id, fp.read_forum',
			'FROM'		=> 'vote_posts AS v',
			'JOINS'		=> array(
				array(
					'LEFT JOIN'		=> 'topics AS t',
					'ON'			=> 't.id=v.topic_id'
				),
				array(
					'LEFT JOIN'		=> 'users AS u',
					'ON'			=> 'v.from_user_id = u.id'
				),
				array(
					'LEFT JOIN'		=> 'forum_perms AS fp',
					'ON'			=> '(fp.forum_id=t.forum_id AND fp.group_id='.$group_id.')'
				)		
			),
			'WHERE'		=> 'v.post_id = '.$post_id,
			'ORDER BY'	=> 'v.time DESC',
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
	
	public function get_view_user($user_id, $group_id, $from, $to)
	{
		$query = array(
			'SELECT'	=> 'v.id, v.time, v.reason, v.post_id, v.vote_up, v.vote_down, v.user_id, t.subject, u.username as from_user_name, u.id as from_user_id, fp.read_forum',
			'FROM'		=> 'vote_posts AS v',
			'JOINS'		=> array(
				array(
					'LEFT JOIN'		=> 'topics AS t',
					'ON'			=> 't.id=v.topic_id'
				),
				array(
					'LEFT JOIN'		=> 'users AS u',
					'ON'			=> 'v.from_user_id = u.id'
				),
				array(
					'LEFT JOIN'		=> 'forum_perms AS fp',
					'ON'			=> '(fp.forum_id=t.forum_id AND fp.group_id='.$group_id.')'
				)		
			),
			'WHERE'		=> 'v.user_id = '.$user_id,
			'ORDER BY'	=> 'v.time DESC',
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
	
	public function get_post_info($post_id, $user_id, $from_user_id, $time)
	{
		$query = array(
			'SELECT'	=> 'p.poster_id, p.id, p.topic_id, t.subject, u.vote_enable, u.username, v.time, v.post_id',
			'FROM'		=> 'posts AS p',
			'JOINS'		=> array(
				array(
					'INNER JOIN'	=> 'topics AS t',
					'ON'			=> 'p.topic_id=t.id'
				),
				array(
					'INNER JOIN'	=> 'users AS u',
					'ON'			=> 'p.poster_id = u.id'
				),
				array(
					'LEFT JOIN'		=> 'vote_posts as v',
					'ON'			=> '(v.from_user_id ='.$from_user_id .' AND v.post_id = p.id AND v.post_id ='.$post_id.') OR (v.from_user_id ='.$from_user_id .' AND v.post_id = p.id  AND v.time > '. $time.')'
				)
			),
			'WHERE'		=> 'p.id='.$post_id.' AND p.poster_id='. $user_id,
			'ORDER BY'	=>	'v.time DESC',
			'LIMIT'	=> '0, 1',
		);	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		return App::$forum_db->fetch_assoc($result);
	}

	public function add_vote($target, $message, $from_user_id, $method)
	{
		$query = array(
			'INSERT'	=> 'user_id, from_user_id, time, post_id, reason, topic_id, vote_'. $method,
			'INTO'		=> 'vote_posts',
			'VALUES'		=> $target['poster_id'].', '.$from_user_id.', '.mktime().', '.$target['id'].', \''.App::$forum_db->escape($message).'\', '.$target['topic_id'].', 1',
		);	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		$query = array(
			'UPDATE'	=> 'users',
			'SET'		=> 'vote_'. $method.'='.'vote_'. $method.'+1',
			'WHERE'		=> 'id='.$target['poster_id']
		);
		App::$forum_db->query_build($query) or error(__FILE__, __LINE__);			

		$query = array(
			'UPDATE'	=> 'posts',
			'SET'		=> 'vote_'. $method.'='.'vote_'. $method.'+1',
			'WHERE'		=> 'id='.$target['id']
		);
		App::$forum_db->query_build($query) or error(__FILE__, __LINE__);			
	}
	
	public function delete_user($user_id, $id_list)
	{
		$query = array(
			'DELETE'	=> 'vote_posts',
			'WHERE'		=> 'id IN('.$id_list.')'
		);
	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		$query = array(
			'SELECT'	=> 'SUM(vote_up) AS up, SUM(vote_down) AS down',
			'FROM'		=> 'vote_posts',
			'WHERE'		=> 'user_id = '.$user_id,
			'GROUP BY'	=> 'user_id'
		);
	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
			
		if (FALSE === ($rep = App::$forum_db->fetch_assoc($result)))
		{
			$rep['up'] = 0;
			$rep['down'] = 0;
		}
		
		$query = array(
			'UPDATE'	=> 'users',
			'SET'		=> 'vote_up='.$rep['up'].',vote_down='.$rep['down'],
			'WHERE'		=> 'id = '.$user_id
		);
	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);				
	}
	
	public function delete_post($user_id, $id_list)
	{
		$query = array(
			'DELETE'	=> 'vote_posts',
			'WHERE'		=> 'id IN('.$id_list.')'
		);
	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		$query = array(
			'SELECT'	=> 'SUM(vote_up) AS up, SUM(vote_down) AS down',
			'FROM'		=> 'vote_posts',
			'WHERE'		=> 'user_id = '.$user_id,
			'GROUP BY'	=> 'user_id'
		);
	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
			
		if (FALSE === ($rep = App::$forum_db->fetch_assoc($result)))
		{
			$rep['up'] = 0;
			$rep['down'] = 0;
		}
		
		$query = array(
			'UPDATE'	=> 'users',
			'SET'		=> 'vote_up='.$rep['up'].',vote_down='.$rep['down'],
			'WHERE'		=> 'id = '.$user_id
		);
	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);				
	}
}
