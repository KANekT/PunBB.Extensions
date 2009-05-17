<?php
/*
 * index file for thanks
 *
 * @copyright Copyright (C) 2009 KANekT @ http://blog.teamrip.ru
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package thanks
*/


// Make sure no one attempts to run this script "directly"
if (!defined('FORUM_ROOT'))	define('FORUM_ROOT', '../../');

require FORUM_ROOT.'include/common.php';
if (file_exists(FORUM_ROOT.'extensions/thanks/lang/'.$forum_user['language'].'.php'))
	require FORUM_ROOT.'extensions/thanks/lang/'.$forum_user['language'].'.php';
else
	require FORUM_ROOT.'extensions/thanks/lang/English.php';
	
$user_id = (isset($_GET['id'])) ? intval($_GET['id']) : '';
if ($forum_user['g_read_board'] == '0')
	message($lang_common['No view']);
if ($user_id < 1)
	message($lang_thanks['error_00']);
	else 
	{
$page_id = (isset($_GET['page'])) ? intval($_GET['page']) : 0;
$page_id = $page_id*50;
// 
$query_thanks = array(
	'SELECT'	=> 't.id, t.post_id, t.thank_date, u.username, t1.subject, t1.id as topic_id, IF(CHAR_LENGTH(p.message)<70, p.message, CONCAT(LEFT(p.message, 70), "...")) as post',
'FROM'		=> 'thanks AS t',
	'JOINS'		=> array(
	array(
		'INNER JOIN'	=> 'users AS u',
		'ON'			=> 't.user_thanked_id=u.id'
		),		
	array(
		'INNER JOIN'	=> 'posts AS p',
		'ON'			=> 't.post_id=p.id'
		),
	array(
		'LEFT JOIN'	=> 'topics AS t1',
		'ON'			=> 't1.id=p.topic_id'
		),
	array(
		'LEFT JOIN'		=> 'forum_perms AS fp',
		'ON'			=> '(fp.forum_id=t1.forum_id AND fp.group_id='.$forum_user['g_id'].')'
		),
	),		
	'WHERE'		=> '(fp.read_forum IS NULL OR fp.read_forum=1) AND t.user_id='.$user_id,
	'LIMIT'		=> $page_id.',50'
);
$result_thanks = $forum_db->query_build($query_thanks) or error(__FILE__, __LINE__);
if ($forum_db->num_rows($result_thanks) > 0)
{
	$UserThanks = '<div class="ct-group"><table>
	<thead><tr>
	<th class="tc0" scope="col" width="25%">'.$lang_thanks['ThanksTopic'].'</th>
	<th class="tc1" scope="col" width="45%">'.$lang_thanks['ThanksPost'].'</th>
	<th class="tc2" scope="col" width="15%">'.$lang_thanks['ThanksAuthor'].'</th>
	<th class="tc3" scope="col" width="20%">'.$lang_thanks['ThanksData'].'</th>
	</tr></thead>
	<tbody>';
while($row = $forum_db->fetch_assoc($result_thanks))
	{
		$row['post'] = preg_replace('#\[(.*?)\](.*?)\[/(.*?)\]#ms','$2',$row['post']);
		$row['post'] = preg_replace('#\[(.*?)\]#ms','',$row['post']);
		$row['post'] = preg_replace('#\[/#ms','',$row['post']);
		$row['post'] = preg_replace('#\[(.*?)\](.*?)#ms','$2',$row['post']);
		$UserThanks .= '<tr>
		<td><a href="viewtopic.php?id='.$row['topic_id'].'">'.$row['subject'].'</a></td>
		<td><a href="viewtopic.php?pid='.$row['post_id'].'#'.$row['post_id'].'">'.$row['post'].'</a></td>
		<td><i>'.$row['username'].'</i></td>
		<td>'.format_time($row['thank_date']).'</td>
		</tr>';
	}
	$UserThanks .= '</tbody></table></div>';
	message($UserThanks, '',$lang_thanks['Thanks']);
}
else
{
	message($lang_thanks['ThanksNo'], '',$lang_thanks['Thanks']);
}
}
?>