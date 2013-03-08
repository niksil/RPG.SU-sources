<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['zakon'] >= 1)
{
	include_once('style/tinyMCE/tinyMCE_header.php');

	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=zakon&new>Добавить закон</a></td></tr>";
		echo "<tr bgcolor=#333333><td>Пункт</td><td>Название</td><td>Описание</td><td></td></tr>";
		$qw=myquery("SELECT * FROM game_zakon order BY id ASC");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td>'.$ar['id'].'</td>
			<td><a href=admin.php?opt=main&option=zakon&edit='.$ar['id'].'>'.$ar['name'].'</a></td><td>'.$ar['text'].'</td>
			<td><a href=admin.php?opt=main&option=zakon&delete='.$ar['id'].'>Удалить закон</a></td>
			</tr>';
		}
		echo'</table>';
	}

	if(isset($edit))
	{
		if (!isset($save))
		{
			$qw=myquery("SELECT * FROM game_zakon where id=$edit");
			$ar=mysql_fetch_array($qw);
			echo'<form action="" method="post"><font color=ff0000><b>Пункт №'.$ar['id'].'</b></font> <input type=text name=name value="'.$ar['name'].'" size=80><br>
			<textarea id="elm1" name="elm1" rows="15" cols="80" style="width: 100%">
			'.$ar['text'].'
			</textarea><br><br>
			Базовое время бана в минутах: <input type=text name=time value="'.$ar['time'].'" size=10><br>
			(В случае рецидива время бана будет увеличено в 2(3,4,5....) раза. <br>Поставьте 0 - если при наказании не должно быть бана)<br><br><br>
			<input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Закон изменен';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил закон: <b>".$name."</b>',
			 ".time().",
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$up=myquery("update game_zakon set name='".$_POST['name']."', text='".$_POST['elm1']."', time='".$_POST['time']."' where id=$edit");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=zakon">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post"><input type=text name=id value="" size=10><br>
			<input type=text name=name value="" size=80><br>
			<textarea id="elm1" name="elm1" rows="15" cols="80" style="width: 100%">
			</textarea>
			<br><br>
			Базовое время бана в минутах: <input type=text name=time value="" size=10><br>
			(В случае рецидива время бана будет увеличено в 2(3,4,5....) раза. <br>Поставьте 0 - если при наказании не должно быть бана)<br><br><br>
			<input name="save" type="submit" value="Добавить закон"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Закон добавлен';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил закон: <b>".$name."</b>',
			 ".time().",
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			if (isset($id) and $id!='')
				$up=myquery("insert into game_zakon (id,name,text,time) VALUES ($id,'".$_POST['name']."','".$_POST['elm1']."','".$_POST['time']."')") or die(mysql_error());
			else
				$up=myquery("insert into game_zakon (name,text,time) VALUES ('".$_POST['name']."','".$_POST['elm1']."','".$_POST['time']."')");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=zakon">';
		}
	}

	if(isset($delete))
	{
		echo'Закон удален';
			list($name) = mysql_fetch_array(myquery("SELECT name FROM game_zakon WHERE id='$delete'"));
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Удалил закон: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
		$up=myquery("delete from game_zakon where id=$delete");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=zakon">';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>