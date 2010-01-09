<?php
/*
 * view user file for thanks
 *
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package thanks
*/


// Make sure no one attempts to run this script "directly"
if (!defined('FORUM_ROOT'))	define('FORUM_ROOT', '../../');

require FORUM_ROOT.'include/common.php';
if ($forum_user['g_read_board'] == '0')
	message($lang_common['No view']);
else if ($forum_user['g_view_users'] == '0')
	message($lang_common['No permission']);

if (file_exists(FORUM_ROOT.'extensions/thanks/lang/'.$forum_user['language'].'.php'))
	require FORUM_ROOT.'extensions/thanks/lang/'.$forum_user['language'].'.php';
else
	require FORUM_ROOT.'extensions/thanks/lang/English.php';

// Miscellaneous setup
$forum_page['show_post_count'] = ($forum_config['o_show_post_count'] == '1' || $forum_user['is_admmod']) ? true : false;
$forum_page['username'] = (isset($_GET['username']) && $_GET['username'] != '-' && $forum_user['g_search_users'] == '1') ? $_GET['username'] : '';
$forum_page['show_group'] = (!isset($_GET['show_group']) || intval($_GET['show_group']) < -1 && intval($_GET['show_group']) > 2) ? -1 : intval($_GET['show_group']);
$forum_page['sort_by'] = (!isset($_GET['sort_by']) || $_GET['sort_by'] != 'username' && $_GET['sort_by'] != 'registered' && ($_GET['sort_by'] != 'num_posts' || !$forum_page['show_post_count'])) ? 'username' : $_GET['sort_by'];
$forum_page['sort_dir'] = (!isset($_GET['sort_dir']) || strtoupper($_GET['sort_dir']) != 'ASC' && strtoupper($_GET['sort_dir']) != 'DESC') ? 'ASC' : strtoupper($_GET['sort_dir']);
	
$user_id = (isset($_GET['id'])) ? intval($_GET['id']) : '';
if ($user_id < 1)
	message($lang_thanks['error_00']);
	else 
	{

// Fetch thanks count
$queryCount = array(
	'SELECT'	=> 'u.thanks',
	'FROM'		=> 'users AS u',
	'WHERE'		=> 'u.id='.$user_id
);
$resultCount = $forum_db->query_build($queryCount) or error(__FILE__, __LINE__);
$forum_page['num_users'] = $forum_db->result($resultCount);

// Determine the user offset (based on $_GET['p'])
$forum_page['num_pages'] = ceil($forum_page['num_users'] / 50);
$forum_page['page'] = (!isset($_GET['p']) || !is_numeric($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $forum_page['num_pages']) ? 1 : intval($_GET['p']);
$forum_page['start_from'] = 50 * ($forum_page['page'] - 1);
$forum_page['finish_at'] = min(($forum_page['start_from'] + 50), ($forum_page['num_users']));

if ($forum_page['num_users'] > 0)
	$forum_page['items_info'] = generate_items_info( ($lang_thanks['Thanks']), ($forum_page['start_from'] + 1), $forum_page['num_users']);
else
	$forum_page['items_info'] = $lang_thanks['Thanks'];

// Generate paging links
$forum_page['page_post']['paging'] = '<p class="paging"><span class="pages">'.$lang_common['Pages'].'</span> '.paginate($forum_page['num_pages'], $forum_page['page'], $forum_url['thanks_user'], $lang_common['Paging separator'], $user_id).'</p>';

// Navigation links for header and page numbering for title/meta description
if ($forum_page['page'] < $forum_page['num_pages'])
{
	$forum_page['nav']['last'] = '<link rel="last" href="'.forum_sublink($forum_url['thanks_user'], $forum_url['page'], $forum_page['num_pages'], $user_id).'" title="'.$lang_common['Page'].' '.$forum_page['num_pages'].'" />';
	$forum_page['nav']['next'] = '<link rel="next" href="'.forum_sublink($forum_url['thanks_user'], $forum_url['page'], ($forum_page['page'] + 1), $user_id).'" title="'.$lang_common['Page'].' '.($forum_page['page'] + 1).'" />';
}
if ($forum_page['page'] > 1)
{
	$forum_page['nav']['prev'] = '<link rel="prev" href="'.forum_sublink($forum_url['thanks_user'], $forum_url['page'], ($forum_page['page'] - 1), $user_id).'" title="'.$lang_common['Page'].' '.($forum_page['page'] - 1).'" />';
	$forum_page['nav']['first'] = '<link rel="first" href="'.forum_link($forum_url['thanks_user'], $user_id).'" title="'.$lang_common['Page'].' 1" />';
}

define('FORUM_ALLOW_INDEX', 1);

define('FORUM_PAGE', 'userthanks');
require FORUM_ROOT.'header.php';

// START SUBST - <!-- forum_main -->
ob_start();
?>
	<div class="main-head">
<?php

	if (!empty($forum_page['main_head_options']))
		echo "\t\t".'<p class="options">'.implode(' ', $forum_page['main_head_options']).'</p>'."\n";

?>
		<h2 class="hn"><span><?php echo $forum_page['items_info'] ?></span></h2>
	</div>
<?

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
	'LIMIT'		=> $forum_page['start_from'].',50'
);
$result_thanks = $forum_db->query_build($query_thanks) or error(__FILE__, __LINE__);
if ($forum_db->num_rows($result_thanks) > 0)
{
	$UserThanks = '<div class="ct-group main-content"><table>
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
		<td><a href="'.$base_url.'/viewtopic.php?id='.$row['topic_id'].'">'.$row['subject'].'</a></td>
		<td><a href="'.$base_url.'/viewtopic.php?pid='.$row['post_id'].'#p'.$row['post_id'].'">'.$row['post'].'</a></td>
		<td><i>'.$row['username'].'</i></td>
		<td>'.format_time($row['thank_date']).'</td>
		</tr>';
	}
	$UserThanks .= '</tbody></table></div>';
	echo $UserThanks;
	//message($UserThanks, '',$lang_thanks['Thanks']);
}
else
{
	//message($lang_thanks['ThanksNo'], '',$lang_thanks['Thanks']);
}
$tpl_temp = forum_trim(ob_get_contents());
$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <!-- forum_main -->

require FORUM_ROOT.'footer.php';

}
?>