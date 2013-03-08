<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['gorod'] >= 1)
{
    if(!isset($edit) and !isset($new) and !isset($delete))
    {
        echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
        echo "<tr bgcolor=#333333><td colspan=6 align=center><a href=admin.php?opt=main&option=port&new>Добавить новый рейс</a></td></tr>";
        echo "<tr bgcolor=#333333><td>Название корабля</td><td>Порт откуда</td><td>Порт куда</td><td>Цена</td><td>Описание</td><td></td></tr>";
        $qw=myquery("SELECT * FROM game_port order BY time ASC");
        while($ar=mysql_fetch_array($qw))
        {
            echo'<tr>
            <td><a href=admin.php?opt=main&option=port&edit='.$ar['id'].'>'.$ar['nazv'].'</a></td>';
            $otkuda='<font color=#FFFF80>'.@mysql_result(@myquery("SELECT rustown FROM game_gorod WHERE town='".$ar['town_from']."'"),0,0);
            $map = @mysql_fetch_array(@myquery("SELECT * FROM game_map WHERE town='".$ar['town_from']."' and to_map_name=0"));
			$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"),0,0);
            $otkuda.='</font> ('.$map_name.' '.$map['xpos'].','.$map['ypos'].')';
            echo'<td>'.$otkuda.' в <font color=#FF8080>'.$ar['time'].'</font></td>';
            $kuda='<font color=#FFFF80>'.@mysql_result(@myquery("SELECT rustown FROM game_gorod WHERE town='".$ar['town_kuda']."'"),0,0);
            $map = @mysql_fetch_array(@myquery("SELECT * FROM game_map WHERE town='".$ar['town_kuda']."' and to_map_name=0"));
            $map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"),0,0);
            $kuda.='</font> ('.$map_name.' '.$map['xpos'].','.$map['ypos'].')';
            echo'<td>'.$kuda.' в <font color=#FF8080>'.$ar['dlit'].'</font></td>';
            echo'<td>'.$ar['cena'].'</td>';
            echo'<td>'.$ar['opis'].'</td>';
            echo'<td><a href=admin.php?opt=main&option=port&delete='.$ar['id'].'>Удалить рейс</a></td>
            </tr>';
        }
        echo'</table>';
    }

    if(isset($edit))
    {
        if (!isset($save))
        {
            $qw=myquery("SELECT * FROM game_port where id='$edit'");
            $ar=mysql_fetch_array($qw);
            echo'<form action="" method="post">
            <table>
            <tr><td>Название корабля: </td><td><input type=text name=nazv value="'.$ar['nazv'].'" size=100></td></tr>
            <tr><td>Цена рейса: </td><td><input type=text name=cena value="'.$ar['cena'].'" size=8> монет</td></tr>';
            echo'<tr><td>Порт откуда: </td><td><select name="town_from">';
            $result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY BINARY rustown");
            while($t=mysql_fetch_array($result))
            {
				echo '<option value="'.$t['town'].'"';
				if ($ar['town_from']==$t['town']) echo ' selected';
				echo'>'.$t['rustown'].'</option>';
            }
            echo '</select></td></tr>
			<tr><td></td><td>Время отправления рейса: <input type=text name=time value="'.$ar['time'].'" size=5 maxsize=5> (только в формате ЧЧ:ММ)</td></tr>';
            echo'<tr><td>Порт куда: </td><td><select name="town_kuda">';
            $result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY BINARY rustown");
            while($t=mysql_fetch_array($result))
            {
				echo '<option value="'.$t['town'].'"';
				if ($ar['town_kuda']==$t['town']) echo ' selected';
				echo'>'.$t['rustown'].'</option>';
            }
            echo '</select></td></tr>
			<tr><td></td><td>Время прибытия рейса: <input type=text name=dlit value="'.$ar['dlit'].'" size=5 maxsize=5> (только в формате ЧЧ:ММ)</td></tr>
            <tr><td>Описание корабля: </td><td><textarea name=opis cols=70 class=input rows=10>'.$ar['opis'].'</textarea></td></tr>
            <tr><td colspan=2><input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value=""></td></tr></table>';
        }
        else
        {
            echo'Рейс изменен';
            $up=myquery("update game_port set nazv='".htmlspecialchars($nazv)."',cena='$cena',opis='".htmlspecialchars($opis)."',town_from='$town_from',time='$time',dlit='$dlit',town_kuda='$town_kuda' where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил морской рейс: <b>".$nazv."</b> в time=".$time."',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=port">';
        }
    }


    if(isset($new))
    {
        if (!isset($save))
        {
            echo'<form action="" method="post">
            <table>
            <tr><td>Название корабля: </td><td><input type=text name=nazv size=100></td></tr>
            <tr><td>Цена рейса: </td><td><input type=text name=cena size=8> монет</td></tr>';
            echo'<tr><td>Порт откуда: </td><td><select name="town_from">';
            $result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY BINARY rustown");
            while($t=mysql_fetch_array($result))
            {
            echo '<option value="'.$t['town'].'"';
            echo'>'.$t['rustown'].'</option>';
            }
            echo '</select></td></tr>
			<tr><td></td><td>Время отправления рейса: <input type=text name=time size=5 maxsize=5> (только в формате ЧЧ:ММ)</td></tr>';
            echo'<tr><td>Порт куда: </td><td><select name="town_kuda">';
            $result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY BINARY rustown");
            while($t=mysql_fetch_array($result))
            {
				echo '<option value="'.$t['town'].'"';
				echo'>'.$t['rustown'].'</option>';
            }
            echo '</select></td></tr>
			<tr><td></td><td>Время прибытия рейса: <input type=text name=dlit size=5 maxsize=5> (только в формате ЧЧ:ММ)</td></tr>
            <tr><td>Описание корабля: </td><td><textarea name=opis cols=70 class=input rows=10></textarea></td></tr>
            <tr><td colspan=2><input name="save" type="submit" value="Добавить"><input name="save" type="hidden" value=""></td></tr></table>';
        }
        else
        {
            echo'Рейс добавлен';
            $up=myquery("insert into game_port set nazv='".htmlspecialchars($nazv)."',cena='$cena',opis='".htmlspecialchars($opis)."',town_from='$town_from',time='$time',dlit='$dlit',town_kuda='$town_kuda'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил морской рейс: <b>".$nazv."</b> в time=".$time."',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=port">';
        }
    }

    if(isset($delete))
    {
        echo'Рейс удален';
		$reis = mysql_fetch_array(myquery("SELECT * FROM game_port WHERE id='$delete'"));
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил морской рейс: <b>".$reis['nazv']."</b> в time=".$reis['time']."',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
        $up=myquery("delete from game_port where id='$delete'");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=port">';
    }

}

if (function_exists("save_debug")) save_debug(); 

?>