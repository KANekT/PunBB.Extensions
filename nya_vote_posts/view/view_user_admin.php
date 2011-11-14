	<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo App::$forum_page['form_action'] ?>">
		<div class="hidden">
			<input type="hidden" name="form_sent" value="1" />
			<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(App::$forum_page['form_action']) ?>" />
		</div>

		<fieldset class="frm-group group<?php echo ++App::$forum_page['group_count'] ?>">
		<div class="ct-group">
	
		<table cellspacing="0">
			<thead>
				<tr>
				<th class="tc1"><?php echo App::$lang['From user'] ?></th>
				<th class="tc3" style="width:20%"><?php echo App::$lang['For topic'] ?></th>
				<th class="tc3" style="width:28%"><?php echo App::$lang['Reason'] ?></th>
				<th class="tc1" style="width:1.5em;text-align:center;">+/-</th>
				<th class="tc3" style="width:4.5em;text-align:center;"><?php echo App::$lang['Date'] ?></th>
				<th class="tc3" style="width:4em;text-align:center;"><?php echo App::$lang['Delete'] ?></th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($records as $cur_vote) : 
			$cur_vote['reason']= parse_message($cur_vote['reason'], 0);
?>
				<tr>					
					<td><?php echo $cur_vote['from_user_name'] ? '<a href="'.forum_link(App::$forum_url['vote_posts_view_user'], $cur_vote['from_user_id']).'">'. forum_htmlencode($cur_vote['from_user_name']).'</a>' :  App::$lang['Profile deleted'] ?></td>
					<td>
<?php 
	if ($cur_vote['read_forum'] == null ||  $cur_vote['read_forum'] == 1)
		echo $cur_vote['subject'] ? '<a href="'.forum_link(App::$forum_url['post'], $cur_vote['post_id']) . '">'.forum_htmlencode($cur_vote['subject']).'</a>' : App::$lang['Removed or deleted'];
	else 
		echo App::$lang['Topic not readable'];
?>
					</td>
					<td>
<?php 
	if ($cur_vote['read_forum'] == null ||  $cur_vote['read_forum'] == 1) {
		echo $cur_vote['reason'];
	}
	else 
		echo App::$lang['Message not readable'];	
?>
					</td>
					<td style="text-align:center;"><?php echo $cur_vote['vote_up']==1 ? '<img src="'.forum_link('extensions/nya_vote_posts').'/img/up_unclicked.png" alt="+" border="0">' : '<img src="'.forum_link('extensions/nya_vote_posts').'/img/down_unclicked.png" alt="-" border="0">'; ?></td>
					<td><?php echo format_time($cur_vote['time']) ?></td>
					<td style="text-align:center;"><input type="checkbox" name="delete_user_id[]" value="<?php echo $cur_vote['id'] ?>"></td>						
				</tr>
<?php endforeach;?>
			</tbody>
		</table>
		</div>
		</fieldset>
		<div class="frm-buttons">
			<p class="postlink conr"><input type="submit" name="del_vote" value="<?php echo App::$lang_common['Delete'] ?>" onclick="return confirm('<?php echo App::$lang['Are you sure']; ?>')" /></p>
		</div>
		</form>

