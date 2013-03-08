<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['tavern'] >= 1)
{
	echo'<center>';
	if (isset($new))
	{
		if (!isset($save))
		{
			echo'<div id="content" onclick="hideSuggestions();"><form action="" method="post"><table border=0>
			<tr><td>Город:</td><td>';
			echo' <select name="town">';
			$result = myquery("SELECT town,rustown FROM game_gorod WHERE rustown!='' ORDER BY rustown");
			while($map=mysql_fetch_array($result))
			{
				echo '<option value="'.$map['town'].'">'.$map['rustown'].'</option>';
			}
			echo '</select></td></tr><tr><td>Владелец:</td><td><input type=text name=vladel value="" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr>';
			echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="Добавить"><input name="save" type="hidden" value=""></td></tr>';
			echo'</table></form></div><script>init();</script>';
		}
		else
		{
			$usrid = @mysql_result(@myquery("(SELECT user_id FROM game_users WHERE name='$vladel') UNION (SELECT user_id FROM game_users_archive WHERE name='$vladel')"),0,0);
			$update=myquery("INSERT INTO game_tavern (town,vladel,hp_store,mp_store,stm_store) VALUES ('$town','$usrid',5000,5000,5000)") or die(mysql_error());
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил нового тавернщика: <b>".$vladel."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo 'Сделано<meta http-equiv="refresh" content="1;url=admin.php?option=tavern&opt=main">';
		}
	}

	if (isset($del))
	{
		echo'Владелец удален<br><br>';
		list($vladel) = mysql_fetch_array(myquery("SELECT vladel FROM game_tavern WHERE id='$del'"));
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Удалил тавернщика: <b>".$vladel."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		$update=myquery("delete from game_tavern where id='$del'");
	}


	if (isset($edit))
	{
		if (!isset($save))
		{
			$sel=myquery("select * from game_tavern where id=$edit");
			$shop=mysql_fetch_array($sel);
			$name = '';
			if ($shop['vladel']>0)
			{
				$selname = myquery("(SELECT name FROM game_users WHERE user_id='".$shop['vladel']."') UNION (SELECT name FROM game_users_archive WHERE user_id='".$shop['vladel']."')");
				if ($selname!=false AND mysql_num_rows($selname)>0)
				{
					list($name) = mysql_fetch_array($selname);
				}
			}
			echo'<form action="" method="post"><table border=0>
			<tr><td>Город:</td><td>';
			echo' <select name="town">';
			$result = myquery("SELECT town,rustown FROM game_gorod WHERE rustown!='' ORDER BY rustown");
			while($map=mysql_fetch_array($result))
			{
			echo '<option value="'.$map['town'].'"';
			if ($map['town'] == $shop['town']) echo ' selected';
			echo'>'.$map['rustown'].'</option>';
			}
			echo '</select></td></tr>
			<tr><td>Владелец:</td><td><input type=text name="vladel" value='.$name.'></td></tr>
			<tr><td>Запасы HP:</td><td><input type=text name="hp_store" value='.$shop['hp_store'].'></td></tr>
			<tr><td>Запасы MP:</td><td><input type=text name="mp_store" value='.$shop['mp_store'].'></td></tr>
			<tr><td>Запасы STM:</td><td><input type=text name="stm_store" value='.$shop['stm_store'].'></td></tr>';
			echo '<tr><td>Новость:</td><td><textarea name=info cols=70 class=input rows=8>'.$shop['info'].'</textarea></td></tr>';
			echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value=""></td></tr>';
			echo'</table>';
		}
		else
		{
			echo'Владелец изменен';
			$usrid = get_user("user_id",$vladel,1);
			if ($usrid!="~~~") 
			{
				$up=myquery("update game_tavern set town='$town',info='$info', vladel='$usrid', hp_store='$hp_store', mp_store='$mp_store', stm_store='$stm_store'  where id='$edit'");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				 VALUES (
				 '".$char['name']."',
				 'Изменил тавернщика: <b>".$vladel."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
			}
			else
			{
				echo 'Игрок не найден!';
			}
			echo '<meta http-equiv="refresh" content="1;url=admin.php?option=tavern&opt=main">';
		}
	}

	echo'<br><a href="?opt=main&option=tavern&new">Добавить владельца</a> | <a href="?opt=main&option=tavern">Главная</a></center>';

	if(!isset($edit) and !isset($new))
	{
		echo'<table border=0 width=70% align=center><tr bgcolor=#333333><td>Город</td><td>Владелец</td><td>Доход</td><td>HP</td><td>MP</td><td>STM</td><td></td><td></td></tr>';
		$sel=myquery("SELECT a.vladel, a.dohod, a.hp_store, a.mp_store, a.stm_store, a.id, b.rustown FROM game_tavern AS a, game_gorod AS b WHERE a.town = b.town ORDER BY binary b.rustown ASC");
		$i=0;
		while($shop=mysql_fetch_array($sel))
		{
			$i++;
			$name = '';
			if ($shop['vladel']>0)
			{
				$selname = myquery("(SELECT name FROM game_users WHERE user_id='".$shop['vladel']."') UNION (SELECT name FROM game_users_archive WHERE user_id='".$shop['vladel']."')");
				if ($selname!=false AND mysql_num_rows($selname)>0)
				{
					list($name) = mysql_fetch_array($selname);
				}
			}
			//$rustown = @mysql_result(@myquery("SELECT rustown FROM game_gorod WHERE town='".$shop['a.town']."'"),0,0);
			if ($i==1) {echo '<tr bgcolor=#330000>';}
			else {echo '<tr bgcolor=#333333>'; $i=0;}
			echo'<td>'.$shop['rustown'].'</td><td>'.$name.'</td><td>'.$shop['dohod'].'</td><td>'.$shop['hp_store'].'</td><td>'.$shop['mp_store'].'</td><td>'.$shop['stm_store'].'</td><td><a href="?opt=main&option=tavern&edit='.$shop['id'].'">Редактировать</a></td><td><a href="?opt=main&option=tavern&del='.$shop['id'].'">Удалить</a></td></tr>';
		}
		echo'</table>';
	}
}

if (function_exists("save_debug")) save_debug(); 

?>