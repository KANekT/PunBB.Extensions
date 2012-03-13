<?php
/**
 * Rewrite rules for URL scheme.
 *
 * @copyright (C) 2011 hcs habr extension for PunBB (C)
 * @copyright Copyright (C) 2011 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package habr
 */

$forum_rewrite_rules['/^habr[\/_-]?view[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=habr/habr/view/uid/$1';
$forum_rewrite_rules['/^habr[\/_-]?id[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=habr/habr/view/id/$1';
$forum_rewrite_rules['/^habr[\/_-]?view?([0-9a-z]+)[\/_-]?(p|page\/)([0-9]+)(\.html?|\/)?$/i'] = 'misc.php?r=habr/habr/view/uid/$1&p=$3';
$forum_rewrite_rules['/^habr[\/_-]?delete[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=habr/habr/delete';
$forum_rewrite_rules['/^habr[\/_-]?(plus|minus)[\/_-]?([0-9a-z]+)[\/_-]?([0-9a-z]+)(\.html?|\/)?$/i'] = 'misc.php?r=habr/habr/$1/pid/$2/uid/$3';
