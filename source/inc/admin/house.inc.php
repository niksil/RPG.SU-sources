<?

if (function_exists("start_debug")) start_debug(); 

if ($char['clan_id']==1 or $char['name']=='mrHawk')
{
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=house&new>Добавить постройку/дом</a></td></tr>";
		echo "<tr bgcolor=#333333><td>ID</td><td>Название</td><td>Тип</td><td>Площадь</td><td>Время</td><td>Стоимость</td><td>Досок</td><td>Кам.блоков</td><td></td></tr>";
		$qw=myquery("SELECT * FROM houses_templates order BY type, id ASC");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td>'.$ar['id'].'</td>
			<td><a href=admin.php?opt=main&option=house&edit='.$ar['id'].'>'.$ar['name'].'</a></td>
			<td>'.(($ar['type']==1) ? ('дом') : ('постройка')).'</td>
			<td>'.$ar['square'].'</td><td>'.$ar['buildtime'].'</td><td>'.$ar['buildcost'].'</td><td>'.$ar['doska'].'</td><td>'.$ar['stone'].'</td>
			<td><a href=admin.php?opt=main&option=house&delete='.$ar['id'].'>Удалить постройку</a></td>
			</tr>';
		}
		echo'</table>';
	}

	if(isset($edit))
	{
		if (isset($_GET['need']))
		{
			if (isset($_GET['deleteneed']))
			{
				myquery("DELETE FROM houses_templates_need WHERE id=".$_GET['deleteneed']."");
			}
			if (isset($_POST['save']))
			{
				$add_templ = '';
				if (isset($_POST["need"]))
				{
					for ($i=0; $i<count($_POST["need"]); $i++)
					{
						$add_templ.=$_POST["need"][$i].',';
					}
				}
				if (strlen($add_templ)>0)
				{
					$add_templ = substr($add_templ,0,strlen($add_templ)-1);
					myquery("INSERT INTO houses_templates_need (build_id,need) VALUES ($edit,'$add_templ')");
				}
			}
			echo '<form action="admin.php?opt=main&option=house&edit='.$edit.'&need" method="post">';
			$selneed = myquery("SELECT * FROM houses_templates_need WHERE build_id=$edit");
			echo '<table cellspacing=2 cellpadding=2 border=1>';
			while ($need = mysql_fetch_array($selneed))
			{
				echo '<tr><td>';
				$selbuildneed = myquery("SELECT name FROM houses_templates WHERE id IN (".$need['need'].")");
				while (list($name_need)=mysql_fetch_array($selbuildneed))
				{
					echo $name_need.'<br />';
				}
				echo '</td><td><a href=admin.php?opt=main&option=house&edit='.$edit.'&need&deleteneed='.$need['id'].'>Удалить</a></td></tr>';
			}
			echo '</table>';
			echo '<select name="need[]" size=40 multiple>';
			$selbuild = myquery("SELECT id,name FROM houses_templates WHERE id<>$edit ORDER BY type ASC, id ASC");
			while ($b = mysql_fetch_array($selbuild))
			{
				echo '<option value='.$b['id'].'>'.$b['name'].'</option>';
			}
			echo '</select>';
			echo '<br /><input name="save" type="submit" value="Сохранить список"></form>';
			echo '<br /><br /><a href=admin.php?opt=main&option=house>Выйти на главную</a>';
		}
		else
		{
			if (!isset($save))
			{
				$qw=myquery("SELECT * FROM houses_templates where id='$edit'");
				$ar=mysql_fetch_array($qw);
				echo'<form action="" method="post">
				<table>
				<tr><td>Название: </td><td><input type="text" name="name" value="'.$ar['name'].'" size="100"></td></tr>
				<tr><td>Занимает площадь: </td><td><input type="text" name="square" value="'.$ar['square'].'" size="5"></td></tr>
				<tr><td>Тип (1 - дом, 2 - постройка): </td><td><input type="text" name="type" value="'.$ar['type'].'" size="5"></td></tr>
				<tr><td>min_value: </td><td><input type="text" name="min_value" value="'.$ar['min_value'].'" size="10"></td></tr>
				<tr><td>max_value: </td><td><input type="text" name="max_value" value="'.$ar['max_value'].'" size="10"></td></tr>
				<tr><td>Время строительства (сек): </td><td><input type="text" name="buildtime" value="'.$ar['buildtime'].'" size="15"></td></tr>
				<tr><td>Стоимость строительства (мон): </td><td><input type="text" name="buildcost" value="'.$ar['buildcost'].'" size="15"></td></tr>
				<tr><td>Досок для строительства: </td><td><input type="text" name="doska" value="'.$ar['doska'].'" size="15"></td></tr>
				<tr><td>Каменных блоков для строительства: </td><td><input type="text" name="stone" value="'.$ar['stone'].'" size="15"></td></tr>
				<tr><td>Требует наличия построек для <br />строительства: (одно из)</td><td>';
				$selneed = myquery("SELECT * FROM houses_templates_need WHERE build_id=$edit");
				echo '<table cellspacing=2 cellpadding=2 border=1>';
				while ($need = mysql_fetch_array($selneed))
				{
					echo '<tr><td>';
					$selbuildneed = myquery("SELECT name FROM houses_templates WHERE id IN (".$need['need'].")");
					while (list($name_need)=mysql_fetch_array($selbuildneed))
					{
						echo $name_need.'<br />';
					}
					echo '</td></tr>';
				}
				echo '</table>';
				echo '<br /><br /><a href=admin.php?opt=main&option=house&edit='.$ar['id'].'&need>Изменить список требуемых построек</a>';
				echo '</td></tr>
				<tr><td colspan="2"><input name="save" type="submit" value="Сохранить постройку"></td></tr>
				</table>
				</form>';
			}
			else
			{
				echo'Постройка изменена';
				$up=myquery("update houses_templates set name='$name',square='$square',type='$type',min_value='$min_value',max_value='$max_value',buildtime='$buildtime',buildcost='$buildcost',stone='$stone',doska='$doska' WHERE id=$edit");
				echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=house">';
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				 VALUES (
				 '".$char['name']."',
				 'Изменил постройку : <b>".$name."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
			}
		}
	}


	if(isset($_GET['new']))
	{
		if (!isset($_POST['save']))
		{
			echo'<form action="" method="post">
			<table>
			<tr><td>Название: </td><td><input type="text" name="name" value="" size="100"></td></tr>
			<tr><td>Занимает площадь: </td><td><input type="text" name="square" value="0" size="5"></td></tr>
			<tr><td>Тип (1 - дом, 2 - постройка): </td><td><input type="text" name="type" value="2" size="5"></td></tr>
			<tr><td>min_value: </td><td><input type="text" name="min_value" value="0" size="10"></td></tr>
			<tr><td>max_value: </td><td><input type="text" name="max_value" value="0" size="10"></td></tr>
			<tr><td>Время строительства (сек): </td><td><input type="text" name="buildtime" value="0" size="15"></td></tr>
			<tr><td>Стоимость строительства (мон): </td><td><input type="text" name="buildcost" value="0" size="15"></td></tr>
			<tr><td>Досок для строительства: </td><td><input type="text" name="doska" value="0" size="15"></td></tr>
			<tr><td>Каменных блоков для строительства: </td><td><input type="text" name="stone" value="0" size="15"></td></tr>
			<tr><td colspan="2"><input name="save" type="submit" value="Добавить постройку"></td></tr>
			</table></form>';
		}
		else
		{
			echo'Постройка добавлена';
			$up=myquery("insert into houses_templates (name,square,type,min_value,max_value,buildtime,buildcost,stone,doska) VALUES ('$name','$square','$type','$min_value','$max_value','$buildtime','$buildcost','$stone','$doska')");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=house">';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил постройку : <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
		}
	}

	if(isset($delete))
	{
		echo'Постройка удалена';
		list($nazv) = mysql_fetch_array(myquery("SELECT name FROM houses_templates WHERE id='$delete'"));
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Удалил постройку/дом : <b>".$nazv."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')");
		myquery("delete from houses_templates where id='$delete'");
		myquery("delete from houses_users where build_id=$delete");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=house">';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>