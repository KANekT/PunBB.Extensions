<?php
/**
 * Caching functions.
 */
function generate_warn_cache_old()
 {
 	global $forum_db, $config;
 	
 	$query = array(
		'SELECT'	=> 'sum(w.expire) as exp, w.uid, w.time, u.warn_edit',
		'FROM'		=> 'warnings as w',
			'JOINS'		=> array(
				array(
					'INNER JOIN'	=> 'users AS u',
					'ON'			=> 'u.id=w.uid'
				)
			), 	
 		'GROUP BY'	=> 'w.uid, w.pid',
 		'ORDER BY' 	=> 'w.uid desc'
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	$cnt = 0; // Кол-во предупреждений
	$uid = 0; // ID User
	while ($cur_warn = $forum_db->fetch_assoc($result))
	{
		if($cur_warn['uid'] == $uid || $uid == 0)
		{
			$uid=$cur_warn['uid'];
			if($cur_warn['time'] + $cur_warn['exp'] < time()) $cnt++;
		}
		else 
		{
			$warn_group='';
			if($cnt==0)	$warn_group='group_id=warn_group_id, warn_group_id=0';
			
			if($cnt==1 && $cur_warn['warn_edit']>0) $warn_group='group_id='.$config['o_warn_group_sig'];
			if($cnt==2 && $cur_warn['warn_edit']>0) $warn_group='group_id='.$config['o_warn_group_ava'];
			if($cnt>2 && $cur_warn['warn_edit']>0) $warn_group='group_id='.$config['o_warn_group_read'];
			
			if ($warn_group != '')
			{
				$query = array(
					'UPDATE'	=> 'users',
					'SET'		=> $warn_group,
					'WHERE'		=> 'id='.$uid
				);
				$forum_db->query_build($query) or error(__FILE__, __LINE__);		
			}
			
			$cnt=0;
			$uid=$cur_warn['uid'];
			if($cur_warn['time'] + $cur_warn['exp'] < time()) $cnt++;
		}
		
	}
		
	$output['cached'] = time();
	$output['fail'] = false;
	
	// Output update status as PHP code
	$fh = @fopen(FORUM_CACHE_DIR.'cache_warn.php', 'wb');
	if (!$fh)
		error('Unable to write updates cache file to cache directory. Please make sure PHP has write access to the directory \'cache\'.', __FILE__, __LINE__);

	fwrite($fh, '<?php'."\n\n".'if (!defined(\'FORUM_WARN_LOADED\')) define(\'FORUM_WARN_LOADED\', 1);'."\n\n".'$forum_warn = '.var_export($output, true).';'."\n\n".'?>');

	fclose($fh);	
 }
/**
 * Caching functions.
 */
function generate_warn_cache()
 {
 	global $forum_db, $config;
 	
 	$query = array(
		'SELECT'	=> 'sum(w.expire) as exp, w.uid, w.time, u.warn_edit',
		'FROM'		=> 'warnings as w',
			'JOINS'		=> array(
				array(
					'INNER JOIN'	=> 'users AS u',
					'ON'			=> 'u.id=w.uid'
				)
			),
		'WHERE'		=> 'u.warn_count>0',
 		'GROUP BY'	=> 'w.uid, w.pid',
 		'ORDER BY' 	=> 'w.uid desc'
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
		
	$output = array();
	while ($cur_warn = $forum_db->fetch_assoc($result))
	{
		if($cur_warn['exp'] != '0')
			$output[] = $cur_warn;
		else
			$query = array(
				'UPDATE'	=> 'users',
				'SET'		=> 'group_id=warn_group_id, warn_group_id=0, warn_edit=0, warn_expiries=0, warn_count=0',
				'WHERE'		=> 'id='.$cur_warn['uid']
			);
		$forum_db->query_build($query) or error(__FILE__, __LINE__);
	}
	// Output update status as PHP code
	$fh = @fopen(FORUM_CACHE_DIR.'cache_warn.php', 'wb');
	if (!$fh)
		error('Unable to write updates cache file to cache directory. Please make sure PHP has write access to the directory \'cache\'.', __FILE__, __LINE__);

	fwrite($fh, '<?php'."\n\n".'if (!defined(\'FORUM_WARN_LOADED\')) define(\'FORUM_WARN_LOADED\', 1);'."\n\n".'$forum_warn = '.var_export($output, true).';'."\n\n".'?>');

	fclose($fh);	
 }
 define('WARN_CACHE_FUNCTIONS_LOADED', 1);
 ?>
