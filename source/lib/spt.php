<?

if (function_exists("start_debug")) start_debug();

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

$output_string = '';

if (isset($errror))
{
	switch ($errror)
	{
		case '':
			$output_string.='Битва не состоялась! Возможные варианты:<br>1. Игрок занят или его нет на этой гексе.<br>2. У вас одинаковые IP.<br />';
		break;

		case 'ip':
			$output_string.='Битва не состоялась!<br>У вас одинаковые IP-адреса!<br />';
		break;

		case 'level':
			$output_string.='Битва не состоялась!<br>У тебя слишком высокий уровень!<br />';
		break;

		case 'clan_id':
			$output_string.='Битва не состоялась!<br>Вы в одном клане!<br />';
		break;

		case 'lost':
			$output_string.='Ты не можешь присоединиться,<br>так как ты уже '.echo_sex('участвовал','участвовала').' в этой битве<br />';
		break;

		case 'max_number':
			$output_string.='Ты не можешь присоединиться,<br>В битве из твоего клана уже участвуют максимальное количество (20) игроков<br />';
		break;

		case 'npc':
			$output_string.='Ты не можешь присоединиться,<br>Игрок занят в битве с ботом (NPC)<br />';
		break;

		case 'clan':
			$output_string.='Это не клановая битва<br />';
		break;

		case 'duel':
			$output_string.='Ты не можешь присоединиться к дуэли<br />';
		break;

		case 'boy':
			$output_string.='Этот тип боя запрещен на этой карте<br />';
		break;

		case 'shakal':
			$output_string.='Противник слишком слаб! Ему надо подлечиться! Бой отменяется.<br />';
		break;

		case 'sred_level':
			$output_string.='Ты не подходишь для битвы<br>из-за несоответствия среднему<br>уровню игроков в битве<br />';
		break;

		case 'arcomage_active':
			$output_string.='Твоего противника нет в игре<br />';
		break;

		case 'arcomage_call_money':
			$output_string.='У тебя недостаточно денег для игры<br />';
		break;

		case 'arcomage_player_money':
			$output_string.='У твоего противника недостаточно денег для игры<br />';
		break;

		case 'full_inv':
			$output_string.='Ты не можешь поднять предмет! У тебя недостаточно свободного места в инвентаре<br />';
		break;

		case 'wrong_clan':
			$output_string.='Ты не можешь поднять предмет! Этот предмет не для твоего клана"<br />';
		break;

		case 'late':
			$output_string.='К боям можно присоединяться только до окончания 3 хода!<br />';
		break;

		case 'max_inv':
			$output_string.='Невозможно поднять предмет!<br />';
		break;

		default:
			$output_string.=urldecode($errror);
		break;
	}
}

/*
if (isset($log))
{
	$del=myquery("delete from game_battles WHERE attacker_id=$user_id");
	$output_string.='Лог боевых сообщений очищен<br /><br />';
}
*/

if (isset($lek))
{
	$prov=myquery("SELECT game_users.name FROM game_users,game_users_func WHERE game_users.user_id='$lek' 
	AND game_users.user_id IN (SELECT user_id FROM game_users_map WHERE  map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' and map_name='".$char['map_name']."')
	AND game_users.user_id=game_users_func.user_id
	AND game_users_func.func_id!='1'
	limit 1
	");
	if (mysql_num_rows($prov) and $char['MS_LEK']>0)
	{
		$us=mysql_fetch_array($prov);
		if (!isset($save))
		{
			$output_string.='<form action="" method="post">';

			$output_string.='<table cellpadding="0" cellspacing="5" border="0">';
			$output_string.='<tr><td>Лекарь '.$char['MS_LEK'].' уровень</td></tr>';
			$output_string.='<tr><td>Исцелить игрока: <b>'.$us['name'].'</b><br>';

			$output_string.='<select name="lekar">';

			$i=1;
			while ($i<=$char['MS_LEK'])
			{
				$hp=0;
				for ($cikl=1; $cikl<=$i; $cikl++)
				{
					$hp=$hp+$cikl;
				}
				$hp=$hp*3;
				$mana=$i*5;
				$energy=$i*5;
				$output_string.='<option value='.$i.'>Исцелить '.$hp.' жизни за '.$energy.' энергии и '.$mana.' маны</option>';
				$i++;
			}
			$output_string.='</select>';

			$output_string.='<br><br><input name="save" type="submit" value="Исцелить"><input name="save" type="hidden" value=""></td></tr></table></form>';
		}
		else
		{

			if (isset($lekar))
			{
				$i=$lekar;
				$hp=0;
				for ($cikl=1; $cikl<=$i; $cikl++)
				{
					$hp=$hp+$cikl;
				}
				$hp=$hp*3;
				$mana=$i*5;
				$energy=$i*5;

				if ($char['MP']>=$mana AND $char['STM']>=$energy)
				{
					$output_string.='Ты '.echo_sex('исцелил','исцелила').' у '.$us['name'].' '.$hp.' жизни за '.$energy.' энергии и '.$mana.' маны';
					$upd=myquery("update game_users set MP=MP-$mana where name='".$char['name']."' limit 1");
					$upd=myquery("update game_users set STM=STM-$energy where name='".$char['name']."' limit 1");
					$upd=myquery("update game_users set HP=HP+$hp where name='".$us['name']."' limit 1");
					$upd=myquery("update game_users set HP=HP_MAX where name='".$us['name']."' AND HP>HP_MAX limit 1");
				}
				else
				{
					$output_string.='Не хватает маны или энергии';
				}
			}
		}
	}
	else
	{
		$output_string.='Игрока нет на этой гексе';
	}
}

if (isset($vor))
{
	if ($char['clan_id']!=1)
	   $prov=myquery("SELECT game_users.* FROM game_users,game_users_func WHERE game_users.name='$vor'
	   AND game_users.user_id IN (SELECT user_id FROM game_users_map WHERE  map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' and map_name='".$char['map_name']."')
	   AND game_users.user_id=game_users_func.user_id
	   AND game_users_func.func_id!='1'
	   limit 1");
	else
		$prov=myquery("select * from game_users where name='$vor' limit 1");

	if (mysql_num_rows($prov) and $char['MS_VOR']>0)
	{
		$user=mysql_fetch_array($prov);
		$output_string.='<table cellpadding="0" cellspacing="5" border="0">';
		$output_string.='<tr><td>Вор '.$char['MS_VOR'].' уровень</td></tr>';
		$output_string.='<tr><td><br>Надетые предметы:</td></tr><tr><td>';
		$lim=1;
		$sel=myquery("select id from game_items where user_id=".$user['user_id']." and used!=0 and priznak=0");
		while($it=mysql_fetch_array($sel))
		{
			$Item = new Item($it['id']);
			if ($Item->getFact('type')>=90) continue;
			if ($lim>$char['MS_VOR']) continue;
			$lim++;
			$Item->hint(0,0,'<a ');
			$output_string.='<img src="http://'.IMG_DOMAIN.'/item/'.$Item->getFact('img').'.gif" width="30" height="30" border="0"></a>';
		}
		$output_string.='</td></tr>';

		if ($char['MS_VOR']>=8)
		{
			$output_string.='<tr><td><br>Предметы в рюкзаке:</td></tr><tr><td>';
			$sel=myquery("select id from game_items where user_id=".$user['user_id']." and used=0 and priznak=0");
			while($it=mysql_fetch_array($sel))
			{
				$Item = new Item($it['id']);
				if ($Item->getFact('type')>=90) continue;
				$Item->hint(0,0,'<a ');
				$output_string.='<img src="http://'.IMG_DOMAIN.'/item/'.$Item->getFact('img').'.gif" width="30" height="30" border="0"></a>';
			}
			$output_string.='</td></tr>';
		}
		$output_string.='</table>';
	}
	else
	{
		$output_string.='Игрока нет на этой гексе';
	}
}

if (isset($menu))
{
	$prov=myquery("select game_users.*,game_users_func.func_id from game_users,game_users_map,game_users_func where game_users.user_id='$menu' and game_users.user_id=game_users_map.user_id AND game_users_map.map_xpos='".$char['map_xpos']."' and game_users_map.map_ypos='".$char['map_ypos']."' and game_users_map.map_name='".$char['map_name']."' AND game_users.user_id=game_users_func.user_id");
	if (mysql_num_rows($prov))
	{
		$up=mysql_fetch_array($prov);
		$output_string.='<table cellpadding="0" cellspacing="5" border="0">';
		$output_string.='<tr><td colspan=2 align="center"><b>';

		if ($up['clan_id']<>0) $output_string.='<a href="http://'.DOMAIN.'/view/?clan='.$up['clan_id'].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/clan/'.$up['clan_id'].'.gif" border=0></a> ';
		$output_string.='<font face=verdana size=2 color=ff0000>'.$up['name'].'</font> ('.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$up['race'].""),0,0).' '.$up['clevel'].' уровня)</b><br> Последнее действие: <b>'.get_delay_reason(get_delay_reason_id($up['user_id'])).'</b>';
		if ($up['vsadnik']!='0') $output_string.='<br><font face=verdana size=2 color=ff0000><b>Всадник!</b></font>';
		$output_string.='<br> Возможные действия:</td></tr>';

		$output_string.='<tr><td><a href="http://'.DOMAIN.'/view/?userid='.$up["user_id"].'" target="_blank"">Информация</a></td></tr>';
		if ($up['clan_id']!='0') $output_string.='<tr><td><a href="http://'.DOMAIN.'/view/?clan='.$up['clan_id'].'" target="_blank"">Информация о клане</a></td></tr>';
		if ($up['name'] != $char['name']) $output_string.='<tr><td><a href="?func=pm&pm=write&komu='.$up["name"].'">Послать сообщение</a></td></tr>';

		$num=0;
		$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id = '".$char['map_name']."'"));
		if ($char['MS_LEK']!='0' and ($up['func_id']!='1')) $output_string.='<tr><td><a href="?func=main&lek='.$up['user_id'].'">Лечить (Специализация '.$char['MS_LEK'].' уровня)</a></td></tr>';
		if ($char['MS_VOR']!='0') $output_string.='<tr><td><a href="?func=main&vor='.$up['name'].'">Воровство (Специализация '.$char['MS_VOR'].' уровня)</a></td></tr>';
		$output_string.='</table>';
	}
	else
	{
		$output_string.='<b><font face=verdana size=1 color=ff0000>Игрок перешел на другую гексу</font></b>';
	}
}


if (isset($_GET['npc_info']))
{
	$npc_info=(int)$_GET['npc_info'];
    if ($npc_info>0)
    {
	    $Npc_object = new Npc($npc_info);
	    $output_string.=$Npc_object->create_output();
    }
}

/*
if (!isset($npc_info) and !isset($menu) and !isset($errror) and !isset($lek) and !isset($vor))
{
	$result_battles = myquery("SELECT type, map_name, map_xpos, map_ypos, contents, post_time FROM game_battles WHERE attacker_id=$user_id ORDER BY post_time DESC LIMIT 5");
	echo '5 последних боевых сообщений</font><br /><br />
	<table cellpadding="0" cellspacing="5" border="0">';
	if ($result_battles!=false AND mysql_num_rows($result_battles) > 0)
	{
		while ($battle = mysql_fetch_array($result_battles))
		{
			echo '<tr><td><font color=#C0FFC0>'.date("H:i",$battle['post_time']).'</font></td><td>'.$battle['contents'].'</td></tr>';
		}
		echo '<tr><td colspan="2" align="center"><a href="act.php?func=main&log">Очистить лог</a></td></tr>';
	}
	echo '</table>';

}
*/
if ($output_string!='')
{
	QuoteTable('open');
	echo $output_string;
	QuoteTable('close');
}

if (function_exists("save_debug")) save_debug();

?>