<?php
/**
 * hook dispatcher class
 * 
 * @author KANekT
 * @copyright (C) 2011 KANekT extension for PunBB
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package first post
 */
class FirstPost_Hook_Dispatcher extends Base
{
    public function select_first_post(& $query)
    {
        $query['SELECT'] .= ', t.post_show_first_post';
    }

    public function post_init()
    {
        App::load_language('nya_first_post.lang');

        App::inject_hook('po_pre_optional_fieldset',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::po_pre_optional_fieldset();'
        ));

        App::inject_hook('po_pre_add_topic',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::po_pre_add_topic($post_info);'
        ));
    }

    public function po_pre_optional_fieldset()
    {
        if(App::$forum_user['g_fp_enable'] == 1)
            App::$forum_page['checkboxes']['first_post'] = '<div class="mf-item"><span class="fld-input"><input type="checkbox" id="fld'.(++App::$forum_page['fld_count']).'" name="first_post" value="1"'.(isset($_POST['first_post']) ? ' checked="checked"' : '').' /></span> <label for="fld'.App::$forum_page['fld_count'].'">'.App::$lang['First post'].'</label></div>';
    }

    public function po_pre_add_topic(& $post_info)
    {
        $post_info['first_post'] = isset($_POST['first_post']) ? 1 : 0;
    }

    public function edit_init()
    {
        App::load_language('nya_first_post.lang');

        App::inject_hook('ed_pre_checkbox_display',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::ed_pre_checkbox_display($can_edit_subject, $cur_post);'
        ));

        App::inject_hook('ed_qr_update_subject',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::ed_qr_update_subject($query);'
        ));

        App::inject_hook('ed_qr_get_post_info',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::select_first_post($query);'
        ));
    }

    public function ed_pre_checkbox_display($can_edit_subject, $cur_post)
    {
        if ($can_edit_subject && App::$forum_user['g_fp_enable'] == 1)
            App::$forum_page['checkboxes']['first_post'] = '<div class="mf-item"><span class="fld-input"><input type="checkbox" id="fld'.(++App::$forum_page['fld_count']).'" name="first_post" value="1"'.((isset($_POST['first_post']) || $cur_post['post_show_first_post'] == '1') ? ' checked="checked"' : '').' /></span> <label for="fld'.App::$forum_page['fld_count'].'">'.App::$lang['First post'].'</label></div>';
    }

    public function ed_qr_update_subject(& $query)
    {
        $query['SET'] .= ', post_show_first_post='.(isset($_POST['first_post']) ? 1 : 0);
    }

    public function topic_init()
    {
        App::load_language('nya_first_post.lang');

        App::inject_hook('vt_qr_get_posts',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::vt_qr_get_posts($query, $cur_topic, $id);'
        ));

        App::inject_hook('vt_row_pre_post_ident_merge',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::vt_row_pre_post_ident_merge($cur_topic);'
        ));

        App::inject_hook('vt_qr_get_topic_info',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::select_first_post($query);'
        ));

        App::inject_hook('vf_qr_get_topics',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::select_first_post($query);'
        ));
    }

    public function vt_qr_get_posts(& $query, $cur_topic, $id)
    {
        if($cur_topic['post_show_first_post'] != 0 and App::$forum_page['start_from'] != 0):

            $query['JOINS'][] = array(
                'INNER JOIN'	=> 'topics AS t',
                'ON'		    => 't.id=p.topic_id AND p.topic_id='.$id
            );

            $query['WHERE'] .= ' OR p.id=t.first_post_id';
        endif;
    }

    public function vt_row_pre_post_ident_merge($cur_topic)
    {
        if($cur_topic['post_show_first_post'] != 0 and App::$forum_page['start_from'] != 0):
            if (App::$forum_page['item_count'] == 1)
            {
                App::$forum_page['post_ident']['num'] = '<span class="post-num">1</span>';
            }
        endif;
    }

    /*
      * Inject hooks for manage global admin options of the reputation
      */
	
	public function admin_init()
	{
		App::load_language('nya_first_post.lang');
		
		App::inject_hook('agr_edit_end_qr_update_group',array(
			'name'	=>	'nya_first_post',
			'code'	=>	'FirstPost_Hook_Dispatcher::agr_edit_end_qr_update_group($query, $is_admin_group);'
		));
		
		App::inject_hook('agr_add_edit_group_pre_user_permissions_fieldset_end',array(
			'name'	=>	'nya_first_post',
			'code'	=>	'FirstPost_Hook_Dispatcher::agr_add_edit_group_pre_user_permissions_fieldset_end($group);'
		));

        App::inject_hook('afo_pre_header_load',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::afo_pre_header_load($lang_common);'
        ));

        App::inject_hook('afo_end',array(
            'name'	=>	'nya_first_post',
            'code'	=>	'FirstPost_Hook_Dispatcher::afo_end($forums, $lang_admin_forums);'
        ));
 	}
	
	/**
	 * Hook agr_add_edit_group_flood_fieldset_end handler
	 * Show admin group setting form for reputation
	 * 
	 * @param $group
	 */
	public function agr_add_edit_group_pre_user_permissions_fieldset_end($group)
	{
		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_first_post/view/admin_group_setting', array('group' => $group));
		echo  View::$instance->render();
	}

	/**
	 * 
	 * @param unknown_type $query
	 * @param unknown_type $is_admin_group
	 */
	public function agr_edit_end_qr_update_group(& $query, $is_admin_group)
	{
		$query['SET'] .= ', g_fp_enable= '.((isset($_POST['pf_enable']) && $_POST['pf_enable'] == '1') || $is_admin_group ? '1' : '0');
	}

    public function afo_pre_header_load($lang_common)
    {
        if (isset($_POST['fix_first_post']))
        {
            $fix_fp = isset($_POST['cat_fp_id']) ? intval($_POST['cat_fp_id']) : 0;
            if ($fix_fp < 1)
                message($lang_common['Bad request']);

            $query = array(
                'UPDATE'	=> 'topics',
                'SET'		=> 'post_show_first_post=1',
                'WHERE'		=> 'forum_id='.$fix_fp
            );

            App::$forum_db->query_build($query) or error(__FILE__, __LINE__);
            // Add flash message
            App::$forum_flash->add_info(App::$lang['Forums First Post updated'].$fix_fp);

            redirect(forum_link(App::$forum_url['admin_forums']), App::$lang['Forums First Post updated']);
        }
    }

    public function afo_end($forums, $lang_admin_forums)
    {
        View::$instance = View::factory(FORUM_ROOT.'extensions/nya_first_post/view/admin_forums', array('forums' => $forums,'lang_admin_forums' => $lang_admin_forums));
        echo  View::$instance->render();
    }
} 
