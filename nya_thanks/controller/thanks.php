<?php
/**
 * Thanks controller class
 *
 * @copyright (C) 2012 KANekT Based on hcs extension for PunBB (C)
 * @license http://creativecommons.org/licenses/by-nc/4.0/deed.ru
 * Attribution-NonCommercial
 * @package thanks
 */
class Nya_Thanks_Controller_Thanks extends Controller
{
	protected $thanks;
	
	public function __construct($ext_path)
	{
		parent::__construct($ext_path);
		App::load_language('nya_thanks.thanks');
		$this->check_access();
		$this->set_filter(array('uid' => 'int',	'pid' => 'int',	'rid' => 'int', 'token' => 'string'));
		$this->thanks = new Nya_Thanks_Model_Thanks;
		$this->page = 'thanks';
	}

	public function check_access()
	{
		if (App::$forum_user['g_thanks_enable'] == 0)
			message(App::$lang['Group Disabled']);
		
		if (App::$forum_user['thanks_disable_adm'] == 1)
			message(App::$lang['Individual Disabled']);

		if (App::$forum_user['thanks_enable'] == 0)
			message(App::$lang['Your Disabled']);
	}

	public function view()
	{		
		if (FALSE === ($user_thanks = $this->thanks->get_user($this->uid)))
			message(App::$lang_common['Bad request']); 

		App::$forum_page['form_action'] = forum_link(App::$forum_url['thanks_delete'], $this->uid);
		View::$instance = View::factory($this->view.'view', array ('heading' => sprintf(App::$lang['User thanks'], forum_htmlencode($user_thanks['username'])) . '&nbsp;&nbsp;<strong>'.$user_thanks['thanks'] .'</strong>'));
		$count = $this->thanks->count_by_user_id($this->uid);
		
		if ($count > 0)
		{		
			App::paginate($count, App::$forum_user['disp_topics'], App::$forum_url['thanks_view'],array($this->uid));
			
			if (App::$forum_user['g_id'] == FORUM_ADMIN)
			{
				/*
				 * Fix table layout described on: http://punbb.ru/post31786.html#p31786
				 */
				App::$forum_loader->add_css('#brd-thanks table{table-layout:inherit;}', array('type' => 'inline'));
				$template = 'view_admin';
			}
			else
			{
				$template = 'view_user';
			}
			View::$instance->content = View::factory($this->view.$template, array ('records' => $this->thanks->get_info($this->uid, App::$forum_user['g_id'], App::$forum_page['start_from'], App::$forum_page['finish_at']))); 
		}
		else {
			
			View::$instance->content = View::factory($this->view.'view_empty', array ('lang' => App::$lang));	
		}
	
		App::$forum_page['crumbs'][] = array(sprintf(App::$lang['User thanks'], forum_htmlencode($user_thanks['username'])), forum_link(App::$forum_url['thanks_view'], $this->uid));
	}
	
	public function delete()
	{
		if (!isset($_POST['delete_thanks_id'])) {
/*
 * TODO
 * Add info for signal of empty ids
 */			
			$this->view();
			return;
		}
		
		$idlist = implode(',',array_map(array($this, '_check_int_val'), $_POST['delete_thanks_id']));
		$this->thanks->delete($idlist);
		
		App::$forum_flash->add_info(App::$lang['Deleted redirect']);
		redirect(forum_link(App::$forum_url['thanks_view'], array($this->uid)), App::$lang['Deleted redirect']);
	}

    public function add()
	{
		$target = $this->pre_process();
		$errors = array();

        if (generate_form_token('thanks'.$this->pid.$this->uid) == $this->token)
        {
            $ret = $this->add_voice($errors, $target);
            if (App::$is_ajax)
            {
                if (empty($errors))
                {
                    App::send_json(array(
                        'message'	=>  App::$lang['Redirect Message'],
                        'uid'       =>  $this->uid,
                        'pid'       =>  $this->pid
                    ));
                }
                else
                {
                    App::send_json(array(
                        'code'   => -1,
                        'message' => implode('<br />',$errors)
                    ));
                }
            }
            elseif ($ret)
            {
                App::$forum_flash->add_info(App::$lang['Redirect Message']);
                redirect(forum_link(App::$forum_url['post'], $this->pid), App::$lang['Redirect Message']);
            }
        }
        else
        {
            echo $this->token.'|'.generate_form_token('thanks'.$this->uid.$this->pid).'|'.generate_form_token('thanks'.$this->pid.$this->uid);
        }
    }
	
	private function add_voice(& $errors, $target)
	{
		if (empty($errors))
		{
			$this->thanks->add_voice($target, App::$forum_user['id']);
			return TRUE;
		}
		return FALSE;
	}
	
	private function pre_process()
	{
		if (!isset($this->pid) OR !isset($this->uid))
			message(App::$lang_common['Bad request']);
			
		if (App::$forum_user['is_guest'])
			message(App::$lang_common['No permission']);

		if (App::$forum_user['id'] == $this->uid)
    		message(App::$lang['Silly user']);

		if (App::$forum_user['g_thanks_min'] > App::$forum_user['num_posts'])
		{
			message(App::$lang['Small Number of post']);
		}

		if (FALSE === ($target = $this->thanks->get_post_info($this->pid, $this->uid, App::$forum_user['id'])))
			message(App::$lang_common['Bad request']);
			
		if ($target['time'])
		{
			message(App::$lang['Duplicate error']);
		}

		if ($target['post_id'] AND $this->pid == $target['post_id'])
		{
			message(App::$lang['Duplicate error']);
		}			
			
		if ($target['thanks_enable'] != 1)
			message(App::$lang['User Disable']);
					
		App::$forum_page['crumbs'][] = array(sprintf(App::$lang['Message on topic'],forum_htmlencode($target['subject'])), forum_link(App::$forum_url['post'], $this->pid));
		
		App::$forum_page['crumbs'][] = sprintf(App::$lang['Thanks on post'], forum_htmlencode($target['username']));

		return $target;
	}
	
	private function _check_int_val($val)
	{
		if (!is_numeric($val))
			message(App::$lang_common['Bad request']);
			
		return $val;
	}	
}