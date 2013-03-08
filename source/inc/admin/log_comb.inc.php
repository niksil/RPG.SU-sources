<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['log_war'] >= 1)
{
	echo '<center>';
	if (isset($user))
	{
		list($id)=mysql_fetch_array(myquery("Select user_id From game_users Where name='$user' Union Select user_id From game_users_archive Where name='$user'"));
		$check=myquery("Select combat_id from combat_users where user_id=$id");
		if (mysql_num_rows($check)==0)
		{
			echo 'Выбранный пользователь не находится сейчас в бою!';
		}
		else
		{
			list($combat_id)=mysql_fetch_array($check);
			myquery("DELETE FROM combat_users WHERE combat_id=$combat_id");
			myquery("DELETE FROM combat_actions WHERE combat_id=$combat_id");
			myquery("DELETE FROM combat_lose_user WHERE combat_id=$combat_id");
			myquery("DELETE FROM combat_users_exp WHERE combat_id=$combat_id");
			myquery("DELETE FROM combat WHERE combat_id=$combat_id");
			myquery("DELETE FROM combat_locked WHERE combat_id=$combat_id");
			myquery("DELETE FROM combat_users_state WHERE combat_id=$combat_id AND state NOT IN (3,4,7,8,9)");
			echo 'Бой игрока завершён!';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				VALUES (
				 '".$char['name']."',
				 'Завершил бой игрока <b>".$user."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
		}
	}
	$result = myquery("SELECT * FROM combat ORDER BY start_time DESC");
	while($log=mysql_fetch_array($result))
	{
		$type=$log['combat_type'];
		if ($type==1) echo '<center><b><font color=#FF0080>Обычный бой</font></b></center><br>';
		if ($type==2) echo '<center><b><font color=#FF0080>Дуэль</font></b></center><br>';
		if ($type==3) echo '<center><b><font color=#FF0080>Общий бой</font></b></center><br>';
		if ($type==4) echo '<center><b><font color=#FF0080>Многоклановый бой</font></b></center><br>';
		if ($type==5) echo '<center><b><font color=#FF0080>Все против всех</font></b></center><br>';
		if ($type==6) echo '<center><b><font color=#FF0080>Бой склонностей</font></b></center><br>';
		if ($type==7) echo '<center><b><font color=#FF0080>Бой рас</font></b></center><br>';
		if ($type==8) echo '<center><b><font color=#FF0080>Турнирная дуэль</font></b></center><br>';
		if ($type==9) echo '<center><b><font color=#FF0080>Турнирный групповой бой</font></b></center><br>';
		if ($type==10) echo '<center><b><font color=#FF0080>Бой с тенью</font></b></center><br>';
		if ($type==11) echo '<center><b><font color=#FF0080>Турнирный хаотичный бой</font></b></center><br>';

		//echo $log['message'];
		echo show_combat_log($log['combat_id'],$log['hod']);
		echo'<hr><br>';
	}
		echo'	<br/>Завершить текущий бой игрока:
			    <br/><br/><form action="admin.php?opt=main&option=log_war&user" method="post">
			    Имя: <input name="user" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)">
			    <div style="display:none;" id="scroll"><div id="suggest"></div></div>
			    <br/><input name="submit" type="submit" value="Выполнить">
		      	</form></div><script>init();</script>';
	echo'<br><br><a href="?opt=main&option=log_war">Обновить</a><br>'; 
	echo '</center>';
}

if (function_exists("save_debug")) save_debug(); 

?>