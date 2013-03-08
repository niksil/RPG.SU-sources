<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['mine'] >= 1)
{
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		echo 'Цветом выделены постройки, которые не могут строить игроки (например, поляны, леса и т.п.)';
		echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=craft&new>Добавить шахту</a></td></tr>";
		echo "<tr bgcolor=#333333><td>Название</td><td>Постройка</td><td>Добыча</td><td></td></tr>";
		$qw=myquery("SELECT * FROM craft_build WHERE dom<>'1' order BY id ASC");
		while($ar=mysql_fetch_array($qw))
		{
			if ($ar['item']>0)
			{
				$item_need = @mysql_result(myquery("SELECT name FROM game_items_factsheet WHERE id=".$ar['item'].""),0,0); 
			}
			else
			{
				$item_need = '';
			}
			echo'<tr'; if ($ar['admin']==1) echo ' bgcolor="#000080"'; echo '>
			<td><a href=admin.php?opt=main&option=craft&edit='.$ar['id'].'>'.$ar['name'].'</a></td>
			<td>';
			echo 'Цена постройки - '.$ar['cost'].' монет<br>';
			echo '<span style="color:pink;">Время постройки - '.$ar['create_time'].' секунд</span><br>';
			echo 'Уровень строителя - '.$ar['lev_need'].'<br>';
			$res = $ar['res_need'];
			if ($res!='')
			{
				$a = explode('|',$res);
				for ($i=0;$i<sizeof($a);$i++)
				{
					$b = explode('-',$a[$i]);

					list($resource) = mysql_fetch_array(myquery("SELECT name FROM craft_resource WHERE id=".$b[0].""));
					echo '<span style="color:lightyellow;">Ресурс '.$resource.' - '.$b[1].'</span><br>';
				}
			}
			echo '</td>
			<td>';
			echo 'Кол-во раб.мест - '.$ar['col'].'<br>';
			echo '<span style="color:pink;">Время добычи - '.$ar['rab_time'].' секунд</span><br>';
			if ($ar['race']!=0) echo 'Только для расы - '.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$ar['race'].""),0,0).'<br>';
			if ($ar['clevel']>0) echo 'Начиная с уровня - '.$ar['clevel'].'<br>';
			if ($ar['item']!='') echo 'Требует предмет - '.$item_need.'<br>';
			$res = $ar['res_dob'];
			if ($res!='')
			{
				$a = explode('|',$res);
				for ($i=0;$i<sizeof($a);$i++)
				{
					$b = explode('-',$a[$i]);
					list($resource) = mysql_fetch_array(myquery("SELECT name FROM craft_resource WHERE id=".$b[0].""));
					echo '<span style="color:lightyellow;">Ресурс '.$resource.' - '.$b[1].'</span><br>';
				}
			}
			echo '</td>
			<td><a href=admin.php?opt=main&option=craft&delete='.$ar['id'].'>Удалить здание</a></td>
			</tr>';
		}
		echo'</table>';
	}

	if(isset($edit))
	{
		if (!isset($save))
		{
			$qw=myquery("SELECT * FROM craft_build where id='$edit'");
			$ar=mysql_fetch_array($qw);
			if ($ar['item']>0)
			{
				$item_need = @mysql_result(myquery("SELECT name FROM game_items_factsheet WHERE id=".$ar['item'].""),0,0); 
			}
			else
			{
				$item_need = '';
			}
			echo'<form action="" method="post">
			<table>
			<tr><td>Название: </td><td><input type=text name=name value="'.$ar['name'].'" size=100></td></tr>
			<tr><td colspan=2><u><b>Требования для постройки:</b></u></td></tr>
			<tr><td>Цена постройки: </td><td><input type=text name=cost value="'.$ar['cost'].'" size=15> монет</td></tr>
			<tr><td>Время постройки: </td><td><input type=text name=create_time value="'.$ar['create_time'].'" size=12> секунд</td></tr>
			<tr><td>Требует уровень строителя: </td><td><input type=text name=lev_need value="'.$ar['lev_need'].'" size=2></td></tr>';
			$res = myquery("SELECT * FROM craft_resource");
			while ($r = mysql_fetch_array($res))
			{
				$val = '';
				$re = $ar['res_need'];
				if ($re!='')
				{
					$a = explode('|',$re);
					for ($i=0;$i<sizeof($a);$i++)
					{
						$b = explode('-',$a[$i]);
						list($resource) = mysql_fetch_array(myquery("SELECT id FROM craft_resource WHERE id=".$b[0].""));
						if ($resource==$r['id']) $val = $b[1];
					}
				}
				echo '<tr><td><span style="color:lightyellow;width:200px;">Требует ресурс '.$r['name'].' - </span></td><td><input size=6 type=text name=res_need'.$r['id'].' value='.$val.'></td></tr>';
			}
			echo '<tr><td colspan=2>&nbsp;</td></tr>
			<tr><td colspan=2><u><b>Добыча(производство):</b></u></td></tr>
			<tr><td>Количество рабочих мест: </td><td><input type=text name=col value="'.$ar['col'].'" size=15> игроков</td></tr>
			<tr><td>Время работы: </td><td><input type=text name=rab_time value="'.$ar['rab_time'].'" size=12> секунд</td></tr>
			<tr><td>Требует уровень игрока для работы: </td><td><input type=text name=clevel value="'.$ar['clevel'].'" size=2></td></tr>
			<tr><td>Требует расу игрока для работы: </td><td>';
			echo '<select name=race><option value=0></option>';
			$selrace = myquery("SELECT * FROM game_har WHERE disable=0");
			while ($race=mysql_fetch_array($selrace))
			{
				echo '<option value='.$race['id'].'';
				if ($race['id']==$ar['race']) echo ' selected';
				echo '>'.$race['name'].'</option>';
			}
			echo '</select></td></tr>
			<tr><td>Требует предмет в руках игрока для работы: </td><td><input type=text name=item value="'.$item_need.'" size=100></td></tr>';
			$res = myquery("SELECT * FROM craft_resource");
			while ($r = mysql_fetch_array($res))
			{
				$val = '';
				$re = $ar['res_dob'];
				if ($re!='')
				{
					$a = explode('|',$re);
					for ($i=0;$i<sizeof($a);$i++)
					{
						$b = explode('-',$a[$i]);
						list($resource) = mysql_fetch_array(myquery("SELECT id FROM craft_resource WHERE id=".$b[0].""));
						if ($resource==$r['id']) $val = $b[1];
					}
				}
				echo '<tr><td><span style="color:lightyellow;width:200px;">Производит ресурс '.$r['name'].' - </span></td><td><input size=6 type=text name=res_dob'.$r['id'].' value='.$val.'> за 1 единицу времени</td></tr>';
			}
			echo '<tr><td colspan=2>&nbsp;</td></tr>
			<tr><td colspan=2><u><b>Описание перед входом:</b></u></td></tr>
			<tr><td colspan=2><textarea cols=100 rows=10 name=opis>'.$ar['opis'].'</textarea></td></tr>
			<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2>&nbsp;</td></tr></table><input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Шахта изменена';
			$res_need='';
			$res = myquery("SELECT * FROM craft_resource");
			while ($r = mysql_fetch_array($res))
			{
				if ($_POST['res_need'.$r['id']]>0)
				{
					$res_need.=$r['id'].'-'.$_POST['res_need'.$r['id']].'|';
				}
			}
			if ($res_need!='') $res_need = substr($res_need,0,strlen($res_need)-1);
			$res_dob='';
			$res = myquery("SELECT * FROM craft_resource");
			while ($r = mysql_fetch_array($res))
			{
				if ($_POST['res_dob'.$r['id']]>0)
				{
					$res_dob.=$r['id'].'-'.$_POST['res_dob'.$r['id']].'|';
				}
			}
			if ($res_dob!='') $res_dob = substr($res_dob,0,strlen($res_dob)-1);
			$item_need = 0; 
			if ($_POST['item']!='')
			{
				$sel_item = myquery("SELECT id FROM game_items_factsheet WHERE name='".$_POST['item']."'");
				if ($sel_item!=false AND mysql_num_rows($sel_item)>0)
			{
					$item_need = mysql_result($sel_item,0,0);
            }
			}
			
			$up=myquery("update craft_build SET name='$name', col='$col', cost='$cost', lev_need='$lev_need', race='$race', clevel='$clevel', item='$item_need', create_time='$create_time', rab_time='$rab_time', opis='$opis', res_need='$res_need', res_dob='$res_dob' where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил шахту: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=craft">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post">
			<table>
			<tr><td>Название: </td><td><input type=text name=name value="" size=100></td></tr>
			<tr><td colspan=2><u><b>Требования для постройки:</b></u></td></tr>
			<tr><td>Цена постройки: </td><td><input type=text name=cost value="" size=15> монет</td></tr>
			<tr><td>Время постройки: </td><td><input type=text name=create_time value="" size=12> секунд</td></tr>
			<tr><td>Требует уровень строителя: </td><td><input type=text name=lev_need value="" size=2></td></tr>';
			$res = myquery("SELECT * FROM craft_resource");
			while ($r = mysql_fetch_array($res))
			{
				echo '<tr><td><span style="color:lightyellow;width:200px;">Требует ресурс '.$r['name'].' - </span></td><td><input size=6 type=text name=res_need'.$r['id'].'></td></tr>';
			}
			echo '<tr><td colspan=2>&nbsp;</td></tr>
			<tr><td colspan=2><u><b>Добыча(производство):</b></u></td></tr>
			<tr><td>Количество рабочих мест: </td><td><input type=text name=col value="" size=15> игроков</td></tr>
			<tr><td>Время работы: </td><td><input type=text name=rab_time value="" size=12> секунд</td></tr>
			<tr><td>Требует уровень игрока для работы: </td><td><input type=text name=clevel value="" size=2></td></tr>
			<tr><td>Требует расу игрока для работы: </td><td>';
			echo '<select name=race><option value=0></option>';
			$selrace = myquery("SELECT id,name FROM game_har WHERE disable=0");
			while ($race = mysql_fetch_array($selrace))
			{
				echo '<option value="'.$race['id'].'">'.$race['name'].'</option>';
			}
			echo '</select>';
			echo '</td></tr>
			<tr><td>Требует предмет в руках игрока для работы: </td><td><input type=text name=item value="" size=100></td></tr>';
			$res = myquery("SELECT * FROM craft_resource");
			while ($r = mysql_fetch_array($res))
			{
				echo '<tr><td><span style="color:lightyellow;width:200px;">Производит ресурс '.$r['name'].' - </span></td><td><input size=6 type=text name=res_dob'.$r['id'].'> за 1 единицу времени</td></tr>';
			}
			echo '<tr><td colspan=2>&nbsp;</td></tr>
			<tr><td colspan=2><u><b>Описание перед входом:</b></u></td></tr>
			<tr><td colspan=2><textarea cols=100 rows=10 name=opis></textarea></td></tr>
			<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2>&nbsp;</td></tr></table><input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'Шахта добавлена';
			$res_need='';
			$res = myquery("SELECT * FROM craft_resource");
			while ($r = mysql_fetch_array($res))
			{
				if ($_POST['res_need'.$r['id']]>0)
				{
					$res_need.=$r['id'].'-'.$_POST['res_need'.$r['id']].'|';
				}
			}
			if ($res_need!='') $res_need = substr($res_need,0,strlen($res_need)-1);
			$res_dob='';
			$res = myquery("SELECT * FROM craft_resource");
			while ($r = mysql_fetch_array($res))
			{
				if ($_POST['res_dob'.$r['id']]>0)
				{
					$res_dob.=$r['id'].'-'.$_POST['res_dob'.$r['id']].'|';
				}
			}
			if ($res_dob!='') $res_dob = substr($res_dob,0,strlen($res_dob)-1);
			
			if ($_POST['item']!='')
			{
				$item_need = @mysql_result(myquery("SELECT id FROM game_items_factsheet WHERE name='".$_POST['item']."'"),0,0); 
			}
			else
			{
				$item_need = 0;
			}
			$up=myquery("insert into craft_build (name,col,cost,lev_need,race,clevel,item,create_time,rab_time,opis,res_need,res_dob) values ('$name','$col','$cost','$lev_need','$race','$clevel','$item_need','$create_time','$rab_time','$opis','$res_need','$res_dob')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил шахту: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=craft">';
		}
	}

	if(isset($delete))
	{
		echo'Шахта удалена';
		$nazv = mysql_result(myquery("SELECT name FROM craft_build where id='$delete'"),0,0);
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
		$up=myquery("delete from craft_build where id='$delete'");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=craft">';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>