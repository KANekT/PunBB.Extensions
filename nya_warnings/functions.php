<?php

/**
 * core nya_warnings
 * 
 * @partially based on code copyright (C) 2009 hcs reputation extension for PunBB (C)
 * @copyright Copyright (C) 2008 PunBB, partially based on code copyright (C) 2008 FluxBB.org
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package nya_warnings
 */

if (!defined('FORUM'))
	die();
	
function get_count() {
	global $forum_db;
	$uid = intval($_GET['uid']);

	$query = array(
		'SELECT'	=> 'count(uid)',
		'FROM'		=> 'warnings',
		'WHERE'		=> 'uid='.$uid
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	list($count) = $forum_db->fetch_row($result);

	return $count;
}
function mi_get_page(&$forum_page){
	global $forum_url, $forum_user, $forum_config, $lang_common;
	
	if ($forum_config['o_warn_enabled'] == 0)
		message($lang_warn['Disabled']);
	
	if (!isset($_GET['uid']))
		message($lang_common['Bad request']);

	$forum_page['uid'] = intval($_GET['uid']);
	if ($forum_page['uid'] < 2)
		message($lang_common['Bad request']);
		
	if (isset($_POST['form_sent'])){
		
		if ($forum_user['is_guest'])
			message($lang_common['No permission']);		
		
		if (!isset($_GET['method']) || ($_GET['method']!='plus' && $_GET['method']!='minus'))
			message($lang_common['Bad request']);
			
		return warn_add($forum_page);
	}	
	if (isset($_GET['pid'])) {
		return mi_view_add($forum_page);
	}
	else {
		return mi_view_user($forum_page);
	}
	message($lang_common['Bad request']);
}
function warn_add(&$forum_page){
	global $forum_config, $forum_db, $forum_user, $forum_url, $lang_warn;
	
	$pid = isset($_GET['pid']) ? intval($_GET['pid']) : message($lang_common['Bad request']);
	
	$expiries = isset($_POST['expiries']) ? intval($_POST['expiries']) : message($lang_common['Bad request']);
	
	if (!isset($_POST['csrf_token']) && (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== generate_form_token(($_GET['method']=='plus' ? forum_link($forum_url['warn_plus'], array($pid, $forum_page['uid'])) : forum_link($forum_url['warn_minus'], array($pid, $forum_page['uid']))))))
		csrf_confirm_form();

	($_GET['method'] == 'plus') ? $method=1 : $method=0;
			
	$query = array(
		'SELECT'	=> 'expire, method',
		'FROM'		=> 'warnings',
		'WHERE'		=> 'pid='.$pid.' AND from_uid='.$forum_user['id']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	$expire_old = 0;
	$cnt = ''; //счетчик предупреждений
	$group = '';//Изменение группы
	while ($cur_warn = $forum_db->fetch_assoc($result))
	{
		if($cur_warn['method'] == $method) $expire_old=$cur_warn['expire'];
		if($cur_warn['method'] == 1) $expire_plus=$cur_warn['expire'];
		if($cur_warn['method'] == 0) $expire_minus=$cur_warn['expire'];
	}
	//Сделать: Нельзя чтобы уменьшалось на более чем наказание.

	$message = forum_linebreaks(forum_trim($_POST['req_message']));
	
	/// вычисляем срок окончания действия
	$now = time();
	$expire = $expiries * 86400;
	$difference = $expiries * 86400 - $expire_old;
	
	if ($message == '')
		$forum_page['errors'][] = ($lang_warn['No message']);
	else if (strlen($message) > $forum_config['o_warn_maxmessage'])
		$forum_page['errors'][] = sprintf($lang_warn['Too long message'],$forum_config['o_warn_maxmessage']);

	$query = array(
		'SELECT'	=> 'p.topic_id, u.warn_expiries, u.group_id, u.warn_group_id',
		'FROM'		=> 'posts AS p',
		'JOINS'		=> array(
			array(
				'INNER JOIN'	=> 'users AS u',
				'ON'			=> 'p.poster_id=u.id'
			)
		),
		'WHERE'		=> 'p.id='.$pid.' AND p.poster_id='.$forum_page['uid'],
		'LIMIT'		=> '0, 1'
	);	
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	if (!$forum_db->num_rows($result))
		message($lang_common['Bad request']);

	$target = $forum_db->fetch_assoc($result);

	if ($method == 0 && $expire>=$expire_plus) {$difference=$expire_plus; $expire=$expire_plus; $group = ', warn_group_id='.$target['warn_group_id'].', group_id='.$target['warn_group_id'];}

	if (empty($forum_page['errors'])) {
		//Add voice
		if ($expire_old == 0)
		{
			if ($target['warn_expiries'] == 0) $difference+=$now;
			$query = array(
				'INSERT'	=> 'uid, from_uid, reason, time, tid, pid, expire, method',
				'INTO'		=> 'warnings',
				'VALUES'	=> '\''.$forum_page['uid'].'\', '.$forum_user['id'].', \''.$forum_db->escape($message).'\', '.$now.', '.$target['topic_id'].', '.$pid.', '.$expire.', '.$method);
			if($method == 1) $cnt='warn_count=warn_count+1, ';
			
			$group = ', warn_group_id='.$target['group_id'].', group_id='.$forum_config['o_warn_group'];
		}
		else
		{
			$query = array(
				'UPDATE'	=> 'warnings',
				'SET'		=> 'reason=\''.$forum_db->escape($message).'\', expire='.$expire,
				'WHERE'		=> 'pid='.$pid.' AND from_uid='.$forum_user['id'].' AND method='.$method
			);
			//if ($method == 1 && isset($expire_minus)){ if ($expire>$expire_minus) {$group = ', warn_group_id='.$target['group_id'].', group_id='.$forum_config['o_warn_group'];}}
		}
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);	

		$method == 1 ? $expiries='warn_expiries=warn_expiries+'.$difference : $expiries='warn_expiries=warn_expiries-'.$difference;
		$method == 1 ? $lang_warn_red=$lang_warn['Redirect Message warning add'] : $lang_warn_red=$lang_warn['Redirect Message warning minus'];
		
		$query = array(
			'UPDATE'	=> 'users',
			'SET'		=> $cnt.$expiries.$group,
			'WHERE'		=> 'id='.$forum_page['uid']
		);
		$forum_db->query_build($query) or error(__FILE__, __LINE__);		
		
		redirect(forum_link($forum_url['post'], $pid), $lang_warn_red);	
	}	
	else {
		$forum_page['req_message'] = $message;
		return mi_view_add($forum_page);
	}		
}
function mi_view_add(&$forum_page){
	global $lang_warn, $forum_url, $base_url, $forum_config, $forum_user;
	
	if ($forum_user['is_guest'])
		message($lang_common['No permission']);

	if ($forum_user['id'] == $forum_page['uid'])
    	message($lang_warn['Silly user']);

	$pid = isset($_GET['pid']) ? intval($_GET['pid']) : message($lang_common['Bad request']);	
		
	if($_GET['method'] == 'plus')
		$forum_page['form_action'] = forum_link($forum_url['warn_plus'], array($pid, $forum_page['uid']));	
	else
		$forum_page['form_action'] = forum_link($forum_url['warn_minus'], array($pid, $forum_page['uid']));
	
	$forum_page['form_attributes'] = array();
	
	$forum_page['hidden_fields'] = array(
		'form_sent'		=> '<input type="hidden" name="form_sent" value="1" />',
		'csrf_token'	=> '<input type="hidden" name="csrf_token" value="'.generate_form_token($forum_page['form_action']).'" />'
	);	
	
	$forum_page['heading'] = sprintf(($_GET['method']=='plus') ? $lang_warn['Plus'] :  $lang_warn['Minus'],forum_htmlencode($forum_user['id']));
	
	// Setup breadcrumbs
	$forum_page['crumbs'][] = array($forum_config['o_board_title'], forum_link($forum_url['index']));
	$forum_page['crumbs'][] = $lang_warn['Punishments mod'];
	
	return form_render($forum_page);
}
function mi_view_user(&$forum_page){
	global $forum_config, $lang_common, $forum_url, $lang_warn, $forum_user, $forum_db;
	$uid = intval($_GET['uid']);

	// Setup form
	$forum_page['count'] = get_count();
		
	$forum_page['heading'] = '&nbsp;&nbsp;<strong>'. $lang_warn['Punishments mod'] .'&nbsp;</strong>';
	
	// Determine the topic offset (based on $_GET['p'])
	$forum_page['num_pages'] = ceil($forum_page['count'] / $forum_user['disp_topics']);
	$forum_page['page'] = (!isset($_GET['p']) || !is_numeric($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $forum_page['num_pages']) ? 1 : $_GET['p'];
	$forum_page['start_from'] = $forum_user['disp_topics'] * ($forum_page['page'] - 1);
	$forum_page['finish_at'] = min(($forum_page['start_from'] + $forum_user['disp_topics']), ($forum_page['count']));

	$forum_page['page_post']['paging'] = '<p class="paging"><span class="pages">'.$lang_common['Pages'].'</span> '.paginate($forum_page['num_pages'], $forum_page['page'], $forum_url['warn_view'], $lang_common['Paging separator']).'</p>';
	
	// Setup breadcrumbs
	$forum_page['crumbs'][] = array($forum_config['o_board_title'], forum_link($forum_url['index']));
	$forum_page['crumbs'][] = $lang_warn['Punishments mod'];	
	
	$query = array(
	'SELECT'	=> 'w.from_uid, w.reason, w.method, w.expire, w.pid, w.tid, w.time, u.username, t.subject',
	'FROM'		=> 'warnings AS w',
	'JOINS'		=> array(
		array(
			'LEFT JOIN'		=> 'topics AS t',
			'ON'			=> 't.id=w.tid'
		),
		array(
			'LEFT JOIN'		=> 'users AS u',
			'ON'			=> 'w.from_uid = u.id'
		)
	),
	'WHERE'		=> 'uid='.$uid,
	'ORDER BY'	=> 'w.time DESC',
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
	global $lang_warn, $forum_url, $base_url;
	
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
				<th class="tc3" style="width:30%"><?php echo $lang_warn['From user'] ?></th>
				<th class="tc3" style="width:70%"><?php echo $lang_warn['Reason'] ?></th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($forum_page['list'] as $current) : 
?>
				<tr>					
					<td><?php echo $current['from_uid'] ? '<a href="'.forum_link($forum_url['user'], $current['from_uid']).'">'. forum_htmlencode($current['username']).'</a>' :  $lang_warn['Profile deleted'] ?></td>
					<td><?php echo $lang_warn['Date']; echo format_time($current['time']); ?></td>
				</tr>
				<tr>					
					<td><?php echo $current['method']==1 ? $lang_warn['Plus'] :  $lang_warn['Minus'] ?></td>
					<td><div class="entry-content"><p><?php echo forum_htmlencode($current['reason']); ?><br/>
					<?php echo $lang_warn['For topic']; echo forum_htmlencode($current['subject']) ? '<a class="permalink" href="'.forum_link($forum_url['post'], $current['pid']).'" rel="bookmark">'.forum_htmlencode($current['subject']).'</a>' :  $lang_warn['Profile deleted'] ?>
					</p></div></td>
				</tr>
<?php endforeach;?>
			</tbody>
		</table>
		</div>
		</fieldset>
<?php else : ?>
		<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
			<p><?php echo $lang_warn['No warnings'] ?></p>
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
function form_render(&$forum_page){
	global $lang_warn, $forum_url, $forum_user, $forum_db, $base_url, $lang_common;

	($_GET['method'] == 'plus') ? $method=1 : $method=0;
	$pid = isset($_GET['pid']) ? intval($_GET['pid']) : message($lang_common['Bad request']);
	
	$query = array(
		'SELECT'	=> 'reason, expire',
		'FROM'		=> 'warnings',
		'WHERE'		=> 'pid='.$pid.' AND from_uid='.$forum_user['id'].' AND method='.$method
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		
	if ($forum_db->num_rows($result)):
			$warn = $forum_db->fetch_assoc($result);
			$expire = $warn['expire']/86400;
	else:
	//if($method==0) message($lang_warn['Minimum warnings']);
	$warn['reason'] = '';
	$expire = 1;
	endif;
	
	$forum_page['group_count'] = $forum_page['item_count'] = $forum_page['fld_count'] = 0;
	
	ob_start();
?>	
	<div class="main-head">
		<h2 class="hn"><span><?php echo $forum_page['heading'] ?></span></h2>
	</div>
	<div class="main-content main-frm">
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo $forum_page['form_action'] ?>"<?php if (!empty($forum_page['form_attributes'])) echo ' '.implode(' ', $forum_page['form_attributes']) ?>>
			<div class="hidden">
				<?php echo implode("\n\t\t\t\t", $forum_page['hidden_fields'])."\n" ?>
			</div>
<?php 
	// If there were any errors, show them
	if (isset($forum_page['errors'] )) :
		$errors = array();
		foreach ($forum_page['errors'] as $cur_error)
			$errors[] = '<li class="warn"><span>'.$cur_error.'</span></li>';
?>
			<div class="ct-box error-box">
				<h2 class="warn hn"><span><?php echo $lang_warn['Errors'] ?></span></h2>
				<ul class="error-list">
					<?php echo implode("\n\t\t\t\t", $errors)."\n" ?>
				</ul>
			</div>
<?php endif; ?>	
			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
					<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
						<div class="sf-box select">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>">
								<span><?php echo $lang_warn['Expiries'] ?></span>
							</label><br />
							<span class="fld-input"><select id="fld<?php echo $forum_page['fld_count'] ?>" name="expiries">
							<optgroup label="<?php echo $lang_warn['Expiries'] ?>">
							<option value="1" <?php if($expire==1) echo 'selected'; ?> >1 <?php echo $lang_warn['Day'] ?></option>
							<option value="3" <?php if($expire==3) echo 'selected'; ?> >3 <?php echo $lang_warn['3 Days'] ?></option>
							<option value="7" <?php if($expire==7) echo 'selected'; ?> >1 <?php echo $lang_warn['Week'] ?></option>
							<option value="10" <?php if($expire==10) echo 'selected'; ?> >10 <?php echo $lang_warn['Days'] ?></option>
							<option value="14" <?php if($expire==14) echo 'selected'; ?> >2 <?php echo $lang_warn['Weeks'] ?></option>
							<option value="21" <?php if($expire==21) echo 'selected'; ?> >3 <?php echo $lang_warn['Weeks'] ?></option>
							<option value="30" <?php if($expire==30) echo 'selected'; ?> >1 <?php echo $lang_warn['Month'] ?></option>
							<option value="60" <?php if($expire==60) echo 'selected'; ?> >2 <?php echo $lang_warn['Months'] ?></option>
							<option value="90" <?php if($expire==90) echo 'selected'; ?> >3 <?php echo $lang_warn['Months'] ?></option>
							</select></span>
						</div>
					</div>
			<div class="txt-set set<?php echo ++$forum_page['item_count'] ?>">
				<div class="txt-box textarea">
					<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_warn['Form reason'] ?></span></label><br />
					<div class="txt-input"><span class="fld-input"><textarea cols='60' rows='10' wrap='soft' name="req_message" class='textinput'><?php echo (!empty($forum_page['errors'])) ? $forum_page['req_message'] : null; echo $warn['reason']; ?></textarea></span></div>
				</div>
			</div>
			</fieldset>
			<div class="frm-buttons">
				<p class="postlink conr"><input type="submit" name="submit" value="<?php echo $lang_common['Submit'] ?>"  /> <?php echo $lang_warn['Punishments description'] ?></p>
			</div>
		</form>
	</div>
<?php 
	$result = ob_get_contents();
	ob_end_clean();

	return $result;
}

