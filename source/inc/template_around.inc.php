 <?php

if (function_exists("start_debug")) start_debug(); 

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
	setLocation('index.php');
}
else
{
	// Помощь новичкам
	if ($char['clevel']<5)
	{
		include('template_intro.inc.php');
	}
	
	// Тут был чат на гексе
	$online_range = time()-300;
	/*
	$result_messages = myquery("SELECT * FROM game_chat WHERE map_name='" . $char['map_name'] . "' AND map_xpos='" . $char['map_xpos'] . "' AND map_ypos='" . $char['map_ypos'] . "' AND post_time>$online_range ORDER BY post_time LIMIT 60");
	if (mysql_num_rows($result_messages))
	{
		$i = 0;
		QuoteTable('open');
		while ($player_messages = mysql_fetch_array($result_messages))
		{
			switch (floor($i/4))
			{
				case 0:
					$font_color = 'eeeeee';
					break;
				case 1:
					$font_color = 'c6c6c6';
					break;
				case 2:
					$font_color = 'bbbbbb';
					break;
				case 3:
					$font_color = '969696';
					break;
				case 4:
					$font_color = '888888';
					break;
				case 5:
					$font_color = '666666';
					break;
				case 6:
					$font_color = '555555';
					break;
			}
			$i++;
			echo '<font size="1" color="#'.$font_color.'">['.date("H:i",$player_messages['post_time']).'] '.$player_messages['name'].'> ' . nl2br(stripslashes($player_messages['contents'])) . '</font><br>';
		}
		QuoteTable('close');
		echo '<br /><br />';
	}
	*/

	// Покажем других игроков на гексе
	echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT>
	<DIV id=hint style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id = ".$char['map_name'].""));

	$zelye_zorkosti = mysql_result(myquery("SELECT COUNT(*) FROM game_obelisk_users WHERE user_id=$user_id AND type=4 AND time_end>".time().""),0,0);

	$str_query = "SELECT 
	view_active_users.*,
	game_users_active.host,
	game_har.name AS race_name,
	game_users_func.func_id
	FROM game_users_map, view_active_users, game_users_active, game_har,game_users_func
	WHERE game_har.id=view_active_users.race AND game_users_active.user_id=view_active_users.user_id 
	AND game_users_map.user_id=view_active_users.user_id 
	AND view_active_users.user_id=game_users_func.user_id
	AND game_users_map.map_xpos='".$char['map_xpos']."' 
	AND game_users_map.map_ypos='".$char['map_ypos']."' 
	AND game_users_map.map_name='".$char['map_name']."'";
	if ($map['id']==map_coliseum)
	{
	}
	else
	{
		$str_query.="    AND (game_users_func.func_id='1' OR game_users_func.func_id='11' OR game_users_func.func_id='5')";
	}
	$str_query.=" AND view_active_users.user_id<>$user_id ";
	if ($zelye_zorkosti==0)
	{
		$str_query.=" AND view_active_users.user_id NOT IN (SELECT user_id FROM game_obelisk_users WHERE time_end>".time()." AND type=5) ";
	}
	$str_query.=" ORDER BY view_active_users.clan_id,view_active_users.name";
	
	$result = myquery($str_query);

	list($host1) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id='$user_id'"));

	if (mysql_num_rows($result) > 0)
	{        
		?>
		<SCRIPT language=JavaScript>var DHTML_texts = new Array();</SCRIPT>
		<SCRIPT language=JavaScript src="../js/popup.js"></SCRIPT>
		<DIV id = "DHTLMenu" onmouseover=OverDHTML(); style="Z-INDEX: 1; POSITION: absolute" onmouseout=OutDHTML();></DIV>
		<?php
		$i__0=0;
		$num=0;
		echo '<table cellpadding="3" cellspacing="3" border="0"><tr>';
		while ($player = mysql_fetch_array($result))
		{
			// Игрок в бою
			if($player['func_id']==1)
			{
				$result_combat = myquery("SELECT combat.combat_type AS combat_type,combat_users.combat_id
				FROM combat,combat_users 
				WHERE combat.combat_id=combat_users.combat_id AND combat_users.user_id=".$player['user_id']."");
				if(mysql_num_rows($result_combat)>0)
				{
					$player_combat = mysql_fetch_array($result_combat);
					$player['combat_type']=$player_combat['combat_type'];   
					$player['boy']=$player_combat['combat_id'];               
				}
				else
				{
					$player['combat_type']=NULL;   
					$player['boy']=0;                    
				}
			}
			else
			{
				$player['combat_type']=NULL;   
				$player['boy']=0;
				
			}
			$host2 = $player['host'];

			if ((
			$player['func_id']==1 OR 
			$player['func_id']==5 OR 
			$player['func_id']==11)OR($map['id']==map_coliseum)
			)
			{
				echo '
				<td>
				<table cellpadding="0" cellspacing="0" border="0"><tr>
				<td align=center valign=center>';
				?><a onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b><?php
				echo '<center><font color=#0000FF>'.$player['name'].' ['.$player['race_name'].' '.$player['clevel'].' уровня]</font>';
				?></b></font>','<?php
				echo '<font color=000000>';
				echo 'Жизнь: '.$player["HP"].'/'.$player["HP_MAX"].'<br>';
				echo 'Мана: '.$player["MP"].'/'.$player["MP_MAX"].'<br>';
				echo 'Энергия: '.$player["STM"].'/'.$player["STM_MAX"].'<br>';
				echo 'Сила: '.$player["STR"].'<br>';
				echo 'Ловкость: '.$player["PIE"].'<br>';
				echo 'Защита: '.$player["VIT"].'<br>';
				echo 'Выносливость: '.$player["DEX"].'<br>';
				echo 'Мудрость: '.$player["SPD"].'<br>';
				echo 'Интеллект: '.$player["NTL"].'';
				if ($player['combat_type']!=NULL)
				{
					if ($player['combat_type']==1) $nam=' Находится в обычном бою';
					if ($player['combat_type']==2) $nam=' Находится в дуэли';
					if ($player['combat_type']==3) $nam=' Находится в общем бою';
					if ($player['combat_type']==4) $nam=' Находится в многоклановом бою';
					if ($player['combat_type']==5) $nam=' Находится в бою все против всех';
					if ($player['combat_type']==6) $nam=' Находится в бою склонностей';
					if ($player['combat_type']==7) $nam=' Находится в бою рас';
					if ($player['combat_type']==8) $nam=' Находится в турнирной дуэли';
					if ($player['combat_type']==9) $nam=' Находится в турнирном групповом бою';
					if ($player['combat_type']==10) $nam=' Находится в бою с тенью';
					if ($player['combat_type']==11) $nam=' Находится в турнирном хаотичном бою';
					echo'<HR>'.$nam;
				}
				echo '</font>';
				?>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?php echo '<img src="http://'.IMG_DOMAIN.'/avatar/'.$player['avatar'].'" border="0" alt="'.$player['name'].'"></a>
				</td>
				<td valign=top>';
				if ($player['clan_id'] != 0)
				{
				   echo '<a href="http://'.DOMAIN.'/view/?clan='.$player['clan_id'].'" target="_blank""><img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif" alt="Информация о клане" title="Информация о клане" border=0></a>';
				}
				print_sklon($player);

				//проверим на доступность значки нападения на игрока и присоединения к бою

				//значок атаки
				$reas = check_attack($char,$player);
				if ($reas==1)
				{
					$popup_menu = 0;
					$str = '';
					if ($char['clan_id']!=0 AND $player['clan_id']!=0) //Для клановых игроков
					{
						$vse = $map['boy_type1']+$map['boy_type2']+$map['boy_type3']+$map['boy_type4']+$map['boy_type5']+$map['boy_type6']+$map['boy_type7'];
						if ($vse>1)
						{
							echo'
							<SCRIPT language=JavaScript>
							DHTML_texts['.$num.']=';
							if ($map['boy_type1']==1)
							{
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=1&id='.$player['user_id'].'\">Атаковать</A><BR>"';
								if ($map['boy_type2']==1 or $map['boy_type3']==1 or $map['boy_type4']==1 or $map['boy_type5']==1 or $map['boy_type6']==1 or $map['boy_type7']==1) echo '+';
							}
							if ($map['boy_type2']==1)
							{
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=2&id='.$player['user_id'].'\">Дуэль</A><BR>"';
								if ($map['boy_type3']==1 or $map['boy_type4']==1 or $map['boy_type5']==1 or $map['boy_type6']==1 or $map['boy_type7']==1) echo '+';
							}
							if ($map['boy_type3']==1)
							{
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=3&id='.$player['user_id'].'\">Общий&nbsp;бой</A><BR>"';
								if ($map['boy_type4']==1 or $map['boy_type5']==1 or $map['boy_type6']==1 or $map['boy_type7']==1) echo '+';
							}
							if ($map['boy_type4']==1)
							{
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=4&id='.$player['user_id'].'\">Клановый&nbsp;бой</A><BR>"';
								if ($map['boy_type5']==1 or $map['boy_type6']==1 or $map['boy_type7']==1) echo '+';
							}
							if ($map['boy_type5']==1)
							{
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=5&id='.$player['user_id'].'\">Все&nbsp;против&nbsp;всех</A><BR>"';
								if ($map['boy_type6']==1 or $map['boy_type7']==1) echo '+';
							}
							if ($map['boy_type6']==1)
							{
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=6&id='.$player['user_id'].'\">Бой&nbsp;склонностей</A><BR>"';
								if ($map['boy_type7']==1) echo '+';
							}
							if ($map['boy_type7']==1) echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=7&id='.$player['user_id'].'\">Бой&nbsp;рас</A><BR>";';
							echo'</SCRIPT>';
							$popup_menu = 1;
						}
						elseif ($vse==1)
						{
							if ($map['boy_type1']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=1&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
							if ($map['boy_type2']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=2&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
							if ($map['boy_type3']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=3&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
							if ($map['boy_type4']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=4&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
							if ($map['boy_type5']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=5&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
							if ($map['boy_type6']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=6&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
							if ($map['boy_type7']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=7&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
						}
					}
					else //Если есть бесклановый игрок
					{
						$vse = $map['boy_type3']+$map['boy_type5']+$map['boy_type6']+$map['boy_type7'];
						if ($vse>1)
						{
							$popup_menu = 1;
							echo'
							<SCRIPT language=JavaScript>
							DHTML_texts['.$num.']=';
							if ($map['boy_type3']==1)
							{
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=3&id='.$player['user_id'].'\">Общий&nbsp;бой</A><BR>"';
								if ($map['boy_type5']==1 or $map['boy_type6'] or $map['boy_type7']==1) echo '+';
							}
							if ($map['boy_type5']==1)
							{
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=5&id='.$player['user_id'].'\">Все&nbsp;против&nbsp;всех</A><BR>"';
								if ($map['boy_type6']==1 or $map['boy_type7']==1) echo '+';
							}
							if ($map['boy_type6']==1)
							{
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=6&id='.$player['user_id'].'\">Бой&nbsp;склонностей</A><BR>";';
								if ($map['boy_type7']==1) echo '+';
							}
							if ($map['boy_type7']==1) echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=attack&type=7&id='.$player['user_id'].'\">Бой&nbsp;рас</A><BR>";';
							echo'</SCRIPT>';
						}
						elseif ($vse==1)
						{
							if ($map['boy_type3']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=3&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
							if ($map['boy_type5']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=5&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
							if ($map['boy_type6']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=6&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
							if ($map['boy_type7']==1)
							{
								$str = '<a href="act.php?func=action&option=attack&type=7&id='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0></a>';
							}
						}
					}

					if ($popup_menu == 0)
					{
						echo '<br>'.$str; 
					}
					else
					{
						echo'
						<br>
						<a style="cursor:url(\'/images/nav/hand.cur\'), pointer;">
						<img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="Атаковать" title="Атаковать" border=0 onClick=PopUp('.$num.',event) onMouseOut=OutDHTML()></a>';
					}
				}
				else
				{
					echo'
					<br>
					<img src="http://'.IMG_DOMAIN.'/nav/action_notattack.gif" alt="'.$reas.'" title="'.$reas.'" border=0 onClick="alert(\''.$reas.'\')">';
				}
				
				//значок присоединения к бою
				if ($player['boy']!=0)
				{
					$join=0;
					$alt='';
					$av_svit=''; 
					$reas = check_join($char,$player,$join,$alt,$av_svit);
					if ($reas==1)
					{
						if ($join==1)
						{
							echo'<br>
							<a href="act.php?func=action&option=join&id='.$player['user_id'].'">
							<img src="http://'.IMG_DOMAIN.'/nav/at.gif" alt="'.$alt.'" title="'.$alt.'" border=0>
							</a>';
						}
						elseif ($join!=99)
						{
							echo'<br>
							<a href="act.php?func=action&option=join&id='.$player['user_id'].'">
							<img src="http://'.IMG_DOMAIN.'/nav/action_attack.gif" alt="'.$alt.'" title="'.$alt.'" border=0>
							</a>';
						}
						else
						{
							//echo 'join='.$join;
						}
						
						if ($map['dolina']!=1 AND $av_svit!='' AND $map['id']!=map_coliseum)
						{
							//Если есть свитки присоединения к бою - обработаем их
							$kol_svit = 0;
							echo'
							<SCRIPT language=JavaScript>
							DHTML_texts['.$num.']=';
							if (strpos($av_svit,',1,')!==false)
							{
								$kol_svit++;
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=join&svitok=1&id='.$player['user_id'].'\">Применить <br>малый свиток<br>присоединения к бою</A><hr>"';
							}
							if (strpos($av_svit,',2,')!==false)
							{
								if ($kol_svit==1)
								{
									echo '+';
								}
								echo '"&nbsp;<A class=DHTMLmnu href=\"act.php?func=action&option=join&svitok=2&id='.$player['user_id'].'\">Применить <br>средний свиток<br>присоединения к бою</A><hr>"';
							}
							//echo '""';						
							echo'</SCRIPT>'; 
							echo'
							<br>
							<a  style="cursor:url(\'/images/nav/hand.cur\'), pointer;">
							<img src="http://'.IMG_DOMAIN.'/nav/at.gif" alt="Исп.свиток" title="Исп.свиток" border=0 onClick=PopUp('.$num.',event) onMouseOut=OutDHTML()>
							</a>';
							//echo 'av_svit='.$av_svit;
						}						
					}
					elseif ($reas!='')
					{
						echo '<img src="http://'.IMG_DOMAIN.'/nav/at_no.gif" alt="'.$reas.'" title="'.$reas.'" border=0 onClick="alert(\''.$reas.'\')">';
					}
				}
				
				//вызов в игру Две Башни
				//еще проверим, не каторжник ли наш товарищ. Заключенным играть в карты запрещено! ;)
				$prison_check=mysql_num_rows(myquery("SELECT * FROM game_prison WHERE user_id='$user_id'"));
				if (($player['func_id']!=1 AND $player['func_id']!=4 AND $host1!=$host2 AND $prison_check==0)OR(DOMAIN==TEST_SERVER))
				{
					echo'
					<br>
					<a href="act.php?func=action&option=arcomage&id='.$player['user_id'].'">
					<img src="http://'.IMG_DOMAIN.'/medal/sbolvost.gif" width=20 height=20 alt="Вызвать на игру в Две Башни" title="Вызвать на игру в Две Башни" border=0></a>';
				}

				echo '
				</td></tr><tr>
				<td colspan=2 valign=top align=center>
				<font size="1">'.$player['name'].' ['.$player['clevel'].']</font><a href="?func=main&menu='.$player['user_id'].'"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" alt="Посмотреть информацию" title="Посмотреть информацию" border=0></a>';
				echo '</td>
				</tr>
				</table>
				</td>';
				$i__0++;
				if ($i__0==4)
				{
					echo '</tr><tr>';
					$i__0=0;
				}
				$num++;
			}
		}
		while ($i__0<4)
		{
			echo '<td>&nbsp;</td>';
			$i__0++;
		} 
		echo '</tr></table>';
	}

	// Покажем ботов
	$result = myquery("SELECT game_npc.id FROM game_npc,game_npc_template WHERE game_npc.map_name ='".$char['map_name']."' AND game_npc.xpos ='".$char['map_xpos']."' AND game_npc.ypos ='".$char['map_ypos']."' AND (game_npc.time_kill+game_npc_template.respawn)<unix_timestamp() AND game_npc.npc_id=game_npc_template.npc_id ORDER BY game_npc_template.npc_level ASC");
	if (mysql_num_rows($result) > 0)
	{
		echo '<table cellpadding="2" cellspacing="0" border="0"><tr>';
		while ($npc = mysql_fetch_array($result))
		{
			$NPC_object = new Npc($npc['id']);
			$NPC_object->show_around();
		}
		echo '</tr></table>';
	}
}

if (function_exists("save_debug")) save_debug(); 

?>
