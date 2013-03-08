<?

if (function_exists("start_debug")) start_debug(); 

if ($char['clan_id']=='1' or $char['name']=='mrHawk')
{
	if(!isset($edit) and !isset($us) and !isset($zaslugi) and !isset($izgn))
	{
		echo'<table border=2 cellspacing="3" cellpadding="1" bgcolor=444444 align=center><tr><td colspan=2 align=center>Администрирование кланов</td></tr>';
		$ql=myquery("select * from game_clans ORDER BY raz,clan_id");
		while ($q=mysql_fetch_array($ql))
		{
			$nameglava = '';
			$glava = myquery("SELECT name FROM game_users WHERE user_id=".$q['glava']."");
			if (!mysql_num_rows($glava)) $glava = myquery("SELECT name FROM game_users_archive WHERE user_id=".$q['glava']."");
			if (mysql_num_rows($glava)) {list($nameglava) = mysql_fetch_array($glava);}
			echo'<tr><td>'.$q['nazv'].' [Глава-'.$nameglava.']';
			if ($q['raz']=='1') echo'<font color=ff0000><b>[!]</b></font>';
			echo'</td><td><input type="button" value="Изменить" OnClick=location.href="admin.php?opt=main&option=dom&opt=main&edit='.$q['clan_id'].'">
			<input type="button" value="Игроки" OnClick=location.href="admin.php?opt=main&option=dom&opt=main&us='.$q['clan_id'].'">
			</td></tr>';
		}
		echo'</table><br><hr><br>';
		
		if (isset($_GET['pay']))
		{
			$tax = mysql_fetch_array(myquery("SELECT * FROM game_clans_taxes WHERE id=".$_GET['pay'].""));
			list($nameclan) = mysql_fetch_array(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$tax['clan_id'].""));
			
			myquery("UPDATE game_clans_taxes SET flag=1,summa=0 WHERE id=".$_GET['pay']."");               
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) VALUES (
			'".$char['name']."',
			'Оплатил долг клана: ".$nameclan." за ".$tax['month'].".".$tax['year']." в сумме ".$tax['summa']."',
			'".time()."',
			'".$da['mday']."',
			'".$da['mon']."',
			'".$da['year']."')
			");
		}
		
		echo'<table border=2 cellspacing="3" cellpadding="3" bgcolor=444444 align=center><tr><td colspan=4 align=center>Текущие долги кланов</td></tr><tr><td>Клан</td><td>Долг за</td><td>Сумма</td><td></td></tr>';
		$ql0=myquery("SELECT DISTINCT clan_id FROM game_clans_taxes WHERE flag=0");
		while (list($clid) = mysql_fetch_array($ql0))
		{
			list($nameclan) = mysql_fetch_array(myquery("SELECT nazv FROM game_clans WHERE clan_id=$clid"));
			$ql=myquery("SELECT * FROM game_clans_taxes WHERE clan_id=$clid AND flag=0 ORDER BY year ASC, month ASC");
			while ($q=mysql_fetch_array($ql))
			{
				echo '<tr><td>'.$nameclan.'</td>';
				echo '<td>'.$q['month'].'.'.$q['year'].'</td><td>'.$q['summa'].'</td><td><input type="button" value="Удалить долг" OnClick=location.href="admin.php?opt=main&option=dom&opt=main&pay='.$q['id'].'"></td></tr>'; 
			}
		}
		echo'</table>';
		
		
	}

	if (isset($edit))
	{
		if (!isset($see))
		{
			echo "<form name=frm method=post>";
			$edit=(int)$edit;
			$w=myquery("select * from game_clans where clan_id='".$edit."'");
			$q=mysql_fetch_array($w);
			echo'<table border=0 cellspacing="1" cellpadding="1" bgcolor=444444 width=95% align=center><tr><td colspan=2 align=center>Редактирование клана</td></tr>';
			echo'<tr><td>Клан N: <b><font color=ff0000>'.$q['clan_id'].'</font></b></td><td></td></tr>';
			$glava = myquery("SELECT name FROM game_users WHERE user_id=".$q['glava']."");
			if (!mysql_num_rows($glava)) $glava = myquery("SELECT name FROM game_users_archive WHERE user_id=".$q['glava']."");
			list($nameglava) = mysql_fetch_array($glava);
			$namezam1 = '';
			$namezam2 = '';
			$namezam3 = '';
			if ($q['zam1']>0)
			{
				$glava = myquery("SELECT name FROM game_users WHERE user_id=".$q['zam1']."");
				if (!mysql_num_rows($glava)) $glava = myquery("SELECT name FROM game_users_archive WHERE user_id=".$q['zam1']."");
				list($namezam1) = mysql_fetch_array($glava);
			}
			if ($q['zam2']>0)
			{
				$glava = myquery("SELECT name FROM game_users WHERE user_id=".$q['zam2']."");
				if (!mysql_num_rows($glava)) $glava = myquery("SELECT name FROM game_users_archive WHERE user_id=".$q['zam2']."");
				list($namezam2) = mysql_fetch_array($glava);
			}
			if ($q['zam3']>0)
			{
				$glava = myquery("SELECT name FROM game_users WHERE user_id=".$q['zam3']."");
				if (!mysql_num_rows($glava)) $glava = myquery("SELECT name FROM game_users_archive WHERE user_id=".$q['zam3']."");
				list($namezam3) = mysql_fetch_array($glava);
			}
			
			echo'<tr><td>Название клана:</td><td><input name="nazv" type="text" value="'.$q['nazv'].'" size="50" maxlength="50"></td></tr>';
			echo'<tr><td>Глава клана:</td><td><input name="glava" type="text" value="'.$nameglava.'" size="50" maxlength="50"></td></tr>';
			echo'<tr><td>1 зам.главы клана:</td><td><input name="zam1" type="text" value="'.$namezam1.'" size="50" maxlength="50"></td></tr>';
			echo'<tr><td>2 зам.главы клана:</td><td><input name="zam2" type="text" value="'.$namezam2.'" size="50" maxlength="50"></td></tr>';
			echo'<tr><td>3 зам.главы клана:</td><td><input name="zam3" type="text" value="'.$namezam3.'" size="50" maxlength="50"></td></tr>';
			echo'<tr><td>Город клана:</td><td>';
			echo'<select name="town_clan">';
			echo'<option></option>';
			$sel = myquery("SELECT town,rustown FROM game_gorod WHERE rustown<>'' ORDER BY BINARY rustown");
			while ($gorod = mysql_fetch_array($sel))
			{
				echo '<option value="'.$gorod['town'].'"';
				if ($q['town']==$gorod['town']) echo ' selected';
				echo '>'.$gorod['rustown'].'</option>';
			}
			echo'</select>';
			echo'</td></tr>';
			echo'<tr><td>Описание:</td><td><textarea name="opis" cols="40" class="input" rows="10">'.$q['opis'].'</textarea></td></tr>';
			echo'<tr><td>Сайт:</td><td><input name="site" type="text" value="'.$q['site'].'" size="50" maxlength="50"></td></tr>';
			echo'<tr><td>Рейтинг:</td><td><input name="raring" type="text" value="'.$q['raring'].'" size="3" maxlength="3"> очков</td></tr>';
			//echo'<tr><td>Заслуги</td><td><textarea name="zaslugi" cols="40" class="input" rows="10">'.$q['zaslugi'].'</textarea></td></tr>';
			echo'<tr><td>Заслуги</td><td><input type="button" name="zaslugi" value="Редактировать заслуги клана" onClick=location.href="admin.php?opt=main&option=dom&zaslugi='.$edit.'"></td></tr>'; 
			echo'<tr><td>Количество побед:</td><td><input name="wins" type="text" value="'.$q['wins'].'" size="3" maxlength="3"></td></tr>';
			echo'<tr><td>Склонность клана:</td><td><select name="sel_sklon">';
			echo '<option value="0"';
			if ($q['sklon']==0) echo ' selected';
			echo '>Без склонности</option>';
			echo '<option value="1"';
			if ($q['sklon']==1) echo ' selected';
			echo '>Нейтральная</option>';
			echo '<option value="2"';
			if ($q['sklon']==2) echo ' selected';
			echo '>Светлая</option>';
			echo '<option value="3"';
			if ($q['sklon']==3) echo ' selected';
			echo '>Темная</option>';
			echo'</select></td></tr>';
			echo'<tr><td></td><td><input name="raz" type="checkbox" value="1" '; if($q['raz']==1) echo'checked'; echo'> Расформировать</td></tr>';
			echo'<tr><td></td><td><input name="submit" type="submit" value="Сохранить"></td></tr>
			<input name="see" type="hidden" value=""></td></tr>';
			echo'</table>';
		}
		else
		{
			$nazv=htmlspecialchars($nazv);
			$site=htmlspecialchars($site);
			$opis=htmlspecialchars($opis);
			$glava=htmlspecialchars($glava);
			$raring=htmlspecialchars($raring);
			if(!isset($raz)) $raz='0';
			
			if ($glava!='')
			{
				$selglava = myquery("SELECT user_id FROM game_users WHERE name='".$glava."'");
				if (!mysql_num_rows($selglava)) $selglava = myquery("SELECT user_id FROM game_users_archive WHERE name='".$glava."'");  
				list($glava) = mysql_fetch_array($selglava);
			}
			if ($zam1!='')
			{
				$selglava = myquery("SELECT user_id FROM game_users WHERE name='".$zam1."'");
				if (!mysql_num_rows($selglava)) $selglava = myquery("SELECT user_id FROM game_users_archive WHERE name='".$zam1."'");  
				list($zam1) = mysql_fetch_array($selglava);
			}
			if ($zam2!='')
			{
				$selglava = myquery("SELECT user_id FROM game_users WHERE name='".$zam2."'");
				if (!mysql_num_rows($selglava)) $selglava = myquery("SELECT user_id FROM game_users_archive WHERE name='".$zam2."'");  
				list($zam2) = mysql_fetch_array($selglava);
			}
			if ($zam3!='')
			{
				$selglava = myquery("SELECT user_id FROM game_users WHERE name='".$zam3."'");
				if (!mysql_num_rows($selglava)) $selglava = myquery("SELECT user_id FROM game_users_archive WHERE name='".$zam3."'");  
				list($zam3) = mysql_fetch_array($selglava);
			}


			$cur = mysql_fetch_array(myquery("SELECT * FROM game_clans WHERE clan_id='".$edit."'"));
			$log = ''.$char['name'].' изменил клан №'.$edit.': ';
			if ($cur['nazv']!=$cur) $log.='Установил новое название: "'.$nazv.'".(старое значение - "'.$cur['nazv'].'") ';
			if ($cur['opis']!=$opis) $log.='Установил новое описание: "'.$opis.'".(старое значение - "'.$cur['opis'].'") ';
			if ($cur['site']!=$site) $log.='Установил новый сайт: "'.$nazv.'".(старое значение - "'.$cur['site'].'") ';
			if ($cur['glava']!=$glava) $log.='Установил нового главу : "'.$glava.'".(старое значение - "'.$cur['glava'].'") ';
			if ($cur['raring']!=$raring) $log.='Установил новый рейтинг: "'.$raring.'".(старое значение - "'.$cur['raring'].'") ';
			//if ($cur['zaslugi']!=$zaslugi) $log.='Установил новые заслуги: "'.$zaslugi.'".(старое значение - "'.$cur['zaslugi'].'") ';
			if ($cur['raz']!=$raz) $log.='Установил признак расформированного клана: "'.$raz.'".(старое значение - "'.$cur['raz'].'") ';
			if ($cur['wins']!=$wins) $log.='Установил количество побед: "'.$wins.'".(старое значение - "'.$cur['wins'].'") ';
			if ($cur['zam1']!=$zam1) $log.='Установил 1 зама главы клана: "'.$zam1.'".(старое значение - "'.$cur['zam1'].'") ';
			if ($cur['zam2']!=$zam2) $log.='Установил 2 зама главы клана: "'.$zam2.'".(старое значение - "'.$cur['zam2'].'") ';
			if ($cur['zam3']!=$zam3) $log.='Установил 3 зама главы клана: "'.$zam3.'".(старое значение - "'.$cur['zam3'].'") ';
			if ($cur['sklon']!=$sel_sklon) $log.='Установил склонность клана: "'.$sel_sklon.'".(старое значение - "'.$cur['sklon'].'") ';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) VALUES (
			'".$char['name']."',
			'".$log."',
			'".time()."',
			'".$da['mday']."',
			'".$da['mon']."',
			'".$da['year']."')
			");

			if ($town_clan=='') $town_clan = 0;
			if ($raz==1)
			{
				//снимаем клеймо клана с вещей
				myquery("UPDATE game_items SET kleymo_nomer=0,kleymo=0,kleymo_id=0 WHERE kleymo=1 AND kleymo_id=".$edit."");
				$result=myquery("UPDATE game_clans SET unreg_time = NOW() WHERE clan_id='".$edit."' AND raz = 0;");
			}
			$result=myquery("update game_clans set nazv='$nazv',opis='$opis',site='$site',glava='$glava',raring='$raring',raz='$raz',wins='$wins',zam1='$zam1',zam2='$zam2',zam3='$zam3',town='$town_clan', sklon='$sel_sklon' where clan_id='".$edit."'");
			echo'Сохранено!<meta http-equiv="refresh" content="1;url=?option=dom&opt=main">';
		}
	}

	if (isset($us))
	{
		$us=(int)$us;
		$qlq=myquery("(select * from game_users where clan_id='$us') UNION (select * from game_users_archive where clan_id='$us')");
		while ($q=mysql_fetch_array($qlq))
		{
			echo''.$q['name'].' <a href="admin.php?opt=main&option=dom&izgn='.$q['user_id'].'">Изгнать</a><br>';
		}
	}

	if (isset($izgn))
	{
		$sel=myquery("(select user_id,clan_id,name from game_users where user_id='".$izgn."') UNION (select user_id,clan_id,name from game_users_archive where user_id='".$izgn."')");
		if (mysql_num_rows($sel))
		{
			list($use,$clan,$user_name)=mysql_fetch_array($sel);
			echo'<center>Игрок изгнан из клана<br>';
			$log = ''.$char['name'].' изгнал из клана №'.mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id='".$clan."'"),0,0).' игрока '.$user_name.'';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) VALUES (
			'".$char['name']."',
			'".$log."',
			'".time()."',
			'".$da['mday']."',
			'".$da['mon']."',
			'".$da['year']."')
			");
			$up=myquery("update game_users set clan_items_old='0',clan_id='0' where user_id='".$use."'");
			$up=myquery("update game_users_archive set clan_items_old='0',clan_id='0' where user_id='".$use."'");
			$up=myquery("update game_users_data set clan_rating=0,clan_zvanie='' where user_id='".$use."'");
			echo'<meta http-equiv="refresh" content="1;url=?option=dom&opt=main">';  
		}
	}
	
	if (isset($zaslugi))
	{
		if(!isset($editz) and !isset($newz) and !isset($deletez))
		{
			echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
			echo "<tr bgcolor=#333333><td colspan=3 align=center><a href=admin.php?opt=main&option=dom&zaslugi=$zaslugi&newz>Добавить запись</a></td></tr>";
			echo "<tr bgcolor=#333333><td></td><td>Описание</td><td></td></tr>";
			$qw=myquery("SELECT * FROM game_clans_zaslugi WHERE clan_id=$zaslugi order BY id ASC");
			$i=0;
			while($ar=mysql_fetch_array($qw))
			{
				$i++;
				echo'<tr>
				<td><a href=admin.php?opt=main&option=dom&zaslugi='.$zaslugi.'&editz='.$ar['id'].'>'.$i.'</a></td>
				<td>'.$ar['zaslugi'].'</td>
				<td><a href=admin.php?opt=main&option=dom&zaslugi='.$zaslugi.'&deletez='.$ar['id'].'>Удалить запись</a></td>
				</tr>';
			}
			echo'</table>';
		}

		if(isset($editz))
		{
			if (!isset($save))
			{
				$qw=myquery("SELECT * FROM game_clans_zaslugi where id='$editz'");
				$ar=mysql_fetch_array($qw);
				echo'<form action="" method="post">
				Описание: <textarea name=zaslugi_text cols=70 class=input rows=10>'.$ar['zaslugi'].'</textarea><br><br>
				<input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
			}
			else
			{
				echo'Запись изменена';
				$zaslugi_text = htmlspecialchars($zaslugi_text);
				$up=myquery("update game_clans_zaslugi set zaslugi='$zaslugi_text' where id='$editz'");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				 VALUES (
				 '".$char['name']."',
				 'Изменил запись заслуги клана ".$zaslugi." №".$editz.": ".$zaslugi_text."',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
				echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=dom&zaslugi='.$zaslugi.'">';
			}
		}


		if(isset($newz))
		{
			if (!isset($save))
			{
				echo'<form action="" method="post">
				Описание: <textarea name=zaslugi_text cols=70 class=input rows=10></textarea><br><br>
				<input name="save" type="submit" value="Добавить запись"><input name="save" type="hidden" value="">';
			}
			else
			{
				$zaslugi_text = htmlspecialchars($zaslugi_text);
				echo'Запись добавлена';
				$up=myquery("insert into game_clans_zaslugi (clan_id,zaslugi) VALUES ('$zaslugi','$zaslugi_text')");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				 VALUES (
				 '".$char['name']."',
				 'Добавил заслугу клану ".$zaslugi.": <b>".$zaslugi_text."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
				echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=dom&zaslugi='.$zaslugi.'">';
			}
		}

		if(isset($deletez))
		{
			echo'Запись удалена';
			$nazv = mysql_result(myquery("SELECT zaslugi FROM game_clans_zaslugi where id='$deletez'"),0,0);
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Удалил заслугу клана ".$zaslugi.": <b>".$nazv."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$up=myquery("delete from game_clans_zaslugi where id='$deletez'");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=dom&zaslugi='.$zaslugi.'">';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>