<?

if (function_exists("start_debug")) start_debug(); 


//$price=0; $price_har=0;
if ($town!=0)
{
	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено пользоваться алтарем!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	$img='http://'.IMG_DOMAIN.'/race_table/human/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';

	//list($count_reload,$count_reload_har) = mysql_fetch_array(myquery("SELECT count_reload,count_reload_har FROM game_users_data WHERE user_id=$user_id"));
    
	//Установим цены на услуги Алтаря
	$price=20*$char['clevel'];
	$price_har=200*$char['clevel'];
	$count_reload =  0;
    $count_reload_har = 0;
	$cost = $price*($count_reload+1);
	$cost_har = $price_har*($count_reload_har+1);
	$cost_prof=50*$char['clevel'];
    /*
	$da = getdate();
	if ($da['mon']==7 AND $da['mday']==15)
	{
		$cost = 0;
	}
	if ($da['mon']==1 AND $da['mday']>=1 AND $da['mday']<=7)
	{
		$cost = 0;
	}
	if ($da['mon']==12 AND $da['mday']==31)
	{
		$cost = 0;
	}
	*/
	
	//Скидывание специализаций
	if(isset($do) AND $do=='nav_down' AND isset($p))
	{
		echo '<center><br/>';
		QuoteTable('open');
		if (isset($agree))
		{
			if ($char['GP'] >= $cost)
			{
				if ($char[$p]>0)
				{
					myquery("UPDATE game_users SET GP=GP-$cost,CW=CW-'".($cost*money_weight)."',".$p."=".$p."-1,exam=exam+1 WHERE user_id=$user_id");
					setGP($user_id,-$cost,52);
					myquery("UPDATE game_users_data SET count_reload=count_reload+1 WHERE user_id=$user_id");
					if ($p=='MS_VSADNIK' AND $char['vsadnik']>0)
					{
						$sel=myquery("select * from game_vsadnik where id='".$char['vsadnik']."'");
						$row=mysql_fetch_array($sel);
						if ($row['vsad']>($char['MS_VSADNIK']-1))
						{
							$g = $row['cena']/2;
							$c = $row['ves'];
							$up=myquery("UPDATE game_users SET dvij=1,vsadnik=0,GP=GP+$g, CC=CC-$c,CW=CW+'".($g*money_weight)."' WHERE user_id='$user_id' LIMIT 1");
							myquery("DELETE FROM game_users_horses WHERE used=1 AND user_id=$user_id");
							setGP($user_id,$g,53);
						}
					} 
					if (substr($p,0,5)=='skill')
					{
						switch ($p)
						{
							case 'skill_war':
								list($name_nav) = mysql_fetch_array(myquery("SELECT name FROM game_spets WHERE war=".$char[$p].""));
								if ($name_nav!='') myquery("DELETE FROM game_spets_item WHERE user_id=$user_id AND name='".$name_nav."'");    
							break;
							case 'skill_music':
								list($name_nav) = mysql_fetch_array(myquery("SELECT name FROM game_spets WHERE music=".$char[$p].""));
								if ($name_nav!='') myquery("DELETE FROM game_spets_item WHERE user_id=$user_id AND name='".$name_nav."'");    
							break;
							case 'skill_cook':
								list($name_nav) = mysql_fetch_array(myquery("SELECT name FROM game_spets WHERE cook=".$char[$p].""));
								if ($name_nav!='') myquery("DELETE FROM game_spets_item WHERE user_id=$user_id AND name='".$name_nav."'");    
							break;
							case 'skill_art':
								list($name_nav) = mysql_fetch_array(myquery("SELECT name FROM game_spets WHERE art=".$char[$p].""));
								if ($name_nav!='') myquery("DELETE FROM game_spets_item WHERE user_id=$user_id AND name='".$name_nav."'");    
							break;
							case 'skill_explor':
								list($name_nav) = mysql_fetch_array(myquery("SELECT name FROM game_spets WHERE explor=".$char[$p].""));
								if ($name_nav!='') myquery("DELETE FROM game_spets_item WHERE user_id=$user_id AND name='".$name_nav."'");    
							break;
							case 'skill_craft':
								list($name_nav) = mysql_fetch_array(myquery("SELECT name FROM game_spets WHERE craft=".$char[$p].""));
								if ($name_nav!='') myquery("DELETE FROM game_spets_item WHERE user_id=$user_id AND name='".$name_nav."'");    
							break;
							case 'skill_card':
								list($name_nav) = mysql_fetch_array(myquery("SELECT name FROM game_spets WHERE card=".$char[$p].""));
								if ($name_nav!='') myquery("DELETE FROM game_spets_item WHERE user_id=$user_id AND name='".$name_nav."'");    
							break;
							case 'skill_pet':
								list($name_nav) = mysql_fetch_array(myquery("SELECT name FROM game_spets WHERE pet=".$char[$p].""));
								if ($name_nav!='') myquery("DELETE FROM game_spets_item WHERE user_id=$user_id AND name='".$name_nav."'");    
							break;
							case 'skill_uknow':
								list($name_nav) = mysql_fetch_array(myquery("SELECT name FROM game_spets WHERE unknow=".$char[$p].""));
								if ($name_nav!='') myquery("DELETE FROM game_spets_item WHERE user_id=$user_id AND name='".$name_nav."'");    
							break;
						}
					}
					
					echo'<br/><center><font face=verdana color=white size=2><b>Твои навыки и специализации обновлены</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
				}
				else
				{
					echo'<br/><center><font face=verdana color=ff0000 size=2>У тебя нет такого навыка</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
				}
			}
			else
			{
				echo'<br/><center><font face=verdana color=ff0000 size=2>У тебя не хватает денег!</font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
			}
		}
		else
		{
			echo '<center><b>Ты действительно хочешь скинуть навык? </b><br />
				  <br /><input type="button" onClick="location.href=\'town.php?option='.$option.'&do=nav_down&p='.$_GET['p'].'&agree\'" value="Да, я хочу скинуть навык">
				  <br /><br />
				  <input type="button" onClick="location.href=\'town.php?option='.$option.'\'" value="Нет, я не хочу скидывать навык"><br /></center>';
		}	  
		QuoteTable('close');
		echo '<br /></center>';	
	}
	
	//Скидывание характеристик
	elseif(isset($do) AND $do=='har_down' AND isset($p))
	{
		echo '<center><br/>';
		QuoteTable('open');
		if (isset($agree))
		{
			if ($char['GP'] >= $cost_har)
			{
				$har_race = mysql_fetch_array(myquery("SELECT * FROM game_har WHERE id=".$char['race'].""));
				$count_itemhar = mysql_fetch_array(myquery("SELECT SUM(g.dstr) STR,SUM(g.dpie) PIE,SUM(g.ddex) DEX,SUM(g.dvit) VIT,SUM(g.dntl) NTL,SUM(g.dspd) SPD FROM game_items i,game_items_factsheet g WHERE i.item_id=g.id AND i.used>0 AND g.type NOT IN (12,13,19,20,21) AND i.priznak=0 AND i.user_id=$user_id"));
				if (($char[$p]-$count_itemhar[$p])>$har_race[strtolower($p)])
				{
					$query_string = "UPDATE game_users SET GP=GP-$cost_har,CW=CW-'".($cost_har*money_weight)."',".$p."=".$p."-1,".$p."_MAX=".$p."_MAX-1,bound=bound+1";
					setGP($user_id,-$cost_har,52);
					myquery("UPDATE game_users_data SET count_reload_har=count_reload_har+1 WHERE user_id=$user_id");
					echo'<br><center><font face=verdana color=white size=2><b>Твои характеристики обновлены</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
					if ($p=='NTL') { $query_string.=",MP_MAX=MP_MAX-10,MP=MP-10";}
					if ($p=='PIE') { $query_string.=",STM_MAX=STM_MAX-10,STM=STM-10";}
					if ($p=='DEX') { $query_string.=",HP_MAX=HP_MAX-10,HP_MAXX=HP_MAXX-10,HP=HP-10,CC=CC-2";}
					$query_string.=" WHERE user_id=$user_id";
					myquery($query_string);
					$char = mysql_fetch_array(myquery("SELECT * FROM game_users WHERE user_id=$user_id"));
					//проверим предметы
					$sel_item = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used>0");
					while (list($item_id) = mysql_fetch_array($sel_item))
					{
						$Item = new Item($item_id);
						if ($Item->check_up()!=1)
						{
							$Item->down();
							echo 'С тебя снят предмет: <b>'.$Item->getFact('name').'</b>';
						}
					}
				}
				else
				{
					echo'<br/><center><font face=verdana color=ff0000 size=2>У тебя нет такой характеристики</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
				}
			}
			else
			{
				echo'<br/><center><font face=verdana color=ff0000 size=2>У тебя не хватает денег!</font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
			}
		}
		else
		{
			echo '<center><b>Ты действительно хочешь скинуть характеристку? </b><br />
				  <br /><input type="button" onClick="location.href=\'town.php?option='.$option.'&do=har_down&p='.$_GET['p'].'&agree\'" value="Да, я хочу скинуть характеристику">
				  <br /><br />
				  <input type="button" onClick="location.href=\'town.php?option='.$option.'\'" value="Нет, я не хочу скидывать характеристику"><br /></center>';
		}	  
		QuoteTable('close');
		echo '<br /></center>';			
	}
	
	//Скидывание профессии
	elseif(isset($do) AND $do=='prof_down' AND isset($_GET['prof'])) 
	{
		echo '<center><br/>';
		QuoteTable('open');
		if (isset($agree))
		{
			if ($char['GP'] >= $cost_prof)
			{
				$pr=$_GET['prof'];
				$test=myquery("Select craft_index from game_users_crafts where user_id=$user_id and profile=1 and craft_index=$pr");
				if (mysql_num_rows($test)>0)
				{
					//myquery("UPDATE game_users_crafts SET profile=0 Where user_id=$user_id and craft_index=$pr");
					myquery("DELETE FROM game_users_crafts Where user_id=$user_id and craft_index=$pr");
					myquery("UPDATE game_users SET GP=GP-$cost_prof,CW=CW-'".($cost_prof*money_weight)."' Where user_id=$user_id");
					setGP($user_id,-$cost_prof,52);
					echo'<br><center><font face=verdana color=white size=2><b>Профессия забыта</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
				}
				else
				{
					echo'<br/><center><font face=verdana color=ff0000 size=2>У тебя нет такой профессии</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br/>';
				}
			}
			else
			{
				echo'<br/><center><font face=verdana color=ff0000 size=2>У тебя не хватает денег!</font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br/>';
			}

		}
		else
		{
			echo '<center><b>Ты действительно хочешь забыть профессию? <br/></b>
			      Учти, все твои достижения будут безвозвратно потеряны!<br />
				  <br /><input type="button" onClick="location.href=\'town.php?option='.$option.'&do=prof_down&prof='.$prof.'&agree\'" value="Да, я хочу забыть профессию">
				  <br /><br />
				  <input type="button" onClick="location.href=\'town.php?option='.$option.'\'" value="Нет, я не хочу забывать профессию"><br /></center>';
		}	  
		QuoteTable('close');
		echo '<br /></center>';			
	}
	
	//Главное меню
	else
	{
		if ($char['clevel'] >= 5)
		{
			echo'<center><font face=verdana color=ff0000 size=2><b>Алтарь великой силы</b></font></center><br>';
			echo'<center><font face=verdana color=white size=2><b>На нашем Алтаре ты можешь за определенную плату избавиться от ненужных тебе навыков, характеристик и профессий. 
			     <br><br></font><font size=2 color="lightblue">Твоя стоимость уменьшения одного навыка: '.$cost.' монет!
				 <br><br>Твоя стоимость уменьшения одной характеристики: '.$cost_har.' монет!
				 <br><br>Твоя стоимость скидывания одной профессии: '.$cost_prof.' монет!<br><br></font><br><br>';
			echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0"><tr valign="top"><td>';
			
			OpenTable('title');
			echo '<table border="0" cellspacing="0" cellpadding="5" width="100%">';
			$har_race = mysql_fetch_array(myquery("SELECT * FROM game_har WHERE id=".$char['race'].""));
            $count_itemhar = mysql_fetch_array(myquery("SELECT SUM(g.dstr) dstr,SUM(g.dpie) dpie,SUM(g.ddex) ddex,SUM(g.dvit) dvit,SUM(g.dntl) dntl,SUM(g.dspd) dspd FROM game_items i,game_items_factsheet g WHERE i.item_id=g.id AND i.used>0 AND g.type NOT IN (12,13,19,20,21) AND i.priznak=0 AND i.user_id=$user_id"));
			if (($char['STR']-$count_itemhar['dstr'])>$har_race['str']) 
			{
				echo '<tr><td height="20" valign="middle">Сила: </td><td align="right">'.$char['STR'].' <a href=town.php?option='.$option.'&do=har_down&p=STR><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if (($char['SPD']-$count_itemhar['dspd'])>$har_race['spd']) 
			{
				echo '<tr><td height="20" valign="middle">Мудрость: </td><td align="right">'.$char['SPD'].' <a href=town.php?option='.$option.'&do=har_down&p=SPD><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if (($char['NTL']-$count_itemhar['dntl'])>$har_race['ntl']) 
			{
				echo '<tr><td height="20" valign="middle">Интеллект: </td><td align="right">'.$char['NTL'].' <a href=town.php?option='.$option.'&do=har_down&p=NTL><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if (($char['PIE']-$count_itemhar['dpie'])>$har_race['pie']) 
			{
				echo '<tr><td height="20" valign="middle">Ловкость: </td><td align="right">'.$char['PIE'].' <a href=town.php?option='.$option.'&do=har_down&p=PIE><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if (($char['VIT']-$count_itemhar['dvit'])>$har_race['vit']) 
			{
				echo '<tr><td height="20" valign="middle">Защита: </td><td align="right">'.$char['VIT'].' <a href=town.php?option='.$option.'&do=har_down&p=VIT><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if (($char['DEX']-$count_itemhar['ddex'])>$har_race['dex']) 
			{
				echo '<tr><td height="20" valign="middle">Выносливость: </td><td align="right">'.$char['DEX'].' <a href=town.php?option='.$option.'&do=har_down&p=DEX><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			echo '</table>';
			
			OpenTable('close');
			echo '</td><td>';
			OpenTable('title');
			echo '<table border="0" cellspacing="0" cellpadding="5" width="100%">';
			if ($char['MS_ART']>0) 
			{
				echo '<tr><td height="20" valign="middle">Эксперт артефактов: </td><td align="right">'.$char['MS_ART'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_ART><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if ($char['MS_KULAK']>0) 
			{
				echo '<tr><td height="20" valign="middle">Мастер кулачного боя: </td><td align="right">'.$char['MS_KULAK'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_KULAK><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if ($char['MS_LUK']>0) 
			{
				echo '<tr><td height="20" valign="middle">Мастер стрелкового оружия: </td><td align="right">'.$char['MS_LUK'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_LUK><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if ($char['MS_THROW']>0) 
			{
				echo '<tr><td height="20" valign="middle">Мастер метательного оружия: </td><td align="right">'.$char['MS_THROW'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_THROW><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if ($char['MS_SWORD']>0) 
			{
				echo '<tr><td height="20" valign="middle">Мастер рубящего оружия: </td><td align="right">'.$char['MS_SWORD'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_SWORD><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if ($char['MS_AXE']>0) 
			{
				echo '<tr><td height="20" valign="middle">Мастер дробящего оружия: </td><td align="right">'.$char['MS_AXE'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_AXE><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if ($char['MS_SPEAR']>0) 
			{
				echo '<tr><td height="20" valign="middle">Мастер колющего оружия: </td><td align="right">'.$char['MS_SPEAR'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_SPEAR><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			if ($char['MS_WEAPON']>0) 
			{
				echo '<tr><td height="20" valign="middle">Эксперт воинских умений: </td><td align="right">'.$char['MS_WEAPON'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_WEAPON><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['MS_VOR']>0) 
			{
				echo '<tr><td height="20" valign="middle">Специализация вора: </td><td align="right">'.$char['MS_VOR'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_VOR><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['MS_VSADNIK']>0) 
			{
				echo '<tr><td height="20" valign="middle">Специализация всадника: </td><td align="right">'.$char['MS_VSADNIK'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_VSADNIK><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['MS_PARIR']>0) 
			{
				echo '<tr><td height="20" valign="middle">Мастер парирования: </td><td align="right">'.$char['MS_PARIR'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_PARIR><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['MS_LEK']>0) 
			{
				echo '<tr><td height="20" valign="middle">Специализация лекаря: </td><td align="right">'.$char['MS_LEK'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_LEK><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['MS_KUZN']>0) 
			{
				echo '<tr><td height="20" valign="middle">Специализация кузнеца: </td><td align="right">'.$char['MS_KUZN'].' <a href=town.php?option='.$option.'&do=nav_down&p=MS_KUZN><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['skill_war']>0) 
			{
				echo '<tr><td height="20" valign="middle">Магия "Воин": </td><td align="right">'.$char['skill_war'].' <a href=town.php?option='.$option.'&do=nav_down&p=skill_war><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['skill_cook']>0) 
			{
				echo '<tr><td height="20" valign="middle">Магия "Волшебник": </td><td align="right">'.$char['skill_cook'].' <a href=town.php?option='.$option.'&do=nav_down&p=skill_cook><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['skill_art']>0) 
			{
				echo '<tr><td height="20" valign="middle">Магия "Лучник": </td><td align="right">'.$char['skill_art'].' <a href=town.php?option='.$option.'&do=nav_down&p=skill_art><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['skill_explor']>0) 
			{
				echo '<tr><td height="20" valign="middle">Магия "Паладин": </td><td align="right">'.$char['skill_explor'].' <a href=town.php?option='.$option.'&do=nav_down&p=skill_explor><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['skill_craft']>0) 
			{
				echo '<tr><td height="20" valign="middle">Магия "Варвар": </td><td align="right">'.$char['skill_craft'].' <a href=town.php?option='.$option.'&do=nav_down&p=skill_craft><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['skill_card']>0) 
			{
				echo '<tr><td height="20" valign="middle">Магия "Вор": </td><td align="right">'.$char['skill_card'].' <a href=town.php?option='.$option.'&do=nav_down&p=skill_card><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['skill_uknow']>0) 
			{
				echo '<tr><td height="20" valign="middle">Магия "Разбойник": </td><td align="right">'.$char['skill_uknow'].' <a href=town.php?option='.$option.'&do=nav_down&p=skill_uknow><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['skill_music']>0) 
			{
				echo '<tr><td height="20" valign="middle">Магия "Бард": </td><td align="right">'.$char['skill_music'].' <a href=town.php?option='.$option.'&do=nav_down&p=skill_music><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			 if ($char['skill_pet']>0) 
			{
				echo '<tr><td height="20" valign="middle">Магия "Друид": </td><td align="right">'.$char['skill_pet'].' <a href=town.php?option='.$option.'&do=nav_down&p=skill_pet><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			echo '</table>';
			OpenTable('close');
			
			//Таблица скидывания профессий
			$prof_test=myquery("Select t1.craft_index, t2.name from game_users_crafts as t1 Join game_craft_prof as t2 on t1.craft_index=t2.prof_id
				                    where t1.user_id=$user_id and t1.profile=1 and t1.craft_index not in (1,2)");
			if (mysql_num_rows($prof_test)>0)
			{
				echo '</tr></td><tr valign="top" ><td colspan="2" >';
				OpenTable('title');
				echo '<table border="0" cellspacing="0" cellpadding="5" width="100%">';
				while ($prof=mysql_fetch_array($prof_test))
				{

					echo '<tr><td align="center">'.$prof["name"].' <a href=town.php?option='.$option.'&do=prof_down&prof='.$prof["craft_index"].'><img src="http://'.IMG_DOMAIN.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
				}
				echo '</table>';
				OpenTable('close');
			}
			
			echo '</tr></td></table>';
		}
		else
		{
			echo'<center>Для перераспределения навыков нужно быть больше 5-го уровня';
		}
	}

	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	

}

if (function_exists("save_debug")) save_debug(); 

?>