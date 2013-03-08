<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['quest'] >= 1)
{
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		$pm=myquery("SELECT count(*) FROM game_quest");
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=25;
		$allpage=ceil(mysql_result($pm,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		echo "<table border=0 cellspacing=3 cellpadding=3>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=quest&new>Добавить запись</a></td></tr>";
		echo "<tr bgcolor=#333333><td>ID</td><td>Имя квеста</td><td>Карта</td><td>Уровень игрока</td><td>Файл</td><td></td></tr>";
		$qw=myquery("SELECT * FROM game_quest order BY id ASC limit ".(($page-1)*$line).", $line");
		while($ar=mysql_fetch_array($qw))
		{
			$selmap = myquery("SELECT name FROM game_maps WHERE id='".$ar['map_name']."'");
			if ($selmap!=false AND mysql_num_rows($selmap)>0)
			{
				$map = mysql_result($selmap,0,0);
			}
			else
			{
				$map = '';
			}
			echo'<tr>
			<td><a href=admin.php?opt=main&option=quest&edit='.$ar['id'].'>'.$ar['id'].'</a></td>
			<td>'.$ar['name'].'</td>
			<td>'.$map.' (x-'.$ar['map_xpos'].', y-'.$ar['map_ypos'].')</td>
			<td>'.$ar['min_clevel'].'<=Уровень<='.$ar['max_clevel'].'</td>
			<td>'.$ar['filename'].'</td>
			<td><a href=admin.php?opt=main&option=quest&delete='.$ar['id'].'>Удалить запись</a></td>
			</tr>';
		}
		echo'</table>';
		$href = '?opt=main&option=quest&';
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
	}

	if(isset($edit))
	{
		if (!isset($save))
		{
			$qw=myquery("SELECT * FROM game_quest where id='$edit'");
			$ar=mysql_fetch_array($qw);
			echo'<form action="" method="post">
			Название квеста: <input type="text" name="namequest" value="'.$ar['name'].'" size=40 maxsize=200><br><br>
			Координаты начала квеста: <select name="map_name_quest">';
			$mapsel = myquery("SELECT * FROM game_maps ORDER BY name");
			while ($map = mysql_fetch_array($mapsel))
			{
				echo '<option value='.$map['id'].'';
				if ($ar['map_name']==$map['id']) echo ' selected';
				echo '>'.$map['name'].'</option>';
			}
			echo '</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  x- <input type="text" name="map_xpos" value='.$ar['map_xpos'].' size=4 maxsize=3>
			  y- <input type="text" name="map_ypos" value='.$ar['map_ypos'].' size=4 maxsize=3><br>
			Уровни игроков для квеста: <input type="text" name="min_clevel" value='.$ar['min_clevel'].' size=3 maxsize=2> <= УРОВЕНЬ ИГРОКА <= <input type="text" name="max_clevel" value='.$ar['max_clevel'].' size=3 maxsize=2><br>
			Имя файла модуля квеста:
			<select name="filename">';
			$dh = opendir('quest/');
			$list='';
			while($file = readdir($dh))
			{
				if ($file=='.') continue;
				if ($file=='..') continue;
				$selec = "";
				if ($file == $ar['filename']) $selec = "selected";
				$list .= "<option value=\"$file\" \"$selec\">$file</option>\n";
				$lastFile = $file;
			}
			echo $list;
			echo'</select><br>
			Начальная фраза квеста: <br><textarea name="begin" cols=60 rows=25>'.$ar['begin'].'</textarea><br><br>
			Кнопка на вход в квест: <input type="text" name="vhod" value="'.$ar['vhod'].'" size=40 maxsize=200><br><br>

			<input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Запись изменена';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил квест № <b>".$edit." (".$namequest.")</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$up=myquery("update game_quest set map_name='$map_name_quest', map_xpos='$map_xpos', map_ypos='$map_ypos', filename='$filename', min_clevel='$min_clevel', max_clevel='$max_clevel', begin='$begin', vhod='$vhod', name='$namequest' where id='$edit'");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=quest">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post">
			Название квеста: <input type="text" name="namequest" size=40 maxsize=200><br><br>
			Координаты начала квеста: <select name="map_name">';
			$mapsel = myquery("SELECT * FROM game_maps ORDER BY name");
			while ($map = mysql_fetch_array($mapsel))
			{
				echo '<option value='.$map['id'].'';
				echo '>'.$map['name'].'</option>';
			}
			echo '</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  x- <input type="text" name="map_xpos" size=4 maxsize=3>
			  y- <input type="text" name="map_ypos" size=4 maxsize=3><br>
			Уровни игроков для квеста: <input type="text" name="min_clevel" size=3 maxsize=2> <= УРОВЕНЬ ИГРОКА <= <input type="text" name="max_clevel" size=3 maxsize=2><br>
			Имя файла модуля квеста:
			<select name="filename">';
			$dh = opendir('quest/');
			$list = '';
			while($file = readdir($dh))
			{
				if ($file=='.') continue;
				if ($file=='..') continue;
				$list .= "<option value=\"$file\">$file</option>\n";
				$lastFile = $file;
			}
			echo $list;
			echo'</select><br>
			Начальная фраза квеста: <br><textarea name="begin" cols=60 rows=25></textarea><br><br>
			Кнопка на вход в квест: <input type="text" name="vhod" size=40 maxsize=200><br><br>

			<input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Запись добавлена';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил новый квест <b>".$namequest."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$up=myquery("insert into game_quest set map_name='$map_name', map_xpos='$map_xpos', map_ypos='$map_ypos', filename='$filename', min_clevel='$min_clevel', max_clevel='$max_clevel', begin='$begin', vhod='$vhod', name='$namequest'");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=quest">';
		}
	}

	if(isset($delete))
	{
		echo'Запись удалена';
		$quest = mysql_fetch_array(myquery("SELECT * FROM game_quest WHERE id='$delete'"));
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Удалил квест №<b>".$quest['id']." (".$quest['name'].")</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		$up=myquery("delete from game_quest where id='$delete'");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=quest">';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>