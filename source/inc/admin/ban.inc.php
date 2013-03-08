<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['ban'] >= 1)
{
	$current_time = time();
	$online_range = $current_time - 300;
	$result = myquery("SELECT * FROM game_users_active WHERE last_active > $online_range ORDER BY host");
	$online_number = mysql_num_rows($result);

	if (!isset($user1))
	{
		echo '<div id="content" onclick="hideSuggestions();"><center>Выберите игрока<br>';
		echo 'В игре '.$online_number.' человек</center>
		<table cellpadding="0" cellspacing="1" border="0" width="60%" align="center">
		<tr>
		<td valign="top">
		<tr bgcolor="#006699"><td width="50"><font size="1" face="Verdana" color="#000000">Ник</font></td><td width="50"><font size="1" face="Verdana" color="#000000">Хост</font></td>
		<td></td><td></td>
		</tr>';
		while ($pl = mysql_fetch_array($result))
		{
			$selpl = myquery("SELECT name FROM game_users WHERE user_id='".$pl['user_id']."'");
			if (!mysql_num_rows($selpl)) $selpl = myquery("SELECT name FROM game_users_archive WHERE user_id='".$pl['user_id']."'"); 
			$player = mysql_fetch_array($selpl);
			echo '<tr bgcolor="#333333"><td><font size="1" face="Verdana" color="#ffffff">' . $player['name'] . '</font></td><td><font size="1" face="Verdana" color="#ffffff">' . number2ip($pl['host']) . '</font></td>
			<td><button onClick="location.href=\'admin.php?opt=main&option=ban&nic='.$player['name'].'&user1='.$pl['user_id'].'\'">По  Нику</button></td>
			<td><button onClick="location.href=\'admin.php?opt=main&option=ban&nic='.$player['name'].'&user1='.$pl['user_id'].'&host_ban='.$pl['host'].'\'">По IP</button></td>
			</tr>';
		}

		if (!isset($see))
		{
			echo'<center><form action="" method="post">
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>Имя: <input name="user2" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td><td><input name="submit" type="submit" value="Забанить"></td></tr>
			<input name="see" type="hidden" value="">
			</form>';
		}
		else
		{
			$prov=myquery("select user_id,name from game_users where name='".$user2."'");
			if (!mysql_num_rows($prov)) $prov=myquery("select user_id,name from game_users_archive where name='".$user2."'");
			if (mysql_num_rows($prov))
			{
				$player=mysql_fetch_array($prov);
				$pl = mysql_fetch_array(myquery("SELECT * FROM game_users_active WHERE user_id='".$player['user_id']."'"));
				echo'Ты '.echo_sex('выбрал','выбрала').': <font color=ff0000><b>'.$user2.'</b></font><br><br>Забанить по: &nbsp;<button onClick="location.href=\'admin.php?opt=main&option=ban&nic='.$player['name'].'&user1='.$player['user_id'].'\'">По Нику</button>
				<button onClick="location.href=\'admin.php?opt=main&option=ban&nic='.$player['name'].'&user1='.$player['user_id'].'&host_ban='.$pl['host'].'\'">По IP</button>';
				echo'<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
			}
			else
			{
				echo'Игрок не найден.';
			}
		}

		echo '</table></div><script>init();</script>';
	}

	if (isset($user1))
	{
		if (!isset($bantype))
		{
			if (!isset($host_ban)) $host_ban=0;
			echo '<center>Ты '.echo_sex('выбрал','выбрала').' <font size=2 color=#FF0000><b>'.$nic.'</b></font><br><br>Выбери вид наказания:<br><br>
			<button onClick="location.href=\'admin.php?opt=main&option=ban&bantype=ban&user1='.$user1.'&host_ban='.$host_ban.'\'">Забанить</button>&nbsp;&nbsp;&nbsp;&nbsp;
			 <button onClick="location.href=\' admin.php?opt=main&option=prison&nic='.$nic.'&user1='.$user1.'\'">Отправить на каторгу</button>&nbsp;&nbsp;&nbsp;&nbsp;           
			<button onClick="location.href=\'admin.php?opt=main&bantype=chaos&option=ban&user1='.$user1.'&host_ban='.$host_ban.'\'">Поставить проклятие</button>&nbsp;&nbsp;&nbsp;&nbsp;
			<button onClick="location.href=\'admin.php?opt=main&bantype=alert&option=ban&user1='.$user1.'&host_ban='.$host_ban.'\'">Поставить предупреждение</button>&nbsp;&nbsp;&nbsp;&nbsp;
			<br><br>';
		}
		else
		{
			if (!isset($see111))
			{
				echo "<form name=frm method=post>";

				 if ($bantype=='ban')
				 {
					 echo'Пункт: ';

					 echo'<select name="zakon">';
					 $result = myquery("SELECT * FROM game_zakon ORDER BY id");
					 while($map=mysql_fetch_array($result))
					 {
						 echo '<option value='.$map['id'].'>№'.$map['id'].'. '.$map['name'].' ('.$map['time'].' минут)</option>';
					 }
					 echo '</select>';
					 echo '<br>Ты можешь указать произвольное время бана (в минутах): <input name="bantime" size=10 type="text">';
				 }
				 else
				 {
					echo 'Укажи время наказания (в минутах): <input name="bantime" size=10 type="text">';
				 }

				 echo'<br><textarea name="za" cols="70" class="input" rows="8"></textarea><br><br>';
				 if ($bantype=='ban')
				 {
					echo 'Поставить бан навсегда <input name="forever" type="checkbox" value="1"><br><br>';
				 }
				 echo'<input name="submit" type="submit" value="';
				 if ($bantype=='ban') echo 'Забанить';
				 elseif ($bantype=='chaos') echo 'Поставить проклятие';
				 elseif ($bantype=='alert') echo 'Поставить предупреждение';
				 echo '">';
				 echo'<input name="bantype" type="hidden" value="'.$bantype.'">';
				 echo'<input name="see111" type="hidden" value="">';
				 echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
				 {if (function_exists("save_debug")) save_debug(); exit;}
			 }
			 else
			 {
				if ($bantype=='ban')
				{
					$sel = myquery("SELECT * FROM game_zakon WHERE id = $zakon");
					$zak = mysql_fetch_array($sel);
					$zakon_text = '№'.$zak['id'].'. '.$zak['name'].'';
					if (isset ($forever))
					{
						$abc=time()+999999999;
						$time=-1;
					}
					else
					{
						$abc=0;
						$abc = time()+$zak['time']*60;
						$time=$zak['time']*60;

						$sel_nakaz = myquery("SELECT * FROM game_nakaz WHERE user_id=$user1 AND id_zakon=$zakon");
						$abc = $abc + mysql_num_rows($sel_nakaz)*$zak['time']*60;
						$time = $time + mysql_num_rows($sel_nakaz)*$zak['time']*60;
					}
					if (isset($bantime) and $bantime>0)
					{
						$abc = time()+$bantime*60;
						$time = $bantime*60;
					}
					else
					{
						$bantime = $zak['time']*60;
					}
				}
				else
				{
					$abc=time()+$bantime*60;
					$time = $bantime*60;
					$zakon_text = '';
					$zakon = '';
				}

				 if ($bantype=='ban') $type_ban= 'бан';
				 elseif ($bantype=='chaos') $type_ban='проклятие';
				 elseif ($bantype=='alert') $type_ban= 'предупреждение';


				 $ban_name = get_user('name',$user1);

				 if ($bantype=='ban')
				 {
					$bansel = myquery("SELECT * FROM game_ban WHERE user_id='$user1' AND type='0'");
					if (!mysql_num_rows($bansel))
					{
						 //поставим новый бан
						 if ($host_ban>0) list($host_ban) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id=$user1"));
						 $ban=myquery("insert into game_ban (user_id,time,ip,adm,za,type) values ($user1,'$abc','$host_ban','".$char['name']."','".$za."','0')");
					}
					else
					{
						 //продлим существующий бан
						 $bans = mysql_fetch_array($bansel);
						 $new_time = $bans['time']+$time;
						 $up = myquery("UPDATE game_ban SET time='$new_time' WHERE id='".$bans['id']."'");
					}
					 $ban=myquery("insert into game_nakaz (user_id,nakaz,date_nak,date_zak,adm,text,id_zakon) values ('$user1','ban','".time()."','".$time."','".$user_id."','".$za."','".$zakon."')");
				 }
				 elseif ($bantype=='chaos')
				 {
					 $ban=myquery("insert into game_ban (user_id,time,ip,adm,za,type) values ('$user1','$abc','$host','".$char['name']."','".$za."','2')");
				 }
				 elseif ($bantype=='alert')
				 {
					 $ban=myquery("insert into game_ban (user_id,time,ip,adm,za,type) values ('$user1','$abc','$host','".$char['name']."','".$za."','3')");
				 }
				 $da = getdate();
				 $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 'Поставил  ".$type_ban." на игрока: <b>".$ban_name."</b>. <br>Причина: ".$za."<br> время наказания: ".($bantime/60)." минут<br>По закону: ".$zakon_text." (пункт ".$zakon.")',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());

				 echo '<center>Сделано!</center>';
				 echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
				 echo '<meta http-equiv="refresh" content="2;url=admin.php?option=ban&opt=main">';
				 {if (function_exists("save_debug")) save_debug(); exit;}
			 }
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>