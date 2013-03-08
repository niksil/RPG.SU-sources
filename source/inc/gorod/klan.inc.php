<?

if (function_exists("start_debug")) start_debug(); 

$img='http://'.IMG_DOMAIN.'/race_table/elf/table';
if (!isset($edit)) $edit='';
if (!isset($alies)) $alies='';
if (!isset($new)) $new='';
if (!isset($izgn)) $izgn='';
if (!isset($edit_user)) $edit_user='';
if (!isset($user_zvanie)) $user_zvanie='';
if (!isset($user_rating)) $user_rating='';
if (!isset($add)) $add='';
if (!isset($confirm)) $confirm='';
if (!isset($del_confirm)) $del_confirm='';
if (!isset($save_zam)) $save_zam='';
if (!isset($save_glava)) $save_glava='';
if ($town!=0)
{

	
	if (isset($town_id) AND $town_id!=$town)
	{
		echo'Ты находишься в другом городе!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти в город" onClick=location.href="town.php">&nbsp;&nbsp;&nbsp;';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
	//echo'<img src="http://'.IMG_DOMAIN.'/clan/screen.jpg"><br><hr>';
	$lev='15';
	$gpc='5000';
	$width='100%';
	$height='100%';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center;" background="'.$img.'_mm.gif" valign="top" width="'.$width.'" height="'.$height.'">';

	if ($confirm!='')
	{
		$sel_clan = myquery("SELECT nazv,clan_id,raz FROM game_clans WHERE clan_id='".$confirm."'");
		$clan = mysql_fetch_array($sel_clan);
		if ($clan['raz']==0)
		{
			$sel_reg = myquery("SELECT * FROM game_users_clan_reg WHERE clan_id='".$confirm."' AND user_id='".$user_id."'");
			if (mysql_num_rows($sel_reg))
			{
				$up = myquery("DELETE FROM game_users_clan_reg WHERE user_id='".$user_id."'");
				$up = myquery("UPDATE game_users SET clan_id='".$confirm."' WHERE user_id='".$user_id."'");
				echo '<center><font face=verdana,tahoma,arial size=3 color=#3DEC17 ><b>Ты '.echo_sex('подтвердил','подтвердила').' заявку на принятие тебя в клан<br><img border=0 src="http://'.IMG_DOMAIN.'/clan/'.$clan['clan_id'].'.gif">&quot;'.$clan['nazv'].'&quot;</font></center>';
				echo '<meta http-equiv="refresh" content="3;url=town.php">';
				$da = getdate();
				myquery("INSERT INTO game_clans_vozrast (clan_id,month,year,vozrast,user_id) VALUES ($confirm,".$da['mon'].",".$da['year'].",".$da['mday'].",$user_id)");
			}
		}

		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if ($del_confirm!='')
	{
		$sel_clan = myquery("SELECT nazv,clan_id,raz FROM game_clans WHERE clan_id='".$del_confirm."'");
		$clan = mysql_fetch_array($sel_clan);
		if ($clan['raz']==0)
		{
			$sel_reg = myquery("SELECT * FROM game_users_clan_reg WHERE clan_id='".$del_confirm."' AND user_id='".$user_id."'");
			if (mysql_num_rows($sel_reg))
			{
				$up = myquery("DELETE FROM game_users_clan_reg WHERE clan_id='".$del_confirm."' AND user_id='".$user_id."'");
				echo '<center><font face=verdana,tahoma,arial size=3 color=#3DEC17 ><b>Ты '.echo_sex('удалил','удалила').' заявку на принятие тебя в клан<br><img border=0 src="http://'.IMG_DOMAIN.'/clan/'.$clan['clan_id'].'.gif">&quot;'.$clan['nazv'].'&quot;</font></center>';
				echo '<meta http-equiv="refresh" content="3;url=town.php">';
			}
		}

		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}


	$select=myquery("select * from game_clans where (glava='".$char['user_id']."' OR zam1='".$char['user_id']."' OR zam2='".$char['user_id']."' OR zam3='".$char['user_id']."') and raz='0' AND clan_id = '".$char['clan_id']."'");
	
	if (mysql_num_rows($select))
	{
        $cl = mysql_fetch_array($select);
		if ($edit!='')
		{
			if (!isset($_POST['see_clan_town']))
			{
				echo "<form name=frm method=post enctype=\"multipart/form-data\">";
				echo '
				<table width="100%">
					<tr>
						<td>Название клана: </td><td><b>'.$cl['nazv'].'</b></td>
					</tr>
					<tr>
						<td>Сайт клана</td><td><input name="site" type="text" value="'.$cl['site'].'" maxlength="255" style="width:100%"></td>
					</tr>
					<tr>
						<td>Инфо</td><td><textarea style="width:100%" name="opis_clan_town" class="input" rows="10">' . $cl['opis'] . '</textarea></td>
					</tr>
					<tr>
						<td>Значок клана: </td><td><img src="http://'.IMG_DOMAIN.'/clan/'.$cl['clan_id'].'.gif"><br /><input type=file name=file_znak size=40><input name="upload1" type="submit" value=Закачать></td>
					</tr>
					<tr>
						<td>Логотип клана: </td><td><img src="http://'.IMG_DOMAIN.'/clan/'.$cl['clan_id'].'_logo.gif"><br /><input type=file name=file_logo size=40 ><input name="upload2" type="submit" value=Закачать></td>
					</tr>
					<tr style="height:30px;">
						<td colspan=2 align="center"><input name="submit" type="submit" value="Сохранить">   <input type="button" value="Выйти" OnClick=location.href="town.php?option='.$option.'"></td>
					</tr>
				</table>
				<input name="see_clan_town" type="hidden" value="">
				<input name="town_id" type="hidden" value="'.$town.'">
				</form>';
			}
			else
			{
				if (isset($submit))
				{
					$site = htmlspecialchars($site);
					$opis_clan_town = htmlspecialchars($opis_clan_town);
					$result=myquery("update game_clans set opis='$opis_clan_town',site='$site' where clan_id = '".$cl['clan_id']."'");
				}
				elseif (isset($upload1))
				{
					$endresult = 'Значок клана загружен';
					$absolute_path = "../../images/clan";
					$limit_size = 10240;
					$image_max_width        = "20";    // максимальные ширина и высота
					$image_max_height        = "20";   //  для графических файлов
					$limit_ext = "yes";
					$ext_count = "2";
					$extensions = array(".gif", ".jpeg", ".jpg", ".GIF", ".JPEG", ".JPG");
					if (isset($_FILES['file_znak']))
					{
						$data = $_FILES['file_znak'];
						$size_limit = "yes";
						if ($data['name'] == "")
						{
							$endresult = "<font size=\"2\">Ты ничего не ".echo_sex('выбрал','выбрала')."</font>";
						}
						else
						{
							if (($size_limit == "yes") && ($limit_size < $data['size']) AND ($char['clan_id']!=1))
							{
								$endresult = "<font size=\"2\">Большой размер (закачано ".$data['size']." байт, разрешено $limit_size байт)</font>";
							}
							else
							{
								$ext = strrchr($data['name'],'.');
								$filename=''.$char['clan_id'].'.gif';
								@unlink ("$absolute_path/$filename");
								@copy($data['tmp_name'], "$absolute_path/$filename") or $endresult = "<font size=\"2\">Такой файл уже существует</font>";

								$size = GetImageSize("$absolute_path/$filename");
								list($width,$height,$bar,$foo) = $size;
								if ($bar!=1 AND $bar!=2 AND $bar!=3 AND $bar!=6)
								{
									$endresult = "<font size=\"2\">Разешены форматы: GIF JPG PNG BMP</font>";
									@unlink ("$absolute_path/$filename");
								}
								if ($width > $image_max_width AND $char['clan_id']!=1)
								{
									$endresult = "Ошибка! Изображение должно быть не шире\n ".$image_max_width." пикселей, а твое $width пикселей<br></li>";
									@unlink ("$absolute_path/$filename");
								}
								if ($height > $image_max_height AND $char['clan_id']!=1)
								{
									$endresult = "Ошибка! Изображение должно быть не выше\n " . $image_max_height . " пикселей, а твое $height пикселей<br></li>";
									@unlink ("$absolute_path/$filename");
								}
							}
						}
					}
					else
					{
						$endresult = 'Не выбран файл значка клана';
					}
					echo"<center>$endresult";
				}
				elseif (isset($upload2))
				{
					$endresult = 'Логотип клана загружен';
					$absolute_path = "../../images/clan";
					$limit_size = 51200;
					$image_max_width        = "200";    // максимальные ширина и высота
					$image_max_height        = "200";   //  для графических файлов
					$limit_ext = "yes";
					$ext_count = "2";
					$extensions = array(".gif", ".jpeg", ".jpg", ".GIF", ".JPEG", ".JPG");
					if (isset($_FILES['file_logo']))
					{
						$data = $_FILES['file_logo'];
						$size_limit = "yes";
						if ($data['name'] == "")
						{
							$endresult = "<font size=\"2\">Ты ничего не ".echo_sex('выбрал','выбрала')."</font>";
						}
						else
						{
							if (($size_limit == "yes") && ($limit_size < $data['size']) AND ($char['clan_id']!=1))
							{
								$endresult = "<font size=\"2\">Большой размер (закачано ".$data['size']." байт, разрешено $limit_size байт)</font>";
							}
							else
							{
								$ext = strrchr($data['name'],'.');
								$filename=''.$char['clan_id'].'_logo.gif';
								if (is_file("$absolute_path/$filename"))
								{
									unlink ("$absolute_path/$filename");
									copy($data['tmp_name'], "$absolute_path/$filename") or $endresult = "<font size=\"2\">Такой файл уже существует</font>";
								}
								$size = GetImageSize("$absolute_path/$filename");
								list($width,$height,$bar,$foo) = $size;
								if ($bar!=1 AND $bar!=2 AND $bar!=3 AND $bar!=6)
								{
									$endresult = "<font size=\"2\">Разешены форматы: GIF JPG PNG BMP</font>";
									@unlink ("$absolute_path/$filename");
								}
								if ($width > $image_max_width AND $char['clan_id']!=1)
								{
									$endresult = "Ошибка! Изображение должно быть не шире\n ".$image_max_width." пикселей, а твое $width пикселей<br></li>";
									@unlink ("$absolute_path/$filename");
								}
								if ($height > $image_max_height AND $char['clan_id']!=1)
								{
									$endresult = "Ошибка! Изображение должно быть не выше\n " . $image_max_height . " пикселей, а твое $height пикселей<br></li>";
									@unlink ("$absolute_path/$filename");
								}
							}
						}
					}
					else
					{
						$endresult = 'Не выбран файл логотипа клана';
					}
					echo"<center>$endresult";
				}
				echo'<center>Информация о клане изменена<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
			}
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		
		if ($save_zam!='' AND $cl['glava']==$char['user_id'])
		{
			$id1 = 0;
			$id2 = 0;
			$id3 = 0;
			if ($save_zam1!='')
			{
				$sel0 = myquery("SELECT user_id,clan_id FROM game_users WHERE name='$save_zam1'");
				if (!mysql_num_rows($sel0)) $sel0 = myquery("SELECT user_id,clan_id FROM game_users_archive WHERE name='$save_zam1'");
				list($id1,$clan_zam) = mysql_fetch_array($sel0);
				if ($clan_zam!=$char['clan_id'])
				{
					echo'<center>1 заместитель не в твоем клане<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
					echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
					{if (function_exists("save_debug")) save_debug(); exit;}
				}
			}
			if ($save_zam2!='')
			{
				$sel0 = myquery("SELECT user_id,clan_id FROM game_users WHERE name='$save_zam2'");
				if (!mysql_num_rows($sel0)) $sel0 = myquery("SELECT user_id,clan_id FROM game_users_archive WHERE name='$save_zam2'");
				list($id2,$clan_zam) = mysql_fetch_array($sel0);
				if ($clan_zam!=$char['clan_id'])
				{
					echo'<center>2 заместитель не в твоем клане<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
					echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
					{if (function_exists("save_debug")) save_debug(); exit;}
				}
			}
			if ($save_zam3!='')
			{
				$sel0 = myquery("SELECT user_id,clan_id FROM game_users WHERE name='$save_zam3'");
				if (!mysql_num_rows($sel0)) $sel0 = myquery("SELECT user_id,clan_id FROM game_users_archive WHERE name='$save_zam3'");
				list($id3,$clan_zam) = mysql_fetch_array($sel0);
				if ($clan_zam!=$char['clan_id'])
				{
					echo'<center>3 заместитель не в твоем клане<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
					echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
					{if (function_exists("save_debug")) save_debug(); exit;}
				}
			}
			$up=myquery("update game_clans set zam1='$id1',zam2='$id2',zam3='$id3' where glava='".$char['user_id']."'");
			echo'<center>Заместители главы в клане установлены<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}

		if ($save_glava!='' AND $char['user_id']==$cl['glava'])
		{
			if ($save_glava!='')
			{
				$sel0 = myquery("SELECT user_id,clan_id FROM game_users WHERE name='$save_glava'");
				if (!mysql_num_rows($sel0)) $sel0 = myquery("SELECT user_id,clan_id FROM game_users_archive WHERE name='$save_glava'");
				list($id,$clan_zam) = mysql_fetch_array($sel0);
				if ($clan_zam!=$char['clan_id'])
				{
					echo'<center>Новый глава не в твоем клане<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
					echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
					{if (function_exists("save_debug")) save_debug(); exit;}
				}
				elseif ($id>0)
				{
					if ($id==$cl['zam1'])
					{
						$up=myquery("update game_clans set zam1='".$cl['glava']."',glava='$id' where glava='".$char['user_id']."'");
					}
					elseif ($id==$cl['zam2'])
					{
						$up=myquery("update game_clans set zam2='".$cl['glava']."',glava='$id' where glava='".$char['user_id']."'");
					}
					elseif ($id==$cl['glava'])
					{
						$up=myquery("update game_clans set zam3='".$cl['glava']."',glava='$id' where glava='".$char['user_id']."'");
					}
					else
					{
						$up=myquery("update game_clans set glava='$id' where glava='".$char['user_id']."'");
					}
					echo'<center>В клане установлен новый глава<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				}
			}
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}

		if ($izgn!='')
		{
			$izgn=(int)$izgn;
			$sel=myquery("select user_id, name, clan_id from game_users where user_id=$izgn");
			if (!mysql_num_rows($sel)) $sel=myquery("select user_id, name, clan_id from game_users_archive where user_id='".$izgn."'");
			if (mysql_num_rows($sel))
			{
				list($use,$iz_name,$clan)=mysql_fetch_array($sel);
				if($clan != $char['clan_id'])
				{
					echo'Игрок не состоит в твоем клане';
				}
				else
				{
					echo '<form method=post>
                        <table align="center">
                        <tr><td colspan=4>Вы действительно хотите выгнать игрока?</td></tr>
                        <tr><td align="center"><input type="submit" name="isgn2" value="Да" style="width: 45px"></input></td>
                        <td align="center"><input type="button" value="Нет" OnClick=location.href="town.php?option='.$option.'" style="width: 45px"></input></td></tr>
                        </table></form>';
                    
                }
                if(isset($_POST['isgn2']))
                {
					$sele=myquery("select glava,zam1,zam2,zam3 from game_clans where clan_id='".$char['clan_id']."' LIMIT 1");
					$cl = mysql_fetch_array($sele);
					if ($cl['glava']==$use)
					{
						//Глава выгоняет сам себя. Бред конечно.
						if ($cl['zam1']!=0) myquery("UPDATE game_clans SET glava='".$cl['zam1']."',zam1=0 WHERE clan_id='".$char['clan_id']."'");
						elseif ($cl['zam2']!=0) myquery("UPDATE game_clans SET glava='".$cl['zam2']."',zam2=0 WHERE clan_id='".$char['clan_id']."'");
						elseif ($cl['zam1']!=0) myquery("UPDATE game_clans SET glava='".$cl['zam3']."',zam3=0 WHERE clan_id='".$char['clan_id']."'");
						else 
						{
							echo'Ты глава клана и у тебя нет заместителей. Ты не можешь выгнать себя!';
							echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
							{if (function_exists("save_debug")) save_debug(); exit;}
						}
					}
					if ($cl['zam1']==$use) $upd = myquery("UPDATE game_clans SET zam1=0 WHERE clan_id='".$char['clan_id']."'");
					if ($cl['zam2']==$use) $upd = myquery("UPDATE game_clans SET zam2=0 WHERE clan_id='".$char['clan_id']."'");
					if ($cl['zam3']==$use) $upd = myquery("UPDATE game_clans SET zam3=0 WHERE clan_id='".$char['clan_id']."'");
					$up=myquery("update game_users set clan_id='0',clan_items_old='0' where user_id='".$use."'");
					$up=myquery("update game_users_archive set clan_id='0',clan_items_old='0' where user_id='".$use."'");
					$up=myquery("update game_users_data set clan_rating='',clan_zvanie='',last_clan_move=vozrast where user_id='".$use."'");
					echo'<center>Игрок изгнан из клана<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				}
			}
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}

		if ($edit_user!='')
		{
			if (!isset($save_user))
			{
				$sel=myquery("select name, clan_id, clan_items_old from game_users where user_id='".$edit_user."'");
				if (!mysql_num_rows($sel)) $sel=myquery("select name, clan_id, clan_items_old from game_users_archive where user_id='".$edit_user."'");
				if (mysql_num_rows($sel))
				{
					list($name,$clan,$dostup)=mysql_fetch_array($sel);
					$sel=myquery("select clan_zvanie, clan_rating from game_users_data where user_id='".$edit_user."'");
					list($zvanie,$rating)=mysql_fetch_array($sel);
					if($clan != $char['clan_id'])
					{
						echo'Игрок не состоит в твоем клане';
					}
					else
					{
						echo '<form name=forma action="" method=post>';
						echo '<table>
						<tr><td colspan=4>Сведения об игроке <font size=2 color=#FF0000><b>'.$name.'</b></font></td></tr>
						<tr><td>Рейтинг</td><td>Звание</td><td>Доступ к рынку клана</td><td collspan=2 align=center>Действие</td></tr>
						<tr><td><input name=user_rating type=text value="'.$rating.'" size=10 maxlength=15></td>
						<td><input name=user_zvanie type=text value="'.$zvanie.'" size=20 maxlength=200></td>
						<td>
						<select name="old_itmes_select">
						<option value="1"';
						if ($dostup=='1') echo ' selected';
						echo'>Полный доступ</option>
						<option value="2"';
						if ($dostup=='2') echo ' selected';
						echo'>Частичный доступ</option>
						<option value="0"';
						if ($dostup=='0') echo ' selected';
						echo'>Закрытый доступ</option>
						</select>
						</td>
						<td><input name="save" type="submit" value="Сохранить"></td>
						<td><input type="button" value="Выйти" OnClick=location.href="town.php?option='.$option.'"></td>
						</tr>
						</table><input name="save_user" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></form>';
					}
				}
			}
			else
			{
				$clan_id=$char['clan_id'];
				$result = myquery("SELECT user_id FROM game_users where user_id='$edit_user' AND clan_id = '$clan_id' LIMIT 1");
				if (!mysql_num_rows($result)) $result = myquery("SELECT user_id FROM game_users_archive where user_id='$edit_user' AND clan_id = '$clan_id' LIMIT 1");
				if (mysql_num_rows($result))
				{
					$up = myquery("UPDATE game_users SET clan_items_old='$old_itmes_select' WHERE user_id='$edit_user'");
					$up = myquery("UPDATE game_users_archive SET clan_items_old='$old_itmes_select' WHERE user_id='$edit_user'");
					$up = myquery("UPDATE game_users_data SET clan_zvanie='$user_zvanie', clan_rating='$user_rating' WHERE user_id='$edit_user'");
					echo'<center>Информация по игроку сохранена!<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				}
				else
				{
					echo'<center>Игрок не найден!<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				}

				echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
				{if (function_exists("save_debug")) save_debug(); exit;}
			}

			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		
        if ($alies!='')
        {
            /*
            if (!isset($save_alies))
            {
                $current_alies='Нет заключенных союзов';
                if ($cl['alies']!=0)
                {
                    $current_alies = mysqlresult(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$cl['alies'].""),0,0);
                }
                echo '<form name=forma action="" method=post>';
                echo '<table>
                <tr><td><b>Текущий союз:</b></td><td><font color=yellow><b>'.$current_alies.'</b></font></td></tr>
                <tr><td>Установить союз с кланом</td><td>';
                echo '<select name=alies>';
                echo '<option value="0"'; if ($cl['alies']==0) echo ' selected'; echo'>Нет союза с кланом</option>';
                $sel_all_clans = myquery("SELECT clan_id,nazv FROM game_clans WHERE clan_id>1 AND raz=0 AND clan_id<>".$cl['clan_id']."");
                while ($all_cl = mysql_fetch_array($sel_all_clans))
                {
                    echo '<option value="'.$all_cl['clan_id'].'"'; if ($cl['alies']==$all_cl['clan_id']) echo ' selected'; echo'>'.$all_cl['nazv'].'</option>';
                }
                echo '</select>';
                echo '</td></tr>
                <tr><td><input name="save_alies" type="submit" value="Сохранить"></td>
                <td><input type="button" value="Выйти" OnClick=location.href="town.php?option='.$option.'"></td>
                </tr>
                </table><input name="town_id" type="hidden" value="'.$town.'"></form>';
            }
            else
            {
                $alies = (int)$alies;
                $result = myquery("SELECT clan_id FROM game_clans where clan_id>1 AND raz=0 AND clan_id<>".$cl['clan_id']." AND clan_id=".$alies."");
                if (mysql_num_rows($result) or $alies==0)
                {
                    $up = myquery("UPDATE game_clans SET alies='$alies' WHERE clan_id='".$cl['clan_id']."'");
                    if ($cl['alies']!=0)
                    {
                        $alies_old = mysql_fetch_array(myquery("SELECT * FROM game_clans WHERE clan_id=".$cl['alies'].""));
                        $title = "Руководство клана &quot;".$cl['nazv']."&quot; разорвало союз с вашим кланом";
                        if ($alies_old['glava']!=0) myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$alies_old['glava'].", 0, '".$title."', '".$title."',0,".time().")");
                        if ($alies_old['zam1']!=0) myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$alies_old['zam1'].", 0, '".$title."', '".$title."',0,".time().")");
                        if ($alies_old['zam2']!=0) myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$alies_old['zam2'].", 0, '".$title."', '".$title."',0,".time().")");
                        if ($alies_old['zam3']!=0) myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$alies_old['zam3'].", 0, '".$title."', '".$title."',0,".time().")");
                    }
                    if ($alies>0)
                    {
                        $alies_new = mysql_fetch_array(myquery("SELECT * FROM game_clans WHERE clan_id=$alies"));
                        $title = "Руководство клана &quot;".$cl['nazv']."&quot; установило с вашим кланом союз";
                        if ($alies_new['glava']!=0) myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$alies_new['glava'].", 0, '".$title."', '".$title."',0,".time().")");
                        if ($alies_new['zam1']!=0) myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$alies_new['zam1'].", 0, '".$title."', '".$title."',0,".time().")");
                        if ($alies_new['zam2']!=0) myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$alies_new['zam2'].", 0, '".$title."', '".$title."',0,".time().")");
                        if ($alies_new['zam3']!=0) myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$alies_new['zam3'].", 0, '".$title."', '".$title."',0,".time().")");
                        echo'<center>Союз с кланом &quot;'.$alies_new['nazv'].'&quot; установлен!<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
                    }
                    else
                    {
                        echo'<center>Союз отменен!<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
                    }
                }
                else
                {
                    echo'<center>Игрок не найден!<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
                }

                echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
                {if (function_exists("save_debug")) save_debug(); exit;}
            }

            echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
            {if (function_exists("save_debug")) save_debug(); exit;}
            */
        }
        
		if ($add=='new')
		{
			$sel_now = mysql_result(myquery("SELECT COUNT(*) FROM game_users WHERE clan_id=".$char['clan_id'].""),0,0)+mysql_result(myquery("SELECT COUNT(*) FROM game_users_archive WHERE clan_id=".$char['clan_id'].""),0,0);
			$new_cost = 200*(ceil(($sel_now-1)/10)+1);
			$sklon_clan = mysql_result(myquery("SELECT sklon FROM game_clans WHERE clan_id=".$char['clan_id'].""),0,0);
			if (!isset($see))
			{
				echo "<div id=\"content\" onclick=\"hideSuggestions();\"><form name=frm method=post>";
				echo '<table>
				<tr><td colspan=4>Чтобы добавить игрока в клан нужно заплатить от '.(0.2*$new_cost).' до '.$new_cost.' золотых (стоимость принятия зависит от уровня нового игрока)</td></tr>
				<tr><td>Имя</td><td><input name="name" type="text" value="" size="20" maxlength="50" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td>
				<td><input name="submit" type="submit" value="Добавить"></td><td><input type="button" value="Выйти" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'"></td></tr>
				<input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"><input name="option" type="hidden" value="'.$option.'">
				</table></form></div><script>init();</script>';
				
				$sel = myquery("SELECT game_users_clan_reg.*,game_users.name,game_users.clevel,game_har.name AS race FROM game_users_clan_reg,game_users,game_har WHERE game_users_clan_reg.clan_id=".$char['clan_id']." AND game_users.user_id=game_users_clan_reg.user_id AND game_har.id=game_users.race ORDER BY game_users_clan_reg.timestamp ASC");
				if ($sel!=false AND mysql_num_rows($sel)>0)
				{
					echo '<center>Твои текущие заявки на игроков:<br />
					<table cellspacing="2" cellpadding="2" border="1"><tr><td style="font-weight:bold;text-align:center;color:lightgreen;font-size:13px;">Игрок</td><td style="font-weight:bold;text-align:center;color:lightgreen;font-size:13px;">Время подачи заявки</td></tr>';
					while ($row = mysql_fetch_array($sel))
					{
						echo '<tr><td style="text-align:center;"><a target=_blank href="http://'.DOMAIN.'/view/?userid='.$row['user_id'].'"><img border="0" src="http://'.IMG_DOMAIN.'/nav/i.gif"</a>'.$row['name'].' ('.$row['race'].' '.$row['clevel'].' уровня)</td><td style="text-align:center;">'.date("d.m.Y H:i:s",$row['timestamp']).'</td></tr>';
					}
					echo '</table>';
				}
			}
			else
			{
				$prov=myquery("select user_id,clevel,sklon from game_users where name='$name' and clan_id=0 limit 1");
				if (!mysql_num_rows($prov)) $prov=myquery("select user_id,clevel,sklon from game_users_archive where name='$name' and clan_id=0 limit 1");
				if (mysql_num_rows($prov))
				{
					$usr = mysql_fetch_array($prov);
					list($last_clan_move,$vozrast)=mysql_fetch_array(myquery("SELECT last_clan_move,vozrast FROM game_users_data WHERE user_id=".$usr['user_id'].""));
					if (($vozrast-$last_clan_move>=10) or ($last_clan_move==0))
					{
						if ($usr['clevel']>=2)
						{
							if ($usr['sklon']==0 OR $usr['sklon']==$sklon_clan)
							{
                                $clan_id = $cl['clan_id'];
                                $nazv = $cl['nazv'];
                                $opis = $cl['opis'];
                                $znak = $cl['img'];
                                $glava = $cl['glava'];
								$sel = myquery("SELECT * FROM game_users_clan_reg WHERE user_id = '".$usr['user_id']."' AND clan_id = '$clan_id'");
								if (mysql_num_rows($sel))
								{
									echo'<center>Игроку уже был отправлен запрос на прием его в твой клан. После подтверждения этой заявки игроком, он будет принят в твой клан<br><meta http-equiv="refresh" content="5;url=town.php?option='.$option.'">';
								}
								else
								{
									if ($usr['clevel']<=10)
									{
										$new_cost = round($new_cost*(1-((10-$usr['clevel'])/10)),2);
									}
									if (!isset($_GET['agree']))
									{
										echo 'За прием в клан игрока <span style="color:red;font-size:12px;font-weight:800;">'.$name.'</span> тебе надо заплатить '.$new_cost.' '.pluralForm($new_cost,'монету','монеты','монет').'<br />';
										$test1=myquery("SELECT * from game_users where user_id='$user_id' and GP>='$new_cost'"); 
										if (mysql_num_rows($test1)>0)
										echo '<br /><input type="button" value="ДА, я '.echo_sex('согласен','согласна').' заплатить" onclick="location.replace(\'town.php?option='.$option.'&add=new&see&name='.urlencode($name).'&town_id='.$town.'&agree\')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="НЕТ, я не буду платить" onclick="location.replace(\'town.php?option='.$option.'\')">';
										else echo '<br /> У вас недостаточно средств для приёма игрока в клан';	
									}
									else
									{
										$up = myquery("INSERT INTO game_users_clan_reg (user_id,clan_id,timestamp) VALUES (".$usr['user_id'].",$clan_id,".time().")");
										$result=myquery("update game_users set GP=GP-".$new_cost.",CW=CW-".($new_cost*money_weight)." where user_id=$user_id");
										setGP($user_id,-$new_cost,39);
										$msg_klan='Руководство клана "'.$nazv.'" подало заявку на прием тебя в клан. Ты можешь подтвердить эту заявку или отказаться от нее в городе Пригорье на карте Средиземье!';
										$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$usr['user_id'].", 0, 'Руководство клана &quot;".$nazv."&quot; подало заявку на прием тебя в клан', '$msg_klan',0,".time().")") or die(mysql_error());
										echo'<center>Игроку отправлен запрос на прием его в клан. После подтверждения твоей заявки игроком, он будет принят в твой клан<br><meta http-equiv="refresh" content="5;url=town.php?option='.$option.'">';
									}
								}
							}
							else
							{
								echo'<center>Игрок имеет другую склонность! Его нельзя принять!<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">'; 
							}
						}
						else
						{
							echo'<center>Игрок еще не достиг 2 уровня!<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
						}
					}
					else
					{
						echo'<center>Возраст игрока между изгнанием из клана и приемом в клан должен увеличиться на 10 игровых месяцев.<br>Игрока принять нельзя.<br>Игроку осталось "позврослеть" еще на '.(10-$vozrast+$last_clan_move).' месяцев.<br><meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
					}
				}
				else
				{
					echo'<center>Такого игрока не существует или он находится в другом клане!<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				}
			}
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		
		if (isset($_GET['tax']))
		{
			$sel = myquery("SELECT * FROM game_clans_taxes WHERE flag = 0 AND clan_id=".$char['clan_id']."");
			if (mysql_num_rows($sel)>0)
			{
				echo 'На данный момент за твоим кланом числитcя задолженность:
				<table align="center" width="80%" border=0 cellspacing=10>
				<tr style="color:white;font-weight:800;font-size:12px;"><td>Месяц</td><td>Сумма</td><td>Сумма рейтинга</td><td></td></tr>';
				while ($tax = mysql_fetch_array($sel))
				{
					list($vozrast,$user_clan_all) = mysql_fetch_array(myquery("SELECT SUM(vozrast),COUNT(*) FROM game_clans_vozrast WHERE clan_id=".$char['clan_id']." AND month=".$tax['month']." AND year = ".$tax['year'].""));
					$rating = floor(8*($vozrast/($user_clan_all*30)));
					if ($rating<1) $rating = 1;
					if ($rating>8) $rating = 8;
					echo '<tr><td>'.$tax['month'].'.'.$tax['year'].'</td><td>'.$tax['summa'].'</td><td>'.$rating.'</td><td><input type="button" value="Оплатить" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&tax&pay='.$tax['id'].'"></td></tr>'; 
				}
				echo '</table>';
			}
			else
			{
				echo '<br />На данный момент за твоим кланом нет задолженности по налогам<br />';
			}
			if (isset($_GET['pay']))
			{
				echo '<div style="width:250px;align:center;color:red;">';
				$pay = (int)$_GET['pay'];
				$check = myquery("SELECT * FROM game_clans_taxes WHERE id=$pay AND flag=0 AND clan_id=".$char['clan_id']."");
				if (mysql_num_rows($check))
				{
					$tax = mysql_fetch_array($check);
					$summa = $tax['summa'];
					if ($summa<=$char['GP'])
					{
						list($vozrast,$user_clan_all) = mysql_fetch_array(myquery("SELECT SUM(vozrast),COUNT(*) FROM game_clans_vozrast WHERE clan_id=".$char['clan_id']." AND month=".$tax['month']." AND year = ".$tax['year'].""));
						$rating = floor(8*($vozrast/($user_clan_all*30)));
						if ($rating<1) $rating = 1;
						if ($rating>8) $rating = 8;
						myquery("UPDATE game_clans_taxes SET summa=0,flag=1,time_pay=".time().",rating=$rating WHERE id=$pay");
						myquery("UPDATE game_users SET GP=GP-$summa,CW=CW-".($summa*money_weight)." WHERE user_id=$user_id");
						setGP($user_id,-$summa,40);
						myquery("UPDATE game_clans SET raring=raring+$rating WHERE clan_id=".$char['clan_id']."");
						echo 'Оплата принята. Спасибо. Ты '.echo_sex('повысил','повысила').' рейтинг своего клана на '.$rating.' '.pluralForm($rating,'очко','очка','очков').'!';    
					}
					else
					{
						echo 'У тебя недостаточно денег!';
					}
				}
				else
				{
					echo 'Произошла ошибка';
				}
				echo '</div>';
			}
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

			echo'<br><center><input type="button" value="Выйти" OnClick=location.href="town.php?option='.$option.'"></center>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}

		$clan_id = $cl['clan_id'];
        $nazv = $cl['nazv'];
        $opis = $cl['opis'];
        $znak = $cl['img'];
        $glava = $cl['glava'];
        $site = $cl['site'];
        $zam1 = $cl['zam1'];
        $zam2 = $cl['zam2'];
        $zam3 = $cl['zam3'];
		$select=myquery("select town from game_map where xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
		list($town)=mysql_fetch_array($select);
		echo'<center><b><font face=verdana size=2>';
		$dostup = 0;
		if ($char['user_id']==$glava)
		{
			echo 'Ты глава клана: ';
		}
		if ($char['user_id']==$zam1 OR $char['user_id']==$zam2 OR $char['user_id']==$zam3)
		{
			echo 'Ты заместитель главы клана: ';
		}
		echo '<font face=verdana size=2 color=ff0000>"'.$nazv.'"</b></font>';
		echo '</center>
		<center><img src="http://'.IMG_DOMAIN.'/clan/'.$clan_id.'.gif"> '.$opis.'';
		echo '<br><br><input type="button" value="Редактировать инфо" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&edit='.$clan_id.'">'; 
        //echo ' <input type="button" value="Установить союз" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&alies='.$clan_id.'">';
        echo ' <input type="button" value="Добавить игрока в клан" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&add=new"><br><br><input type="button" value="Уплата налога за содержание клана" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&tax">';

		if ($char['user_id']==$glava)
		{
			$zamname1 = '';
			$zamname2 = '';
			$zamname3 = '';
			$sel22 = myquery("SELECT name FROM game_users WHERE user_id=$glava");
			if (!mysql_num_rows($sel22)) $sel22 = myquery("SELECT name FROM game_users_archive WHERE user_id=$glava");
			list($glavaname) = mysql_fetch_array($sel22);
			if ($zam1>0) 
			{
				$zamname1 = get_user('name',$zam1);
			}
			if ($zam2>0) 
			{
				$zamname2 = get_user('name',$zam2);
			}
			if ($zam3>0) 
			{
				$zamname3 = get_user('name',$zam3);
			}
			echo '<table><tr><td colspan=4><center><b><font color=ff0000>Заместители главы клана</font></b></center></td></tr>
			<tr>
			<td><input name="zam_name1" id="zam_name1" type="text" value="'.$zamname1.'" size="20"></td>
			<td><input name="zam_name2" id="zam_name2" type="text" value="'.$zamname2.'" size="20"></td>
			<td><input name="zam_name3" id="zam_name3" type="text" value="'.$zamname3.'" size="20"></td>
			<td><input type="button" value="Сохранить" OnClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&save_zam=1&save_zam1=\'+document.getElementById(\'zam_name1\').value+\'&save_zam2=\'+document.getElementById(\'zam_name2\').value+\'&save_zam3=\'+document.getElementById(\'zam_name3\').value"></td>
			</tr>
			</table>';

			echo '<table><tr><td colspan=2><center><b><font color=ff0000>Сменить главу клана</font></b></center></td></tr>
			<tr>
			<td><input id="glava_name" name="glava_name" type="text" value="'.$glavaname.'" size="20"></td>
			<td><input type="button" value="Сохранить" OnClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&save_glava=\'+document.getElementById(\'glava_name\').value"></td>
			</tr>
			</table>';

			echo '<table><tr><td colspan=2><center><b><font color=ff0000>Склонность клана</font></b></center></td></tr>
			<tr>';
			$sklon = mysql_result(myquery("SELECT sklon FROM game_clans WHERE clan_id=".$char['clan_id'].""),0,0);
			if (isset($_GET['select_sklon']) AND $sklon==0)
			{
				$sklon = (int)$_GET['select_sklon'];
				myquery("UPDATE game_clans SET sklon=".$sklon." WHERE clan_id=".$char['clan_id']."");
			}
			if ($sklon==0)
			{
				echo '
				<td><select id="sel_sklon"><option value="1">Нейтральная</option><option value="2">Светлая</option><option value="3">Темная</option></select></td>
				<td><input type="button" value="Выбрать склонность" OnClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&select_sklon=\'+document.getElementById(\'sel_sklon\').value"></td>';
			}
			else
			{
				echo '<td>';
				if ($sklon==1) echo '<img src="http://'.IMG_DOMAIN.'/sklon/neutral.gif" border="0">&nbsp;&nbsp;<span style="color:#D0D0D0;font-weight:800;">Нейтральная</span>';
				if ($sklon==2) echo '<img src="http://'.IMG_DOMAIN.'/sklon/light.gif" border="0">&nbsp;&nbsp;<span style="color:#FFFFC0;font-weight:800;">Светлая</span>';
				if ($sklon==3) echo '<img src="http://'.IMG_DOMAIN.'/sklon/dark.gif" border="0">&nbsp;&nbsp;<span style="color:#969696;font-weight:800;">Темная</span>';
				echo '</td>
				<td>&nbsp;&nbsp;</td>';
			}            
			echo'</tr>
			</table>';
		}
		echo '</center>
		<br>В твоем клане состоят:<br>';

		echo'<table border=0 width=100%><tr><td><font size=1>№</td><td><font size=1>Имя</td><td><font size=1>WIN</td><td><font size=1>LOSE</td><td><font size=1>Звание</td><td><font size=1>Рейтинг</td><td><font size=1>Изгнание</td><td><font size=1>Редактировать</td></tr>';
		$i=1;

		$select=myquery("(select * from game_users where clan_id=".$clan_id.") UNION (select * from game_users_archive where clan_id=".$clan_id.") ORDER BY name ASC");
		while ($who=mysql_fetch_array($select))
		{
			list($rating,$zvanie) = mysql_fetch_array(myquery("SELECT clan_rating,clan_zvanie FROM game_users_data WHERE user_id='".$who['user_id']."'"));
			echo'<tr><td width=20><font size=1>'.$i.'</td><td width=100><font face=verdana color=#97FFFF size=1>'.$who['name'].'</td><td width=50><font face=verdana size=1>'.$who['win'].'</td><td width=50><font face=verdana size=1>'.$who['lose'].'</td><td width=50><font face=verdana size=1>'.$zvanie.'</td><td width=100><font face=verdana size=1>'.$rating.'</td>
			<td>';
			if (($who['user_id']==$glava OR $who['user_id']==$zam1 OR $who['user_id']==$zam2 OR $who['user_id']==$zam3) and $char['user_id']==$glava)
			{
				echo '<input type="button" value="Изгнать" OnClick=location.href="town.php?option='.$option.'&izgn='.$who['user_id'].'">';
				echo '</td><td>';
				echo '<input type="button" value="Звание и рейтинг" OnClick=location.href="town.php?option='.$option.'&edit_user='.$who['user_id'].'">';
			}
			elseif (($char['user_id']==$glava OR $char['user_id']==$zam1 OR $char['user_id']==$zam2 OR $char['user_id']==$zam3) and ($who['user_id']!=$glava and $who['user_id']!=$zam1 and $who['user_id']!=$zam2 and $who['user_id']!=$zam3))
			{
				echo '<input type="button" value="Изгнать" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&izgn='.$who['user_id'].'">';
				echo '</td><td>';
				echo '<input type="button" value="Звание и рейтинг" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&edit_user='.$who['user_id'].'">';
			}
			echo '</td>';
			echo'</tr>';
			$i++;
		}
		echo'</table>';
	}
	else
	{
		if ($new=='newklan')
		{
			if ($char['clevel']< $lev)
			{
				echo'<center>У тебя маленький уровень для создания клана<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			if ($char['GP']<$gpc)
			{
				echo'<center>У тебя недостаточно золота<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			if ($char['clan_id']!='0')
			{
				echo'<center>Ты состоишь в клане.<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			if (!isset($see))
			{
				$select=myquery("select town from game_map where xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
				list($town)=mysql_fetch_array($select);

				echo "<form name=frm method=post>";
				echo '<table>
				<tr><td colspan=2><center><font color=ff0000><b>Цена за регистрацию клана '.$gpc.' золотых</b></font></td></tr>
				<tr><td>Название клана</td><td><input name="nazv" type="text" value="" size="50" maxlength="50"></td></tr>
				<tr><td>Эмблема (20x20)</td><td><input name="embl" type="text" value="http://" size="50" maxlength="200"></td></tr>
				<tr><td>Инфо</td><td><textarea name="opis_new_clan" cols="40" class="input" rows="6"></textarea></td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td colspan=2>* Клан будет расмотрен на ближайшем совете гильдии регистрации кланов и только после утверждения всеми членами гильдии появится в "Cредиземье"</td></tr>
				<tr><td colspan="2" align="center"><input name="submit" type="submit" value="Подать заявку"></td></tr>
				<input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'">
				
				</table></form>';
			}
			else
			{
				$nazv=htmlspecialchars($nazv);
				$opis=htmlspecialchars($opis);

				$msg_klan='Добавление нового клана: '.$nazv.'<br>'.$opis.'<br>'.$char['name'].'';
				//$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('1', '0', 'Гильдия регистрации кланов', '$msg_klan','0','".time()."')");
				//$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('889', '0', 'Гильдия регистрации кланов', '$msg_klan','0','".time()."')");
				$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('612', '0', 'Гильдия регистрации кланов', '$msg_klan','0','".time()."')");
				$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('1016', '0', 'Гильдия регистрации кланов', '$msg_klan','0','".time()."')");

				$sel=myquery("select clan_id from game_clans order by clan_id DESC limit 1");
				list($nid)=mysql_fetch_array($sel);
				$n=''.($nid+1).'';

				$result=myquery("insert into game_clans (clan_id,nazv,opis,img,glava,raz,sklon) values ('$n','$nazv','$opis_new_clan','$embl','".$char['name']."','1',".$char['sklon'].")");
				$result=myquery("update game_users set GP=GP-$gpc,CW=CW-'".($gpc*money_weight)."', clan_id='$n' where user_id='".$char['user_id']."'");
				setGP($user_id,-$gpc,41);
				echo'<center>Клан будет расмотрен на ближайшем совете гильдии регистрации кланов и только после утверждения всеми членами гильдии появится в "Cредиземье"<br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
			}
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		$pro=myquery("select * from game_clans where glava='".$char['user_id']."' and raz='1'");
		if (mysql_num_rows($pro))
		{
			echo'<center>Твоя заявка будет рассмотрена в ближайшее время';
		}
		else
		{
			if ($char['clan_id']!='0')
			{
				echo 'Ты уже состоишь в клане. Для тебя нет никаких возможностей&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти" OnClick=location.href="town.php">';
			}
			else
			{
				echo'<center><b>Гильдия регистрации кланов рада приветствовать тебя!</b>';
				echo'<br><font face=verdana color=ff0000><b>Правила регистрации клана:</b></font></center><br>';
				echo'1. Глава клана не должен быть ниже '.$lev.'-го уровня<br>';
				echo'2. Для клана необходимо: Название, Описание, эмблема (20х20)<br>';
				echo'3. Стоимость регистрации: '.$gpc.' золотых<br>';
				echo'4. Перед регистрацией клана нужно набрать минимум 3 игроков<br>';
				echo'5. Если в течении месяца клан не будет участвовать в жизни Средиземья - он будет расформирован!<br>';
				$select=myquery("select town from game_map where xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
				list($town)=mysql_fetch_array($select);
				echo'<br><br><center><input type="button" value="Согласен со всеми правилами" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&new=newklan">';
			}
		}
	}

	if ($char['clan_id']==0)
	{
		$sel = myquery("SELECT * FROM game_users_clan_reg WHERE user_id='$user_id' ORDER BY clan_id");
		if (mysql_num_rows($sel))
		{
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

			$width='100%';
			$height='100%';
			echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
			<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top" width="'.$width.'" height="'.$height.'">';

			echo '<table>';
			echo '<th> На твое имя были поданы заявки на прием в кланы: </th><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>';
			while ($z = mysql_fetch_array($sel))
			{
				$sel_clan = myquery("SELECT * FROM game_clans WHERE clan_id = '".$z['clan_id']."'");
				$clan = mysql_fetch_array($sel_clan);
				if ($clan['raz']==0)
				{
					echo '<tr>';
					echo '<td><br>Клан <img border=0 src="http://'.IMG_DOMAIN.'/clan/'.$clan['clan_id'].'.gif">&quot;'.$clan['nazv'].'&quot;&nbsp;&nbsp;';
					echo '&nbsp;<input type="button" value="Подтвердить заявку" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&confirm='.$clan['clan_id'].'">';
					echo '&nbsp;<input type="button" value="Отменить заявку" OnClick=location.href="town.php?option='.$option.'&town_id='.$town.'&del_confirm='.$clan['clan_id'].'"></td>';
					echo '</tr>';
				}
			}
			echo '<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td align=center valign=top><font face=arial>Ты можешь подтвердить заявку на прием в клан, нажав на кнопку &quot;Подтвердить заявку&quot;</font></td></tr></table>';
		}
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>