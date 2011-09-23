<?php

$forum_url_f = array(
	'admin_settings_fields'		=> 'admin/settings.php?section=fields',
	'admin_settings_fields_id'	=> 'admin/settings.php?section=fields&amp;id=$1',
	'admin_settings_fields_do'	=> 'admin/settings.php?section=fields&amp;id=$1&amp;$2',
);

$forum_url = array_merge($forum_url, $forum_url_f);

?>