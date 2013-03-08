<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['koni'] >= 1)
{
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=koni&new>Добавить коня</a></td></tr>";
		echo "<tr bgcolor=#333333><td>Название</td><td>Цена</td><td>Уровень</td><td>Перенос</td><td>Жизней</td><td>Стоимость еды</td><td></td></tr>";
		$qw=myquery("SELECT * FROM game_vsadnik order BY id ASC");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td><a href=admin.php?opt=main&option=koni&edit='.$ar['id'].'>'.$ar['nazv'].'</a></td>
			<td>'.$ar['cena'].'</td>
			<td>'.$ar['vsad'].'</td>
			<td>'.$ar['ves'].'</td> 
			<td>'.$ar['life_horse'].'</td>
			<td>'.$ar['price_eat'].'</td>
			<td><a href=admin.php?opt=main&option=koni&delete='.$ar['id'].'>Удалить коня</a></td>
			</tr>';
		}
		echo'</table>';
	}

	if(isset($edit))
	{
		if (!isset($save))
		{
			$qw=myquery("SELECT * FROM game_vsadnik where id='$edit'");
			$ar=mysql_fetch_array($qw);
			echo'<form action="" method="post">
			Название: <input type=text name=nazv value="'.$ar['nazv'].'" size=100><br>
			Повышает максимальный вес на: <input type=text name=ves value="'.$ar['ves'].'" size=3><br>
			Требует уровень навыка всадника: <input type=text name=vsad value="'.$ar['vsad'].'" size=2><br>
			Цена: <input type=text name=cena value="'.$ar['cena'].'" size=8> монет<br>
			Длительность жизни ( в календ. днях ): <input type=text name=life_horse value="'.$ar['life_horse'].'" size=8><br>
			Цена ежедневной кормежки : <input type=text name=price_eat value="'.$ar['price_eat'].'" size=8> монет<br>
			Картинка: images\vsd\<input type=text name=img_koni value="'.$ar['img'].'" size=30>.jpg <br>';
			echo'Город продажи: <select name="town">';
			$result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY rustown");
			while($t=mysql_fetch_array($result))
			{
			echo '<option value="'.$t['town'].'"';
			if ($ar['town']==$t['town']) echo ' selected';
			echo'>'.$t['rustown'].'</option>';
			}
			echo '</select><br>
			Описание коня: <textarea name=opis cols=70 class=input rows=10>'.$ar['opis'].'</textarea><br><br>
			<input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Конь изменен';
			$up=myquery("update game_vsadnik set price_eat='$price_eat',life_horse='$life_horse', nazv='$nazv', ves='$ves',vsad='$vsad',cena='$cena',img='$img_koni',opis='$opis',town='$town' where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил коня: <b>".$nazv."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=koni">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post">
			Название: <input type=text name=nazv value="" size=100><br>
			Повышает максимальный вес на: <input type=text name=ves value="" size=3><br>
			Требует уровень навыка всадника: <input type=text name=vsad value="" size=2><br>
			Цена: <input type=text name=cena value="" size=8> монет<br>
			Длительность жизни ( в календ. днях ): <input type=text name=life_horse value="530" size=8> монет<br>
			Цена ежедневной кормежки : <input type=text name=price_eat value="30" size=8> монет<br>
			Картинка: images\vsd\<input type=text name=img_koni value="" size=30>.jpg <br>';
			echo'Город продажи: <select name="town">';
			$result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY rustown");
			while($t=mysql_fetch_array($result))
			{
			echo '<option value="'.$t['town'].'">'.$t['rustown'].'</option>';
			}
			echo '</select><br>
			Описание коня: <textarea name=opis cols=70 class=input rows=10></textarea><br><br>
			<input name="save" type="submit" value="Добавить коня"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Конь добавлен';
			$up=myquery("insert into game_vsadnik (price_eat,life_horse,nazv,ves,vsad,cena,img,town,opis) VALUES ('$price_eat','$life_horse','$nazv','$ves','$vsad','$cena','$img_koni','$town','$opis')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил коня: <b>".$nazv."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=koni">';
		}
	}

	if(isset($delete))
	{
		echo'Конь удален';
		$nazv = mysql_result(myquery("SELECT nazv FROM game_vsadnik where id='$delete'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Удалил коня: <b>".$nazv."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		$up=myquery("delete from game_vsadnik where id='$delete'");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=koni">';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>