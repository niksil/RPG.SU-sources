<?

if (function_exists("start_debug")) start_debug();

$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
if (mysql_num_rows($userban))
{
	$userr = mysql_fetch_array($userban);
	$min = ceil(($userr['time']-time())/60);
	echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено заниматься у тренеров!';
	{if (function_exists("save_debug")) save_debug(); exit;}
}

$img='http://'.IMG_DOMAIN.'/race_table/gnom/table';

echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';

if ($town!=0)
{
	list($town_now) = mysql_fetch_array(myquery("SELECT town FROM game_map WHERE name=".$char['map_name']." AND xpos=".$char['map_xpos']." AND ypos=".$char['map_ypos'].""));
	if ($town_now!=$town) exit;

	$town_now = mysql_fetch_array(myquery("SELECT * FROM game_gorod WHERE town=$town"));
	$race = mysql_fetch_array(myquery("SELECT * FROM game_har WHERE id=".$char['race'].""));

	$ren = array();
	$ren['STR']='har_1';
	$ren['SPD']='har_2';
	$ren['NTL']='har_3';
	$ren['PIE']='har_4';
	$ren['VIT']='har_5';
	$ren['DEX']='har_6';

	$ren['MS_ART']='ms_1';
	$ren['MS_VOR']='ms_2';
	$ren['MS_KULAK']='ms_3';
	$ren['MS_WEAPON']='ms_4';
	$ren['MS_LUK']='ms_5';
	$ren['MS_PARIR']='ms_6';
	$ren['MS_KUZN']='ms_7';
	$ren['MS_LEK']='ms_8';
	$ren['MS_VSADNIK']='ms_9';
	$ren['MS_SWORD']='ms_10';
	$ren['MS_AXE']='ms_11';
	$ren['MS_SPEAR']='ms_12';
	$ren['MS_THROW']='ms_13';

	$ren['skill_war']='mag_1';
	$ren['skill_music']='mag_2';
	$ren['skill_cook']='mag_3';
	$ren['skill_art']='mag_4';
	$ren['skill_explor']='mag_5';
	$ren['skill_craft']='mag_6';
	$ren['skill_card']='mag_7';
	$ren['skill_pet']='mag_8';
	$ren['skill_uknow']='mag_9';

	$ar = array();
	$ar['max']['MS_KUZN']=11;
	$ar['max']['MS_LUK']=10;
	$ar['max']['MS_THROW']=10;
	$ar['max']['skill_war']=$race['skill_war'];
	$ar['max']['skill_music']=$race['skill_music'];
	$ar['max']['skill_cook']=$race['skill_cook'];
	$ar['max']['skill_art']=$race['skill_art'];
	$ar['max']['skill_explor']=$race['skill_explor'];
	$ar['max']['skill_craft']=$race['skill_craft'];
	$ar['max']['skill_card']=$race['skill_card'];
	$ar['max']['skill_pet']=$race['skill_pet'];
	$ar['max']['skill_uknow']=$race['skill_uknow'];


	$ar['ren']['har_1']='STR';
	$ar['town']['har_1']='STR';
	$ar['name']['har_1']='СИЛА';
	$ar['ren']['har_2']='SPD';
	$ar['town']['har_2']='SPD';
	$ar['name']['har_2']='МУДРОСТЬ';
	$ar['ren']['har_3']='NTL';
	$ar['town']['har_3']='NTL';
	$ar['name']['har_3']='ИНТЕЛЛЕКТ';
	$ar['ren']['har_4']='PIE';
	$ar['town']['har_4']='PIE';
	$ar['name']['har_4']='ЛОВКОСТЬ';
	$ar['ren']['har_5']='VIT';
	$ar['town']['har_5']='VIT';
	$ar['name']['har_5']='ЗАЩИТА';
	$ar['ren']['har_6']='DEX';
	$ar['town']['har_6']='DEX';
	$ar['name']['har_6']='ВЫНОСЛИВОСТЬ';

	$ar['ren']['ms_1']='MS_ART';
	$ar['town']['ms_1']='MS_ART';
	$ar['name']['ms_1']='ЭКСПЕРТ АРТЕФАКТОВ';
	$ar['ren']['ms_2']='MS_VOR';
	$ar['town']['ms_2']='MS_VOR';
	$ar['name']['ms_2']='ВОРОВСТВО';
	$ar['ren']['ms_3']='MS_KULAK';
	$ar['town']['ms_3']='MS_KULAK';
	$ar['name']['ms_3']='МАСТЕР КУЛАЧНОГО БОЯ';
	$ar['ren']['ms_4']='MS_WEAPON';
	$ar['town']['ms_4']='MS_WEAPON';
	$ar['name']['ms_4']='ЭКСПЕРТ ВОИНСКИХ УМЕНИЙ';
	$ar['ren']['ms_5']='MS_LUK';
	$ar['town']['ms_5']='MS_LUK';
	$ar['name']['ms_5']='МАСТЕР СТРЕЛКОВОГО ОРУЖИЯ';
	$ar['ren']['ms_6']='MS_PARIR';
	$ar['town']['ms_6']='MS_PARIR';
	$ar['name']['ms_6']='ПАРИРОВАНИЕ';
	$ar['ren']['ms_7']='MS_KUZN';
	$ar['town']['ms_7']='MS_KUZN';
	$ar['name']['ms_7']='КУЗНЕЦ';
	$ar['ren']['ms_8']='MS_LEK';
	$ar['town']['ms_8']='MS_LEK';
	$ar['name']['ms_8']='ЛЕКАРЬ';
	$ar['ren']['ms_9']='MS_VSADNIK';
	$ar['town']['ms_9']='MS_VSADNIK';
	$ar['name']['ms_9']='ВСАДНИК';
	$ar['ren']['ms_10']='MS_SWORD';
	$ar['town']['ms_10']='MS_SWORD';
	$ar['name']['ms_10']='МАСТЕР РУБЯЩЕГО ОРУЖИЯ';
	$ar['ren']['ms_11']='MS_AXE';
	$ar['town']['ms_11']='MS_AXE';
	$ar['name']['ms_11']='МАСТЕР ДРОБЯЩЕГО ОРУЖИЯ';
	$ar['ren']['ms_12']='MS_SPEAR';
	$ar['town']['ms_12']='MS_SPEAR';
	$ar['name']['ms_12']='МАСТЕР КОЛЮЩЕГО ОРУЖИЯ';
	$ar['ren']['ms_13']='MS_THROW';
	$ar['town']['ms_13']='MS_THROW';
	$ar['name']['ms_13']='МАСТЕР МЕТАТЕЛЬНОГО ОРУЖИЯ';

	$ar['ren']['mag_1']='skill_war';
	$ar['town']['mag_1']='WAR';
	$ar['name']['mag_1']='ВОИН';
	$ar['ren']['mag_2']='skill_music';
	$ar['town']['mag_2']='MUSIC';
	$ar['name']['mag_2']='БАРД';
	$ar['ren']['mag_3']='skill_cook';
	$ar['town']['mag_3']='COOK';
	$ar['name']['mag_3']='ВОЛШЕБНИК';
	$ar['ren']['mag_4']='skill_art';
	$ar['town']['mag_4']='ART';
	$ar['name']['mag_4']='ЛУЧНИК';
	$ar['ren']['mag_5']='skill_explor';
	$ar['town']['mag_5']='EXPLOR';
	$ar['name']['mag_5']='ПАЛЛАДИН';
	$ar['ren']['mag_6']='skill_craft';
	$ar['town']['mag_6']='CRAFT';
	$ar['name']['mag_6']='ВАРВАР';
	$ar['ren']['mag_7']='skill_card';
	$ar['town']['mag_7']='CARD';
	$ar['name']['mag_7']='ВОР';
	$ar['ren']['mag_8']='skill_pet';
	$ar['town']['mag_8']='PET';
	$ar['name']['mag_8']='ДРУИД';
	$ar['ren']['mag_9']='skill_uknow';
	$ar['town']['mag_9']='UNKNOW';
	$ar['name']['mag_9']='РАЗБОЙНИК';

	//ar[max] - максимальные значения прокачки навыков и специализаций, иначе по умолчанию максимум = 15
	//ar[ren] - перевод параметра $_GET в поле game_users
	//ar[town] - перевод параметра $_GET в поле game_gorod

	function check_training($par)
	{
		global $char,$town_now,$ar;
		$max_ms = 15;
		switch ($ar['ren'][$par])
		{
			case 'STR': {if ($char['bound']>0 AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'SPD': {if ($char['bound']>0 AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'NTL': {if ($char['bound']>0 AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'PIE': {if ($char['bound']>0 AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'VIT': {if ($char['bound']>0 AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'DEX': {if ($char['bound']>0 AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			default:
			{
				if (!isset($town_now[$ar['town'][$par]])) return 0;
				if (!isset($char[$ar['ren'][$par]])) return 0;
				if ($town_now[$ar['town'][$par]]!=1) return 0;
				if ($char['exam']<=0) return 0;
				if (isset($ar['max'][$ar['ren'][$par]]) AND $char[$ar['ren'][$par]]>=$ar['max'][$ar['ren'][$par]]) return 0;
				elseif ($char[$ar['ren'][$par]]==$max_ms) return 0;

				if (substr($par,0,3)=='mag')
				{
					return 3;
				}
				else
				{
					return 2;
				}
			}
		}
		return 0;
	}

	function training($par)
	{
		global $char,$ar,$town_now,$option;
		$check = check_training($par);
		$query_string = '';
		if ($check==1) //повышаем характеристики
		{
			$par_new = $ar['ren'][$par];
			$query_string = "UPDATE game_users SET bound=bound-1,$par_new=$par_new+1,".$par_new."_MAX=".$par_new."_MAX+1";
			if ($par_new=='NTL') { $query_string.=",MP_MAX=MP_MAX+10";}
			if ($par_new=='PIE') { $query_string.=",STM_MAX=STM_MAX+10";}
			if ($par_new=='DEX') { $query_string.=",HP_MAX=HP_MAX+10,HP_MAXX=HP_MAXX+10,CC=CC+2";}
			$query_string.=" WHERE user_id=".$char['user_id']."";
		}
		elseif ($check==2 OR $check==3) //повышаем специализацию или магические навыки
		{
			$par_new = $ar['ren'][$par];
			$query_string = "UPDATE game_users SET exam=exam-1,$par_new=$par_new+1";
			$query_string.=" WHERE user_id=".$char['user_id']."";
		}
		if ($query_string!='')
		{
			myquery($query_string);
			echo'<center><b><font face=verdana size=2 color=ff0000>';
			if ($check==1)
			{
				echo'Ты '.echo_sex('повысил','повысила').' характеристику '.$ar['name'][$par];
			}
			elseif ($check==2)
			{
				echo'Ты '.echo_sex('повысил','повысила').' специализацию '.$ar['name'][$par];
			}
			elseif ($check==3)
			{
				echo'Ты '.echo_sex('повысил','повысила').' уровень магической школы '.$ar['name'][$par];
				list($new_skill) = mysql_fetch_array(myquery("SELECT $par_new FROM game_users WHERE user_id=".$char['user_id'].""));
				//надо еще сделать game_spets_item
				//$update_users_acquirement = myquery("UPDATE game_users SET exam=$new_exam, skill_war=$new_skill_war, skill_music=$new_skill_music, skill_cook=$new_skill_cook, skill_art=$new_skill_art, skill_explor=$new_skill_explor, skill_craft=$new_skill_craft, skill_card=$new_skill_card, skill_pet=$new_skill_pet, skill_uknow=$new_skill_uknow WHERE user_id=$user_id LIMIT 1");
				$update_spet=myquery("select * from game_spets where ".strtolower($ar['town'][$par])."=$new_skill");
				$print='У тебя появились новые знания магической школы: ';
				while ($spets_m=mysql_fetch_array($update_spet))
				{
					$proverka=myquery("select id from game_spets_item where name='".$spets_m['name']."' and user_id=".$char['user_id']."");
					if (!mysql_num_rows($proverka))
					{
						$dob=myquery("insert into game_spets_item (user_id,name,indx,deviation,mode,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation) values (".$char['user_id'].",'".$spets_m['name']."','".$spets_m['indx']."','".$spets_m['deviation']."','".$spets_m['mode']."','".$spets_m['img']."','".$spets_m['mana']."','".$spets_m['hp']."','".$spets_m['stm']."','".$spets_m['mana_deviation']."','".$spets_m['hp_deviation']."','".$spets_m['stm_deviation']."','".$spets_m['indx_mp']."','".$spets_m['indx_mp_deviation']."','".$spets_m['indx_stm']."','".$spets_m['indx_stm_deviation']."')");
						echo '<br /></font></b>Ты '.echo_sex('выучил','выучила').' новое заклинание: <span style="color:#80FF80;font-weight:bold;text-size:13px;">'.$spets_m['mode'].'</span>';
					}
				}
			}
			echo '</font></b></center><meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';
	   }
	}

	if (isset($_GET['do']))
	{
        /*
		if ($_GET['do']=='level_up' AND $char['clevel']<40)
		{
			$clevel=$char['clevel'];
            $new_clevel = get_new_level($clevel);

            if ($char['EXP'] >= $new_clevel)
			{
                $gp=0; $nav=0; $xar=0;
                
                if ($clevel >= 0 and $clevel < 9) { $gp=50; $nav=1; $xar=2;}
                elseif ($clevel == 9) {$gp=300; $nav=3; $xar=3;}
                elseif ($clevel >= 10 and $clevel < 19) {$gp=100; $nav=1; $xar=2;}
                elseif ($clevel == 19) {$gp=500; $nav=3; $xar=3;}
                elseif ($clevel >= 20 and $clevel < 29){ $gp=200; $nav=1; $xar=2;}
                elseif ($clevel == 29) {$gp=1000; $nav=3; $xar=3;}
                elseif ($clevel >= 30 and $clevel < 39){ $gp=300; $nav=1; $xar=2;}
                elseif ($clevel == 39) {$gp=1500; $nav=3; $xar=3;}
                
            	$update=myquery("UPDATE game_users SET clevel=clevel+1, bound=bound+'$xar', exam=exam+'$nav', EXP=EXP-'$new_clevel', GP=GP+'$gp' WHERE user_id=$user_id LIMIT 1");
				setGP($user_id,$gp,21);
				echo'<br><center><b><font face=verdana size=2 color=ff0000>Ты '.echo_sex('повысил','повысила').' уровень!<br>Ты '.echo_sex('получил','получила').': '.$gp.' золотых, '.$nav.' дополнительных навыков и '.$xar.' дополнительных характеристик!<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br><br>';
                
                $char['clevel']++;
			}
			else
			{
				echo'У тебя недостаточно опыта чтобы прокачать уровень!<br><br><input type="button" value="Выйти" onClick=location.replace("town.php?option='.$option.'")>';
			}
		}
		else
		{
			training($_GET['do']);
		}
        */
        training($_GET['do']);
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	echo'<table border=0 cellspacing="1" cellpadding="2"><tr><td valign=top>';
	OpenTable('title');

	echo'<table cellpadding="1" cellspacing="1" border="0" width="260"><tr><td>
	<font color="#FFFF00"><b>Характеристики:</font> (<font color="#FF0000">' . $char['bound'] . '</font>)</b><br><br>
	<table cellpadding="0" cellspacing="0" border="0" width="260"><tr><td>';
	
	function print_har($par)
	{
		global $town_now,$ar,$char,$option;
		if ($town_now[$ar['town'][$par]]>0)
		{
			echo '<tr><td width="260">'.$ar['name'][$par].'</td><td width="50"><div align="right">' . $char[$ar['ren'][$par]] . ' ';
			if(check_training($par)>0)
			{
				echo'<a href=town.php?option='.$option.'&do='.$par.'><img src="http://'.IMG_DOMAIN.'/nav/up.gif" border=0></a>';
			}
		}
	}

	print_har($ren['STR']);
	print_har($ren['NTL']);
	print_har($ren['PIE']);
	print_har($ren['VIT']);
	print_har($ren['DEX']);
	print_har($ren['SPD']);

	echo'</div></td></tr></table>';
	echo'</td></tr></table>';
	OpenTable('close');

	OpenTable('title');
	echo' <table width="260" border="0" cellspacing="1" cellpadding="1"><tr><td height="21">';
	echo' <font color="#FFFF00"><b>Навыки и специализации:</font> (<font color="#FF0000">' . $char['exam'] . '</font>)';
	echo' <table cellpadding="1" cellspacing="0" align="center" border="0" width="260">';

	echo'<tr><td colspan=2>&nbsp;</td></tr>';
	echo'<tr><td colspan=2><font color="#FFFF00"><b>Магические школы:</b></font></td></tr>';

	function print_magic($par)
	{
		global $town_now,$ar,$char,$option;
		if ($town_now[$ar['town'][$par]]>0 and $ar['max'][$ar['ren'][$par]]>0)
		{
			echo '<tr><td width="260">';
			if ($ar['max'][$ar['ren'][$par]]>=30) echo '<font color=ff0000><b>Магия школы '.$ar['name'][$par].'</b></font>';
			elseif ($ar['max'][$ar['ren'][$par]]>=10) echo 'Магия школы '.$ar['name'][$par].'';
			echo '</td><td width="50"><div align="right">'.$char[$ar['ren'][$par]].' ';
			if(check_training($par)>0)
			{
				echo'<a href=town.php?option='.$option.'&do='.$par.'><img src="http://'.IMG_DOMAIN.'/nav/up1.gif" border=0></a>';
			}
			echo'</div></td></tr>';
		}
	}
	
	print_magic($ren['skill_war']);
	print_magic($ren['skill_cook']);
	print_magic($ren['skill_art']);
	print_magic($ren['skill_explor']);
	print_magic($ren['skill_craft']);
	print_magic($ren['skill_card']);
	print_magic($ren['skill_uknow']);
	print_magic($ren['skill_music']);
	print_magic($ren['skill_pet']);

	echo'<tr><td colspan=2>&nbsp;</td></tr>';
	echo'<tr><td colspan=2><font color="#FFFF00"><b>Специализация:</b></font></td></tr>';

	function print_ms($par)
	{
		global $town_now,$ar,$char,$option;
		if ($town_now[$ar['town'][$par]]>0)
		{
			echo '<tr><td width="130">'.$ar['name'][$par].'</td><td width="108"><div align="right">' . $char[$ar['ren'][$par]] . ' ';
			if(check_training($par)>0)
			{
				echo'<a href=town.php?option='.$option.'&do='.$par.'><img src="http://'.IMG_DOMAIN.'/nav/up1.gif" border=0></a>';
			}
			echo'</div></td></tr>';
		}
	}
	
	print_ms($ren['MS_KULAK']);
	print_ms($ren['MS_WEAPON']);
	print_ms($ren['MS_LUK']);
	print_ms($ren['MS_THROW']);
	print_ms($ren['MS_SWORD']);
	print_ms($ren['MS_AXE']);
	print_ms($ren['MS_SPEAR']);
	print_ms($ren['MS_ART']);
	print_ms($ren['MS_VSADNIK']);
	print_ms($ren['MS_VOR']);
	print_ms($ren['MS_LEK']);
	print_ms($ren['MS_PARIR']);
	print_ms($ren['MS_KUZN']);

	echo'</div></td></table></td>';
	echo'</td></tr></table>';
	OpenTable('close');
	echo '</td><td width="100%" valign=top>';
	OpenTable('title');
	echo '<div style="padding: 5px 5px; font-size:12px;"><span style="color:red;font_weight:900">';
    /*
    $clevel = $char['clevel'];
    $new_clevel = get_new_level($clevel);
	if ($char['clevel']==40)
        echo'Ты достиг максимального 40 уровня!<br><br>';
    elseif ($char['EXP'] >= $new_clevel)
		echo'Чтобы прокачать уровень нужно '.$new_clevel.' опыта<br><br><input type="button" value="Повысить уровень" onClick=location.replace("town.php?option='.$option.'&do=level_up")><br><br>';
	else
		echo'У тебя недостаточно опыта чтобы прокачать уровень!<br><br>Тебе нужно '.$new_clevel.' опыта<br><br>';
        */

	echo'</span><table width="100%" border="0" cellspacing="0" cellpadding="2">';
	$par = $ren['STR'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Cила - Влияет на атаку</td></tr>';
	$par = $ren['PIE'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Ловкость - Влияет на количество энергии и уворачивание от ударов</td></tr>';
	$par = $ren['VIT'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Защита - Влияет на защиту от атак оружием</td></tr>';
	$par = $ren['DEX'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Выносливость - Влияет на количество жизней и перенос вещей</td></tr>';
	$par = $ren['SPD'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Мудрость - Влияет на удачное использование заклинаний!</td></tr>';
	$par = $ren['NTL'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Интеллект - Влияет на атаку магией и количество маны.</td></tr>';
	$par = $ren['MS_KULAK'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Искусство кулачного боя: позволяет использовать кулачное оружие, +2 к удару кулаком, +1 к атаке кулачным оружием</td></tr>';
	$par = $ren['MS_WEAPON'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Эксперт воинских умений позволяет изучить приемы "прицельный удар" (2ур.), "пробивающий удар" (4ур.), "мощный удар" (6ур.), "круговая защита" (8ур.)</td></tr>';
	$par = $ren['MS_LUK'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Мастер стрелкового оружия: позволяет использовать стрелковое оружие, влияет на шанс попадания</td></tr>';
	$par = $ren['MS_THROW'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Мастер метательного оружия: позволяет использовать метательное оружие, влияет на шанс попадания</td></tr>';
	$par = $ren['MS_SWORD'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Мастер рубящего оружия: позволяет использовать рубящее оружие, +1 к атаке рубящим оружием</td></tr>';
	$par = $ren['MS_AXE'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Мастер дробящего оружия: позволяет использовать дробящее оружие, +1 к атаке дробящим оружием</td></tr>';
	$par = $ren['MS_SPEAR'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Мастер колющего оружия: позволяет использовать колющее оружие, +1 к атаке колющим оружием</td></tr>';
	$par = $ren['MS_VSADNIK'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Верховая езда: позволяет стать всадником</td></tr>';
	$par = $ren['MS_LEK'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Лекарь: Способность лечить себя и игроков</td></tr>';
	$par = $ren['MS_VOR'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Вор: Способность просмотра всех предметов игроков и скрытность</td></tr>';
	$par = $ren['MS_ART'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Эксперт артефактов: +1 мастерству владения всеми артефактами</td></tr>';
	$par = $ren['MS_PARIR'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Мастер парирования: +5 к мастерству защиты</td></tr>';
	$par = $ren['MS_KUZN'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Кузнец: позволяет ремонтировать оружие</td></tr>';
	echo '</table>';
	echo '</div>';
	OpenTable('close');
	echo'</tr></table>';
}
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

if (function_exists("save_debug")) save_debug();

?>