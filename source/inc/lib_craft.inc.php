<?php
function get_craft_index($craft_name)
{
	switch ($craft_name)
	{
		case 'sobiratel': return 1; break;
		case 'alchemist': return 2; break;
		case 'stroitel': return 3; break;
		case 'lumberjack': return 4; break;
		case 'stonemason': return 5; break;
		case 'mining': return 6; break;
		case 'sawmill': return 7; break;
		case 'hunter': return 8; break;
		case 'meating': return 9; break;
		case 'founder': return 10; break;
		case 'orujeinik': return 11; break;
        case 'kuznec': return 12; break;
		default: return 0; break;
	}
}

function get_craft_name($craft_index)
{
	switch ($craft_index)
	{
		case 1: return 'Собиратель'; break;
		case 2: return 'Алхимик'; break;
		case 3: return 'Строитель'; break;
		case 4: return 'Лесоруб'; break;
		case 5: return 'Каменотес'; break;
		case 6: return 'Рудокоп'; break;
		case 7: return 'Плотник'; break;
		case 8: return 'Охотник'; break;
		case 9: return 'Скорняк'; break;
		case 10: return 'Литейщик'; break;
		case 11: return 'Оружейник'; break;
        case 12: return 'Кузнец'; break;
		default: return ''; break;
	}
}

function getCraftLevel($user_id,$craft_index)
{
	$craft_times = getCraftTimes($user_id,$craft_index);
	return floor(CraftSpetsTimeToLevel($craft_index,$craft_times));
}

function getCraftTimes($user_id,$craft_index)
{
	$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=$craft_index");
	if ($sel!=false AND mysql_num_rows($sel)>0)
	{
		return mysqlresult($sel,0,0);
	}
	else
	{
		return 0;
	}
}

function setCraftTimes($user_id,$craft_index,$craft_times,$inc=0)
{
	$sel= myquery("SELECT profile FROM game_users_crafts WHERE user_id=$user_id AND craft_index=$craft_index");
	if (mysql_num_rows($sel))
	{
		list($profile) = mysql_fetch_array($sel);
		if (($profile==1)OR($craft_index==1)OR($craft_index==2))
		{
			if ($inc!=0)
			{
				myquery("UPDATE game_users_crafts SET times=times+$craft_times,last_time=UNIX_TIMESTAMP() WHERE user_id=$user_id AND craft_index=$craft_index");
			} 
			else
			{
				myquery("UPDATE game_users_crafts SET times=$craft_times,last_time=UNIX_TIMESTAMP() WHERE user_id=$user_id AND craft_index=$craft_index");
			}  
		}
		else
		{
			myquery("UPDATE game_users_crafts SET last_time=UNIX_TIMESTAMP() WHERE user_id=$user_id AND craft_index=$craft_index");
		}
	} 
	else
	{
		myquery("INSERT INTO game_users_crafts SET user_id=$user_id,craft_index=$craft_index,last_time=UNIX_TIMESTAMP()");
	}   
}

function checkCraftTrain($user_id,$craft_index)
{
	//если не профильная профессия, проверяем last_time, если он менее 30 минут возвращаем 0
	//иначе возвращаем 1
	$sel = myquery("SELECT last_time,profile FROM game_users_crafts WHERE user_id=$user_id AND craft_index=$craft_index");
	if (!mysql_num_rows($sel)) 
	{
		return true;
	}
	else
	{
		list($last_time,$profile) = mysql_fetch_array($sel);
		if ($profile==1)
		{
			return true;
		}
		else
		{
			if ((time()-$last_time)>(30*60))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	return true;
}

function CraftSpetsTimeToLevel($craft_index,$time)
{
	switch ($craft_index)
	{
		case 1:
		{
			//собиратель
			$level = floor(sqrt($time/4)+0.5);
			if ($level>50)
			{
				return 50;
			}
			else
			{
				return $level;
			}
		}
		break;
		
		case 2:
		{
			//алхимик
			return max($time,floor(sqrt(7/4*$time))); 
		}
		break;
		
		case 4:
		{
			//лесоруб
			if ($time<10) return 0;
			$i=10;
			$level=0;
			while ($time>=$i)
			{	
				$level++;
				$i=$i+10*($level+1);
			}
			//$level = floor(sqrt($time/50)); 
			//if ($level>25) $level=25;
			return $level;
		}
		break;
		
		case 5:
		{
			//каменотес
			if ($time<10) return 0;
			$i=10;
			$level=0;
			while ($time>=$i)
			{	
				$level++;
				$i=$i+10*($level+1);
			}
			//$level = floor(sqrt($time/50)); 
			//if ($level>25) $level=25;
			return $level;
		}
		break;
		
		case 6:
		{
			//рудокоп
			if ($time<50) return 0;
			return floor(sqrt($time/50));  
		}
		break;
		
		case 7:
		{
			//плотник
			if ($time<50) return 0;
			return floor(sqrt($time/50));
		}
		break;
		
		case 8:
		{
			//охотник
			if ($time<100) return 0;
			$level = floor(sqrt($time/50)); 
			if ($level>25) $level=25;
			return $level;
		}
		break;

		case 9:
		{
			//скорняк
			if ($time<100) return 0;
			$level = floor(sqrt($time/50)); 
			//if ($level>25) $level=25;
			return $level;
		}
		break;
		
		case 10:
		{
			//литейщик
			if ($time<50) return 0;
			return floor(sqrt($time/25));  
		}
		break;
		
		case 11:	
		{
			//оружейник
			//if ($time<50) return 0;
			$level = floor(sqrt(7*$time)*2);
			if ($time<$level) $level = $time;
			return $level; 
		}
		break;
		
        case 12:    
        {
            //кузнец
            $level = floor(sqrt($time/10));
            if ($time<$level) $level = $time;
            return $level; 
        }
        break;
        
		default:
		{
			return 0;
		}
		break;
	}
}

function CreateArrayForCraftEliksir()
{
	global $char;
	$eliksir = array();
	$check=myquery("Select game_items_factsheet.name, game_items_factsheet.weight, game_items_factsheet.img, game_items_factsheet.hp_p, game_items_factsheet.mp_p, game_items_factsheet.stm_p, game_items_factsheet.dstr, game_items_factsheet.ddex, game_items_factsheet.dvit, game_items_factsheet.dspd, game_items_factsheet.dntl, game_items_factsheet.dpie, game_items_factsheet.dlucky, game_items_factsheet.cc_p, game_eliksir_alchemist.* 
				   From game_items_factsheet Join game_eliksir_alchemist On game_items_factsheet.id=game_eliksir_alchemist.elik_id 	
				  ");
    $i=0;
	while ($elik=mysql_fetch_array($check))
	{
		list($dlit)=mysql_fetch_array(myquery("Select dlit from game_eliksir_dlit where elik_id=".$elik['elik_id'].""));
		$check2=myquery("Select * From game_eliksir_res Where elik_id=".$elik['elik_id']."");
		if (mysql_num_rows($check2)>0)
		{
			$eliksir[$i]['item_id']=$elik['elik_id'];
			$eliksir[$i]['name']=$elik['name'];
			$eliksir[$i]['weight']=$elik['weight'];
			$eliksir[$i]['img']=$elik['img'];
			$eliksir[$i]['hp']=$elik['hp_p'];
			$eliksir[$i]['mp']=$elik['mp_p'];
			$eliksir[$i]['stm']=$elik['stm_p'];
			$eliksir[$i]['str']=$elik['dstr'];
			$eliksir[$i]['vit']=$elik['dvit'];
			$eliksir[$i]['spd']=$elik['dspd'];
			$eliksir[$i]['ntl']=$elik['dntl'];
			$eliksir[$i]['pie']=$elik['dpie'];
			$eliksir[$i]['dex']=$elik['ddex'];
			$eliksir[$i]['lucky']=$elik['dlucky'];
			$eliksir[$i]['cc']=$elik['cc_p'];
			$eliksir[$i]['alchemist']=$elik['alchemist'];
			$eliksir[$i]['clevel']=$elik['clevel'];
			$eliksir[$i]['time']=max($elik['mintime'], $elik['maxtime']-getCraftLevel($char['user_id'],2)*60);
			$eliksir[$i]['dlit']=$dlit;
			$j=0;
			while ($res=mysql_fetch_array($check2))
			{
				$eliksir[$i]['resource'][$j]['id']=$res['res_id'];
				$eliksir[$i]['resource'][$j]['kol']=$res['kol'];
				$j++;
			}
			$i++;
		}
	}
  
/*	
	$eliksir[0]['name']='Малое зелье восстановления здоровья (+50 HP)';
	$eliksir[0]['alchemist']=0;
	$eliksir[0]['clevel']=10;
	$eliksir[0]['hp']=50;
	$eliksir[0]['mp']=0;
	$eliksir[0]['stm']=0;
	$eliksir[0]['time']=max(10*60,60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[0]['resource'][0]['id'] = 25;
	$eliksir[0]['resource'][0]['kol'] = 1;
	$eliksir[0]['resource'][1]['id'] = 23;
	$eliksir[0]['resource'][1]['kol'] = 1;
	$eliksir[0]['resource'][2]['id'] = 22;
	$eliksir[0]['resource'][2]['kol'] = 2;
	$eliksir[0]['weight']=2;
	$eliksir[0]['img']='eliksir/life_Z_1';#item/****.gif
	$eliksir[0]['str']=0;
	$eliksir[0]['dex']=0;
	$eliksir[0]['spd']=0;
	$eliksir[0]['vit']=0;
	$eliksir[0]['ntl']=0;
	$eliksir[0]['pie']=0;
	$eliksir[0]['dlit']=0;
	$eliksir[0]['item_id']=314;

	$eliksir[1]['name']='Среднее зелье восстановления здоровья (+100 HP)';
	$eliksir[1]['alchemist']=50;
	$eliksir[1]['clevel']=10;
	$eliksir[1]['hp']=100;
	$eliksir[1]['mp']=0;
	$eliksir[1]['stm']=0;
	$eliksir[1]['time']=max(10*60,2*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[1]['resource'][0]['id'] = 25;
	$eliksir[1]['resource'][0]['kol'] = 2;
	$eliksir[1]['resource'][1]['id'] = 23;
	$eliksir[1]['resource'][1]['kol'] = 2;
	$eliksir[1]['resource'][2]['id'] = 22;
	$eliksir[1]['resource'][2]['kol'] = 2;
	$eliksir[1]['resource'][3]['id'] = 21;
	$eliksir[1]['resource'][3]['kol'] = 1;
	$eliksir[1]['resource'][4]['id'] = 20;
	$eliksir[1]['resource'][4]['kol'] = 1;
	$eliksir[1]['weight']=2;
	$eliksir[1]['img']='eliksir/life_Z_2';#item/****.gif
	$eliksir[1]['str']=0;
	$eliksir[1]['dex']=0;
	$eliksir[1]['spd']=0;
	$eliksir[1]['vit']=0;
	$eliksir[1]['ntl']=0;
	$eliksir[1]['pie']=0;
	$eliksir[1]['dlit']=0;
	$eliksir[1]['item_id']=316;

	$eliksir[2]['name']='Большое зелье восстановления здоровья (+200 HP)';
	$eliksir[2]['alchemist']=100;
	$eliksir[2]['clevel']=10;
	$eliksir[2]['hp']=200;
	$eliksir[2]['mp']=0;
	$eliksir[2]['stm']=0;
	$eliksir[2]['time']=max(10*60,3*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[2]['resource'][0]['id'] = 25;
	$eliksir[2]['resource'][0]['kol'] = 5;
	$eliksir[2]['resource'][1]['id'] = 23;
	$eliksir[2]['resource'][1]['kol'] = 5;
	$eliksir[2]['resource'][2]['id'] = 22;
	$eliksir[2]['resource'][2]['kol'] = 5;
	$eliksir[2]['resource'][3]['id'] = 21;
	$eliksir[2]['resource'][3]['kol'] = 3;
	$eliksir[2]['resource'][4]['id'] = 17;
	$eliksir[2]['resource'][4]['kol'] = 2;
	$eliksir[2]['weight']=2;
	$eliksir[2]['img']='eliksir/life_Z_3';#item/****.gif
	$eliksir[2]['str']=0;
	$eliksir[2]['dex']=0;
	$eliksir[2]['spd']=0;
	$eliksir[2]['vit']=0;
	$eliksir[2]['ntl']=0;
	$eliksir[2]['pie']=0;
	$eliksir[2]['dlit']=0;
	$eliksir[2]['item_id']=322;

	/*
	$eliksir[3]['name']='Малый эликсир восстановления здоровья (+400 HP)';
	$eliksir[3]['alchemist']=150;
	$eliksir[3]['clevel']=15;
	$eliksir[3]['hp']=400;
	$eliksir[3]['mp']=0;
	$eliksir[3]['stm']=0;
	$eliksir[3]['time']=max(10*60,6*60*60-$char['alchemist']*60);
	$eliksir[3]['resource'][0]['id'] = 25;
	$eliksir[3]['resource'][0]['kol'] = 10;
	$eliksir[3]['resource'][1]['id'] = 23;
	$eliksir[3]['resource'][1]['kol'] = 5;
	$eliksir[3]['resource'][2]['id'] = 22;
	$eliksir[3]['resource'][2]['kol'] = 5;
	$eliksir[3]['resource'][3]['id'] = 21;
	$eliksir[3]['resource'][3]['kol'] = 10;
	$eliksir[3]['resource'][4]['id'] = 20;
	$eliksir[3]['resource'][4]['kol'] = 10;
	$eliksir[3]['weight']=1;
	$eliksir[3]['img']='eliksir/life_E_1_2';#item/****.gif
	$eliksir[3]['str']=0;
	$eliksir[3]['dex']=0;
	$eliksir[3]['spd']=0;
	$eliksir[3]['vit']=0;
	$eliksir[3]['ntl']=0;
	$eliksir[3]['pie']=0;
	$eliksir[3]['dlit']=0;

	$eliksir[4]['name']='Средний эликсир восстановления здоровья (+800 HP)';
	$eliksir[4]['alchemist']=200;
	$eliksir[4]['clevel']=15;
	$eliksir[4]['hp']=800;
	$eliksir[4]['mp']=0;
	$eliksir[4]['stm']=0;
	$eliksir[4]['time']=max(10*60,9*60*60-$char['alchemist']*60);
	$eliksir[4]['resource'][0]['id'] = 25;
	$eliksir[4]['resource'][0]['kol'] = 15;
	$eliksir[4]['resource'][1]['id'] = 23;
	$eliksir[4]['resource'][1]['kol'] = 10;
	$eliksir[4]['resource'][2]['id'] = 22;
	$eliksir[4]['resource'][2]['kol'] = 10;
	$eliksir[4]['resource'][3]['id'] = 21;
	$eliksir[4]['resource'][3]['kol'] = 10;
	$eliksir[4]['resource'][4]['id'] = 20;
	$eliksir[4]['resource'][4]['kol'] = 15;
	$eliksir[4]['resource'][5]['id'] = 17;
	$eliksir[4]['resource'][5]['kol'] = 20;
	$eliksir[4]['weight']=1;
	$eliksir[4]['img']='eliksir/life_E_2_2';#item/****.gif
	$eliksir[4]['str']=0;
	$eliksir[4]['dex']=0;
	$eliksir[4]['spd']=0;
	$eliksir[4]['vit']=0;
	$eliksir[4]['ntl']=0;
	$eliksir[4]['pie']=0;
	$eliksir[4]['dlit']=0;

	$eliksir[5]['name']='Большой эликсир восстановления здоровья (+1600 HP)';
	$eliksir[5]['alchemist']=250;
	$eliksir[5]['clevel']=15;
	$eliksir[5]['hp']=1600;
	$eliksir[5]['mp']=0;
	$eliksir[5]['stm']=0;
	$eliksir[5]['time']=max(10*60,12*60*60-$char['alchemist']*60);
	$eliksir[5]['resource'][0]['id'] = 25;
	$eliksir[5]['resource'][0]['kol'] = 20;
	$eliksir[5]['resource'][1]['id'] = 23;
	$eliksir[5]['resource'][1]['kol'] = 10;
	$eliksir[5]['resource'][2]['id'] = 22;
	$eliksir[5]['resource'][2]['kol'] = 10;
	$eliksir[5]['resource'][3]['id'] = 21;
	$eliksir[5]['resource'][3]['kol'] = 20;
	$eliksir[5]['resource'][4]['id'] = 20;
	$eliksir[5]['resource'][4]['kol'] = 25;
	$eliksir[5]['resource'][5]['id'] = 17;
	$eliksir[5]['resource'][5]['kol'] = 30;
	$eliksir[5]['weight']=1;
	$eliksir[5]['img']='eliksir/life_E_3_2';#item/****.gif
	$eliksir[5]['str']=0;
	$eliksir[5]['dex']=0;
	$eliksir[5]['spd']=0;
	$eliksir[5]['vit']=0;
	$eliksir[5]['ntl']=0;
	$eliksir[5]['pie']=0;
	$eliksir[5]['dlit']=0;

	$eliksir[3]['name']='Малое зелье восстановления маны (+50 MP)';
	$eliksir[3]['alchemist']=0;
	$eliksir[3]['clevel']=10;
	$eliksir[3]['hp']=0;
	$eliksir[3]['mp']=50;
	$eliksir[3]['stm']=0;
	$eliksir[3]['time']=max(10*60,1*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[3]['resource'][0]['id'] = 25;
	$eliksir[3]['resource'][0]['kol'] = 1;
	$eliksir[3]['resource'][1]['id'] = 24;
	$eliksir[3]['resource'][1]['kol'] = 2;
	$eliksir[3]['resource'][2]['id'] = 19;
	$eliksir[3]['resource'][2]['kol'] = 2;
	$eliksir[3]['weight']=2;
	$eliksir[3]['img']='eliksir/mana_Z_1';#item/****.gif
	$eliksir[3]['str']=0;
	$eliksir[3]['dex']=0;
	$eliksir[3]['spd']=0;
	$eliksir[3]['vit']=0;
	$eliksir[3]['ntl']=0;
	$eliksir[3]['pie']=0;
	$eliksir[3]['dlit']=0;
	$eliksir[3]['item_id']=313;

	$eliksir[4]['name']='Среднее зелье восстановления маны (+100 MP)';
	$eliksir[4]['alchemist']=50;
	$eliksir[4]['clevel']=10;
	$eliksir[4]['hp']=0;
	$eliksir[4]['mp']=100;
	$eliksir[4]['stm']=0;
	$eliksir[4]['time']=max(10*60,2*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[4]['resource'][0]['id'] = 25;
	$eliksir[4]['resource'][0]['kol'] = 2;
	$eliksir[4]['resource'][1]['id'] = 24;
	$eliksir[4]['resource'][1]['kol'] = 2;
	$eliksir[4]['resource'][2]['id'] = 19;
	$eliksir[4]['resource'][2]['kol'] = 2;
	$eliksir[4]['resource'][3]['id'] = 18;
	$eliksir[4]['resource'][3]['kol'] = 2;
	$eliksir[4]['resource'][4]['id'] = 20;
	$eliksir[4]['resource'][4]['kol'] = 1;
	$eliksir[4]['weight']=2;
	$eliksir[4]['img']='eliksir/mana_Z_2';#item/****.gif
	$eliksir[4]['str']=0;
	$eliksir[4]['dex']=0;
	$eliksir[4]['spd']=0;
	$eliksir[4]['vit']=0;
	$eliksir[4]['ntl']=0;
	$eliksir[4]['pie']=0;
	$eliksir[4]['dlit']=0;
	$eliksir[4]['item_id']=323;

	$eliksir[5]['name']='Большое зелье восстановления маны (+200 MP)';
	$eliksir[5]['alchemist']=100;
	$eliksir[5]['clevel']=10;
	$eliksir[5]['hp']=0;
	$eliksir[5]['mp']=200;
	$eliksir[5]['stm']=0;
	$eliksir[5]['time']=max(10*60,3*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[5]['resource'][0]['id'] = 25;
	$eliksir[5]['resource'][0]['kol'] = 5;
	$eliksir[5]['resource'][1]['id'] = 24;
	$eliksir[5]['resource'][1]['kol'] = 7;
	$eliksir[5]['resource'][2]['id'] = 19;
	$eliksir[5]['resource'][2]['kol'] = 5;
	$eliksir[5]['resource'][3]['id'] = 18;
	$eliksir[5]['resource'][3]['kol'] = 5;
	$eliksir[5]['resource'][4]['id'] = 17;
	$eliksir[5]['resource'][4]['kol'] = 2;
	$eliksir[5]['weight']=2;
	$eliksir[5]['img']='eliksir/mana_Z_3';#item/****.gif
	$eliksir[5]['str']=0;
	$eliksir[5]['dex']=0;
	$eliksir[5]['spd']=0;
	$eliksir[5]['vit']=0;
	$eliksir[5]['ntl']=0;
	$eliksir[5]['pie']=0;
	$eliksir[5]['dlit']=0;
	$eliksir[5]['item_id']=321;
	/*
	$eliksir[9]['name']='Малый эликсир восстановления маны (+400 MP)';
	$eliksir[9]['alchemist']=150;
	$eliksir[9]['clevel']=15;
	$eliksir[9]['hp']=0;
	$eliksir[9]['mp']=400;
	$eliksir[9]['stm']=0;
	$eliksir[9]['time']=max(10*60,6*60*60-$char['alchemist']*60);
	$eliksir[9]['resource'][0]['id'] = 25;
	$eliksir[9]['resource'][0]['kol'] = 10;
	$eliksir[9]['resource'][1]['id'] = 24;
	$eliksir[9]['resource'][1]['kol'] = 7;
	$eliksir[9]['resource'][2]['id'] = 19;
	$eliksir[9]['resource'][2]['kol'] = 7;
	$eliksir[9]['resource'][3]['id'] = 18;
	$eliksir[9]['resource'][3]['kol'] = 12;
	$eliksir[9]['resource'][4]['id'] = 20;
	$eliksir[9]['resource'][4]['kol'] = 12;
	$eliksir[9]['weight']=1;
	$eliksir[9]['img']='eliksir/mana_E_1_5';#item/****.gif
	$eliksir[9]['str']=0;
	$eliksir[9]['dex']=0;
	$eliksir[9]['spd']=0;
	$eliksir[9]['vit']=0;
	$eliksir[9]['ntl']=0;
	$eliksir[9]['pie']=0;
	$eliksir[9]['dlit']=0;

	$eliksir[10]['name']='Средний эликсир восстановления маны (+800 MP)';
	$eliksir[10]['clevel']=15;
	$eliksir[10]['alchemist']=200;
	$eliksir[10]['hp']=0;
	$eliksir[10]['mp']=800;
	$eliksir[10]['stm']=0;
	$eliksir[10]['time']=max(10*60,9*60*60-$char['alchemist']*60);
	$eliksir[10]['resource'][0]['id'] = 25;
	$eliksir[10]['resource'][0]['kol'] = 15;
	$eliksir[10]['resource'][1]['id'] = 24;
	$eliksir[10]['resource'][1]['kol'] = 10;
	$eliksir[10]['resource'][2]['id'] = 19;
	$eliksir[10]['resource'][2]['kol'] = 10;
	$eliksir[10]['resource'][3]['id'] = 18;
	$eliksir[10]['resource'][3]['kol'] = 10;
	$eliksir[10]['resource'][4]['id'] = 20;
	$eliksir[10]['resource'][4]['kol'] = 21;
	$eliksir[10]['resource'][5]['id'] = 17;
	$eliksir[10]['resource'][5]['kol'] = 30;
	$eliksir[10]['weight']=1;
	$eliksir[10]['img']='eliksir/mana_E_2_5';#item/****.gif
	$eliksir[10]['str']=0;
	$eliksir[10]['dex']=0;
	$eliksir[10]['spd']=0;
	$eliksir[10]['vit']=0;
	$eliksir[10]['ntl']=0;
	$eliksir[10]['pie']=0;
	$eliksir[10]['dlit']=0;

	$eliksir[11]['name']='Большой эликсир восстановления маны (+1600 MP)';
	$eliksir[11]['alchemist']=250;
	$eliksir[11]['clevel']=15;
	$eliksir[11]['hp']=0;
	$eliksir[11]['mp']=1600;
	$eliksir[11]['stm']=0;
	$eliksir[11]['time']=max(10*60,12*60*60-$char['alchemist']*60);
	$eliksir[11]['resource'][0]['id'] = 25;
	$eliksir[11]['resource'][0]['kol'] = 20;
	$eliksir[11]['resource'][1]['id'] = 24;
	$eliksir[11]['resource'][1]['kol'] = 10;
	$eliksir[11]['resource'][2]['id'] = 19;
	$eliksir[11]['resource'][2]['kol'] = 15;
	$eliksir[11]['resource'][3]['id'] = 18;
	$eliksir[11]['resource'][3]['kol'] = 25;
	$eliksir[11]['resource'][4]['id'] = 20;
	$eliksir[11]['resource'][4]['kol'] = 35;
	$eliksir[11]['resource'][5]['id'] = 17;
	$eliksir[11]['resource'][5]['kol'] = 45;
	$eliksir[11]['weight']=1;
	$eliksir[11]['img']='eliksir/mana_E_3_5';#item/****.gif
	$eliksir[11]['str']=0;
	$eliksir[11]['dex']=0;
	$eliksir[11]['spd']=0;
	$eliksir[11]['vit']=0;
	$eliksir[11]['ntl']=0;
	$eliksir[11]['pie']=0;
	$eliksir[11]['dlit']=0;

	$eliksir[6]['name']='Малое зелье восстановления энергии (+50 STM)';
	$eliksir[6]['alchemist']=0;
	$eliksir[6]['clevel']=10;
	$eliksir[6]['hp']=0;
	$eliksir[6]['mp']=0;
	$eliksir[6]['stm']=50;
	$eliksir[6]['time']=max(10*60,1*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[6]['resource'][0]['id'] = 25;
	$eliksir[6]['resource'][0]['kol'] = 1;
	$eliksir[6]['resource'][1]['id'] = 13;
	$eliksir[6]['resource'][1]['kol'] = 1;
	$eliksir[6]['resource'][2]['id'] = 15;
	$eliksir[6]['resource'][2]['kol'] = 1;
	$eliksir[6]['weight']=2;
	$eliksir[6]['img']='eliksir/energ_Z_1';#item/****.gif
	$eliksir[6]['str']=0;
	$eliksir[6]['dex']=0;
	$eliksir[6]['spd']=0;
	$eliksir[6]['vit']=0;
	$eliksir[6]['ntl']=0;
	$eliksir[6]['pie']=0;
	$eliksir[6]['dlit']=0;
	$eliksir[6]['item_id']=315;

	$eliksir[7]['name']='Среднее зелье восстановления энергии (+100 STM)';
	$eliksir[7]['alchemist']=50;
	$eliksir[7]['clevel']=10;
	$eliksir[7]['hp']=0;
	$eliksir[7]['mp']=0;
	$eliksir[7]['stm']=100;
	$eliksir[7]['time']=max(10*60,2*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[7]['resource'][0]['id'] = 25;
	$eliksir[7]['resource'][0]['kol'] = 2;
	$eliksir[7]['resource'][1]['id'] = 13;
	$eliksir[7]['resource'][1]['kol'] = 1;
	$eliksir[7]['resource'][2]['id'] = 15;
	$eliksir[7]['resource'][2]['kol'] = 1;
	$eliksir[7]['resource'][3]['id'] = 16;
	$eliksir[7]['resource'][3]['kol'] = 1;
	$eliksir[7]['resource'][4]['id'] = 21;
	$eliksir[7]['resource'][4]['kol'] = 1;
	$eliksir[7]['weight']=2;
	$eliksir[7]['img']='eliksir/energ_Z_2';#item/****.gif
	$eliksir[7]['str']=0;
	$eliksir[7]['dex']=0;
	$eliksir[7]['spd']=0;
	$eliksir[7]['vit']=0;
	$eliksir[7]['ntl']=0;
	$eliksir[7]['pie']=0;
	$eliksir[7]['dlit']=0;
	$eliksir[7]['item_id']=318;

	$eliksir[8]['name']='Большое зелье восстановления энергии (+200 STM)';
	$eliksir[8]['alchemist']=100;
	$eliksir[8]['clevel']=10;
	$eliksir[8]['hp']=0;
	$eliksir[8]['mp']=0;
	$eliksir[8]['stm']=200;
	$eliksir[8]['time']=max(10*60,3*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[8]['resource'][0]['id'] = 25;
	$eliksir[8]['resource'][0]['kol'] = 5;
	$eliksir[8]['resource'][1]['id'] = 13;
	$eliksir[8]['resource'][1]['kol'] = 4;
	$eliksir[8]['resource'][2]['id'] = 15;
	$eliksir[8]['resource'][2]['kol'] = 3;
	$eliksir[8]['resource'][3]['id'] = 16;
	$eliksir[8]['resource'][3]['kol'] = 2;
	$eliksir[8]['resource'][4]['id'] = 21;
	$eliksir[8]['resource'][4]['kol'] = 2;
	$eliksir[8]['weight']=2;
	$eliksir[8]['img']='eliksir/energ_Z_3';#item/****.gif
	$eliksir[8]['str']=0;
	$eliksir[8]['dex']=0;
	$eliksir[8]['spd']=0;
	$eliksir[8]['vit']=0;
	$eliksir[8]['ntl']=0;
	$eliksir[8]['pie']=0;
	$eliksir[8]['dlit']=0;
	$eliksir[8]['item_id']=319;

	$eliksir[15]['name']='Малый эликсир восстановления энергии (+400 STM)';
	$eliksir[15]['alchemist']=150;
	$eliksir[15]['clevel']=15;
	$eliksir[15]['hp']=0;
	$eliksir[15]['mp']=0;
	$eliksir[15]['stm']=400;
	$eliksir[15]['time']=max(10*60,6*60*60-$char['alchemist']*60);
	$eliksir[15]['resource'][0]['id'] = 25;
	$eliksir[15]['resource'][0]['kol'] = 10;
	$eliksir[15]['resource'][1]['id'] = 13;
	$eliksir[15]['resource'][1]['kol'] = 3;
	$eliksir[15]['resource'][2]['id'] = 15;
	$eliksir[15]['resource'][2]['kol'] = 3;
	$eliksir[15]['resource'][3]['id'] = 16;
	$eliksir[15]['resource'][3]['kol'] = 8;
	$eliksir[15]['resource'][4]['id'] = 20;
	$eliksir[15]['resource'][4]['kol'] = 8;
	$eliksir[15]['weight']=1;
	$eliksir[15]['img']='eliksir/energ_E_1';#item/****.gif
	$eliksir[15]['str']=0;
	$eliksir[15]['dex']=0;
	$eliksir[15]['spd']=0;
	$eliksir[15]['vit']=0;
	$eliksir[15]['ntl']=0;
	$eliksir[15]['pie']=0;
	$eliksir[15]['dlit']=0;

	$eliksir[16]['name']='Средний эликсир восстановления энергии (+800 STM)';
	$eliksir[16]['alchemist']=200;
	$eliksir[16]['clevel']=15;
	$eliksir[16]['hp']=0;
	$eliksir[16]['mp']=0;
	$eliksir[16]['stm']=800;
	$eliksir[16]['time']=max(10*60,9*60*60-$char['alchemist']*60);
	$eliksir[16]['resource'][0]['id'] = 25;
	$eliksir[16]['resource'][0]['kol'] = 15;
	$eliksir[16]['resource'][1]['id'] = 13;
	$eliksir[16]['resource'][1]['kol'] = 10;
	$eliksir[16]['resource'][2]['id'] = 15;
	$eliksir[16]['resource'][2]['kol'] = 5;
	$eliksir[16]['resource'][3]['id'] = 16;
	$eliksir[16]['resource'][3]['kol'] = 9;
	$eliksir[16]['resource'][4]['id'] = 20;
	$eliksir[16]['resource'][4]['kol'] = 10;
	$eliksir[16]['resource'][5]['id'] = 17;
	$eliksir[16]['resource'][5]['kol'] = 15;
	$eliksir[16]['weight']=1;
	$eliksir[16]['img']='eliksir/energ_E_2';#item/****.gif
	$eliksir[16]['str']=0;
	$eliksir[16]['dex']=0;
	$eliksir[16]['spd']=0;
	$eliksir[16]['vit']=0;
	$eliksir[16]['ntl']=0;
	$eliksir[16]['pie']=0;
	$eliksir[16]['dlit']=0;

	$eliksir[17]['name']='Большой эликсир восстановления энергии (+1600 STM)';
	$eliksir[17]['alchemist']=250;
	$eliksir[17]['clevel']=15;
	$eliksir[17]['hp']=0;
	$eliksir[17]['mp']=0;
	$eliksir[17]['stm']=1600;
	$eliksir[17]['time']=max(10*60,12*60*60-$char['alchemist']*60);
	$eliksir[17]['resource'][0]['id'] = 25;
	$eliksir[17]['resource'][0]['kol'] = 20;
	$eliksir[17]['resource'][1]['id'] = 13;
	$eliksir[17]['resource'][1]['kol'] = 5;
	$eliksir[17]['resource'][2]['id'] = 15;
	$eliksir[17]['resource'][2]['kol'] = 5;
	$eliksir[17]['resource'][3]['id'] = 16;
	$eliksir[17]['resource'][3]['kol'] = 10;
	$eliksir[17]['resource'][4]['id'] = 20;
	$eliksir[17]['resource'][4]['kol'] = 15;
	$eliksir[17]['resource'][5]['id'] = 17;
	$eliksir[17]['resource'][5]['kol'] = 25;
	$eliksir[17]['weight']=1;
	$eliksir[17]['img']='eliksir/energ_E_3';#item/****.gif
	$eliksir[17]['str']=0;
	$eliksir[17]['dex']=0;
	$eliksir[17]['spd']=0;
	$eliksir[17]['vit']=0;
	$eliksir[17]['ntl']=0;
	$eliksir[17]['pie']=0;
	$eliksir[17]['dlit']=0;

	//return $eliksir;
	//{if (function_exists("save_debug")) save_debug(); exit;}

	$i=9;
	$eliksir[$i]['name']='Малый эликсир силы';
	$eliksir[$i]['alchemist']=250;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,20*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 3;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 6 шт
	$eliksir[$i]['resource'][1]['kol'] = 6;
	$eliksir[$i]['resource'][2]['id'] = 21; //корень мандрагоры 3 шт
	$eliksir[$i]['resource'][2]['kol'] = 3;
	$eliksir[$i]['resource'][3]['id'] = 13; //троллья трава 3 шт
	$eliksir[$i]['resource'][3]['kol'] = 3;
	$eliksir[$i]['resource'][4]['id'] = 15; //белый гриб 3 шт
	$eliksir[$i]['resource'][4]['kol'] = 3;
	$eliksir[$i]['resource'][5]['id'] = 16; //древний мох 3 шт
	$eliksir[$i]['resource'][5]['kol'] = 3;
	$eliksir[$i]['resource'][6]['id'] = 24; //чертополох 3 шт
	$eliksir[$i]['resource'][6]['kol'] = 3;
	$eliksir[$i]['resource'][7]['id'] = 19; //поганка 3 шт
	$eliksir[$i]['resource'][7]['kol'] = 3;
	$eliksir[$i]['resource'][8]['id'] = 18; //мухомор 3 шт
	$eliksir[$i]['resource'][8]['kol'] = 3;
	$eliksir[$i]['resource'][9]['id'] = 20; //корень женьшеня 3 шт
	$eliksir[$i]['resource'][9]['kol'] = 3;
	$eliksir[$i]['resource'][10]['id'] = 17; //эльфийский цветок 3 шт
	$eliksir[$i]['resource'][10]['kol'] = 3;
	$eliksir[$i]['resource'][11]['id'] = 43; //сердце морийского тролля
	$eliksir[$i]['resource'][11]['kol'] = 3;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_11_22';#item/****.gif
	$eliksir[$i]['str']=2;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=24*60*60;
	$eliksir[$i]['item_id']=362;

	$i=10;
	$eliksir[$i]['name']='Малый эликсир интеллекта';
	$eliksir[$i]['alchemist']=250;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,20*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 3;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 6 шт
	$eliksir[$i]['resource'][1]['kol'] = 6;
	$eliksir[$i]['resource'][2]['id'] = 23; //девясил 3 шт
	$eliksir[$i]['resource'][2]['kol'] = 3;
	$eliksir[$i]['resource'][3]['id'] = 22; //зверобой 3 шт
	$eliksir[$i]['resource'][3]['kol'] = 3;
	$eliksir[$i]['resource'][4]['id'] = 15; //белый гриб 3 шт
	$eliksir[$i]['resource'][4]['kol'] = 3;
	$eliksir[$i]['resource'][5]['id'] = 16; //древний мох 3 шт
	$eliksir[$i]['resource'][5]['kol'] = 3;
	$eliksir[$i]['resource'][6]['id'] = 24; //чертополох 3 шт
	$eliksir[$i]['resource'][6]['kol'] = 3;
	$eliksir[$i]['resource'][7]['id'] = 19; //поганка 3 шт
	$eliksir[$i]['resource'][7]['kol'] = 3;
	$eliksir[$i]['resource'][8]['id'] = 18; //мухомор 3 шт
	$eliksir[$i]['resource'][8]['kol'] = 3;
	$eliksir[$i]['resource'][9]['id'] = 20; //корень женьшеня 3 шт
	$eliksir[$i]['resource'][9]['kol'] = 3;
	$eliksir[$i]['resource'][10]['id'] = 17; //эльфийский цветок 3 шт
	$eliksir[$i]['resource'][10]['kol'] = 3;
	$eliksir[$i]['resource'][11]['id'] = 44; //глаз морийского орка
	$eliksir[$i]['resource'][11]['kol'] = 3;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_2_2';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=2;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=24*60*60;
	$eliksir[$i]['item_id']=363;

	$i=11;
	$eliksir[$i]['name']='Малый эликсир ловкости';
	$eliksir[$i]['alchemist']=250;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,20*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 3;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 6 шт
	$eliksir[$i]['resource'][1]['kol'] = 6;
	$eliksir[$i]['resource'][2]['id'] = 23; //девясил 3 шт
	$eliksir[$i]['resource'][2]['kol'] = 3;
	$eliksir[$i]['resource'][3]['id'] = 22; //зверобой 3 шт
	$eliksir[$i]['resource'][3]['kol'] = 3;
	$eliksir[$i]['resource'][4]['id'] = 21; //корень мандрагоры 3 шт
	$eliksir[$i]['resource'][4]['kol'] = 3;
	$eliksir[$i]['resource'][5]['id'] = 13; //троллья трава 3 шт
	$eliksir[$i]['resource'][5]['kol'] = 3;
	$eliksir[$i]['resource'][6]['id'] = 24; //чертополох 3 шт
	$eliksir[$i]['resource'][6]['kol'] = 3;
	$eliksir[$i]['resource'][7]['id'] = 19; //поганка 3 шт
	$eliksir[$i]['resource'][7]['kol'] = 3;
	$eliksir[$i]['resource'][8]['id'] = 18; //мухомор 3 шт
	$eliksir[$i]['resource'][8]['kol'] = 3;
	$eliksir[$i]['resource'][9]['id'] = 20; //корень женьшеня 3 шт
	$eliksir[$i]['resource'][9]['kol'] = 3;
	$eliksir[$i]['resource'][10]['id'] = 17; //эльфийский цветок 3 шт
	$eliksir[$i]['resource'][10]['kol'] = 3;
	$eliksir[$i]['resource'][11]['id'] = 39; //шкура морийского крота
	$eliksir[$i]['resource'][11]['kol'] = 3;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_11_6';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=2;
	$eliksir[$i]['dlit']=24*60*60;
	$eliksir[$i]['item_id']=364;
	
	$i=12;
	$eliksir[$i]['name']='Малый эликсир защиты';
	$eliksir[$i]['alchemist']=250;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,20*60*60-$char['alchemist']*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 3;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 6 шт
	$eliksir[$i]['resource'][1]['kol'] = 6;
	$eliksir[$i]['resource'][2]['id'] = 23; //девясил 3 шт
	$eliksir[$i]['resource'][2]['kol'] = 3;
	$eliksir[$i]['resource'][3]['id'] = 22; //зверобой 3 шт
	$eliksir[$i]['resource'][3]['kol'] = 3;
	$eliksir[$i]['resource'][4]['id'] = 21; //корень мандрагоры 3 шт
	$eliksir[$i]['resource'][4]['kol'] = 3;
	$eliksir[$i]['resource'][5]['id'] = 13; //троллья трава 3 шт
	$eliksir[$i]['resource'][5]['kol'] = 3;
	$eliksir[$i]['resource'][6]['id'] = 15; //белый гриб 3 шт
	$eliksir[$i]['resource'][6]['kol'] = 3;
	$eliksir[$i]['resource'][7]['id'] = 16; //древний мох 3 шт
	$eliksir[$i]['resource'][7]['kol'] = 3;
	$eliksir[$i]['resource'][8]['id'] = 18; //мухомор 3 шт
	$eliksir[$i]['resource'][8]['kol'] = 3;
	$eliksir[$i]['resource'][9]['id'] = 20; //корень женьшеня 3 шт
	$eliksir[$i]['resource'][9]['kol'] = 3;
	$eliksir[$i]['resource'][10]['id'] = 17; //эльфийский цветок 3 шт
	$eliksir[$i]['resource'][10]['kol'] = 3;
	$eliksir[$i]['resource'][11]['id'] = 34; //голова морийской летучей мыши
	$eliksir[$i]['resource'][11]['kol'] = 3;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_11_4';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=2;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=24*60*60;
	$eliksir[$i]['item_id']=365;
	
	$i++;
	$eliksir[$i]['name']='Малый эликсир выносливости';
	$eliksir[$i]['alchemist']=250;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,20*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 3;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 6 шт
	$eliksir[$i]['resource'][1]['kol'] = 6;
	$eliksir[$i]['resource'][2]['id'] = 23; //девясил 3 шт
	$eliksir[$i]['resource'][2]['kol'] = 3;
	$eliksir[$i]['resource'][3]['id'] = 22; //зверобой 3 шт
	$eliksir[$i]['resource'][3]['kol'] = 3;
	$eliksir[$i]['resource'][4]['id'] = 21; //корень мандрагоры 3 шт
	$eliksir[$i]['resource'][4]['kol'] = 3;
	$eliksir[$i]['resource'][5]['id'] = 13; //троллья трава 3 шт
	$eliksir[$i]['resource'][5]['kol'] = 3;
	$eliksir[$i]['resource'][6]['id'] = 15; //белый гриб 3 шт
	$eliksir[$i]['resource'][6]['kol'] = 3;
	$eliksir[$i]['resource'][7]['id'] = 16; //древний мох 3 шт
	$eliksir[$i]['resource'][7]['kol'] = 3;
	$eliksir[$i]['resource'][8]['id'] = 24; //чертополох 3 шт
	$eliksir[$i]['resource'][8]['kol'] = 3;
	$eliksir[$i]['resource'][9]['id'] = 19; //поганка 3 шт
	$eliksir[$i]['resource'][9]['kol'] = 3;
	$eliksir[$i]['resource'][10]['id'] = 17; //эльфийский цветок 3 шт
	$eliksir[$i]['resource'][10]['kol'] = 3;
	$eliksir[$i]['resource'][11]['id'] = 43; //сердце морийского тролля
	$eliksir[$i]['resource'][11]['kol'] = 1;
	$eliksir[$i]['resource'][12]['id'] = 33; //сердце морийской крысы
	$eliksir[$i]['resource'][12]['kol'] = 1;
	$eliksir[$i]['resource'][13]['id'] = 34; //голова морийской летучей мыши
	$eliksir[$i]['resource'][13]['kol'] = 1;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_1_2';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=2;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=24*60*60;
	$eliksir[$i]['item_id']=366;

	$i++;
	$eliksir[$i]['name']='Малый эликсир мудрости';
	$eliksir[$i]['alchemist']=250;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,20*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 3;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 6 шт
	$eliksir[$i]['resource'][1]['kol'] = 6;
	$eliksir[$i]['resource'][2]['id'] = 23; //девясил 3 шт
	$eliksir[$i]['resource'][2]['kol'] = 3;
	$eliksir[$i]['resource'][3]['id'] = 24; //чертополох 3 шт
	$eliksir[$i]['resource'][3]['kol'] = 3;
	$eliksir[$i]['resource'][4]['id'] = 21; //корень мандрагоры 3 шт
	$eliksir[$i]['resource'][4]['kol'] = 3;
	$eliksir[$i]['resource'][5]['id'] = 13; //троллья трава 3 шт
	$eliksir[$i]['resource'][5]['kol'] = 3;
	$eliksir[$i]['resource'][6]['id'] = 15; //белый гриб 3 шт
	$eliksir[$i]['resource'][6]['kol'] = 3;
	$eliksir[$i]['resource'][7]['id'] = 16; //древний мох 3 шт
	$eliksir[$i]['resource'][7]['kol'] = 3;
	$eliksir[$i]['resource'][8]['id'] = 18; //мухомор 3 шт
	$eliksir[$i]['resource'][8]['kol'] = 3;
	$eliksir[$i]['resource'][9]['id'] = 20; //корень женьшеня 3 шт
	$eliksir[$i]['resource'][9]['kol'] = 3;
	$eliksir[$i]['resource'][10]['id'] = 19; //поганка 3 шт
	$eliksir[$i]['resource'][10]['kol'] = 3;
	$eliksir[$i]['resource'][11]['id'] = 33; //сердце морийской крысы
	$eliksir[$i]['resource'][11]['kol'] = 3;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_11_24';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=2;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=24*60*60;
	$eliksir[$i]['item_id']=367;
	
	//Средние эликсиры характеристик
	$i++;
	$eliksir[$i]['name']='Средний эликсир силы';
	$eliksir[$i]['alchemist']=350;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,30*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 6;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 16 шт
	$eliksir[$i]['resource'][1]['kol'] = 16;
	$eliksir[$i]['resource'][2]['id'] = 18; //мухомор 10 шт
	$eliksir[$i]['resource'][2]['kol'] = 10;
	$eliksir[$i]['resource'][3]['id'] = 20; //корень женьшеня 3 шт
	$eliksir[$i]['resource'][3]['kol'] = 10;
	$eliksir[$i]['resource'][4]['id'] = 69; //хелицеры морийского паука 1 шт
	$eliksir[$i]['resource'][4]['kol'] = 1;
	$eliksir[$i]['resource'][5]['id'] = 73; //зуб морийского зомби
	$eliksir[$i]['resource'][5]['kol'] = 1;
    $eliksir[$i]['resource'][6]['id'] = 76; //кисть морийского кобольда
	$eliksir[$i]['resource'][6]['kol'] = 1;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_3_1';#item/****.gif
	$eliksir[$i]['str']=3;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=48*60*60;
	$eliksir[$i]['item_id']=1157;

	$i++;
	$eliksir[$i]['name']='Средний эликсир интеллекта';
	$eliksir[$i]['alchemist']=350;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,30*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 6;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 16 шт
	$eliksir[$i]['resource'][1]['kol'] = 16;
	$eliksir[$i]['resource'][2]['id'] = 21; //корень мандрагоры 10 шт
	$eliksir[$i]['resource'][2]['kol'] = 10;
	$eliksir[$i]['resource'][3]['id'] = 16; //древний мох 10 шт
	$eliksir[$i]['resource'][3]['kol'] = 10;
	$eliksir[$i]['resource'][4]['id'] = 72; //голова морийского зомби 1 шт
	$eliksir[$i]['resource'][4]['kol'] = 1;
	$eliksir[$i]['resource'][5]['id'] = 77; //ухо морийского кобольда
	$eliksir[$i]['resource'][5]['kol'] = 1;
    $eliksir[$i]['resource'][6]['id'] = 79; //рука морийского мертвяка
	$eliksir[$i]['resource'][6]['kol'] = 1;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_3_2';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=3;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=48*60*60;
	$eliksir[$i]['item_id']=1158;
	
	$i++;
	$eliksir[$i]['name']='Средний эликсир ловкости';
	$eliksir[$i]['alchemist']=350;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,30*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 6;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 16 шт
	$eliksir[$i]['resource'][1]['kol'] = 16;
	$eliksir[$i]['resource'][2]['id'] = 18; //мухомор 10 шт
	$eliksir[$i]['resource'][2]['kol'] = 10;
	$eliksir[$i]['resource'][3]['id'] = 17; //эльфийский цветок 10 шт
	$eliksir[$i]['resource'][3]['kol'] = 10;
	$eliksir[$i]['resource'][4]['id'] = 75; //голова морийского кобольда 1 шт
	$eliksir[$i]['resource'][4]['kol'] = 1;
	$eliksir[$i]['resource'][5]['id'] = 80; //нога морийского мертвяка
	$eliksir[$i]['resource'][5]['kol'] = 1;
    $eliksir[$i]['resource'][6]['id'] = 82; //лапа морийского корневика
	$eliksir[$i]['resource'][6]['kol'] = 1;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_3_3';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=3;
	$eliksir[$i]['dlit']=48*60*60;
	$eliksir[$i]['item_id']=1159;
	
	$i++;
	$eliksir[$i]['name']='Средний эликсир защиты';
	$eliksir[$i]['alchemist']=350;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,30*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 6;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 16 шт
	$eliksir[$i]['resource'][1]['kol'] = 16;
	$eliksir[$i]['resource'][2]['id'] = 21; //корень мандрагоры 10 шт
	$eliksir[$i]['resource'][2]['kol'] = 10;
	$eliksir[$i]['resource'][3]['id'] = 20; //корень женьшеня 10 шт
	$eliksir[$i]['resource'][3]['kol'] = 10;
	$eliksir[$i]['resource'][4]['id'] = 78; //голова морийского мертвяка 1 шт
	$eliksir[$i]['resource'][4]['kol'] = 1;
	$eliksir[$i]['resource'][5]['id'] = 83; //щупальца морийского корневика 1 шт
	$eliksir[$i]['resource'][5]['kol'] = 1;
    $eliksir[$i]['resource'][6]['id'] = 70; //лапа морийского паука 1 шт
	$eliksir[$i]['resource'][6]['kol'] = 1;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_3_4';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=3;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=48*60*60;
	$eliksir[$i]['item_id']=1160;
	
	$i++;
	$eliksir[$i]['name']='Средний эликсир выносливости';
	$eliksir[$i]['alchemist']=350;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,30*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 6;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 16 шт
	$eliksir[$i]['resource'][1]['kol'] = 16;
	$eliksir[$i]['resource'][2]['id'] = 18; //мухомор 10 шт
	$eliksir[$i]['resource'][2]['kol'] = 10;
	$eliksir[$i]['resource'][3]['id'] = 16; //древний мох 10 шт
	$eliksir[$i]['resource'][3]['kol'] = 10;
	$eliksir[$i]['resource'][4]['id'] = 77; //ухо морийского кобольда 1 шт
	$eliksir[$i]['resource'][4]['kol'] = 1;
	$eliksir[$i]['resource'][5]['id'] = 71; //паутина морийского корневика 1 шт
	$eliksir[$i]['resource'][5]['kol'] = 1;
    $eliksir[$i]['resource'][6]['id'] = 83; //щупальца морийского корневика 1 шт
	$eliksir[$i]['resource'][6]['kol'] = 1;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_3_5';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=3;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=48*60*60;
	$eliksir[$i]['item_id']=1161;
	
		$i++;
	$eliksir[$i]['name']='Средний эликсир мудрости';
	$eliksir[$i]['alchemist']=350;
	$eliksir[$i]['clevel']=15;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,30*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 25; //вода
	$eliksir[$i]['resource'][0]['kol'] = 6;
	$eliksir[$i]['resource'][1]['id'] = 14; //папортник 16 шт
	$eliksir[$i]['resource'][1]['kol'] = 16;
	$eliksir[$i]['resource'][2]['id'] = 21; //корень мандрагоры 10 шт
	$eliksir[$i]['resource'][2]['kol'] = 10;
	$eliksir[$i]['resource'][3]['id'] = 17; //эльфийский цветок 10 шт
	$eliksir[$i]['resource'][3]['kol'] = 10;
	$eliksir[$i]['resource'][4]['id'] = 81; //глаз морийского корневика 1 шт
	$eliksir[$i]['resource'][4]['kol'] = 1;
	$eliksir[$i]['resource'][5]['id'] = 71; //паутина морийского паука 1 шт
	$eliksir[$i]['resource'][5]['kol'] = 1;
    $eliksir[$i]['resource'][6]['id'] = 74; //кость морийского зомби 1 шт
	$eliksir[$i]['resource'][6]['kol'] = 1;
	$eliksir[$i]['weight']=2;
	$eliksir[$i]['img']='eliksir/phial_3_6';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=3;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=48*60*60;
	$eliksir[$i]['item_id']=1162;
		/*
	$i++;
	$eliksir[$i]['name']='Великий Эликсир Света';
	$eliksir[$i]['alchemist']=0;
	$eliksir[$i]['clevel']=0;
	$eliksir[$i]['hp']=0;
	$eliksir[$i]['mp']=0;
	$eliksir[$i]['stm']=0;
	$eliksir[$i]['time']=max(10*60,20*60*60-getCraftLevel($char['user_id'],2)*60);
	$eliksir[$i]['resource'][0]['id'] = 48; //луч света
	$eliksir[$i]['resource'][0]['kol'] = 200;
	$eliksir[$i]['weight']=10;
	$eliksir[$i]['img']='eliksir/phial-K-03';#item/****.gif
	$eliksir[$i]['str']=0;
	$eliksir[$i]['dex']=0;
	$eliksir[$i]['spd']=0;
	$eliksir[$i]['vit']=0;
	$eliksir[$i]['ntl']=0;
	$eliksir[$i]['pie']=0;
	$eliksir[$i]['dlit']=0;
	$eliksir[$i]['item_id']=407;
	*/
	return $eliksir;
}
?>