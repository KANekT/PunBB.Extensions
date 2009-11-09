<?php
/*
 * ajax file for jQuery Chat
 *
 * @copyright Copyright (C) 2009 KANekT @ http://blog.teamrip.ru
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package jQuery Chat
*/

// настройки для подключения к MySQl
if (!defined('FORUM_ROOT'))	define('FORUM_ROOT', '../../');

require FORUM_ROOT.'config.php';
 
// подключаемся к MySQL, если не вышло то выходим
if( !mysql_connect($db_host, $db_username, $db_password) )
{
        exit();
}
// Выбираем базу данных, если не вышло то выходим
if( !mysql_select_db($db_name) )
{
        exit();
}
mysql_query("SET NAMES 'utf8'"); // говорим MySQl'у то что мы будем работать с UTF-8
 
Header("Cache-Control: no-cache, must-revalidate"); // говорим браузеру что-бы он не кешировал эту страницу
Header("Pragma: no-cache");
 
Header("Content-Type: text/javascript; charset=utf-8"); // говорим браузеру что это javascript в кодировке UTF-8
 
// проверяем есть ли переменная act (send или load), которая указываем нам что делать
if( isset($_POST['act']) )
{
        // $_POST['act'] - существует
        switch ($_POST['act'])
        {
                case "send" : // если она равняется send, вызываем функцию Send()
                        Send();
                        break;
                case "load" : // если она равняется load, вызываем функцию Load()
                        Load();
                        break;
                default : // если ни тому и не другому  - выходим
        exit();
        }
}
 
// Функция выполняем сохранение сообщения в базе данных
function Send()
{
	global $db_prefix;
	$db_prefix = $db_prefix.'jq_chat';

        // тут мы получили две переменные переданные нашим java-скриптом при помощи ajax
        // это:  $_POST['name'] - имя пользователя
        // и $_POST['text'] - сообщение
 
        $name = substr($_POST['name'], 0, 200); // обрезаем до 200 символов
        $name = htmlspecialchars($name); // заменяем опасные теги (<h1>,<br>, и прочие) на безопасные
        $name = mysql_real_escape_string($name); // функция экранирует все спец-символы в unescaped_string , вследствие чего, её можно безопасно использовать в mysql_query()
 
        $text = substr($_POST['text'], 0, 200); // обрезаем до 200 символов
        $text = htmlspecialchars($text); // заменяем опасные теги (<h1>,<br>, и прочие) на безопасные
        $text = mysql_real_escape_string($text); // функция экранирует все спец-символы в unescaped_string , вследствие чего, её можно безопасно использовать в mysql_query()
 
        // добавляем новую запись в таблицу messages
        mysql_query("INSERT INTO $db_prefix (name,text) VALUES ('" . $name . "', '" . $text . "')");
}
 
 
// функция выполняем загрузку сообщений из базы данных и отправку их пользователю через ajax виде java-скрипта
function Load()
{
	global $db_prefix;
	$db_prefix = $db_prefix.'jq_chat';

        // тут мы получили переменную переданную нашим java-скриптом при помощи ajax
        // это:  $_POST['last'] - номер последнего сообщения которое загрузилось у пользователя
 
        $last_message_id = intval($_POST['last']); // возвращает целое значение переменной
 
        // выполняем запрос к базе данных для получения 10 сообщений последних сообщений с номером большим чем $last_message_id
        $query = mysql_query("SELECT * FROM $db_prefix WHERE ( id > $last_message_id ) ORDER BY id DESC LIMIT 10");
 
        // проверяем есть ли какие-нибудь новые сообщения
        if( mysql_num_rows($query) > 0 )
        {
                // начинаем формировать javascript который мы передадим клиенту
                $js = 'var chat = $("#chat_area");'; // получаем "указатель" на div, в который мы добавим новые сообщения
 
                // следующий конструкцией мы получаем массив сообщений из нашего запроса
                $messages = array();
                while ( $row = mysql_fetch_array($query) )
                {
                        $messages[] = $row;
                }
 
                // записываем номер последнего сообщения
                // [0] - это вернёт нам первый элемент в массиве $messages, но так как мы выполнили запрос с параметром "DESC" (в обратном порядке),
                // то это получается номер последнего сообщения в базе данных
                $last_message_id = $messages[0]['id'];
 
                // переворачиваем массив (теперь он в правильном порядке)
                $messages = array_reverse($messages);
 
                // идём по всем элементам массива $messages
                foreach ( $messages as $value )
                {
                        // продолжаем формировать скрипт для отправки пользователю
                        $js .= 'chat.append("<span>' . $value['name'] . '&raquo; ' . $value['text'] . '</span>");'; // добавить сообщние (<span>Имя &raquo; текст сообщения</span>) в наш div
                }
 
                $js .= "last_message_id = $last_message_id;"; // запишем номер последнего полученного сообщения, что бы в следующий раз начать загрузку с этого сообщения
 
                // отправляем полученный код пользователю, где он будет выполнен при помощи функции eval()
                echo $js;
        }
}
?>
