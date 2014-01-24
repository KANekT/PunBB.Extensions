<?php
/**
 * Pun_store Cache Moderators
 *
 * @author PunBB team
 * @copyright Copyright (C) 2008-2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package pun_store
 */

defined('FORUM_ROOT') or die('Direct access not allowed');
if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
	require FORUM_ROOT.'include/cache.php';
class Nya_Fields_Module_Cache
{
	static function fields()
	{
		$return = ($hook = get_hook('ch_fn_generate_fields_cache_start')) ? eval($hook) : null;
		if ($return != null)
			return;

		$query = array(
			'SELECT'	=> 'f. *',
			'FROM'		=> 'fields AS f',
			'ORDER BY'	=> 'f.id'
		);

		($hook = get_hook('ch_fn_generate_fields_cache_get_fields')) ? eval($hook) : null;
		$result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

		$output = array();
		while ($cur_row = App::$forum_db->fetch_assoc($result))
			$output[] = $cur_row;

		// Output fields list as PHP code
		$fh = @fopen(FORUM_CACHE_DIR.'cache_fields.php', 'wb');
		if (!$fh)
			error('Unable to write fields cache file to cache directory. Please make sure PHP has write access to the directory \'cache\'.', __FILE__, __LINE__);

		fwrite($fh, '<?php'."\n\n".'define(\'FORUM_FIELDS_LOADED\', 1);'."\n\n".'$forum_fields = '.var_export($output, true).';'."\n\n".'?>');

		fclose($fh);
	}
}