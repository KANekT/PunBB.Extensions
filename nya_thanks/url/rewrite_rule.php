<?php
/**
 * Rewrite rules for URL scheme.
 *
 * @copyright (C) 2012 KANekT Based on hcs extension for PunBB (C)
 * @copyright Copyright (C) 2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package thanks
 */

$forum_rewrite_rules['/^thanks[\/_-]?view[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_thanks/thanks/view/uid/$1';
$forum_rewrite_rules['/^thanks[\/_-]?view?([0-9a-z]+)[\/_-]?(p|page\/)([0-9]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_thanks/thanks/view/uid/$1&p=$3';
$forum_rewrite_rules['/^thanks[\/_-]?delete[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_thanks/thanks/delete/uid/$1';
$forum_rewrite_rules['/^thanks[\/_-]?([0-9a-z]+)[\/_-]?([0-9a-z]+)[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=nya_thanks/thanks/add/pid/$1/uid/$2/token/$3';
