<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['unban'] >= 1)
{
if (!isset($use))
{
	$result = myquery("SELECT * FROM game_ban WHERE time<".(time()+7*24*60*60)."");

	echo '<center>Сейчас в бане находятся:';
	echo '<input class="button" type="button" value="Обычные баны" onclick="document.getElementById(\'ban\').style.display=\'block\';document.getElementById(\'longban\').style.display=\'none\';"><input class="button" type="button" value="ДООЛГИЕ баны" onclick="document.getElementById(\'ban\').style.display=\'none\';document.getElementById(\'longban\').style.display=\'block\';">';
	echo '<div id="ban" style="display:block;">';
	echo '<table cellpadding="0" cellspacing="1" border="0" width="100%" align="center">
	<tr>
	<td valign="top">
	<tr bgcolor="#006699"><td><font size="1" face="Verdana" color="#000000">Ник</font></td><td>Тип бана</td><td>Причина</td><td>Адм</td><td><font size="1" face="Verdana" color="#000000">Осталось</font></td><td><font size="1" face="Verdana" color="#000000">Действие</font></td>
	</tr>';
	while ($play = mysql_fetch_array($result))
	{
		$player=myquery("select * from game_users where user_id='".$play['user_id']."'");
		if (!mysql_num_rows($player)) $player=myquery("select * from game_users_archive where user_id='".$play['user_id']."'");
		$player=mysql_fetch_array($player);
		echo '<tr bgcolor="#333333"><td><font size="1" face="Verdana" color="#ffffff">' . $player['name'] . '</font></td><td>';
		if ($play['type']<2) echo 'Обычный бан';
		if ($play['type']==2) echo 'Проклятие';
		if ($play['type']==3) echo 'Предупреждение';
		echo '</td><td>'.$play['za'].'</td><td>'.$play['adm'].'</td>
		<td width="50"><font size="1" face="Verdana" color="fffffff">';

		if ($play['time']<0)
				echo 'Навечно';
		else
				echo ''.ceil(($play['time']-time())/60).' мин';

		echo '</font></td><td><input type="button" value="Снять бан" onClick="location.href=\'admin.php?opt=main&option=unban&unban_name='.$player['name'].'&use='.$play['id'].'\'"></td>
		</tr>';
	};
	echo '</table>';
	echo '</div>';


	$result = myquery("SELECT * FROM game_ban WHERE time>=".(time()+7*24*60*60)."");
	echo '<div id="longban" style="display:none;">';
	echo '<table cellpadding="0" cellspacing="1" border="0" width="100%" align="center">
	<tr>
	<td valign="top">
	<tr bgcolor="#006699"><td><font size="1" face="Verdana" color="#000000">Ник</font></td><td>Тип бана</td><td>Причина</td><td>Адм</td><td><font size="1" face="Verdana" color="#000000">Осталось</font></td><td><font size="1" face="Verdana" color="#000000">Действие</font></td>
	</tr>';
	while ($play = mysql_fetch_array($result))
	{
		$player=myquery("select * from game_users where user_id='".$play['user_id']."'");
		if (!mysql_num_rows($player)) $player=myquery("select * from game_users_archive where user_id='".$play['user_id']."'");
		$player=mysql_fetch_array($player);
		echo '<tr bgcolor="#333333"><td><font size="1" face="Verdana" color="#ffffff">' . $player['name'] . '</font></td><td>';
		if ($play['type']<2) echo 'Обычный бан';
		if ($play['type']==2) echo 'Проклятие';
		if ($play['type']==3) echo 'Предупреждение';
		echo '</td><td>'.$play['za'].'</td><td>'.$play['adm'].'</td>
		<td width="50"><font size="1" face="Verdana" color="fffffff">';

		if ($play['time']<0)
				echo 'Навечно';
		else
				echo ''.ceil(($play['time']-time())/60).' мин';

		echo '</font></td><td><input type="button" value="Снять бан" onClick="location.href=\'admin.php?opt=main&option=unban&unban_name='.$player['name'].'&use='.$play['id'].'\'"></td>
		</tr>';
	};
	echo '</table>';
	echo '</div>';


	//New by ITF
	include("inc/admin/unprison.inc.php");
}
else
{
	$usrid = mysql_result(myquery("SELECT user_id FROM game_ban WHERE id='$use'"),0,0);
	$usrname=get_user("name",$usrid);
	$upda=myquery("delete from game_ban where id='$use'");
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
	 VALUES (
	 '".$char['name']."',
	 'Снял бан с игрока <b>".$usrname."</b>',
	 '".time()."',
	 '".$da['mday']."',
	 '".$da['mon']."',
	 '".$da['year']."')")
		 or die(mysql_error());
	echo 'Бан удален <meta http-equiv="refresh" content="1;url=admin.php?option=unban&opt=main">';
}

}

if (function_exists("save_debug")) save_debug(); 

?>