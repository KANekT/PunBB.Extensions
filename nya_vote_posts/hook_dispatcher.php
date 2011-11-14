<?php
/**
 * Like Post hook dispatcher class
 * 
 * 
 * @copyright (C) 2011 KANekT like post extension for PunBB (C)
 * @based on 2011 hcs vote_posts extension for PunBB (C)
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Like Post
 */
class Vote_Posts_Hook_Dispatcher extends Base {
	/**
 	 * Front-end hook dispatcher
	 * Inject hooks for showing vote_posts in topic messages
	 */
	public function topic_init()
	{
		App::$forum_loader->add_css('extensions/nya_vote_posts/css/style.css', array('type' => 'url'));
		$GLOBALS['ext_jQuery_UI']->add_jQuery_UI_style(' .ui-widget {font-size: 0.8em;} .validateTips { border: 1px solid transparent; padding: 0.3em; }', 'ui_dailog_02'); // добавляем переопределение стиля в footer
		
		App::load_language('nya_vote_posts.vote_posts');
		App::inject_hook('vt_qr_get_posts',array(
			'name'	=>	'nya_vote_posts',
			'url'	=>	$GLOBALS['ext_info']['url'],
			'code'	=>	'Vote_Posts_Hook_Dispatcher::vt_qr_get_posts($query, $forum_user[\'id\'], App::$now, $posts_id);'
		));
		App::inject_hook('vt_row_pre_post_actions_merge',array(
			'name'	=>	'nya_vote_posts',
			'code'	=>	'Vote_Posts_Hook_Dispatcher::vt_row_pre_post_actions_merge($cur_post,$forum_user);'
		));
	}

	
	/**
	 * Hook vt_qr_get_posts handler
	 * Change standart query for collect vote_posts info
	 * Prepare UI dialog
	 * 
	 * @param array $query
	 * @param int $user_id
	 * @param int $time
	 * @param int $posts_id
	 */
	public function vt_qr_get_posts(& $query, $user_id, $time, $posts_id)
	{
		$GLOBALS['ext_jQuery_UI']->add_jQuery_UI("Dialog");
		$GLOBALS['ext_jQuery_UI']->add_jQuery_UI("Fade");
		$GLOBALS['ext_jQuery_UI']->add_jQuery_UI("Resizable");
		$GLOBALS['ext_jQuery_UI']->add_jQuery_UI("Draggable");
		$GLOBALS['ext_jQuery_UI']->add_jQuery_UI("Button");
		
		$vote_js_env = '
    		PUNBB.env.vote_vars = {
				"Reason" : "'.App::$lang['Form reason'].'",
		    };';

		App::$forum_loader->add_js($vote_js_env, array('type' => 'inline'));
		App::$forum_loader->add_js($GLOBALS['ext_info']['url'].'/js/vote_posts.js', array('type' => 'url'));
		
		$query['SELECT'] .= ', p.vote_up, p.vote_down, u.vote_enable, u.vote_disable_adm, v.id as vote_id';
		$query['JOINS'][] = array(
			'LEFT JOIN'	=> 'vote_posts AS v',
			'ON'			=> '(v.post_id = p.id AND v.from_user_id = '.$user_id.') OR (v.user_id = u.id AND v.from_user_id = '.$user_id.' AND v.time > '. $time.')'
		);	
		
		$GLOBALS['forum_page']['time'] = App::$now - App::$forum_config['o_vote_posts_timeout']*60;
	}
	
	/**
	 * Hook vt_row_pre_post_actions_merge handler
	 * Prepare user vote_posts for showing in topic messages
	 * 
	 * @param $cur_post
	 * @param $forum_user
	 */
	public function vt_row_pre_post_actions_merge($cur_post, $forum_user)
	{
		if ($cur_post['poster_id']!=1 && $forum_user['g_vote_enable'] == 1 && App::$forum_config['o_vote_posts_enabled'] == 1 && $cur_post['vote_enable'] == 1 && $forum_user['vote_disable_adm'] == 0 && $forum_user['vote_enable'] == 1)
		{
			App::$forum_page['post_actions']['vote_posts'] = '<p class="post-vote">';
			
			if(!$forum_user['is_guest'] AND $forum_user['id'] != $cur_post['poster_id'] AND $cur_post['vote_id'] == NULL)// AND $GLOBALS['forum_page']['vote_posts_info'][$cur_post['id']]['vote_time'] < $GLOBALS['forum_page']['time'])
			{
				if (App::$forum_user['g_vote_up_min'] < App::$forum_user['num_posts'])
				{
					App::$forum_page['post_actions']['vote_posts'] .= '<a class="vote_info_link" href="'.forum_link(App::$forum_url['vote_posts_up'], array($cur_post['id'],$cur_post['poster_id'])).'"><img src="'.forum_link('extensions/nya_vote_posts').'/img/up_unclicked.png" alt="+"></a>&nbsp;';
				}
				
 				App::$forum_page['post_actions']['vote_posts'] .= '<a href="'.forum_link(App::$forum_url['vote_posts_view'], $cur_post['id']).'">';

				if (App::$forum_config['o_vote_posts_show_full']== '1' )
				{
 					App::$forum_page['post_actions']['vote_posts'] .= '[ <font style="color:green">'.$cur_post['vote_up'] . '</font> | <font style="color:red">'. $cur_post['vote_down'] . '</font> ]';
 				} 
 				else 
 				{       
					App::$forum_page['post_actions']['vote_posts'] .= $cur_post['vote_up'] - $cur_post['vote_down'];
 				}
 				
 				App::$forum_page['post_actions']['vote_posts'] .= '</a>';
 				
 				if (App::$forum_user['g_vote_down_min'] < App::$forum_user['num_posts'])
 				{
 					App::$forum_page['post_actions']['vote_posts'] .= '&nbsp;<a class="vote_info_link" href="'. forum_link(App::$forum_url['vote_posts_down'], array($cur_post['id'],$cur_post['poster_id'])) .'"><img src="'.forum_link('extensions/nya_vote_posts').'/img/down_unclicked.png" alt="-"></a></p>';
 				}
 				 
    		}  
    		else
    		{
				App::$forum_page['post_actions']['vote_posts'] .= '&nbsp;<img src="'.forum_link('extensions/nya_vote_posts').'/img/up_disabled.png" alt="+"></a>';
				App::$forum_page['post_actions']['vote_posts'] .= '&nbsp;<a href="'.forum_link(App::$forum_url['vote_posts_view'], $cur_post['id']).'">';

				if (App::$forum_config['o_vote_posts_show_full']== '1' ) 
				{
 					App::$forum_page['post_actions']['vote_posts'] .= '[ <font style="color:green">'.$cur_post['vote_up'] . '</font> | <font style="color:red">'. $cur_post['vote_down'] . '</font> ]';
 				}
 				else
 				{       
        			App::$forum_page['post_actions']['vote_posts'] .= $cur_post['vote_up'] - $cur_post['vote_down'];
 				}
 				
				App::$forum_page['post_actions']['vote_posts'] .= '</a>';
				App::$forum_page['post_actions']['vote_posts'] .= '&nbsp;<img src="'.forum_link('extensions/nya_vote_posts').'/img/down_disabled.png" alt="-">&nbsp;';
				App::$forum_page['post_actions']['vote_posts'] .= '</p>';

    		}
		}			
	}

	/*
	 * Back-end hook  dispatcher
	 * Inject hooks for manage global admin options of the vote_posts
	 */
	
	public function admin_init()
	{
		App::load_language('nya_vote_posts.vote_posts');
		
		App::inject_hook('agr_edit_end_qr_update_group',array(
			'name'	=>	'nya_vote_posts',
			'code'	=>	'Vote_Posts_Hook_Dispatcher::agr_edit_end_qr_update_group($query, $is_admin_group);'
		));
		
		App::inject_hook('agr_add_edit_group_flood_fieldset_end',array(
			'name'	=>	'nya_vote_posts',
			'code'	=>	'Vote_Posts_Hook_Dispatcher::agr_add_edit_group_flood_fieldset_end($group);'
		));

		App::inject_hook('aop_features_message_fieldset_end',array(
			'name'	=>	'nya_vote_posts',
			'code'	=>	'Vote_Posts_Hook_Dispatcher::aop_features_message_fieldset_end();'
		));
		
		App::inject_hook('aop_features_validation',array(
			'name'	=>	'nya_vote_posts',
			'code'	=>	'Vote_Posts_Hook_Dispatcher::aop_features_validation($form);'
		));		
	}		
	
	/**
	 * Hook agr_add_edit_group_flood_fieldset_end handler
	 * Show admin group setting form for vote_posts
	 * 
	 * @param $group
	 */
	public function agr_add_edit_group_flood_fieldset_end($group)
	{
		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_vote_posts/view/admin_group_setting', array('group' => $group));	
		echo  View::$instance->render();
	}
	
	/**
	 * Hook aop_features_message_fieldset_end handler
	 * Show global vote_posts setting form
	 */
	public function aop_features_message_fieldset_end()
	{
		$forum_page['group_count'] = $forum_page['item_count'] = 0;
		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_vote_posts/view/admin_options_features', array('forum_page' => $forum_page));	
		echo  View::$instance->render();
	}	

	/**
	 * Hook agr_edit_end_qr_update_group handler
	 * @param unknown_type $query
	 * @param unknown_type $is_admin_group
	 */
	public function agr_edit_end_qr_update_group(& $query, $is_admin_group)
	{
		$vote_enable = (isset($_POST['vote_enable']) && $_POST['vote_enable'] == '1') || $is_admin_group ? '1' : '0';
		$vote_down_min = isset($_POST['vote_down_min']) ? intval($_POST['vote_down_min']) : '0';
		$vote_up_min = isset($_POST['vote_up_min']) ? intval($_POST['vote_up_min']) : '0';
		$query['SET'] .= ', g_vote_enable= '.$vote_enable.', g_vote_down_min='.$vote_down_min.', g_vote_up_min='.$vote_up_min;
	}
	
	/**
	 * Hook aop_features_validation handler
	 * @param $form
	 */
	public function aop_features_validation(& $form)
	{
		if (!isset($form['vote_posts_enabled']) || $form['vote_posts_enabled'] != '1')
		{
			$form['vote_posts_enabled'] = '0';
		}
		if (!isset($form['vote_posts_show_full']) || $form['vote_posts_show_full'] != '1')
		{
			$form['vote_posts_show_full'] = '0';
		}
		$form['vote_posts_maxmessage'] = intval($form['vote_posts_maxmessage']);
		$form['vote_posts_timeout'] = intval($form['vote_posts_timeout']);		
	}
	
	/**
	 * Profile dispatcher init
	 */
	public function profile_init()
	{
		App::load_language('nya_vote_posts.vote_posts');
		
		App::inject_hook('pf_change_details_settings_local_fieldset_end',array(
			'name'	=>	'nya_vote_posts',
			'code'	=>	'Vote_Posts_Hook_Dispatcher::pf_change_details_settings_local_fieldset_end($user, $lang_profile);'
		));

		App::inject_hook('pf_change_details_settings_validation',array(
			'name'	=>	'nya_vote_posts',
			'code'	=>	'Vote_Posts_Hook_Dispatcher::pf_change_details_settings_validation($user, $form);'
		));		

		App::inject_hook('pf_change_details_about_pre_header_load',array(
			'name'	=>	'nya_vote_posts',
			'code'	=>	'Vote_Posts_Hook_Dispatcher::pf_change_details_about_pre_header_load($user);'
		));
		
		App::inject_hook('pf_delete_user_form_submitted',array(
			'name'	=>	'nya_vote_posts',
			'code'	=>	'Vote_Posts_Hook_Dispatcher::pf_delete_user_form_submitted($id);'
		));				
	}		

	/**
	 * Hook pf_change_details_settings_local_fieldset_end handler
	 * @param array $user 
	 * @param array $lang_profile 
	 */
	public function pf_change_details_settings_local_fieldset_end($user, $lang_profile)
	{
		$forum_page['group_count'] = $forum_page['item_count'] = 0;
		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_vote_posts/view/profile_settings', array('user' => $user, 'lang_profile' => $lang_profile));	
		echo  View::$instance->render();
	}	
	
	public function pf_change_details_settings_validation($user, & $form)
	{
		if (App::$forum_user['is_admmod'] && $user['id'] != App::$forum_user['id'])
		{
			$form['vote_disable_adm'] = (isset($_POST['form']['vote_disable_adm'])) ? 1 :0;
		}
		else 
		{ 
		 	$form['vote_enable'] = (isset($_POST['form']['vote_enable'])) ? 1 :0; 
		}
	}	

	public function pf_change_details_about_pre_header_load($user)
	{
		if ($user['vote_disable_adm'] == 1)
		{
			App::$forum_page['user_info']['vote_posts'] = '<li><span>'.App::$lang['Individual Disabled'].'</span></li></a> ';
		}
		else if ($user['vote_enable'] == 0)
		{
			App::$forum_page['user_info']['vote_posts'] = '<li><span>'.App::$lang['User Disable'].'</span></li></a> ';
		}
		else
		{			
			App::$forum_page['user_info']['vote_posts'] = '<li><span><a href="'.forum_link(App::$forum_url['vote_posts_view_user'], $user['id']).'">'.App::$lang['Vote Posts'].': <strong>[ + '.$user['vote_up'].' | '. $user['vote_down'].' - ]</strong></span></li></a> ';
		}
	}	
	
	public function pf_delete_user_form_submitted($id)
	{
		$query = array(
			'DELETE'	=> 'vote_posts',
			'WHERE'		=> 'user_id='.$id
		);
		App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
	}	
	
} 
