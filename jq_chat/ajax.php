<?php
/*
 * ajax file for jQuery Chat
 *
 * @copyright Copyright (C) 2009 KANekT @ http://blog.teamrip.ru
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package jQuery Chat
*/

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
                case "delmsg" : // если она равняется load, вызываем функцию Load()
                        DelMsg();
                        break;
                default : // если ни тому и не другому  - выходим
        exit();
        }
}
 
// Функция выполняем сохранение сообщения в базе данных
function Send()
{
		$chatFile = 'data/chat.dat';
		//$chatLog = 'data/log.log';

        // тут мы получили две переменные переданные нашим java-скриптом при помощи ajax
        // это: $_POST['name'] - имя пользователя
        // и $_POST['text'] - сообщение
		if($_POST['text'] != '')
		{
		$content = file_get_contents($chatFile);
		$content = (empty($content)) ? array() : json_decode($content);

		date_default_timezone_set('UTC');
		$message = (isset($_POST['text'])) ? $_POST['text'] : null;
		
		$user = htmlspecialchars($_POST['name']);
		
		// check double message
		$last = end($content);
		if(!empty($last) && $last[1]===$user && $last[3]===$message) {
			header('HTTP/1.x 403 Forbidden');
			exit();
		}
		$id = $last[0];
		$id++;

		// add the new message
		$content[] = array(
			$id,
			$user,
			date('r'),
			$message
		);
		
		// remove if there's more than 50 messages
		while(count($content) > 50) {
			array_shift($content);
		}
		
		// encode and write
		$content = json_encode($content);
		file_put_contents($chatFile, $content);
		
		// push the checksum
		$check = md5($content);
		$htaccess = file_get_contents('data/.htaccess');
		$htaccess = preg_replace('`X-json "\\\"\w{32}\\\""`', 'X-json "\"'.$check.'\""', $htaccess);
		file_put_contents('data/.htaccess', $htaccess);
					
		/*$log = file_get_contents($chatLog);
		$log = (empty($content)) ? array() : json_decode($log);

		date_default_timezone_set('UTC');
		$message = (isset($_POST['text'])) ? $_POST['text'] : null;
		
		$user = htmlspecialchars($_POST['name']);
		// check double message
		$last = end($log);
		if(!empty($last) && $last[1]===$user && $last[3]===$message) {
			header('HTTP/1.x 403 Forbidden');
			exit();
		}
		$id = $last[0];
		$id++;
		
		// add the new message
		$log[] = array(
			$id,
			$user,
			date('r'),
			$message
		);		
		
		$log = json_encode($log);
		file_put_contents($chatLog, $log);*/
		}
}
 
 
// функция выполняем загрузку сообщений из базы данных и отправку их пользователю через ajax виде java-скрипта
function Load()
{
		$chat_start = intval($_POST['start']); // возвращает целое значение переменной
		$chat_end = intval($_POST['end']); // возвращает целое значение переменной
		$metka = intval($_POST['met']); // возвращает целое значение переменной
		$mes = intval($_POST['mes']); // возвращает целое значение переменной
		$hour = intval($_POST['hour']); // возвращает целое значение переменной
		//$last_message_id = 0;
		$chatFile = 'data/chat.dat';

		$content = file_get_contents($chatFile);
		$content = (empty($content)) ? array() : json_decode($content);
			
		if (count($content) > 0)
		{
			// начинаем формировать javascript который мы передадим клиенту
			$js = 'var chat = $("#chat_area");'; // получаем "указатель" на div, в который мы добавим новые сообщения

			if ($mes>count($content)) 
			{
				$metka = 0;
				$cnt = $chat_end;
			}
			if ($chat_start == 0) $cnt = 0;
			else $cnt = $chat_end - $chat_start;
			if ($metka == 1) $cnt=$mes-1;
			/*if ($chat_start != $content[0][0] && $chat_start != 0) $cnt=0;*/
			$day4at = '';
			for($i=$cnt;$i<count($content);$i++) 
			{
				$my = strtotime($content[$i][2]);
				$m_day = gmdate('D Y-m-d ', $my + $hour);
				$m_day = '<b>'.$m_day.'</b>';
				$mhour = gmdate('H:i:s', $my + $hour);
				$myday = $m_day.$mhour;
				
				if ($day4at == gmdate('Y-m-d', $my + $hour)) $myday = gmdate('H:i:s', $my + $hour);
				else $day4at = gmdate('Y-m-d', $my + $hour);
				if ($metka == 1) $myday = gmdate('H:i:s', $my + $hour);
				
				if ($content[$i][3] != '')
				{
					$js .= 'chat.append("<span class=\"remove\" id=\"'.$content[$i][0].'\">'.$myday.'&raquo; '.$content[$i][1].'&raquo; '.$content[$i][3].'</span>");'; // добавить сообщние (<span>Имя &raquo; текст сообщения</span>) в наш div
				}

			}
			$start = $content[0][0];
			$end = $content[0][0]+$i;
			$js .= "start = $start;";
			$js .= "end = $end;";
			echo $js;

		}
		
}
// функция удаляем сообщение из базы данных
function DelMsg()
{
		$chatFile = 'data/chat.dat';
		$msgId = intval($_POST['msgId']);
		
		if ($msgId > 0)
		{
			// get content, seek for the message and delete it
			$found = false;
			$content = file_get_contents($chatFile);
			$content = (empty($content)) ? array() : json_decode($content);
			foreach($content as $i=>$msg) {
				if($msg[0] === $msgId) {
					array_splice($content, $i, 1);
					$found = true;
					break;
				}
			}

			// return new content or an error
			if($found) {
				// encode and write
				$content = json_encode($content);
				file_put_contents($chatFile, $content);
				$check = md5($content);
				$htaccess = file_get_contents('data/.htaccess');
				$htaccess = preg_replace('`X-json "\\\"\w{32}\\\""`', 'X-json "\"'.$msgId.$check.'\""', $htaccess);
				file_put_contents('data/.htaccess', $htaccess);
				}
		}
}
?>
