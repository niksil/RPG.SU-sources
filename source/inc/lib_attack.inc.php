<?php
//Типы боев:
//1 - обычный бой
//2 - дуэль
//3 - общий бой
//4 - клановый бой
//5 - все против всех
//6 - бой склонностей
//7 - бой рас

require_once('combat/combat_log.php');
function attack_npc($char,$npc_id,$from_aggro_npc=0,$shadow=0)
{
	global $pol;
	
	if ($shadow==0 AND $from_aggro_npc==0)
	{
		$est_plash_monaha_char = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=5 AND item_id=".plash_monaha.""),0,0);
		if ($est_plash_monaha_char==1) return 0;
	}
	
	$user_id = $char['user_id'];
	//NPC всегда атакует "владелец" скрипта
	if (close_combat==1 AND $user_id!=1 AND $from_aggro_npc==0)
		return 0;
	
	$current_time = time();
	$online_range = $current_time - 300;

	myquery("DELETE FROM combat_users WHERE user_id=$user_id");
	myquery("DELETE FROM combat_users_exp WHERE user_id=$user_id");

	$boy_exist_flag=0;
	$uid=0;
	$res = myquery("SELECT combat_id FROM combat_users WHERE npc=1 AND user_id=$npc_id LIMIT 1");
	if(mysql_num_rows($res)>0)
	{
		$res_arr=mysql_fetch_array($res);
		include_once(getenv("DOCUMENT_ROOT").'/combat/class_combat.php');
		if (check_boy($res_arr['combat_id'])!=1)
		{
			// Бот уже с кем-то бьётся
			// Запоминаем uid
			$uid=$res_arr['combat_id'];
			$boy_exist_flag=1;
		}
	}
	//начинаем бой с ботом
	list($npc_quest_id) = mysql_fetch_array(myquery("SELECT npc_quest_id FROM game_npc WHERE id=$npc_id"));
	if ($npc_quest_id==1)
	{
		$user_quest = mysql_result(myquery("SELECT COUNT(*) FROM game_quest_users WHERE user_id=$user_id AND quest_id=1"),0,0);
		if ($user_quest==0)
		{
			echo '<br>Это квестовый бот, ты не можешь его атаковать<br>';
			{if (function_exists("save_debug")) save_debug(); return 0;}
		}
	}

	//движок квестов. проверим, не является ли этот бот созданным движком квестов
	$attack_user_id = $user_id;
	include(getenv("DOCUMENT_ROOT")."/quest/quest_engine_types/quests_engine_start_combat.php");

	$text='';
	if ($char['HP']==0)
	{
		if (function_exists("save_debug")) save_debug();
		return 0;
	}
	
	$join = 1;
    $current_hod = 0;
	$combat_type = 1;
	if ($shadow!=0) $combat_type=10;
	$npc = mysql_fetch_array(myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.id=$npc_id AND game_npc.npc_id=game_npc_template.npc_id"));
	list($maze,$map) = mysql_fetch_array(myquery("SELECT maze,name FROM game_maps WHERE id=".$char['map_name'].""));
	if ($maze==1 AND $npc['prizrak']==1)
	{
		//update_maze_npc_for_user($user_id,$npc_id);
		$npc = mysql_fetch_array(myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.id=$npc_id AND game_npc.npc_id=game_npc_template.npc_id"));
	}
	if($uid==0)
	{
		$join = 0;
		$ins = myquery("insert into combat (hod,combat_type,start_time,time_last_hod,map_name,map_xpos,map_ypos) values (1,$combat_type,".time().",".time().",".$char['map_name'].",".$char['map_xpos'].",".$char['map_ypos'].")");
		$uid = mysql_insert_id();
		
		$side=$npc_id;
		
		$npc_ms_kulak       = 0;
		$npc_ms_weapon      = 0;
		$npc_ms_art         = 0;
		$npc_ms_parir       = 0;
		$npc_ms_luk         = 0;
		$npc_ms_sword       = 0;
		$npc_ms_axe         = 0;
		$npc_ms_spear       = 0;
		$npc_ms_throw       = 0;

		$check_option=myquery("Select * From game_npc_set_option Where opt_id>=8 and opt_id<=11 and npc_id=".$npc['npc_id']."");
		while ($npc_opt=mysql_fetch_array($check_option))
		{
			switch ($npc_opt['opt_id'])
			{
				case 8:
						$npc_ms_kulak=$char['MS_KULAK'];
						$npc_ms_weapon=$char['MS_WEAPON'];
						$npc_ms_art=$char['MS_ART'];
						$npc_ms_parir=$char['MS_PARIR'];
						$npc_ms_luk=$char['MS_LUK'];
						$npc_ms_sword =$char['MS_SWORD'];
						$npc_ms_axe=$char['MS_AXE'];
						$npc_ms_spear=$char['MS_SPEAR'];
						$npc_ms_throw=$char['MS_THROW'];
						break;
						
				case 9:
						list($koef)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=".$npc_opt['id'].""));
						$npc['HP'] = round($char['HP_MAX']*$koef/100);
						$npc['npc_max_hp'] = round($char['HP_MAX']*$koef/100);
						break;
						
				case 10:
						list($koef)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=".$npc_opt['id'].""));
						$npc['MP'] = round($char['MP_MAX']*$koef/100);
						$npc['npc_max_mp'] = round($char['MP_MAX']*$koef/100);
						break;
						
				case 11:
						list($type)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=".$npc_opt['id']." and number=1"));
						list($koef1)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=".$npc_opt['id']." and number=2"));
						list($koef2)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=".$npc_opt['id']." and number=3"));
						$koef_har=$koef1/100;
						$koef_dev=$koef2/100;
						switch ($type)
						{
							case 1:
								if ($npc['npc_id']!=npc_id_olen)
								{
									$koef_lev=0.5;
								}
								else
								{
									$koef_lev=0;
								}
								
								$npc['npc_pie'] = (mt_rand($char['SPD']*(1-$koef_dev),$char['SPD']*(1+$koef_dev))+$koef_lev*$char['clevel']*3)*$koef_har;;
								$npc['npc_vit'] = (mt_rand($char['VIT']*(1-$koef_dev),$char['VIT']*(1+$koef_dev))+$koef_lev*$char['clevel']*1)*$koef_har;
								$npc['npc_spd'] = (mt_rand($char['PIE']*(1-$koef_dev),$char['PIE']*(1+$koef_dev))+$koef_lev*$char['clevel']*2)*$koef_har;
								if ($char['NTL']>$char['STR'])
								{
									$npc['npc_str'] = (mt_rand($char['NTL']*(1-$koef_dev),$char['NTL']*(1+$koef_dev))+$koef_lev*$char['clevel']*3)*$koef_har;
									$npc['npc_dex'] = mt_rand($char['NTL']*(1-$koef_dev),$char['NTL']*(1+$koef_dev))*$koef_har;
									$npc['npc_ntl'] = (mt_rand($char['NTL']*(1-$koef_dev)/10,$char['NTL']*(1+$koef_dev))/10+$koef_lev*$char['clevel']*3)*$koef_har;
								}
								else
								{
									$npc['npc_str'] = (mt_rand($char['STR']*(1-$koef_dev)/10,$char['STR']*(1+$koef_dev))/10+$koef_lev*$char['clevel']*3)*$koef_har;
									$npc['npc_dex'] = mt_rand($char['STR']*(1-$koef_dev),$char['STR']*(1+$koef_dev))*$koef_har;
									$npc['npc_ntl'] = (mt_rand($char['STR']*(1-$koef_dev),$char['STR']*(1+$koef_dev))+$koef_lev*$char['clevel']*3)*$koef_har;
								}
								$npc_hp0 = $npc['npc_dex']*10+10+1.5*$char['clevel'];
								$npc_mp0 = $npc['npc_ntl']*10+10+1.5*$char['clevel'];
								//TODO сделать шаблон бота с расой "Минотавр Лабиринта"
								if ($npc['npc_race']=='Минотавр Лабиринта')
								{
									$npc['HP'] = $npc_hp0*10;
									$npc['MP'] = $npc_mp0*10;
									$npc['npc_max_hp'] = $npc_hp0*10;
									$npc['npc_max_mp'] = $npc_mp0*10;
									$npc['npc_str'] = $npc['npc_str']*3;
									$npc['npc_dex'] = $npc['npc_dex']*3;
									$npc['npc_spd'] = $npc['npc_spd']*3;
									$npc['npc_vit'] = $npc['npc_vit']*3;
									$npc['npc_ntl'] = $npc['npc_ntl']*3;
									$npc['npc_pie'] = $npc['npc_pie']*3;
								}
								if ($npc['HP']==0)
								{
									$npc['HP'] = 50;
									$npc['MP'] = 50;
									$npc['npc_max_hp'] = 50;
									$npc['npc_max_mp'] = 50;
								}
								$npc['npc_str_deviation'] = 0;
								$npc['npc_dex_deviation'] = 0;
								$npc['npc_spd_deviation'] = 0;
								$npc['npc_vit_deviation'] = 0;
								$npc['npc_ntl_deviation'] = 0;
								$npc['npc_pie_deviation'] = 0;
							break;
							
							case 2:
								$npc['npc_str']                 =$char['NTL']*$koef_har;
								$npc['npc_str_deviation']       =$char['NTL']*$koef_dev;
								$npc['npc_dex']                 =$char['DEX']*$koef_har;
								$npc['npc_dex_deviation']       =$char['DEX']*$koef_dev;
								$npc['npc_spd']                 =$char['SPD']*$koef_har;
								$npc['npc_spd_deviation']       =$char['SPD']*$koef_dev;
								$npc['npc_vit']                 =$char['VIT']*$koef_har;
								$npc['npc_vit_deviation']       =$char['VIT']*$koef_dev;
								$npc['npc_ntl']                 =$char['STR']*$koef_har;
								$npc['npc_ntl_deviation']       =$char['STR']*$koef_dev;
								$npc['npc_pie']                 =$char['PIE']*$koef_har;
								$npc['npc_pie_deviation']       =$char['PIE']*$koef_dev;
							break;
						}
						break;
			}
			
		}
				
		if ($npc['HP']==0 OR $npc['HP']>$npc['npc_max_hp']) $npc['HP'] = $npc['npc_max_hp'];
		if ($npc['MP']==0 OR $npc['MP']>$npc['npc_max_mp']) $npc['MP'] = $npc['npc_max_mp'];
		myquery("DELETE FROM combat_users WHERE npc=1 AND user_id=$npc_id");
		$ins = myquery("insert into combat_users (
		combat_id,user_id,npc,time_last_active,name,clevel,side,
		HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,
		MS_KULAK,MS_WEAPON,MS_ART,MS_PARIR,MS_LUK,MS_SWORD,MS_AXE,MS_SPEAR,MS_THROW,
		HP_MAX,MP_MAX,STM_MAX,
		lucky,pol,avatar,race,HP_start
		)VALUES(
		$uid,$npc_id,1,".time().",'".$npc['npc_name']."',".$npc['npc_level'].",$side,
		".$npc['HP'].",".$npc['MP'].",10000,
		".mt_rand($npc['npc_str']-$npc['npc_str_deviation'],$npc['npc_str']+$npc['npc_str_deviation']).",
		".mt_rand($npc['npc_dex']-$npc['npc_dex_deviation'],$npc['npc_dex']+$npc['npc_dex_deviation']).",
		".mt_rand($npc['npc_spd']-$npc['npc_spd_deviation'],$npc['npc_spd']+$npc['npc_spd_deviation']).",
		".mt_rand($npc['npc_vit']-$npc['npc_vit_deviation'],$npc['npc_vit']+$npc['npc_vit_deviation']).",
		".mt_rand($npc['npc_ntl']-$npc['npc_ntl_deviation'],$npc['npc_ntl']+$npc['npc_ntl_deviation']).",
		".mt_rand($npc['npc_pie']-$npc['npc_pie_deviation'],$npc['npc_pie']+$npc['npc_pie_deviation']).",
		".$npc_ms_kulak.",".$npc_ms_weapon.",".$npc_ms_art.",".$npc_ms_parir.",".$npc_ms_luk.",".$npc_ms_sword.",".$npc_ms_axe.",".$npc_ms_spear.",".$npc_ms_throw.",
		".$npc['npc_max_hp'].",".$npc['npc_max_mp'].",10000,
		'".$char['lucky']."','','".$npc['npc_img']."','".$npc['npc_race']."',".$npc['npc_max_hp'].")"); 
		combat_setFunc($npc_id,6,$uid);
		
		$check_call=myquery("SELECT t1.npc_id, t2.value as kogo, t3.value as kol
							FROM game_npc_set_option AS t1
							JOIN game_npc_set_option_value AS t2 ON t1.id = t2.id AND t2.number =1
							JOIN game_npc_set_option_value AS t3 ON t1.id = t3.id AND t3.number =2
							WHERE t1.opt_id =6 and t1.npc_id=".$npc['npc_id']."");
		if (mysql_num_rows($check_call)>0)
		{
			$che = myquery("SELECT id FROM game_combats_log_text WHERE (name='".$npc['npc_name']."' AND mode=".$npc['id'].")");
			if (mysql_num_rows($che)==0)
			{
				myquery("Insert Into game_combats_log_text (name, mode) VALUES ('".$npc['npc_name']."', ".$npc['id'].")");
				$text_id = mysql_insert_id();
			}
			else
			{
				list($text_id) = mysql_fetch_array($che);
			}
			myquery("Insert Into game_combats_log_data (boy, user_id, hod, action, kto, text_id) Values ($uid, ".$npc['id'].", 0, 81, ".$npc['id'].", $text_id)");
			while ($call=mysql_fetch_array($check_call))
			{
				npc_call($npc['id'],$call['kogo'],$call['kol']);
			}
		}
	}
    else
    {
        $current_hod = mysqlresult(myquery("SELECT hod FROM combat WHERE combat_id=$uid"),0,0);
		$check_option2=myquery("Select id From game_npc_set_option Where opt_id=12 and npc_id=".$npc['npc_id']."");
		if (mysql_num_rows($check_option2)>0)
		{
			list($npc_opt)=mysql_fetch_array($check_option2);
			list($type_npc)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$npc_opt"));
			switch ($type_npc)
			{
				case 1:
					$no_boy=1;
				break;
				
				case 2:
					$check_boy=myquery("Select * From game_combats_log_data Where user_id=$user_id and boy=$uid");
					if (mysql_num_rows($check_boy)>0) $no_boy=1;
				break;
			}
		}
    }

	
	if (!isset($no_boy) or $no_boy<>1)
	{
		$side=1;
		$ins = myquery("insert into combat_users (
			combat_id,user_id,npc,time_last_active,name,clevel,side,
			HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,
			MS_KULAK,MS_WEAPON,MS_ART,MS_PARIR,MS_LUK,MS_SWORD,MS_AXE,MS_SPEAR,MS_THROW,
			HP_MAX,MP_MAX,STM_MAX,
			lucky,pol,avatar,sklon,race,clan_id,`join`,HP_start,hod_start) values (
			$uid,$user_id,0,".time().",'".$char['name']."',".$char['clevel'].",$side,
			".$char['HP'].",".$char['MP'].",".$char['STM'].",".$char['STR'].",".$char['DEX'].",".$char['SPD'].",".$char['VIT'].",".$char['NTL'].",".$char['PIE'].",
			".$char['MS_KULAK'].",".$char['MS_WEAPON'].",".$char['MS_ART'].",".$char['MS_PARIR'].",".$char['MS_LUK'].",'".$char['MS_SWORD']."','".$char['MS_AXE']."','".$char['MS_SPEAR']."','".$char['MS_THROW']."',
			".$char['HP_MAX'].",".$char['MP_MAX'].",".$char['STM_MAX'].",
			'".$char['lucky']."','$pol','".$char['avatar']."','".$char['sklon']."','".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0)."',".$char['clan_id'].",$join,".$char['HP_MAX'].",'".$current_hod."')");
		
		if ($join==0)
		{
			combat_setFunc($user_id,5,$uid);	
		}
		else
		{
			combat_setFunc($user_id,6,$uid);    
		}
		if ($shadow==0)
		{
			set_delay_reason_id($user_id,12);
			save_stat($user_id,'','',8,'','',$npc_id,'','','','','');
		}
		else
		{
			set_delay_reason_id($user_id,45);
		}

		// Принудительно забросим игрока в бой. Баг возникает из-за того, что бой создан, но браузер не всегда "доходит" до combat.php
		// А вот так - будет всегда.
		// Только нужно убедиться, что на этой стадии уже точно можно игроку дать зайти в бой.
		ForceFunc($user_id,1);
		setLocation("http://".DOMAIN."/combat.php");
	}
	else
	{
		echo '<b><center>Дождитесь, пока закончится текущий бой!</center></b>';
	}
}

function npc_call ($npc_id, $kogo, $kol)
{
	while ($kol>0)
	{
		list($uid)=mysql_fetch_array(myquery("Select combat_id From combat_users Where npc=1 and user_id=$npc_id"));
		$templ = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=$kogo"));
		myquery("INSERT INTO game_npc SET stay=2,npc_id=$kogo,map_name=672,xpos=0,ypos=0,view=0,dropable=1,HP=".$templ['npc_max_hp'].",MP=".$templ['npc_max_mp'].", EXP=".$templ['npc_exp_max']."");
		$new=mysql_insert_id();
		$npc = mysql_fetch_array(myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.id=$new AND game_npc.npc_id=game_npc_template.npc_id"));	
		$npc_ms_kulak       = 0;
		$npc_ms_weapon      = 0;
		$npc_ms_art         = 0;
		$npc_ms_parir       = 0;
		$npc_ms_luk         = 0;
		$npc_ms_sword       = 0;
		$npc_ms_axe         = 0;
		$npc_ms_spear       = 0;
		$npc_ms_throw       = 0;
		$ins = myquery("insert into combat_users (
			combat_id,user_id,npc,time_last_active,name,clevel,side,
			HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,
			MS_KULAK,MS_WEAPON,MS_ART,MS_PARIR,MS_LUK,MS_SWORD,MS_AXE,MS_SPEAR,MS_THROW,
			HP_MAX,MP_MAX,STM_MAX,
			lucky,pol,avatar,race,HP_start,`join`,hod_start
			)VALUES(
			$uid,$new,1,".time().",'".$npc['npc_name']."',".$npc['npc_level'].",$npc_id,
			".$npc['HP'].",".$npc['MP'].",10000,
			".mt_rand($npc['npc_str']-$npc['npc_str_deviation'],$npc['npc_str']+$npc['npc_str_deviation']).",
			".mt_rand($npc['npc_dex']-$npc['npc_dex_deviation'],$npc['npc_dex']+$npc['npc_dex_deviation']).",
			".mt_rand($npc['npc_spd']-$npc['npc_spd_deviation'],$npc['npc_spd']+$npc['npc_spd_deviation']).",
			".mt_rand($npc['npc_vit']-$npc['npc_vit_deviation'],$npc['npc_vit']+$npc['npc_vit_deviation']).",
			".mt_rand($npc['npc_ntl']-$npc['npc_ntl_deviation'],$npc['npc_ntl']+$npc['npc_ntl_deviation']).",
			".mt_rand($npc['npc_pie']-$npc['npc_pie_deviation'],$npc['npc_pie']+$npc['npc_pie_deviation']).",
			".$npc_ms_kulak.",".$npc_ms_weapon.",".$npc_ms_art.",".$npc_ms_parir.",".$npc_ms_luk.",".$npc_ms_sword.",".$npc_ms_axe.",".$npc_ms_spear.",".$npc_ms_throw.",
			".$npc['npc_max_hp'].",".$npc['npc_max_mp'].",10000,
			5,'','".$npc['npc_img']."','".$npc['npc_race']."',".$npc['npc_max_hp'].", 1,1)"); 
			combat_setFunc($new,6,$uid);
			$kol--;
	}
}

function check_attack_type($char,$player,$type,$check_level=1)
{
	$est_kamikadze = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=6 AND item_id=".povyazka_kamikadze.""),0,0);
	if ($check_level==1)
	{
		if ($type==3 OR $type==5)
		{
			//Для боев "все против всех", "общий бой" можно нападать на игрока +3 -2 уровня
			$max_level = 3;
			$min_level = 2;
		}
		else
		{
			$max_level = 3;
			$add = floor(($char['clevel']-5)/5);
			$max_level+=$add;
			$min_level = 2;
		}
		if ($est_kamikadze && $char['clevel'] <= $player['clevel'])
		{
		}
		else
		{
			if ($player['clevel']-$max_level <= $char['clevel'] and
				$char['clevel'] <= ($player['clevel']+$min_level) and
				$player['clevel']!=0)
			{
			}
			else
			{
				return 'Ты не подходишь по уровню противника!';
			} 
		}
	}
	switch ($type)
	{
		case 1:
		{
			if ($char['clan_id']==0 OR $player['clan_id']==0)
			{
				return "Этот бой доступен только для клановых игроков";
			}
		}
		break;
		case 2:
		{
		}
		break;
		case 3:
		{
		}
		break;
		case 4:
		{
			if ($char['clan_id']==0 OR $player['clan_id']==0)
			{
				return "Этот бой доступен только для клановых игроков";
			}
		}
		break;
		case 5:
		{
		}
		break;
		case 6:
		{
			if ($char['sklon']==0 OR $player['sklon']==0)
			{
				return "Этот бой доступен только для игроков со склонностью";
			}
			if ($char['sklon']==$player['sklon'])
			{
				return "Этим боем можно нападать только на игроков другой склонности";
			}
		}
		break;
		case 7:
		{
			if ($char['race']==$player['race'])
			{
				return "Этим боем можно нападать только на игроков другой расы";
			}
		}
		break;
	}
	return 1;
}

function check_attack($char,$player,$type=0,$map=0)
{
	global $user_id;
	$check='Бой невозможен';
	
	if (close_combat==1 AND $user_id!=1) return 'Все бои закрыты';
	/*if ($map==0) */$map=mysql_fetch_array(myquery("select * from game_maps where id='".$char['map_name']."'"));
	if (clans_war==0 OR $player['user_id']==1 OR $map['dolina']==0)
	{
		$est_plash_monaha_char = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=5 AND item_id=".plash_monaha.""),0,0);
		if ($est_plash_monaha_char==1) return 'Ты отказываешься от боя';
		$est_plash_monaha_player = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$player['user_id']." AND priznak=0 AND used=5 AND item_id=".plash_monaha.""),0,0);
		if ($est_plash_monaha_player==1) return 'Противник отказывается от боя';
	}
	if (!isset($map['maps_name'])) $map['maps_name'] = $map['name'];
	
	if (
		$map['boy_type1']==0 AND 
		$map['boy_type2']==0 AND 
		$map['boy_type3']==0 AND 
		$map['boy_type4']==0 AND 
		$map['boy_type5']==0 AND 
		$map['boy_type6']==0 AND 
		$map['boy_type7']==0)
	{
		return 'Запрещены все бои на карте';
	}

	if ($type>=1 AND $type<=7)
	{
		if ($type==1 AND $map['boy_type1']!=1) return "Данный тип боя на этой карте запрещен";
		elseif ($type==2 AND $map['boy_type2']!=1) return "Данный тип боя на этой карте запрещен";
		elseif ($type==3 AND $map['boy_type3']!=1) return "Данный тип боя на этой карте запрещен";
		elseif ($type==4 AND $map['boy_type4']!=1) return "Данный тип боя на этой карте запрещен";
		elseif ($type==5 AND $map['boy_type5']!=1) return "Данный тип боя на этой карте запрещен";
		elseif ($type==6 AND $map['boy_type6']!=1) return "Данный тип боя на этой карте запрещен";
		elseif ($type==7 AND $map['boy_type7']!=1) return "Данный тип боя на этой карте запрещен";
		//проверка в зависимости от типа боя
		$str_check_type = check_attack_type($char,$player,$type,0);  
		if ($str_check_type!=1)
		{
			return $str_check_type;
		}
		elseif ($map['dolina']==0 AND $map['maps_name']!='Гильдия новичков')
		{
			$str_check_type = check_attack_type($char,$player,$type); 
			if ($str_check_type!=1)
			{
				return $str_check_type;
			}
		}
	}
	
	list($host1,$time1) = mysql_fetch_array(myquery("SELECT host,last_active FROM game_users_active WHERE user_id='".$char['user_id']."'"));
	list($host2,$time2) = mysql_fetch_array(myquery("SELECT host,last_active FROM game_users_active WHERE user_id='".$player['user_id']."'"));
	
	$player_func = getFunc($player['user_id']);

	//$host1=1; $host2=2;
	
	if ($player['user_id']==$char['user_id'])
	{
		//нельзя напасть на себя
		return 'Нельзя напасть на самого себя';
	}
	elseif ((time()-$time2)>300)
	{
		//противник не в игре
		return 'Противника нет в игре';
	}
	elseif ($host1==$host2)
	{
		//нельзя напасть на мульта
		return 'Нельзя напасть на мульта';
	}
	elseif ($player['clan_id']==$char['clan_id'] and $char['clan_id']!=0 and $player['clan_id']!=0 and $map['dolina']!=1)
	{
		//нельзя напасть на своего соклановца, но можно в Долине Смерти
		return 'Нельзя нападать на соклановца (кроме Долины Смерти)';
	}
	elseif ($player_func==1)
	{
		return 'Игрок занят в бою';
	}
	elseif ($player_func==2)
	{
		return 'Игрок занят в крафте';
	}
	elseif ($player_func==3)
	{
		return 'Игрок в магазине';
	}
	elseif ($player_func==4)
	{
		return 'Игрок занят в Двух Башнях';
	}
	elseif ($player_func==10)
	{
		return 'Игрок проходит квест';
	}
	elseif (($char['clan_id']==0 or $player['clan_id']==0) AND (($player['HP'])<(($player['HP_MAX'])/2)))
	{
		//уберем шакальство
		return 'Шакальство не пройдет!';
	}
	elseif ($player['HP']==0 or $char['HP']==0)
	{
		//нельзя нападать мертвым или на мертвого
		return 'Кто-то из вас двоих мертв!';
	}
	if (clans_war==0 OR ($map['dolina']==0 AND $map['arena']==0))
	{
		$player_delay_reason_id = get_delay_reason_id($user_id);

		if ($map['dolina']==1)
		{
			return 'В Долине Смерти бои еще запрещены!';
		}
		elseif ($player_func==9)
		{
			return 'Игрок в городе';
		}
		elseif ($player_func==6)
		{
			return 'Игрок находится в зале палантиров';
		}
		elseif ($player_func==7)
		{
			return 'Игрок находится в дневниках';
		}
		elseif ($player_delay_reason_id==32)
		{
			return 'Игрок находится в личном землевладении';
		}
	}

	if ($map['dolina']==1)
	{
		//все ограничения на атаку сняты
		$check=1;
	}
	elseif ($map['maps_name']=='Гильдия новичков')
	{
		//В Гильдии Новичков можно нападать только на ровесников и уровнем выше независимо от типа боя
		if ($player['clevel']-3 <= $char['clevel'] and
			$char['clevel'] <= $player['clevel'] and
			$player['clevel']!='0')
		{
			$check=1;
		}
		else
		{
			return 'Ты не подходишь по уровню противника!';
		}
	}
	else
	{
		$est_kamikadze = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=6 AND item_id=".povyazka_kamikadze.""),0,0);
		if ($est_kamikadze && $char['clevel'] <= $player['clevel'])
		{
			 $check=1;
		}
		else
		{
			$max_level = 3;
			$add = floor(($char['clevel']-5)/5);
			$max_level+=$add;
			$min_level = 2;
			if ($player['clevel']-$max_level <= $char['clevel'] and
				$char['clevel'] <= ($player['clevel']+$min_level) and
				$player['clevel']!=0)
			{
				 $check=1;
			}
			else
			{
				return 'Ты не подходишь по уровню противника!';
			}
		}
	}
	return ($check);
}

function attack_user($char,$player,$type,$turnir_type=0)
{
	$id = $player['user_id'];
	$user_id = $char['user_id'];
	
	$l_type = $type;
	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=".$char['map_name'].""));
	
	if ($l_type!=8 AND $l_type!=9)
	{
		if (check_attack($char,$player,$l_type,$map)!=1)
		{
			{if (function_exists("save_debug")) save_debug(); return 0;}
		}
	}

	if ((clans_war==0 AND $l_type!=8 AND $l_type!=9)OR($map['dolina']==0 AND $map['arena']==0))
	{
		if ($char['vsadnik']!=0 and $map['dolina']!=1)
		{
			$sel=myquery("select * from game_vsadnik where id='".$char['vsadnik']."'");
			$row=mysql_fetch_array($sel);
			$vsad_char = $row['vsad'];
		}
		else $vsad_char = 0;

		if ($player['vsadnik']!=0 and $map['dolina']!=1)
		{
			$sel=myquery("select * from game_vsadnik where id='".$player['vsadnik']."'");
			$row=mysql_fetch_array($sel);
			$vsad_enemy = $row['vsad'];
		}
		else $vsad_enemy = 0;
	}
	else
	{
		$vsad_char = 0;
		$vsad_enemy = 0;
	}
	
	if (clans_war==1 AND $map['dolina']==1 AND clans_war_type>0) $type=clans_war_type;
	if (chaos_war==1) $type=5;

	if ($type==1) $nam=14;
	if ($type==2) $nam=15;
	if ($type==3) $nam=16;
	if ($type==4) $nam=17;
	if ($type==5) $nam=18;
	if ($type==6) $nam=49;
	if ($type==7) $nam=48;
	if ($type==8) $nam=47;
	if ($type==9) $nam=46;

	$begin=0;
	//if ($type>=2) $begin=time()+180;
	$ins = myquery("insert into combat (hod,combat_type,map_name,map_xpos,map_ypos,start_time,time_last_hod,turnir_type) values (1,'$type','".$char['map_name']."','".$char['map_xpos']."','".$char['map_ypos']."',".time().",".time().",$turnir_type)");
	$uid = mysql_insert_id();
		
	list($pol_char) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=".$char['user_id'].""));
	list($pol_player) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=".$player['user_id'].""));

	$side_char = 1;
	$side_player = 2;
	switch($type)
	{
		case 4:
		{
			$side_char = $char['clan_id'];
			$side_player = $player['clan_id'];    
		}
		break;
		case 5:
		{
			$side_char = $char['user_id'];
			$side_player = $player['user_id'];    
		}
		break;
		case 6:
		{
			$side_char = $char['sklon'];
			$side_player = $player['sklon'];    
		}
		break;
		case 7:
		{
			$side_char = $char['race'];
			$side_player = $player['race'];    
		}
		break;
	}

	//1 игрок
	$time_hod = time();
	
	myquery("DELETE FROM combat_users WHERE user_id=".$char['user_id']."");
	myquery("DELETE FROM combat_users_exp WHERE user_id=".$char['user_id']."");
	myquery("insert into combat_users (
		combat_id,user_id,npc,time_last_active,name,clevel,side,
		HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,
		MS_KULAK,MS_WEAPON,MS_ART,MS_PARIR,MS_LUK,MS_SWORD,MS_AXE,MS_SPEAR,MS_THROW,
		HP_MAX,MP_MAX,STM_MAX,
		lucky,pol,avatar,sklon,race,clan_id,HP_start,hod_start) values (
		$uid,".$char['user_id'].",0,".$time_hod.",'".$char['name']."',".$char['clevel'].",$side_char,
		".$char['HP'].",".$char['MP'].",".$char['STM'].",".$char['STR'].",".$char['DEX'].",".$char['SPD'].",".$char['VIT'].",".$char['NTL'].",".$char['PIE'].",
		".$char['MS_KULAK'].",".$char['MS_WEAPON'].",".$char['MS_ART'].",".$char['MS_PARIR'].",".$char['MS_LUK'].",'".$char['MS_SWORD']."','".$char['MS_AXE']."','".$char['MS_SPEAR']."','".$char['MS_THROW']."',
		".$char['HP_MAX'].",".$char['MP_MAX'].",".$char['STM_MAX'].",
		'".$char['lucky']."','$pol_char','".$char['avatar']."','".$char['sklon']."','".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0)."',".$char['clan_id'].",".$char['HP_MAX'].",1)");
	
	//2 игрок
	myquery("DELETE FROM combat_users WHERE user_id='".$player['user_id']."'");
	myquery("DELETE FROM combat_users_exp WHERE user_id='".$player['user_id']."'");
	myquery("insert into combat_users (
		combat_id,user_id,npc,time_last_active,name,clevel,side,
		HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,
		MS_KULAK,MS_WEAPON,MS_ART,MS_PARIR,MS_LUK,MS_SWORD,MS_AXE,MS_SPEAR,MS_THROW,
		HP_MAX,MP_MAX,STM_MAX,
		lucky,pol,avatar,sklon,race,clan_id,HP_start,hod_start) values (
		$uid,".$player['user_id'].",0,".$time_hod.",'".$player['name']."',".$player['clevel'].",$side_player,
		".$player['HP'].",".$player['MP'].",".$player['STM'].",".$player['STR'].",".$player['DEX'].",".$player['SPD'].",".$player['VIT'].",".$player['NTL'].",".$player['PIE'].",
		".$player['MS_KULAK'].",".$player['MS_WEAPON'].",".$player['MS_ART'].",".$player['MS_PARIR'].",".$player['MS_LUK'].",'".$player['MS_SWORD']."','".$player['MS_AXE']."','".$player['MS_SPEAR']."','".$player['MS_THROW']."',
		".$player['HP_MAX'].",".$player['MP_MAX'].",".$player['STM_MAX'].",
		'".$player['lucky']."','$pol_player','".$player['avatar']."','".$player['sklon']."','".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$player['race'].""),0,0)."',".$player['clan_id'].",".$player['HP_MAX'].",1)");

	if ($turnir_type==0 AND $player['vsadnik']!=0  and $map['dolina']!=1 and ($vsad_enemy>$vsad_char OR ($vsad_enemy==$vsad_char AND $char['MS_VSADNIK']<$player['MS_VSADNIK'])))
	{
		combat_setFunc($id,1,$uid);
		combat_setFunc($char['user_id'],2,$uid);
	}
	else
	{
		combat_setFunc($user_id,5,$uid);
		combat_setFunc($id,5,$uid);
		if ($type==4)
		{
			myquery("INSERT INTO game_log (message,date,fromm,ob) VALUES ('".iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-weight:900;font-size:14px;color:red;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\"> ВНИМАНИЕ! <img align=\"center\" src=\"http://".IMG_DOMAIN."/clan/".$char['clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$char['clan_id'].""),0,0)." и <img align=\"center\" src=\"http://".IMG_DOMAIN."/clan/".$player['clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$player['clan_id'].""),0,0)." начинают бой: ".$map['name']."(".$char['map_xpos']."; ".$char['map_ypos'].") </span>'").",".time().",-1,1)");
		}
	}
	set_delay_reason_id($id,$nam);
	set_delay_reason_id($user_id,$nam);

	if ($l_type!=8 AND $l_type!=9) 
	{
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">На тебя напал игрок <b>".$char['name']."</b></span>");
		myquery("INSERT INTO game_log (`message`,`date`,`fromm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,$id,1)");
	}
	else
	{
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">Начинается турнирная дуэль с игроком: <b>".$char['name']."</b></span>");
		myquery("INSERT INTO game_log (`message`,`date`,`fromm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,".$player['user_id'].",1)");
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">Начинается турнирная дуэль с игроком: <b>".$player['name']."</b></span>");
		myquery("INSERT INTO game_log (`message`,`date`,`fromm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,".$char['user_id'].",1)");
	}

	if ($l_type!=8 AND $l_type!=9) save_stat($user_id,'','',16,'','','',$player['clan_id'],'','','');
	
	myquery("DELETE FROM game_combats_log WHERE boy=$uid");
	$log=myquery("insert into game_combats_log (boy,hod,time,type,map_name,map_xpos,map_ypos) values ($uid,0,".time().",$type,'".$char['map_name']."','".$char['map_xpos']."','".$char['map_ypos']."')");
	myquery("DELETE FROM game_combats_users WHERE (boy=$uid AND user_id=$user_id)");
	myquery("INSERT INTO game_combats_users (boy,user_id) VALUES ($uid,$user_id)");
	myquery("DELETE FROM game_combats_users WHERE (boy=$uid AND user_id=$id)");
	myquery("INSERT INTO game_combats_users (boy,user_id) VALUES ($uid,$id)");

	list($host) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id=$user_id"));
	list($host_more) = mysql_fetch_array(myquery("SELECT host_more FROM game_users_active_host WHERE user_id=$user_id"));
	myquery("INSERT INTO combat_lose_user (combat_id,user_id,host,host_more) VALUES ($uid,$user_id,".$host.",'".$host_more."')");
	list($host) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id=$id"));
	list($host_more) = mysql_fetch_array(myquery("SELECT host_more FROM game_users_active_host WHERE user_id=$id"));
	myquery("INSERT INTO combat_lose_user (combat_id,user_id,host,host_more) VALUES ($uid,$id,".$host.",'".$host_more."')");

	$sel_arco = myquery("SELECT * FROM arcomage_users WHERE user_id=$id");
	if (mysql_num_rows($sel_arco)>0)
	{
		$arco = mysql_fetch_array($sel_arco);
		myquery("DELETE FROM arcomage WHERE id='".$arco['arcomage_id']."'");
		myquery("DELETE FROM arcomage_users_cards WHERE arcomage_id='".$arco['arcomage_id']."'");
		myquery("DELETE FROM arcomage_history WHERE arcomage_id='".$arco['arcomage_id']."'");
		myquery("DELETE FROM arcomage_users WHERE arcomage_id='".$arco['arcomage_id']."'");
	}
	myquery("DELETE FROM arcomage_call WHERE user_id IN ($user_id,$id)");
	
	ForceFunc($id,1);
	ForceFunc($user_id,1);
	setLocation("http://".DOMAIN."/combat.php");
	return '';
}

function check_level5()
{
	global $char,$sred_level;
	if ($char['clevel']>($sred_level+3)) return false;
	if ($char['clevel']<($sred_level-3)) return false;
	return true;
}

function check_level3()
{
	global $char,$sred_level;
	if ($char['clevel']>($sred_level+3)) return false;
	if ($char['clevel']<($sred_level-3)) return false;
	return true;
}

function check_level6()
{
	global $char,$sred_level;
	if ($char['clevel']>($sred_level+3)) return false;
	if ($char['clevel']<($sred_level-3)) return false;
	return true;
}

function check_level7()
{
	global $char,$sred_level;
	if ($char['clevel']>($sred_level+3)) return false;
	if ($char['clevel']<($sred_level-3)) return false;
	return true;
}

function check_join($char,$player,&$join,&$alt,&$svit,$auto=0)
{
	//return 0;
	global $user_id;
	if (checkFunc($user_id,1,1)==0)
	{
		//нельзя присоединяться если уже в бою
		return 0;
	}
	
	$est_plash_monaha_char = mysqlresult(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=5 AND item_id=".plash_monaha.""),0,0);
	if ($est_plash_monaha_char==1) return 'Ты отказываешься от боя';

	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id='".$char['map_name']."'"));
	$svitok = 0;
	list($host) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id=$user_id"));
	list($host_more) = mysql_fetch_array(myquery("SELECT host_more FROM game_users_active_host WHERE user_id=$user_id"));
	list($cur_hod,$type) = mysql_fetch_array(myquery("SELECT hod,combat_type FROM combat WHERE combat_id=".$player['boy'].""));
	list($player_svitok) = mysql_fetch_array(myquery("SELECT svitok FROM combat_users WHERE combat_id=".$player['boy']." AND user_id=".$player['user_id'].""));
	  
	if ($map['dolina']==0)
	{
		if ($map['boy_type1']==0 AND $map['boy_type2']==0 AND $map['boy_type3']==0 AND $map['boy_type4']==0 AND $map['boy_type5']==0 AND $map['boy_type6']==0 AND $map['boy_type7']==0)
		{
			return 'На карте все бои запрещены';
		}
	}
	else
	{
		if (clans_war==0 AND $auto==0)
		{
			return 'В Долине Смерти бои еще запрещены';
		}
		if ($cur_hod>3)
		{
			return 'После 3 хода в Долине вход запрещен';
		}
	}
	if ($type==8 OR $type==9 OR $type==10)
	{
		//в турнирные бои вступать нельзя
		return 'В турнирые бои вступать нельзя';
	}
	$comb_func=combat_getFunc($player['user_id']);
	if ($player['boy']==0)
	{
		//присоединяться можно только к игрокам в бою
		return 'Игрок не участвует в бою';
	}
	elseif ($player['HP']==0 or $char['HP']==0)
	{
		//нельзя нападать мертвым или на мертвого
		return 'Ты или игрок мертв';
	}
	elseif ($comb_func!=5 AND $comb_func!=6)
	{
		//присоединяться можно только к уже начавшим битву
		return 'Игрока нет в бою';
	}
	elseif (mysql_result(myquery("SELECT COUNT(*) FROM combat_lose_user WHERE combat_id=".$player['boy']." AND (user_id=$user_id OR (host='".$host."' AND host_more='".$host_more."'))"),0,0)>0 AND $auto==0)
	{
		//нельзя повторно вступать в бой
		return 'Ты уже '.echo_sex('участвовал','участвовала').' в этом бою';
	}
	elseif ($cur_hod>6)
	{
		//после 6 хода в бой вступать нельзя
		return 'Вход в бой закрыт';
	}

	if ($map['id']!=map_coliseum)
	{
		//Если есть свитки присоединения к бою - обработаем их
		$ch_small = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id AND priznak=0 AND used IN (12,13,14) AND item_id='".svitok_small_item_id."'"),0,0);
		$ch_sred = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id AND priznak=0 AND used IN (12,13,14) AND item_id='".svitok_sred_item_id."'"),0,0);
	}
	else
	{
		$ch_small = 0;
		$ch_sred = 0;
	}
	
	//echo '$ch_small = '.$ch_small.',$ch_sred='.$ch_sred;

	//Не в Долине Смерти проверим доступность присоединения по свиткам
	if ($map['dolina']==0)
	{
		if ($cur_hod>3)
		{
			if ($map['id']==map_coliseum)
			{
				return 'В бой можно войти только до конца 3 хода боя!';
			}
			elseif ($ch_small==0 AND $ch_sred==0)
			{
				//присоединяться к бою после 3 хода без свитков нельзя
				return 'У тебя нет нужного свитка!';
			}
		}
	}
	
	//Далее проверим тип боя и право вступления в него с учетом свитков на руках
	if ($type==1 AND $char['clan_id']!=0)
	{
		//в обычный бой можно присоединиться только к соклановцу
		$error='';
		$select=myquery("select view_active_users.clan_id from view_active_users,combat_users where view_active_users.clan_id<>'".$player['clan_id']."' AND view_active_users.user_id=combat_users.user_id and combat_users.combat_id='".$player['boy']."' ORDER BY view_active_users.clan_id ASC limit 1");
		list($clan)=mysql_fetch_array($select);
		if ($clan==0)
		{
			return 'Можно присоединяться только к битве клановых игроков';
		}

		//если надо показать значок меча - возвращаем 1, join=1, alt = текст ALT тега картинки значка
		//если надо показать значок выбора свитка - возвращаем 1, join=99, alt = значения не имеет
		//сначала проверяем возможность обычного входа в бой без свитков. Если разрешено - возвращаемся, иначе начинаем проверку по свиткам
		if ($cur_hod<=3)
		{
			if ($player['clan_id']==$char['clan_id'])
			{
				$join=1;
				$alt='Присоединиться к обычной битве';
				return 1;
			}
		}

		$svit = '';
		//по среднему свитку вступаем в любой бой до 7 хода
		if ($ch_sred>0 AND $cur_hod<=6 AND $player_svitok<=2) $svit.=',2,';  
		//по малому свитку вступаем на обычных условиях в любое время после 3 хода
		if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND $player['clan_id']==$char['clan_id'] AND $player_svitok<=1) $svit.=',1,'; 
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='Вступить в обычную битву';
			return 1;
		}
		else
		{
			$join = 0;
			return 'Ты не можешь присоединиться к обычной битве';
		}
	}
	elseif ($type==2)
	{
		return 'К дуэлям нельзя присоединяться!';
	}
	elseif ($type==3)
	{
		$error='';
		$sred_level = 0;
		$kol = 0;
		$sel = myquery("SELECT SUM(clevel) AS sumlevel,COUNT(*) AS kol FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0 GROUP BY combat_id");
		list($sred_level,$kol) = mysql_fetch_array($sel);
		if ($kol>0)
		{
			$sred_level=round($sred_level/$kol);
			//если надо показать значок меча - возвращаем 1, join=1, alt = текст ALT тега картинки значка
			//если надо показать значок выбора свитка - возвразаем 1, join=99, alt = значения не имеет
			//сначала проверяем возможность обычного входа в бой без свитков. Если разрешено - возвращаемся, иначе начинаем проверку по свиткам
			if ($cur_hod<=3)
			{
				if (check_level3() OR (clans_war!=0 AND $map['dolina']==1))
				{
					$join=1;
					$alt='Присоединиться к общей битве';
					return 1;
				}
			}

			$svit = '';
			//по среднему свитку вступаем в любой бой до 7 хода
			if ($ch_sred>0 AND $cur_hod<=6 AND $player_svitok<=2) $svit.=',2,'; 
			//по малому свитку вступаем на обычных условиях в любое время после 3 хода 
			if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND check_level3() AND $player_svitok<=1) $svit.=',1,'; 
			if ($svit!='' OR $cur_hod<3)
			{
				$join=99;
				$alt='Вступить в общую битву';
				return 1;
			}
			else
			{
				$join = 0;
				return 'Ты не можешь присоединиться к общей битве (ср.уровень = '.$sred_level.')';
			}
		}
		else
		{
			return 'В бою никого нет';
		}
	}
	elseif ($type==4)
	{
		//в клановый бой можно присоединиться только к соклановцу или выступить еще одной стороной в бое
		$error='';
		//если надо показать значок меча - возвращаем 1, join=1, alt = текст ALT тега картинки значка
		//если надо показать значок выбора свитка - возвразаем 1, join=99, alt = значения не имеет
		//сначала проверяем возможность обычного входа в бой без свитков. Если разрешено - возвращаемся, иначе начинаем проверку по свиткам
		if ($cur_hod<=3)
		{
			if (($char['clan_id']==$player['clan_id']))
			{
				$join=1;
				$alt='Присоединиться к клановой битве';
				return 1;
			}
			elseif ($char['clan_id']!=0)
			{
				$join=1;
				$alt='Вступить в клановую битву';
				return 1;
			}
		}

		$svit = '';
		//по среднему свитку вступаем в любой бой до 7 хода
		if ($ch_sred>0 AND $cur_hod<=6 AND $player_svitok<=2) $svit.=',2,';  
		//по малому свитку вступаем на обычных условиях в любое время после 3 хода
		if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND ($char['clan_id']==$player['clan_id'] OR $char['clan_id']!=0) AND $player_svitok<=1) $svit.=',1,'; 
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='Вступить в клановую битву';
			return 1;
		}
		else
		{
			$join = 0;
			return 'Ты не можешь присоединиться к клановой битве';
		}
	}
	elseif ($type==5)
	{
		$error='';
		$sred_level = 0;
		$kol = 0;
		$sel = myquery("SELECT SUM(clevel) AS sumlevel,COUNT(*) AS kol FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0 GROUP BY combat_id");
		list($sred_level,$kol) = mysql_fetch_array($sel);
		if ($kol==0)
		{
			return 'В бою уже никого нет!';
		}
		if ($auto==1)
		{
			//для автоприсоединения (Битвы Хаоса) вступаем независимо от свитков
			return 1;
		}
		$sred_level=round($sred_level/$kol);
		//если надо показать значок меча - возвращаем 1, join=1, alt = текст ALT тега картинки значка
		//если надо показать значок выбора свитка - возвразаем 1, join=99, alt = значения не имеет
		//сначала проверяем возможность обычного входа в бой без свитков. Если разрешено - возвращаемся, иначе начинаем проверку по свиткам
		if ($cur_hod<=3)
		{
			if (check_level5() OR (clans_war!=0 AND $map['dolina']==1))
			{
				$join=1;
				$alt='Присоединиться к битве все против всех';
				return 1;
			}
		}

		$svit = '';
		//по среднему свитку вступаем в любой бой до 7 хода
		if ($ch_sred>0 AND $cur_hod<=6 AND $player_svitok<=2) $svit.=',2,';  
		//по малому свитку вступаем на обычных условиях в любое время после 3 хода
		if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND check_level5() AND $player_svitok<=1) $svit.=',1,'; 
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='Вступить в битву все против всех';
			return 1;
		}
		else
		{
			$join = 0;
			return 'Ты не можешь присоединиться к битве все против всех (ср.уровень = '.$sred_level.')';
		}
	}
	elseif ($type==6)
	{
		$error='';
		$sred_level = 0;
		$kol = 0;
		$sel = myquery("SELECT SUM(clevel) AS sumlevel,COUNT(*) AS kol FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0  GROUP BY combat_id");
		list($sred_level,$kol) = mysql_fetch_array($sel);
		if ($kol==0)
		{
			return 'В бою уже никого нет!';
		}
		$sred_level=round($sred_level/$kol);
		//если надо показать значок меча - возвращаем 1, join=1, alt = текст ALT тега картинки значка
		//если надо показать значок выбора свитка - возвразаем 1, join=99, alt = значения не имеет
		//сначала проверяем возможность обычного входа в бой без свитков. Если разрешено - возвращаемся, иначе начинаем проверку по свиткам
		if ($cur_hod<=3)
		{
			if (check_level6() OR (clans_war!=0 AND $map['dolina']==1))
			{
				$join=1;
				$alt='Присоединиться к битве склонностей';
				return 1;
			}
		}

		$svit = '';
		//по среднему свитку вступаем в любой бой до 7 хода  
		if ($map['id']!=map_coliseum)
		{
			if ($ch_sred>0 AND $cur_hod<=6 AND $player_svitok<=2) $svit.=',2,';  
			//по малому свитку вступаем на обычных условиях в любое время после 3 хода
			if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND check_level6() AND $player_svitok<=1) $svit.=',1,';
		}
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='Вступить в битву склонностей';
			return 1;
		}
		else
		{
			$join = 0;
			return 'Ты не можешь присоединиться к битве склонностей (ср.уровень = '.$sred_level.')';
		}
	}
	elseif ($type==7)
	{
		$error='';
		$sred_level = 0;
		$kol = 0;
		$sel = myquery("SELECT SUM(clevel) AS sumlevel,COUNT(*) AS kol FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0  GROUP BY combat_id");
		list($sred_level,$kol) = mysql_fetch_array($sel);
		if ($kol==0)
		{
			return 'В бою уже никого нет!';
		}
		$sred_level=round($sred_level/$kol);
		//если надо показать значок меча - возвращаем 1, join=1, alt = текст ALT тега картинки значка
		//если надо показать значок выбора свитка - возвразаем 1, join=99, alt = значения не имеет
		//сначала проверяем возможность обычного входа в бой без свитков. Если разрешено - возвращаемся, иначе начинаем проверку по свиткам
		if ($cur_hod<=3)
		{
			if (check_level7() OR (clans_war!=0 AND $map['dolina']==1))
			{
				$join=1;
				$alt='Присоединиться к битве рас';
				return 1;
			}
		}

		$svit = '';  
		//по среднему свитку вступаем в любой бой до 7 хода  
		if ($ch_sred>0 AND $cur_hod<=6 AND $player_svitok<=2) $svit.=',2,';  
		//по малому свитку вступаем на обычных условиях в любое время после 3 хода
		if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND check_level7() AND $player_svitok<=1) $svit.=',1,'; 
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='Вступить в битву рас';
			return 1;
		}
		else
		{
			$join = 0;
			return 'Ты не можешь присоединиться к битве рас (ср.уровень = '.$sred_level.')';
		}
	}
	return '';
}

function join_attack_user($char,$player,$svit)
{
	list($pol_char) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=".$char['user_id'].""));
	$user_id=$char['user_id'];
	
	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id='".$char['map_name']."'"));
	list($type)=mysql_fetch_array(myquery("SELECT combat_type FROM combat WHERE combat_id=".$player['boy'].""));
	switch ($type)
	{
		case 1:
			list($side) = mysql_fetch_array(myquery("SELECT side FROM combat_users WHERE combat_id=".$player['boy']." and user_id=".$player['user_id']." and npc=0"));
		break;

		case 2:
			$error='duel';
		break;

		case 3:
			$sred_level = 0;
			$sred_level_1 = 0;
			$side_1 = 0;
			$sred_level_2 = 0;
			$side_2 = 0;
			$kol = 0;
			$sel = myquery("SELECT clevel,side FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0 AND svitok=0");
			while ($boyuser = mysql_fetch_array($sel))
			{
				$sred_level+=$boyuser['clevel'];
				$kol++;
				if ($side_1==0 or $side_1==$boyuser['side'])
				{
					$side_1=$boyuser['side'];
					$sred_level_1+=$boyuser['clevel'];
				}
				elseif ($side_2==0 or $side_2==$boyuser['side'])
				{
					$side_2=$boyuser['side'];
					$sred_level_2+=$boyuser['clevel'];
				}
			}
			$sred_level=round($sred_level/$kol);
			if ($sred_level_1<=$sred_level_2) $side=$side_1;
			else $side=$side_2;
		break;

		case 4:
			$side = $char['clan_id'];
		break;

		case 5:
			$side = $char['user_id'];
		break;

		case 6:
			$side = $char['sklon'];
		break;

		case 7:
			$side = $char['race'];
		break;
	}
	if ($svit==2 AND clans_war==0)
	{
		list($side) = mysql_fetch_array(myquery("SELECT side FROM combat_users WHERE combat_id=".$player['boy']." AND user_id=".$player['user_id'].""));
	}
	if (!isset($side))
	{
		list($side) = mysql_fetch_array(myquery("SELECT side FROM combat_users WHERE combat_id=".$player['boy']." AND user_id=".$player['user_id'].""));
	}
	//используем свиток
	if ($svit==1)
	{
		$ch = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used IN (12,13,14) AND item_id='".svitok_small_item_id."' LIMIT 1");
		if (mysql_num_rows($ch))
		{
			$svitok = mysql_fetch_array($ch);
			$Item = new Item($svitok['id']);
			$Item->use_item();
		}
	}
	if ($svit==2)
	{
		$ch = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used IN (12,13,14) AND item_id='".svitok_sred_item_id."' LIMIT 1");
		if (mysql_num_rows($ch))
		{
			$svitok = mysql_fetch_array($ch);
			$Item = new Item($svitok['id']);
			$Item->use_item();
		}
	}
	myquery("DELETE FROM combat_users WHERE user_id=".$char['user_id']."");
	$not_gp = 0;
	$not_exp = 0;
	$k_komu = 0;
	if (clans_war==0)
	{
		if ($svit==2)
		{
			$not_exp = 1;
			$not_gp = 1;
			$k_komu = $player['user_id'];
		}
		if ($svit==1)
		{
			$not_exp = 1;
			$k_komu = $player['user_id'];
		}
	}
	
	save_stat($char['user_id'],'','',1,'','',$player['user_id'],'','','','','');

	$nam = 0;
	if ($type==1) $nam=14;
	if ($type==2) $nam=15;
	if ($type==3) $nam=16;
	if ($type==4) $nam=17;
	if ($type==5) $nam=18;
	if ($type==6) $nam=49;
	if ($type==7) $nam=48;
	combat_setFunc($user_id,6,$player['boy']);
	set_delay_reason_id($user_id,$nam);
	
	myquery("DELETE FROM game_combats_users WHERE boy=".$player['boy']." AND user_id=$user_id");
	myquery("INSERT INTO game_combats_users (boy,user_id) VALUES (".$player['boy'].",$user_id)");
	list($host) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id=$user_id"));
	list($host_more) = mysql_fetch_array(myquery("SELECT host_more FROM game_users_active_host WHERE user_id=$user_id"));
	myquery("INSERT INTO combat_lose_user (combat_id,user_id,host,host_more) VALUES (".$player['boy'].",$user_id,".$host.",'".$host_more."')");

    list($current_hod) = mysql_fetch_array(myquery("SELECT hod FROM combat WHERE combat_id=".$player['boy'].""));
    
	myquery("DELETE FROM combat_users_exp WHERE user_id=".$char['user_id']."");
	myquery("insert into combat_users (
		combat_id,user_id,npc,time_last_active,name,clevel,side,
		HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,
		MS_KULAK,MS_WEAPON,MS_ART,MS_PARIR,MS_LUK,MS_SWORD,MS_AXE,MS_SPEAR,MS_THROW,
		HP_MAX,MP_MAX,STM_MAX,
		lucky,pol,`join`,svitok,k_komu,avatar,sklon,not_exp,not_gp,race,clan_id,HP_start,hod_start) values (
		".$player['boy'].",".$char['user_id'].",0,".time().",'".$char['name']."',".$char['clevel'].",$side,
		".$char['HP'].",".$char['MP'].",".$char['STM'].",".$char['STR'].",".$char['DEX'].",".$char['SPD'].",".$char['VIT'].",".$char['NTL'].",".$char['PIE'].",
		".$char['MS_KULAK'].",".$char['MS_WEAPON'].",".$char['MS_ART'].",".$char['MS_PARIR'].",".$char['MS_LUK'].",'".$char['MS_SWORD']."','".$char['MS_AXE']."','".$char['MS_SPEAR']."','".$char['MS_THROW']."',
		".$char['HP_MAX'].",".$char['MP_MAX'].",".$char['STM_MAX'].",
		'".$char['lucky']."','$pol_char',1,$svit,$k_komu,'".$char['avatar']."','".$char['sklon']."',$not_exp,$not_gp,'".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0)."',".$char['clan_id'].",".$char['HP_MAX'].",$current_hod)");
	ForceFunc($user_id,1);
	setLocation("combat.php");
}

function fill_maze_by_npc_for_user($mapid,$userid,$not_boss_exit=0)
{
	$user = mysql_fetch_array(myquery("SELECT * FROM view_active_users WHERE user_id=$userid"));
	list($kol_npc) = mysql_fetch_array(myquery("SELECT count_npc FROM game_maps WHERE id=$mapid"));
	if ($kol_npc>0)
	{
        myquery("DELETE FROM game_npc_template WHERE to_delete=1 AND npc_id IN (SELECT npc_id FROM game_npc WHERE map_name=$mapid AND for_user_id=$userid)");
		myquery("DELETE FROM game_npc WHERE map_name=$mapid AND for_user_id=$userid");
	}
	$pos = myquery("SELECT xpos,ypos FROM game_maze WHERE map_name=$mapid AND (move_up+move_down+move_left+move_right)>=1");
	$selnpc = myquery("SELECT npc_id FROM game_npc_template");

	while ($kol_npc>0)
	{
		jump_random_query($pos);
		$position = mysql_fetch_assoc($pos);
		jump_random_query($selnpc);
		list($npc_copy) = mysql_fetch_array($selnpc);
        if ($not_boss_exit==1)
        {
            //Для Новогодних лабиринтов:
            //сначала создадим шаблон
            $npc_copy = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=$npc_copy"));
            myquery("INSERT INTO game_npc_template (npc_name,npc_race,npc_img,npc_max_hp,npc_max_mp,item,npc_opis,agressive,npc_level,respawn,to_delete) VALUES ('".$npc_copy['npc_name']."','".$npc_copy['npc_race']."','".$npc_copy['npc_img']."','".max($user['HP_MAXX'],$user['HP_MAX'])."','".$user['MP_MAX']."','".$npc_copy['item']."','".$npc_copy['npc_opis']."','2','".$user['clevel']."','".$npc_copy['respawn']."','1')");
            //потом самого бота
            $npc_copy = mysql_insert_id();
        }
		myquery("INSERT INTO game_npc (prizrak,for_user_id,map_name,xpos,ypos,HP,MP,view,npc_id,npc_flag) VALUES ('1',$userid,$mapid,".$position['xpos'].",".$position['ypos'].",".max($user['HP_MAXX'],$user['HP_MAX']).",".$user['MP_MAX'].",0,".$npc_copy.",1)");
		$kol_npc--;
	}
	if ($not_boss_exit==0 and $kol_npc<>0)
	{
		list($max_x,$max_y) = mysql_fetch_array(myquery("SELECT xpos,ypos FROM game_maze WHERE map_name=$mapid ORDER BY xpos DESC, ypos DESC LIMIT 1"));
		myquery("INSERT INTO game_npc (prizrak,for_user_id,map_name,xpos,ypos,view,npc_id,npc_flag) VALUES ('1',$userid,$mapid,$max_x,$max_y,0,".npc_id_boss_labirint.",1)");
	}
}
?>