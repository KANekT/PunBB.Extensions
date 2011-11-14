		<fieldset class="frm-group group<?php echo ++App::$forum_page['group_count'] ?>">
		<div class="ct-group">
	
		<table cellspacing="0">
			<thead>
				<tr>
				<th class="tc3"><?php echo App::$lang['From user'] ?></th>
				<th class="tc3"><?php echo App::$lang['For topic'] ?></th>
				<th class="tc3"  style="width:35%"><?php echo App::$lang['Reason'] ?></th>
				<th class="tc3" style="text-align:center;"><?php echo App::$lang['Estimation'] ?></th>
				<th class="tc3"><?php echo App::$lang['Date'] ?></th>
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
				</tr>
<?php endforeach;?>
			</tbody>
		</table>
		</div>
		</fieldset>
