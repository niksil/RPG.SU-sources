<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['mag'] >= '1')
{
        if (isset($del))
        {
			echo'Удалено';
			$name = mysql_result(myquery("SELECT name FROM game_mag WHERE id='$del'"),0,0);
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Удалил из списка магов городов: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$del=myquery("delete from game_mag where id='$del'");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=mag">';
        }

        if (isset($edit))
        {
            if (!isset($see))
            {
                echo'<center><form action="" method="post">
                <font size="1" face="Verdana" color="#ffffff">';

                echo'<table width=70% border=0 align=left>';

                $kto=myquery("select * from game_mag where id=$edit");
                $mag=mysql_fetch_array($kto);

                echo'<tr><td align=right>Имя</td><td>'.$mag['name'].'</td></tr>';

                echo'<tr><td align=right>Маг в городе: </td><td><select name="gorod"><option value="0"';
                if ($mag['town']==0) echo ' selected';
                echo '>Общий чат игры</option>';
                $result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY rustown");
                while($t=mysql_fetch_array($result))
                {
                        echo '<option value="'.$t['town'].'"';
                        if ($mag['town']==$t['town']) echo 'selected';
                        echo '>'.$t['rustown'].'</option>';
                }
                echo '</select></td></tr>';

                $status2 = $mag['status'];

                echo'<tr><td align=right>Печать молчания</td><td><input name="mol" type="checkbox" value="1"'; if($mag['mol']==1) echo'checked'; echo'></td></tr>';
                echo'<tr><td align=right>Печать изгнания</td><td><input name="izgn" type="checkbox" value="1"'; if($mag['izgn']==1) echo'checked'; echo'></td></tr>';
                echo'<tr><td align=right>Печать обновления</td><td><input name="obn" type="checkbox" value="1"'; if($mag['obn']==1) echo'checked'; echo'></td></tr>';
                echo'<tr><td align=right>Печать слепоты</td><td><input name="slep" type="checkbox" value="1"'; if($mag['slep']==1) echo'checked'; echo'></td></tr>';
                echo'<tr><td align=right>Печать проклятия</td><td><input name="prok" type="checkbox" value="1"'; if($mag['prok']==1) echo'checked'; echo'></td></tr>';
                echo'<tr><td align=right>Печать телепорта</td><td><input name="teleport" type="checkbox" value="1"'; if($mag['teleport']==1) echo'checked'; echo'></td></tr>';
                echo'<tr><td align=right>Печать лабиринта</td><td><input name="lab" type="checkbox" value="1"'; if($mag['lab']==1) echo'checked'; echo'></td></tr>';

                echo '<tr><td align=right>Статус мага</td><td><input name="status" value="'.$status2.'" type="text" size="60"></td></tr>';


                echo'<tr><td align=right><input name="submit" type="submit" value="Сохранить"><input name="see" type="hidden" value=""></td></tr>';
                echo'</table></form>';
                }
                else
                {
					if(!isset($mol)) $mol='0';
					if(!isset($izgn)) $izgn='0';
					if(!isset($obn)) $obn='0';
					if(!isset($slep)) $slep='0';
					if(!isset($prok)) $prok='0';
					if(!isset($teleport)) $teleport='0';
					if(!isset($lab)) $lab='0';

					$up=myquery("update game_mag set
					mol='$mol',
					izgn='$izgn',
					obn='$obn',
					slep='$slep',
					prok='$prok',
					teleport='$teleport',
					lab='$lab',
					status='$status',
					town='$gorod'
					where id='$edit'");
					echo '<br>Готово';
					$name = mysql_result(myquery("SELECT name FROM game_mag WHERE id=$edit"),0,0);
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					 VALUES (
					 '".$char['name']."',
					 'Изменил мага: <b>".$name."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=mag">';
                }
        }

        if(!isset($edit) and !isset($new) and !isset($del))
        {
            $ktoo=myquery("select * from game_mag ORDER BY name ASC");
            echo '<center><table>';
            $i=0;
            while($stat=mysql_fetch_array($ktoo))
            {
                $i++;
                if ($stat['town']==0)
                {
                    $rustown = 'Общий чат игры';
                }
                else
                {
                    $sel = myquery("SELECT rustown FROM game_gorod WHERE town='".$stat['town']."' LIMIT 1");
                    list($rustown)=mysql_fetch_array($sel);
                }
                if ($i==1) echo '<tr bgcolor=#000040>';
                else {$i=0;echo '<tr bgcolor=#000024>';};
                echo '<td><b>'.$stat['name'].'</b></td><td>'.$rustown.'</td><td><a href=?opt=main&option=mag&edit='.$stat['id'].'>Редактировать</a></td><td><a href=?opt=main&option=mag&del='.$stat['id'].'>Удалить</a></td></tr>';
            }
            echo'</table>';
		    echo'<a href="?opt=main&option=mag&new">Добавить</a>';
        }

        if(isset($new))
        {
            if (!isset($see))
            {
                echo'<div id="content" onclick="hideSuggestions();"><center><form action="" method="post">
                <font size="1" face="Verdana" color="#ffffff">';

                echo'<table border=0 align=left>';

                echo'<tr><td align=right>Имя</td><td><input name="name_v" value="" type="text" size="30" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr>';

                echo'<tr><td align=right>Маг в городе: </td><td><select name="gorod"><option value="0">Общий чат игры</option>';
                $result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY rustown");
                while($t=mysql_fetch_array($result))
                {
                        echo '<option value="'.$t['town'].'"';
                        echo '>'.$t['rustown'].'</option>';
                }
                echo '</select></td></tr>';

                echo'<tr><td align=right>Печать молчания</td><td><input name="mol" type="checkbox" value="1"></td></tr>';
                echo'<tr><td align=right>Печать изгнания</td><td><input name="izgn" type="checkbox" value="1"></td></tr>';
                echo'<tr><td align=right>Печать обновления</td><td><input name="obn" type="checkbox" value="1"></td></tr>';
                echo'<tr><td align=right>Печать слепоты</td><td><input name="slep" type="checkbox" value="1"></td></tr>';
                echo'<tr><td align=right>Печать проклятия</td><td><input name="prok" type="checkbox" value="1"></td></tr>';
                echo'<tr><td align=right>Печать телепорта</td><td><input name="teleport" type="checkbox" value="1"></td></tr>';
                echo'<tr><td align=right>Печать лабиринта</td><td><input name="lab" type="checkbox" value="1"></td></tr>';

                echo '<tr><td align=right>Статус мага</td><td><input name="status" type="text" size="60"></td></tr>';


                echo'<tr><td align=right><input name="submit" type="submit" value="Сохранить"><input name="see" type="hidden" value=""></td></tr>';
                echo'</table></form></div><script>init();</script>';
                }
                else
                {
					$sel = myquery("SELECT * FROM game_users WHERE name='$name_v'");
					if (!mysql_num_rows($sel)) $sel = myquery("SELECT * FROM game_users_archive WHERE name='$name_v'");
					if (mysql_num_rows($sel))
					{
							if(!isset($mol)) $mol='0';
							if(!isset($izgn)) $izgn='0';
							if(!isset($obn)) $obn='0';
							if(!isset($slep)) $slep='0';
							if(!isset($prok)) $prok='0';
							if(!isset($teleport)) $teleport='0';
							if(!isset($lab)) $lab='0';

							$up=myquery("insert into game_mag set
							name='$name_v',
							mol='$mol',
							izgn='$izgn',
							obn='$obn',
							slep='$slep',
							prok='$prok',
							teleport='$teleport',
							lab='$lab',
							status='$status',
							town='$gorod'");

							$da = getdate();
							$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
							 VALUES (
							 '".$char['name']."',
							 'Добавил нового мага: <b>".$name_v."</b>',
							 '".time()."',
							 '".$da['mday']."',
							 '".$da['mon']."',
							 '".$da['year']."')")
								 or die(mysql_error());
							
							echo '<br>Готово';
							echo '<meta http-equiv="refresh" content="1;url=admin.php?option=mag&opt=main">';
					 }
					else
					{
							echo '<br>Игрок не найден';
							echo '<meta http-equiv="refresh" content="1;url=admin.php?option=mag&opt=main">';
					 }
                }
        }
		echo'<center><a href="?opt=main&option=mag">Главная</a>';
}

if (function_exists("save_debug")) save_debug(); 

?>