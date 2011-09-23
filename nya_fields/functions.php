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

	$query = array(
		'SELECT'	=> 'r. *',
		'FROM'		=> 'fields AS r',
		'ORDER BY'	=> 'r.id'
	);

	($hook = get_hook('ch_fn_generate_fields_cache_get_fields')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	$output = array();
	while ($cur_rank = $forum_db->fetch_assoc($result))
		$output[] = $cur_rank;

	$query_lang = array(
		'SELECT'	=> 'r. *',
		'FROM'		=> 'fields_lang AS r',
		'ORDER BY'	=> 'r.fields_id'
	);

	$result_lang = $forum_db->query_build($query_lang) or error(__FILE__, __LINE__);

	$output_lang = array();
	while ($cur_rank = $forum_db->fetch_assoc($result_lang))
		$output_lang[] = $cur_rank;
		
		// Output fields list as PHP code
	$fh = @fopen(FORUM_CACHE_DIR.'cache_fields.php', 'wb');
	if (!$fh)
		error('Unable to write fields cache file to cache directory. Please make sure PHP has write access to the directory \'cache\'.', __FILE__, __LINE__);

	fwrite($fh, '<?php'."\n\n".'define(\'FORUM_FIELDS_LOADED\', 1);'."\n\n".'$forum_fields = '.var_export($output, true).';'."\n\n".'$forum_fields_lang = '.var_export($output_lang, true).'?>');

	fclose($fh);
}
function change_field($table_name, $field_name, $field_name_new)
{
	global $forum_db;

	$forum_db->query('ALTER TABLE '.$forum_db->prefix.$table_name.' CHANGE f_'.$field_name.' f_'.$field_name_new.' VARCHAR(50)') or error(__FILE__, __LINE__);
}
function generate_fields_lang_cache($lang)
{
	global $forum_db;
	
	// Get the rank list from the DB
	$query = array(
		'SELECT'	=> 'r.fields_name, l.fields_value',
		'FROM'		=> 'fields AS r',
		'JOINS'		=> array(
			array(
				'INNER JOIN'	=> 'fields_lang AS l',
				'ON'			=> 'l.fields_id = r.id'
			)
		),
		'WHERE'		=> 'l.fields_lang=\''.$lang.'\'',
		'ORDER BY'	=> 'l.fields_lang'
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	$output = "";
	while ($cur_fields = $forum_db->fetch_assoc($result))
	{
		$output .= '\''.$cur_fields['fields_name'].'\' => \''.$cur_fields['fields_value'].'\','."\n";
	}
		// Output fields list as PHP code
	$cache = '../extensions/nya_fields/lang/'.$lang.'.php';
	$fh = @fopen($cache, 'r');
	if (!$fh)
		error('Unable to write fields cache file to cache directory. Please make sure PHP has write access to the directory \''.$cache.'\'.', __FILE__, __LINE__);
	$text = fread($fh,filesize($cache));
	fclose($fh); 
	$text = preg_replace('#\#\[fields\](.*?)\#\[/fields\]#si', '#[fields]'."\n".'$lang_fields_nya = array ('."\n".''.$output.');'."\n".'#[/fields]', $text);
	$fw = @fopen($cache, 'w');
	fwrite($fw,$text); 
	fclose($fw); 
}

if (file_exists(FORUM_CACHE_DIR.'cache_fields.php'))
	include FORUM_CACHE_DIR.'cache_fields.php';

if (!defined('FORUM_FIELDS_LOADED'))
{
	generate_fields_cache();
	generate_fields_lang_cache($forum_config['o_default_lang']);
	require FORUM_CACHE_DIR.'cache_fields.php';
}