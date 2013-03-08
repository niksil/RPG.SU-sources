<?php
$dirclass="../class";
require_once('../inc/engine.inc.php');
include('../inc/xray.inc.php');
include('../inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '8');
}
else
{
	die();
}
include('../inc/lib_session.inc.php');

require_once('chat.class.php');
require_once('JSON.php');

myquery("DELETE FROM game_chat_nakaz WHERE date_zak<'".time()."'");

if (!isset($char)) {if (function_exists("save_debug")) save_debug(); exit;}

$selban = myquery("SELECT COUNT(*) FROM game_ban WHERE user_id='$user_id' AND type='0'");
if ($selban!=false AND mysql_num_rows($selban))
{
	$sel = mysql_result($selban,0,0);
	if ($sel>0) die('Ты '.echo_sex('забанен','забанена').'!');
}
$selban = myquery("SELECT COUNT(*) FROM game_prison WHERE user_id='$user_id'");
if ($selban!=false AND mysql_num_rows($selban))
{
	$sel = mysql_result($selban,0,0);
	if ($sel>0) die('Ты на каторге!');
}

$json=new Services_JSON();
if (isset($_POST['mode']))
{
	$mode = $_POST['mode'];
}
else
{
	$mode = 'RetrieveNew';
}
$id = 0;
$chat = new Chat();
$name = iconv("Windows-1251","UTF-8//IGNORE",$char['name']);
if (isset($_POST['id']))
{
	$id = $_POST['id'];
}
myquery("UPDATE game_users_active SET chat_active=".time()." WHERE user_id=$user_id");

if($mode == 'SendAndRetrieveNew')
{
	$message = $_POST['message'];
	if (isset($_SESSION['chat_color']))
	{
		$color = $_SESSION['chat_color'];
	}
	else
	{
		$color = '#FFFFFF';
	}
	$to_sklon = 0;
	$to_clan = 0;
	if (isset($_POST['to']))
	{
		if (substr($_POST['to'],0,6)=='sklon=')
		{
			$to_sklon = (int)substr($_POST['to'],6,strlen($_POST['to'])-6);
		}
		elseif (substr($_POST['to'],0,8)=='clan_id=')
		{
			$to_clan = (int)substr($_POST['to'],8,strlen($_POST['to'])-8);
		}
		elseif (substr($_POST['to'],0,11) == iconv("Windows-1251","UTF-8//IGNORE","клану "))
		{
			$seltoo = myquery("SELECT `clan_id` FROM  `game_clans` WHERE  `nazv` = '".iconv("UTF-8","Windows-1251//IGNORE",substr($_POST['to'],11))."';");
			if ($seltoo != false AND mysql_num_rows($seltoo) > 0)
				$to_clan = mysql_result($seltoo,0,0);
			else
				$too = 0;
		}
		elseif (substr($_POST['to'],-21) == iconv("Windows-1251","UTF-8//IGNORE"," склонности"))
		{
			switch (substr($_POST['to'],0,-21))
			{
			case iconv("Windows-1251","UTF-8//IGNORE","Нейтральной"):
				$to_sklon = 1;
				break;
			case iconv("Windows-1251","UTF-8//IGNORE","Светлой"):
				$to_sklon = 2;
				break;
			case iconv("Windows-1251","UTF-8//IGNORE","Тёмной"):
				$to_sklon = 3;
				break;
			default:
				$too = 0;
			}
		}
		else
		{
			$too = 0;
			$seltoo = myquery("SELECT user_id FROM game_users WHERE name='".iconv("UTF-8","Windows-1251//IGNORE",$_POST['to'])."'");
			if ($seltoo!=false AND mysql_num_rows($seltoo)>0)
			{
				$too = mysql_result($seltoo,0,0);
			}
		}
	}
	else
	{
		$too = 0;
	}
	if (isset($_POST['chat']))
	{
		$channel = (int)$_POST['chat'];
	}
	else
	{
		$channel = 0;
	}
	if ($message != '' AND $message!='CLEAR')
	{
		setlocale (LC_ALL, "ru_RU.CP1251");
		$String_AM = new anti_mate;
		$message = str_replace("ERRNO","",$message);
		$message = iconv("UTF-8","Windows-1251//IGNORE",$message);
		$message = $String_AM->filter($message);
		$message = iconv("Windows-1251","UTF-8//IGNORE",$message);

		$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
		if (!mysql_num_rows($userban))
		{

			if ($to_sklon > 0)
				$say = $chat->postMessage($message, $color, $to_sklon, $user_id, $channel, 3);
			elseif ($to_clan > 0)
				$say = $chat->postMessage($message, $color, $to_clan,  $user_id, $channel, 2);
			elseif ($too > 0)
				$say = $chat->postMessage($message, $color, $too,      $user_id, $channel, 1);
			else
				$say = $chat->postMessage($message, $color, 0,         $user_id, $channel, 0);

			$message = iconv("UTF-8","Windows-1251//IGNORE",$message); 
			if (preg_match("/Нафаня/", "$message")>0)
			{   
				$selpech = myquery("select count(*) from game_chat_nakaz where town=0 AND user_id='$user_id' and date_zak>'".time()."'");
				$userpech=0;
				if ($selpech!=false AND mysql_num_rows($selpech)>0)
				{
					$userpech=mysql_result($selpech,0,0);
				}
				if ($userpech==0)
				{    
					include('bot/index.php');
				}
			}
	   }       
	}
}
if(ob_get_length()) ob_clean();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/javascript');

$_SESSION['lastMessageID'] = $id;
echo $json->encode($chat->retrieveNewMessages($user_id, $id, $char['clan_id'], $char['sklon']));
?>