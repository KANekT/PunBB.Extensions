<?php
/**
 * Thanks hook dispatcher class
 * 
 *
 * @copyright (C) 2012 KANekT Based on hcs extension for PunBB (C)
 * @copyright Copyright (C) 2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package thanks
 */
class Thanks_Hook_Dispatcher extends Base {
	/**
 	 * Front-end hook dispatcher
	 * Inject hooks for showing thanks in topic messages
	 */
	public function front_end_init()
	{
        App::$forum_loader->add_css('.thanks_sig { font-style:italic; font-size: 90%; border-radius: 8px 8px; background-color:#F3F3F3; padding: 6px 12px !important;} .thanks_mess { font-style:normal; color:#008000; } .thanks_error { font-style:normal; color:#FF0000; }', array('type' => 'inline'));
        App::$forum_loader->add_css($GLOBALS['ext_info']['url'].'/css/style.css', array('type' => 'url'));
        App::load_language('nya_thanks.thanks');

		App::inject_hook('vt_qr_get_posts',array(
			'name'	=>	'thanks',
			'url'	=>	$GLOBALS['ext_info']['url'],
			'code'	=>	'Thanks_Hook_Dispatcher::vt_qr_get_posts($query, $forum_user[\'id\'], App::$now, $posts_id);'
		));

		App::inject_hook('vt_row_pre_display',array(
			'name'	=>	'thanks',
			'code'	=>	'Thanks_Hook_Dispatcher::vt_row_pre_display($forum_page, $cur_post);'
		));

        App::inject_hook('vt_row_pre_post_actions_merge',array(
            'name'	=>	'thanks',
            'code'	=>	'Thanks_Hook_Dispatcher::vt_row_pre_post_actions_merge($cur_post, $forum_user);'
        ));
	}

    /**
     * Hook vt_row_pre_post_actions_merge
     * Create block thanks info
     *
     * @param array $forum_page
     * @param array $cur_post
     */
    public function vt_row_pre_post_actions_merge($cur_post, $forum_user)
    {
        if ($cur_post['poster_id']!=1 && $forum_user['g_thanks_enable'] == 1 && $cur_post['thanks_enable'] == 1 && $forum_user['thanks_disable_adm'] == 0 && $forum_user['thanks_enable'] == 1)
        {
			App::$forum_page['author_info']['thanks'] = '<a href="'.forum_link(App::$forum_url['thanks_view'], $cur_post['poster_id']).'">'.App::$lang['Thanks'].'</a><strong>: <span id="thp'.$cur_post['id'].'" class="thu'.$cur_post['poster_id'].'">'.$cur_post['thanks_user'].'</span></strong>';

            if(!$forum_user['is_guest'] AND $forum_user['id'] != $cur_post['poster_id'])// AND $cur_post['thanks_id'] == NULL)// AND $GLOBALS['forum_page']['thanks_info'][$cur_post['id']]['thanks_time'] < $GLOBALS['forum_page']['time'])
            {
                if ($forum_user['g_thanks_min'] <= App::$forum_user['num_posts'])
                {
                    App::$forum_page['post_actions']['thanks'] = '<span><a class="thanks_info_link thl'.$cur_post['id'].'" href="'.forum_link(App::$forum_url['thanks'], array($cur_post['id'],$cur_post['poster_id'],generate_form_token('thanks'.$cur_post['id'].$cur_post['poster_id']))).'">'.App::$lang['Thanks on post'].'</a></span>';
                }
            }
        }
    }

	/**
	 * Hook vt_row_pre_display handler
	 * Create block thanks info
	 * 
	 * @param array $forum_page
	 * @param array $cur_post
	 */	
	public function vt_row_pre_display(& $forum_page, $cur_post)
	{
        $bufer = array ();
		if (isset($forum_page['thanks_info'][$cur_post['id']]))
		{
			foreach ($forum_page['thanks_info'][$cur_post['id']] as $cur_thanks_info )
			{
                if ($cur_post['id'] == $cur_thanks_info['post_id'] && App::$forum_user['id'] == $cur_thanks_info['from_user_id'])
                    $forum_page['post_actions']['thanks'] = '';
                $bufer[]= '<a href="'.forum_link(App::$forum_url['user'], $cur_thanks_info['from_user_id']).'">'.forum_htmlencode($cur_thanks_info['username']).'</a>';
			}

			if (!empty($bufer))
			{
                $thanks = '<div class="thanks_sig"><span class="thanks_sig_head">'.App::$lang['Thanks assessed'].'</span><span>'.implode(', ', $bufer).'</span>';

				if (!isset($forum_page['message']['signature']))
				{
					$forum_page['message']['thanks_sig'] = '<div class="sig-content"><span class="sig-line"><!-- --></span>'.$thanks.'<span class="thanks">'.$cur_post['thanks'].'</span></div></div>';
				}
				else
				{
					$forum_page['message']['thanks_sig'] = '<div class="sig-content">'.$thanks.'<span class="thanks">'.$cur_post['thanks'].'</span></div></div>';
				}
			}	
		}
        $forum_page['post_options']['actions'] = '<p class="post-actions">'.implode(' ', $forum_page['post_actions']).'</p>';
	}
	
	/**
	 * Hook vt_qr_get_posts handler
	 * Change standart query for collect thanks info
	 * Prepare UI dialog
	 * 
	 * @param array $query
	 * @param int $user_id
	 * @param int $time
	 * @param int $posts_id
	 */
	public function vt_qr_get_posts(& $query, $user_id, $time, $posts_id)
	{
		App::$forum_loader->add_js($GLOBALS['ext_info']['url'].'/js/thanks.min.js', array('type' => 'url'));
		
		$GLOBALS['forum_page']['thanks_info'] = array();
		$query_thanks = array(
			'SELECT'	=> 'h.id AS thanks_id, h.post_id, u.username, h.from_user_id, h.time AS thanks_time',
			'FROM'		=> 'thanks AS h',
			'JOINS'		=> array(
				array(
					'INNER JOIN'	=> 'users AS u',
					'ON'			=> 'u.id = h.from_user_id'
				),
			),
			'WHERE'		=> 'h.post_id IN ('.implode(',', $posts_id).')'
		);
		
		$thanks_result = App::$forum_db->query_build($query_thanks) or error(__FILE__, __LINE__);
		
		while($cur_thanks = App::$forum_db->fetch_assoc($thanks_result))
		{
			$GLOBALS['forum_page']['thanks_info'][$cur_thanks['post_id']][] = $cur_thanks;
		}
/**
 * 
 * TODO:
 * make query separately
 * temporary fix
 */		
		//$query['SELECT'] .= ', u.thanks_plus, u.thanks_minus, u.thanks_enable, u.thanks_disable_adm, h.id as thanks_id';
		$query['SELECT'] .= ', p.thanks, u.thanks_enable, u.thanks_disable_adm, u.thanks as thanks_user';
		/*
		$query['JOINS'][] = array(
			'LEFT JOIN'	=> 'thanks AS h',
			'ON'			=> '(h.post_id = p.id AND h.from_user_id = '.$user_id.') OR (h.user_id = u.id AND h.from_user_id = '.$user_id.' AND h.time > '. $time.')'
		);	
		*/
	}

	/*
	 * Back-end hook  dispatcher
	 * Inject hooks for manage global admin options of the thanks
	 */
	
	public function back_end_init()
	{
		App::load_language('nya_thanks.thanks');
		
		App::inject_hook('agr_edit_end_qr_update_group',array(
			'name'	=>	'thanks',
			'code'	=>	'Thanks_Hook_Dispatcher::agr_edit_end_qr_update_group($query, $is_admin_group);'
		));

		App::inject_hook('agr_add_edit_group_flood_fieldset_end',array(
			'name'	=>	'thanks',
			'code'	=>	'Thanks_Hook_Dispatcher::agr_add_edit_group_flood_fieldset_end($group);'
		));
	}
	
	/**
	 * Hook agr_add_edit_group_flood_fieldset_end handler
	 * Show admin group setting form for thanks
	 * 
	 * @param int $group
	 */
	public function agr_add_edit_group_flood_fieldset_end($group)
	{
		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_thanks/view/admin_group_setting', array('group' => $group));	
		echo  View::$instance->render();
	}
	
	/**
	 * Hook agr_edit_end_qr_update_group handler
	 * @param array $query 
	 * @param bool $is_admin_group
	 */
	public function agr_edit_end_qr_update_group(& $query, $is_admin_group)
	{
		$thanks_enable = (isset($_POST['thanks_enable']) && $_POST['thanks_enable'] == '1') || $is_admin_group ? '1' : '0';
		$thanks_min = isset($_POST['thanks_min']) ? intval($_POST['thanks_min']) : '0';
		$query['SET'] .= ', g_thanks_enable= '.$thanks_enable.', g_thanks_min='.$thanks_min;
	}
	
	/**
	 * Profile dispatcher init
	 */
	public function profile_init()
	{
		App::load_language('nya_thanks.thanks');
		
		App::inject_hook('pf_change_details_settings_pre_local_fieldset_end',array(
			'name'	=>	'thanks',
			'code'	=>	'Thanks_Hook_Dispatcher::pf_change_details_settings_pre_local_fieldset_end($user);'
		));

		App::inject_hook('pf_change_details_settings_validation',array(
			'name'	=>	'thanks',
			'code'	=>	'Thanks_Hook_Dispatcher::pf_change_details_settings_validation($user, $form);'
		));		

		App::inject_hook('pf_change_details_about_pre_header_load',array(
			'name'	=>	'thanks',
			'code'	=>	'Thanks_Hook_Dispatcher::pf_details_about_pre_header_load($user);'
		));

        App::inject_hook('pf_view_details_pre_header_load',array(
            'name'	=>	'thanks',
            'code'	=>	'Thanks_Hook_Dispatcher::pf_details_about_pre_header_load($user);'
        ));
		
		App::inject_hook('pf_delete_user_form_submitted',array(
			'name'	=>	'thanks',
			'code'	=>	'Thanks_Hook_Dispatcher::pf_delete_user_form_submitted($id);'
		));				
	}		

	/**
	 * Hook pf_change_details_settings_local_fieldset_end handler
	 * @param array $user 
	 * @param array $lang_profile 
	 */
	public function pf_change_details_settings_pre_local_fieldset_end($user)
	{
		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_thanks/view/profile_settings', array('user' => $user));
		echo  View::$instance->render();
	}	
	
	/**
	 * Hook pf_change_details_settings_validation handler
	 * @param int $user user id
	 * @param array $form form data array
	 */
	public function pf_change_details_settings_validation($user, & $form)
	{
		if (App::$forum_user['is_admmod'] && $user['id'] != App::$forum_user['id'])
		{
			$form['thanks_disable_adm'] = (isset($_POST['form']['thanks_disable_adm'])) ? 1 :0;
		}
		else 
		{ 
		 	$form['thanks_enable'] = (isset($_POST['form']['thanks_enable'])) ? 1 :0; 
		}
	}	

	/**
	 * Hook pf_change_details_about_pre_header_load handler
	 * @param array $user user data
	 */
	public function pf_details_about_pre_header_load($user)
	{
		if ($user['thanks_disable_adm'] == 1)
		{
			App::$forum_page['user_info']['thanks'] = '<li><span>'.App::$lang['Individual Disabled'].'</span></li></a> ';
		}
		else if ($user['thanks_enable'] == 0)
		{
			App::$forum_page['user_info']['thanks'] = '<li><span>'.App::$lang['User Disable'].'</span></li></a> ';
		}
		else
		{			
			App::$forum_page['user_info']['thanks'] = '<li><span><a href="'.forum_link(App::$forum_url['thanks_view'], $user['id']).'">'.App::$lang['Thanks'].'</a><strong>: '.$user['thanks'].'</strong></span></li>';
		}
	}

	/**
	 * Hook pf_delete_user_form_submitted handler
	 * @param int $id user id for delete thanks
	 */
	public function pf_delete_user_form_submitted($id)
	{
		$query = array(
			'DELETE'	=> 'thanks',
			'WHERE'		=> 'user_id='.$id
		);
		App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
	}	
	
} 
