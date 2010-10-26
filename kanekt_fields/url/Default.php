<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM'))
	exit;


$forum_url_f = array(
	'admin_settings_fields'	=> 'admin/settings.php?section=fields',
);

$forum_url = array_merge($forum_url, $forum_url_f);