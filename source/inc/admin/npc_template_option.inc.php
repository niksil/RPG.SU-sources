<?
echo '<center>';
			if (isset($delopt))
			{
				list($id)=mysql_fetch_array(myquery("Select id From game_npc_set_option Where opt_id=$delopt and npc_id=$edit"));
				myquery("Delete From game_npc_set_option_value Where id=$id");
				myquery("Delete From game_npc_set_option Where id=$id");
				echo 'Опция удалена!';
				echo '<meta http-equiv="refresh" content="2;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			elseif (isset($opt_id))
			{
				switch ($opt_id)
				{
					case 4:
								if (isset($min) and isset($max) and $min>=0 and $min<=$max)
								{
									myquery("Delete From game_npc_set_option_value Where id in (Select id From game_npc_set_option Where (opt_id=4 or opt_id=5) and npc_id=$edit)");
									myquery("Delete From game_npc_set_option Where (opt_id=4 or opt_id=5) and npc_id=$edit");
									myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
									list($new)=mysql_fetch_array(myquery("Select max(id) From game_npc_set_option"));
									myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', $min), ($new, '2', $max)");
									echo 'Опция добавлена!';
									echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
								}
								else
								{
									echo 'Введите дополнительные параметры:';
									echo '
									<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id='.$opt_id.'">
									<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
									<td width="300"><b>Параметр</b></td><td width="150"><b>Значение</b></td></tr>
									<tr align="center"><td>Минимальный урон:</td><td><input type="text" size="10" maxlength="10" name="min"></td></tr>
									<tr align="center"><td>Максимальный урон:</td><td><input type="text" size="10" maxlength="10" name="max"></td></tr>
									</table>
									<br/><input type="submit" value="Добавить параметры"></form>';;
								}								
								break;
					case 5:
								if (isset($value) and $value>0 and $value<=100)
								{
									myquery("Delete From game_npc_set_option_value Where id in (Select id From game_npc_set_option Where (opt_id=4 or opt_id=5) and npc_id=$edit)");
									myquery("Delete From game_npc_set_option Where (opt_id=4 or opt_id=5) and npc_id=$edit");
									myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
									$new=mysql_insert_id();
									myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', $value)");
									echo 'Опция добавлена!';
									echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
								}
								else
								{
									echo 'Введите дополнительные параметры:';
									echo '
									<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id='.$opt_id.'">
									<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
									<td width="300"><b>Параметр</b></td><td width="150"><b>Значение</b></td></tr>
									<tr align="center"><td>% от жизней игрока:</td><td><input type="text" size="10" maxlength="10" name="value"></td></tr>
									</table>
									<br/><input type="submit" value="Добавить параметры"></form>';;
								}		
								break;
					case 6:
								if (isset($kol) and isset($idf) and $kol>0)
								{
									myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
									$new=mysql_insert_id();
									myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', $idf), ($new, '2', $kol)");
									echo 'Опция добавлена!';
									echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
								}
								else
								{
									echo 'Введите дополнительные параметры:';
									echo '
									<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id='.$opt_id.'">
									<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
									<td width="300"><b>Параметр</b></td><td width="150"><b>Значение</b></td></tr>
									<tr align="center"><td>Призываемый бот:</td><td>';
									$npc_list=myquery("Select npc_id, npc_name From game_npc_template Order By binary npc_name");
									echo '<select name="idf">';
									while ($npc=mysql_fetch_array($npc_list))
									{
										echo '<option value='.$npc['npc_id'].'>'.$npc['npc_name'].'</option>';
									}
									echo '</select>';
									echo '</td></tr>
									<tr align="center"><td>Количество ботов:</td><td><input type="text" size="10" maxlength="10" name="kol"></td></tr>
									</table>
									<br/><input type="submit" value="Добавить параметры"></form>';;
								}		
								break;
					case 7:
								if (isset($min) and isset($max) and $min>=0 and $max<=40 and $min<=$max)
								{
									myquery("Delete From game_npc_set_option_value Where id in (Select id From game_npc_set_option Where opt_id=7 and npc_id=$edit)");
									myquery("Delete From game_npc_set_option Where opt_id=7 and npc_id=$edit");
									myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
									$new=mysql_insert_id();
									myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', $min), ($new, '2', $max)");
									echo 'Опция добавлена!';
									echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
								}
								else
								{
									echo 'Введите дополнительные параметры:';
									echo '
									<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id='.$opt_id.'">
									<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
									<td width="300"><b>Параметр</b></td><td width="150"><b>Значение</b></td></tr>
									<tr align="center"><td>Минимальный уровень:</td><td><input type="text" size="10" maxlength="10" name="min"></td></tr>
									<tr align="center"><td>Максимальный уровень:</td><td><input type="text" size="10" maxlength="10" name="max"></td></tr>
									</table>
									<br/><input type="submit" value="Добавить параметры"></form>';;
								}								
								break;
					case 9:
								if (isset($koef) and $koef>0)
								{
									myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
									$new=mysql_insert_id();
									myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', $koef)");
									echo 'Опция добавлена!';
									echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
								}
								else
								{
									echo 'Введите дополнительные параметры:';
									echo '
									<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id='.$opt_id.'">
									<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
									<td width="300"><b>Параметр</b></td><td width="150"><b>Значение</b></td></tr>
									<tr align="center"><td>Коэффициент жизней:</td><td><input type="text" size="10" maxlength="10" name="koef"></td></tr>
									</table>
									<br/><input type="submit" value="Добавить параметры"></form>';;
								}								
								break;
					case 10:
								if (isset($koef) and $koef>0)
								{
									myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
									$new=mysql_insert_id();
									myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', $koef)");
									echo 'Опция добавлена!';
									echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
								}
								else
								{
									echo 'Введите дополнительные параметры:';
									echo '
									<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id='.$opt_id.'">
									<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
									<td width="300"><b>Параметр</b></td><td width="150"><b>Значение</b></td></tr>
									<tr align="center"><td>Коэффициент маны:</td><td><input type="text" size="10" maxlength="10" name="koef"></td></tr>
									</table>
									<br/><input type="submit" value="Добавить параметры"></form>';;
								}								
								break;
					case 11:
								if (isset($koef_har) and $koef_har>0 and isset($koef_dev) and $koef_dev>=0 and isset($type))
								{
									myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
									$new=mysql_insert_id();
									myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', $type), ($new, '2', $koef_har), ($new, '3', $koef_dev)");
									echo 'Опция добавлена!';
									echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
								}
								else
								{
									echo 'Введите дополнительные параметры:';
									echo '
									<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id='.$opt_id.'">
									<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
									<td width="300"><b>Параметр</b></td><td width="150"><b>Значение</b></td></tr>
									<tr align="center"><td>Тип бота:</td><td colspan=2><select name="type">
										<option value="1">Шаблон бота "Воин-Маг"</option>
										<option value="2">Шаблон бота Мории</option>
									 </select></tr>
									<tr align="center"><td>Коэффициент характеристик:</td><td><input type="text" size="10" maxlength="10" name="koef_har"></td></tr>
									<tr align="center"><td>Коэффициент разброса:</td><td><input type="text" size="10" maxlength="10" name="koef_dev"></td></tr>
									</table>
									<br/><input type="submit" value="Добавить параметры"></form>';;
								}								
								break;
					case 12:
								if (isset($type))
								{
									myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
									$new=mysql_insert_id();
									myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', $type)");
									echo 'Опция добавлена!';
									echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
								}
								else
								{
									echo 'Введите дополнительные параметры:';
									echo '
									<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id='.$opt_id.'">
									<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
									<td width="300"><b>Параметр</b></td><td width="150"><b>Значение</b></td></tr>
									<tr align="center"><td>Тип бота:</td><td colspan=2><select name="type">
										<option value="1">Нельзя напасть на бота до окончания текущего боя</option>
										<option value="2">Нельзя напасть на бота до окончания текущего боя, если игрок уже проиграл ему</option>
									 </select></tr>
									</table>
									<br/><input type="submit" value="Добавить параметры"></form>';;
								}								
								break;								
					default:
								myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
								echo 'Опция добавлена!';
								echo '<meta http-equiv="refresh" content="1	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
								break;
				}
				
			}
			else
			{
				$check=myquery("Select * From game_npc_set_option Where npc_id=$edit Order by id");
				if (mysql_num_rows($check)>0)
				{
					echo '<b>Текущие опции бота<b><br/><br/>';
					echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
						  <td width="30"><b>Номер</b></td>
						  <td width="350"><b>Опция</b></td>	
						  <td width="150"><b>Действие</b></td></tr>
						 ';
					$i=1; 
					while ($stat=mysql_fetch_array($check))
					{
						list($name)=mysql_fetch_array(myquery("Select name From game_npc_option Where opt_id=".$stat['opt_id'].""));
						echo '<tr align="center">';
						echo '<td>'.$i.'</td>';
						echo '<td>'.$name.'';
						$check2=myquery("Select * From game_npc_set_option_value Where id=".$stat['id']."");
						if (mysql_num_rows($check2)>0)
						{
							while ($stat1=mysql_fetch_array($check2))
							{
								if ($stat['opt_id']==6 and $stat1['number']==1) 
								{
									list($npc_name)=mysql_fetch_array(myquery("Select npc_name From game_npc_template Where npc_id=".$stat1['value'].""));
									echo '<br/>Значение '.$stat1['number'].': '.$npc_name.'';
								}
								else
								{
									echo '<br/>Значение '.$stat1['number'].': '.$stat1['value'].'';
								}
							}
						}
						echo '</td>';
						echo '<td><a href="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&delopt='.$stat['opt_id'].'">Удалить опцию</a></td></tr>';
						$i++;
					} 
					echo '</table><br/><br/>';
				}
				echo 'Добавить опцию боту:';
				echo '<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt">';
				$opt_list=myquery("Select t1.* From game_npc_option as t1 Left Join game_npc_set_option as t2 on t1.opt_id=t2.opt_id and t2.npc_id=$edit Where t2.id is null Order by t1.opt_id");
				echo '<select name="opt_id">';
				while ($opt=mysql_fetch_array($opt_list))
				{
					echo '<option value='.$opt['opt_id'].'>'.$opt['name'].'</option>';
				}
				echo '</select>';
				echo '<br/><br/><input type="submit" value="Добавить"></form>';
				echo '</form>';
				echo '<br/><b><i>Все коэффициенты задаются в целых числах. <br>Значение 100 в расчётах учитывается как 1!</i></b>';
				echo '</center>';
			}
?>			