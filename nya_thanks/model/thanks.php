<?php
/**
 * Thanks model class
 *
 * @copyright (C) 2012 KANekT Based on hcs extension for PunBB (C)
 * @copyright Copyright (C) 2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package thanks
 */
class Nya_Thanks_Model_Thanks
{
	
	public function get_user($user_id)
	{
		$query = array(
			'SELECT'	=> 'u.username, u.thanks',
			'FROM'		=> 'users AS u',
			'WHERE'		=> 'u.id='.$user_id
		);	
		
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);	
		
		return App::$forum_db->fetch_assoc($result);
	}
	
	public function get_by_id($id)
	{
		$query = array(
			'SELECT'	=> 'h.*, u.username',
			'FROM'		=> 'thanks AS h',
			'JOINS'		=> array(
				array(
					'LEFT JOIN'	=> 'users AS u',
					'ON'			=> 'h.from_user_id = u.id'
				),
			),	
			'WHERE'		=> 'h.id='.$id
		);	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		return App::$forum_db->fetch_assoc($result);
	}
	
	
	function count_by_user_id($user_id) 
	{
		$query = array(
			'SELECT'	=> 'count(id)',
			'FROM'		=> 'thanks',
			'WHERE'		=> 'user_id = '.$user_id
		);

		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		list($count) = App::$forum_db->fetch_row($result);

		return $count;
	}	
	
	public function get_info($user_id, $group_id, $from, $to)
	{
		$query = array(
			'SELECT'	=> 'h.id, h.time, h.post_id, h.user_id, t.subject, u.username as from_user_name, u.id as from_user_id, fp.read_forum',
			'FROM'		=> 'thanks AS h',
			'JOINS'		=> array(
				array(
					'LEFT JOIN'		=> 'topics AS t',
					'ON'			=> 't.id=h.topic_id'
				),
				array(
					'LEFT JOIN'		=> 'users AS u',
					'ON'			=> 'h.from_user_id = u.id'
				),
				array(
					'LEFT JOIN'		=> 'forum_perms AS fp',
					'ON'			=> '(fp.forum_id=t.forum_id AND fp.group_id='.$group_id.')'
				)		
			),
			'WHERE'		=> 'h.user_id = '.$user_id,
			'ORDER BY'	=> 'h.time DESC',
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
	
	public function get_post_info($post_id, $user_id, $from_user_id)
	{
		$query = array(
			'SELECT'	=> 'p.poster_id, p.id, p.topic_id, t.subject, u.thanks_enable, u.username, h.time, h.post_id',
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
					'LEFT JOIN'		=> 'thanks as h',
					'ON'			=> 'h.from_user_id ='.$from_user_id .' AND h.user_id = u.id AND h.post_id ='.$post_id
				)
			),
			'WHERE'		=> 'p.id='.$post_id.' AND p.poster_id='. $user_id,
			'ORDER BY'	=>	'h.time DESC',
			'LIMIT'	=> '0, 1',
		);	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		return App::$forum_db->fetch_assoc($result);
	}

	public function add_voice($target, $from_user_id)
	{
		$query = array(
			'INSERT'	=> 'user_id, from_user_id, time, post_id, topic_id',
			'INTO'		=> 'thanks',
			'VALUES'	=> '\''.$target['poster_id'].'\', '.$from_user_id.', '.mktime().', '.$target['id'].', '.$target['topic_id'],
		);	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);		

		$query = array(
			'UPDATE'	=> 'users',
			'SET'		=> 'thanks=thanks+1',
			'WHERE'		=> 'id='.$target['poster_id']
		);
		App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

        $query = array(
            'UPDATE'	=> 'posts',
            'SET'		=> 'thanks=thanks+1',
            'WHERE'		=> 'id='.$target['id']
        );
        App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
    }

	public function delete($id_list)
	{
        $query = array(
            'SELECT'    => 'user_id, post_id',
            'FROM'	    => 'thanks',
            'WHERE'		=> 'id IN('.$id_list.')'
        );

        $result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

        while($cur_thanks = App::$forum_db->fetch_assoc($result))
        {
            $query = array(
                'UPDATE'	=> 'users',
                'SET'		=> 'thanks=thanks-1',
                'WHERE'		=> 'id = '.$cur_thanks['user_id']
            );

            App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

            $query = array(
                'UPDATE'	=> 'posts',
                'SET'		=> 'thanks=thanks-1',
                'WHERE'		=> 'id = '.$cur_thanks['post_id']
            );

            App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
        }

		$query = array(
			'DELETE'	=> 'thanks',
			'WHERE'		=> 'id IN('.$id_list.')'
		);
	
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
    }
}