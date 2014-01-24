<?php
/**
 * Fields hook dispatcher class
 * 
 *
 * @copyright (C) 2012 KANekT Based on hcs extension for PunBB (C)
 * @copyright Copyright (C) 2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package fields
 */
class Fields_Hook_Dispatcher extends Base {
	/**
	 * Profile dispatcher init
	 */
    public static function pf_init()
	{
		App::load_language('nya_fields.fields');
		
		App::inject_hook('pf_change_details_identity_personal_fieldset_end',array(
			'name'	=>	'fields',
			'code'	=>	'Fields_Hook_Dispatcher::pf_change_details_identity_personal_fieldset_end($user);'
		));

		App::inject_hook('pf_change_details_identity_validation',array(
			'name'	=>	'fields',
			'code'	=>	'Fields_Hook_Dispatcher::pf_change_details_identity_validation($form);'
		));

		App::inject_hook('pf_change_details_about_pre_header_load',array(
			'name'	=>	'fields',
			'code'	=>	'Fields_Hook_Dispatcher::pf_change_details_about_pre_header_load($forum_page, $user);'
		));

		App::inject_hook('pf_view_details_pre_header_load',array(
			'name'	=>	'fields',
			'code'	=>	'Fields_Hook_Dispatcher::pf_change_details_about_pre_header_load($forum_page, $user);'
		));
		
		App::inject_hook('pf_change_details_about_pre_user_contact_info',array(
			'name'	=>	'fields',
			'code'	=>	'Fields_Hook_Dispatcher::pf_change_details_about_pre_user_contact_info($forum_page);'
		));				
		
		App::inject_hook('pf_view_details_pre_user_contact_info',array(
			'name'	=>	'fields',
			'code'	=>	'Fields_Hook_Dispatcher::pf_change_details_about_pre_user_contact_info($forum_page);'
		));
	}		

	/**
	 * Hook pf_change_details_settings_local_fieldset_end handler
	 * @param array $user
	 */
    public static function pf_change_details_identity_personal_fieldset_end($user)
	{
		if (file_exists(FORUM_CACHE_DIR.'cache_fields.php'))
			require FORUM_CACHE_DIR.'cache_fields.php';
		else
			Nya_Fields_Module_Cache::fields();

		if (!empty($forum_fields))
		{
			?>
		<fieldset class="frm-group group<?php echo ++App::$forum_page['group_count'] ?>">
			<legend class="group-legend"><strong><?php echo App::$lang['Fields legend'] ?></strong></legend>
			<?php
			$forum_page['item_count'] = 0;
			foreach ($forum_fields as $fields_key => $cur_fields)
			{
				$key = forum_htmlencode($cur_fields['fields_name']);

				?>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $cur_fields['fields_desc'] ?></span></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page['fld_count'] ?>" type="text" name="form[f_<?php echo $key ?>]" value="<?php echo(isset($form['f_'.$key]) ? forum_htmlencode($form['f_'.$key]) : $user['f_'.$key]) ?>" size="40" maxlength="100" /></span>
					</div>
				</div>
				<?
			}
			?>
		</fieldset>
		<?php
		}
	}	
	
	/**
	 * Hook pf_change_details_settings_validation handler
	 * @param int $user user id
	 * @param array $form form data array
	 */
	public static function pf_change_details_identity_validation(& $form)
	{
		if (file_exists(FORUM_CACHE_DIR.'cache_fields.php'))
			require FORUM_CACHE_DIR.'cache_fields.php';
		else
			Nya_Fields_Module_Cache::fields();

		if (!empty($forum_fields))
		{
			foreach ($forum_fields as $fields_key => $cur_fields)
			{
				$key = forum_htmlencode($cur_fields['fields_name']);
				if (isset($_POST['form']['f_'.$key])) {
					$form['f_'.$key] = $_POST['form']['f_'.$key];
				}
			}
		}
	}

	/**
	 * Hook pf_change_details_about_pre_header_load handler
	 * @param array $user user data
	 */
	public static function pf_change_details_about_pre_header_load(& $forum_page, $user)
	{
		if (file_exists(FORUM_CACHE_DIR.'cache_fields.php'))
			require FORUM_CACHE_DIR.'cache_fields.php';
		else
			Nya_Fields_Module_Cache::fields();

		if (!empty($forum_fields))
		{
			foreach ($forum_fields as $fields_key => $cur_fields)
			{
				$key = forum_htmlencode($cur_fields['fields_name']);

				if ($user['f_'.$key] != '') {
					$user['f_'.$key] = forum_htmlencode($user['f_'.$key]);

					if (App::$forum_config['o_censoring'] == '1') {
						$user['f_'.$key] = censor_words($user['f_'.$key]);
					}

					if($cur_fields['fields_url'] != NULL)
					{
						$user = '<a href="'.$cur_fields['fields_url'].$user['f_'.$key].'" class="external url">'.$user['f_'.$key].'</a>';
						$forum_page['user_fields']['f_'.$key] = '<li><span>'.$cur_fields['fields_desc'].': '.$user.'</span></li>';
					}
					else
					{
						$forum_page['user_fields']['f_'.$key] = '<li><span>'.$cur_fields['fields_desc'].': <strong>'.$user['f_'.$key].'</strong></span></li>';
					}
				}
			}
		}
	}

	/**
	 * Hook pf_delete_user_form_submitted handler
	 * @param int $id user id for delete fields
	 */
	public static function pf_change_details_about_pre_user_contact_info($forum_page)
	{
		if (!empty($forum_page['user_fields'])): ?>
		<div class="ct-set data-set set<?php echo ++$forum_page['item_count'] ?>">
			<div class="ct-box data-box">
				<h4 class="ct-legend hn"><span><?php echo App::$lang['Fields info'] ?></span></h4>
				<ul class="data-box">
					<?php echo implode("\n\t\t\t\t\t\t", $forum_page['user_fields'])."\n" ?>
				</ul>
			</div>
		</div>
		<?php endif;
	}

	public static function menu(& $forum_page)
	{
		App::load_language('nya_fields.fields');
		if (FORUM_PAGE_SECTION == 'users')
		{
			$forum_page['admin_submenu']['fields'] = '<li'.((FORUM_PAGE == 'admin-fields') ? ' class="active"' : '').'><a href="'.forum_link(App::$forum_url['admin_fields']).'">'.App::$lang['Fields'].'</a></li>';
		}
	}

	/*
	  * Back-end hook  dispatcher
	  * Inject hooks for manage global admin options of the fields
	  */

	public static function back_end_init()
	{
		App::load_language('nya_fields.fields');

		App::inject_hook('agr_edit_end_qr_update_group',array(
			'name'	=>	'fields',
			'code'	=>	'Fields_Hook_Dispatcher::agr_edit_end_qr_update_group($query, $is_admin_group);'
		));

		App::inject_hook('agr_add_edit_group_flood_fieldset_end',array(
			'name'	=>	'fields',
			'code'	=>	'Fields_Hook_Dispatcher::agr_add_edit_group_flood_fieldset_end($group);'
		));
	}

	/**
	 * Hook agr_add_edit_group_flood_fieldset_end handler
	 * Show admin group setting form for fields
	 *
	 * @param int $group
	 */
	public static function agr_add_edit_group_flood_fieldset_end($group)
	{
		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_fields/view/admin_group_setting', array('group' => $group));
		echo  View::$instance->render();
	}

	/**
	 * Hook agr_edit_end_qr_update_group handler
	 * @param array $query
	 * @param bool $is_admin_group
	 */
	public static function agr_edit_end_qr_update_group(& $query, $is_admin_group)
	{
		$fields_enable = (isset($_POST['fields_enable']) && $_POST['fields_enable'] == '1') || $is_admin_group ? '1' : '0';
		$fields_min = isset($_POST['fields_min']) ? intval($_POST['fields_min']) : '0';
		$query['SET'] .= ', g_fields_enable= '.$fields_enable.', g_fields_min='.$fields_min;
	}
} 
