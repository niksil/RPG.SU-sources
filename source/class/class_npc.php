<?php

//TODO:
// сделать шаблон бота-босса Минотавр Лабиринта и его id прописать в xray.inc.php

// Написать Методы:
// Перемещение "шагающего" бота к ближайшей "цели"

/*====!!=====
Опции ботов (таблица: game_npc_option)
(Основные цифры (1,2,3... -> значения в таблице: game_npc_set_option)
(Дополнительные цифры (1.1,1.2,2.1... -> значения в таблице: game_npc_set_option_value)::
1 - Не промахивается
2 - Не защищается
3 - Бьёт одного игрока
4 - Фиксированным урон
	4.1 - Минимальное значение
	4.2 - Максимальное значение
5 - Бьёт на процент от жизней игрока
	5.1 - Процент от жизней
6 - Призывает ботов
	6.1 - Идф бота
	6.2 - Количество ботов
7 - Бот по уровню
	7.1 - Минимальный уровень
	7.2 - Максимальный уровень
8 - Бот копирует навыки игрока
9 - Жизни бота равны жизням игрока
	9.1 - Коэффициент равенства
10 - Мана бота равна мане игрока
	10.1 - Коэффициент равенства
11 - Автогенерация характеристик
	11.1 - Тип шаблона
	11.1 - Коэффициент характеристик
	11.2 - Коэффициент разброса
12 - Возможность присоединения в бой
	12.1 - Тип присоединения
13 - Регенерирующий бот
*/

	function gcd($n, $m)
	{
		$n=abs($n); 
		$m=abs($m);
		if ($n==0 and $m==0)
			return 1; //avoid infinite recursion
		if ($n==$m and $n>=1)
			return $n;
		return $m < $n ? gcd($n - $m, $n) : gcd($n, $m - $n);
	}

	function lcm($n, $m)
	{
		return $m * ($n/gcd($n,$m));
	}

	function lcm_arr($items)
	{
		//Input: An Array of numbers
		//Output: The LCM of the numbers
		while(count($items) >= 2)
		{
			array_push($items, lcm(array_shift($items), array_shift($items)));
		}
		return reset($items);
	}

class Npc
{
	public $npc; // запись из game_npc
	public $templ; //запись из game_npc_template
	private $error;
	
	public function __destruct()
	{
	}
	
	public function __construct($id)
	{
		global $user_id;
		$this->error = 0;
		$this->npc = mysql_fetch_array(myquery("SELECT * FROM game_npc WHERE id=$id"));
		if (!isset($this->npc['npc_id']) OR $this->npc['npc_id']=='') 
		{
			$this->error = 1;	
			$theme = "Вызов класса NPC с пустым npc_id";
			$backtrace = debug_backtrace();
			$error_message = '<pre>'.print_r($backtrace,true).'</pre>';			
			mysql_query("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time) VALUES ('1', '$user_id', '".mysql_real_escape_string($theme)."', '".mysql_real_escape_string($error_message)."', '0','0',".time().")");
			
		}
		else
		{
			$this->templ = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=".$this->npc['npc_id'].""));
		}
	}

	public function drop_loot($user_id) // Дроп лута с бота при его убийстве
	{
		if ($this->error==1) return;
		if ($this->npc['dropable']==1)
		{
			$seldrop = myquery("SELECT * FROM game_npc_drop WHERE npc_id=".$this->templ['npc_id'].";");
			if ($seldrop!=false AND mysql_num_rows($seldrop))
			{
				$sum_chance = 0;
				$massiv = array(0);
				$m_last = 1;

				$mes = "";
				$lcm = array();
				$str = "";

				$r = 0;
				//увеличим шанс дропа за счет навыка "Охотник"
				if ($this->templ['npc_id']==npc_id_olen)
					$r = $r + 2 * getCraftLevel($user_id,8);

				while ($chance = mysql_fetch_array($seldrop))
					$lcm[] = $chance['random_max'];

				mysql_data_seek($seldrop, 0);
				$lcm = lcm_arr($lcm);

				while ($chance = mysql_fetch_array($seldrop))
				{
					$this_chance = ($chance['random'] + $r) * $lcm / gcd($lcm, $chance['random_max']);
					$massiv = array_merge($massiv, array_fill($m_last, $this_chance, $chance));
					$m_last += $this_chance;
				}

				if ($m_last < $lcm + 1)
					$massiv = array_merge($massiv, array_fill($m_last, $lcm + 1 - $m_last, array('items_id' => 0)));

				$drop = $massiv[mt_rand(1, $lcm)];
				if ($drop['items_id'] != 0)
				{
					$it_user_id = 0;
					$priznak = 2;
					$kol_predmetov = mt_rand($drop['mincount'],$drop['maxcount']);
					$map_name = 0;
					$map_xpos = 0;
					$map_ypos = 0;

					for ($cikl = 1; $cikl <= $kol_predmetov; $cikl++)
					{
						$add_result = array(0);
						if ($drop['kuda']==1)
						{
							$it_user_id = $user_id;
							$priznak=0;
							//проверим вес
							if($drop['drop_type']==1)
								$item = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=".$drop['items_id'].""));
							//если ресурс
							elseif ($drop['drop_type']==2)
								$item = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$drop['items_id'].""));
							list($CW,$CC) = mysql_fetch_array(myquery("SELECT CW,CC FROM view_active_users WHERE user_id=$it_user_id"));
							$prov = mysqlresult(myquery("SELECT COUNT(*) FROM game_wm WHERE user_id=$it_user_id AND type=1"),0,0);
							if (($CC-$CW)<$item['weight'] AND $prov==0)
							{
								//инвентарь заполнен. На землю его!
								$it_user_id=0;
								$priznak=2;
								list($map_name,$map_xpos,$map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id=$it_user_id"));
								$drop['kuda'] = 0;
							}
						}
						//если предмет
						if($drop['drop_type']==1)
						{
							if ($priznak==0)
							{
								$Item = new Item();
								$add_result = $Item->add_user($drop['items_id'],$it_user_id,0,0,1);
								// не получилось добавить один - не выйдет и остальные.
								if ($add_result[0] == 0)
									break;
							}
							else
							{
								if (!isset($item)) $item = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=".$drop['items_id'].""));
								myquery("INSERT INTO game_items (user_id,item_id,priznak,ref_id,item_uselife,item_uselife_max,item_cost,map_name,map_xpos,map_ypos) VALUES ('$it_user_id','".$drop['items_id']."','$priznak',0,'".$item['item_uselife']."','".$item['item_uselife_max']."','".$item['item_cost']."','$map_name','$map_xpos','$map_ypos')");
							}
						}
						//если ресурс
						elseif ($drop['drop_type']==2)
						{
							if ($drop['kuda']==1)
							{
								$is=myquery("SELECT * FROM craft_resource_user WHERE user_id=$it_user_id AND res_id=".$drop['items_id']."");
								if(mysql_num_rows($is)>0)
									myquery("UPDATE craft_resource_user SET col=col+1 WHERE user_id=$it_user_id AND res_id=".$drop['items_id']."");
								else
									myquery("INSERT INTO craft_resource_user (user_id,res_id,col) VALUES ($it_user_id,".$drop['items_id'].",1)");

								if ($this->templ['npc_id']==npc_id_olen)
								{
									myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, ".$drop['items_id'].", 0, 1, ".time().", $it_user_id, 'z')");
									if (checkCraftTrain($it_user_id,8))
										setCraftTimes($it_user_id, 8, 1, 1);
								}
							}
						/*
							elseif ($drop['kuda']==0)
							{
								myquery("INSERT INTO `craft_resource_market` (`user_id`, `town`, `col`, `price`, `res_id`, `opis`, `id`, `map_name`, `map_xpos`, `map_ypos`, `priznak`, `sell_time`) VALUES (0, 0, ".$kol_predmetov.", 0.00, ".$itemsid.", '', 0, '".$map."', '".$xposs."', '".$yposs."', 0, 0)");
							}
							*/
						}
						if ($it_user_id>0)
						{
							
							if($drop['drop_type']==1)
							{
								$mes = '<font color=\"#eeeeee\">После убийства монстра ты '.echo_sex('увидел','увидела').' оставшийся от него предмет: <'.$item['name'].'>.Ты быстро '.echo_sex('подобрал','подобрала').' выпавший предмет и '.echo_sex('положил','положила').' его в свой инвентарь!</font>';
							}
							elseif($drop['drop_type']==2)
							{
								myquery("UPDATE game_users SET CW=CW+".$item['weight']." WHERE user_id=$it_user_id");
								$mes = '<font color=\"#eeeeee\">После убийства монстра ты '.echo_sex('заметил','заметила').', как он выронил из рук: <'.$item['name'].'>. Не мешкая ты быстро '.echo_sex('подобрал','подобрала').' выпавший ресурс и '.echo_sex('положил','положила').' его в свой инвентарь!</font>';
							}
						   $map_user = mysql_fetch_array(myquery("SELECT * FROM game_users_map WHERE user_id=$user_id"));
						   $result = myquery("INSERT game_battles SET attacker_id=".$user_id.", target_id=0, map_name=".$map_user['map_name'].", map_xpos=".$map_user['map_xpos'].", map_ypos=".$map_user['map_ypos'].", contents='$mes', post_time=".time()."");
						}
					}
					if ($drop['kuda']==0)
							{
								$map_user = mysql_fetch_array(myquery("SELECT * FROM game_users_map WHERE user_id=$user_id"));
								$map = $map_user['map_name'];
								$yposs = $map_user['map_ypos'];
								$xposs = $map_user['map_xpos'];
								$itemsid = $drop['items_id'];
								myquery("INSERT INTO `craft_resource_market` (`user_id`, `town`, `col`, `price`, `res_id`, `opis`, `id`, `map_name`, `map_xpos`, `map_ypos`, `priznak`, `sell_time`) VALUES (0, 0, ".$kol_predmetov.", 0.00, ".$itemsid.", '', 0, '".$map."', '".$xposs."', '".$yposs."', 0, 0)");
							             

							}
					
				}
			}
		}
	}
    
	public function check_hunter($user_id) // Квест "Помощь Лесничему"
	{
		$npc_level=$this->templ['npc_level'];
		list($map)=mysql_fetch_array(myquery("select map_name from game_users_map where user_id=$user_id"));
		$check=myquery("Select * From game_users_hunter Where level=$npc_level and level>times and map=$map and user_id=$user_id");
		if (mysql_num_rows($check)>0)
		{
			myquery("Update game_users_hunter Set times=times+1 Where map=$map and user_id=$user_id");
		}
	}
	
	public function check_aggro($char)  // Автонападение агрессивного бота на игрока
	{
		if ($this->error==1) return;
		if ($this->npc['prizrak']==1)
		{
			if ($this->npc['for_user_id']!=0 AND $this->npc['for_user_id']!=$char['user_id']) return;
            if ($this->templ['npc_level']<($char['clevel']-3)) return;
			if ($this->templ['npc_level']>($char['clevel']+3)) return;
		}
		$check_level=myquery("SELECT t2.value FROM game_npc_set_option as t1
							  Join game_npc_set_option_value as t2 on t1.id=t2.id
							  WHERE t1.opt_id=7 and t1.npc_id=".$this->templ['npc_id']." 
							  Order By t2.number
							");
		if (mysql_num_rows($check_level)==2) 
		{
			list($min_level)=mysql_fetch_array($check_level);
			list($max_level)=mysql_fetch_array($check_level);
			if ($char['clevel']<$min_level or $char['clevel']>$max_level) return;
		}
		$level_for_aggressive = 9999;
		//Проверка на автонападение бота
		if (
		($this->templ['agressive']=='2' AND $char['clevel']<($this->templ['npc_level']+$level_for_aggressive) AND $char['clevel']>($this->templ['npc_level']-$level_for_aggressive)) 
		or 
		($this->templ['agressive']=='1' AND $char['clevel']>($this->templ['npc_level']+$this->templ['agressive_level']))
        )
		{
			attack_npc($char,$this->npc['id'],1);
		}
	}
	
	public function show_around()  // Отображение бота на карте
	{
		global $char;
		if ($this->error==1) return;
		if ($this->npc['prizrak']==1)
		{
			if ($this->npc['for_user_id']!=0 AND $this->npc['for_user_id']!=$char['user_id']) return;
            if ($this->templ['npc_level']<($char['clevel']-3)) return;
			if ($this->templ['npc_level']>($char['clevel']+3)) return;
		}
		$npc_string = '<img id="npc'.$this->npc['id'].'" src="http://'.IMG_DOMAIN.'/npc/' . $this->templ['npc_img'] . '.gif" border="0" alt="' . $this->templ['npc_name'] . '" onclick="location.href=\'act.php?func=npc&option=npc&id='.$this->npc['id'].'\'"><br><center><font size="1">' . $this->templ['npc_name'] . '</font></center>';
		echo '<td valign="top"><table cellpadding="0" cellspacing="2" border="0"><tr><td>';
		?>
		<a onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b><?
		echo '<center><font color=#0000FF>'.$this->templ['npc_name'].'</font>';
		?></b></font>','<?
		echo '<font color=000000>';
		echo 'Жизнь: '.$this->npc["HP"].'/'.$this->templ["npc_max_hp"].'<br>';
		echo 'Мана: '.$this->npc["MP"].'/'.$this->templ["npc_max_mp"].'<br>';
		echo 'Уровень: '.$this->templ["npc_level"].'<br>';
		echo 'Сила: '.$this->templ["npc_str"].'&plusmn;'.$this->templ["npc_str_deviation"].'<br>';
		echo 'Ловкость: '.$this->templ["npc_pie"].'&plusmn;'.$this->templ["npc_pie_deviation"].'<br>';
		echo 'Защита: '.$this->templ["npc_vit"].'&plusmn;'.$this->templ["npc_vit_deviation"].'<br>';
		echo 'Выносливость: '.$this->templ["npc_dex"].'&plusmn;'.$this->templ["npc_dex_deviation"].'<br>';
		echo 'Мудрость: '.$this->templ["npc_spd"].'&plusmn;'.$this->templ["npc_spd_deviation"].'<br>';
		echo 'Интеллект: '.$this->templ["npc_ntl"].'&plusmn;'.$this->templ["npc_ntl_deviation"].'<br>';
		echo 'Опыт за победу: '.$this->npc["EXP"].'<br>';
		echo 'Монеты за победу: '.$this->templ["npc_gold"].'<br>';
		echo 'Время воскрешения: '.$this->templ["respawn"].' секунд<br>';
		echo '</font>';
		?>',0,1,event)" onmouseout="showhint('','',0,0,event)">
		<?
			echo $npc_string;
		?>
		</a>
		<?
		echo '<td valign="top">';
		
		//модификация из движка квестов
		if($this->npc['npc_quest_engine_id']==0)
		{
			if($this->templ['agressive']>='0')
			{
				echo '<a href="act.php?func=npc&option=npc&id='.$this->npc['id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attacknpc.gif" width="20" height="20" border="0" alt="Атаковать" title="Атаковать"></a>';
			}
			elseif($this->templ['agressive'] == '-1')
			{
				echo '<a href="/quest/quests_engine_chek.php?talk&npc_id='.$this->npc['id'].'"><img src="http://'.IMG_DOMAIN.'/nav/babble.gif" width="20" height="20" border="0" alt="Поговорить" title="Поговорить"></a>';
			}
		}
		else
		{
			//сделаем так, чтобы на квестового бота не могли нападать другие игроки (до истечения срока задания)
			list($fin_time)=mysql_fetch_array(myquery("SELECT quest_finish_time FROM quest_engine_users WHERE quest_type=1 AND par1_value='".$this->npc['id']."'"));
			if($this->npc['npc_quest_engine_id']!=$user_id AND $fin_time>time()) 
			{
				$reas='Приглядевшись&nbsp;к&nbsp;монстру,&nbsp;вы&nbsp;почувствовали,&nbsp;что&nbsp;лучше&nbsp;его&nbsp;cейчас&nbsp;не&nbsp;трогать';
				echo '<img src="http://'.IMG_DOMAIN.'/nav/action_notattack.gif" width="20" height="20" border="0" alt='.$reas.' title='.$reas.' border=0>';
			}
			else 
			{
				if ($fin_time<=time())
				{
					if($this->npc['EXP']==0)
					{
						$exp=ceil($this->templ["npc_max_hp"]*0.5);
						$gold=round(max(1,$this->templ["npc_max_hp"]/150),1);
						$exp_up=myquery("UPDATE game_npc SET EXP=".$exp.", GP=".$gold." WHERE npc_id='".$this->npc['id']."'");
					}
				} 
				echo '<a href="act.php?func=npc&option=npc&id='.$this->npc['id'].'"><img src="http://'.IMG_DOMAIN.'/nav/action_attacknpc.gif" width="20" height="20" border="0" alt="Атаковать" title="Атаковать"></a>';
			}
		}
		
		echo '<br><a href="act.php?func=main&npc_info='.$this->npc['id'].'"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" alt="Посмотреть информацию" title="Посмотреть информацию" border=0></a>
		</td></tr></table></td>';
	}
	
	public function can_attack($char)
	{
		if ($this->error==1) return;
		
		if (($this->npc['time_kill']+$this->templ['respawn'])<time() AND $this->templ['agressive']>='0' AND $this->npc['map_name']==$char['map_name'] AND $this->npc['xpos']==$char['map_xpos'] AND $this->npc['ypos']==$char['map_ypos'])
		{
			return true;
		}
		return false;   
	}
	
	public function npc_for_level($char) //Бот рассчитан на определённый уровень игрока
	{
		$check_level=myquery("SELECT t2.value FROM game_npc_set_option as t1
							  Join game_npc_set_option_value as t2 on t1.id=t2.id
							  WHERE t1.opt_id=7 and t1.npc_id=".$this->templ['npc_id']." 
							  Order By t2.number
							");
		if (mysql_num_rows($check_level)==2) 
		{
			list($min_level)=mysql_fetch_array($check_level);
			list($max_level)=mysql_fetch_array($check_level);
			if ($char['clevel']<$min_level or $char['clevel']>$max_level) 
			{
				return false;   
			}
		}
		return true;
	}
	
	public function create_output()
	{
		if ($this->error==1) return;
		$output_string=' 
		<table cellpadding="0" cellspacing="5" border="0">
		<tr><td colspan=2 align="center"><b><font face=verdana size=2 color=ff0000>'.$this->templ["npc_name"].'</font></b></td></tr>

		<tr><td><font color=#80FFFF>Жизнь</font></td><td><div align="left">'.$this->npc["HP"].'/'.$this->templ["npc_max_hp"].' </div></td></tr>
		<tr><td><font color=#80FFFF>Мана</font></td><td><div align="left">'.$this->npc["MP"].'/'.$this->templ["npc_max_mp"].' </div></td></tr>
		<tr><td><font color=#80FFFF>Уровень</font></td><td><div align="left">'.$this->templ["npc_level"].' </div></td></tr>
		<tr><td><font color=#80FFFF>Сила</font></td><td><div align="left">'.$this->templ["npc_str"].'&plusmn;'.$this->templ["npc_str_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>Ловкость</font></td><td><div align="left">'.$this->templ["npc_pie"].'&plusmn;'.$this->templ["npc_pie_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>Защита</font></td><td><div align="left">'.$this->templ["npc_vit"].'&plusmn;'.$this->templ["npc_vit_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>Выносливость</font></td><td><div align="left">'.$this->templ["npc_dex"].'&plusmn;'.$this->templ["npc_dex_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>Мудрость</font></td><td><div align="left">'.$this->templ["npc_spd"].'&plusmn;'.$this->templ["npc_spd_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>Интеллект</font></td><td><div align="left">'.$this->templ["npc_ntl"].'&plusmn;'.$this->templ["npc_ntl_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>Время воскрешения</font></td><td><div align="left">'.$this->templ["respawn"].' секунд</div></td></tr>
		<tr><td><font color=#80FFFF>Опыт за победу</font></td><td><div align="left">'.$this->npc["EXP"].'</div></td></tr>';
		if  ($this->templ['agressive']=='-1') $output_string.='<tr><td><font color=#80FFFF>Тип монстра</font></td><td><div align="left">НЕ ИГРОВОЙ (на бота напасть нельзя)</div></td></tr>';
		if  ($this->templ['agressive']=='0') $output_string.='<tr><td><font color=#80FFFF>Тип монстра</font></td><td><div align="left">СПОКОЙНЫЙ (не нападает на игроков)</div></td></tr>';
		if  ($this->templ['agressive']=='1') $output_string.='<tr><td><font color=#80FFFF>Тип монстра</font></td><td><div align="left">НЕРВНЫЙ (нападает на игроков >'.($this->templ['npc_level']+$this->templ['agressive_level']).' уровня)</div></td></tr>';
		if  ($this->templ['agressive']=='2') $output_string.='<tr><td><font color=#80FFFF>Тип монстра</font></td><td><div align="left">ЗЛОБНЫЙ (нападает на всех игроков без разбора)</div></td></tr>';
		if ($this->templ['npc_gold']>0) $output_string.='<tr><td><font color=#80FFFF>Монеты за победу</font></td><td><div align="left">'.$this->templ["npc_gold"].'</div></td></tr>';
		 $output_string.='<tr><td><font color=#80FFFF>Описание NPC</font></td><td><div align="justify">'.$this->templ["npc_opis"].'</div></td></tr>';
		 $output_string.='</table>';
		 return $output_string;
	}
	
	public function on_dead() // Действия при убийстве бота в бою
	{
		if ($this->error==1) return;
		if ($this->npc['stay']==0 AND $this->npc['prizrak']!=1 AND $this->templ['to_delete']!=1)
		{
			$try = 0;
            $npc_map = $this->npc['map_name'];
			while (1==1)
			{
				list($maze) = mysql_fetch_array(myquery("SELECT maze FROM game_maps WHERE id=$npc_map"));
				if ($maze==1)
				{
					$battle_map_query = myquery("SELECT xpos,ypos FROM game_maze where map_name=$npc_map ORDER BY xpos DESC, ypos DESC LIMIT 1");
				}
				else
				{
                    if ($this->npc['npc_id']==npc_id_mrachn_hranitel)
                    {
                        //МРАЧНЫЙ ХРАНИТЕЛЬ спавнится или на 1 или на 2 уровне подземки
                        $npc_map = mt_rand(691,692);
                        $battle_map_query = myquery("SELECT xpos,ypos FROM game_map where name=$npc_map ORDER BY xpos DESC, ypos DESC LIMIT 1");
                    }
                    else
                    {
					    $battle_map_query = myquery("SELECT xpos,ypos FROM game_map where name=$npc_map ORDER BY xpos DESC, ypos DESC LIMIT 1");
                    }
				}
				$battle_map_result = mysql_fetch_array($battle_map_query, MYSQL_ASSOC);

				$xrandmap = mt_rand(0, $battle_map_result['xpos']);
				$yrandmap = mt_rand(0, $battle_map_result['ypos']);
				$che = myquery("SELECT COUNT(*) FROM game_npc WHERE xpos=$xrandmap AND ypos=$yrandmap AND map_name=$npc_map");
				if (mysql_result($che,0,0)==0 or $try==10)
				{
					$xrandmapview = mt_rand(0,4)-2;
					$yrandmapview = mt_rand(0,4)-2;
					break;
				}
				else
				{
					$try++;
				}
			}
            $time_kill = time();
            if ($npc_map==id_map_tuman)
            {
                $time_kill = time()+365*24*60*60;
            }
			myquery("update game_npc set LOSE=LOSE+1,kill_last_hour=kill_last_hour+1,HP=".$this->templ['npc_max_hp'].",MP=".$this->templ['npc_max_mp'].", time_kill=".$time_kill.",xpos='".$xrandmap."',ypos='".$yrandmap."',xpos_view='".$xrandmapview."',ypos_view='".$yrandmapview."',map_name=$npc_map where id=".$this->npc['id']."");
            if ($this->templ['npc_id']==npc_id_mrachn_hranitel)
            {
                myquery("UPDATE game_npc_template SET respawn=".mt_rand(24*60*60,32*60*60)." WHERE npc_id=".npc_id_mrachn_hranitel."");
            }
		}
		else
		{
			if ($this->npc['stay']>=2 and $this->npc['stay']<=4)
			{
				myquery("Delete From game_npc Where id=".$this->npc['id']."");
				if ($this->npc['stay']==3) myquery("Delete From game_npc_template Where npc_id=".$this->npc['npc_id']."");
			}
			elseif ($this->npc['prizrak']!=1 AND $this->templ['to_delete']!=1)
			{
				//убиваем бота, но оставляем на месте, т.к. бот не перемещается
				myquery("UPDATE game_npc SET LOSE=LOSE+1,kill_last_hour=kill_last_hour+1,HP=".$this->templ['npc_max_hp'].",MP=".$this->templ['npc_max_mp'].", time_kill=".time()." WHERE id=".$this->npc['id']."");
			}
			else
			{
				//это бот-призрак, он удаляется после убийства
				myquery("delete from game_npc where id=".$this->npc['id']."");
				if ($this->templ['to_delete']==1)
				{
					$kol = mysql_result(myquery("SELECT COUNT(*) FROM game_npc WHERE npc_id=".$this->npc['npc_id'].""),0,0);
					if ($kol==0)
					{
						//удаляем шаблон бота
						myquery("DELETE FROM game_npc_template WHERE npc_id=".$this->npc['npc_id']."");
					}
				}
			} 
		}
	}
}
?>
