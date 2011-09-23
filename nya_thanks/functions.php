<?php

/**
 * core thanks
 * 
 * @partially based on code copyright (C) 2009 hcs reputation extension for PunBB (C)
 * @copyright Copyright (C) 2008 PunBB, partially based on code copyright (C) 2008 FluxBB.org
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package thanks
 */

if (!defined('FORUM'))
	die();
function get_count($uid) {
	global $forum_db;

	$query = array(
		'SELECT'	=> 'u.thanks',
		'FROM'		=> 'users AS u',
		'WHERE'		=> 'u.id='.$uid
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	list($count) = $forum_db->fetch_row($result);

	return $count;
}

function thanks_get_page(&$forum_page){
	global $forum_url, $forum_user, $lang_common;
	
	/*check_access();*/

	if (!isset($_GET['uid']))
		message($lang_common['Bad request']);

	$forum_page['uid'] = intval($_GET['uid']);
	if ($forum_page['uid'] < 2)
		message($lang_common['Bad request']);

	return thanks_view($forum_page);

	message($lang_common['Bad request']);
}

function thanks_view(&$forum_page){
	global $forum_config, $lang_common, $forum_url, $lang_thanks, $forum_user, $forum_db;

	// Setup form
	$forum_page['count'] = get_count($forum_page['uid']);
		
	$query = array(
		'SELECT'	=> 'u.username',
		'FROM'		=> 'users AS u',
		'WHERE'		=> 'u.id='.$forum_page['uid']
	);	
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);	
	
	if (!$forum_db->num_rows($result))
		message($lang_common['Bad request']);
		
	$user_rep = $forum_db->fetch_assoc($result);	
	
	$forum_page['heading'] = $lang_thanks['User Thanks']. forum_htmlencode($user_rep['username']);
	
	// Determine the topic offset (based on $_GET['p'])
	$forum_page['num_pages'] = ceil($forum_page['count'] / $forum_user['disp_topics']);
	$forum_page['page'] = (!isset($_GET['p']) || !is_numeric($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $forum_page['num_pages']) ? 1 : $_GET['p'];
	$forum_page['start_from'] = $forum_user['disp_topics'] * ($forum_page['page'] - 1);
	$forum_page['finish_at'] = min(($forum_page['start_from'] + $forum_user['disp_topics']), ($forum_page['count']));

	$forum_page['page_post']['paging'] = '<p class="paging"><span class="pages">'.$lang_common['Pages'].'</span> '.paginate($forum_page['num_pages'], $forum_page['page'], $forum_url['thanks_user'], $lang_common['Paging separator'], array($forum_page['uid'])).'</p>';
	
	// Setup breadcrumbs
	$forum_page['crumbs'][] = array($forum_config['o_board_title'], forum_link($forum_url['index']));
	$forum_page['crumbs'][] = $lang_thanks['Thanks'];	
	
	$query = array(
	'SELECT'	=> 't.id, t.post_id, t.thank_date, t.user_thanked_id, u.username, t1.subject, t1.id as topic_id, IF(CHAR_LENGTH(p.message)<70, p.message, CONCAT(LEFT(p.message, 70), "...")) as post',
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
	'WHERE'		=> '(fp.read_forum IS NULL OR fp.read_forum=1) AND t.user_id='.$forum_page['uid'],
	'LIMIT'		=> $forum_page['start_from'].','.$forum_user['disp_topics']		
	);	
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);	
	
	$records = array();
	while ($row = $forum_db->fetch_assoc($result))
		$records[] = $row;

	$forum_page['list'] = $records;

	return page_render($forum_page);
}

function page_render(&$forum_page){
	global $lang_thanks, $forum_url, $forum_user, $ext_info, $lang_common;
	
	$forum_page['group_count'] = $forum_page['fld_count'] = 0;
	
	ob_start();
?>	

	<div class="main-head">
		<h2 class="hn"><span><?php echo $forum_page['heading'] ?></span></h2>
	</div>
	<div class="main-content main-frm">
<?php if ($forum_page['count'] > 0) : ?>

		<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
		<div class="ct-group">
	
		<table cellspacing="0">
			<thead>
				<tr>
	<th class="tc0" width="25%"><?php echo $lang_thanks['ThanksTopic']; ?></th>
	<th class="tc1" width="45%"><?php echo $lang_thanks['ThanksPost']; ?></th>
	<th class="tc2" width="15%"><?php echo $lang_thanks['ThanksAuthor']; ?></th>
	<th class="tc3" width="20%"><?php echo $lang_thanks['ThanksData']; ?></th>
				</tr>
			<tbody>
<?php foreach ($forum_page['list'] as $cur_th) : 
/*
 * TODO need forum_url replace
 */
		$cur_th['post'] = preg_replace('#\[(.*?)\](.*?)\[/(.*?)\]#ms','$2',$cur_th['post']);
		$cur_th['post'] = preg_replace('#\[(.*?)\]#ms','',$cur_th['post']);
		$cur_th['post'] = preg_replace('#\[/#ms','',$cur_th['post']);
		$cur_th['post'] = preg_replace('#\[(.*?)\](.*?)#ms','$2',$cur_th['post']);

?>
				<tr>					
					<td><?php echo $cur_th['subject'] ? '<a href="viewtopic.php?id=' . $cur_th['topic_id'] . '">'.forum_htmlencode($cur_th['subject']).'</a>' : $lang_thanks['Removed or deleted'] ?></td>
					<td><?php echo $cur_th['post'] ? '<a href="viewtopic.php?pid=' . $cur_th['post_id'] . '#p'. $cur_th['post_id'] . '">'.forum_htmlencode($cur_th['post']).'</a>' : $lang_thanks['Removed or deleted'] ?></td>
					<td><?php echo $cur_th['username'] ? '<a href="'.forum_link($forum_url['thanks_user'], $cur_th['user_thanked_id']).'">'. forum_htmlencode($cur_th['username']).'</a>' :  $lang_thanks['Profile deleted'] ?></td>
					<td><?php echo format_time($cur_th['thank_date']) ?></td>
				</tr>
<?php endforeach;?>
			</tbody>
		</table>
		</div>
		</fieldset>
<?php else : ?>
		<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
			<p><?php echo $lang_thanks['No data'] ?></p>
		</fieldset>
<?php endif; ?>
	</div>
	<div class="main-foot">
		<h2 class="hn"><span><span class="item-info"></span></span></h2>
	</div>

	
<?php 
	$result = ob_get_contents();
	ob_end_clean();

	return $result;
}

