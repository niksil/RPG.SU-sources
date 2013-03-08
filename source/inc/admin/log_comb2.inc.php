<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['log_war_today'] >= 1)
{
	if (isset($log_id))
	{
		$log_id = (int)$log_id;
		$result = myquery("SELECT * FROM game_combats_log WHERE boy=".$log_id."");
		while($log=mysql_fetch_array($result))
		{
			if ($log['type']==1) echo' <b>Обычный бой</b><br>';
			if ($log['type']==2) echo' <b>Дуэль</b><br>';
			if ($log['type']==3) echo' <b>Общий бой</b><br>';
			if ($log['type']==4) echo' <b>Многоклановый бой</b><br>';
			if ($log['type']==5) echo' <b>Все против всех</b><br>';
			if ($log['type']==6) echo' <b>Бой склонностей</b><br>';
			if ($log['type']==7) echo' <b>Бой рас</b><br>';
			if ($log['type']==8) echo' <b>Турнирная дуэль</b><br>';
			if ($log['type']==9) echo' <b>Турнирный групповой бой</b><br>';
			if ($log['type']==10) echo' <b>Бой с тенью</b><br>';
			if ($log['type']==11) echo' <b>Турнирный хаотичный бой</b><br>';
			echo 'Всего ходов: '.$log['hod'].'<br><br> '; 
			$path="".PATCH."/combat/log";
			
			echo show_combat_log($log_id);
			if ($adm['log_war'] >= 2)
			{
				$sel_chat = myquery("SELECT * FROM game_combats_chat WHERE boy=$log_id ORDER BY id");
				while ($ch = mysql_fetch_array($sel_chat))
				{
					echo'<br>'.$ch['chat'].'';
				}
			}
			echo'<br>';
			$file2 = $path."/".$log_id."_stat.dat";
			if (file_exists($file2)) {include($file2);};
		}
	}
	else
	{
		echo 'Логи боев:<br><hr><br>';

		$view=myquery("(select game_combats_log.boy,game_combats_log.time,game_combats_log.hod,game_users.name from game_combats_log,game_users,game_combats_users WHERE game_combats_log.time>=".(time()-24*60*60)." AND game_combats_log.time<=".time()." AND game_combats_users.boy=game_combats_log.boy AND game_users.user_id=game_combats_users.user_id) UNION (select game_combats_log.boy,game_combats_log.time,game_combats_log.hod,game_users_archive.name from game_combats_log,game_users_archive,game_combats_users WHERE game_combats_log.time>=".(time()-24*60*60)." AND game_combats_log.time<=".time()." AND game_combats_users.boy=game_combats_log.boy AND game_users_archive.user_id=game_combats_users.user_id)");
		$i=01;
		$cur_boy = 0;
		while ($use=mysql_fetch_array($view))
		{
			if ($cur_boy!=$use['boy'])
			{
				if ($cur_boy>0)
				{
					echo '</a><br>';
				}
				echo''.$i.'. <a href="admin.php?opt=main&option=log_war2&log_id='.$use['boy'].'">'.date("d-m-y",$use['time']).' '.date("H:i:s",$use['time']).' <b>'.$use['hod'].'</b> ходов ';
				$i++;
				$cur_boy = $use['boy'];
			}
			echo '['.$use['name'].'] ';
		}
		if ($cur_boy>0)
		{
			echo '</a><br>';
		}
		echo'</div>';
	}
}

if (function_exists("save_debug")) save_debug(); 

?>