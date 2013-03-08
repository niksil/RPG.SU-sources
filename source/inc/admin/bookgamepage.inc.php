<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['quest'] >= 1)
{
	$book = mysql_fetch_array(myquery("SELECT * FROM bookgame WHERE id=".$_GET['book'].""));
	echo '<h3>Книга-игра: <b>'.$book['name'].'</b></h3>';
	echo '<a href=admin.php?opt=main&option=bookgamepage&book='.$_GET['book'].'>На главную</a><br /><br />';
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		$pm=myquery("SELECT COUNT(*) FROM bookgame_step WHERE bookgame=".$_GET['book']."");
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=25;
		$allpage=ceil(mysql_result($pm,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		$href = '?opt=main&option=bookgamepage&book='.$_GET['book'].'&';
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);

		echo "<table border=0 cellspacing=3 cellpadding=3>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bookgamepage&book=".$_GET['book']."&new>Добавить страницу</a></td></tr>";
		echo "<tr bgcolor=#333333><td>ID</td><td>Текст</td><td></td></tr>";
		$qw=myquery("SELECT * FROM bookgame_step WHERE bookgame=".$_GET['book']." order BY step ASC limit ".(($page-1)*$line).", $line");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td><a href=admin.php?opt=main&option=bookgamepage&book='.$_GET['book'].'&edit='.$ar['step'].'>'.$ar['step'].'</a></td>
			<td>'.$ar['text'].'</td><td>';
			if ($ar['flag']==3) echo '<br /><br />(<i>Старт</i>)';
			if ($ar['flag']==1) echo '<br /><br />(<i>Провал</i>)';
			if ($ar['flag']==2) echo '<br /><br />(<i>Финиш</i>)';
			if ($ar['flag']==4) echo '<br /><br />(<i>Бой</i>)';
			if ($ar['flag']==5) echo '<br /><br />(<i>Удача</i>)';
			echo '</td>
			<td><a href=admin.php?opt=main&option=bookgamepage&book='.$_GET['book'].'&delete='.$ar['step'].'>Удалить запись</a></td>
			</tr>';
		}
		echo'</table>';
		$href = '?opt=main&option=bookgamepage&book='.$_GET['book'].'&';
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
	}

	if(isset($edit))
	{
		if (!isset($save))
		{
			$qw=mysql_fetch_array(myquery("SELECT * FROM bookgame_step where step='$edit' AND bookgame=".$_GET['book'].""));
			include_once('style/tinyMCE/tinyMCE_header.php');
			echo'<form action="" method="post">
			Номер страницы: <input type="text" size="5" name="page" value="'.$qw['step'].'"><br />
			На странице прибавить: <input type="text" size="5" name="add_dex" value="'.$qw['add_dex'].'"> выносливости, <input type="text" size="5" name="add_master" value="'.$qw['add_master'].'"> мастерства, <input type="text" size="5" name="add_lucky" value="'.$qw['add_lucky'].'"> удачи, <input type="text" size="5" name="add_gp" value="'.$qw['add_gp'].'"> монет<br /> 
			Для страницы боя: <input type="checkbox" name="check_lucky" value="1"';
			if ($qw['check_lucky']==1) echo ' checked';
			echo '> есть проверка удачи, <input type="checkbox" name="retreat" value="1"';
			if ($qw['retreat']==1) echo ' checked';
			echo '> есть побег из боя<br />
			Тип страницы: <select id="flag_select" name="flag" onChange="show_hide()" onBlur="show_hide()" onKeyPress="show_hide()">
			<option value="0"'; if ($qw['flag']==0) echo ' selected'; echo '>Обычная</option>
			<option value="1"'; if ($qw['flag']==1) echo ' selected'; echo '>Провал</option>
			<option value="2"'; if ($qw['flag']==2) echo ' selected'; echo '>Финиш</option>
			<option value="3"'; if ($qw['flag']==3) echo ' selected'; echo '>Старт</option>
			<option value="4"'; if ($qw['flag']==4) echo ' selected'; echo '>Бой</option>
			<option value="5"'; if ($qw['flag']==5) echo ' selected'; echo '>Проверка удачи</option>
			</select>
			Текст:'; 
			?>
			<textarea id="elm1" name="elm1" rows="25" cols="80" style="width: 100%">
			<? echo $qw['text']; ?>
			</textarea>
			<?
			echo '<br><br>
			<input name="save" type="submit" value="Сохранить страницу">';
			
			if (isset($_GET['step_to_step']))
			{
				if (isset($_GET['new_page']))
				{
					myquery("INSERT INTO bookgame_step_to_step (bookgame,step_from,step_to,lucky_win,lucky_lose,retreat) VALUES (".$_GET['book'].",$edit,".$_GET['new_page'].",".$_GET['lucky_win'].",".$_GET['lucky_lose'].",".$_GET['retreat'].")");
				}
				if (isset($_GET['del_page']))
				{
					myquery("DELETE FROM bookgame_step_to_step WHERE bookgame=".$_GET['book']." AND step_from=$edit AND step_to=".$_GET['del_page']."");
				}
			}
			
			//редактирование зависимостей страниц
			echo '<br /><br /><hr>';
			echo 'С данной страницы возможен переход на страницы:<br />';
			echo '<input type="text" size="5" value="" id="new_page">&nbsp;&nbsp;<br />
			<div id="div_lucky" style="display:none"><input type="checkbox" value="1" id="lucky_win">Переход при выигрыше удачи<br /><input type="checkbox" value="1" id="lucky_lose">Переход при проигрыше удачи</div><div id="div_combat_retreat" style="display:none"><input type="checkbox" value="1" id="retreat">Переход при побеге из боя</div><br /><br /><input type="button" onClick="location.href=\'admin.php?opt=main&option=bookgamepage&book='.$_GET['book'].'&edit='.$edit.'&step_to_step&new_page=\'+document.getElementById(\'new_page\').value+\'&lucky_win=\'+document.getElementById(\'lucky_win\').checked+\'&lucky_lose=\'+document.getElementById(\'lucky_lose\').checked+\'&retreat=\'+document.getElementById(\'retreat\').checked+\'\'" value="Добавить новый переход"><br /><br /><br />';
			$sel = myquery("SELECT * FROM bookgame_step_to_step WHERE bookgame=".$_GET['book']." AND step_from=$edit");
			while ($s = mysql_fetch_array($sel))
			{
				echo '<input type="text" size="5" value="'.$s['step_to'].'" id="page'.$s['step_to'].'">&nbsp;&nbsp;';
				if ($s['lucky_win']==1) echo '&nbsp;(выигрыш удачи)&nbsp;';
				if ($s['lucky_lose']==1) echo '&nbsp;(проигрыш удачи)&nbsp;';
				if ($s['retreat']==1) echo '&nbsp;(побег из боя)&nbsp;';
				echo '<input type="button" onClick="location.href=\'admin.php?opt=main&option=bookgamepage&book='.$_GET['book'].'&edit='.$edit.'&step_to_step&del_page=\'+document.getElementById(\'page'.$s['step_to'].'\').value+\'\'" value="Удалить переход"><br />';
			}
			echo '<br /><br /><br /><div id="div_combat" style="display:none"><i>Для страницы с типом "бой" надо указать номер страницы, на которую будет выполнен переход в случае победы игрока в бою</i>';
			
			if (isset($_POST['add_npc']))
			{
				myquery("INSERT INTO bookgame_step_npc SET bookgame=".$_GET['book'].",step=$edit,name='".$_POST['name']."',master='".$_POST['master']."',dex='".$_POST['dex']."',lucky='".$_POST['lucky']."'");
			}
			if (isset($_POST['save_npc']))
			{
				$id_npc = $_POST['npc_id'];
				myquery("UPDATE bookgame_step_npc SET bookgame=".$_GET['book'].",step=$edit,name='".$_POST['name']."',master='".$_POST['master']."',dex='".$_POST['dex']."',lucky='".$_POST['lucky']."' WHERE id=$id_npc");
			}
			if (isset($_GET['del_npc']))
			{
				myquery("DELETE FROM bookgame_step_npc WHERE id=".$_GET['del_npc']."");
			}
			//боты для боя на странице
			echo '<br /><br /><hr>';
			echo 'На данной странице начинается бой с противниками:<br />';
			echo '
			<form name="new_npc" method="post" action="admin.php?opt=main&option=bookgamepage&book='.$_GET['book'].'&edit='.$edit.'"><br />
			<table>
			<tr><td>Имя монстра: </td><td><input name="name" type="text" size="50" value=""></td></tr>
			<tr><td>Мастерство: </td><td><input name="master" type="text" size="5" value="0"></td></tr>
			<tr><td>Выносливость: </td><td><input name="dex" type="text" size="5" value="0"></td></tr> 
			<tr><td>Удача: </td><td><input name="lucky" type="text" size="5" value="0"></td></tr> 
			<tr><td></td><td><input type="submit" name="add_npc" value="Добавить нового монстра"></td></tr>
			</table></form>
			';
			$sel = myquery("SELECT * FROM bookgame_step_npc WHERE bookgame=".$_GET['book']." AND step=$edit");
			while ($s = mysql_fetch_array($sel))
			{
				echo '
				<form name="npc'.$s['id'].'" method="post" action="admin.php?opt=main&option=bookgamepage&book='.$_GET['book'].'&edit='.$edit.'">
				<table>
				<tr><td>Имя монстра: </td><td><input name="name" type="text" size="50" value="'.$s['name'].'"></td></tr>
				<tr><td>Мастерство: </td><td><input name="master" type="text" size="5" value="'.$s['master'].'"></td></tr>
				<tr><td>Выносливость: </td><td><input name="dex" type="text" size="5" value="'.$s['dex'].'"></td></tr> 
				<tr><td>Удача: </td><td><input name="lucky" type="text" size="5" value="'.$s['lucky'].'"></td></tr> 
				<tr><td><input type="hidden" name="npc_id" value="'.$s['id'].'"></td><td>
				<input type="submit" name="save_npc" value="Сохранить монстра"></td></tr>
				</table></form>
				<input type="button" onClick="location.href=\'admin.php?opt=main&option=bookgamepage&book='.$_GET['book'].'&edit='.$edit.'&step_to_step&del_npc='.$s['id'].'\'" value="Удалить монстра"><br />';
			}
			?>
			</div>
			<script type="text/javascript" language="JavaScript">
			function show_hide()
			{
				select = document.getElementById("flag_select");
				div = document.getElementById("div_lucky");
				if (select.value==5)
				{
					div.style.display="block";
				}
				else
				{
					div.style.display="none"; 
				}
				div = document.getElementById("div_combat");
				if (select.value==4)
				{
					div.style.display="block";
				}
				else
				{
					div.style.display="none"; 
				}
				div = document.getElementById("div_combat_retreat");
				if (select.value==4)
				{
					div.style.display="block";
				}
				else
				{
					div.style.display="none"; 
				}
			}
			show_hide();
			</script>
			<?
		}
		else
		{
			echo'Запись изменена';
			if (!isset($_POST['check_lucky']))
			{
				$_POST['check_lucky']=0;
			}
			else
			{
				$_POST['check_lucky']=1;
			}
			if (!isset($_POST['retreat']))
			{
				$_POST['retreat']=0;
			}
			else
			{
				$_POST['retreat']=1;
			}
			$up=myquery("update bookgame_step set text='".$_POST['elm1']."',step='".$_POST['page']."',flag='".$_POST['flag']."',add_dex='".$_POST['add_dex']."',add_master='".$_POST['add_master']."',add_lucky='".$_POST['add_lucky']."',add_gp='".$_POST['add_gp']."',check_lucky='".$_POST['check_lucky']."',retreat='".$_POST['retreat']."' where step='$edit' AND bookgame=".$_GET['book']."");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Изменил страницу $edit книги-игры ".$_GET['book']."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bookgamepage&book='.$_GET['book'].'">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			include_once('style/tinyMCE/tinyMCE_header.php');
			$maxpage = mysqlresult(myquery("SELECT step FROM bookgame_step WHERE bookgame=".$_GET['book']." ORDER BY step DESC LIMIT 1"),0,0);
			echo'<form action="" method="post">
			Номер страницы: <input type="text" size="5" name="page" value="'.($maxpage+1).'"><br />
			На странице прибавить: <input type="text" size="5" name="add_dex" value="0"> выносливости, <input type="text" size="5" name="add_master" value="0"> мастерства, <input type="text" size="5" name="add_lucky" value="0"> удачи, <input type="text" size="5" name="add_gp" value="0"> монет<br /> 
			Для страницы боя: <input type="checkbox" name="lucky" value="0"> есть проверка удачи, <input type="checkbox" name="retreat" value="0"> есть побег из боя<br />
			Тип страницы: <select name="flag">
			<option value="0" selected>Обычная</option>
			<option value="1">Провал</option>
			<option value="2">Финиш</option>
			<option value="3">Старт</option>
			<option value="4">Бой</option>
			<option value="5">Проверка удачи</option>
			</select><br /><br />
			Текст:'; 
			?>
			<textarea id="elm1" name="elm1" rows="25" cols="80" style="width: 100%">
			</textarea>
			<?
			echo '<br><br>
			<input name="save" type="submit" value="Добавить страницу"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Запись добавлена';
			if (!isset($_POST['lucky']))
			{
				$_POST['lucky']=0;
			}
			else
			{
				$_POST['lucky']=1;
			}
			if (!isset($_POST['retreat']))
			{
				$_POST['retreat']=0;
			}
			else
			{
				$_POST['retreat']=1;
			}
			$up=myquery("insert into bookgame_step (bookgame,step,text,flag,add_dex,add_master,add_lucky,add_gp,check_lucky,retreat) VALUES (".$_GET['book'].",".$_POST['page'].",'".$_POST['elm1']."',".$_POST['flag'].",".$_POST['add_dex'].",".$_POST['add_master'].",".$_POST['add_lucky'].",".$_POST['add_gp'].",".$_POST['lucky'].",".$_POST['retreat'].")");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Добавил страницу ".$_POST['page']." в книгу-игру ".$_GET['book']."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bookgamepage&book='.$_GET['book'].'&edit='.$_POST['page'].'">';
		}
	}

	if(isset($delete))
	{
		echo'Запись удалена';
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Удалил страницу $delete из книги-игры ".$_GET['book']."',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		$up=myquery("delete from bookgame_step where step='$delete' AND bookgame=".$_GET['book']."");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bookgamepage&book='.$_GET['book'].'">';
	}

}
if (function_exists("save_debug")) save_debug(); 
?>