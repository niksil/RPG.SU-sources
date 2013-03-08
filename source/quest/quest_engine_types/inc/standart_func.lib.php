<?PHP
//Используется для вставки в тексты движка различных слов
	function new_word($type, $num, $pos)
	{
		global $quest_user;
		switch ($type)
		{
			case "oh":case "ent_w": 
			{
				//$text="вай-вай"; 
				$words=myquery("SELECT word FROM quest_engine_words WHERE type='".$type."'");
				jump_random_query($words);
				list($text)=mysql_fetch_array($words);
				break;
			}
			//case "ent_w": $text="короче говоря"; break;
			case "obr": 
			{
				//$text="сэр"; 
				global $user_id;
				list($sex)=mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=".$user_id.""));
				if($sex=="male")
					$words=myquery("SELECT word FROM quest_engine_words WHERE SUBSTRING(word,1,1)='m' AND type='obr'");			
				else
					$words=myquery("SELECT word FROM quest_engine_words WHERE SUBSTRING(word,1,1)='f' AND type='obr'");			
				jump_random_query($words);
				list($text)=mysql_fetch_array($words);
				$text=substr($text,2);
				break;
			}
			
			case "gold": 
			{
				if($quest_user['quest_reward']%10==1)
					$text=$num.' золотой';
				else 	
					$text=$num.' золотых';			
				break;
			}	
			case "exp":
			{
				if($num%10==0 OR $num%10>=5)							
					$text=$num.' очков опыта';
				elseif($num%10==1) $text=$num.' очко опыта';
					else $text=$num.' очка опыта';	
				break;	
			}
			case "wins":
			{
				if($num%10==0 OR $user['par1_value']%10>=5)							
					$text=$num.' побед';
				elseif($num%10==1) $text=$num.' победу';
				else $text=$num.' победы';			
				break;
			}
			case "map_name":
			{
				$text=$num;
				if(substr_count($num,'елe'))  $text.='e';
				break;				
			}
			case "items":
			{
				switch ($num)
				{
					case 1:$text="хорошее оружие";break;
					case 2:$text="хорошие кольца";break;
					case 3:$text="хорошие артефакты";break;
					case 4:$text="хорошие щиты";break;
					case 5:$text="хорошие доспехи";break;
					case 6:$text="хорошие шлемы";break;
					case 7:$text="хорошая магия";break;
					case 8:$text="хорошие пояса";break;
					case 9:$text="хорошие ожерелья";break;
					case 10:$text="хорошие перчатки";break;
					case 11:$text="хорошая обувь";break;
					case 12:$text="хорошие бутылочки :)";break;		
					default:$text="неизвестный тип (".$num.")";break;
				}
				break;
			}
			default: return ""; break;
		}
		if($pos==1)
		{
			$text[0]=strtoupper($text[0]);
		}
		return $text;
	}
//понято что	
function parameter_rus_name($npc_par_name)
{
	switch($npc_par_name)
	{
		case 'npc_max_hp': return ', максимальное здоровье которого не меньше ';break;
		case'npc_max_mp':return ', максимальная мана которого не меньше ';break;
		case 'npc_str':return ', сила которого не меньше ';break;
		case 'npc_dex':return ', выносливость (а не ловкость) которого не меньше ';break;
		case 'npc_wis':return ', ловкость (а не мудрость) которого не меньше ';break;
		case 'npc_basefit':return ', защита (а не выносливость) которого не меньше ';break;
		case 'npc_basedef':return ', мудрость (а не защита) которого не меньше ';break;
		case 'npc_exp':return ', опыта за которого дают не меньше ';break;
		case 'npc_gold':return ', золота за которого дают не меньше ';break;
		case 'npc_ntl':return ', интеллект которого не меньше ';break;
		case 'npc_level':return ', уровень которого не меньше ';break;
		default: return ''; break;
	}
}
//------------------------------------------------------
function item_par_name($name)
{
	switch ($name)
	{
		case 'dstr': return 'силу'; break;
		case 'ddex': return 'выносливость (ловкость)'; break;
		case 'dpie': return 'ловкость (защиту)'; break;
		case 'dvit': return 'защиту (выносливость)'; break;
		case 'dspd': return 'мудрость'; break;
		case 'dntl': return 'интеллект'; break;
	}		
}
//---------------------------------------------------
/*function item_type_name($type)
{
	switch ($type)
	{
		case 1:return"хорошее оружие";break;
		case 2:return"хорошие кольца";break;
		case 3:return"хорошие артефакты";break;
		case 4:return"хорошие щиты";break;
		case 5:return"хорошие доспехи";break;
		case 6:return"хорошие шлемы";break;
		case 7:return"хорошая магия";break;
		case 8:return"хорошие пояса";break;
		case 9:return"хорошие ожерелья";break;
		case 10:return"хорошие перчатки";break;
		case 11:return"хорошие ботинки";break;
		case 12:return"хорошие бутылочки :)";break;		
	}
}*/
//--------------------------------
?>