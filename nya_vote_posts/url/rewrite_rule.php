<?php
/**
 * Rewrite rules for URL scheme.
 *
 * @copyright (C) 2011 hcs vote_posts extension for PunBB (C)
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package vote_posts
 */

$forum_rewrite_rules['/^vote_posts[\/_-]?view[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_vote_posts/vote_posts/view/uid/$1';
$forum_rewrite_rules['/^vote_posts[\/_-]?user[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_vote_posts/vote_posts/view/pid/$1';
$forum_rewrite_rules['/^vote_posts[\/_-]?id[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_vote_posts/vote_posts/view/id/$1';
$forum_rewrite_rules['/^vote_posts[\/_-]?view[\/_-]?([0-9a-z]+)[\/_-]?(p|page\/)([0-9]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_vote_posts/vote_posts/view/uid/$1&p=$3';
$forum_rewrite_rules['/^vote_posts[\/_-]?user[\/_-]?([0-9a-z]+)[\/_-]?(p|page\/)([0-9]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_vote_posts/vote_posts/view/pid/$1&p=$3';
$forum_rewrite_rules['/^vote_posts[\/_-]?delete[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_vote_posts/vote_posts/delete/uid/$1';
$forum_rewrite_rules['/^vote_posts[\/_-]?(up|down)[\/_-]?([0-9a-z]+)[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_vote_posts/vote_posts/$1/pid/$2/uid/$3';
