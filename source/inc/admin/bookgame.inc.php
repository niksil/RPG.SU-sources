<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['quest'] >= 1)
{
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		$pm=myquery("SELECT COUNT(*) FROM bookgame");
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=25;
		$allpage=ceil(mysql_result($pm,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		echo "<table border=0 cellspacing=3 cellpadding=3>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bookgame&new>Добавить книгу-игру</a></td></tr>";
		echo "<tr bgcolor=#333333><td>ID</td><td>Название</td><td></td></tr>";
		$qw=myquery("SELECT * FROM bookgame order BY id ASC limit ".(($page-1)*$line).", $line");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td><a href=admin.php?opt=main&option=bookgame&edit='.$ar['id'].'>'.$ar['id'].'</a></td>
			<td>'.$ar['name'].'</td>
			<td><a href=admin.php?opt=main&option=bookgame&delete='.$ar['id'].'>Удалить запись</a></td>
			</tr>';
		}
		echo'</table>';
		$href = '?opt=main&option=bookgame&';
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
	}

	if(isset($edit))
	{
		if (!isset($save))
		{
			$qw=myquery("SELECT * FROM bookgame where id='$edit'");
			$ar=mysql_fetch_array($qw);
			echo'<form action="" method="post">
			Название: <input type="text" name="name" size=50 value="'.$ar['name'].'"><br><br>
			<input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
			
			//выводим список страниц книги
			echo '<br /><br /><br /><br /><a href="admin.php?opt=main&option=bookgamepage&book='.$ar['id'].'">Редактировать страницы книги</a>';
		}
		else
		{
			echo'Запись изменена';
			$up=myquery("update bookgame set name='".htmlspecialchars($name)."' where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Изменил название книги-игры $edit на : <b>".htmlspecialchars($name)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bookgame">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post">
			Название: <input name=name size=50 value=""><br><br>
			<input name="save" type="submit" value="Добавить запись"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Запись добавлена';
			$up=myquery("insert into bookgame (name) VALUES ('".htmlspecialchars($name)."')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Добавил книгу-игру : <b>".htmlspecialchars($name)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bookgame">';
		}
	}

	if(isset($delete))
	{
		echo'Запись удалена';
		$text = mysqlresult(myquery("SELECT name FROM bookgame WHERE id='$delete'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Удалил книгу-игру : <b>".htmlspecialchars($text)."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		$up=myquery("delete from bookgame where id='$delete'");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bookgame">';
	}

}
if (function_exists("save_debug")) save_debug(); 
?>