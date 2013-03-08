<?php

if (function_exists("start_debug")) start_debug();

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

set_delay_reason_id($char['user_id'],24);

echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top">';

OpenTable('title');

echo '<font face="Verdana" size="2" color="#f3f3f3"><b>Журнал игрока</b></font>';
echo '<table cellpadding="15" cellspacing="0" border="0">
<tr><td valign="top">';

QuoteTable('open','95%');
// Боевые сообщения
$result_battles = myquery("SELECT type, map_name, map_xpos, map_ypos, contents, post_time FROM game_battles WHERE attacker_id=$user_id ORDER BY post_time DESC LIMIT 5");
echo '5 последних боевых сообщений</font><br /><br />
<table cellpadding="0" cellspacing="5" border="0">';
if ($result_battles!=false AND mysql_num_rows($result_battles) > 0)
{
	while ($battle = mysql_fetch_array($result_battles))
	{
		echo '<tr><td><font color=#C0FFC0>'.date("d.m.Y : H:i:s",$battle['post_time']).'</font></td><td>'.$battle['contents'].'</td></tr>';
	}
}
echo '</table>';

QuoteTable('close');
echo '<br />';

// Крафт
QuoteTable('open','95%');
$result_craft = myquery("SELECT craft_resource.name as res_name,craft_stat.gp,craft_stat.dob,craft_stat.vip,craft_stat.dat,craft_stat.type FROM craft_stat,craft_resource,craft_build_user WHERE craft_resource.id=craft_stat.res_id AND craft_build_user.id=craft_stat.build_id AND craft_stat.user=$user_id AND (craft_stat.type='z' OR craft_stat.type='n') ORDER BY dat DESC LIMIT 5");
echo '5 последних попыток добыть ресурсы</font><br /><br />
<table cellpadding="0" cellspacing="5" border="0">';
if ($result_craft!=false AND mysql_num_rows($result_craft) > 0)
{
	while ($craft = mysql_fetch_array($result_craft))
	{
		echo '<tr><td><font color=#C0FFC0>'.date("d.m.Y : H:i:s",$craft['dat']).'</font></td><td>Ты '.echo_sex('попытался','попыталась').' добыть ресурс <b>"'.$craft['res_name'].'"</b> и ';
		if($craft['type']=='z')
		{
			echo 'тебе это удалось!';
		}
		else
		{
			echo 'тебе это не удалось!';
		}
		echo '</td></tr>';
	}
}
echo '</table>';

QuoteTable('close');

echo '<br />';

// Деньги
QuoteTable('open','95%');
$result_gp = myquery("SELECT gp,reason,timestamp FROM game_users_stat_gp WHERE user_id='".$user_id."' ORDER BY timestamp DESC LIMIT 5");
echo '5 последних операций с деньгами</font><br /><br />
<table cellpadding="0" cellspacing="5" border="0">';
if ($result_gp!=false AND mysql_num_rows($result_gp) > 0)
{
	while ($gp = mysql_fetch_array($result_gp))
	{
		echo '<tr><td><font color=#C0FFC0>'.date("d.m.Y : H:i:s",$gp['timestamp']).'</font></td><td>Твой баланс изменился на <b>"'.$gp['gp'].'"</b> по причине "'.get_GP_reason($gp['reason']).'".';
		echo '</td></tr>';
	}
}
echo '</table>';

QuoteTable('close');

echo '<br />';

// Опыт
QuoteTable('open','95%');
$result_gp = myquery("SELECT exp,reason,timestamp FROM game_users_stat_exp WHERE user_id='".$user_id."' ORDER BY timestamp DESC LIMIT 5");
echo '5 последних прибавлений опыта</font><br /><br />
<table cellpadding="0" cellspacing="5" border="0">';
if ($result_gp!=false AND mysql_num_rows($result_gp) > 0)
{
	while ($gp = mysql_fetch_array($result_gp))
	{
		echo '<tr><td><font color=#C0FFC0>'.date("d.m.Y : H:i:s",$gp['timestamp']).'</font></td><td>Опыт увеличился на <b>"'.$gp['exp'].'"</b> по причине "'.get_EXP_reason($gp['reason']).'".';
		echo '</td></tr>';
	}
}
echo '</table>';

QuoteTable('close');
echo '<br />';

//Квест для новичков
if ($char['clevel']<5)
{
	$from_jurnal = 1;
	include(getenv("DOCUMENT_ROOT")."/inc/template_intro.inc.php");
	unset($from_jurnal);
}


echo '</td></tr></table>';


OpenTable('close');

echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo '</td></tr></table>';
?>


<?
if (function_exists("save_debug")) save_debug();

?>