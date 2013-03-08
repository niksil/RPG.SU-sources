<?php
if ($adm['items'] >= 1)
{
	echo '<center>';
	if (isset($editdlit))
	{
		list($name)=mysql_fetch_array(myquery("Select name From game_items_factsheet Where id=$editdlit"));
		if (isset($new))
		{
			if ($dlit>=0)
			{
				myquery("Insert Into game_eliksir_dlit (elik_id, dlit) Values('$editdlit','$dlit')");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					 '".$char['name']."',
					 'Добавил длительность действия <b>".$dlit."</b> для эликсира: <b>".$name."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				echo 'Длительность действия эликсира успешно установлена!';
			}
			else
			{
				echo 'Длительность действия эликсира не введена или введена неверно!';
			}
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=eliksir&editdlit='.$editdlit.'">';
		}
		
		elseif (isset($edit))
		{
			if ($dlit>=0)
			{
				myquery("Update game_eliksir_dlit Set dlit=$dlit Where elik_id=$editdlit");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					 '".$char['name']."',
					 'Установил длительность действия эликсира: <b>".$name."</b>, равную <b>".$dlit."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				echo 'Длительность действия эликсира успешно обновлена!';		
			}
			else
			{
				echo 'Длительность действия эликсира не введена или введена неверно!';
			}
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=eliksir&editdlit='.$editdlit.'">';
		}
		
		elseif (isset($del))
		{
			myquery("Delete From game_eliksir_dlit Where elik_id=$editdlit");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				VALUES (
				 '".$char['name']."',
				 'Удалил длительность действия эликсира: <b>".$name."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
			echo 'Длительность действия эликсира успешно удалена!';
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=eliksir&editdlit='.$editdlit.'">';
		}
		else
		{
			
			echo '<b>'.$name.'</b><br/><br/>';
			echo 'Введите длительность действия эликсира:<br/><br/>';
			echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" >';
			$check=myquery("Select * From game_eliksir_dlit Where elik_id=$editdlit");
			if (mysql_num_rows($check)>0)
			{
				$value='Изменить длительность действия';
				$elik_par=mysql_fetch_array($check);
				echo '<form method="post" action="admin.php?opt=main&option=eliksir&editdlit='.$editdlit.'&edit">';
				echo '<tr ><td align="center" width="200">Длительность действия:</td><td><input type="text" maxlength="10" size="10" value="'.$elik_par['dlit'].'" name="dlit"></td></tr>';
			}
			else
			{
				$value='Добавить длительность действия';
				echo '<form method="post" action="admin.php?opt=main&option=eliksir&editdlit='.$editdlit.'&new">';
				echo '<tr ><td align="center" width="200">Длительность действия:</td><td><input type="text" maxlength="10" size="10" name="dlit"></td></tr>';
			}
			echo '</table>';
			echo '<br/><i>Длительность действия эликсира измеряется в секундах</i><br/>';
			echo '<br/><input type="submit" value="'.$value.'"></form><br/>';
			echo '<br/><a href="admin.php?opt=main&option=eliksir&editdlit='.$editdlit.'&del">Удалить длительность действия эликсира</a><br/><br/>';
		}
	}
	
	elseif (isset($editpar))
	{
		list($name)=mysql_fetch_array(myquery("Select name From game_items_factsheet Where id=$editpar"));
		if (isset($new))
		{
			if ($_POST['alchemist']>=0 and $_POST['clevel']>=0 and $_POST['mintime']>=0 and $_POST['maxtime']>0)
			{
				myquery("Insert Into game_eliksir_alchemist (elik_id, alchemist, clevel, mintime, maxtime) Values('$editpar','".$_POST['alchemist']."','".$_POST['clevel']."','".$_POST['mintime']."','".$_POST['maxtime']."')");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					 '".$char['name']."',
					 'Добавил параметры эликсира: <b>".$name."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				echo 'Параметры эликсира успешно добавлены!';
			}
			else
			{
				echo 'Параметры эликсира не введены или введены неверно!';
			}
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=eliksir&editpar='.$editpar.'">';
		}
		
		elseif (isset($edit))
		{
			if ($_POST['alchemist']>=0 and $_POST['clevel']>=0 and $_POST['mintime']>=0 and $_POST['maxtime']>0)
			{
				myquery("Update game_eliksir_alchemist Set alchemist='".$_POST['alchemist']."', clevel='".$_POST['clevel']."', mintime='".$_POST['mintime']."', maxtime='".$_POST['maxtime']."' Where elik_id=$editpar");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					 '".$char['name']."',
					 'Изменил параметры эликсира: <b>".$name."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				echo 'Параметры эликсира успешно обновлены!';		
			}
			else
			{
				echo 'Параметры эликсира не введены или введены неверно!';
			}
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=eliksir&editpar='.$editpar.'">';
		}
		
		elseif (isset($del))
		{
			myquery("Delete From game_eliksir_alchemist Where elik_id=$editpar");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				VALUES (
				 '".$char['name']."',
				 'Очистил параметры эликсира: <b>".$name."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
			echo 'Параметры эликсира успешно очищены!';
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=eliksir&editpar='.$editpar.'">';
		}
		else
		{
			
			echo '<b>'.$name.'</b><br/><br/>';
			echo 'Введите параметры эликсира:<br/><br/>';
			echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" >';
			$check=myquery("Select * From game_eliksir_alchemist Where elik_id=$editpar");
			if (mysql_num_rows($check)>0)
			{
				$value='Изменить параметры';
				$elik_par=mysql_fetch_array($check);
				echo '<form method="post" action="admin.php?opt=main&option=eliksir&editpar='.$editpar.'&edit">';
				echo '<tr ><td width="300" align="center">Требуемый уровень алхимика:</td><td width="50"><input type="text" size="5" maxlength="5" value="'.$elik_par['alchemist'].'" name="alchemist"></td></tr>';
				echo '<tr ><td align="center">Требуемый уровень игрока:</td><td><input type="text" maxlength="5" size="5" value="'.$elik_par['clevel'].'" name="clevel"></td></tr>';
				echo '<tr ><td align="center">Минимальное время варки:</td><td><input type="text" maxlength="5" size="5" value="'.$elik_par['mintime'].'" name="mintime"></td></tr>';
				echo '<tr ><td align="center">Максимальное время варки:</td><td><input type="text" maxlength="5" size="5" value="'.$elik_par['maxtime'].'" name="maxtime"></td></tr>';
			}
			else
			{
				$value='Добавить параметры';
				echo '<form method="post" action="admin.php?opt=main&option=eliksir&editpar='.$editpar.'&new">';
				echo '<tr ><td width="300" align="center">Требуемый уровень алхимика:</td><td width="50"><input type="text" size="5" maxlength="5" name="alchemist"></td></tr>';
				echo '<tr ><td align="center">Требуемый уровень игрока:</td><td><input type="text" maxlength="5" size="5" name="clevel"></td></tr>';
				echo '<tr ><td align="center">Минимальное время варки:</td><td><input type="text" maxlength="5" size="5" name="mintime"></td></tr>';
				echo '<tr ><td align="center">Максимальное время варки:</td><td><input type="text" maxlength="5" size="5" name="maxtime"></td></tr>';
			}
			echo '</table>';
			echo '<br/><i>Временные параметры эликсира измеряются в секундах</i><br/>';
			echo '<br/><input type="submit" value="'.$value.'"></form><br/>';
			echo '<br/><a href="admin.php?opt=main&option=eliksir&editpar='.$editpar.'&del">Очистить параметры эликсира</a><br/><br/>';
		}
	}
	
	elseif (isset($editres))
	{
		list($name)=mysql_fetch_array(myquery("Select name From game_items_factsheet Where id=$editres"));
				
		if (isset($newres))
		{
			if (isset($res_kol) and $res_kol>0 and isset($id))
			{
				$test=myquery("Select * From game_eliksir_res Where elik_id=$editres and res_id=$id");
				if (mysql_num_rows($test)==0)
				{
					list($res_name)= mysql_fetch_array(myquery("Select name From craft_resource Where id=$id"));
					myquery("Insert Into game_eliksir_res (elik_id, res_id, kol) Values('$editres','$id', '$res_kol')");
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
						VALUES (
						 '".$char['name']."',
						 'Закрепил ресурс <b>".$res_name."</b> за эликсиром: <b>".$name."</b>',
						 '".time()."',
						 '".$da['mday']."',
						 '".$da['mon']."',
						 '".$da['year']."')")
							 or die(mysql_error());
					echo 'Ресурс успешно закреплён за эликсиром!';
				}
				else
				{
					echo 'Выбранный ресурс уже закреплён за эликсиром!';
				}
			}
			else
			{
				echo 'Данные не введены или введены неверно!';
			}
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=eliksir&editres='.$editres.'">';
		}
		
		elseif (isset($delres))
		{
			myquery("Delete From game_eliksir_res Where elik_id=$editres and res_id=$delres");
			list($res_name)= mysql_fetch_array(myquery("Select name From craft_resource Where id=$delres"));
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
						VALUES (
						 '".$char['name']."',
						 'Удалил закрепление ресурса <b>".$res_name."</b> за эликсиром: <b>".$name."</b>',
						 '".time()."',
						 '".$da['mday']."',
						 '".$da['mon']."',
						 '".$da['year']."')")
							 or die(mysql_error());
			echo 'Ресурс больше не закреплён за эликсиром!'; 
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=eliksir&editres='.$editres.'">';
		}
		
		else
		{
			echo '<b>'.$name.'</b><br/><br/>';
			$check=myquery("SELECT id, name	FROM craft_resource Order by binary name");
			echo '<form method="post" action="admin.php?opt=main&option=eliksir&editres='.$editres.'&newres">';
			echo '<table border="1" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr align="center">
				 <td width="200"><b>Ресурс</b></td>
				 <td width="100"><b>Количество</b></td></tr>
				 ';
			echo '<tr align="center"><td><select name="id">';
			while ($res=mysql_fetch_array($check))
			{
				echo '<option value='.$res["id"].'>'.$res["name"].'</option>';
			}
			echo '</td>';
			echo '<td><input type="text" maxlength="10" size="10" name="res_kol"></td></tr></table>';
			echo '<br/><input type="submit" value="Добавить ресурс"></form>';
			
			$check2=myquery("Select t2.name, t1.kol, t1.res_id From game_eliksir_res as t1 
							Join craft_resource as t2 on t1.res_id=t2.id 
							Where t1.elik_id=$editres");
			if (mysql_num_rows($check2)>0)
			{
				echo '<br/><br/><b>Ресурсы эликсира:</b><br/><br/>';
				echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				 <td width="300"><b>Ресурс</b></td>
				 <td width="100"><b>Количество</b></td>
				 <td width="160"><b>Действие</b></td></tr>
				 ';
				while ($res=mysql_fetch_array($check2))
				{
					echo '<tr align="center"><td>'.$res['name'].'</td>';
					echo '<td>'.$res['kol'].'</td>';
					echo '<td><a href="admin.php?opt=main&option=eliksir&editres='.$editres.'&delres='.$res['res_id'].'">Удалить ресурс</a></td></tr>';
				}
				echo '</table><br/>';
			}
		}
	}
	
	else
	{
			echo 'Таблица эликсиров';
			echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
			 <td width="400"><b>Эликсир</b></td>
			 <td width="140"><b>Время действия</b></td>
			 <td width="120"><b>Алхимия</b></td>
			 <td width="120"><b>Ресурсы</b></td></tr>
			 ';
		 
		 $check=myquery("Select * from game_items_factsheet Where type=13 Order By id");
		 while ($elik=mysql_fetch_array($check))
		 {
			 echo '<tr align="center"><td>'.$elik['name'].'</td>';
			 echo '<td><a href="admin.php?opt=main&option=eliksir&editdlit='.$elik['id'].'">Редактировать</a></td>';
			 echo '<td><a href="admin.php?opt=main&option=eliksir&editpar='.$elik['id'].'">Редактировать</a></td>';
			 echo '<td><a href="admin.php?opt=main&option=eliksir&editres='.$elik['id'].'">Редактировать</a>
			 </td></tr>';
		 }	 
		 echo '</table>';
	}
	echo '</center>';
}
?>