<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['unban'] >= 1)
{
	if (!isset($use))
	{
		$result = myquery("SELECT * FROM game_prison");    
		$number = mysql_num_rows($result);    
		echo '<center>Сейчас на каторге отбывают наказание <font color=#FF0000>'.$number.'</font><font color=#F0F0F0> преступников:</font>';
		echo '<table cellpadding="0" cellspacing="1" border="0" width="100%" align="center">
		<tr>
		<td valign="top">
		<tr bgcolor="#006699"><td><font size="1" face="Verdana" color="#000000">Ник</font></td><td>Причина</td><td>Кто засадил</td><td><font size="1" face="Verdana" color="#000000">Осталось</font></td><td><font size="1" face="Verdana" color="#000000">Действие</font></td>
		</tr>';
		while ($play = mysql_fetch_array($result))
		{    	
			$za = '';
			$playername = get_user('name',$play['user_id']); 
			$playerexp = get_user('EXP',$play['user_id']);                              
			$sel=myquery("select * from game_nakaz where user_id=".$play['user_id']." AND nakaz='prison'");
			if (mysql_num_rows($sel))
			{            
				while($nak=mysql_fetch_array($sel))
				{
				  $za= ''.$nak['text'].'';                
				}
			}        
			echo '<tr bgcolor="#333333"><td><font size="1" face="Verdana" color="#ffffff">' . $playername . '</font></td>'; 
			echo '<td>'.$za.'</td><td>'.$play['adm'].'</td>
			<td width="50"><font size="1" face="Verdana" color="fffffff">';  
			$ob=$play['exp_was']+$play['exp_need']-$playerexp;             
			echo $ob.' оборотов';        
			echo '</font></td><td><input type="button" value="Выпустить на волю" onClick="location.href=\'admin.php?opt=main&option=unprison&ob='.$ob.'&exp_was='.$play['exp_was'].'&use='.$play['user_id'].'\'"></td>
			</tr>';
		};
		echo '</table>';
	}
	else
	{
		$del=myquery("delete from game_prison where user_id='$use'");	
		$return_exp=myquery("UPDATE game_users SET EXP = '$exp_was' where user_id='$use'");
		$return_exp=myquery("UPDATE game_users_archive SET EXP = '$exp_was' where user_id='$use'");
		$sel = myquery("SELECT map_from FROM game_prison WHERE user_id='$use'");
		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			list($idmap)=mysql_fetch_array($sel);
		}
		else
		{
			$idmap = mysql_result(myquery("SELECT id FROM game_maps WHERE name LIKE '%Средиземье%'"),0,0);  
		}
		$go=myquery("UPDATE game_users_map SET map_name=$idmap, map_xpos=0, map_ypos=0 where user_id='$use'");
		
		$name = @mysql_result(@myquery("(SELECT name FROM game_users WHERE user_id='$use') UNION (SELECT name FROM game_users_archive WHERE user_id='$use')"),0,0);	
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Выпустил с каторги игрока <b>".$name."</b> за ".$ob." оборотов до конца наказания.',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		echo 'Преступник выпущен. <meta http-equiv="refresh" content="1;url=admin.php?option=unban&opt=main">';
	}
}

if (function_exists("save_debug")) save_debug(); 

?>