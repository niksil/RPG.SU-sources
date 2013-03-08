<?php
//ob_start('ob_gzhandler',9);
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

if (function_exists("start_debug")) start_debug(); 

if (isset($_REQUEST['ignore_user']))
{
	$sel = myquery("SELECT user_id,name FROM game_users WHERE user_id=".((int)$_REQUEST['ignore_user'])."");
	if ($sel!=false AND mysql_num_rows($sel)>0)
	{
		list($ign,$name) = mysql_fetch_array($sel);
		if (isset($_REQUEST['add']))
		{
			$check1 = mysql_result(myquery("SELECT COUNT(*) FROM game_admins WHERE user_id=$ign"),0,0);
			$check2 = mysql_result(myquery("SELECT COUNT(*) FROM game_mag WHERE name='".$name."' AND town=0"),0,0);
			if ($check1>0)
			{
				$msg = '<span style="font-weight:700;color:red;font-size:12px;">'.iconv("Windows-1251","UTF-8//IGNORE","Нельзя добавлять в игнор стражей игры").'</span>';
				myquery("insert into game_log (town,message,date,fromm,too,color,ptype) values (0,'".mysql_real_escape_string($msg)."','".time()."','-1','$user_id','red',1)");
				setLocation("chat_online.php");
			}
			elseif ($check2>0)
			{
				$msg = '<span style="font-weight:700;color:red;font-size:12px;">'.iconv("Windows-1251","UTF-8//IGNORE","Нельзя добавлять в игнор модераторов чата").'</span>';
				myquery("insert into game_log (town,message,date,fromm,too,color,ptype) values (0,'".mysql_real_escape_string($msg)."','".time()."','-1','$user_id','red',1)");
				setLocation("chat_online.php");
			}
			else
			{
				myquery("INSERT INTO game_chat_ignore (user_id,ignore_id) VALUES ($user_id,$ign) ON DUPLICATE KEY UPDATE ignore_id=$ign");
				$msg = '<span style="font-weight:700;color:red;font-size:12px;">'.iconv("Windows-1251","UTF-8//IGNORE","В игнор список добавлен ".$name."").'</span>';
				myquery("insert into game_log (town,message,date,fromm,too,color,ptype) values (0,'".mysql_real_escape_string($msg)."','".time()."','-1','$user_id','red',1)");
				setLocation("chat_online.php");
			 }
		}
		else
		{
			myquery("DELETE FROM game_chat_ignore WHERE user_id=$user_id AND ignore_id=$ign");
			$msg = '<span style="font-weight:700;color:red;font-size:12px;">'.iconv("Windows-1251","UTF-8//IGNORE","Из игнор списка удален ".$name."").'</span>';
			myquery("insert into game_log (town,message,date,fromm,too,color,ptype) values (0,'".mysql_real_escape_string($msg)."','".time()."','-1','".$user_id."','red',1)");
			setLocation("chat_online.php");
		}
	}
}
elseif (isset($b) or isset($i) or isset($col) or isset($font) or isset($size) or isset($ref) or isset($autosc) or isset($fram))
{
	$_SESSION['chat_color'] = $col;
	if (!isset($col)) $col='#FFFFFF';
	$col=mysql_real_escape_string($col);
	if (isset($b)) $b=1;
	   else $b=0;
	if (isset($i)) $i=1;
	   else $i=0;
	if (isset($priv)) $priv=1;
		else $priv=0;
	if (isset($autosc)) $autosc=1;
	   else $autosc=0;
	if (!isset($fram) or $fram<210) $fram=210;
	$check = myquery("SELECT * FROM game_chat_option  where user_id='$user_id'");
	if (mysql_num_rows($check))
		$upd=myquery("update game_chat_option set color='$col',b='$b',i='$i',privat='$priv',font='$font',size='$size',ref='$ref',autosc='$autosc',frame='$fram' where user_id='$user_id'");
	else
		$upd=myquery("insert into game_chat_option (user_id,color,b,i,font,size,ref,autosc,frame) values ('$user_id','$col','$b','$i','$font','$size','$ref','$autosc','$fram')");
	$msg = '<span style="font-weight:700;color:red;font-size:12px;">'.iconv("Windows-1251","UTF-8//IGNORE","Настройки изменены").'</span>';
	myquery("insert into game_log (town,message,date,fromm,too,color,ptype) values (0,'".mysql_real_escape_string($msg)."','".time()."','',$user_id,'".$col."',1)");
	unset($b); unset($i); unset($col); unset($font); unset($size); unset($ref); unset($autosc); unset($fram);
	setLocation("chat_online.php");
}

$ban = mysql_result(myquery("SELECT COUNT(*) FROM game_ban WHERE user_id='$user_id' AND type='0'"),0,0);
if ($ban>0) die('Ты '.echo_sex('забанен','забанена').'');

$bot = 'Нафаня';
?>
<html>
<head>
<title>Чат игры</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">@import url("http://<?=DOMAIN;?>/style/global.css");</style>
<style type="text/css">@import url("chat.css");</style>
<style type="text/css">
BODY {
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FONT-SIZE: 11px; background: black url("http://<?=DOMAIN;?>/chat/img/background.gif"); background-repeat: repeat-y; PADDING-BOTTOM: 0px; MARGIN: 0px; COLOR: #fff; LINE-HEIGHT: 1.4; PADDING-TOP: 0px; FONT-FAMILY: Verdana; TEXT-ALIGN: center
}
</style>
</head>
<script type="text/javascript" language="JavaScript" src="chat.js" ></script>
<script type="text/javascript" language="JavaScript" src="../js/contextmenu.js"></script>
<?
echo '<body onload="initContextMenus();hideContextMenus();" onclick="hide_all();">';
$online_range=time()-300;
$ch=myquery("SELECT view_active_users.user_id, view_active_users.sklon, view_active_users.name, view_active_users.clan_id, view_active_users.clevel, game_users_data.sex, 
game_mag.name AS mag_name, game_mag.town AS mag_town, game_chat_ignore.user_id AS chat_ignore, game_clans.nazv
FROM (view_active_users, game_users_data, game_users_active)
LEFT JOIN (game_mag) ON (game_mag.name=view_active_users.name)
LEFT JOIN (game_clans) ON (game_clans.clan_id=view_active_users.clan_id)
LEFT JOIN (game_chat_ignore) ON (game_chat_ignore.ignore_id=view_active_users.user_id AND game_chat_ignore.user_id=$user_id)
WHERE game_users_data.user_id = view_active_users.user_id AND game_users_active.chat_active>=".$online_range." AND game_users_active.user_id=view_active_users.user_id
GROUP BY name
ORDER BY clan_id ASC , clevel DESC , name ASC
");
$chaters_array = array();
while ($users = mysql_fetch_array($ch))
{
	if ($users['user_id']==$user_id) continue;  
	$ind = '999';
	
	if ($users['clan_id']>0)
	{
		if ($users['clan_id']==$char['clan_id'])
		{
			$ind = '000';
		}
		else
		{
			if ($users['clan_id']<10)
			{
				$ind = '00'.$users['clan_id'].'';
			}
			elseif ($users['clan_id']<100)
			{
				$ind = '0'.$users['clan_id'].'';
			}
			else
			{
				$ind = ''.$users['clan_id'].'';
			}
		}
	}
	$level_user = 1000-$users['clevel'];
	if ($level_user<10)
	{
		$lev = '000'.$level_user.'';
	}
	elseif ($level_user<100)
	{
		$lev = '00'.$level_user.'';
	}
	elseif ($level_user<100)
	{
		$lev = '0'.$level_user.'';
	}
	else
	{
		$lev = ''.$level_user.'';
	} 
	$chaters_array[$ind][$lev][$users['name']] = $users;   
}
echo '<span id="channel_name"></span>';
echo '<center><b><span id="chat_kol" style="color:lightblue; width:95%; text-align:center; font-weight:bold; font-size:9px;">Чат  ['.mysql_num_rows($ch).']    </span><input type="button" value="обн." id="refresh" onclick="location.replace(\'chat_online.php\')" title="Обновить список игроков в чате"></b></center>';
echo '<span id="bot"><div align=left nowrap><span style="cursor:url(\'/images/nav/hand.cur\'), pointer" onClick="cha(\''.$bot.'\',0)"><font size=1 color=#0080FF>[Бот] '.$bot.'</font></span></div></span>';
echo '<table width="100%">';
$moder = myquery("SELECT * FROM game_mag WHERE town=0 AND name='".$char['name']."'");
$mod = mysql_fetch_array($moder);
//while ($chaters = mysql_fetch_array($ch))
//echo '<pre>'.print_r($chaters_array).'</pre>';
//mysql_query("INSERT INTO game_log (`message`,`date`,`fromm`) VALUES ('#obn:',".time().",-1)");
//$pismo = iconv("Windows-1251","UTF-8//IGNORE","<pre>".print_r($chaters_array,TRUE)."</pre>");
//mysql_query("INSERT INTO game_log (`message`,`date`,`fromm`,`too`) VALUES ('".$pismo."',".time().",-1,612)");
							
ksort($chaters_array,SORT_NUMERIC);
foreach ($chaters_array as $ar_clan_id=>$ar_clan_array)
{
	if (gettype($ar_clan_array)=='array')
	{
		ksort($ar_clan_array,SORT_NUMERIC);
		foreach ($ar_clan_array as $ar_name_id=>$ar_name_array)
		{    
			if (gettype($ar_name_array)=='array')
			{
				ksort($ar_name_array,SORT_STRING);
				foreach ($ar_name_array as $name_users=>$chaters)
				{
					if (gettype($chaters)=='array')
					{
						$msg = '<div style="position:relative;z-index:0;"><span style="cursor:url(\'/images/nav/hand.cur\'), pointer" onClick="priv(\''.$chaters['name'].'\')"><img src="img/p.gif" alt="Приват" title="Приват"></span> ';
						if ($chaters['clan_id']!=0) $msg.= '<span style="cursor:url(\'/images/nav/hand.cur\'), pointer" onClick="priv(\'клану '.$chaters['nazv'].'\')"><img width=12 height=12 src="http://'.IMG_DOMAIN.'/clan/'.$chaters['clan_id'].'.gif"></span>&nbsp;';
						$msg.=print_sklon($chaters,1);
						$font_color = "#F0F0F0";
						if ($chaters['sex']=='male') {$font_color = "#79FFFF";}
						elseif ($chaters['sex']=='female') {$font_color = "#FF80FF";}
						if ($chaters['mag_name']!=NULL AND $chaters['mag_town']==0)
						{
							$msg.= '<span onClick="cha(\''.$chaters['name'].'\',0)" id="_'.$chaters['user_id'].'" class="contextEntry" style="font-size:11px;cursor:url(\'/images/nav/hand.cur\'), pointer;color:'.$font_color.';font-weight:bold;">'.$chaters['name'].' ['.$chaters['clevel'].']</span>';
						}
						else
						{
							$msg.= '<span onClick="cha(\''.$chaters['name'].'\',0)" id="_'.$chaters['user_id'].'" class="contextEntry" style="font-size:11px;cursor:url(\'/images/nav/hand.cur\'), pointer;color:'.$font_color.'">'.$chaters['name'].' ['.$chaters['clevel'].']';
						}
						$msg.='</span>';
						if($chaters['chat_ignore']!=NULL)
						{
							$msg.='&nbsp;&nbsp;<a href="http://'.DOMAIN.'/chat/chat_online.php?del&ignore_user='.$chaters['user_id'].'" target="chat_online"><img border=0 alt="Из игнор списка" title="Из игнор списка" src="http://'.IMG_DOMAIN.'/chat/out.gif"></a><br />';
						}
						else
						{
							$msg.='&nbsp;&nbsp;<a href="http://'.DOMAIN.'/chat/chat_online.php?add&ignore_user='.$chaters['user_id'].'" target="chat_online"><img border=0 alt="В игнор список" title="В игнор список" src="http://'.IMG_DOMAIN.'/chat/in.gif"></a><br />';
						}
						$msg.='<div id="contextMenu_'.$chaters['user_id'].'" class="contextMenus" onclick="hideContextMenus()" onmouseup="execMenu(event)" onmouseover="toggleHighlight(event)" onmouseout="toggleHighlight(event)">
						<a href="http://'.DOMAIN.'/view/?userid='.$chaters['user_id'].'" target="_blank">Посмотреть&nbsp;инфо</a><br />
						<a href="http://'.DOMAIN.'/act.php?func=pm&pm=write&new&komu='.htmlentities(urlencode($chaters['name'])).'" target="game">Отправить&nbsp;письмо</a><br />
						<a style="cursor:url(\'/images/nav/hand.cur\'), pointer;"  onClick="priv(\''.$chaters['name'].'\')">Сказать&nbsp;в&nbsp;приват</a><br />';
						if ($moder)
						{
							if ($mod['mol']=='1') $msg.='<a style="cursor:url(\'/images/nav/hand.cur\'), pointer;" onClick="cha(\'#mol:10:'.$chaters['name'].'\',1)">Молчанка</a><br />';
							if ($mod['slep']=='1') $msg.='<a style="cursor:url(\'/images/nav/hand.cur\'), pointer;" onClick="cha(\'#slep:10:'.$chaters['name'].'\',1)">Слепота</a><br />';
							if ($mod['slep']=='1') $msg.='<a style="cursor:url(\'/images/nav/hand.cur\'), pointer;" onClick="cha(\'#eat:'.$chaters['name'].':причина\',1)">Съесть</a><br />';
						}
						if($chaters['chat_ignore']!=NULL)
						{
							$msg.='<a href="http://'.DOMAIN.'/chat/chat_online.php?del&ignore_name='.htmlentities(urlencode($chaters['name'])).'" target="chat_online">Из игнор списка</a><br />';
						}
						else
						{
							$msg.='<a href="http://'.DOMAIN.'/chat/chat_online.php?add&ignore_name='.htmlentities(urlencode($chaters['name'])).'" target="chat_online">В игнор список</a><br />';
						}
						$msg.='</div></div>';
						echo '<tr><td>'.$msg.'</td></tr>';
					}
				} 
			}
		} 
	}
}
			 
echo '</table>';
echo '</body></html>';

if (function_exists("save_debug")) save_debug(); 

?>