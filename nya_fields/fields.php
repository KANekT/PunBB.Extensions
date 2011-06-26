<?php
if (!defined('FORUM'))
	exit;
	
		if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
		require $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
	else
		require $ext_info['path'].'/lang/English.php';

		require $ext_info['path'].'/functions.php';

// Add
if (isset($_POST['add']))
{
	if ($_POST['fields_name'] == '')
		message($lang_fields['Fields name error']);
		
	if ($_POST['fields_desc'] == '')
		message($lang_fields['Fields desc error']);

	$f_vt  = ($_POST['fields_in_vt'] != '')  ? '\''.$forum_db->escape($_POST['fields_in_vt']).'\''  : 'NULL';
	$f_name = ($_POST['fields_name'] != '') ? '\''.$forum_db->escape($_POST['fields_name']).'\'' : 'NULL';
	$f_desc = ($_POST['fields_desc'] != '') ? '\''.$forum_db->escape($_POST['fields_desc']).'\'' : 'NULL';
	$f_url  = ($_POST['fields_url'] != '')  ? '\''.$forum_db->escape($_POST['fields_url']).'\''  : '0';

	$query = array(
		'INSERT'	=> 'fields_name, fields_desc, fields_url, fields_in_vt',
		'INTO'		=> 'fields',
		'VALUES'	=> $f_name.', '.$f_desc.', '.$f_url.', '.$f_vt
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	$forum_db->add_field('users', 'f_'.$_POST['fields_name'], 'VARCHAR(50)', false, NULL);

	generate_fields_cache();

	redirect(forum_link($forum_url['admin_settings_fields']), $lang_fields['Fields added'].' '.$lang_admin_common['Redirect']);
}
// Update
else if (isset($_POST['update']))
{
	$id = intval(key($_POST['update']));

	if ($_POST['fields_name'] == '')
		message($lang_fields['Fields name error']);
		
	if ($_POST['fields_desc'] == '')
		message($lang_fields['Fields desc error']);

	$query_f = array(
		'SELECT'	=> 'f.fields_name',
		'FROM'		=> 'fields AS f',
		'WHERE'		=> 'f.id='.$id
	);

	$result = $forum_db->query_build($query_f) or error(__FILE__, __LINE__);
	$fields = $forum_db->result($result);
		
	$f_name = ($_POST['fields_name'][$id] != '') ? $forum_db->escape($_POST['fields_name'][$id]) : 'NULL';
	$f_desc = ($_POST['fields_desc'][$id] != '') ? $forum_db->escape($_POST['fields_desc'][$id]) : 'NULL';
	$f_url  = ($_POST['fields_url'][$id] != '')  ? $forum_db->escape($_POST['fields_url'][$id])  : 'NULL';
	$f_vt  = ($_POST['fields_in_vt'][$id] != '')  ? $forum_db->escape($_POST['fields_in_vt'][$id])  : '0';

	$query = array(
		'UPDATE'	=> 'fields',
		'SET'		=> 'fields_name=\''.$forum_db->escape($f_name).'\', fields_desc=\''.$forum_db->escape($f_desc).'\', fields_url=\''.$forum_db->escape($f_url).'\', fields_in_vt=\''.$forum_db->escape($f_vt).'\'',
		'WHERE'		=> 'id='.$id
	);

	$forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	if ($fields != $_POST['fields_name'][$id]) change_field('users', $fields, $_POST['fields_name'][$id]);
	
	generate_fields_cache();

	redirect(forum_link($forum_url['admin_settings_fields']), $lang_fields['Fields updated'].' '.$lang_admin_common['Redirect']);

}
// Remove 
else if (isset($_POST['remove']))
{
	$id = intval(key($_POST['remove']));

	$query_f = array(
		'SELECT'	=> 'f.fields_name',
		'FROM'		=> 'fields AS f',
		'WHERE'		=> 'f.id='.$id
	);

	$result = $forum_db->query_build($query_f) or error(__FILE__, __LINE__);
	$fields = $forum_db->result($result);
	
	$forum_db->drop_field('users', 'f_'.$fields);
	
	$query = array(
		'DELETE'	=> 'fields',
		'WHERE'		=> 'id='.$id
	);

	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	generate_fields_cache();

	redirect(forum_link($forum_url['admin_settings_fields']), $fields.' '.$lang_admin_common['Redirect']);
}

	if (file_exists(FORUM_CACHE_DIR.'cache_fields.php')) require FORUM_CACHE_DIR.'cache_fields.php';

// Setup the form
$forum_page['part_count'] = $forum_page['fld_count'] = $forum_page['set_count'] = 0;

// Setup breadcrumbs
$forum_page['crumbs'] = array(
	array($forum_config['o_board_title'], forum_link($forum_url['index'])),
	array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
	array($lang_admin_common['Settings'], forum_link($forum_url['admin_settings_setup'])),
	$lang_fields['Fields']
);

($hook = get_hook('aop_portal_pre_header_load')) ? eval($hook) : null;

	generate_fields_cache();

define('FORUM_PAGE_SECTION', 'settings');
define('FORUM_PAGE', 'admin-settings-fields');
require FORUM_ROOT.'header.php';

ob_start();

// Reset counter
$forum_page['group_count'] = $forum_page['item_count'] = 0;


?>
	<div class="main-content main-frm">
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['admin_settings_fields']) ?>&action=foo">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['admin_settings_fields']).'&action=foo') ?>" />
			</div>
			<div class="ct-box" id="info-ranks-intro">
				<p><?php echo $lang_fields['Fields intro']; ?></p>
			</div>
			<fieldset class="frm-group frm-hdgroup group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_fields['Fields legend'] ?></strong></legend>
				<fieldset class="mf-set set<?php echo ++$forum_page['item_count'] ?><?php echo ($forum_page['item_count'] == 1) ? ' mf-head' : ' mf-extra' ?>">
					<legend><span><?php echo $lang_fields['Add'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-field mf-field1 text">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>">
								<span class="fld-label"><?php echo $lang_fields['Fields name'] ?></span>
							</label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_name" size="15" maxlength="50" value="" /></span>
						</div>
						<div class="mf-field text">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>">
								<span class="fld-label"><?php echo $lang_fields['Fields desc'] ?></span>
							</label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_desc" size="20" maxlength="100" value="" /></span>
						</div>
						<div class="mf-field text">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>">
								<span class="fld-label"><?php echo $lang_fields['Fields url'] ?></span>
							</label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_url" size="25" maxlength="100" value="" /></span>
						</div>
						<div class="mf-field text">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>">
								<span class="fld-label"><?php echo $lang_fields['Fields url'] ?></span>
							</label><br />
							<span class="fld-input"><input type="checkbox" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_in_vt" value="1" /></span>
						</div>
						<div class="mf-field text">
							<span class="submit"><input type="submit" name="add" value="<?php echo $lang_fields['Add'] ?>" /></span>
						</div>
					</div>
				</fieldset>
			</fieldset>
		</form>
			
<?php

	// Reset counter
	$forum_page['group_count'] = $forum_page['item_count'] = 0;

if (!empty($forum_fields))
{
	// Reset fieldset counter
	$forum_page['group_count'] = $forum_page['item_count'] = 0;

?>
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['admin_settings_fields']) ?>&action=foo">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['admin_settings_fields']).'&action=foo') ?>" />
			</div>
			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><span><?php echo $lang_fields['Existing fieldss legend'] ?></span></legend>
<?php

	foreach ($forum_fields as $fields_key => $cur_fields)
	{
	?>
				<fieldset class="mf-set mf-extra set<?php echo ++$forum_page['item_count'] ?>">
					<legend><span><?php echo $lang_fields['Fields list'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-field text mf-field1">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_fields['Fields name'] ?></span></label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_name[<?php echo $cur_fields['id'] ?>]" value="<?php echo forum_htmlencode($cur_fields['fields_name']) ?>" size="15" maxlength="50" /></span>
						</div>
						<div class="mf-field text">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span class="fld-label"><?php echo $lang_fields['Fields url'] ?></span></label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_desc[<?php echo $cur_fields['id'] ?>]" value="<?php echo forum_htmlencode($cur_fields['fields_desc']) ?>" size="20" maxlength="100" /></span>
						</div>
						<div class="mf-field text">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span class="fld-label"><?php echo $lang_fields['Fields url'] ?></span></label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_url[<?php echo $cur_fields['id'] ?>]" value="<?php echo forum_htmlencode($cur_fields['fields_url']) ?>" size="25" maxlength="100" /></span>
						</div>
						<div class="mf-field text">
								<span class="fld-input"><input type="checkbox" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_in_vt[<?php echo $cur_fields['id'] ?>]" value="1" <?php if ($cur_fields['fields_in_vt'] == 1) echo 'checked="checked" ' ?>/></span>
						</div>
						<div class="mf-field text">
							<span class="submit"><input type="submit" name="update[<?php echo $cur_fields['id'] ?>]" value="<?php echo $lang_fields['Update'] ?>" /> <input type="submit" name="remove[<?php echo $cur_fields['id'] ?>]" value="<?php echo $lang_fields['Remove'] ?>" /></span>
						</div>
					</div>
				</fieldset>
<?php
	}

?>
			</fieldset>
		</form>
	</div>
<?php

}
else
{

?>
		<div class="frm-form">
			<div class="ct-box">
				<p><?php echo $lang_fields['Fields no'] ?></p>
			</div>
		</div>
	</div>
<?php

}


