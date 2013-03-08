<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['search_users'] == 1)
{
	echo '<center>Поиск игроков:</center><br><hr><br>';

	if (!isset($user_id_search)) $user_id_search='';
	if (!isset($user_name)) $user_name='';
	if (!isset($name)) $name='';
	if (!isset($email)) $email='';
	if (!isset($STATUS)) $STATUS='';
	if (!isset($gorod)) $gorod='';
	if (!isset($hobbi)) $hobbi='';
	if (!isset($info)) $info='';
	if (!isset($GP)) $GP='';
	if (!isset($func)) $func='';
	if (!isset($host_user)) $host_user='';

	echo'<center><form action="" method="post">
	<table border="0" width="50%">
	<tr><td>User ID</td><td><input name="user_id_search" value="'.$user_id_search.'" type="text" size="35"></td></tr>
	<tr><td>Логин</td><td><input name="user_name" value="'.$user_name.'" type="text" size="35"></td></tr>
	<tr><td>Имя</td><td><input name="name" value="'.$name.'" type="text" size="35"></td></tr>
	<tr><td>email</td><td><input name="email" value="'.$email.'" type="text" size="35"></td></tr>
	<tr><td>Статус</td><td><input name="STATUS" value="'.$STATUS.'" type="text" size="35"></td></tr>
	<tr><td>Город</td><td><input name="gorod" value="'.$gorod.'" type="text" size="35"></td></tr>
	<tr><td>Хобби</td><td><input name="hobbi" value="'.$hobbi.'" type="text" size="35"></td></tr>
	<tr><td>Информация</td><td><input name="info" value="'.$info.'" type="text" size="35"></td></tr>
	<tr><td>Золото</td><td><input name="GP" value="'.$GP.'" type="text" size="35"></td></tr>
	<tr><td>func</td><td><input name="func" value="'.$func.'" type="text" size="35"></td></tr>
	<tr><td>Хост</td><td><input name="host_user" value="'.$host_user.'" type="text" size="35"></td></tr>

	<tr><td>&nbsp;</td><td></td></tr>
	<tr><td>';
	echo '<input name="search" type="submit" value="Найти">';
	echo '</table></form>';
	if (isset($search))
	{
		echo '<center><hr><br>Найденные игроки:</center><br><br>';

		$str_search='';
		$nom=0;
		if ($user_id_search!='')
		{
			$str_search.='(user_id=\''.$user_id_search.'\')';
			$nom++;
		}
		if ($user_name!='')
		{
			if ($nom>0) $str_search.=' OR ';
			$str_search.='(user_name LIKE "%'.$user_name.'%")';
			$nom++;
		}
		if ($name!='')
		{
			if ($nom>0) $str_search.=' OR ';
			$str_search.='(name LIKE "%'.$name.'%")';
			$nom++;
		}
		if ($GP!='')
		{
			if ($nom>0) $str_search.=' OR ';
			$str_search.='(GP LIKE "%'.$GP.'%")';
			$nom++;
		}
		if ($func!='')
		{
			if ($nom>0) $str_search.=' OR ';
			$str_search.='(func LIKE "%'.$func.'%")';
			$nom++;
		}

		if ($nom>0)
		{
			$select = myquery("
			(SELECT user_id,clan_id,name,clevel FROM game_users WHERE (".$str_search."))
			UNION
			(SELECT user_id,clan_id,name,clevel FROM game_users_archive WHERE (".$str_search."))")
			or die(mysql_error());

			$nom_br=0;
			echo '<table><tr>';
			while ($suser = mysql_fetch_array($select))
			{
				echo '<td><font size="2" color="#bbbbbb">';
				$data_user = mysql_fetch_array(myquery("SELECT email,last_visit FROM game_users_data WHERE user_id=".$suser['user_id'].""));
				if ($suser['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$suser['clan_id'].'.gif"> ';
				echo '<a href="http://'.DOMAIN.'/view/?userid='.$suser['user_id'].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>';
				echo'' . $suser['name']. '['.$suser['clevel'].']</font><br /><font color=#80FFFF>посл.виз.-'.date("d.m.Y H:i:s",$data_user['last_visit']).'</font><br /><font color=#FFFF80> ('.$data_user['email'].')</font><br />&nbsp;&nbsp;&nbsp;</td>';
				$nom_br++;
				if ($nom_br==4) {$nom_br=0;echo'</tr><tr>';};
			}
			echo'</tr></table>';
		}


		$str_search1='';
		$nom1=0;
		if ($STATUS!='')
		{
			if ($nom1>0) $str_search1.=' OR ';
			$str_search1.='(STATUS LIKE "%'.$STATUS.'%")';
			$nom1++;
		}
		if ($gorod!='')
		{
			if ($nom1>0) $str_search1.=' OR ';
			$str_search1.='(gorod LIKE "%'.$gorod.'%")';
			$nom1++;
		}
		if ($hobbi!='')
		{
			if ($nom1>0) $str_search1.=' OR ';
			$str_search1.='(hobbi LIKE "%'.$hobbi.'%")';
			$nom1++;
		}
		if ($info!='')
		{
			if ($nom1>0) $str_search1.=' OR ';
			$str_search1.='(info LIKE "%'.$info.'%")';
			$nom1++;
		}
		if ($email!='')
		{
			if ($nom1>0) $str_search1.=' OR ';
			$str_search1.='(email LIKE "%'.$email.'%")';
			$nom1++;
		}



		if ($nom1>0)
		{
			$select1 = myquery("SELECT clan_id,name,clevel,game_users.user_id,email,last_visit FROM game_users_data,game_users WHERE game_users_data.user_id=game_users.user_id AND (".$str_search1.")
			UNION
			SELECT clan_id,name,clevel,game_users_archive.user_id,email,last_visit FROM game_users_data,game_users_archive WHERE game_users_data.user_id=game_users_archive.user_id AND (".$str_search1.")
			")
			or die(mysql_error());


			echo 'Поиск по текстовой информации (Статус, город, инфо, хобби) (отдельный список)<br><table><tr>';
			$nom_br=0;
			while ($suser = mysql_fetch_array($select1))
			{

				echo '<td><font size="2" color="#bbbbbb">';
				if ($suser['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$suser['clan_id'].'.gif"> ';
				echo '<a href="http://'.DOMAIN.'/view/?userid='.$suser['user_id'].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>';
				echo'' . $suser['name']. '['.$suser['clevel'].']</font>&nbsp;&nbsp;&nbsp;<font color=#80FFFF>п.в.-'.date("d.m.Y H:i:s",$suser['last_visit']).'</font><font color=#FFFF80> ('.$suser['email'].')</font></td>';
				$nom_br++;
				if ($nom_br==4) {$nom_br=0;echo'</tr><tr>';};
			}
			echo'</tr></table>';
		}

		if ($host_user!='')
		{
			$select2 = myquery("
SELECT clan_id,name,clevel,game_users.user_id,email,last_visit FROM game_users_data,game_users,game_users_active WHERE game_users_data.user_id=game_users.user_id AND game_users.user_id=game_users_active.user_id AND game_users_active.host='".ip2number($host_user)."'
			UNION
			SELECT clan_id,name,clevel,game_users_archive.user_id,email,last_visit FROM game_users_data,game_users_archive,game_users_active WHERE game_users_data.user_id=game_users_archive.user_id AND game_users_archive.user_id=game_users_active.user_id AND game_users_active.host='".ip2number($host_user)."'
			")
			or die(mysql_error());


			$nom_br=0;
			echo 'Поиск по IP адресу (отдельный список)<br><table><tr>';
			while ($suser = mysql_fetch_array($select2))
			{

				echo '<td><font size="2" color="#bbbbbb">';
				if ($suser['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$suser['clan_id'].'.gif"> ';
				echo '<a href="http://'.DOMAIN.'/view/?userid='.$suser['user_id'].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>';
				echo'' . $suser['name']. '['.$suser['clevel'].']</font>&nbsp;&nbsp;&nbsp;<font color=#80FFFF>п.в.-'.date("d.m.Y H:i:s",$suser['last_visit']).'</font><font color=#FFFF80> ('.$suser['email'].')</font></td>';
				$nom_br++;
				if ($nom_br==4) {$nom_br=0;echo'</tr><tr>';};
			}
			echo'</tr></table>';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>