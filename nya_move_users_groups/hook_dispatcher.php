<?php
/**
 * hook dispatcher class
 *
 * @author KANekT
 * @copyright (C) 2011-2012 KANekT extension for PunBB
 * @copyright Copyright (C) 2011-2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Move user to group
 */
class MoveUsers_Hook_Dispatcher extends Base
{
    public function fn_update_user(& $query)
    {
        if (App::$forum_user['is_admmod'])
            return '';
        
        if (!defined('FORUM_MOVE_USERS_LOADED'))
        {
            if (file_exists(FORUM_CACHE_DIR.'cache_move_users.php'))
                include FORUM_CACHE_DIR.'cache_move_users.php';

            if (!defined('FORUM_MOVE_USERS_LOADED'))
            {
                if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
                    require FORUM_ROOT.'include/cache.php';

                new Nya_Move_Users_Groups_Controller_MoveUsers();
                require FORUM_CACHE_DIR.'cache_move_users.php';
            }
        }
        $return = '';
        foreach ($forum_move_users as $cur)
        {
            if (intval(App::$forum_user['num_posts']) >= $cur['g_mug_count'] && $cur['g_mug_count'] > 0 && App::$forum_user['g_id'] != $cur['g_id'] &&  App::$forum_user['g_mug_enable'] == 1)
            {
                $return = ', group_id='.$cur['g_id'];
            }
        }
        return $return;
    }

      /*
      * Inject hooks for manage global admin options
      */
	
	public function admin_init()
	{
		App::load_language('nya_move_users_groups.lang');
		
		App::inject_hook('agr_edit_end_qr_update_group',array(
			'name'	=>	'nya_move_users_groups',
			'code'	=>	'MoveUsers_Hook_Dispatcher::agr_edit_end_qr_update_group($query, $is_admin_group);'
		));
		
		App::inject_hook('agr_add_edit_group_flood_fieldset_end',array(
			'name'	=>	'nya_move_users_groups',
			'code'	=>	'MoveUsers_Hook_Dispatcher::agr_add_edit_group_flood_fieldset_end($group);'
		));

        App::inject_hook('agr_add_edit_pre_redirect',array(
            'name'	=>	'nya_move_users_groups',
            'code'	=>	'MoveUsers_Hook_Dispatcher::agr_add_edit_pre_redirect();'
        ));
    }
	
	/**
	 * Hook agr_add_edit_group_flood_fieldset_end handler
	 * Show admin group setting form
	 * 
	 * @param $group
	 */
	public function agr_add_edit_group_flood_fieldset_end($group)
	{
		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_move_users_groups/view/admin_group_setting', array('group' => $group));
		echo  View::$instance->render();
	}

	/**
	 * 
	 * @param unknown_type $query
	 * @param unknown_type $is_admin_group
	 */
	public function agr_edit_end_qr_update_group(& $query, $is_admin_group)
	{
        $mug_count = isset($_POST['mug_count']) ? intval($_POST['mug_count']) : '0';
        $mug_enable = (isset($_POST['mug_enable']) && $_POST['mug_enable'] == '1') || $is_admin_group ? '1' : '0';
		$query['SET'] .= ', g_mug_enable= '.$mug_enable.', g_mug_count='.$mug_count;
	}

    public function agr_add_edit_pre_redirect()
    {
        new Nya_Move_Users_Groups_Controller_MoveUsers();
    }
} 
