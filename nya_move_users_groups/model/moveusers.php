<?php
/**
 * Model class
 *
 * @author KANekT
 * @copyright (C) 2011-2012 KANekT extension for PunBB
 * @copyright Copyright (C) 2011-2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package Move user to group
 */
class Nya_Move_Users_Groups_Model_MoveUsers
{
	public function get_groups()
	{
        $query = array(
            'SELECT'	=> 'g_id, g_mug_count, g_mug_enable',
            'FROM'		=> 'groups AS g',
            'ORDER BY'	=> 'g.g_id'
        );

        $result = App::$forum_db->query_build($query) or error(__FILE__, __LINE__);

        $output = array();
        while ($cur = App::$forum_db->fetch_assoc($result))
            $output[] = $cur;

        return $output;
    }
}