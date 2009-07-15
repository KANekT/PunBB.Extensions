<?php // vim:ts=4 

if (!defined('FORUM_ROOT'))	define('FORUM_ROOT', '../../');
if (!defined('FORUM'))	define('FORUM', 1);
require FORUM_ROOT.'config.php';
require FORUM_ROOT.'include/dblayer/common_db.php';
if (file_exists(FORUM_ROOT.'extensions/thanks/lang/'.$forum_user['language'].'.php'))
	require FORUM_ROOT.'extensions/thanks/lang/'.$forum_user['language'].'.php';
else
	require FORUM_ROOT.'extensions/thanks/lang/English.php';

$post_id = (isset($_POST['po'])) ? intval($_POST['po']) : '';

$query_thanks = array(
	'SELECT'	=> 't.thank_date, u.username',
	'FROM'		=> 'thanks AS t',
	'JOINS'		=> array(
	array(
		'INNER JOIN'	=> 'users AS u',
		'ON'			=> 't.user_thanked_id=u.id'
		)),		
	'WHERE'		=> 't.post_id='.$post_id
);
$result_thanks = $forum_db->query_build($query_thanks) or error(__FILE__, __LINE__);
	if (!$forum_db->num_rows($result_thanks) > 0)
		$error =  $lang_thanks['error_03'];
	else
	{
		$UserThanks = '';
		while($row = $forum_db->fetch_assoc($result_thanks))
		{
			$timeT = date( 'd-m-Y H:h', $row['thank_date']);

			$UserThanks .= ' <b>'.$row['username'].'</b> ('.$timeT.')';
		}
	}
	if (empty($error)) echo $UserThanks;
	else  echo $error;

?>