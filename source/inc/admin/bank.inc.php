<?

if (function_exists("start_debug")) start_debug(); 
if ($adm['bank']>=1)
{
	if(!isset($_REQUEST['edit']) and !isset($_REQUEST['new']) and !isset($_REQUEST['delete']) and !isset($_REQUEST['log']) and !isset($_REQUEST['kredit']) and !isset($_REQUEST['vklad']))
	{
		$pm=myquery("select count(*) from game_bank WHERE summa>0 ");
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=25;
		$allpage=ceil(mysql_result($pm,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		echo "<table border=0 cellspacing=3 cellpadding=3>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bank&log>Просмотреть лог всех переводов</a></td></tr>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bank&log&usr>Просмотреть лог всех переводов одного игрока</a></td></tr>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bank&log&att>Просмотреть лог подозрительных переводов</a></td></tr>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bank&vklad>Просмотреть вклады</a></td></tr>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bank&kredit>Просмотреть кредиты</a></td></tr>";
		if ($char['clan_id']==1)
		{
			echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bank&new>Добавить запись</a></td></tr>";
			echo "<tr bgcolor=#333333><td>User ID</td><td>Имя</td><td>Сумма</td><td></td></tr>";
			$qw=myquery("SELECT * FROM game_bank WHERE summa>0 order BY summa DESC limit ".(($page-1)*$line).", $line");
			while($ar=mysql_fetch_array($qw))
			{
				$sel_name = myquery("SELECT name FROM game_users WHERE user_id='".$ar['user_id']."'");
				if (!mysql_num_rows($sel_name)) $sel_name = myquery("SELECT name FROM game_users_archive WHERE user_id='".$ar['user_id']."'");
				$name = '';
				if (mysql_num_rows($sel_name)) list($name) = mysql_fetch_array($sel_name);
				echo'<tr>
				<td><a href=admin.php?opt=main&option=bank&edit='.$ar['user_id'].'>'.$ar['user_id'].'</a></td>
				<td>'.$name.'</td>
				<td>'.$ar['summa'].'</td>
				<td><a href=admin.php?opt=main&option=bank&delete='.$ar['user_id'].'>Удалить запись</a></td>
				</tr>';
			}
		}
		echo'</table><br>';
		$href = '?opt=main&option=bank&';
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
	}

	if(isset($edit) AND $char['clan_id']==1)
	{
		if (!isset($save))
		{
			$qw=myquery("SELECT * FROM game_bank where user_id=$edit");
			$ar=mysql_fetch_array($qw);
			$name = @mysql_result(@myquery("(SELECT name FROM game_users WHERE user_id=".$ar['user_id'].") UNION (SELECT name FROM game_users_archive WHERE user_id=".$ar['user_id'].")"),0,0);
			echo'<form action="" method="post">
			Игрок: <input type="text" value='.$name.' name="user_name" size="20" readonly><br>
			<input type="hidden" value='.$ar['user_id'].' name="userid" readonly>
			Сумма: <input type="text" value='.$ar['summa'].' name="summa" size="20"><br>
			<input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Запись изменена';
			$up=myquery("update game_bank set summa=$summa where user_id=$userid");
			$name = @mysql_result(@myquery("(SELECT name FROM game_users WHERE user_id=$userid) UNION (SELECT name FROM game_users_archive WHERE user_id=$userid)"),0,0);
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Для игрока: <b>".$name."</b> изменил сумму в банке на: ".$summa."',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bank">';
		}
	}


	if(isset($new) AND $char['clan_id']==1)
	{
		if (!isset($save))
		{
			echo'<div id="content" onclick="hideSuggestions();"><form action="" method="post">
			Игрок: <input type="text" value="" name="user_name" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div><br>
			Сумма: <input type="text" name="summa" size="20"><br>
			<input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value=""></div><script>init();</script>';
		}
		else
		{
			echo'Запись добавлена';
			$usrid = @mysql_result(@myquery("(SELECT user_id FROM game_users WHERE name='".$user_name."') UNION (SELECT user_id FROM game_users_archive WHERE name='".$user_name."')"),0,0);
			if ($usrid>0)
			{
				$sel = myquery("SELECT * FROM game_bank WHERE user_id='$usrid'");
				if (mysql_num_rows($sel))
				{
					myquery("UPDATE game_bank SET summa=summa+'$summa' WHERE user_id='$usrid'");
				}
				else
				{
					myquery("insert into game_bank (user_id,summa) VALUES ('$usrid','$summa')");
				}
				$name = @mysql_result(@myquery("(SELECT name FROM game_users WHERE user_id='$usrid') UNION (SELECT name FROM game_users_archive WHERE user_id='$usrid')"),0,0);
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 'Для игрока: <b>".$name."</b> добавил сумму в банке: ".$summa."',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
			}
			else
			{
				echo 'Игрока не существует';
			}
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bank">';
		}
	}

	if(isset($delete) AND $char['clan_id']==1)
	{
		echo'Запись удалена';
		$summa = @mysql_result(@myquery("SELECT summa FROM game_bank WHERE user_id='$delete'"),0,0);
		$up=myquery("delete from game_bank where user_id='$delete'");
		$name = @mysql_result(@myquery("(SELECT name FROM game_users WHERE user_id='$delete') UNION (SELECT name FROM game_users_archive WHERE user_id='$delete')"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Для игрока: <b>".$name."</b> удалил сумму в банке: ".$summa."',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bank">';
	}

	if(isset($log))
	{
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=30;
		if (isset($_GET['usr']) AND $_GET['usr']!='')
		{
			$pg=myquery("select count(*) from game_bank_log WHERE name_from='".$_GET['usr']."' OR name_to='".$_GET['usr']."' ORDER BY `time` DESC");
		}
		else
		{
			$pg=myquery("SELECT count(*) FROM game_bank_log WHERE user_id_from>0 AND user_id_to>0 ORDER BY `time` DESC");
		}
		$allpage=ceil(mysql_result($pg,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		
		if (isset($_GET['usr']))
		{
			echo '<div id="content" onclick="hideSuggestions();">Просмотр логов переводов по игроку: <input type="text" size="25" value="'.$_GET['usr'].'" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>&nbsp;&nbsp;<button onClick="location.href=\'admin.php?opt=main&option=bank&usr=\'+document.getElementById(\'keyword\').value+\'&log\'">Просмотреть логи</button></div><script>init();</script>';
		}

		echo'<table cellspacing=3 cellpadding=3 border=0>';
		echo'<tr bgcolor=#383838 ><td rowspan=2 align=center>Время перевода</td><td colspan=3 align=center>От кого</td><td colspan=3 align=center>Кому</td><td rowspan=2 align=center>Сумма</td></tr>';
		echo'<tr bgcolor=#383838 ><td align=center>Имя</td><td align=center>User ID</td><td align=center>Хост</td><td align=center>Имя</td><td align=center>User ID</td><td align=center>Хост</td></tr>';
		if ($page<1) $page=1;
		if (isset($_GET['usr']) AND $_GET['usr']!='')
		{
			$sel=myquery("select * from game_bank_log WHERE name_from='".$_GET['usr']."' OR name_to='".$_GET['usr']."' ORDER BY `time` DESC limit ".(($page-1)*$line).", $line");
		}
		else
		{
			$sel=myquery("select * from game_bank_log WHERE user_id_from>0 AND user_id_to>0 ORDER BY `time` DESC limit ".(($page-1)*$line).", $line");
		}
		$i=0;
		while($log = mysql_fetch_array($sel))
		{
			$i++;
			if($i%2==0) { $col='#D3D3D3';
			}
			else{$col='#A7A7A7';
			}
			$dif = $log['host_from']-$log['host_to'];
			if($dif==0) {
				$col='#FF0000';
			}
			elseif($dif>=-255 AND $dif<=255)
			{
				$col='#FFFF00';
			}
			elseif(isset($att)) {
				continue;
			}
			$t = date("H:i:s d-m-Y",$log['time']);
			echo'<tr bgcolor='.$col.'><td align=center valign=center><font color=000000>'.$t.'</td><td align=center valign=center><font color=000000>'.$log['name_from'].'</td><td align=center valign=center><font color=000000>'.$log['user_id_from'].'</td><td align=center valign=center><font color=000000>'.number2ip($log['host_from']).'</td><td align=center valign=center><font color=000000>'.$log['name_to'].'</td><td align=center valign=center><font color=000000>'.$log['user_id_to'].'</td><td align=center valign=center><font color=000000>'.number2ip($log['host_to']).'</td><td align=center valign=center><font color=000000>'.$log['summa'].'</td></tr>';
		}
		echo'</table>';
		$href = '?opt=main&option=bank&log&';
		if (isset($att))
		{
			$href.='att&';
		}
		if (isset($_GET['usr']))
		{
			$href.='usr='.$_GET['usr'].'&';
		}
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
	}

	if(isset($_REQUEST['kredit']))
	{
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=30;
		$pg=myquery("SELECT count(*) FROM game_bank_db_kr WHERE vid=1 ORDER BY `time_begin` DESC");
		$allpage=ceil(mysql_result($pg,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;
		$da = getdate();
		$current_month = GetGameCalendar_Year($da['year'],$da['mon'],$da['mday'])*12+GetGameCalendar_Month($da['year'],$da['mon'],$da['mday']);
		echo '<b>Сейчас идет <font color=red>'.$current_month.'</font> игровой месяц</b><br/>';

		echo'<table cellspacing=3 cellpadding=3 border=0>';
		echo'<tr bgcolor=#383838 ><td align=center>Игрок</td><td align=center>Сумма кредита нач.</td><td align=center>Сумма кредита кон.</td><td align=center>Игр.мес.нач.</td><td align=center>Игр.мес.кон.</td><td align=center>Время нач.</td><td align=center>Время кон.</td></tr>';

		$sel=myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 ORDER BY `time_begin` DESC limit ".(($page-1)*$line).", $line");
		$i=0;
		while($log = mysql_fetch_array($sel))
		{
			$i++;
			if($i%2==0) { $col='#D3D3D3';
			}
			else{$col='#A7A7A7';
			}
			$t1 = date("H:i:s d-m-Y",$log['time_begin']);
			$t2 = date("H:i:s d-m-Y",$log['time_end']);
			$usr = myquery("SELECT name FROM game_users WHERE user_id=".$log['user_id']."");
			if (!mysql_num_rows($usr)) $usr = myquery("SELECT name FROM game_users_archive WHERE user_id=".$log['user_id']."");
			list($usrname) = mysql_fetch_array($usr);
			echo'<tr bgcolor='.$col.'><td align=center valign=center><font color=000000>'.$usrname.'</td><td align=center valign=center><font color=000000>'.$log['summa_begin'].'</td><td align=center valign=center><font color=000000>'.$log['summa_end'].'</td><td align=center valign=center><font color=000000>'.$log['game_month_begin'].'</td><td align=center valign=center><font color=000000>'.$log['game_month_end'].'</td><td align=center valign=center><font color=000000>'.$t1.'</td><td align=center valign=center><font color=000000>'.$t2.'</td></tr>';
		}
		echo'</table>';
		$href = '?opt=main&option=bank&kredit&';
		if (isset($att))
		{
			$href.='att&';
		}
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
	}
	if(isset($_REQUEST['vklad']))
	{
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=30;
		$pg=myquery("SELECT count(*) FROM game_bank_db_kr WHERE vid=2 ORDER BY `time_begin` DESC");
		$allpage=ceil(mysql_result($pg,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;
		$da = getdate();
		$current_month = GetGameCalendar_Year($da['year'],$da['mon'],$da['mday'])*12+GetGameCalendar_Month($da['year'],$da['mon'],$da['mday']);
		echo '<b>Сейчас идет <font color=red>'.$current_month.'</font> игровой месяц</b><br/>';

		echo'<table cellspacing=3 cellpadding=3 border=0>';
		echo'<tr bgcolor=#383838 ><td align=center>Игрок</td><td align=center>Сумма вклада нач.</td><td align=center>Сумма вклада кон.</td><td align=center>Игр.мес.нач.</td><td align=center>Игр.мес.кон.</td><td align=center>Время нач.</td><td align=center>Время кон.</td></tr>';

		$sel=myquery("SELECT * FROM game_bank_db_kr WHERE vid=2 ORDER BY `time_begin` DESC limit ".(($page-1)*$line).", $line");
		$i=0;
		while($log = mysql_fetch_array($sel))
		{
			$i++;
			if($i%2==0) { $col='#D3D3D3';
			}
			else{$col='#A7A7A7';
			}
			$t1 = date("H:i:s d-m-Y",$log['time_begin']);
			$t2 = date("H:i:s d-m-Y",$log['time_end']);
			$usr = myquery("SELECT name FROM game_users WHERE user_id=".$log['user_id']."");
			if (!mysql_num_rows($usr)) $usr = myquery("SELECT name FROM game_users_archive WHERE user_id=".$log['user_id']."");
			list($usrname) = mysql_fetch_array($usr);
			echo'<tr bgcolor='.$col.'><td align=center valign=center><font color=000000>'.$usrname.'</td><td align=center valign=center><font color=000000>'.$log['summa_begin'].'</td><td align=center valign=center><font color=000000>'.$log['summa_end'].'</td><td align=center valign=center><font color=000000>'.$log['game_month_begin'].'</td><td align=center valign=center><font color=000000>'.$log['game_month_end'].'</td><td align=center valign=center><font color=000000>'.$t1.'</td><td align=center valign=center><font color=000000>'.$t2.'</td></tr>';
		}
		echo'</table>';
		$href = '?opt=main&option=bank&vklad&';
		if (isset($att))
		{
			$href.='att&';
		}
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
	}
				
	echo '<br><br><a href="?opt=main&option=bank">На главную</a>';
}

if (function_exists("save_debug")) save_debug(); 

?>