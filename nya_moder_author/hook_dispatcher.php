<?php
/**
 * hook dispatcher class
 *
 * @author KANekT
 * @copyright (C) 2011-2012 KANekT extension for PunBB
 * @copyright Copyright (C) 2011-2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Moder - Author
 */
class ModerAuthor_Hook_Dispatcher extends Base
{
    public function user_init()
    {
        App::inject_hook('ed_pre_permission_check',array(
            'name'	=>	'nya_moder_author',
            'code'	=>	'ModerAuthor_Hook_Dispatcher::ed_pre_permission_check($cur_post);'
        ));

        App::inject_hook('ed_qr_get_post_info',array(
            'name'	=>	'nya_moder_author',
            'code'	=>	'ModerAuthor_Hook_Dispatcher::qr_get_topic_info($query);'
        ));

        App::inject_hook('vt_modify_page_details',array(
            'name'	=>	'nya_moder_author',
            'code'	=>	'ModerAuthor_Hook_Dispatcher::vt_modify_page_details($cur_topic);'
        ));

        App::inject_hook('vt_pre_header_load',array(
            'name'	=>	'nya_moder_author',
            'code'	=>	'ModerAuthor_Hook_Dispatcher::vt_pre_header_load($cur_topic);'
        ));

        App::inject_hook('vt_qr_get_topic_info',array(
            'name'	=>	'nya_moder_author',
            'code'	=>	'ModerAuthor_Hook_Dispatcher::qr_get_topic_info($query);'
        ));

        App::inject_hook('mr_pre_permission_check',array(
            'name'	=>	'nya_moder_author',
            'code'	=>	'ModerAuthor_Hook_Dispatcher::mr_pre_permission_check($mods_array, $cur_forum);'
        ));

        App::inject_hook('mr_qr_get_forum_data',array(
            'name'	=>	'nya_moder_author',
            'code'	=>	'ModerAuthor_Hook_Dispatcher::mr_qr_get_forum_data($query, $lang_common);'
        ));
    }

    public function ed_pre_permission_check($cur_post)
    {
        if (App::$forum_user['g_mta_enable'] == '1' && App::$forum_user['username'] == $cur_post['post_user'])
        {
            App::$forum_page['is_admmod'] = true;
        }
    }

    public function qr_get_topic_info(& $query)
    {
        $query['SELECT'] .= ', t.poster AS post_user';
    }

    public function vt_modify_page_details($cur_topic)
    {
        if (App::$forum_user['g_mta_enable'] == '1' && App::$forum_user['username'] == $cur_topic['post_user'])
        {
            App::$forum_user['may_post'] = App::$forum_page['is_admmod'] = App::$forum_user['is_admmod'] = true;
        }
    }

    public function vt_pre_header_load($cur_post)
    {
        if (App::$forum_user['g_mta_enable'] == '1' && App::$forum_user['username'] == $cur_post['post_user'])
        {
            unset(App::$forum_page['main_foot_options']['move']);
            unset(App::$forum_page['main_foot_options']['close']);
            unset(App::$forum_page['main_foot_options']['sticky']);
        }
    }

    public function mr_pre_permission_check(& $mods_array, $cur_forum)
    {
        if (isset($_GET['tid']))
        {
            if (App::$forum_user['g_mta_enable'] == '1' && App::$forum_user['username'] == $cur_forum['post_user'])
            {
                $mods_array[App::$forum_user['username']] = App::$forum_user['id'];
                App::$forum_user['g_moderator'] = '1';
            }
        }
    }

    public function mr_qr_get_forum_data(& $query, $lang_common)
    {
        if (isset($_GET['tid']))
        {
            $tid = intval($_GET['tid']);
            if ($tid < 1)
                message($lang_common['Bad request']);

            $query['SELECT'] .= ', t.poster AS post_user';
            $query['JOINS'][] = array(
                'LEFT JOIN'		=> 'topics AS t',
                'ON'			=> 't.id='.$tid.' AND t.moved_to IS NULL'
            );
        }
    }

      /*
      * Inject hooks for manage global admin options
      */
	
	public function admin_init()
	{
		App::load_language('nya_moder_author.lang');
		
		App::inject_hook('agr_edit_end_qr_update_group',array(
			'name'	=>	'nya_moder_author',
			'code'	=>	'ModerAuthor_Hook_Dispatcher::agr_edit_end_qr_update_group($query, $is_admin_group);'
		));
		
		App::inject_hook('agr_add_edit_group_user_permissions_fieldset_end',array(
			'name'	=>	'nya_moder_author',
			'code'	=>	'ModerAuthor_Hook_Dispatcher::agr_add_edit_group_user_permissions_fieldset_end($group);'
		));
 	}
	
	/**
	 * Hook agr_add_edit_group_flood_fieldset_end handler
	 * Show admin group setting form
	 * 
	 * @param $group
	 */
	public function agr_add_edit_group_user_permissions_fieldset_end($group)
	{
		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_moder_author/view/admin_group_setting', array('group' => $group));
		echo  View::$instance->render();
	}

	/**
	 * 
	 * @param unknown_type $query
	 * @param unknown_type $is_admin_group
	 */
	public function agr_edit_end_qr_update_group(& $query, $is_admin_group)
	{
		$query['SET'] .= ', g_mta_enable= '.((isset($_POST['mta_enable']) && $_POST['mta_enable'] == '1') || $is_admin_group ? '1' : '0');
	}
} 
