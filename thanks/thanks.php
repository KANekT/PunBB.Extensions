<?php
/*
 * thanks file for thanks
 *
 * @copyright Copyright (C) 2009 KANekT @ http://blog.teamrip.ru
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package thanks
*/

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM_ROOT'))	define('FORUM_ROOT', '../../');
if (!defined('FORUM'))	define('FORUM', 1);
require FORUM_ROOT.'config.php';
require FORUM_ROOT.'include/dblayer/common_db.php';
if (file_exists(FORUM_ROOT.'extensions/thanks/lang/'.$forum_user['language'].'.php'))
	require FORUM_ROOT.'extensions/thanks/lang/'.$forum_user['language'].'.php';
else
	require FORUM_ROOT.'extensions/thanks/lang/English.php';
	
$user_id = (isset($_POST['user'])) ? intval($_POST['user']) : '';
$post_id = (isset($_POST['post'])) ? intval($_POST['post']) : '';
$user_thanked_id = (isset($_POST['user_t'])) ? intval($_POST['user_t']) : '';
//$user_id = (isset($_GET['user'])) ? intval($_GET['user']) : '';
//$post_id = (isset($_GET['post'])) ? intval($_GET['post']) : '';
//$user_thanked_id = (isset($_GET['user_t'])) ? intval($_GET['user_t']) : '';
$date = time();

if (empty($user_id) && empty($post_id) && empty($user_thanked_id))
	$error = $lang_thanks['error_00'];
else if ($user_id == 1)
	$error = $lang_thanks['error_01'];
else if ($user_id == $user_thanked_id)
	$error = $lang_thanks['error_02'];
else
{
	$query = array(
		'SELECT'	=> 'poster',
		'FROM'		=> 'posts',
		'WHERE'		=> 'id='.$post_id.' AND poster_id='.$user_id.''
	);
	$result = $forum_db->query_build($query) or $error = $lang_thanks['error_06'];
	$res = $forum_db->fetch_assoc($result);

	if (!$forum_db->num_rows($result) > 0)
		$error =  $lang_thanks['error_03'];
}
	$query = array(
		'SELECT'	=> 'user_thanked_id, post_id',
		'FROM'		=> 'thanks',
		'WHERE'		=> 'post_id='.$post_id.' AND user_thanked_id='.$user_thanked_id
	);
	$result = $forum_db->query_build($query) or $error = $lang_thanks['error_06'];
	$say_thanks = sprintf($lang_thanks['error_04'], $res['poster']);
	
	if ($forum_db->num_rows($result) > 0) $error = $say_thanks;

if (empty($error))
{
	$query = array(
		'INSERT'	=> 'user_id, user_thanked_id, post_id, thank_date',
		'INTO'		=> 'thanks',
		'VALUES'	=> $user_id.', '.$user_thanked_id.', '.$post_id.', '.$date
	);
	$forum_db->query_build($query);
	$query2 = array(
		'UPDATE'	=> 'users',
		'SET'		=> 'thanks=thanks+1',
		'WHERE'		=> 'id='.$user_id
	);
	$forum_db->query_build($query2);
	$query3 = array(
		'UPDATE'	=> 'posts',
		'SET'		=> 'thanks=thanks+1',
		'WHERE'		=> 'id='.$post_id
	);
	$forum_db->query_build($query3);
		//if(!$forum_db->query_build($query) && !$forum_db->query_build($query2) && !$forum_db->query_build($query3)) $error = $lang_thanks['error_06'];

}
if (empty($error))
{
	//echo $error;
}
?>