<?php

/**
 * core fields
 * 
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package fields
 */

if (!defined('FORUM'))
	die();
	
function generate_fields_cache()
{
	global $forum_db;
	
	$return = ($hook = get_hook('ch_fn_generate_fields_cache_start')) ? eval($hook) : null;
	if ($return != null)
		return;

	// Get the rank list from the DB
	$query = array(
		'SELECT'	=> 'r.*',
		'FROM'		=> 'fields AS r',
		'ORDER BY'	=> 'r.id'
	);

	($hook = get_hook('ch_fn_generate_fields_cache_qr_get_fields')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	$output = array();
	while ($cur_rank = $forum_db->fetch_assoc($result))
		$output[] = $cur_rank;

	// Output fields list as PHP code
	$fh = @fopen(FORUM_CACHE_DIR.'cache_fields.php', 'wb');
	if (!$fh)
		error('Unable to write fields cache file to cache directory. Please make sure PHP has write access to the directory \'cache\'.', __FILE__, __LINE__);

	fwrite($fh, '<?php'."\n\n".'define(\'FORUM_fields_LOADED\', 1);'."\n\n".'$forum_fields = '.var_export($output, true).';'."\n\n".'?>');

	fclose($fh);
}
function change_field($table_name, $field_name, $field_name_new)
{
	global $forum_db;

	$forum_db->query('ALTER TABLE '.$forum_db->prefix.$table_name.' CHANGE f_'.$field_name.' f_'.$field_name_new.' VARCHAR(50)') or error(__FILE__, __LINE__);
}