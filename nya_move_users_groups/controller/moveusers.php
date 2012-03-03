<?php
/**
 * Controller class
 *
 * @author KANekT
 * @copyright (C) 2011-2012 KANekT extension for PunBB
 * @copyright Copyright (C) 2011-2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Move user to group
 */
class Nya_Move_Users_Groups_Controller_MoveUsers extends Controller
{
	protected $move_users;
	
	public function __construct()
	{
		$this->move_users = new Nya_Move_Users_Groups_Model_MoveUsers;
        $this->cache();
	}

	public function cache()
	{
		if (FALSE === ($groups = $this->move_users->get_groups()))
			message(App::$lang_common['Bad request']);

        if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
            require FORUM_ROOT.'include/cache.php';

        // Output ranks list as PHP code
        if (!write_cache_file(FORUM_CACHE_DIR.'cache_move_users.php', '<?php'."\n\n".'define(\'FORUM_MOVE_USERS_LOADED\', 1);'."\n\n".'$forum_move_users = '.var_export($groups, true).';'."\n\n".'?>'))
        {
            error('Unable to write ranks cache file to cache directory.<br />Please make sure PHP has write access to the directory \'cache\'.', __FILE__, __LINE__);
        }
	}
}