<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (isset($option) AND $option == 'chat')
{
   if (!empty($voice))
		{
			$voice = htmlspecialchars($voice);
			$voice = strip_tags($voice);
			$voice = mysql_real_escape_string($voice);
			$userban=myquery("select * from game_ban where user_id='".$char['user_id']."' and type=2 and time>'".time()."'");
			if (mysql_num_rows($userban))
			{
				echo 'На тебя наложено проклятие. Тебе запрещено разговаривать.';
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			$result = myquery("INSERT game_chat (name, map_name, map_xpos, map_ypos, contents, post_time) VALUES ('".$char['name']."', '".$char['map_name']."', ".$char['map_xpos'].", ".$char['map_ypos'].", '$voice', '" . time() . "')");
		}
}

if (!empty($reason))
{
	include('inc/template_reason.inc.php');
}
if(isset($_GET['prison_action']))
{	
	include('quest/inc/print.inc.php');
}

echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class=m background="http://'.IMG_DOMAIN.'/nav/image_01.jpg"><tr><td valign="top">';
$refresh = 30;
if ($user_id==2694)
{
	$refresh=120;
}
echo '<meta http-equiv="refresh" content="'.$refresh.';url=act.php?func=main">';

echo '<img src="http://'.IMG_DOMAIN.'/nav/game.gif" align=right>';

echo '<table cellpadding="0" cellspacing="0" border="0">
  <tr><td valign="top">';
include('inc/template_nav2.inc.php');
echo '</td><td valign="top" width="100%">';

if ($char['map_name']==map_sea_id)
{
	$sel=myquery("select * from game_port_bil where user_id='".$char['user_id']."' and stat='1'");
	if ($sel!=false AND mysql_num_rows($sel))
	{
		$q=mysql_fetch_array($sel);

		$sell=myquery("select * from game_port where id='".$q['bil']."'");
		$qq=mysql_fetch_array($sell);

		$kuda='<font color=#FFFF80>'.@mysql_result(@myquery("SELECT rustown FROM game_gorod WHERE town='".$qq['town_kuda']."'"),0,0);
		$map = @mysql_fetch_array(@myquery("SELECT * FROM game_map WHERE town='".$qq['town_kuda']."' and to_map_name=0"));
		$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"),0,0);
		$kuda.='</font> ('.$map_name.' '.$map['xpos'].','.$map['ypos'].')';
		
		echo'<b>Ты плывешь в <font color=ff0000>'.$kuda.'</font>!<br>Прибытие ровно в: <font color=ff0000>'.$qq['dlit'].'</font><br>Сейчас:  <font color=ff0000>'.date("H:i").'</font>';

		$da = getdate($q['buydate']);
		$tm_bil = explode(":",$qq['dlit']);
		$datestamp = mktime($tm_bil[0],$tm_bil[1],0,$da['mon'],$da['mday'],$da['year']);
		if (time()>=$datestamp)
		{
			echo'<br><br><font color=ff0000 size=3><b>Ты '.echo_sex('прибыл','прибыла').'!!!</b></font>';
			$up=myquery("update game_users_map set map_name='".$map['name']."', map_xpos='".$map['xpos']."', map_ypos='".$map['ypos']."' where user_id='".$char['user_id']."'");
			$up=myquery("delete from game_port_bil where user_id='".$char['user_id']."'");
		}
	}
	else
	{
		myquery("UPDATE game_users_map SET map_name=18 WHERE user_id=$user_id");
	}
}

//перенос из боевого режима
include('inc/template_choose.inc.php');
//QuoteTable('open');
//include('inc/template_chat.inc.php');
//QuoteTable('close');
include("inc/template_around.inc.php");
include('inc/template_dropped.inc.php');

if (isset($_GET['getsunduk']) AND isset($_SESSION['getsunduk']))
{
	echo '<br /><br />';
	QuoteTable('open');
	echo $_SESSION['getsunduk'];
	QuoteTable('close');
	echo '<br /><br />';
}

//QuoteTable('open');
include('spt.php');
//QuoteTable('close');
echo '</td></tr></table>';

echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo '</td></tr></table>';
if($char['delay_reason']!=8) set_delay_reason_id($user_id,1);
if (function_exists("save_debug")) save_debug(); 
?>