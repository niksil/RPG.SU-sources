<?php
if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (function_exists("start_debug")) start_debug(); 

error_reporting('E_ALL');

$GLOBALS['numsql']=0;
$GLOBALS['time_mysql_query']=0;
include('inc/template.inc.php');

echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top">';

$from_boy = true;
include("inc/gorod/turnir.inc.php");
echo '<br /><br />';
$link = "http://".DOMAIN."/act.php?func=boy";
//echo'<meta http-equiv="refresh" content="20;url='.$link.'">';

if (!isset($arcomage_type)) $arcomage_type=1;

//Подача заявки
if (isset($arcomage_timeout) AND isset($arcomage_zayava))
{
	if ($arcomage_timeout!=3 and $arcomage_timeout!=5 and $arcomage_timeout!=10 and $arcomage_timeout!=15) $arcomage_timeout=3;
	$arcomage_timeout = $arcomage_timeout*60;
	myquery("DELETE FROM arcomage_call WHERE user_id='$user_id'");
	myquery("INSERT INTO arcomage_call (user_id,user_name,begin,end) VALUES ('".$user_id."','".$char['name']."','".time()."','".(time()+$arcomage_timeout)."')");
}

//Отмена заявки
if (isset($arcomage_no))
{
	myquery("DELETE FROM arcomage_call WHERE user_id='$user_id'");
}

//Вызов
if (isset($arcomage_arco_id) AND isset($arcomage_call_zayava))
{
	$arcomage_arco_id=(int)$arcomage_arco_id;
	$sel = myquery("SELECT * FROM arcomage_call WHERE user_id='$arcomage_arco_id' AND end>=".time()." LIMIT 1");
	if (mysql_num_rows($sel))
	{
		$call = mysql_fetch_array($sel);
		$arcomage_online_range = time()-300;
		$arcomage_result = myquery("SELECT view_active_users.*,IFNULL(combat_users.combat_id,0) as boy FROM view_active_users LEFT JOIN combat_users ON (view_active_users.user_id=combat_users.user_id) WHERE view_active_users.user_id=$arcomage_arco_id and view_active_users.user_id<>".$char['user_id']." ");
		$player = mysql_fetch_array($arcomage_result);
		if (!mysql_num_rows($arcomage_result))
		{
			echo '<script>location.replace("act.php?errror=arcomage_active")</script>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		if (played_arco($call['user_id'])!=0)        
		{
			echo '<script>location.replace("act.php?errror=arcomage_active")</script>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		if ((($call['money']>0) AND ($call['money']>$char['GP'])))
		{
			echo '<script>location.replace("act.php?errror=arcomage_call_money")</script>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		if ((($call['money']>0) AND ($call['money']>$player['GP'])))
		{
			echo '<script>location.replace("act.php?errror=arcomage_player_money")</script>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}

		list($last_active1,$host1) = mysql_fetch_array(myquery("SELECT last_active,host FROM game_users_active WHERE user_id='$user_id'"));
		list($last_active2,$host2) = mysql_fetch_array(myquery("SELECT last_active,host FROM game_users_active WHERE user_id='$arcomage_arco_id'"));

		$online_range = time()-PHPRPG_SESSION_EXPIRY;
		if ($player['delay_reason']!=2 AND $player['delay_reason']!=3 AND $player['delay_reason']!=4AND $player['delay_reason']!=5 AND $player['delay_reason']!=6 AND $player['delay_reason']!=13 AND $last_active2>$online_range AND $player['boy']==0 AND $host1!=$host2)
		{
			myquery("DELETE FROM arcomage_call WHERE user_id='$arcomage_arco_id'");
			arcomage_user($char,$player,$call['money']);
			echo '<script>location.replace("arcomage.php")</script>';
		}
		else
		{
			echo '<script>location.replace("act.php?errror")</script>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
	}
}

$zayavka='<br /><br />Подать заявку на игру в Две Башни<br /><form action="" method=post>
<table border=0 cellspacing=0 cellpadding=0><tr><td>Таймаут:</td>
<td>
<select name=arcomage_timeout>
<option value=3>3 мин.</option>
<option value=5>5 мин.</option>
<option value=10>10 мин.</option>
<option value=15>15 мин.</option>
</select>
</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp;</td><td>&nbsp</td><td><input name="arcomage_zayava" type=submit value="Подать заявку"></td>
</tr></table></form>';

$img='http://'.IMG_DOMAIN.'/race_table/elf/table';
echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top">';

echo'<table border=0 width=98% cellspacing=0 cellpadding=2>
<tr bgcolor=#333333><td style="color:#F0E68C">Поединки в Две Башни: Заявки</td></tr><tr><td>';

unset($arcomage_no);
unset($arcomage_zayava);
unset($arcomage_call_zayava);

$call_can = 1;
if ($arcomage_type==1)
{
	echo'<form action="" method=post>';
	$not = 0;
	$count = 0;
	$selcall = myquery("SELECT * FROM arcomage_call WHERE end>".time()."");
	while ($call = mysql_fetch_array($selcall))
	{
		$count++;
		$end = $call['end']-time();
		$end_min = floor($end/60);
		$end_sec = $end%60;
		if ($user_id == $call['user_id'])
		{
			echo ''.date("H:i",$call['begin']).' <input type=radio name=arcomage_arco_id value='.$user_id.' disabled> <font color=F0E68C><b><a href="http://'.DOMAIN.'/view/?userid='.$user_id.'" target=_blank>'.$call['user_name'].'</a></b></font> таймаут через: '.$end_min.' мин. '.$end_sec.' сек. &nbsp;&nbsp;&nbsp;<input name="arcomage_no" type=submit value="Отозвать заявку"><br>';
			$call_can = 0;
		}
		else
		{
			echo ''.date("H:i",$call['begin']).' <input type=radio name=arcomage_arco_id value='.$call['user_id'].'> <font color=F0E68C><b><a href="http://'.DOMAIN.'/view/?userid='.$call['user_id'].'" target=_blank>'.$call['user_name'].'</a></b></font> таймаут через : '.$end_min.' мин. '.$end_sec.' сек. <br>';
			$not++;
		}
	}
	if ($count!=0 AND $not!=0) echo'<br>&nbsp;&nbsp;<input name="arcomage_call_zayava" type=submit value="Вызвать"></form>';
	if ($count==0) echo'Заявок нет.';
}

if ($call_can == 1) echo'<br><br>'.$zayavka.'';
echo'</td></tr>
<tr><td>&nbsp;<br /><br />&nbsp;</td></tr>';

$query = "SELECT view_active_users.name, view_active_users.user_id,arcomage.id,arcomage.money FROM arcomage,view_active_users WHERE view_active_users.arcomage=arcomage.id ORDER BY arcomage.id";
$selarco = myquery($query);
if (mysql_num_rows($selarco)>0)
{
	echo '<tr bgcolor=#333333><td style="color:#F0E68C">Поединки в Две Башни: Текущие</td></tr><tr><td>';
	$cur_arco = 0;
	$cur_user = 0;
	echo '<center>';
	while ($arco = mysql_fetch_array($selarco))
	{
		if ($cur_arco!=$arco['id'])
		{
			if ($cur_arco>0)
			{
				echo '<br>';
			}
			$cur_arco=$arco['id'];
			$cur_user = 0;
		}
		$cur_user++;
		if ($cur_user == 1)
		{
			echo '<font color=F0E68C><b><a href="http://'.DOMAIN.'/view/?userid='.$arco['user_id'].'" target=_blank>'.$arco['name'].'</a></b></font> играет против ';
		}
		else
		{
			echo '<font color=F0E68C><b><a href="http://'.DOMAIN.'/view/?userid='.$arco['user_id'].'" target=_blank>'.$arco['name'].'</a></b></font>&nbsp;&nbsp;<font color=#00FF80>Ставка <font color=#FF0000><b>'.$arco['money'].'</b></font> монет.</font>';
		}
	}
	echo '</td></tr>';
}

echo'</table>';

echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table><br /><br />';

OpenTable('title');

$nn=0;
echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
$sel_boy = myquery("SELECT combat.combat_type, game_maps.name AS map_name, combat.map_xpos, combat.map_ypos, combat.combat_id, combat.hod
FROM combat, view_active_users, game_maps, combat_users
WHERE view_active_users.user_id=combat_users.user_id
AND combat_users.combat_id = combat.combat_id
AND game_maps.id = combat.map_name
AND combat.time_last_hod>".(time()-3*60-20)."
AND combat.combat_id NOT IN (SELECT DISTINCT combat_id FROM combat_users WHERE npc>0)
GROUP BY combat.combat_id
ORDER BY combat.combat_type ASC , combat.map_name ASC , combat.map_xpos ASC , combat.map_ypos ASC , combat.combat_id ASC");
$nn+=mysql_num_rows($sel_boy);
if ($nn>0)
{
	echo '<tr bgcolor=333333 style="color:#F0E68C"><td><b>Текущие бои:</b></td></tr>'; 
	echo '<tr><td valign="top">';
	
	$cur_type = -1;
	while ($boy = mysql_fetch_array($sel_boy))
	{
		if ($cur_type!=$boy['combat_type'])
		{
			if ($cur_type!=-1) echo '</ul>';
			$cur_type = $boy['combat_type'];
			if ($cur_type==1) $type_str='Обычный бой';
			if ($cur_type==2) $type_str='Дуэль';
			if ($cur_type==3) $type_str='Общий бой';
			if ($cur_type==4) $type_str='Многоклановый бой';
			if ($cur_type==5) $type_str='Бой все против всех';
			if ($cur_type==6) $type_str='Бой склонностей';
			if ($cur_type==7) $type_str='Бой рас';
			if ($cur_type==8) $type_str='Турнирная дуэль';
			if ($cur_type==9) $type_str='Турнирный групповой бой';
			if ($cur_type==10) $type_str='Бой с тенью';
			if ($cur_type==11) $type_str='Турнирный хаотичный бой';
			echo'<li><b><font face="Tahoma" size=2 color=#51A8FF>'.$type_str.'</font></b></li><ul>';
		}
		$begin=0;
		echo'<li><b>'.$boy['map_name'].'&nbsp;&nbsp;&nbsp;x-'.$boy['map_xpos'].', y-'.$boy['map_ypos'].'</b><font color=#80FF00>';
		if ($begin==0 or $begin<=time()) echo'&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Бой начался&nbsp;]  ['.($boy['hod']).' ход боя]';
		else echo'&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;До начала боя осталось '.($begin-time()).' секунд&nbsp;]';
		echo'</font></li><br>
		<center>
		<table width="100%" border="0"><tr>';

		if ($cur_type!=5)
		{
			$query = "SELECT combat_users.side,game_users.name,game_har.name as race,game_users.clevel,game_users.clan_id,game_users.user_id,game_users.sklon FROM combat_users,game_users,game_har WHERE combat_users.combat_id='".$boy['combat_id']."' AND game_users.race=game_har.id AND combat_users.user_id=game_users.user_id ORDER BY combat_users.side";
			$selcomb = myquery($query);
			$cur_side = -1;
			while ($comb = mysql_fetch_array($selcomb))
			{
				if ($boy['map_name']=='Арена Хаоса')
				{
					$comb["user_id"] = 0;
					$comb["name"] = "*********";
					$comb['clan_id'] = 0;
					$comb['sklon'] = 0;
				}
				if ($cur_side!=$comb['side'])
				{
					if ($cur_side!=-1)
					{
						echo '</td>';
					}
					echo '<td valign="center">';
					$nom=0;
					$cur_side=$comb['side'];
				}
				$nom++;
				echo ''.$nom.'.  ';
				if ($boy['map_name']!='Арена Хаоса')
				{
					echo '<a href="http://'.DOMAIN.'/view/?userid='.$comb["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"  title="Инфо" width=12 height=12></a>&nbsp;';
				}                 
				if ($comb['clan_id']!=0) echo '<img src="http://'.IMG_DOMAIN.'/clan/'.$comb['clan_id'].'.gif">&nbsp;';
				print_sklon($comb);
				echo ''.$comb['name'].' ('.$comb['race'].' '.$comb['clevel'].' уровня)<br>';
			}
			echo '</td>';
		}
		else
		{
			echo '<td align="center" valign="center">';
			$sel_user = myquery("SELECT game_users.user_id,game_users.name,game_users.race,game_users.clevel,game_users.clan_id,game_har.name As race_name,game_users.user_id,game_users.sklon FROM combat_users,game_users,game_har WHERE combat_users.combat_id='".$boy['combat_id']."' AND combat_users.user_id=game_users.user_id AND game_har.id=game_users.race");
			$nom=0;
			while ($us = mysql_fetch_array($sel_user))
			{
				$nom++;
				echo '<td align="center" valign="center">';
				if ($boy['map_name']!='Арена Хаоса')
				{
					echo '<a href="http://'.DOMAIN.'/view/?userid='.$us["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"  title="Инфо" width=12 height=12></a>&nbsp;';  
					if ($us['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$us['clan_id'].'.gif">&nbsp;';
					print_sklon($us);
					echo ''.$us['name'].' ('.$us['race_name'].' '.$us['clevel'].' уровня)';
				}
				else
				{
					echo '******';
				}
				echo '</td>';
				if ($nom==3) {echo'</tr><tr>';$nom=0;}
			}
			echo '</td>';
		}
		echo'</tr></table></center>';
	}
	if ($cur_type!=-1) echo '</ul>';
	echo '</td></tr>';
}
echo '</table>';

OpenTable('close');

echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo '</td></tr></table>';

if (function_exists("save_debug")) save_debug(); 
?>