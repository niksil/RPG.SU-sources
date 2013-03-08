<?
function show_combat_log($boy,$for_hod=0,$width="100%")
{
	$est = 0;
	$text = '<table width='.$width.' id="log_table" border=1 cellspacing=0 cellpadding=0><tbody>';
	$boy=(int)$boy;
	if ($for_hod>0)
	{
		$add_where=' AND game_combats_log_data.hod='.$for_hod;
	}
	else
	{
		$add_where='';
	}
	$sel = myquery("SELECT game_combats_log_data.* , text1.name AS name, text1.mode AS mode, text1.kuda AS kuda , text2.name AS na_kogo_name, text2.mode AS na_kogo_id , text3.name AS kto_name, text3.mode AS kto_id, sex1.sex AS sex
	FROM (
	game_combats_log_data
	)
	LEFT JOIN game_combats_log_text AS text1 ON ( text1.id = game_combats_log_data.text_id )
	LEFT JOIN game_combats_log_text AS text2 ON ( text2.id = game_combats_log_data.na_kogo )
	LEFT JOIN game_combats_log_text AS text3 ON ( text3.id = game_combats_log_data.kto )
	LEFT JOIN game_users_data AS sex1 ON ( sex1.user_id = game_combats_log_data.user_id )
	WHERE game_combats_log_data.boy =$boy AND game_combats_log_data.action<>99 $add_where
	ORDER BY game_combats_log_data.hod, game_combats_log_data.sort, game_combats_log_data.id");

	$cur_user = -1;
	$cur_hod = 0;
	while ($log=mysql_fetch_array($sel))
	{
		if (($cur_user!=$log['user_id'])OR($cur_hod!=$log['hod']))
		{
			if ($cur_user!=-1)
			{
				if ($prev_log['kto_name']=='') $prev_log['kto_name']='&nbsp;';
				$text.='<tr height="20"><td height="20" align="center" valign="center"><b>'.$prev_log['hod'].'</b></td><td height="20" align="center" valign="center"><b><font color="#80FF80">'.$prev_log['kto_name'].'</font></b></td><td height="20" valign="center" style="padding-left:5px;">'.$action.'</td></tr>';
				$est=1;
			}
			$action = '';
			$cur_user=$log['user_id'];
		}
		if ($cur_hod!=$log['hod'])
		{
			if ($cur_hod>0)
			{
				$text.='<tr style="height:5px;background-color:#000080"><td colspan="3"></td></tr>';
				$est = 1;
			}
			$cur_hod=$log['hod'];
		}
		switch ($log['action'])
		{
			case 1:
			{
				$action.= 'Начат обсчет хода №'.$log['hod'].'';
			}
			break;
			case 3:
			{
				/*
				$selpol = myquery("SELECT sex FROM game_users_data WHERE user_id IN (SELECT user_id FROM game_users WHERE name='".$log['na_kogo_name']."')");
				if (!mysql_num_rows($selpol)) $selpol = myquery("SELECT sex FROM game_users_data WHERE user_id IN (SELECT user_id FROM game_users_archive WHERE name='".$log['na_kogo_name']."')");
				list($l_pol) = mysql_fetch_array($selpol);
				*/
				$l_pol = 'male';
				$action.= 'В бой '.echo_sex('вступил','вступила',$l_pol).' <span style="font-weight:900;color:gold;font-style:italic;">'.$log['na_kogo_name'].'</span>';
				if ($log['mode']!=NULL)
				{
					if ($log['mode']=='1')
					{
						$action.=' (использовав малый свиток присоединения)';
					}
					if ($log['mode']=='2')
					{
						$action.=' (использовав средний свиток присоединения, присоединился к '.$log['name'].')';
					}
					if ($log['mode']=='3')
					{
						$action.=' (использовав большой свиток присоединения, присоединился к '.$log['name'].')';
					}
				}
			}
			break;
			case 4:
			{
				$action.= 'Жаль тебе это сообщать...Но ты - '.echo_sex('убит','убита',$log['sex']).'!';
			}
			break;
			case 5:
			{
				$action.= 'Нельзя сходить более чем на 100%';
			}
			break;
			case 6:
			{
				$action.= 'Игрок <font color="white"><b>'.$log['na_kogo_name'].'</b></font> не участвует в этом ходе боя или уже убит';
			}
			break;
			case 61:
			{
				$action.= 'Игрок <font color="white"><b>'.$log['na_kogo_name'].'</b></font> не участвует в этом ходе боя или уже убита';
			}
			break;
			case 7:
			{
				$action.= ''.echo_sex('Использовал','Использовала',$log['sex']).' заклинание лечения на <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> '.$log['mode'].' ('.$log['name'].')> (<font color=#FFBC79>'.$log['procent'].'%</font>). ';
				if ($log['add_hp']>0)
				{
					$action.='+<font color=#00FF80>'.$log['add_hp'].'</font> ед.здоровья';
				}
				if ($log['add_mp']>0)
				{
					if ($log['add_hp']>0)
					{
						$action.=', ';
					}
					$action.='+<font color=#00FF80>'.$log['add_mp'].'</font> ед.маны';
				}
				if ($log['add_stm']>0)
				{
					if ($log['add_hp']>0 OR $log['add_mp']>0)
					{
						$action.=', ';
					}
					$action.='+<font color=#00FF80>'.$log['add_stm'].'</font> ед.энергии';
				}
				$action.='. Истрачено ';
				if ($log['minus_hp']>0)
				{
					$action.='-<font color=#79D3FF>'.$log['minus_hp'].'</font> ед.здоровья';
				}
				if ($log['minus_mp']>0)
				{
					if ($log['minus_hp']>0)
					{
						$action.=', ';
					}
					$action.='-<font color=#79D3FF>'.$log['minus_mp'].'</font> ед.маны';
				}
				if ($log['minus_stm']>0)
				{
					if ($log['minus_hp']>0 OR $log['minus_mp']>0)
					{
						$action.=', ';
					}
					$action.='-<font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
				}
				$action.= '.';
			}
			break;
			case 8:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Нехватает ед.маны, ед.здоровья или ед.энергии для лечения c помощью магического навыка <font color=#8080C0>'.$log['mode'].' ('.$log['name'].')</font>';
			}
			break;
			case 9:
			{
				$action.= 'Применено неопознанное магическое заклинание.';
			}
			break;
			case 10:
			{
				$action.= ''.echo_sex('Использовал','Использовала',$log['sex']).' артефакт лечения <'.$log['name'].'> на <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#FFBC79>'.$log['procent'].'%</font>). +<font color=#00FF80>'.$log['add_hp'].'</font> ед.здоровья.   Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии.';
			}
			break;
			case 11:
			{
				$action.= 'Неопознанный артефакт';
			}
			break;
			case 12:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Нехватает ед.энергии для лечения артефактом.';
			}
			break;
			case 13:
			{
				$action.= ''.echo_sex('Использовал','Использовала',$log['sex']).' эликсир '.$log['name'].' на <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#FFBC79>'.$log['procent'].'%</font>).   Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
			}
			break;
			case 14:
			{
				$action.= 'Неопознанный эликсир';
			}
			break;
			case 15:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Нехватает ед.энергии для использования эликсира';
			}
			break;
			case 16:
			{
                $addstr = '';
                if ($log['minus_hp']==5) {$addstr='круговую';$log['name']='все тело';};
                $action.= ''.echo_sex('Поставил','Поставила',$log['sex']).' '.$addstr.' защиту <font color=ff0000><b>'.$log['mode'].'</b></font> (<font color=#FFBC79>'.$log['procent'].'%</font>) на '.$log['name'].' игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (-<font color=#82FFFF>'.$log['add_hp'].'</font> ед.урона здоровья)     ';
                if ($log['minus_mp']!=0)
                {
                    $action.= 'Истрачено <font color=#79D3FF>'.$log['minus_mp'].'</font> ед.маны';
                }
                else
                {
                    $action.= 'Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
                }
			}
			break;
			case 17:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Нехватает ед.энергии для защиты '.$log['mode'].'';
			}
			break;
			case 18:
			{
				$action.= 'Неопознанный щит!';
			}
			break;
			case 19:
			{
				$action.= ''.echo_sex('Защищал','Защищала',$log['sex']).' заклинанием защиты '.$log['mode'].' ('.$log['name'].')> (<font color=#FFBC79>'.$log['procent'].'%</font>) игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (';
				if ($log['add_hp']!=0)
				{
					$action.='-<font color=#82FFFF>'.$log['add_hp'].'</font> ед.урона здоровья';
				}
				if ($log['add_mp']!=0)
				{
					if ($log['add_hp']!=0)
					{
						$action.=', ';
					}
					$action.='-<font color=#82FFFF>'.$log['add_mp'].'</font> ед.урона маны';
				}
				if ($log['add_stm']!=0)
				{
					if ($log['add_hp']!=0 OR $log['add_mp']!=0)
					{
						$action.=', ';
					}
					$action.='-<font color=#82FFFF>'.$log['add_stm'].'</font> ед.урона энергии';
				}
				$action.=').   Истрачено ';
				if ($log['minus_mp']!=0)
				{
					$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ед.маны';
				}
				if ($log['minus_hp']!=0)
				{
					if ($log['minus_mp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#79D3FF>'.$log['minus_hp'].'</font> ед.здоровья';
				}
				if ($log['minus_stm'])
				{
					if ($log['minus_mp']!=0 OR $log['minus_hp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
				}
				$action.='.';
			}
			break;
			case 20:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Нехватает ед.маны, ед.здоровья или ед.энергии для защиты магическим заклинанием <font color=#8080C0>'.$log['mode'].' ('.$log['name'].')</font>';
			}
			break;
			case 21:
			{
				$action.= 'Неопознанное магическое заклинание защиты!';
			}
			break;
			case 22:
			{
				$action.= ''.echo_sex('Поставил','Поставила',$log['sex']).' защиту на '.$log['mode'].' (<font color=#FFBC79>'.$log['procent'].'%</font>) '.$log['name'].' игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (-<font color=#82FFFF>'.$log['add_hp'].'</font> ед.урона здоровья)     Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
			}
			break;
			case 23:
			{
				$action.= 'Неопознанный защитный артефакт!';
			}
			break;
			case 24:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Нехватает ед.энергии для защиты артефактом';
			}
			break;
			case 25:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Ты '.echo_sex('промахнулся','промахнулась',$log['sex']).'!';
			}
			break;
			case 26:
			{
				$action.= '<font color=ffff00><b>КРИТ.УДАР!</b></font> '.echo_sex('Нанес','Нанесла',$log['sex']).' '.$log['name'].' <кулаком> (<font color=#FFBC79>'.$log['procent'].'%</font>) в <font color=ff0000><b>'.$log['mode'].'</b></font> игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья)';
			}
			break;
			case 27:
			{
				$action.= ''.echo_sex('Укусил','Укусила',$log['sex']).' из последних сил (<font color=#FFBC79>'.$log['procent'].'%</font>) в <font color=ff0000><b>'.$log['mode'].'</b></font> игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья)';
			}
			break;
			case 28:
			{
				$action.= ''.echo_sex('Нанес','Нанесла',$log['sex']).' '.$log['name'].' <кулаком> (<font color=#FFBC79>'.$log['procent'].'%</font>) в <font color=ff0000><b>'.$log['mode'].'</b></font> игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья)';
			}
			break;
			case 29:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Ты '.echo_sex('промахнулся','промахнулась',$log['sex']).'!     Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
			}
			break;
			case 30:
			{
				$action.= '<font color=ff0000><b>Во время мощной атаки ты '.echo_sex('угодил','угодила',$log['sex']).' оружием в землю и полностью его '.echo_sex('сломал','сломала',$log['sex']).'</b></font>';
			}
			break;
			case 31:
			{
				$action.= '<font color=ff0000><b>Ты '.echo_sex('угодил','угодила',$log['sex']).' оружием в землю и '.echo_sex('потерял','потеряла',$log['sex']).' '.$log['procent'].'% прочности</b></font>';
			}
			break;
			case 32:
			{
				$action.= '<font color=ffff00><b>КРИТ.УДАР!</b></font> '.echo_sex('Нанес','Нанесла',$log['sex']).' '.$log['name'].' оружием <'.$log['mode'].'> (<font color=#FFBC79>'.$log['procent'].'%</font>) в <font color=ff0000><b>'.$log['kuda'].'</b></font> игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья).     Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
			}
			break;
			case 33:
			{
				$action.= ''.echo_sex('Нанес','Нанесла',$log['sex']).' '.$log['name'].' оружием <'.$log['mode'].'> (<font color=#FFBC79>'.$log['procent'].'%</font>) в <font color=ff0000><b>'.$log['kuda'].'</b></font> игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья).     Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
			}
			break;
			case 34:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Оружие полностью сломано!';
			}
			break;
			case 35:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Не хватает энергии для атаки оружием';
			}
			break;
			case 36:
			{
				$action.= 'Удар неопознанным оружием!';
			}
			break;
			case 37:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>.  Ты не '.echo_sex('смог','смогла',$log['sex']).' удержать магическую энергию заклинания '.$log['mode'].' ('.$log['name'].').';
				if (($log['minus_mp']!=0) OR ($log['minus_hp']) OR ($log['minus_stm']))
				{
					$action.= '   Истрачено ';
					if ($log['minus_mp']!=0)
					{
						$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ед.маны';
					}
					if ($log['minus_hp']!=0)
					{
						if ($log['minus_mp']!=0)
						{
							$action.=', ';
						}
						$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ед.здоровья';
					}
					if ($log['minus_stm']!=0)
					{
						if ($log['minus_mp']!=0 OR $log['minus_hp']!=0)
						{
							$action.=', ';
						}
						$action.='<font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
					}
					$action.='.';
				}
			}
			break;
			case 38:
			{
				 $action.= ''.echo_sex('Произнес','Произнесла',$log['sex']).' атакующее заклинание <'.$log['mode'].' ('.$log['name'].')> (<font color=#FFBC79>'.$log['procent'].'%</font>) на игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (';
				if ($log['add_hp']!=0)
				{
					$action.='<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья';
				}
				if ($log['add_mp']!=0)
				{
					if ($log['add_hp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#F4518A>'.$log['add_mp'].'</font> ед.урона маны';
				}
				if ($log['add_stm']!=0)
				{
					if ($log['add_hp']!=0 OR $log['add_mp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#F4518A>'.$log['add_stm'].'</font> ед.урона энергии';
				}
				$action.=').    Истрачено ';
				if ($log['minus_mp']!=0)
				{
					$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ед.маны';
				}
				if ($log['minus_hp']!=0)
				{
					if ($log['minus_mp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ед.здоровья';
				}
				if ($log['minus_stm']!=0)
				{
					if ($log['minus_mp']!=0 OR $log['minus_hp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
				}
				$action.='.';
			}
			break;
			case 39:
			{
				if ($log['procent']!=0)
				{
					$action.= '(<font color=ff0000><b>Магическая сила может разрушить предмет, на нем уже появились первые трещины</b></font>. Прочность оружия снизилась на '.$log['procent'].'%)';
				}
			}
			break;
			case 40:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Нехватает ед.маны, ед.здоровья или ед.энергии для атаки магическим заклинанием <font color=#8080C0>'.$log['mode'].' ('.$log['name'].')</font>';
			}
			break;
			case 41:
			{
				$action.= 'Удар неопознанным атакующим заклинанием!';
			}
			break;
			case 42:
			{
				$action.= ''.echo_sex('Нанес','Нанесла',$log['sex']).' удар артефактом '.$log['mode'].' (<font color=#FFBC79>'.$log['procent'].'%</font>) в <font color=ff0000><b>'.$log['kuda'].'</b></font> игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья).     Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
			}
			break;
			case 43:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. В артефакте закончились заряды!';
			}
			break;
			case 44:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Не хватает энергии для атаки артефактом';
			}
			break;
			case 45:
			{
				$action.= 'Удар неопознанным атакующим артефактом!';
			}
			break;
			break;
			case 46:
			{
				$action.= '&nbsp;&nbsp;но игрок был защищен (';
				if ($log['minus_hp']!=0)
				{
					$action.='-'.$log['minus_hp'].' ед.урона здоровья';
				}
				if ($log['minus_mp']!=0)
				{
					if ($log['minus_hp']!=0)
					{
						$action.=', ';
					}
					$action.='-'.$log['minus_mp'].' ед.урона маны';
				}
				if ($log['minus_stm']!=0)
				{
					if ($log['minus_hp']!=0 OR $log['minus_mp']!=0)
					{
						$action.=', ';
					}
					$action.='-'.$log['minus_stm'].' ед.урона энергии';
				}
				$action.='). Суммарный урон: ';
				if ($log['add_hp']!=0 OR $log['minus_hp']!=0)
				{
					$action.=''.$log['add_hp'].' ед.урона здоровья';
				}
				if ($log['add_mp']!=0 OR $log['minus_mp']!=0)
				{
					if ($log['add_hp']!=0 OR $log['minus_hp']!=0)
					{
						$action.=', ';
					}
					$action.=''.$log['add_mp'].' ед.урона маны';
				}
				if ($log['add_stm']!=0 OR $log['minus_stm']!=0)
				{
					if ($log['add_hp']!=0 OR $log['add_mp']!=0 OR $log['minus_hp']!=0 OR $log['minus_mp']!=0)
					{
						$action.=', ';
					}
					$action=''.$log['add_stm'].' ед.урона энергии';
				}
				$action.='.';
			}
			break;
			case 47:
			{
				$action.= '<font color="#8080C0" face="Tahoma"><b>&nbsp;&nbsp;Ты '.echo_sex('погиб','погибла',$log['sex']).' от собственного заклинания</b></font>';
			}
			break;
			case 48:
			{
				$action.= '<font color="#FF8000" size="2" face="Tahoma"><b>&nbsp;&nbsp;'.$log['name'].' одержала победу над '.$log['na_kogo_name'].'</b></font>';
			}
			break;
			case 49:
			{
				$action.= '<font color="#FF8000" size="2" face="Tahoma"><b>&nbsp;&nbsp;'.$log['name'].' одержал победу над '.$log['na_kogo_name'].'</b></font>';
			}
			break;
			case 50:
			{
				$action.= '<font color="#0080C0" size="2" face="Verdana">'.$log['na_kogo_name'].' была убита на поле сражения.</font>';
			}
			break;
			case 51:
			{
				$action.= '<font color="#0080C0" size="2" face="Verdana">'.$log['na_kogo_name'].' был убит на поле сражения.</font>';
			}
			break;
			case 52:
			{
				if ($log['add_hp']!=0 OR $log['procent']!=0)
				{
					$action.= 'Ты получаешь ';
					if ($log['add_hp']!=0)
					{
						$action.='<b><font color="#FF0000">'.$log['add_hp'].'</font></b> опыта';
					}
					if ($log['procent']!=0)
					{
						if ($log['add_hp']!=0)
						{
							$action.=' и ';
						}
						$action.='<b><font color="#FF0000">'.$log['procent'].'</font></b> монет';
					}
					$action.='!';
				}
			}
			break;
			case 53:
			{
				$action.= '<font color="#0080C0" size="2" face="Verdana">'.$log['na_kogo_name'].' вылетела по таймауту.</font>';
			}
			break;
			case 54:
			{
				$action.= '<font color="#0080C0" size="2" face="Verdana">'.$log['na_kogo_name'].' вылетел по таймауту.</font>';
			}
			break;
			case 55:
			{
				$action.= 'Ты '.echo_sex('выиграл','выиграл',$log['sex']).' бой.    Ты '.echo_sex('заработал','заработала',$log['sex']).' ';
				if ($log['add_hp']>0) $action.='<b><font color="#FF0000">'.$log['add_hp'].'</font></b> очков опыта';
				if ($log['procent']>0)
				{
					if ($log['add_hp']>0) $action.=' и ';
					$action.='<b><font color="#FF0000">'.$log['procent'].'</font></b> монет';
				}
				if ($log['add_hp']==0 AND $log['procent']==0) $action.=' Н И Ч Е Г О ! (упс)';
			}
			break;
			case 56:
			{
				$action.= 'Ты '.echo_sex('согласился','согласилась',$log['sex']).' на ничью.';
			}
			break;
			case 57:
			{
				$action.= 'Лечить можно только своих союзников';
			}
			break;
			case 58:
			{
				$action.= 'Защищать можно только своих союзников';
			}
			break;
			case 59:
			{
				if ($log['procent']!=0)
				{
					$action.= '(<font color=ff0000><b>Оружие в руках раскалилось до красна и может взорваться</b></font>. Прочность оружия снизилась на '.$log['procent'].'%)';
				}
			}
			break;
			case 60:
			{
				$action.= 'Атаковать можно только своих противников';
			}
			break;
			case 61:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Не хватает энергии для выстрела';
			}
			break;
			case 62:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Не хватает маны для выстрела';
			}
			break;
			case 63:
			{
				$action.= 'Удар неопознанным луком!';
			}
			break;
			case 64:
			{
				$action.= ''.echo_sex('Выстрелил','Выстрелила',$log['sex']).' '.$log['mode'].' (в <font color=ff0000><b>'.$log['kuda'].'</b></font> игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья).     Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
			}
			break;
			case 65:
			{
				$action.= ''.echo_sex('Выстрелил','Выстрелила',$log['sex']).' '.$log['mode'].' (в <font color=ff0000><b>'.$log['kuda'].'</b></font> игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья).     Истрачено <font color=#79D3FF>'.$log['minus_mp'].'</font> ед.маны';
			}
			break;
			case 66:
			{
				$action.= 'Твой выстрел прошел мимо цели!';
			}
			break;
			case 67:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Не хватает энергии для броска';
			}
			break;
			case 68:
			{
				$action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Не хватает маны для броска';
			}
			break;
			case 69:
			{
				$action.= 'Бросок неопознанным предметом!';
			}
			break;
			case 70:
			{
				$action.= ''.echo_sex('Бросил','Бросила',$log['sex']).' '.$log['mode'].' (в <font color=ff0000><b>'.$log['kuda'].'</b></font>) игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья).     Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
			}
			break;
			case 71:
			{
				$action.= ''.echo_sex('Бросил','Бросила',$log['sex']).' '.$log['mode'].' (в <font color=ff0000><b>'.$log['kuda'].'</b></font>) игрока <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ед.урона здоровья).     Истрачено <font color=#79D3FF>'.$log['minus_mp'].'</font> ед.маны';
			}
			break;
			case 72:
			{
				$action.= 'Твой бросок прошел мимо цели!';
			}
			break;
            case 73:
            {
                $action.= echo_sex('Использовал','Использовала',$log['sex']).' '.$log['mode'].'.';
            }
            break;
            case 74:
            {
                $action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Не хватает энергии для использования свитка';
            }
            break;
            case 75:
            {
                $action.= '<font color=#FF0000><b>НЕУДАЧНО</b></font>. Не хватает маны для использования свитка';
            }
            break;
            case 76:
            {
                $action.= 'Использование неопознанного свитка!';
            }
            break;
            case 77:
            {
                $action.= '<font color=#FF0000><b>НЕУДАЧНАЯ</b></font> попытка круговой защиты оружием!';

                if ($log['minus_stm'] != 0)
                  $action.= ' Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.энергии';
                elseif ($log['minus_mp'] != 0)
                  $action.= ' Истрачено <font color=#79D3FF>'.$log['minus_stm'].'</font> ед.маны';
            }
            break;
            case 78:
            {
                $action.= 'Занял стандартную позицию.';
            }
            break;
            case 79:
            {
                $action.= 'Занял оборонительную позицию.';
            }
            break;
            case 80:
            {
                $action.= 'Занял атакующую позицию.';
            }
            break;
			case 81:
            {
				$action.= '<b><font color=#FFFFFF size="2" face="Verdana">'.$log['name'].'</font></b> призвал помощников в бой.';
            }
            break;
		}
		$action.='<br />';
		$prev_log = $log;
	}
	if (isset($prev_log))
	{
		if ($prev_log['kto_name']=='') $prev_log['kto_name']='&nbsp;';
		$text.='<tr height="20"><td height="20" align="center" valign="center"><b>&nbsp;'.$prev_log['hod'].'&nbsp;</b></td><td height="20" align="center" valign="center"><b><font color="#80FF80">'.$prev_log['kto_name'].'</font></b></td><td height="20" valign="center">&nbsp;&nbsp;'.$action.'</td></tr>';
		$est= 1;
	}
	$text.='</tbody></table>';

	if ($for_hod==0)
	{
		$sel = myquery("SELECT game_combats_log_data.*, text.name AS user_name, text.mode AS clan_id, text.kuda AS clevel
		FROM (
		game_combats_log_data
		)
		LEFT JOIN game_combats_log_text AS text ON ( text.id = game_combats_log_data.kto )
		WHERE game_combats_log_data.boy=$boy AND game_combats_log_data.action=99
		ORDER BY text.mode,text.name");

		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			$text.='<br><br>Битва окончена! <br>';
			$i = mysql_num_rows($sel);
			if ($i<=1)
			{
				$text.='Победил:  ';
			}
			else
			{
				$text.='Победили:  ';
			}
			while ($log = mysql_fetch_array($sel))
			{
				if ((int)$log['clan_id']>0) $text.='<img src="http://'.IMG_DOMAIN.'/clan/'.$log['clan_id'].'.gif">';
				$text.=''.$log['user_name'].'['.$log['clevel'].']';
				$i--;
				if ($i>0)
				{
					$text.=', ';
				}
				else
				{
					$text.='.';
				}
			}
		}
	}

	if ($est==0) return '';

	return $text;
}
?>