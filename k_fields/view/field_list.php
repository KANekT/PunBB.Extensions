<div class="main-content main-frm">
	<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo App::$forum_page['form_action'] ?>">
		<div class="hidden">
			<input type="hidden" name="form_sent" value="1" />
			<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(App::$forum_page['form_action']) ?>" />
		</div>
		<div class="ct-group">
		<table>
			<thead>
				<tr>
					<th class="tc1"><?php echo App::$lang['Fields name'] ?></th>
					<th class="tc3"><?php echo App::$lang['Fields desc'] ?></th>
					<th class="tc1"><?php echo App::$lang['Fields url'] ?></th>
					<th class="tc2"><?php echo App::$lang['Fields checkbox label'] ?></th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($records as $cur_fields) :
	if ($cur_fields['fields_in_vt'] == 0) $in_vt = App::$lang['Fields no'];
	else $in_vt = App::$lang['Fields yes'];
	if ($cur_fields['fields_url'] == "") $url = App::$lang['Fields url none'];
	else $url = $cur_fields['fields_url'];
	?>
<tr>
	<td><a href="<?php echo forum_link(App::$forum_url['admin_fields_id'], $cur_fields['id']) ?>"><?php echo $cur_fields['fields_name'] ?></a></td>
	<td><?php echo $cur_fields['fields_desc'] ?></td>
	<td><?php echo $url ?></td>
	<td><?php echo $in_vt ?></td>
				</tr>
<?php endforeach;?>
			</tbody>
		</table></div>
		</form>
</div>
