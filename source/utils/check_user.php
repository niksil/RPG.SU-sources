<?
$dirclass = "../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

function Check($param_user_name,$p1,$p2,$p3,$p4,$p5,$p6)
{
	$sel = myquery("(SELECT * FROM game_users WHERE name='$param_user_name') UNION (SELECT * FROM game_users_archive WHERE name='$param_user_name') LIMIT 1");
	$user = mysql_fetch_array($sel);
    
    $obnul = mysql_result(myquery("SELECT obnul_free FROM game_users_data WHERE user_id=".$user['user_id'].""),0,0);
    if ($obnul==1) return;
    
	$str = $user['STR_MAX'];
	$ntl = $user['NTL_MAX'];
	$pie = $user['PIE_MAX'];
	$vit = $user['VIT_MAX'];
	$dex = $user['DEX_MAX'];
	$spd = $user['SPD_MAX'];
	$clevel = $user['clevel'];
	$race = $user['race'];
	$cc_p=0;
    
    $param_user_name = $param_user_name.'<br />'.$user['user_id'];

	$vsego = $str+$ntl+$pie+$vit+$dex+$spd+$user['bound'];

	$sel_race = myquery("SELECT * FROM game_har WHERE id = '".$race."' LIMIT 1");
	$user_race = mysql_fetch_array($sel_race);
	$summa_race = $user_race['str']+$user_race['ntl']+$user_race['pie']+$user_race['vit']+$user_race['dex']+$user_race['spd'];
	$summa = $summa_race;
	$dex = $dex-$user_race['dex'];
	$str = $str-$user_race['str'];
	$vit = $vit-$user_race['vit'];
	$spd = $spd-$user_race['spd'];
	$ntl = $ntl-$user_race['ntl'];
	$pie = $pie-$user_race['pie'];
	$dex_clean = $user_race['dex'];
	$str_clean = $user_race['str'];
	$vit_clean = $user_race['vit'];
	$spd_clean = $user_race['spd'];
	$ntl_clean = $user_race['ntl'];
	$pie_clean = $user_race['pie'];

	$hp=0;
	$mp=0;
	$stm=0;

	$sel_items = myquery("SELECT * FROM game_items WHERE user_id='".$user['user_id']."' AND priznak=0 AND used>0 AND used NOT IN (12,13,14)");
	while ($user_items = mysql_fetch_array($sel_items))
	{
	  $Item = new Item($user_items['id']);
	  $summa=$summa+$Item->getFact('dstr');
	  $summa=$summa+$Item->getFact('dntl'); 
	  $summa=$summa+$Item->getFact('dpie'); 
	  $summa=$summa+$Item->getFact('dvit'); 
	  $summa=$summa+$Item->getFact('ddex'); 
	  $summa=$summa+$Item->getFact('dspd'); 
	  $dex=$dex-$Item->getFact('ddex');  
	  $str=$str-$Item->getFact('dstr');      
	  $vit=$vit-$Item->getFact('dvit');   
	  $spd=$spd-$Item->getFact('dspd');  
	  $pie=$pie-$Item->getFact('dpie');    
	  $ntl=$ntl-$Item->getFact('dntl');      
	  $dex_clean=$dex_clean+$Item->getFact('ddex');  
	  $str_clean=$str_clean+$Item->getFact('dstr');      
	  $vit_clean=$vit_clean+$Item->getFact('dvit');   
	  $spd_clean=$spd_clean+$Item->getFact('dspd');  
	  $pie_clean=$pie_clean+$Item->getFact('dpie');    
	  $ntl_clean=$ntl_clean+$Item->getFact('dntl');      
	  $cc_p = $cc_p+$Item->getFact('cc_p');    
	  $hp = $hp+$Item->getFact('hp_p');    
	  $mp = $mp+$Item->getFact('mp_p');    
	  $stm = $stm+$Item->getFact('stm_p');    
	}

	$har_level=0;
	$nav_level=0;
	for ($i=1;$i<=$clevel;$i++)
	{
		if ($i==10 OR $i==20 OR $i==30 OR $i==40)
		{
			$har_level=$har_level+3;
			$nav_level=$nav_level+3;
		}
		else
		{
			$har_level=$har_level+2;
			$nav_level=$nav_level+1;
		}
	}

	$summa=$summa+$har_level;

	$razn1 = $vsego-$summa;

	//Проверка навыков игрока
	if ($p1==1)
	{
	if ($razn1!=0)
	{
		//есть расхождение в характеристиках
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в характеристиках</td><td>Текущие характеристики: '.$vsego.'<br>Расчетные характеристики: '.$summa.'</td><td bgcolor=#FFFFA8>Разница: '.$razn1.'</td></tr>
		';
	}
	if ($str<0)
	{
		//есть расхождение в характеристиках
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в СИЛЕ</td><td>Текущая сила: '.$user['STR_MAX'].'<br>Расчетная сила не меньше: '.$str_clean.'</td><td bgcolor=#FFFFA8>Разница: '.$str.'</td></tr>
		';
	}
	if ($dex<0)
	{
		//есть расхождение в характеристиках
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в ВЫНОСЛИВОСТИ</td><td>Текущая выносливость: '.$user['DEX_MAX'].'<br>Расчетная выносливость не меньше: '.$dex_clean.'</td><td bgcolor=#FFFFA8>Разница: '.$dex.'</td></tr>
		';
	}
	if ($vit<0)
	{
		//есть расхождение в характеристиках
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в ЗАЩИТЕ</td><td>Текущая защита: '.$user['VIT_MAX'].'<br>Расчетная защита не меньше: '.$vit_clean.'</td><td bgcolor=#FFFFA8>Разница: '.$vit.'</td></tr>
		';
	}
	if ($pie<0)
	{
		//есть расхождение в характеристиках
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в ЛОВКОСТИ</td><td>Текущая ловкость: '.$user['PIE_MAX'].'<br>Расчетная ловкость не меньше: '.$pie_clean.'</td><td bgcolor=#FFFFA8>Разница: '.$pie.'</td></tr>
		';
	}
	if ($ntl<0)
	{
		//есть расхождение в характеристиках
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в ИНТЕЛЛЕКТЕ</td><td>Текущий интеллект: '.$user['NTL_MAX'].'<br>Расчетный интеллект не меньше: '.$ntl_clean.'</td><td bgcolor=#FFFFA8>Разница: '.$ntl.'</td></tr>
		';
	}
	if ($spd<0)
	{
		//есть расхождение в характеристиках
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в МУДРОСТИ</td><td>Текущая мудрость: '.$user['SPD_MAX'].'<br>Расчетная мудрость не меньше: '.$spd_clean.'</td><td bgcolor=#FFFFA8>Разница: '.$spd.'</td></tr>
		';
	}
	}

	$summa = $nav_level;
	$vsego = $user['MS_ART']+$user['exam']+$user['MS_VOR']+$user['MS_KULAK']+$user['MS_WEAPON']+$user['MS_LUK']+$user['MS_PARIR']+$user['MS_KUZN']+$user['MS_LEK']+$user['MS_VSADNIK']+$user['skill_war']+$user['skill_music']+$user['skill_cook']+$user['skill_art']+$user['skill_explor']+$user['skill_craft']+$user['skill_card']+$user['skill_pet']+$user['skill_uknow']+$user['MS_AXE']+$user['MS_SWORD']+$user['MS_SPEAR'];
	$razn1 = $vsego-$summa;
	if ($p2==1)
	{
	if ($razn1!=0)
	{
		//есть расхождение в навыках
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в навыках</td><td>Текущие навыки: '.$vsego.'<br>Расчетные навыки: '.$summa.'</td><td bgcolor=#FFFFA8>Разница: '.$razn1.'</td></tr>
		';
	}
	}

	//Проверка максимального веса игрока
	$vsego = $user['CC'];
	$summa = 40+$user['DEX']*2+$cc_p;
	if ($user['vsadnik']>0)
	{
		list($dex_kon) = mysql_fetch_array(myquery("SELECT ves FROM game_vsadnik WHERE id = '".$user['vsadnik']."'"));
		$summa=$summa+$dex_kon;
	}
	$razn1 = $vsego-$summa;
	if ($p3==1)
	{
	    if ($razn1!=0)
	    {
		    //есть расхождение в максимальном весе
		    echo'
		    <tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в максимальном весе</td><td>Текущий максимальный вес: '.$vsego.'<br>Расчетный максимальный вес: '.$summa.'</td><td bgcolor=#FFFFA8>Разница: '.$razn1.'</td></tr>
		    ';
            
	    }
	}

	//Проверка HP, MP and STM
	$vsego = $user['HP_MAXX'];
	$summa = $hp+$dex*10+$user_race['hp'];
	$razn1 = $vsego-$summa;
	if ($p4==1)
	{
	if ($razn1!=0)
	{
		//есть расхождение в максимальном весе
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в HP_MAX</td><td>Текущий HP_MAX: '.$vsego.'<br>Расчетный HP_MAX: '.$summa.'</td><td bgcolor=#FFFFA8>Разница: '.$razn1.'</td></tr>
		';
	}
	}

	$vsego = $user['MP_MAX'];
	$summa = $mp+$ntl*10+$user_race['mp'];
	$razn1 = $vsego-$summa;
	if ($p5==1)
	{
	if ($razn1!=0)
	{
		//есть расхождение в максимальном весе
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в MP_MAX</td><td>Текущий MP_MAX: '.$vsego.'<br>Расчетный MP_MAX: '.$summa.'</td><td bgcolor=#FFFFA8>Разница: '.$razn1.'</td></tr>
		';
	}
	}

	$vsego = $user['STM_MAX'];
	$summa = $stm+$pie*10+$user_race['stm'];
	$razn1 = $vsego-$summa;
	if ($p6==1)
	{
	if ($razn1!=0)
	{
		//есть расхождение в максимальном весе
		echo'
		<tr><td bgcolor=#FF8A8A align="center" valign="center">'.$param_user_name.'</td><td align="center" valign="center">Расхождения в STM_MAX</td><td>Текущий STM_MAX: '.$vsego.'<br>Расчетный STM_MAX: '.$summa.'</td><td bgcolor=#FFFFA8>Разница: '.$razn1.'</td></tr>
		';
	}
	}
}






echo '<form action="" method="post"><font size="3" face="Verdana" color="#800000">Проверка игроков<br></font><br>
<input type="checkbox" name="par1"'; if (isset($par1)) echo' checked'; echo'>Проверить характеристики (сила, ловкость, интеллект, ...)<br>
<input type="checkbox" name="par2"'; if (isset($par2)) echo' checked'; echo'>Проверить навыки и специализации<br>
<input type="checkbox" name="par3"'; if (isset($par3)) echo' checked'; echo'>Проверить максимальный вес<br>
<input type="checkbox" name="par4"'; if (isset($par4)) echo' checked'; echo'>Проверить максимальный HP<br>
<input type="checkbox" name="par5"'; if (isset($par5)) echo' checked'; echo'>Проверить максимальный MP<br>
<input type="checkbox" name="par6"'; if (isset($par6)) echo' checked'; echo'>Проверить максимальный STM<br>
<br>';
if (!isset($user_name)) $user_name='';
echo '<input name="user_name" type="text" size="25" value="'.$user_name.'">
<input name="check_user" type="submit" value="Проверить игрока">
<input name="check_all" type="submit" value="Проверить всех игроков"><br><br><br></form>
';

if (isset($check_user) OR isset($check_all))
{
	echo '<table border=1>';
	$ind=0;
	if (isset($check_user))
	{
		$sel = myquery("(SELECT * FROM game_users WHERE name='$user_name') UNION (SELECT * FROM game_users_archive WHERE name='$user_name') LIMIT 1");
	}
	else
	{
		$sel = myquery("(SELECT * FROM game_users) UNION (SELECT * FROM game_users_archive)");
	}
	if (mysql_num_rows($sel)>0)
	{
		while ($user = mysql_fetch_array($sel))
		{
			$ind++;
			if (isset($par1)) $p1=1; else $p1=0;
			if (isset($par2)) $p2=1; else $p2=0;
			if (isset($par3)) $p3=1; else $p3=0;
			if (isset($par4)) $p4=1; else $p4=0;
			if (isset($par5)) $p5=1; else $p5=0;
			if (isset($par6)) $p6=1; else $p6=0;
			//echo ''.$user['name'].'-'.$p1.'-'.$p2.'-'.$p3.'-'.$p4.'-'.$p5.'-'.$p6.'';
			Check($user['name'],$p1,$p2,$p3,$p4,$p5,$p6);
		}
	}
	else
	{
		echo 'Игрок не найден!';
	}
	echo '</table>';
}
?>