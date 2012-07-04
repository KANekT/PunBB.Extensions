		<fieldset class="frm-group group<?php echo ++App::$forum_page['group_count'] ?>">
		<div class="ct-group">
	
		<table cellspacing="0">
			<thead>
				<tr>
				<th class="tc0"><?php echo App::$lang['From user'] ?></th>
				<th class="tc1"><?php echo App::$lang['For topic'] ?></th>
				<th class="tc2"><?php echo App::$lang['Date'] ?></th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($records as $cur_rep) : 
?>
				<tr>					
					<td class="tc0"><?php echo $cur_rep['from_user_name'] ? '<a href="'.forum_link(App::$forum_url['thanks_view'], $cur_rep['from_user_id']).'">'. forum_htmlencode($cur_rep['from_user_name']).'</a>' :  App::$lang['Profile deleted'] ?></td>
					<td class="tc1">
<?php 
	if ($cur_rep['read_forum'] == null ||  $cur_rep['read_forum'] == 1)
		echo $cur_rep['subject'] ? '<a href="'.forum_link(App::$forum_url['post'], $cur_rep['post_id']) . '">'.forum_htmlencode($cur_rep['subject']).'</a>' : App::$lang['Removed or deleted'];
	else 
		echo App::$lang['Topic not readable'];
?>
					</td>
					<td class="tc2"><?php echo format_time($cur_rep['time']) ?></td>
				</tr>
<?php endforeach;?>
			</tbody>
		</table>
		</div>
		</fieldset>