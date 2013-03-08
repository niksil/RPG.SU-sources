<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['pech'] >= 1)
{
$result = myquery("SELECT * FROM game_chat_nakaz");
$online_number = mysql_num_rows($result);

if(!isset($use))
{
	echo '<center>Сейчас поставлены печати на:';
	echo '<table cellpadding="0" cellspacing="1" border="0" width="100%" align="center">
	<tr>
	<td valign="top">
	<tr bgcolor="#006699"><td><font size="1" face="Verdana" color="#000000">Ник</font></td><td>Город</td><td>Вид печати</td><td>Адм</td><td><font size="1" face="Verdana" color="#000000">Осталось</font></td><td><font size="1" face="Verdana" color="#000000">Действие</font></td>
	</tr>';
	while ($play = mysql_fetch_array($result))
	{
		$nameuser = get_user("name",$play['user_id']);
		$namemag = get_user("name",$play['mag']); 
		echo '<tr bgcolor="#333333"><td><font size="1" face="Verdana" color="#ffffff">' . $nameuser . '</font></td><td>';
		if ($play['town']==0) echo 'Общий чат';
		else
		{
			$mysql = myquery("SELECT rustown FROM game_gorod WHERE town='".$play['town']."'");
			list($rustown) = mysql_fetch_array($mysql);
			echo $rustown;
		}
		echo '</td><td>';
		if ($play['nakaz']=='mol') echo 'Печать молчания';
		if ($play['nakaz']=='izgn') echo 'Печать изгнания';
		if ($play['nakaz']=='slep') echo 'Печать слепоты';
		if ($play['nakaz']=='prok') echo 'Печать проклятия';
		echo '</td><td>'.$namemag.'</td>
		<td width="50"><font size="1" face="Verdana" color="fffffff">';
		echo ''.ceil(($play['date_zak']-time())/60).' мин';
		echo '</font></td><td><input type="button" value="Снять печать" onClick="location.href=\'admin.php?opt=main&option=unpech&unpech_name='.urlencode($nameuser).'&use='.$play['id'].'\'"></td>
		</tr>';
	};
	echo '</table>';
}
else
{
	$upda=myquery("delete from game_chat_nakaz where id='$use'");
	echo 'Печать снята';
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
	 VALUES (
	 '".$char['name']."',
	 'Снял печать с игрока: <b>".$unpech_name."</b>',
	 '".time()."',
	 '".$da['mday']."',
	 '".$da['mon']."',
	 '".$da['year']."')")
		 or die(mysql_error());
	echo '<meta http-equiv="refresh" content="2;url=admin.php?option=unpech&opt=main">';
}
}

if (function_exists("save_debug")) save_debug(); 

?>