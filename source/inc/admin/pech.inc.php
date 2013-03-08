<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['pech'] >= 1)
{
if (!isset($see))
{
	echo '<div id="content" onclick="hideSuggestions();"><form name=frm method=post>';
	echo'<b>Введите имя игрока и выберите время, печать и город:</b><br>';
	echo'Имя: <input name="userp" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>

	<select name="ptime">
	<option value="300">5 мин</option>
	<option value="900">15 мин</option>
	<option value="1800">30 мин</option>
	<option value="2700">45 мин</option>
	<option value="3600">1 час</option>
	<option value="10800">3 часа</option>
	<option value="36000">10 часов</option>
	<option value="86400">24 часа</option>
	<option value="172800">2 дня</option>
	<option value="432000">5 дней</option>
	<option value="604800">Неделя</option>
	<option value="Навсегда">Навсегда</option>
	</select>

	<select name="pech">
	<option value="mol">Печать молчания</option>
	<option value="izgn">Печать изгнания</option>
	<option value="slep">Печать слепоты</option>
	<option value="prok">Печать проклятия</option>
	</select> ';

	echo'<select name="gorod">';
	$result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY rustown");
	echo '<option value="0">Печати в чате</option>';
	while($t=mysql_fetch_array($result))
	{
		echo '<option value="'.$t['town'].'">'.$t['rustown'].'</option>';
	}
	echo '</select><br>
	<input name="submit" type="submit" value="Поставить печать"></td></tr>
	<input name="see" type="hidden" value="">
	</form></div><script>init();</script>';
}
else
{
	echo'Ты '.echo_sex('поставил','поставила').' печать мага на '.$userp.'';
	$usrid = myquery("(SELECT user_id FROM game_users WHERE name='".$userp."') UNION (SELECT user_id FROM game_users_archive WHERE name='".$userp."')");
	if (!$usrid)
		echo 'Игрок не найден';
	else
	{ 
		list($usrid) = mysql_fetch_array($usrid);   
		$ban=myquery("insert into game_chat_nakaz (id,town,user_id,nakaz,date_nak,date_zak,mag) values ('','$gorod','".$usrid."','$pech','".time()."','".($ptime+time())."','".$user_id."')");
		
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Наложил печать <b>".$pech."</b> на игрока ".$userp." сроком на ".ceil($ptime/60)."',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	}
	echo '<meta http-equiv="refresh" content="2;url=admin.php?option=pech&opt=main">';
}
}

if (function_exists("save_debug")) save_debug(); 

?>