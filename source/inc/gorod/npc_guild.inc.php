<?php

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	if (isset($town_id) AND $town_id!=$town)
	{
	echo'Ты находишься в другом городе!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти" onClick=location.href="act.php">&nbsp;&nbsp;&nbsp;';
	}

	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено входить в Гильдию!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	//Основное окно Гильдии
	echo'<b>Гильдия охотников за монстрами</b>';
	
	// Проверка на наличие профессии
	$test = myquery("SELECT * From game_users_guild Where user_id='$user_id'");
	
	$f=0; //флаг
	$prof_cost=1000; //стоимость получения профессии
	if (mysql_num_rows($test)==1) $guild = mysql_fetch_array($test); //количество выполенных заданий у игрока
	
	if (isset($_GET['quest_now'])) 
	{
		$f=1;
		echo '<p align="left">Глава Гильдии испытывающе на вас смотрит.<br />
		<i>- Нам нужны новые охотники за монстрами. В награду за свою деятельность мы предоставим услуги лучших кузнецов. Но, </i>- лицо главы исказила хитрая ухмылка, <i>- за вступление к нам придёться заплатить...и заплатить немало. <b>'.$prof_cost.'</b> монет и ты присоединишься к нам.</i></p><br />
		<input type="submit" value="Заплатить деньги" onclick="location.replace(\'town.php?option='.$option.'&part1&new_guild\')"></input>&nbsp;&nbsp;&nbsp;
		<input type="submit" value="Развернуться и уйти" onclick="location.replace(\'town.php?option='.$option.'&quest_later\')"></input>';
	}
	
	if (isset($_GET['new_guild'])) 
	{
		$test = myquery("SELECT * From game_users_guild Where user_id='$user_id'");
		if (mysql_num_rows($test)==0)
		{
			$test2 = myquery("SELECT * From game_users Where user_id=$user_id and GP>$prof_cost");
			if (mysql_num_rows($test2)==0)
			{
				echo '<p align="left">Глава Гильдии презрительно усмехается:</br>
					 <i>- Ты меня обмануть вздумал? На первый раз я тебя прощаю, но не смей больше возвращаться без денег!</p>';
					 $f=1;
			}	
			else
			{
				myquery("INSERT INTO game_users_guild (user_id) VALUES ($user_id)");
				myquery("UPDATE game_users SET GP=GP-$prof_cost,CW=CW-".($prof_cost*money_weight)." WHERE user_id=$user_id");
				setGP($user_id,-$prof_cost,106); 
				echo '<br /><br /><b>Поздравляем с полноценным вступлением в Гильдию и изучением новой профессии!</b>';
				$f=1;
				$guild=0;
			}
		}
	}
	
	if (isset($_GET['quest_later'])) 
	{
		$f=1;
	}
	
	if (mysql_num_rows($test)==0 and $f!=1 and $town==12)
	{
		echo '<center><br />Один из юных охотников за монстрами сообщает, что у главы Гильдии охотников есть для Вас некое предложение<br /><br />
		<ul><li><a href="town.php?option='.$option.'&quest_now">Поговорить с главой Гильдии охотников</a></li><br />
		<li><a href="town.php?option='.$option.'&quest_later">Проигнорировать предлагаемый разговор</a></li></ul></center>';
	}
	
	
	// Основное действо Гильдии
	echo'<table border="0" cellpadding="8" cellspacing="1" style="border-collapse: collapse" width="96%" bgcolor="111111"><tr><td></td></tr></table>';
	
	
	$sel = myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.npc_quest_guild='$town' AND game_npc.npc_quest_end_time>".time()." AND game_npc_template.npc_id=game_npc.npc_id");
	if (mysql_num_rows($sel)>0)
	{
		$npc = mysql_fetch_array($sel);
		$npc_exp = floor($npc['npc_exp_max']*1.2);
		$end_time = $npc['npc_quest_end_time']-time();
		echo'В данный момент в нашей гильдии объявлена охота за этим монстром:<br><br>';
		QuoteTable('open');
		$player=$npc;
		$quest_id=$npc['npc_quest_id'];

		echo'<font face="Verdana" size="2" color="#f3f3f3"><b>'.$player['npc_name'].'</b></font></div>';
		echo '<img src="http://'.IMG_DOMAIN.'/npc/'.$player['npc_img'].'.gif" border="0" align="left">';

		echo '<table cellpadding="2" cellspacing="0" width="100%" border="0">';
		/*
		if ($player['npc_max_hp']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = number_format($player['npc_hp'] / $player['npc_max_hp'] * 100, 0);
		}
		if ($bar_percentage >= '100')
		{
			$bar_percentage = '100';
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_green.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= '0')
		{
			$bar_percentage = '0';
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_green.gif" width="' . $bar_percentage . '" height="7" border="0">';
		}
		echo '
		<tr>
		<td>Здоровье</td>
		<td width=70% align=right>' . $player['npc_hp'] . ' / ' . $player['npc_max_hp'] . '</td>
		</tr>
		<tr>
		<td colspan="2"><div align="right"><img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'
		. $append_string . '<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"><br><img
		src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></div></td>
		</tr>
		';

		if ($player['npc_max_mp']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = number_format($player['npc_mp'] / $player['npc_max_mp'] * 100, 0);
		}
		if ($bar_percentage >= '100')
		{
			$bar_percentage = '100';
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_orange.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= '0')
		{
			$bar_percentage = '0';
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_orange.gif" width="' . $bar_percentage . '" height="7" border="0">';
		}
		echo '
		<tr>
		<td>Мана</td>
		<td width=70% align=right>' . $player['npc_mp'] . ' / ' . $player['npc_max_mp'] . '</td>
		</tr>
		<tr>
		<td colspan="2"><div align="right"><img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'
		. $append_string . '<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"><br><img
		src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></div></td>
		</tr>
		';
		*/
		echo '<tr>
		<td>Сила</td><td><div align="right">'.$player['npc_str'].'&plusmn;'.$player['npc_str_deviation'].'</td></tr>
		<tr><td>Ловкость</td><td><div align="right">'.$player['npc_pie'].'&plusmn;'.$player['npc_pie_deviation'].'</td></tr>
		<tr><td>Защита</td><td><div align="right">'.$player['npc_vit'].'&plusmn;'.$player['npc_vit_deviation'].'</td></tr>
		<tr><td>Выносливость</td><td><div align="right">'.$player['npc_dex'].'&plusmn;'.$player['npc_dex_deviation'].'</td></tr>
		<tr><td>Мудрость</td><td><div align="right">' . $player['npc_spd'] . '&plusmn;'.$player['npc_spd_deviation'].'</td></tr>
		<tr><td>Интеллект</td><td><div align="right">'.$player['npc_ntl'].'&plusmn;'.$player['npc_ntl_deviation'].'</td></tr>
		<tr><td>Опыт за победу</td><td><div align="right">' . $player['npc_exp_max'] . '</td></tr></table>';
		QuoteTable('close');

		QuoteTable('open');
		echo'<font size=2 color=#F0F0F0><div align="justify">Ты можешь взять у нас задание на убийство этого монстра! Приходи к нам после того, как убьешь монстра и принеси нам его голову (или любую другую часть тела) как доказательство выполнения задания! В качестве оплаты за оказанные тобой услуги мы дадим тебе <b><font color=#FF0000>'.$npc_exp.'</font></b> очков опыта, а также бесплатно частично починим один из твоих предметов. Но мы должны предупредить тебя, что заплатим мы только одному - самому первому искателю приключений - который принесет нам доказательства убийства этого монстра. И еще! Учти, у тебя только 1 попытка и твоя награда будет зависеть от того, справишься ли ты с заданием один, или позовешь на помощь друзей.';
		QuoteTable('close');

		QuoteTable('open');
		$min = floor($end_time/60);
		$sec = $end_time-$min*60;
		echo '<font size=2 color=#FFFF80>Задание действительно еще '.$min.'  мин. '.$sec.' сек.</font>';
		QuoteTable('close');

		$quest_users = mysql_result(myquery("SELECT COUNT(*) FROM game_quest_users WHERE quest_id='$quest_id' AND sost='".$npc['npc_id']."'"),0,0);
		QuoteTable('open');
		if ($quest_users==0) echo '<font size=2 color=#FFFF80>Еще ни один герой не вызывался на наше задание.</font>';
		else echo '<font size=2 color=#FFFF80>За этим монстром уже охотятся '.$quest_users.' героев.</font>';
		QuoteTable('close');

		$sel_quest = myquery("SELECT sost FROM game_quest_users WHERE user_id='$user_id' AND quest_id='$quest_id'");
		if (mysql_num_rows($sel_quest)>0)
		{
			list($sost) = mysql_fetch_array($sel_quest);

			if ($sost==$npc['npc_id'])
			{
				$sel_item = myquery("SELECT item_uselife FROM game_items WHERE user_id='$user_id' AND used=0 AND item_for_quest='$quest_id' AND priznak=0");
				if (mysql_num_rows($sel_item)>0)
				{
					if (!isset($take_head))
					{
						QuoteTable('open');
						echo'<font size=2 color=#F0F0F0><div align="center">Поздравляю! Ты '.echo_sex('убил','убила').' монстра "'.$npc['npc_name'].'" и мы выполним наш контракт!<br>';
						echo'<form action="" method="post"><input type="submit" name="take" value="Отдать часть тела монстра"><input type="hidden" name="take_head"><input name="town_id" type="hidden" value="'.$town.'">';
						QuoteTable('close');
					}
					else
					{
						QuoteTable('open');
						$it = mysql_fetch_array($sel_item);
						$proc = $it['item_uselife']/100;
						$npc_exp = floor($npc_exp*$proc);
						echo'<div align="center"><font size=2 color=#F0F0F0>Поздравляю! Вот твои "'.$npc_exp.'" очков опыта за убитого монстра!<br>';
						echo'<br>*** <font size=2 color=#00FF00>Ты получаешь к текущему опыту <font color=#FF0000>'.$npc_exp.'</font> очков </font> ***';
						myquery("UPDATE game_npc SET npc_quest_guild=0,npc_quest_end_time=0,npc_quest_id=0 WHERE npc_quest_guild=$town");
						myquery("UPDATE game_users SET EXP=EXP+$npc_exp WHERE user_id=$user_id");
						setEXP($user_id,$npc_exp,5);
						myquery("DELETE FROM game_items WHERE item_for_quest=$quest_id");
						myquery("DELETE FROM game_quest_users WHERE quest_id=$quest_id");
						myquery("INSERT INTO game_npc_guild_log (user_id,user_name,time_end,vremya,exp) VALUES ($user_id,'".$char['name']."',".time().",'".date("d-m-Y H:i",time())."',$npc_exp)");
						
						// Починка предметов
						$r = mt_rand(1,17);
						if($r==12) $r=18;
						if($r==13) $r=19;
						if($r==14) $r=20;
						$it = myquery("SELECT item_uselife,id FROM game_items WHERE user_id=$user_id AND item_uselife<100 AND used=$r AND priznak=0");
						if (mysql_num_rows($it))
						{
							if ($r==1) $rem=25; 
                               else $rem=8; 
							if (isset($guild)) $rem=$rem+$guild['guild_lev'];
							$it = mysql_fetch_array($it);
							$uslife = min(100,$it['item_uselife']+$rem*$proc);
							myquery("UPDATE game_items SET item_uselife=$uslife WHERE user_id=$user_id AND id = ".$it['id']." AND priznak=0");
							echo'<br>*** <font size=2 color=#00FF00>Наш кузнец бесплатно починил один из твоих предметов</font> ***';
						}
						
						
						// Повышение уровня профессии, если оная есть
						if (isset($guild))
						{
							$guild['guild_times']++;
							myquery("UPDATE game_users_guild SET guild_times=guild_times+1 WHERE user_id=$user_id");
							$i=$guild['guild_times'];
							$j=0;
							do 
							{
								$j++;
								$i=$i-$j*5-5;
							} while ($i > 0);
							if ($i==0 and $j<27) 
							{
								$guild['guild_lev']++;
								myquery("UPDATE game_users_guild SET guild_lev=guild_lev+1 WHERE user_id=$user_id");
							}
						}
						
						QuoteTable('close');
					}
				}
				else
				{
					QuoteTable('open');
					echo'<font size=2 color=#F0F0F0><div align="justify">Ты уже '.echo_sex('взял','взяла').' задание на убийство монстра, и мы ждем что ты его исполнишь! Приходи к нам после того, как убьешь монстра и принеси нам его голову (или любую другую часть тела) в качестве доказательства выполнения задания!';
					QuoteTable('close');
				}
			}
			else
			{
				QuoteTable('open');
				echo'<font size=2 color=#F0F0F0><div align="justify">Ты уже '.echo_sex('взял','взяла').' задание на убийство монстра, но не '.echo_sex('смог','смогла').' убить его. Нам не нужны такие герои! Можешь попытать счастья в другом филиале гильдии!';
				QuoteTable('close');
			}
		}
		else
		{
			if (!isset($take_quest))
			{
				QuoteTable('open');
				echo'<form action="" method="post"><input type="submit" name="take" value="Взять задание на убийство монстра "'.$npc_exp.'""><input type="hidden" name="take_quest"><input name="town_id" type="hidden" value="'.$town.'">';
				QuoteTable('close');
			}
			else
			{
				QuoteTable('open');
				echo'<font size=2 color=#F0F0F0><div align="justify">Итак! Мы заключили контракт! Удачи в твоем опасном деле! И не забудь - ТЫ '.echo_sex('ДОЛЖЕН','ДОЛЖНА').' ПРИНЕСТИ НАМ ДОКАЗАТЕЛЬСТВА '.echo_sex('ПЕРВЫМ','ПЕРВОЙ').'! (иначе ты ничего не получишь)!';
				myquery("INSERT INTO game_quest_users(quest_id,user_id,sost) VALUES ('$quest_id','$user_id','".$npc['npc_id']."')");
				QuoteTable('close');
			}
		}
	}
	else
	{
		$sel_npc_other = myquery("SELECT game_npc.*,game_npc_template.npc_name FROM game_npc,game_npc_template WHERE game_npc.npc_quest_id>0 AND game_npc.npc_quest_end_time>".time()." AND game_npc.npc_id=game_npc_template.npc_id");
		if (mysql_num_rows($sel_npc_other)>0)
		{
			echo'<hr>Извини, но сейчас в нашем филиале гильдии нет для тебя работы. Приходи позже!<hr>';
			if (isset($guild)) $info_cost=200-$guild['guild_lev']*7.6;
			else $info_cost=200;
			if (!isset($know_quest_where))
			{
				QuoteTable('open');
				echo'<font size=2 color=#F0F0F0><div align="justify">Но мы готовы поделиться с тобой информацией в каких филиалах гильдии еще не выполнено задание. Только это будет стоить для тебя '.$info_cost.' '.pluralForm($info_cost,'монету','монеты','монет').'';
				QuoteTable('close');
				QuoteTable('open');
				echo'<form action="" method="post"><input type="submit" name="take_info" value="Заплатить за информацию"><input type="hidden" name="know_quest_where"><input name="town_id" type="hidden" value="'.$town.'">';
				QuoteTable('close');
			}
			else
			{
				if ($char['GP']>=$info_cost)
				{
					QuoteTable('open');
					echo'<font size=2 color=#F0F0F0><div align="justify">Спасибо что воспользовался услугами нашего информационного отдела:<br><br><center><b>Вот нужная тебе информация:</b></center><br><br>';
					myquery("UPDATE game_users SET GP=GP-200,CW=CW-'".($info_cost*money_weight)."' WHERE user_id='$user_id'");
					setGP($user_id,$info_cost,47);
					echo'<table width=100% cellspacing=2 cellpadding=2>';
					echo '<tr><th>Город</th><th>Монстр</th><th>Ост.время</th></tr>';
					while ($npc_other = mysql_fetch_array($sel_npc_other))
					{
						$town_id = $npc_other['npc_quest_guild'];
						$end_time = $npc_other['npc_quest_end_time']-time();
						$npc_name = $npc_other['npc_name'];
						$min = floor($end_time/60);
						$sec = $end_time-$min*60;
						list($rustown) = mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town='$town_id'"));
						$map = mysql_fetch_array(myquery("SELECT * FROM game_map WHERE town='$town_id' AND to_map_name=0"));
						list($map_name) = mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"));
						echo '<tr><td>'.$rustown.'</td><td>'.$npc_name.'</td><td>'.$min.' мин. '.$sec.' сек.</td></tr>';
						//echo '<tr><td>Где-то в '.$map_name.'</td><td>'.$npc_name.'</td><td>'.$min.' мин. '.$sec.' сек.</td></tr>';
					}
					echo'</table>';
					QuoteTable('close');
				}
				else
				{
					QuoteTable('open');
					echo'<font size=2 color=#F0F0F0><div align="justify">У тебя нет 200 монет для оплаты наших услуг.';
					QuoteTable('close');
				}
			}
		}
		else
		{
			echo'<hr>Извини, но сейчас работа гильдии охотников за монстрами закрыта в связи с ожиданием от главы Гильдии новых указаний. Приходи позже!<hr>';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>