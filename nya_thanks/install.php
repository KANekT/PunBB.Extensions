<?php
/**
 * Installer
 *
 * @copyright (C) 2012 KANekT Based on hcs extension for PunBB (C)
 * @copyright Copyright (C) 2012 PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package thanks
 */

defined('THANKS_INSTALL') or die('Direct access not allowed');

if (!defined('EXT_CUR_VERSION')){
    if (!$forum_db->table_exists('thanks')) {
        $schema = array(
            'FIELDS' => array(
                'id'		=> array(
                    'datatype'		=> 'SERIAL',
                    'allow_null'	=> false
                ),
                'user_id'	=> array(
                    'datatype'		=> 'INT(10) UNSIGNED',
                    'allow_null'	=> false,
                    'default'		=> '0'
                ),
                'from_user_id'	=> array(
                    'datatype'		=> 'INT(10) UNSIGNED',
                    'allow_null'	=> false,
                    'default'		=> '0'
                ),
                'time'		=> array(
                    'datatype'		=> 'INT(10) UNSIGNED',
                    'allow_null'	=> false
                ),
                'post_id'	=> array(
                    'datatype'		=> 'INT(10) UNSIGNED',
                    'allow_null'	=> false
                ),
                'topic_id'		=> array(
                    'datatype'		=> 'INT(10) UNSIGNED',
                    'allow_null'	=> false
                )
            ),
            'PRIMARY KEY'	=> array('id'),
            'INDEXES'		=> array(
                'thanks_post_id_idx'	=> array('post_id'),
                'thanks_time_idx'	=> array('time')
            )
        );
        $forum_db->create_table('thanks', $schema);
    }

    /*Может ли пользователь голосовать*/
    if (!$forum_db->field_exists('users', 'thanks_disable_adm'))
        $forum_db->add_field('users', 'thanks_disable_adm', 'TINYINT(1)', true, '0');
    /*Отключил ли пользователь сам голосование*/
    if (!$forum_db->field_exists('users', 'thanks_enable'))
        $forum_db->add_field('users', 'thanks_enable', 'TINYINT(1)', true, '1');
    /*Кол-во сообщений у пользователя*/
    if (!$forum_db->field_exists('users', 'thanks'))
        $forum_db->add_field('users', 'thanks', 'INT(10)', true, '0');

    /*Кол-во сообщений у поста*/
    if (!$forum_db->field_exists('posts', 'thanks'))
        $forum_db->add_field('posts', 'thanks', 'INT(10)', true, '0');

    /*Могут ли голосовать*/
    if (!$forum_db->field_exists('groups', 'g_thanks_enable'))
        $forum_db->add_field('groups', 'g_thanks_enable', 'TINYINT(1)', true, '1');
    /*Кол-во сообщений для возможности голосования*/
    if (!$forum_db->field_exists('groups', 'g_thanks_min'))
        $forum_db->add_field('groups', 'g_thanks_min', 'INT(10)', true, '0');
}