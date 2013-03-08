<?php
if ($adm['lr'] >= 1)
{
	echo '<center>';
	if (isset($user))
	{
		if (isset($serv))
		{
			list($id)=mysql_fetch_array(myquery("Select user_id From game_users Where name='$user' Union Select user_id From game_users_archive Where name='$user'"));
			list($cost)=mysql_fetch_array(myquery("Select cost From game_lr_services Where serv_id=$serv_id"));
			list($check)=mysql_fetch_array(myquery("SELECT user_rating From game_users_data Where user_id=$id"));
			if (mysql_num_rows(myquery("Select * from game_lr_services_hist where user_id=$id"))>0)
			{
				list($lr_old)=mysql_fetch_array(myquery("Select sum(lr) from game_lr_services_hist where user_id=$id"));
				$check=$check-$lr_old;
			} 
			if ($check-$cost>=0)
			{
				myquery("Insert into game_lr_services_hist (user_id, serv_id, lr, value) Values ($id, $serv_id, $cost, 'adm:".$user_id."')");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					 '".$char['name']."',
					 'Заплатил ЛР игрока <b>".$user."</b> за услугу: <b>".mysql_result(myquery("SELECT name FROM game_lr_services Order by serv_id Desc Limit 1"),0,0)."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				echo 'Игрок заплатил за услугу!';
			}
			else
			{
				echo 'У игрока недостаточно ЛР для покупки услуги!';
			}
			echo '<meta http-equiv="refresh" content="5;url=?opt=main&option=lr_services">';
		}
		else
		{
			list($id)=mysql_fetch_array(myquery("Select user_id From game_users Where name='$name' Union Select user_id From game_users_archive Where name='$name'"));
			list($check)=mysql_fetch_array(myquery("SELECT user_rating From game_users_data Where user_id=$id"));
			if (mysql_num_rows(myquery("Select * from game_lr_services_hist where user_id=$id"))>0)
			{
				list($lr_old)=mysql_fetch_array(myquery("Select sum(lr) from game_lr_services_hist where user_id=$id"));
				$check=$check-$lr_old;
			} 
			echo 'У игрока есть <b>'.$check.'</b> ЛР<br/><br/>';
			echo 'Заплатить ЛР игрока за услугу:<br/>';
			echo '<form method="post" action="admin.php?opt=main&option=lr_services&user='.$name.'&serv">';
			$serv_list=myquery("Select serv_id, name From game_lr_services");
			echo '<select name="serv_id">';
			while ($serv=mysql_fetch_array($serv_list))
			{
				echo '<option value='.$serv['serv_id'].'>'.$serv['name'].'</option>';
			}
			echo '</select>';
			echo '<br/><br/><input type="submit" value="Заплатить"></form>';
			echo '</form>';
			
			$stat1=myquery("Select * From game_lr_services_hist Where user_id=$id");
			if (mysql_num_rows($stat1)>0)
			{
				echo '<br/><br/><b>Покупки услуг за ЛР игрока<b><br/><br/>';
				echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
					  <td width="300"><b>Услуга</b></td>
					  <td width="150"><b>Потраченный ЛР</b></td>
					  <td width="150"><b>Дата</b></td></tr>
					 ';
				while ($stat=mysql_fetch_array($stat1))
				{
					list($serv)=mysql_fetch_array(myquery("Select name From game_lr_services Where serv_id=".$stat['serv_id'].""));
					echo '<tr align="center">';
					echo '<td>'.$serv.'</td>';
					echo '<td>'.$stat['lr'].'</td>';
					echo '<td>'.$stat['date'].'</td></tr>';
				} 
				echo '</table>';
			}
		}
	}
	elseif (isset($stat))
	{
		$stat1=myquery("Select * From game_lr_services_hist Order By id Desc Limit 0, 10");
		if (mysql_num_rows($stat1)>0)
		{
			echo '<b>10 последних покупок за ЛР<b><br/><br/>';
			echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
		 <td width="200"><b>Игрок</b></td>
		 <td width="300"><b>Услуга</b></td>
		 <td width="150"><b>Потраченный ЛР</b></td>
		 <td width="150"><b>Дата</b></td></tr>
		 ';
			while ($stat=mysql_fetch_array($stat1))
			{
				list($name)=mysql_fetch_array(myquery("SELECT name FROM game_users Where user_id=".$stat['user_id']."
                                                 Union Select name From game_users_archive Where user_id=".$stat['user_id'].""));
				list($serv)=mysql_fetch_array(myquery("Select name From game_lr_services Where serv_id=".$stat['serv_id'].""));
				echo '<tr align="center">';
				echo '<td>'.$name.'</td>';
				echo '<td>'.$serv.'</td>';
				echo '<td>'.$stat['lr'].'</td>';
				echo '<td>'.$stat['date'].'</td></tr>';
			}
			echo '</table>';
			
			echo '<br/><br/><b>10 игроков, потративших больше всего рейтинга<b><br/><br/>';
			$stat2=myquery("Select user_id, sum(lr) as sum From game_lr_services_hist Group By user_id Order By sum Desc Limit 0, 10");
			echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
		 <td width="200"><b>Игрок</b></td>
		 <td width="150"><b>Потраченный ЛР</b></td></tr>
		 ';
			while ($stat=mysql_fetch_array($stat2))
			{
				list($name)=mysql_fetch_array(myquery("SELECT name FROM game_users Where user_id=".$stat['user_id']."
                                                 Union Select name From game_users_archive Where user_id=".$stat['user_id'].""));
				echo '<tr align="center">';
				echo '<td>'.$name.'</td>';
				echo '<td>'.$stat['sum'].'</td></tr>';
			}
			echo '</table><br/><br/>';
		}
		else
		{
			echo 'За ЛР ещё ничего не покупалось!';
			echo '<meta http-equiv="refresh" content="5;url=?opt=main&option=lr_services">';
		}
	}
	elseif (isset($new))
	{
		if (isset($do))
		{
			if (isset($_POST['name']) and isset($_POST['cost']) and isset($id) and $_POST['cost']<>'' and $_POST['name']<>''and $id<>'')
			{
				myquery("Insert Into game_lr_services (serv_id, name, cost) Values($id,'".$_POST['name']."', ".$_POST['cost'].")");
				echo 'Услуга добавлена!';
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					 '".$char['name']."',
					 'Добавил ЛР услугу: <b>".mysql_result(myquery("SELECT name FROM game_lr_services Order by serv_id Desc Limit 1"),0,0)."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
			}
			else
			{
				echo 'Вы что-то не ввели!';
			}
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=lr_services">';
		}
		else
		{
			echo 'Введите параметры услуги:<br/><br/>';
			echo '<form method="post" action="admin.php?opt=main&option=lr_services&new&do">';
			echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" >';
			echo '<tr><td align="center" width="150"><b>Поле</b></td><td width="250" align="center"><b>Значение<b/></td></tr>';
			echo '<tr><td align="center">Номер услуги:</td><td><input type="text" size="5" maxlength="5" name="id"></td></tr>';
			echo '<tr><td align="center">Название услуги:</td><td><input type="text" size="50" maxlength="50" name="name"></td></tr>';
			echo '<tr><td align="center">Цена услуги:</td><td><input type="text" size="5" maxlength="5" name="cost"></td></tr>';
			echo '</table>';
			echo '<br/><input type="submit" value="Добавить услугу"></form>';
		}
	}
	
	elseif (isset($edit))
	{
		if (isset($do))
		{
			if (isset($_POST['name']) and isset($_POST['cost']) and isset($id) and $_POST['cost']<>'' and $_POST['name']<>'' and $id<>'')
			{
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					 '".$char['name']."',
					 'Изменил ЛР услугу: <b>".mysql_result(myquery("SELECT name FROM game_lr_services WHERE serv_id=$do"),0,0)."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				myquery("Update game_lr_services Set serv_id=$id, name='".$_POST['name']."', cost='".$_POST['cost']."' Where serv_id=$do");
				echo 'Услуга изменена!';

			}
			else
			{
				echo 'Вы что-то не ввели!';
			}
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=lr_services">';
		}
		else
		{
			$check=myquery("Select * from game_lr_services Where serv_id=$edit");
			if (mysql_num_rows($check)>0)
			{
				$serv=mysql_fetch_array($check);
				echo 'Введите параметры услуги:<br/><br/>';
				echo '<form method="post" action="admin.php?opt=main&option=lr_services&edit&do='.$serv['serv_id'].'">';
				echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" >';
				echo '<tr><td align="center" width="150"><b>Поле</b></td><td width="250" align="center"><b>Значение<b/></td></tr>';
				echo '<tr><td align="center">Номер услуги:</td><td><input type="text" size="5" maxlength="5" value="'.$serv['serv_id'].'" name="id"></td></tr>';
				echo '<tr ><td align="center">Название услуги:</td><td><input type="text" size="50" maxlength="50" value="'.$serv['name'].'" name="name"></td></tr>';
				echo '<tr ><td align="center">Цена услуги:</td><td><input type="text" maxlength="5" size="5" value="'.$serv['cost'].'" name="cost"></td></tr>';
				echo '</table>';
				echo '<br/><input type="submit" value="Изменить услугу"></form>';
			}
		}
	}
	
	elseif (isset($del))
	{
		
		if (isset($_POST['servdel']))
		{
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					 '".$char['name']."',
					 'Удалил ЛР услугу: <b>".mysql_result(myquery("SELECT name FROM game_lr_services WHERE serv_id=$del"),0,0)."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
			myquery("Delete from game_lr_services Where serv_id=$del");
			echo 'Услуга удалена!';
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=lr_services">';
		}
		elseif (isset($_POST['servnodel']))
		{
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=lr_services">';
		}
		else
		{	
			echo ('<b> Вы действительно хотите удалить услугу? 
							<form method="Post">
							<table><tr>
							<td width="60px"><input type="submit" name="servdel" value="Да" style="width: 45px"></input></td>
							<td width="60px"><input type="submit" name="servnodel" value="Нет" style="width: 45px"></input></td>
							</b></tr></table>');
		}
	}
	
	else
	{
		echo "<a href=admin.php?opt=main&option=lr_services&new>Добавить новую услугу</a></br/><br/>";
		echo "<a href=admin.php?opt=main&option=lr_services&stat>Просмотреть статистику</a></br/></br/>";
		echo'<form action="admin.php?opt=main&option=lr_services&user" method="post">
			Имя: <input name="name" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)">
			     <div style="display:none;" id="scroll"><div id="suggest"></div></div>
			     <br/><input name="submit" type="submit" value="ЛР игрока">
		      	 </form></div><script>init();</script>';
			
		echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
		 <td width="60"><b>Номер</b></td>
		 <td width="300"><b>Название услуги</b></td>
		 <td width="130"><b>Стоимость в ЛР</b></td>
		 <td width="180"><b>Действие</b></td></tr>
		 ';
		$check=myquery("Select * from game_lr_services Order By serv_id");
		while ($lr_serv=mysql_fetch_array($check))
		{
			echo '<tr align="center">';
			echo '<td>'.$lr_serv['serv_id'].'</td>';
			echo '<td>'.$lr_serv['name'].'</td>';
			echo '<td>'.$lr_serv['cost'].'</td>';
			echo '<td><input type="button"  style="width: 80px" onClick="location.href=\'admin.php?opt=main&option=lr_services&edit='.$lr_serv['serv_id'].'\'" value="Изменить">&nbsp;&nbsp;&nbsp;
			      <input type="button"  style="width: 80px" onClick="location.href=\'admin.php?opt=main&option=lr_services&del='.$lr_serv['serv_id'].'\'" value="Удалить"></td></tr>';
			echo '</tr>';	  
		}	
		echo '</table>';
	}
	echo '</center>';
} 
?>
