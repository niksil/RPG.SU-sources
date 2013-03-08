<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['ban'] >= 1)
{
	if (isset($user1))
	{               
		if (!isset($see111))
		{
			echo "<form name=frm method=post>";                 
			echo'Пункт: ';
			echo'<select name="zakon">';
			$result = myquery("SELECT * FROM game_zakon ORDER BY id");
			while($law=mysql_fetch_array($result))
			{
				if($law['time']<=120)
					$ob=$law['time']*30;
				else 
				//если $бан >= 2 часа {
			   //$обороты = 3600 + 3600 * (бан/60/24);              		
					$ob=3600 + 3600*ceil($law['time']/(60*24));
				echo '<option value='.$law['id'].'>№'.$law['id'].'. '.$law['name'].' ('.$law['time'].' минут = '.$ob.' оборотов ворота)</option>';
			}
			echo '</select>';
			echo '<br>Ты можешь указать произвольное время наказания (в оборотах ворота): <input name="naktime" size=15 type="text">';
			echo'<br><textarea name="za" cols="70" class="input" rows="8"></textarea><br><br>';                
			echo'<input name="submit" type="submit" value="Отправить">';
			echo'<input name="see111" type="hidden" value="">';
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		else
		{
			$sel = myquery("SELECT * FROM game_zakon WHERE id = '$zakon'");
			$zak = mysql_fetch_array($sel);
			$zakon_text = '№'.$zak['id'].'. '.$zak['name'].'';                                                    
			//$time=$zak['time']*30;
			if($zak['time']<=120)
					$time=$zak['time']*30;
				else               	
					$time=3600 + 3600*ceil($zak['time']/(60*24));
			$sel_nakaz = myquery("SELECT * FROM game_nakaz WHERE user_id='$user1' AND id_zakon='$zakon'");
			$time = $time * (mysql_num_rows($sel_nakaz)+1);                                                    
			if (isset($naktime) and $naktime>0)
			{
				$time = $naktime;
			}
			$sel = myquery("SELECT name FROM game_users WHERE user_id='$user1'");
			if (!mysql_num_rows($sel)) $sel = myquery("SELECT name FROM game_users_archive WHERE user_id='$user1'");
			list($pris_name)=mysql_fetch_array($sel);                             
			$prissel = myquery("SELECT * FROM game_prison WHERE user_id='$user1' ");
			if (!mysql_num_rows($prissel))
			{
				//создадим новую запись					 
				$exp=myquery("SELECT EXP FROM game_users WHERE user_id='$user1'");
				if (!mysql_num_rows($exp)) $exp = myquery("SELECT EXP FROM game_users_archive WHERE user_id='$user1'");
				list($exp_was)=mysql_fetch_array($exp);
				list($map_was)=mysql_fetch_array(myquery("SELECT map_name FROM game_users_map WHERE user_id='$user1'"));
				//засунем перса в таблицу
				$bams/* $8] */=myquery("INSERT INTO game_prison (user_id,exp_was,exp_need,last_active,adm,map_from) values ('$user1','$exp_was','$time',0,'".$char['name']."', '$map_was')");
				//сменим его координаты
				$idmap = 666;//mysql_result(myquery("SELECT id FROM game_maps WHERE name LIKE '%Каторга%'"),0,0);
				myquery("UPDATE game_users_map SET map_name=$idmap, map_xpos=1, map_ypos=1  WHERE user_id='$user1'");
			}
			else
			{
				//продлим существующую каторгу						 						
				$up = myquery("UPDATE game_prison SET exp_need=exp_need+'$time' WHERE user_id='$user1'");
			}                    
			$ban=myquery("insert into game_nakaz (user_id,nakaz,date_nak,date_zak,adm,text,id_zakon) values ('$user1','prison','".time()."','".$time."','".$user_id."','".$za."','".$zakon."')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
						VALUES (
						'".$char['name']."',
						'Отправил на каторгу игрока: <b>".$pris_name."</b>. <br>Причина: ".$za."<br>, время наказания: ".$time." оборотов Мифрильного Ворота.<br>По закону: ".$zakon_text." (пункт ".$zakon.")',
						'".time()."',
						'".$da['mday']."',
						'".$da['mon']."',
						'".$da['year']."')")
					 or die(mysql_error());
				 
			echo '<center>Сделано!</center>';
			echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
			echo '<meta http-equiv="refresh" content="2;url=admin.php?option=ban&opt=main">';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}        
	}
}

if (function_exists("save_debug")) save_debug(); 

?>