<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['mine'] >= 1)
{
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		echo "<table border=0 cellspacing=3 cellpadding=3>";
		echo "<tr bgcolor=#333333><td colspan=9 align=center><a href=admin.php?opt=main&option=obelisk&new>Добавить обелиск</a></td></tr>";
		echo "<tr bgcolor=#333333><td>Название</td><td>Тип</td><td>Позиция</td><td>Описание</td><td>Кол-во использований</td><td>Начало</td><td>Конец</td><td></td></tr>";
		$qw=myquery("SELECT * FROM game_obelisk order BY id ASC");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td><a href=admin.php?opt=main&option=obelisk&edit='.$ar['id'].'>'.$ar['name'].'</a></td>';
			switch ($ar['type'])
			{
				case 'STR':
					echo '<td>Сила</td>';
				break;
				case 'DEX':
					echo '<td>Выносливость</td>';
				break;
				case 'SPD':
					echo '<td>Мудрость</td>';
				break;
				case 'NTL':
					echo '<td>Интеллект</td>';
				break;
				case 'VIT':
					echo '<td>Защита</td>';
				break;
				case 'PIE':
					echo '<td>Ловкость</td>';
				break;
				default:
					echo '<td></td>';
				break;
			}
			echo '<td>'.@mysql_result(myquery("SELECT name FROM game_maps WHERE id=".$ar['map_name'].""),0,0).' ('.$ar['map_xpos'].', '.$ar['map_ypos'].')</td>';
			echo '<td>'.$ar['opis'].'</td>';
			echo '<td>'.$ar['count_use'].'</td>';
			echo '<td>'.date("d.m.Y H:i",$ar['time_begin']).'</td>';
			echo '<td>'.date("d.m.Y H:i",$ar['time_end']).'</td>';
			echo '<td><a href=admin.php?opt=main&option=obelisk&delete='.$ar['id'].'>Удалить обелиск</a></td>
			</tr>';
		}
		echo'</table><br><br>Внимание! Обязательно должно быть 6 обелисков - по 1 на каждую характеристику';
	}

	if(isset($edit))
	{
		if (!isset($save))
		{
			$qw=myquery("SELECT * FROM game_obelisk where id='$edit'");
			$ar=mysql_fetch_array($qw);
			echo'<form action="" method="post">
			Название: <input type=text name="name" value="'.$ar['name'].'" size=70><br>
			Описание: <textarea name="opis" cols=70 class=input rows=10>'.$ar['opis'].'</textarea><br>
			Характеристика: <select name="type">';
			echo '<option value="STR"'; if ($ar['type']=='STR') echo ' selected'; echo '>Сила</option>';
			echo '<option value="DEX"'; if ($ar['type']=='DEX') echo ' selected'; echo '>Выносливость</option>';
			echo '<option value="SPD"'; if ($ar['type']=='SPD') echo ' selected'; echo '>Мудрость</option>';
			echo '<option value="VIT"'; if ($ar['type']=='VIT') echo ' selected'; echo '>Защита</option>';
			echo '<option value="PIE"'; if ($ar['type']=='PIE') echo ' selected'; echo '>Ловкость</option>';
			echo '<option value="NTL"'; if ($ar['type']=='NTL') echo ' selected'; echo '>Интеллект</option></select><br>';
			echo 'Позиция: <select name="map_name">';
			$result = myquery("SELECT name,id FROM game_maps ORDER BY name");
			while($t=mysql_fetch_array($result))
			{
			echo '<option value="'.$t['id'].'"';
			if ($ar['map_name']==$t['id']) echo ' selected';
			echo'>'.$t['name'].'</option>';
			}
			echo '</select>  X-<input type=text name="map_xpos" value="'.$ar['map_xpos'].'" size=10>, Y-<input type=text name="map_ypos" value="'.$ar['map_ypos'].'" size=10><br>
			 <input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Обелиск изменен';
			$up=myquery("update game_obelisk set name='$name', type='$type',map_name='$map_name',map_xpos='$map_xpos',map_ypos='$map_ypos',opis='$opis' where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил обелиск: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=obelisk">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post">
			Название: <input type=text name="name" size=70><br>
			Описание: <textarea name="opis" cols=70 class=input rows=10></textarea><br>
			Характеристика: <select name="type">';
			echo '<option value="STR">Сила</option>';
			echo '<option value="DEX">Выносливость</option>';
			echo '<option value="SPD">Мудрость</option>';
			echo '<option value="VIT">Защита</option>';
			echo '<option value="PIE">Ловкость</option>';
			echo '<option value="NTL">Интеллект</option></select><br>';
			echo 'Позиция: <select name="map_name">';
			$result = myquery("SELECT name,id FROM game_maps ORDER BY name");
			while($t=mysql_fetch_array($result))
			{
			echo '<option value="'.$t['id'].'">'.$t['name'].'</option>';
			}
			echo '</select>  X-<input type=text name="map_xpos" value="0" size=10>, Y-<input type=text name="map_ypos" value="0" size=10><br>
			<input name="save" type="submit" value="Добавить обелиск"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Обелиск добавлен';
			$up=myquery("insert into game_obelisk (name,type,map_name,map_xpos,map_ypos,opis) VALUES ('$name','$type','$map_name','$map_xpos','$map_ypos','$opis')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил обелиск: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=obelisk">';
		}
	}

	if(isset($delete))
	{
		echo'Обелиск удален';
		$nazv = mysql_result(myquery("SELECT name FROM game_obelisk where id='$delete'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Удалил обелиск: <b>".$nazv."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		$up=myquery("delete from game_obelisk where id='$delete'");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=obelisk">';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>