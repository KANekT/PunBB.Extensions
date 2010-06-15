<?php
/*
 * ajax file for jQuery Chat
 *
 * @copyright Copyright (C) 2009-2010 KANekT @ http://blog.kanekt.ru
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * Donate Web Money Z104136428007 R346491122688
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
                        break;
                case "delmsg" : // если она равняется delmsg, вызываем функцию DelMsg()
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

        // тут мы получили две переменные переданные нашим java-скриптом при помощи ajax
        // это: $_POST['name'] - имя пользователя
        // и $_POST['text'] - сообщение
		if($_POST['text'] != '')
		{
		$content = file_get_contents($chatFile);
		$content = (empty($content)) ? array() : json_decode($content);

		date_default_timezone_set('UTC');
		$message = (isset($_POST['text'])) ? $_POST['text'] : null;
		
		$user = htmlspecialchars($_POST['user']);
		$userId = intval($_POST['uid']);

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
			$message,
			$userId
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
					
		
			if ($_POST['log'] == '1') {
				$chatLog = 'data/log.log';
				$log = fopen($chatLog, 'a-');
				
				// add the new message
				$dat = date('Y-m-d H:i:s');
				$text = $user.' '.$dat.' '.$message."\n";
				fwrite($log,$text);  // 7

				fclose($log);				
			}
		}
}
 
 
// функция выполняем загрузку сообщений из базы данных и отправку их пользователю через ajax виде java-скрипта
function Load()
{
		$chat_start = intval($_POST['start']); // возвращает целое значение переменной
		$hour = intval($_POST['hour']); // возвращает целое значение переменной
		$admin = intval($_POST['adm']); // возвращает целое значение переменной
		$send = intval($_POST['send']); // возвращает целое значение переменной
		$chatFile = 'data/chat.dat';

		$content = file_get_contents($chatFile);
		$content = (empty($content)) ? array() : json_decode($content);
			
		if (count($content) > 0)
		{
			// начинаем формировать javascript который мы передадим клиенту
			$js = 'var chat = $("#chat_area");'; // получаем "указатель" на div, в который мы добавим новые сообщения
			$c_cnt = count($content);
			$day4at = $_POST['day4'];
			$end = $content[$c_cnt-1][0];
			
			if ($chat_start == 0) $mmm = 0;
			else $mmm = $c_cnt - $chat_start;
			$mmm = $content[$c_cnt-1][0] - $chat_start;
			$i=$chat_start;
			if (($content[$c_cnt-1][0] - $chat_start) > 0 && $chat_start != 0)
			{
				$i=$c_cnt-1;
			}
			while($i <= $c_cnt)
			{
				$my = strtotime($content[$i][2]);
				$m_day = gmdate('D Y-m-d ', $my + $hour);
				$m_day = '<b>'.$m_day.'</b>';
				$mhour = gmdate('H:i:s', $my + $hour);
				$myday = $m_day.$mhour;
				
				if ($day4at == gmdate('D Y-m-d', $my + $hour)) 
				{
					$myday = gmdate('H:i:s', $my + $hour);
				}
				else 
				{
					$day4at = gmdate('D Y-m-d', $my + $hour);
				}
				if ($admin == 1)
				{
					$del = '<a onclick=\"DelMsg('.$content[$i][0].')\">&chi; </a>';
				}
				else
				{
					$del =  '';
				}
				$reply = '<a onclick=\"ReplyMsg('.$content[$i][0].')\">&dArr; </a>';
				$content[$i][3] = htmlspecialchars($content[$i][3]);

				$bbcode = array("[b]", "[/b]", "[i]", "[/i]"); 
				$htmlcode = array("<b>", "</b>", "<i>", "</i>"); 
				$content[$i][3] = str_replace($bbcode, $htmlcode, $content[$i][3]);

				if ($content[$i][3] != '')
				{
					if ($content[$i][4] != 1)
					$js .= 'chat.append("<span class=\"'.$content[$i][0].'\" \">'.$del.$myday.'&raquo;'.$reply.'<a class=\"reply\" href=\"profile.php?id='.$content[$i][4].'\">'.$content[$i][1].'</a>&raquo; '.$content[$i][3].'</span>");';
					else $js .= 'chat.append("<span class=\"'.$content[$i][0].'\" id=\"'.$content[$i][0].'\">'.$del.$myday.'&raquo;'.$reply.'<a class=\"reply\" href=\"#\">'.$content[$i][1].'</a>&raquo; '.$content[$i][3].'</span>");';
				}
			$i++;
			}
			
			$my = strtotime($content[$c_cnt-1][2]);
			$day4at = gmdate('D Y-m-d', $my + $hour);
			$js .= "end = $end;send = '1';";
			$js .= "day4 = '$day4at';";
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
