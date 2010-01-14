<?php

/**
 * core who_does
 * 
 * @partially based on code copyright (C) 2009 hcs reputation extension for PunBB (C)
 * @copyright Copyright (C) 2008 PunBB, partially based on code copyright (C) 2008 FluxBB.org
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package who_does
 */

if (!defined('FORUM'))
	die();
function get_count() {
	global $forum_db;

	$query = array(
		'SELECT'	=> 'count(user_id)',
		'FROM'		=> 'online'
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	list($count) = $forum_db->fetch_row($result);

	return $count;
}

function who_does_view(&$forum_page){
	global $forum_config, $lang_common, $forum_url, $lang_who_does, $forum_user, $forum_db;

	// Setup form
	$forum_page['count'] = get_count();
		
	$forum_page['heading'] = '&nbsp;&nbsp;<strong>'. $lang_who_does['Who_Does'] .'&nbsp;</strong>';
	
	// Determine the topic offset (based on $_GET['p'])
	$forum_page['num_pages'] = ceil($forum_page['count'] / $forum_user['disp_topics']);
	$forum_page['page'] = (!isset($_GET['p']) || !is_numeric($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $forum_page['num_pages']) ? 1 : $_GET['p'];
	$forum_page['start_from'] = $forum_user['disp_topics'] * ($forum_page['page'] - 1);
	$forum_page['finish_at'] = min(($forum_page['start_from'] + $forum_user['disp_topics']), ($forum_page['count']));

	$forum_page['page_post']['paging'] = '<p class="paging"><span class="pages">'.$lang_common['Pages'].'</span> '.paginate($forum_page['num_pages'], $forum_page['page'], $forum_url['who_does_view'], $lang_common['Paging separator']).'</p>';
	
	// Setup breadcrumbs
	$forum_page['crumbs'][] = array($forum_config['o_board_title'], forum_link($forum_url['index']));
	$forum_page['crumbs'][] = $lang_who_does['Who_Does'];	
	
	$query = array(
	'SELECT'	=> 'o.user_id, o.ident, o.prev_url',
	'FROM'		=> 'online AS o',
	/*'JOINS'		=> array(
		array(
			'LEFT JOIN'		=> 'topics AS t',
			'ON'			=> 't.id=r.topic_id'
		),
		array(
			'LEFT JOIN'		=> 'users AS u',
			'ON'			=> 'r.from_user_id = u.id'
		)
	),*/
	'ORDER BY'	=> 'o.logged DESC',
	'LIMIT'		=> $forum_page['start_from'].','.$forum_user['disp_posts']		
	);	
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);	
	
	$records = array();
	while ($row = $forum_db->fetch_assoc($result))
		$records[] = $row;

	$forum_page['list'] = $records;

	return page_render($forum_page);
}

function page_render(&$forum_page){
	global $lang_who_does, $forum_url, $forum_user, $ext_info, $lang_common;
	
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
				<th class="tc3" style="width:20%"><?php echo $lang_who_does['User'] ?></th>
				<th class="tc3" style="width:75%"><?php echo $lang_who_does['Who Does'] ?></th>
				</tr>
			<tbody>
<?php foreach ($forum_page['list'] as $current) : 
/*
 * TODO need forum_url replace
 */			
?>
				<tr>					
					<td><?php echo $current['ident'] ? '<a href="'.forum_link($forum_url['user'], $current['user_id']).'">'. forum_htmlencode($current['ident']).'</a>' :  $lang_who_does['Profile deleted'] ?></td>
					<td><?php echo $current['prev_url'] ?></td>
				</tr>
<?php endforeach;?>
			</tbody>
		</table>
		</div>
		</fieldset>
<?php else : ?>
		<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
			<p><?php echo $lang_who_does['No data'] ?></p>
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

