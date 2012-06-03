<?php
class Nya_Fields_Controller_Fields extends Controller
{
	protected $_fields;

	public function __construct($ext_path)
	{
		parent::__construct($ext_path);
		App::load_language('nya_fields.fields');
		// Setup breadcrumbs
		App::$forum_page['crumbs'] = array(
			array(App::$forum_config['o_board_title'], forum_link(App::$forum_url['index'])),
			array(App::$lang_admin_common['Forum administration'], forum_link(App::$forum_url['admin_index'])),
			array(App::$lang_admin_common['Settings'], forum_link(App::$forum_url['admin_settings_setup'])),
			array(App::$lang['Fields'], forum_link(App::$forum_url['admin_settings_announcements']))
		);
		if (App::$forum_user['is_guest'])
		{
			message(App::$lang_common['Bad request']);
		}
		$this->page = 'admin-fields';
		$this->section = 'users';
		$this->_fields = new Nya_Fields_Model_Fields;
		$this->set_filter(array('uid' => 'int'));
	}

	public function index ()
	{
		$count = $this->_fields->count_fields();

		View::$instance = View::factory(FORUM_ROOT.'extensions/nya_fields/view/field_form', array('lang' => App::$lang));

		App::paginate($count, App::$forum_user['disp_topics'], App::$forum_url['admin_fields']);

		if ($count > 0)
		{
			App::$forum_page['form_action'] = forum_link(App::$forum_url['admin_fields_del']);
			View::$instance->content = View::factory(FORUM_ROOT.'extensions/nya_fields/view/field_list', array ('records' => $this->_fields->get_fields(App::$forum_page['start_from'], App::$forum_page['finish_at'])));
		}
		else
			View::$instance->content = View::factory(FORUM_ROOT.'extensions/nya_fields/view/empty');
	}

	public function add()
	{
		if ($_POST['fields_name'] == '')
			message(App::$lang['Fields name error']);

		if ($_POST['fields_desc'] == '')
			message(App::$lang['Fields desc error']);

		if (!isset($_POST['fields_in_vt']) || $_POST['fields_in_vt'] != '1')
			$field['vt'] = '0';
		else
			$field['vt'] = '1';

		$field['name'] = ($_POST['fields_name'] != '') ? '\''.App::$forum_db->escape($_POST['fields_name']).'\'' : NULL;
		$field['desc'] = ($_POST['fields_desc'] != '') ? '\''.App::$forum_db->escape($_POST['fields_desc']).'\'' : NULL;
		$field['url']  = ($_POST['fields_url'] != '')  ? '\''.App::$forum_db->escape($_POST['fields_url']).'\''  : '\'\'';
		$field['field'] = $_POST['fields_name'];

		$uid = $this->_fields->add_field($field);

		Nya_Fields_Module_Cache::fields();
		App::$forum_flash->add_info(App::$lang['Fields added']);
		redirect(forum_link(App::$forum_url['admin_fields_id'], array($uid)), App::$lang['Fields added']);
	}

	public function edit()
	{
		if (isset($_POST['update']))
		{
			if ($_POST['fields_name'] == '')
				message(App::$lang['Fields name error']);

			$field['name'] = ($_POST['fields_name'] != '') ? '\''.App::$forum_db->escape($_POST['fields_name']).'\'' : NULL;
			$field['desc'] = ($_POST['fields_desc'] != '') ? '\''.App::$forum_db->escape($_POST['fields_desc']).'\'' : NULL;
			$field['url']  = ($_POST['fields_url'] != '')  ? '\''.App::$forum_db->escape($_POST['fields_url']).'\''  : '\'\'';

			if (!isset($_POST['fields_in_vt']) || $_POST['fields_in_vt'] != '1')
				$field['vt'] = '0';
			else
				$field['vt'] = '1';

			$this->_fields->set_fields_by_uid($field, $this->uid);

			if ($_POST['field'] != $_POST['fields_name'])
				$this->_fields->change_field('users', $_POST['field'], $_POST['fields_name']);

			Nya_Fields_Module_Cache::fields();

			App::$forum_flash->add_info(App::$lang['Fields updated']);
			redirect(forum_link(App::$forum_url['admin_fields_id'], array($this->uid)), App::$lang['Fields updated']);
		}
		elseif (isset($_POST['delete']))
		{
			$this->_fields->delete_field($this->uid);

			Nya_Fields_Module_Cache::fields();
			App::$forum_flash->add_info(App::$lang['Fields removed']);
			redirect(forum_link(App::$forum_url['admin_fields'], array($this->uid)), App::$lang['Fields removed']);
		}
		else
			View::$instance = View::factory(FORUM_ROOT.'extensions/nya_fields/view/field_edit', array ('records' => $this->_fields->get_fields_by_uid($this->uid)));
	}
}