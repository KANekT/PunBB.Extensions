<?php
if (!defined('FORUM'))
	exit;
	
		if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
		require $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
	else
		require $ext_info['path'].'/lang/English.php';

		require $ext_info['path'].'/functions.php';
global $forum_config;
// Add
if (isset($_POST['add']))
{
	if ($_POST['fields_name'] == '')
		message($lang_fields['Fields name error']);
		
	if ($_POST['fields_desc'] == '')
		message($lang_fields['Fields desc error']);
		
	if (!isset($form['fields_in_vt']) || $form['fields_in_vt'] != '1')
				$f_vt = '0';
	else $f_vt = '1';

	$f_name = ($_POST['fields_name'] != '') ? '\''.$forum_db->escape($_POST['fields_name']).'\'' : NULL;
	$f_desc = ($_POST['fields_desc'] != '') ? '\''.$forum_db->escape($_POST['fields_desc']).'\'' : NULL;
	$f_url  = ($_POST['fields_url'] != '')  ? '\''.$forum_db->escape($_POST['fields_url']).'\''  : '';
	$f_lang = '\''.$forum_config['o_default_lang'].'\'';
	$query = array(
		'INSERT'	=> 'fields_name, fields_url, fields_in_vt',
		'INTO'		=> 'fields',
		'VALUES'	=> $f_name.', '.$f_url.', '.$f_vt
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
	$insert_id = $forum_db->insert_id();
	$query_lang = array(
		'INSERT'	=> 'fields_id, fields_lang, fields_value',
		'INTO'		=> 'fields_lang',
		'VALUES'	=> $insert_id.', '.$f_lang.', '.$f_desc
	);
	$forum_db->query_build($query_lang) or error(__FILE__, __LINE__);

	$forum_db->add_field('users', 'f_'.$_POST['fields_name'], 'VARCHAR(50)', false, NULL);

	generate_fields_cache();

	redirect(forum_link($forum_url['admin_settings_fields_id'], $insert_id), $lang_fields['Fields added'].' '.$lang_admin_common['Redirect']);
}
elseif (isset($_GET['id'])) {
$fields_id = intval($_GET['id']);
// Update
if (isset($_POST['update']))
{
	if ($_POST['fields_name'] == '')
		message($lang_fields['Fields name error']);
		
	$query_f = array(
		'SELECT'	=> 'f.fields_name',
		'FROM'		=> 'fields AS f',
		'WHERE'		=> 'f.id='.$fields_id
	);

	$result = $forum_db->query_build($query_f) or error(__FILE__, __LINE__);
	$fields = $forum_db->result($result);
		
	$f_name = ($_POST['fields_name'] != '') ? $forum_db->escape($_POST['fields_name']) : NULL;
	$f_url  = ($_POST['fields_url'] != '')  ? $forum_db->escape($_POST['fields_url'])  : '';
	if (!isset($_POST['fields_in_vt']))	$f_vt = '0';
	else $f_vt = '1';

	$query = array(
		'UPDATE'	=> 'fields',
		'SET'		=> 'fields_name=\''.$forum_db->escape($f_name).'\', fields_url=\''.$forum_db->escape($f_url).'\', fields_in_vt=\''.$forum_db->escape($f_vt).'\'',
		'WHERE'		=> 'id='.$fields_id
	);

	$forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	if ($fields != $_POST['fields_name']) change_field('users', $fields, $_POST['fields_name']);
	
	generate_fields_cache();

	redirect(forum_link($forum_url['admin_settings_fields_id'], $fields_id), $lang_fields['Fields updated'].' '.$lang_admin_common['Redirect']);

}
// Remove 
if (isset($_GET['remove']))
{
	$query_f = array(
		'SELECT'	=> 'f.fields_name',
		'FROM'		=> 'fields AS f',
		'WHERE'		=> 'f.id='.$fields_id
	);

	$result = $forum_db->query_build($query_f) or error(__FILE__, __LINE__);
	$fields = $forum_db->result($result);
	
	$forum_db->drop_field('users', 'f_'.$fields);
	
	$query = array(
		'DELETE'	=> 'fields',
		'WHERE'		=> 'id='.$fields_id
	);

	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	$query = array(
		'DELETE'	=> 'fields_lang',
		'WHERE'		=> 'fields_id='.$fields_id
	);

	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	generate_fields_cache();

	redirect(forum_link($forum_url['admin_settings_fields']), $fields.' '.$lang_admin_common['Redirect']);
}
// Add lang
if (isset($_POST['add_lang']))
{
	if ($_POST['fields_lang'] == '')
		message($lang_fields['Fields name error']);
		
	if ($_POST['fields_value'] == '')
		message($lang_fields['Fields desc error']);
		
	$lang = $forum_db->escape($_POST['fields_lang']);
	$cache = '../extensions/nya_fields/lang/'.$lang.'.php';
	$fh = @fopen($cache, 'r');
	if (!$fh)
		message($lang_fields['Fields lang error'].$lang);
		

	$f_lang = ($_POST['fields_lang'] != '') ? '\''.$forum_db->escape($_POST['fields_lang']).'\'' : NULL;
	$f_value = ($_POST['fields_value'] != '') ? '\''.$forum_db->escape($_POST['fields_value']).'\'' : NULL;
		
	$query_lang = array(
		'INSERT'	=> 'fields_id, fields_lang, fields_value',
		'INTO'		=> 'fields_lang',
		'VALUES'	=> $fields_id.', '.$f_lang.', '.$f_value
	);
	$forum_db->query_build($query_lang) or error(__FILE__, __LINE__);

	generate_fields_cache();
	generate_fields_lang_cache($lang);

	redirect(forum_link($forum_url['admin_settings_fields_do'], array($fields_id, 'lang')), $lang_fields['Fields added'].' '.$lang_admin_common['Redirect']);
}
// Update Lang
if (isset($_POST['update_lang']))
{
	$f_lang = (key($_POST['update_lang']) != '') ? $forum_db->escape(key($_POST['update_lang'])) : NULL;
	
	if ($_POST['fields_lang'][$f_lang] == '')
		message($lang_fields['Fields lang error']);
		
	if ($_POST['fields_value'][$f_lang] == '')
		message($lang_fields['Fields value error']);

	$lang = $forum_db->escape($_POST['fields_lang'][$f_lang]);
	$cache = '../extensions/nya_fields/lang/'.$lang.'.php';

	$fh = @fopen($cache, 'r');
	if (!$fh)
		message($lang_fields['Fields lang error'].$lang);

	$f_name = ($_POST['fields_lang'][$f_lang] != '') ? $forum_db->escape($_POST['fields_lang'][$f_lang]) : NULL;
	$f_desc = ($_POST['fields_value'][$f_lang] != '') ? $forum_db->escape($_POST['fields_value'][$f_lang]) : NULL;

	$query = array(
		'UPDATE'	=> 'fields_lang',
		'SET'		=> 'fields_lang=\''.$forum_db->escape($f_name).'\', fields_value=\''.$forum_db->escape($f_desc).'\'',
		'WHERE'		=> 'fields_lang=\''.$f_lang.'\' AND fields_id='.$fields_id
	);

	$forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	generate_fields_cache();
	generate_fields_lang_cache($lang);

	redirect(forum_link($forum_url['admin_settings_fields_do'], array($fields_id, 'lang')), $lang_fields['Fields updated'].' '.$lang_admin_common['Redirect']);
}

// Remove Lang
if (isset($_POST['remove_lang']))
{
	$f_lang = (key($_POST['remove_lang']) != '') ? $forum_db->escape(key($_POST['remove_lang'])) : NULL;

	$query = array(
		'DELETE'	=> 'fields_lang',
		'WHERE'		=> 'fields_lang=\''.$f_lang.'\' AND fields_id='.$fields_id
	);

	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	generate_fields_cache();
	generate_fields_lang_cache($f_lang);

	redirect(forum_link($forum_url['admin_settings_fields_do'], array($fields_id, 'lang')), $f_lang.' '.$lang_admin_common['Redirect']);
}
if(isset($_GET['lang']))
{
// Setup the form
$forum_page['part_count'] = $forum_page['fld_count'] = $forum_page['set_count'] = 0;

// Setup breadcrumbs
$forum_page['crumbs'] = array(
	array($forum_config['o_board_title'], forum_link($forum_url['index'])),
	array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
	array($lang_admin_common['Settings'], forum_link($forum_url['admin_settings_setup'])),
	$lang_fields['Fields']
);

define('FORUM_PAGE_SECTION', 'settings');
define('FORUM_PAGE', 'admin-settings-fields');
require FORUM_ROOT.'header.php';

ob_start();

// Reset counter
$forum_page['group_count'] = $forum_page['item_count'] = 0;


?>
	<div class="main-content main-frm">
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['admin_settings_fields_do'], array($fields_id, 'lang')) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['admin_settings_fields_do'], array($fields_id, 'lang'))) ?>" />
			</div>
			<fieldset class="frm-group frm-hdgroup group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_fields['Fields legend'] ?></strong></legend>
				<fieldset class="mf-set set<?php echo ++$forum_page['item_count'] ?><?php echo ($forum_page['item_count'] == 1) ? ' mf-head' : ' mf-extra' ?>">
					<legend><span><?php echo $lang_fields['Add'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-field mf-field1 text">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>">
								<span class="fld-label"><?php echo $lang_fields['Fields lang'] ?></span>
							</label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_lang" size="20" maxlength="50" value="" /></span>
						</div>
						<div class="mf-field text">
							<label for="fld<?php echo ++$forum_page['fld_count'] ?>">
								<span class="fld-label"><?php echo $lang_fields['Fields desc'] ?></span>
							</label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_value" size="20" maxlength="100" value="" /></span>
						</div>
						<div class="mf-field text">
							<span class="submit"><input type="submit" name="add_lang" value="<?php echo $lang_fields['Add'] ?>" /></span>
						</div>
					</div>
				</fieldset>
			</fieldset>
		</form>
			
<?php

	// Reset counter
	$forum_page['group_count'] = $forum_page['item_count'] = 0;
if (!empty($forum_fields_lang))
{
	// Reset fieldset counter
	$forum_page['group_count'] = $forum_page['item_count'] = 0;

?>
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['admin_settings_fields_do'], array($fields_id, 'lang')) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['admin_settings_fields_do'], array($fields_id, 'lang'))) ?>" />
			</div>
			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><span><?php echo $lang_fields['Fields legend'] ?></span></legend>
<?php

	foreach ($forum_fields_lang as $cur_fields)
	{
		if($cur_fields['fields_id'] == $fields_id)
		{
		if ($cur_fields['fields_lang'] == $forum_config['o_default_lang'])
		{
			$btn_del='';
			$fi_lang='READONLY';
		}
		else
		{
			$btn_del="<input type=\"submit\" name=\"remove_lang[".$cur_fields['fields_lang']."]\" value=\"".$lang_fields['Remove']."\" />";
			$fi_lang='';
		}
	?>
				<fieldset class="mf-set mf-extra set<?php echo ++$forum_page['item_count'] ?>">
					<legend><span><?php echo $lang_fields['Fields list'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-field text mf-field1">
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_lang[<?php echo $cur_fields['fields_lang'] ?>]" value="<?php echo forum_htmlencode($cur_fields['fields_lang']) ?>" size="20" maxlength="50" <?php echo $fi_lang ?>/></span>
						</div>
						<div class="mf-field text">
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_value[<?php echo $cur_fields['fields_lang'] ?>]" value="<?php echo forum_htmlencode($cur_fields['fields_value']) ?>" size="20" maxlength="100" /></span>
						</div>
						<div class="mf-field text">
							<span class="submit"><input type="submit" name="update_lang[<?php echo $cur_fields['fields_lang'] ?>]" value="<?php echo $lang_fields['Update'] ?>" /><?php echo $btn_del ?></span>
						</div>
					</div>
				</fieldset>
<?php
		}
	}

?>
			</fieldset>
		</form>
	</div>
<?php

}

}
else
{
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'fields',
		'WHERE'		=> 'id='.$fields_id
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	if (!$forum_db->num_rows($result))
		message($lang_common['Bad request']);

	$cur_fields = $forum_db->fetch_assoc($result);

// Setup the form
$forum_page['part_count'] = $forum_page['fld_count'] = $forum_page['set_count'] = 0;

// Setup breadcrumbs
$forum_page['crumbs'] = array(
	array($forum_config['o_board_title'], forum_link($forum_url['index'])),
	array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
	array($lang_admin_common['Settings'], forum_link($forum_url['admin_settings_setup'])),
	$lang_fields['Fields']
);

define('FORUM_PAGE_SECTION', 'settings');
define('FORUM_PAGE', 'admin-settings-fields');
require FORUM_ROOT.'header.php';

ob_start();

// Reset counter
$forum_page['group_count'] = $forum_page['item_count'] = 0;


?>
	<div class="main-content main-frm">
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['admin_settings_fields_id'], $fields_id) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['admin_settings_fields_id'], $fields_id)) ?>" />
			</div>
			<div class="ct-box" id="info-ranks-intro">
				<p><?php printf($lang_fields['Fields intro id'], $fields_id); ?></p>
			</div>
			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_fields['Fields legend'] ?></strong></legend>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_fields['Fields name'] ?></span></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_name" size="24" maxlength="50" value="<?php echo $cur_fields['fields_name'] ?>"/></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_fields['Fields url'] ?></span></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_url" size="24" maxlength="100" value="<?php echo $cur_fields['fields_url'] ?>"/></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="fields_in_vt" value="1" <?php if ($cur_fields['fields_in_vt'] == 1) echo 'checked="checked" ' ?>/></span>
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo $lang_fields['Fields checkbox'] ?></span> <?php echo $lang_fields['Fields checkbox label'] ?></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box checkbox">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_fields['Fields lang'] ?></span></label><br />
						<span class="fld-input"><a href="<?php echo forum_link($forum_url['admin_settings_fields_do'], array($cur_fields['id'], 'lang')) ?>"><?php echo $lang_fields['Add'] ?></a></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<span class="submit"><input type="submit" name="update" value="<?php echo $lang_fields['Update'] ?>" /></span>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
<?
}
}
else{
	$query = array(
		'SELECT'	=> 'r. *',
		'FROM'		=> 'fields AS r',
		'ORDER BY'	=> 'r.id'
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

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
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['admin_settings_fields']) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['admin_settings_fields'])) ?>" />
			</div>
			<div class="ct-box" id="info-ranks-intro">
				<p><?php echo $lang_fields['Fields intro']; ?></p>
			</div>
			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_fields['Fields legend'] ?></strong></legend>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_fields['Fields name'] ?></span></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_name" size="24" maxlength="50" /></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_fields['Fields desc'] ?></span></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_desc" size="24" maxlength="100" /></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_fields['Fields url'] ?></span></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="fields_url" size="24" maxlength="100" /></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="fields_in_vt" value="1" /></span>
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo $lang_fields['Fields checkbox'] ?></span> <?php echo $lang_fields['Fields checkbox label'] ?></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<span class="submit"><input type="submit" name="add" value="<?php echo $lang_fields['Add'] ?>" /></span>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="main-content main-frm">
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['admin_settings_fields']) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['admin_settings_fields'])) ?>" />
			</div>
<?php
if ($forum_db->num_rows($result))
{
	// Reset fieldset counter
	$forum_page['group_count'] = $forum_page['item_count'] = 0;

?>
<div class="main-content">
	<table cellspacing="0">
		<thead>
		<tr>
			<th class="tc0" scope="col"><?php echo $lang_fields['Fields number'] ?></th>
			<th class="tc1" scope="col"><?php echo $lang_fields['Fields name'] ?></th>
			<th class="tc2" scope="col"><?php echo $lang_fields['Fields url'] ?></th>
			<th class="tc3" scope="col"><?php echo $lang_fields['Fields checkbox label'] ?></th>
			<th class="tc4" scope="col"><?php echo $lang_fields['Fields lang'] ?></th>
			<th class="tc5" scope="col"><?php echo $lang_fields['Remove'] ?></th>
		</tr>
		</thead>
		<tbody>
<?php

	while ($cur_fields=$forum_db->fetch_assoc($result))
	{
	if ($cur_fields['fields_in_vt'] == 0) $in_vt = $lang_fields['Fields no'];
	else $in_vt = $lang_fields['Fields yes'];
	if ($cur_fields['fields_url'] == "") $url = $lang_fields['Fields url none'];
	else $url = $cur_fields['fields_url'];
	?>
		<tr class="odd row<?php echo ++$forum_page['item_count'] ?>">
			<td class="tc0"><?php echo $forum_page['item_count'] ?></td>
			<td class="tc1"><a href="<?php echo forum_link($forum_url['admin_settings_fields_id'], $cur_fields['id']) ?>"><?php echo $cur_fields['fields_name'] ?></a></td>
			<td class="tc2"><?php echo $url ?></td>
			<td class="tc3"><?php echo $in_vt ?></td>
			<td class="tc4"><a href="<?php echo forum_link($forum_url['admin_settings_fields_do'], array($cur_fields['id'], 'lang')) ?>"><?php echo $lang_fields['Add'] ?></td>
			<td class="tc5"><a href="<?php echo forum_link($forum_url['admin_settings_fields_do'], array($cur_fields['id'], 'remove')) ?>"><?php echo $lang_fields['Remove'] ?></td>
		</tr>
<?php
	}

?>
		</tbody>
	</table>
</div>
<?php

}
else
{

?>
		<div class="frm-form">
			<div class="ct-box">
				<p><?php echo $lang_fields['Fields none'] ?></p>
			</div>
		</div>
	</div>
<?php

}
?>
		</form>
	</div>
<?
}