<?php
/**
 * Vote Posts controller class
 * 
 * @copyright (C) 2011 KANekT like post extension for PunBB (C)
 * @based on 2011 hcs nya_like extension for PunBB (C)
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Vote Posts
 */
class nya_Vote_Posts_Controller_Vote_Posts extends Controller
{
	protected $vote_posts;
	
	public function __construct($ext_path)
	{
		parent::__construct($ext_path);
		App::load_language('nya_vote_posts.vote_posts');
		$this->check_access();
		$this->set_filter(array('uid' => 'int',	'pid' => 'int',	'rid' => 'int'));		
		$this->vote_posts = new nya_Vote_Posts_Model_Vote_Posts;
		$this->page = 'vote_posts';
	}

	public function check_access()
	{
		if (App::$forum_user['g_vote_enable'] == 0)
			message(App::$lang['Group Disabled']);
		
		if (App::$forum_user['vote_disable_adm'] == 1)
			message(App::$lang['Individual Disabled']);
		
		if (App::$forum_config['o_vote_posts_enabled'] == 0)
			message(App::$lang['Disabled']);
		
		if (App::$forum_user['vote_enable'] == 0)
			message(App::$lang['Your Disabled']);		
	}

	public function view()
	{
		if (isset($this->id))
		{
			if (FALSE === ($user_vote = $this->vote_posts->get_by_id($this->id)))
				message(App::$lang_common['Bad request']);
				
			global $smilies;
			if (!defined('FORUM_PARSER_LOADED'))
			{
				require FORUM_ROOT.'include/parser.php';
			}	
			$user_vote['reason'] = parse_message($user_vote['reason'], 0);
				
			App::send_json(array('message' => $user_vote['reason']));
		}
		
		if (isset($this->uid))
		{
			if (FALSE === ($user_vote = $this->vote_posts->get_user($this->uid)))
				message(App::$lang_common['Bad request']); 

			App::$forum_page['form_action'] = forum_link(App::$forum_url['vote_posts_delete'], $this->pid);
			View::$instance = View::factory($this->view.'view', array ('heading' => sprintf(App::$lang['User Vote'], forum_htmlencode($user_vote['username'])) . '&nbsp;&nbsp;<strong>[+'. $user_vote['count_vote_up'] . ' / -' . $user_vote['count_vote_down'] .'] &nbsp;</strong>'));
			$count = $this->vote_posts->count_by_user_id($this->uid);
			
			if ($count > 0)
			{
				global $smilies;
				if (!defined('FORUM_PARSER_LOADED'))
				{
					require FORUM_ROOT.'include/parser.php';
				}			
				App::paginate($count, App::$forum_user['disp_topics'], App::$forum_url['vote_posts_view'],array($this->pid));
				App::$forum_loader->add_css('table{table-layout:inherit;}', array('type' => 'inline'));
				$template = (App::$forum_user['g_id'] == FORUM_ADMIN) ? 'view_user_admin' : 'view_user_user';
				View::$instance->content = View::factory($this->view.$template, array ('records' => $this->vote_posts->get_view_user($this->uid, App::$forum_user['g_id'], App::$forum_page['start_from'], App::$forum_page['finish_at']))); 
			}
			else {
				View::$instance->content = View::factory($this->view.'view_user_empty', array ('lang' => App::$lang));	
			}
		
			App::$forum_page['crumbs'][] = array(sprintf(App::$lang['User Vote'], forum_htmlencode($user_vote['username'])), forum_link(App::$forum_url['vote_posts_view'], $this->pid));
		}

		if (isset($this->pid))
		{
			if (FALSE === ($user_vote = $this->vote_posts->get_post($this->pid)))
				message(App::$lang_common['Bad request']); 

			App::$forum_page['form_action'] = forum_link(App::$forum_url['vote_posts_delete'], $this->pid);
			App::$forum_loader->add_css('table{table-layout:inherit;}', array('type' => 'inline'));
			View::$instance = View::factory($this->view.'view', array ('heading' => sprintf(App::$lang['Post Vote'], $this->pid) . '&nbsp;&nbsp;<strong>[+'. $user_vote['count_vote_up'] . ' / -' . $user_vote['count_vote_down'] .'] &nbsp;</strong>'));
			$count = $this->vote_posts->count_by_post_id($this->pid);
			
			if ($count > 0)
			{
				global $smilies;
				if (!defined('FORUM_PARSER_LOADED'))
				{
					require FORUM_ROOT.'include/parser.php';
				}			
				App::paginate($count, App::$forum_user['disp_topics'], App::$forum_url['vote_posts_view'],array($this->pid));
				$template = (App::$forum_user['g_id'] == FORUM_ADMIN) ? 'view_admin' : 'view_user';
				View::$instance->content = View::factory($this->view.$template, array ('records' => $this->vote_posts->get_view_post($this->pid, App::$forum_user['g_id'], App::$forum_page['start_from'], App::$forum_page['finish_at']))); 
			}
			else {
				View::$instance->content = View::factory($this->view.'view_empty', array ('lang' => App::$lang));	
			}
		
			App::$forum_page['crumbs'][] = array(sprintf(App::$lang['Post Vote'], $this->pid), forum_link(App::$forum_url['vote_posts_view'], $this->pid));
		}
	}
	
	public function delete()
	{
		if (!isset($_POST['delete_post_id']) AND !isset($_POST['delete_user_id']) ) {
			$this->view();
			return;
		}
		
		if(isset($_POST['delete_post_id']))
		{
			$idlist = implode(',',array_map(array($this, '_check_int_val'), $_POST['delete_post_id']));
			$this->vote_posts->delete_post($this->pid, $idlist);
				
			App::$forum_flash->add_info(App::$lang['Deleted redirect']);
			redirect(forum_link(App::$forum_url['vote_posts_view'], array($this->pid)), App::$lang['Deleted redirect']);
		}
		
		if(isset($_POST['delete_user_id']))
		{
			$idlist = implode(',',array_map(array($this, '_check_int_val'), $_POST['delete_user_id']));
			$this->vote_posts->delete_user($this->uid, $idlist);
				
			App::$forum_flash->add_info(App::$lang['Deleted redirect']);
			redirect(forum_link(App::$forum_url['vote_posts_view_user'], array($this->uid)), App::$lang['Deleted redirect']);
		}
	}
	
	public function up()
	{
		$this->do_action('up');
	}
	
	public function down()
	{
		$this->do_action('down');
	}
	
	private function do_action($action)
	{
		$target = $this->pre_process($action);
		$errors = array();
		
		if (isset($_POST['form_sent']))
		{
			if ($this->add_vote($errors, $target, $action))
			{
	    		App::$forum_flash->add_info(App::$lang['Redirect Message']);
    			redirect(forum_link(App::$forum_url['post'], $this->pid), App::$lang['Redirect Message']);			
			}
		}	
			
		App::$forum_page['form_action'] = forum_link(App::$forum_url['vote_posts_'.$action], array($this->pid, $this->uid));
		
		if (App::$is_ajax) 
		{
			if (empty($errors))
			{
				App::send_json(array(		
					'csrf_token'=> generate_form_token(App::$forum_page['form_action']),
					'title'		=> App::$lang['Vote Posts'],
					'description'=> sprintf(App::$lang[ucfirst($action)], forum_htmlencode($target['username'])),
					'user'		=>  $target['username'],
					'cancel'	=>  forum_htmlencode(App::$lang_common['Cancel']),
					'submit'	=>  forum_htmlencode(App::$lang_common['Submit'])
				));
			}
			else 
			{
				App::send_json(array(
					'error'	=> implode('<br />',$errors),
				));				
			}
		}		
		
		View::$instance = View::factory($this->view.'form', array('heading' => sprintf(App::$lang[ucfirst($action)],forum_htmlencode($target['username']))));
		View::$instance->errors = View::factory($this->view.'errors', array('errors'=>$errors, 'head' => App::$lang['Errors']));
	}
	
	
	private function add_vote(& $errors, $target, $method)
	{
		$message = $this->prepare_message($errors);
		
		if (empty($errors))
		{
			$this->vote_posts->add_vote($target, $message, App::$forum_user['id'], $method);
			return TRUE;
		}
		return FALSE;
	}
	
	private function prepare_message(& $errors)
	{
		if (!isset($_POST['req_message']))
			message(App::$lang_common['Bad request']);
			
		$message = forum_linebreaks(forum_trim($_POST['req_message']));

		if ($message == '')
		{
			$errors[] = (App::$lang['No message']);
		}
		else if (strlen($message) > App::$forum_config['o_vote_posts_maxmessage'])
		{
			$errors[] = sprintf(App::$lang['Too long message'], App::$forum_config['o_vote_posts_maxmessage']);
		}
		
		if (App::$forum_config['p_message_bbcode'] == '1' || App::$forum_config['o_make_links'] == '1')
		{
			if (!defined('FORUM_PARSER_LOADED'))
			{
				require FORUM_ROOT.'include/parser.php';
			}
			$message = preparse_bbcode($message, $errors);
		}	
		return $message;	
	}
	
	private function pre_process($method)
	{
		if (!isset($this->pid) OR !isset($this->uid))
			message(App::$lang_common['Bad request']);
			
		if (App::$forum_user['is_guest'])
			message(App::$lang_common['No permission']);

		if (App::$forum_user['id'] == $this->uid)
    		message(App::$lang['Silly user']);

		if (($method == 'up' AND App::$forum_user['g_vote_up_min'] > App::$forum_user['num_posts']) OR ($method == 'down' AND App::$forum_user['g_vote_down_min'] > App::$forum_user['num_posts']))
		{
			message(App::$lang['Small Number of post']);
		}

		$time = App::$now - App::$forum_config['o_vote_posts_timeout']*60;	
	
		if (FALSE === ($target = $this->vote_posts->get_post_info($this->pid, $this->uid, App::$forum_user['id'], $time)))
			message(App::$lang_common['Bad request']);
			
		if ($target['time'] AND $target['time'] > $time) 
		{
			message(sprintf(App::$lang['Timeout error'],$target['username'],floor(((($target['time'] + App::$forum_config['o_vote_posts_timeout'] * 60) - App::$now) / 60))));
		}

		if ($target['post_id'] AND $this->pid == $target['post_id'])
		{
			message(App::$lang['Error Posts revote']);
		}			
			
		if ($target['vote_enable'] != 1)
			message(App::$lang['User Disable']);
					
		App::$forum_page['crumbs'][] = array(sprintf(App::$lang['Message on topic'],forum_htmlencode($target['subject'])), forum_link(App::$forum_url['post'], $this->pid));
		
		if ($method == 'up')
		{
			App::$forum_page['crumbs'][] = sprintf(App::$lang['Up'], forum_htmlencode($target['username']));
		}
		else 
		{
			App::$forum_page['crumbs'][] = sprintf(App::$lang['Down'], forum_htmlencode($target['username']));
		}		

		return $target;
	}
	
	private function _check_int_val($val)
	{
		if (!is_numeric($val))
			message(App::$lang_common['Bad request']);
			
		return $val;
	}	
}
