<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['resource'] >= 1)
{
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
		echo "<tr bgcolor=#333333><td colspan=5 align=center><a href=admin.php?opt=main&option=resource&new>Добавить ресурс</a></td></tr>";
		echo "<tr bgcolor=#333333><td>№</td><td>Название</td><td>Вес</td><td coslpan=2></td></tr>";
		$qw=myquery("SELECT * FROM craft_resource order BY id ASC");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td>'.$ar['id'].'</td>
			<td><a href=admin.php?opt=main&option=resource&edit='.$ar['id'].'>'.$ar['name'].'</a></td>
			<td>'.$ar['weight'].'</td>
			<td><a href=admin.php?opt=main&option=resource&delete='.$ar['id'].'>Удалить ресурс</a></td>
			<td><img src="http://'.IMG_DOMAIN.'/item/resources/'.$ar['img3'].'.gif"</td>
			</tr>';
		}
		echo'</table>';
	}

	if(isset($edit))
	{
		if (!isset($save))
		{
			$qw=myquery("SELECT * FROM craft_resource where id='$edit'");
			$ar=mysql_fetch_array($qw);
			echo'<form action="" method="post">
			<table>
			<tr><td>Название: </td><td><input type=text name=name value="'.$ar['name'].'" size=100></td></tr>
			<tr><td>Вес 1 ед. ресурса: </td><td><input type=text name=weight value="'.$ar['weight'].'" size=10></td></tr>
			<tr><td>Цена покупки за 1 ед. ресурса: </td><td><input type=text name=incost value="'.$ar['incost'].'" size=10> монет</td></tr>
			<tr><td>Цена продажи за 1 ед. ресурса: </td><td><input type=text name=outcost value="'.$ar['outcost'].'" size=10> монет</td></tr>
			<tr><td>Добыча прокачивает навык</td><td>';
			echo '<select name=spets>
			<option value=\'\'>Нет навыка</option>
			<option value=sobiratel'; if ($ar['spets']=='sobiratel') echo ' selected'; echo '>Собирательство</option>
			<option value=minestone'; if ($ar['spets']=='minestone') echo ' selected'; echo '>Добыча камня</option>
			<option value=mineore'; if ($ar['spets']=='mineore') echo ' selected'; echo '>Добыча руды</option>
			<option value=minewood'; if ($ar['spets']=='minewood') echo ' selected'; echo '>Добыча дерева</option>
			<option value=minemetal'; if ($ar['spets']=='minemetal') echo ' selected'; echo '>Добыча металла</option>
			</select>';
			echo '</td></tr>
			<tr><td>Для повышения уровня навыка требуется</td><td><input type=text name=need_count_for_level value="'.$ar['need_count_for_level'].'" size=10> действий по добыче</td></tr>
			<tr><td>Каждый уровень навыка снижает время добычи на</td><td><input type=text name=decrease_rab_time value="'.$ar['decrease_rab_time'].'" size=10> секунд</td></tr>
			<tr><td>Каждый уровень навыка повышает шанс добычи на</td><td><input type=text name=increase_chance value="'.$ar['increase_chance'].'" size=10> %</td></tr>
			<tr><td>Картинка 20x20: images\item\resources\</td><td><input type=text name=img1 value="'.$ar['img1'].'" size=30>.gif</td></tr>
			<tr><td>Картинка 30x30: images\item\resources\</td><td><input type=text name=img2 value="'.$ar['img2'].'" size=30>.gif</td></tr>
			<tr><td>Картинка 50x50: images\item\resources\</td><td><input type=text name=img3 value="'.$ar['img3'].'" size=30>.gif</td></tr>
			<tr><td><input name="save" type="submit" value="Сохранить"></td><td><input name="save" type="hidden" value=""></td></tr></table>';
		}
		else
		{
			echo'Ресурс изменен';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил ресурс: <b>".mysql_result(myquery("SELECT name FROM craft_resource WHERE id=$edit"),0,0)."</b> на <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$up=myquery("update craft_resource set name='$name',weight='$weight',incost='$incost',outcost='$outcost',img1='$img1',img2='$img2',img3='$img3',spets='$spets', need_count_for_level='$need_count_for_level', decrease_rab_time='$decrease_rab_time', increase_chance='$increase_chance' where id='$edit'");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=resource">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post">
			<table>
			<tr><td>Название: </td><td><input type=text name=name value="" size=100></td></tr>
			<tr><td>Вес 1 ед. ресурса: </td><td><input type=text name=weight value="" size=10></td></tr>
			<tr><td>Цена покупки за 1 ед. ресурса: </td><td><input type=text name=incost value="" size=10> монет</td></tr>
			<tr><td>Цена продажи за 1 ед. ресурса: </td><td><input type=text name=outcost value=""" size=10> монет</td></tr>
			<tr><td>Добыча прокачивает навык</td><td>';
			echo '<select name=spets>
			<option value=\'\'>Нет навыка</option>
			<option value=sobiratel>Собирательство</option>
			<option value=minestone>Добыча камня</option>
			<option value=mineore>Добыча руды</option>
			<option value=minewood>Добыча дерева</option>
			<option value=minemetal>Добыча металла</option>
			</select>';
			echo '</td></tr>
			<tr><td>Для повышения уровня навыка требуется</td><td><input type=text name=need_count_for_level value="" size=10> действий по добыче</td></tr>
			<tr><td>Каждый уровень навыка снижает время добычи на</td><td><input type=text name=decrease_rab_time value="" size=10> секунд</td></tr>
			<tr><td>Каждый уровень навыка повышает шанс добычи на</td><td><input type=text name=increase_chance value="" size=10> %</td></tr>
			<tr><td>Картинка 20x20: images\item\resources\</td><td><input type=text name=img1 value="" size=30>.gif</td></tr>
			<tr><td>Картинка 30x30: images\item\resources\</td><td><input type=text name=img2 value="" size=30>.gif</td></tr>
			<tr><td>Картинка 50x50: images\item\resources\</td><td><input type=text name=img3 value="" size=30>.gif</td></tr>
			<tr><td><input name="save" type="submit" value="Добавить ресурс"></td><td><input name="save" type="hidden" value=""></td></tr></table>';
		}
		else
		{
			echo'Ресурс добавлен';
			$up=myquery("insert into craft_resource (name,weight,incost,outcost,img1,img2,img3,spets,need_count_for_level,decrease_rab_time,increase_chance) VALUES ('$name','$weight','$incost','$outcost','$img1','$img2','$img3','$spets','$need_count_for_level','$decrease_rab_time','$increase_chance')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил новый ресурс: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=resource">';
		}
	}

	if(isset($delete))
	{
		echo ('<center><b> Вы действительно хотите удалить ресурс? 
		<form method="Post">
		<table><tr>
		<td width="60px"><input type="submit" name="resdel" value="Да" style="width: 45px"></input></td>
		<td width="60px"><input type="submit" name="resnodel" value="Нет" style="width: 45px"></input></td>
		</b></center></tr></table>');
		if (isset($_POST['resdel']))
		{
			echo'<br />Ресурс удален';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Удалил ресурс: <b>".mysql_result(myquery("SELECT name FROM craft_resource WHERE id=$delete"),0,0)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$up=myquery("delete from craft_resource_user where res_id='$delete'");
			$up=myquery("delete from craft_resource where id='$delete'");
		}
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=resource">';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>