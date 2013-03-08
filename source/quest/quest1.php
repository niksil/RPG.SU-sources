<?php
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require_once('../inc/xray.inc.php');
require_once('../inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '10');
}
else
{
	die();
}
require_once('../inc/lib_session.inc.php');

//if (isset($_SESSION['quest1_step'])) echo '<hr>'.$_SESSION['quest1_step'].'<hr>';
//if (isset($_SESSION['quest1_state'])) echo '<hr>'.$_SESSION['quest1_state'].'<hr>';

function add_item_to_user($item_id)
{
	global $user_id,$char;
	$Item = new Item();
	$Item->add_user($item_id,$user_id,0);
}

function attack_npc_quest()
{
	global $user_id,$char;
	//начинаем бой с ботом охранником
	myquery("INSERT INTO game_quest_users SET user_id=$user_id,quest_id=1,last_time=".time().",sost=2 ON DUPLICATE KEY UPDATE sost=2");
	//создадим бота-охранника
	//$sel111=myquery("select npc_id from game_npc order by npc_id DESC limit 1");
	//list($nid)=mysql_fetch_array($sel111);
	//$n=''.($nid+1).'';

	if (
		$_SESSION['quest1_step']==7 or
		$_SESSION['quest1_step']==51
	   )
	{
		$npc_img = 'Monster-07';
		$npc_name = 'Охранник тюрьмы';
		$npc_race = 'Человек';
		$npc_hp = round(0.4*$char['HP_MAX']);
		$npc_max_hp = round(0.4*$char['HP_MAX']);
		$npc_mp = round(0.4*$char['MP_MAX']);
		$npc_max_mp =round(0.4*$char['MP_MAX']);
		$npc_str = round(0.4*$char['STR']);
		$npc_dex = round(0.4*$char['DEX']);
		$npc_wis = round(0.4*$char['PIE']);
		$npc_basefit = round(0.4*$char['VIT']);
		$npc_basedef = round(0.4*$char['SPD']);
		$npc_exp = 5;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = round(0.4*$char['NTL']);
		$npc_level = $char['clevel'];
		$npc_item = 'мечом';
	}
	if ($_SESSION['quest1_step']==14)
	{
		$npc_img = 'Clear/scorpy';
		$npc_name = 'Жуки и скорпионы';
		$npc_race = 'Пресмыкающееся';
		$npc_hp = 1000;
		if(($_SESSION['quest1_bad_portal']==1))
		{	$npc_hp = 2100;}
		else
		{	$npc_hp = 10;}

		if(($_SESSION['quest1_bad_portal']==1))
		{	$npc_max_hp = 2100;}
		else
		{	$npc_max_hp = 1000;}
		$npc_mp = 1;
		$npc_max_mp = 1;
		$npc_str = 0;
		$npc_dex = 9;
		$npc_wis = 2;
		$npc_basefit = 5;
		$npc_basedef = 0;
		$npc_exp = 15;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = -5;
		$npc_level = $char['clevel'];
		$npc_item = 'жалом';
	}
	if ($_SESSION['quest1_step']==24)
	{
		$npc_img = 'blackknight';
		$npc_name = 'Парень с ятаганом';
		$npc_race = 'Человек';
		$npc_hp = round(0.9*$char['HP_MAX']);
		$npc_max_hp = round(0.9*$char['HP_MAX']);
		$npc_mp = round(0.7*$char['MP_MAX']);
		$npc_max_mp =round(0.7*$char['MP_MAX']);
		$npc_str = $char['STR']+2;
		$npc_dex = $char['DEX']-2;
		$npc_wis = $char['PIE']-3;
		$npc_basefit = $char['VIT'];
		$npc_basedef = $char['SPD'];
		$npc_exp = 6;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = $char['NTL'];
		$npc_level = $char['clevel'];
		$npc_item = 'ятаганом';
	}
	if ($_SESSION['quest1_step']==27)
	{
		$npc_img = 'ettim';
		$npc_name = 'Два охранника';
		$npc_race = 'Человек';
		$npc_hp = $char['HP_MAX'];
		$npc_max_hp = $char['HP_MAX'];
		$npc_mp = round(1*$char['MP_MAX']);
		$npc_max_mp = round(1*$char['MP_MAX']);
		$npc_str = round(1*$char['STR']);
		$npc_dex = round(1*$char['DEX']);
		$npc_wis =round(1*$char['PIE']);
		$npc_basefit = round(1*$char['VIT']);
		$npc_basedef = round(1*$char['SPD']);
		$npc_exp = 8;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = $char['NTL'];
		$npc_level = $char['clevel'];
		$npc_item = 'ятаганом';
	}
	if ($_SESSION['quest1_step']==30)
	{
		$npc_img = 'ettim';
		$npc_name = '5 разбойников';
		$npc_race = 'Человек';
		$npc_hp = round(1.5*$char['HP_MAX']);
		$npc_max_hp = round(1.5*$char['HP_MAX']);
		$npc_mp = round(1.5*$char['MP_MAX']);
		$npc_max_mp =round(1.5*$char['MP_MAX']);
		$npc_str = round(1.5*$char['STR']);
		$npc_dex = round(1.5*$char['DEX']);
		$npc_wis = round(1.5*$char['PIE']);
		$npc_basefit = round(1.5*$char['VIT']);
		$npc_basedef = round(1.5*$char['SPD']);
		$npc_exp = 10;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = round(1.5*$char['NTL']);
		$npc_level = $char['clevel'];
		$npc_item = 'мечами';
	}
	if (
			$_SESSION['quest1_step']==32 or
			$_SESSION['quest1_step']==34 or
			$_SESSION['quest1_step']==36 or
			$_SESSION['quest1_step']==38 or
			$_SESSION['quest1_step']==40 or
			$_SESSION['quest1_step']==42 or
			$_SESSION['quest1_step']==44
		)
	{
		$npc_img = 'blackknight';
		$npc_name = 'Разбойник';
		$npc_race = 'Человек';
		$npc_hp = $char['HP_MAX'];
		$npc_max_hp = $char['HP_MAX'];
		$npc_mp = $char['MP_MAX'];
		$npc_max_mp = $char['MP_MAX'];
		$npc_str = $char['STR'];
		$npc_dex = $char['DEX'];
		$npc_wis = $char['PIE'];
		$npc_basefit = $char['VIT'];
		$npc_basedef = $char['SPD'];
		$npc_exp = 2;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = $char['NTL'];
		$npc_level =$char['clevel'];
		$npc_item = 'мечом';
	}
	if ($_SESSION['quest1_step']==44) $npc_item = 'булавой';

	$ins = myquery("INSERT INTO game_npc_template
	(npc_name, npc_race, npc_img, npc_max_hp, npc_max_mp, npc_str, npc_dex, npc_pie, npc_vit, npc_spd, npc_exp_max, npc_gold, npc_opis, npc_ntl, npc_level, respawn, item, to_delete)
	VALUES
	('$npc_name', '$npc_race', '$npc_img', '$npc_max_hp', '$npc_max_mp', '$npc_str', '$npc_dex', '$npc_wis', '$npc_basefit', '$npc_basedef', '$npc_exp', '$npc_gold', '', '$npc_ntl', '$npc_level', '0', '$npc_item', '1')");
	$npc_id = mysql_insert_id();
	
	$ins = myquery("INSERT INTO game_npc
	(npc_id, HP, MP, EXP, map_name, xpos, ypos, time_kill, stay, view, prizrak, npc_quest_id)
	VALUES
	('$npc_id', '$npc_hp', '$npc_mp', '$npc_exp', '$npc_map_name', '$npc_xpos', '$npc_ypos', '$npc_time', '0', '0', '1', '1')");
	$id = mysql_insert_id();
	attack_npc($char,$id,0);
}

function unset_all()
{
	foreach ($_SESSION AS $key=>$value)
	{
		if (substr($key,0,6)=='quest1')
		{
			unset($_SESSION[$key]);
		}
	}
}

$sel_sost = myquery("SELECT sost,finish FROM game_quest_users WHERE user_id=$user_id AND quest_id=1");
if ($sel_sost!=false AND mysql_num_rows($sel_sost)>0)
{
	$sst = mysql_fetch_array($sel_sost);
	$sost = $sst['sost'];
	if ($sost==99 AND $sst['finish']!=1)
	{
		$sst['finish'] = 1;
		myquery("UPDATE game_quest_users SET finish=1 WHERE user_id=$user_id AND quest_id=1");
	}
	if ($sst['finish']>=1)
	{
		ForceFunc($user_id,5);
		setLocation("../act.php");
	}
}

$new_clevel=get_new_level($char['clevel']);
$get_exp = floor(0.2*$new_clevel);

$last_time = time()-10*60;
if (isset($_SESSION['quest1_exit']) and $_SESSION['quest1_exit']>0)
{
	$sost=0;
	$finish=0;
	set_delay_reason_id($user_id,1);
	if ($_SESSION['quest1_exit']==99)
	{
		myquery("UPDATE game_users SET EXP=EXP+$get_exp,GP=GP+'".$_SESSION['quest1_get_gp']."',CW=CW+'".($_SESSION['quest1_get_gp']*money_weight)."' WHERE user_id=$user_id");
		setGP($user_id,$_SESSION['quest1_get_gp'],59);
		setEXP($user_id,$get_exp,6);
		if ($_SESSION['quest1_take_weapon']==1){add_item_to_user(4);}
		if ($_SESSION['quest1_take_shlem']==1) {add_item_to_user(8);}
		$sost=99;
		$finish=1;
	}
	if ($_SESSION['quest1_exit']==50)
	{
		$_SESSION['quest1_get_gp']=90;
		myquery("UPDATE game_users SET HP=1,MP=1,STM=1 WHERE user_id=$user_id");
		myquery("UPDATE game_users SET EXP=EXP+$get_exp,GP=GP+".$_SESSION['quest1_get_gp'].",CW=CW+".($_SESSION['quest1_get_gp']*money_weight)." WHERE user_id=$user_id");
		setGP($user_id,$_SESSION['quest1_get_gp'],59);
		setEXP($user_id,$get_exp,6);
		add_item_to_user(8);
		$sost=99;
		$finish=1;
	}
	if ($_SESSION['quest1_exit']==1)
	{
		myquery("UPDATE game_users SET HP=1,MP=1,STM=1 WHERE user_id=$user_id");
	}
	unset_all();
	myquery("INSERT INTO game_quest_users (user_id,quest_id,last_time,sost,finish) VALUES ($user_id,1,".time().",$sost,1) ON DUPLICATE KEY UPDATE sost=$sost,last_time=".time().",finish=GREATEST($finish,finish)");
	ForceFunc($user_id,5);
	setLocation("../act.php");
}
if (isset($return))
{
	$_SESSION['quest1_step'] = 0;
	$_SESSION['quest1_state']='0';
	$_SESSION['quest1_search_ohr']=0;
	$_SESSION['quest1_have_key']=0;
	$_SESSION['quest1_need_key']=0;
	$_SESSION['quest1_search_room']=0;
	$_SESSION['quest1_count_money']=0;
	$_SESSION['quest1_bad_portal']=0;
	$_SESSION['quest1_take_shlem']=0;
	$_SESSION['quest1_take_weapon']=0;
	$_SESSION['quest1_ves_plita']=0;
	$_SESSION['quest1_get_gp']=0;
	$_SESSION['quest1_searh_trup']=0;
	$_SESSION['quest1_money_girl']=0;
	$_SESSION['quest1_wind_move']=0;
	$_SESSION['quest1_wind_might']=0;
	$state='0';
}
if (isset($_GET['begin']))
{
	unset_all();
}

echo '<title>'.GAME_NAME.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="Keywords" content=""><style type="text/css">@import url("../style/global.css");</style>';


if (isset($_SESSION['quest1_lose']) OR isset($exit_from_quest))
{
	echo '<BR><BR><BR>';
	QuoteTable('open');
	echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через 5 минут';
	QuoteTable('close');
	QuoteTable('open');
	$_SESSION['quest1_exit']=2;
	echo'<a href="?exit">Квест провален</a><br>';
	QuoteTable('close');
	unset($_SESSION['quest1_lose']);
	myquery("INSERT INTO game_quest_users (user_id,quest_id,last_time) VALUES ($user_id,1,".time().") ON DUPLICATE KEY UPDATE last_time=".time()."");
	if (isset($exit_from_quest)) exit;
}

$questsel = myquery("SELECT * FROM game_quest WHERE map_name=".$char['map_name']." AND map_xpos=".$char['map_xpos']." AND map_ypos=".$char['map_ypos']." AND min_clevel<=".$char['clevel']." AND max_clevel>=".$char['clevel']." AND id=1");
if (mysql_num_rows($questsel))
{
	$quest = mysql_fetch_array($questsel);
	$check = mysql_result(myquery("SELECT COUNT(*) FROM game_quest_users WHERE user_id=$user_id AND quest_id=1 AND (last_time>=$last_time OR sost>0)"),0,0);
	if ($check==0)
	{
		//myquery("INSERT INTO game_quest_users (user_id,quest_id,last_time) VALUES ($user_id,1,".time().") ON DUPLICATE KEY UPDATE last_time=".time()."");

	set_delay_reason_id($user_id,13);
		OpenTable('title');
		echo '<p align=left>';
		if (!isset($_SESSION['quest1_state'])) $_SESSION['quest1_state']='0';
		if (!isset($_SESSION['quest1_step'])) $_SESSION['quest1_step']=0;
		if (!isset($state)) $state='0';

		if ($_SESSION['quest1_step']==1 and substr($state,0,1)=='0') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==2 and substr($state,0,1)=='1') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==3 and substr($state,0,2)=='12') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==4 and substr($state,0,3)=='123') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==5 and substr($state,0,4)=='1232') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==6 and substr($state,0,5)=='12321') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==7 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==8 and isset($win_bot)) {$_SESSION['quest1_state'] = '1232120'; myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==9 and substr($state,0,6)=='123212') $_SESSION['quest1_state'] = $state;
		//if ($_SESSION['quest1_step']==10 and substr($state,0,7)=='1232121') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==10 and substr($state,0,6)=='123212') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==11 and substr($state,0,7)=='1232125') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==12 and substr($state,0,10)=='1232125202') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==13 and substr($state,0,11)=='12321252023') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==14 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==15 and isset($win_bot))
		{
			$_SESSION['quest1_state'] = '777';
			myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");
		}
		if ($_SESSION['quest1_step']==16 and substr($state,0,7)=='1232125') $_SESSION['quest1_state'] = $state;
		/*if ($_SESSION['quest1_step']==17 and isset($win_bot)) {$_SESSION['quest1_state'] = '999999999';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id='$user_id'");};*/
		if ($_SESSION['quest1_step']==19 and substr($state,0,5)=='12323') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==20 and substr($state,0,6)=='123233') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==21 and substr($state,0,7)=='1232332') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==9 and $state=='123241') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==22 and substr($state,0,2)=='13') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==23 and substr($state,0,1)=='3') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==24 and substr($state,0,2)=='31') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==24 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==25 and isset($win_bot)) {$_SESSION['quest1_state'] = '320';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==26 and substr($state,0,2)=='32') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==27 and substr($state,0,3)=='321') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==27 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==28 and isset($win_bot)) {$_SESSION['quest1_state'] = '32120';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==29 and substr($state,0,4)=='3212') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==30 and substr($state,0,5)=='32122') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==30 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==31 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212210';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==32 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==33 and isset($win_bot)) {$_SESSION['quest1_state'] = '1232120';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==34 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==35 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212222';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==36 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==37 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212223';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==38 and isset($boy))
		{
			attack_npc_quest();
		}
		if ($_SESSION['quest1_step']==39 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212224';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==40 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==41 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212225';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==42 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==43 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212226';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==44 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==45 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212227';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==46 and substr($state,0,1)=='4') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==47 and substr($state,0,2)=='43') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==48 and substr($state,0,3)=='430') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==49 and substr($state,0,4)=='4302') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==50 and substr($state,0,5)=='43020') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==51 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==52 and isset($win_bot)) {$_SESSION['quest1_state'] = '1232120';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};

		switch ($_SESSION['quest1_state'])
		{
			case '0':
			{
				$_SESSION['quest1_search_ohr']=0;
				$_SESSION['quest1_have_key']=0;
				$_SESSION['quest1_step']=1;
				$_SESSION['quest1_need_key']=0;
				$_SESSION['quest1_search_room']=0;
				$_SESSION['quest1_count_money']=0;
				$_SESSION['quest1_bad_portal']=0;
				$_SESSION['quest1_take_shlem']=0;
				$_SESSION['quest1_take_weapon']=0;
				$_SESSION['quest1_ves_plita']=0;
				$_SESSION['quest1_get_gp']=0;
				$_SESSION['quest1_searh_trup']=0;
				$_SESSION['quest1_money_girl']=0;
				$_SESSION['quest1_wind_move']=0;
				$_SESSION['quest1_wind_might']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Завернув за угол, вы видите как трое дюжих вооруженных мужчин тащат по направлению к холмам молодую девушку, весьма изыскано одетую. Она отчаянно отбивается от вооруженных, хотя, конечно, ничего не выходит, и, кажется, что-то кричит, но слов вы разобрать не можете. Ваши действия:';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=01">С криком &quot;Эй, вы что делаете?!&quot; побежать к месту действия.</a><br>
				2) <a href="?state=02">Побежать, выхватив меч, с криком &quot;Прочь от девушки, нечестивые псы!&quot;</a><br>
				3) <a href="?state=03">Молча достать лук</a><br>
				4) <a href="?state=04">Пригнуться и последовать за странной компанией</a><br>
				5) <a href="?state=05">Пожать плечами и продолжить путь</a>';
				QuoteTable('close');
				break;
			};
			case '01':
			{
				$_SESSION['quest1_step']=2;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы догоняете этих четверых. Кажется, все они люди, хотя девушка, возможно, эльфийка. Вперед выходит рыжий бородатый мужчина с двуручным мечом за спиной, а остальные, вооруженные булавой и изогнутым ятаганом, держат за плечи пленницу. Вы замечаете, как один из них заносит ей за спину кинжал.<br><B>- Ты еще кто?</B> - довольно недружелюбно спрашивает бородатый.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=11">*выхватить меч*  - Тот, в чьей коллекции сейчас станет на три головы больше!</a><br>
				2) <a href="?state=12">Представиться и спросить, кто они, и что вообще происходит.</a><br>
				3) <a href="?state=13">Я страж Гильдии! Приказываю вам немедленно назваться и объяснить, что тут творится!</a><br>
				4) <a href="?state=14">Э-э-э… Да я так, мимо проходил.. Ладно, пора мне! *уйти*</a>';
				QuoteTable('close');
				break;
			}
			case '11':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы успеваете пырнуть в живот явно не ожидавшего ничего подобного бородатого, но его соратники уже выхватили оружие. Завязался бой, и последнее, что вы помните - сильный удар по голове…Когда вы очнулись, никого рядом уже не было..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '12':
			{
				$_SESSION['quest1_step']=3;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Специальный отдел стражников Арды!</B> - чопорно заявил бородатый и махнул у вас перед лицом какой-то блестящей штукой. - <B>Она</B>, - тут он указал на девушку, - <B>нарушала законы. А теперь идите своей дорогой и не мешайте проведению операции!</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=121">Ох, простите, пожалуйста, конечно, я удаляюсь.</a><br>
				2) <a href="?state=122">Что?! Да нет никакого специального отдела! Смерть самозванцам!</a><br>
				3) <a href="?state=123">Значит, мы коллеги! *Махнуть перед ним пряжкой от старого пояса*</a><br>';
				QuoteTable('close');
				break;
			}
			case '121':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы пошли своим путем, а трое мужчин с девушкой - своим.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '122':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы успеваете пырнуть в живот явно не ожидавшего ничего подобного бородатого, но его соратники уже выхватили оружие. Завязался бой, и последнее, что вы помните - сильный удар по голове…Когда вы очнулись, никого рядом уже не было..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '123':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Неужели?</B> - ухмыляется бородатый - <B>Тогда позвольте сообщить вам один факт.</B> - Он берет вас за плечо и разворачивает; тут вы чувствуете у себя за спиной какое-то шевеление, а в следующий момент - сильный удар по затылку. В глазах темнеет…<br><br>Очнувшись, вы обнаруживаете себя за решеткой тесной сумрачной камеры, эдакого каменного мешка два на два метра. Всё оружие у вас, похоже отобрали, да и почти все остальные вещи тоже. Хорошо, что они не нашли ловко припрятанный кошелек!
';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">Исследовать стены.</a><br>
				2) <a href="?state=1232">Привлечь внимание охранников</a><br>
				3) <a href="?state=1233">Распустить рубашку на нитки, свить веревку и удавиться.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1231':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы обшарили каждый сантиметр вашей комнаты. Стены как стены, ничего необычного. Хотя, кажется один кирпичик слегка пошатнулся.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">Исследовать стены.</a><br>
				2) <a href="?state=1232">Привлечь внимание охранников</a><br>
				3) <a href="?state=1233">Распустить рубашку на нитки, свить веревку и удавиться.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232':
			{
				$_SESSION['quest1_step']=5;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>На ваши громкие вопли и стук по прутьям решетки пришел толстый охранник с дубинкой на поясе. Лицо его выражает крайнюю степень недовольства. ';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=12321">Попросить поесть.</a><br>
				2) <a href="?state=12322">Мило улыбнуться и сказать: &quot;Привет, я просто хотел познакомиться!&quot;</a><br>
				3) <a href="?state=12323">Предложить выкуп за свое освобождение.</a><br>
				4) <a href="?state=12324">Симулировать сердечный приступ.</a><br>
				5) <a href="?state=12325">Заорать: &quot;Эй, ты, жирный боров, да ты хоть знаешь, кто я?! Если не выпустишь меня, то через 15 минут тут будет отряд очень злых и вооруженных до зубов головорезов, которые разнесут весь этот гадюжник по кирпичикам!!!&quot;</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321':
			{
				$_SESSION['quest1_step']=6;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Пробурчав что-то нечленораздельное с оттенком согласия, охранник ушел. Минут через десять он вернулся, держа в руках тарелку с чем-то наполовину жидким, выглядящим не слишком аппетитно. Стражник попытался просунуть тарелку между прутьями, но та решительно не желала пролезать. Тогда он поставил тарелку на пол, в правую руку взял небольшой меч и, не сводя с вас настороженного взгляда, отпер дверь..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=123211">Смирно ждать, пока он поставит к вам тарелку и закроет дверь, а затем скушать отвратительное варево.</a><br>
				2) <a href="?state=123212">Наброситься на охранника</a><br>';
				QuoteTable('close');
				break;
			}
			case '123211':
			{
				$_SESSION['quest1_step']=5;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Да, местные повара сегодня явно не в ударе.. Желудок смирился с клейкой массой, в него попавшей, но остался недоволен.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=12321">Попросить поесть.</a><br>
				2) <a href="?state=12322">Мило улыбнуться и сказать: &quot;Привет, я просто хотел познакомиться!&quot;</a><br>
				3) <a href="?state=12323">Предложить выкуп за свое освобождение.</a><br>
				4) <a href="?state=12324">Симулировать сердечный приступ.</a><br>
				5) <a href="?state=12325">Заорать: &quot;Эй, ты, жирный боров, да ты хоть знаешь, кто я?! Если не выпустишь меня, то через 15 минут тут будет отряд очень злых и вооруженных до зубов головорезов, которые разнесут весь этот гадюжник по кирпичикам!!!&quot;</a><br>';
				QuoteTable('close');
				break;
			}
			case '123212':
			{
				$_SESSION['quest1_step']=7;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>В тот момент, когда ваш страж наклонился за тарелкой, вы прыгнули на него. Но он был настороже, так что - бой!';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				//$_SESSION['quest1_exit']=2;
				echo'<a href="?boy">Начать бой с охранником</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232120':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вам удалось-таки победить охранника! Вы стоите над его поверженным телом. Что делать?';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1232121">Надругаться над трупом.</a><br>
				2) <a href="?state=1232122">Снять с пояса ключи.</a><br>
				3) <a href="?state=1232123">Обыскать тело. </a><br>
				4) <a href="?state=1232124">Взять меч.</a><br>
				5) <a href="?state=1232125">Отойти.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232121':
			{
				$_SESSION['quest1_step']=10;
				$_SESSION['quest1_bad_portal']=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы *ВЫРЕЗАНО ЦЕНЗУРОЙ*, затем * ВЫРЕЗАНО ЦЕНЗУРОЙ*, но, утомившись *ВЫРЕЗАНО ЦЕНЗУРОЙ*, решили просто *ВЫРЕЗАНО ЦЕНЗУРОЙ*, что и сделали.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_have_key']==1){} else {$i++;echo $i.') <a href="?state=1232122">Снять с пояса ключи.</a><br>';}
				if ($_SESSION['quest1_search_ohr']==1){} else {$i++;echo $i.') <a href="?state=1232123">Обыскать тело.</a><br>';}
				if ($_SESSION['quest1_take_weapon']==1){} else {$i++;echo $i.') <a href="?state=1232124">Взять меч.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">Отойти.</a><br>';
				QuoteTable('close');
				break;
			}
		   /* case '12321211':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Пошарив некоторое время в этом кровавом месиве и перемазавшись по локти, вы нашли 6 монет. ';
				//myquery("UPDATE game_users SET GP=GP+6,CW=CW+'".(6*money_weight)."' WHERE user_id='$user_id'");
				$_SESSION['quest1_get_gp']+=6;
				$_SESSION['quest1_search_ohr']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_have_key']==1){} else {$i++;echo $i.') <a href="?state=1232122">Снять с пояса ключи.</a><br>';}
				if ($_SESSION['quest1_take_weapon']==1){} else {$i++;echo $i.') <a href="?state=1232124">Взять меч.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">Отойти.</a><br>';
				QuoteTable('close');
				break;
			}*/
			case '1232122':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы сняли с пояса трупа большую связку ключей самых разных форм и размеров. ';
				$_SESSION['quest1_have_key']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_bad_portal']==1/*$_SESSION['quest1_searh_trup']==1 OR $_SESSION['quest1_search_ohr']==1*/){} else {$i++;echo $i.') <a href="?state=1232121">Надругаться над трупом.</a><br>';}
				if ($_SESSION['quest1_search_ohr']==1){} else {$i++;echo $i.') <a href="?state=1232123">Обыскать тело.</a><br>';}
				if ($_SESSION['quest1_take_weapon']==1){} else {$i++;echo $i.') <a href="?state=1232124">Взять меч.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">Отойти.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232123':
			{
				$_SESSION['quest1_step']=9;
				if($_SESSION['quest1_bad_portal']==0)
				{
					 QuoteTable('open');
					 echo '<font size=3 color=#F0F0F0>Полазив некоторое время по карманам охранника вы нашли 23 монеты.';
					 $_SESSION['quest1_get_gp']+=23;
					 $_SESSION['quest1_search_ohr']=1;
					 QuoteTable('close');
				}
				else
				{
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>Пошарив некоторое время в этом кровавом месиве и перемазавшись по локти, вы нашли 6 монет. ';
					$_SESSION['quest1_get_gp']+=6;
					$_SESSION['quest1_search_ohr']=1;
					$_SESSION['quest1_searh_trup']=1;
					QuoteTable('close');
				}
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_bad_portal']==1/*$_SESSION['quest1_searh_trup']==1 OR $_SESSION['quest1_search_ohr']==1*/){} else {$i++;echo $i.') <a href="?state=1232121">Надругаться над трупом.</a><br>';}
				if ($_SESSION['quest1_have_key']==1){} else {$i++;echo $i.') <a href="?state=1232122">Снять с пояса ключи.</a><br>';}
				if ($_SESSION['quest1_take_weapon']==1){} else {$i++;echo $i.') <a href="?state=1232124">Взять меч.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">Отойти.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232124':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы подобрали выпавший из мертвых рук мистический меч. Что ж, трофейное оружие.';
				//add_item_to_user(4);
				$_SESSION['quest1_take_weapon']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_bad_portal']==1/*$_SESSION['quest1_searh_trup']==1 OR $_SESSION['quest1_search_ohr']==1*/){} else {$i++;echo $i.') <a href="?state=1232121">Надругаться над трупом.</a><br>';}
				if ($_SESSION['quest1_have_key']==1){} else {$i++;echo $i.') <a href="?state=1232122">Снять с пояса ключи.</a><br>';}
				if ($_SESSION['quest1_search_ohr']==1){} else {$i++;echo $i.') <a href="?state=1232123">Обыскать тело.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">Отойти.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232125':
			{
				$_SESSION['quest1_step']=11;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы отходите от бездыханного тела охранника и вдруг слышите, как кто-то окликает вас по имени, голос исходит из соседней камеры. Вы заглядываете туда, и обнаруживаете ту самую девушку, которую тащили бородатый и двое его прихвостней. Она обращается к вам.<br>
		- <B>Пожалуйста, освободи меня! Эти бандиты держат меня тут как заложницу и хотят убить, коли за меня не заплатят выкуп! Если ты меня выпустишь, я щедро вознагражу тебя и помогу выбраться отсюда! У тебя ведь есть ключи?</B>
';
				QuoteTable('close');
				echo '<br><br><br>';
				$_SESSION['quest1_money_girl']=200;
				QuoteTable('open');
				if ($_SESSION['quest1_have_key']==1)
				{
					 echo'
					1) <a href="?state=12321252">Да, конечно, сейчас я освобожу вас.</a><br>
					2) <a href="?state=12321253">Да, взял, но прежде хотелось бы уточнить, что вы подразумеваете под словом &quot;щедро&quot;?</a><br>
					3) <a href="?state=12321252024">Ну вас, леди, я буду свою шкуру спасать! *выйти из  помещения*</a><br>';
				}
				else
				{
					echo'<a href="?state=12321251">Секунду! *пойти и взять ключи*</a><br>';
				}
				QuoteTable('close');
				break;
			}
			case '12321251':
			{
				$_SESSION['quest1_step']=11;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы вернулись к телу охранника и сняли с его пояса большую связку ключей самых разных форм и размеров.<br>
		- <B>Теперь отопри, пожалуйста, решетку!</B> - взмолилась девушка.';
				$_SESSION['quest1_have_key']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=12321252">Да, конечно, сейчас я освобожу вас.</a><br>
				2) <a href="?state=12321253">Да, взял, но прежде хотелось бы уточнить, что вы подразумеваете под словом &quot;щедро&quot;?</a><br>
				3) <a href="?state=12321252024">Ну вас, леди, я буду свою шкуру спасать! *выйти из  помещения*</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321252':
			{
				if (!isset($key)) $key=0;
				if ($key==0)
				{
					$_SESSION['quest1_step']=11;
					$_SESSION['quest1_need_key']=mt_rand(5,18);
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>Вы смотрите на скважину, затем перебираете ключи и понимаете, что с виду все они подходят к замку. Придется гадать.';
					QuoteTable('close');
				}
				else
				{
					if($key == $_SESSION['quest1_need_key'])
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>Вы вставляете ключ в скважину и, невольно задержав дыхание, пытаетесь его повернуть. Есть! Раздается негромких щелчок, и ключ легко проворачивается на два оборота, замок отперт! Вы открываете решетку и пленница выходит из камеры. Ее руки скованы кандалами из непонятного матового материала, поэтому с выбором ключа проблем не возникает. Оковы с мягким стуком падают на пол, а освобожденная девушка со вздохом облегчения потирает запястья.<br>
		 <B>- Спасибо тебе, '.$char['name'].'! Ты даже не представляешь, как много сейчас сделал для Средиземья! В благодарность я помогу тебе выбраться отсюда</B>, - тут девушка взмахивает руками, и с кончиков ее пальцев срываются ослепительно белые искры, устремившиеся к противоположной стене комнаты; искр все больше и больше, и тут.. В стене образовывается портал прямиком к крепости Гильдии Новичков! Кажется, ваша челюсть отвисла по крайней мере до уровня пояса. Вы спешно захлопываете рот, а волшебница улыбается, довольная произведенным впечатлением.<br>
		<B>- Кроме того</B>, - продолжает она, - <B>вот деньги, которые я обещала</B>, - она протягивает вам приятно позвякивающий мешочек, - <B>это почти все, что у меня есть. Еще раз спасибо, я никогда не забуду, что ты для меня сделал!</B> - тут она на мгновение закрывает глаза и добавляет, загадочно улыбаясь. - <B>Но я вижу, это, возможно, не последняя наша встреча…</B> <br>
И вдруг - не успеваете вы произнести &quot;Пожалуйста&quot; или хотя бы спросить &quot;Кто вы?&quot;, девушка исчезает в вихре синих молний! Ну и дела…';
						//myquery("UPDATE game_users SET GP=GP+200,CW=CW+'".(200*money_weight)."' WHERE user_id='$user_id'");
						QuoteTable('close');
					}
					else
					{
						QuoteTable('open');
						$phrase=mt_rand(1,8);
						switch ($phrase)
						{
							case 1: {echo '<font size=3 color=#F0F0F0>Ключ не поворачивается.';break;}
							case 2: {echo '<font size=3 color=#F0F0F0>Замок не поддается.';break;}
							case 3: {echo '<font size=3 color=#F0F0F0>Кажется, не тот ключ.';break;}
							case 4: {echo '<font size=3 color=#F0F0F0>Видимо, стоит попробовать другой.';break;}
							case 5: {echo '<font size=3 color=#F0F0F0>Этот ключ не подходит.';break;}
							case 6: {echo '<font size=3 color=#F0F0F0>Этим ключом не открыть камеру.';break;}
							case 7: {echo '<font size=3 color=#F0F0F0>Тут нужен не этот ключ.';break;}
							case 8: {echo '<font size=3 color=#F0F0F0>Бесполезно. Нужно попробовать еще один.';break;}
						};

						QuoteTable('close');
					}
				}
				echo '<br><br><br>';
				QuoteTable('open');
				if($key == $_SESSION['quest1_need_key'])
				{
			  $_SESSION['quest1_get_gp']+=$_SESSION['quest1_money_girl'];
					$_SESSION['quest1_step']=12;
					echo'
					1) <a href="?state=12321252021">Пересчитать деньги</a><br>
					2) <a href="?state=12321252022">Обыскать камеру девушки</a><br>
					3) <a href="?state=12321252023">Пойти в портал</a><br>
					4) <a href="?state=12321252024">С криком &quot;Всех положу, ублюдки!!!"&quot; распахнуть дверь из комнаты. </a><br>';
				}
				else
				{
				echo'
				1) <a href="?state=12321252&key=1">Старый медный ключ</a><br>
				2) <a href="?state=12321252&key=2">Обычный железный ключ</a><br>
				3) <a href="?state=12321252&key=3">Странный серебристый ключ</a><br>
				4) <a href="?state=12321252&key=4">Крепкий стальной ключ</a><br>
				5) <a href="?state=12321252&key=5">Какой-то металлический ключ</a><br>
				6) <a href="?state=12321252&key=6">Еще медный ключ</a><br>
				7) <a href="?state=12321252&key=7">И еще медный ключ</a><br>
				8) <a href="?state=12321252&key=8">Опять медный ключ</a><br>
				9) <a href="?state=12321252&key=9">Еще железный ключ</a><br>
				10) <a href="?state=12321252&key=10">Еще один железный ключ</a><br>
				11) <a href="?state=12321252&key=11">Еще стальной ключ</a><br>
				12) <a href="?state=12321252&key=12">Еще один стальной ключ</a><br>
				13) <a href="?state=12321252&key=13">Снова серебристый ключ</a><br>
				14) <a href="?state=12321252&key=14">Большой металлический ключ</a><br>
				15) <a href="?state=12321252&key=15">Ржавый медный ключ</a><br>
				16) <a href="?state=12321252&key=16">И еще железный ключ</a><br>
				17) <a href="?state=12321252&key=17">Обыкновенный медный ключ</a><br>
				18) <a href="?state=12321252&key=18">Длинный серебристый ключ</a><br>
				19) <a href="?state=12321252&key=19">Ключ из непонятного материала</a><br>';
				}
				QuoteTable('close');
				break;
			}
			case '12321252021':
			{
				$_SESSION['quest1_step']=12;
				$_SESSION['quest1_count_money']=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы открываете мешок и пересчитываете монеты.';
				$user_time_quest=time()+60;
				echo '<font color="#ff0000">Подождите:</font> ещё <span id="pendule"></span>&nbsp;<font color="#ff0000">'.'
				<script type="text/javascript" language="JavaScript">
				var a='.abs($user_time_quest - time()).';
				text1="";
				function clock_status()
				{
					if (a<=9) text="&nbsp;"+a;
					if (a<=0) {text1="(Ходите)";text="0";vybor.style.display="block";}
					else {text=a;vybor.style.display="none";};
					if (document.layers) 
					{
						document.layers.pendule.document.write(text);
						document.layers.pendule.document.close();
						document.layers.pend.document.write(text1);
						document.layers.pend.document.close();
					}
					else
					{
						pendule = document.getElementById("pendule");
						pendule.innerHTML = text;
						pend.innerHTML = text1;
						a=a-1;
						window.setTimeout("clock_status()",1000);
					}
				}
				clock_status();
				</script>
				'.' </font>сек. <span id="pend"></span><br><br>';
				QuoteTable('close');
				echo '<br><br><br>';
				echo'<span id="vybor">';
				QuoteTable('open');
				echo'
					1) <a href="?state=12321252023">Пойти в портал</a><br>
					2) <a href="?state=12321252024">С криком &quot;Всех положу, ублюдки!!!"&quot; распахнуть дверь из комнаты. </a><br>';
				if ($_SESSION['quest1_search_room']==1) {} else echo '3) <a href="?state=12321252022">Обыскать камеру девушки</a><br>';
				QuoteTable('close');
				echo '</span>';
				break;
			}
			case '12321252022':
			{
				$_SESSION['quest1_step']=12;
				$_SESSION['quest1_search_room']=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Тщательно обшарив камеру, в которой держали спасенную вами волшебницу, вы не обнаруживаете ничего, кроме старого, подпачканного изнутри кровью узорного шлема. Впрочем, если слегка помыть - вполне можно носить! ';
				//add_item_to_user(8);
				$_SESSION['quest1_take_shlem']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
					1) <a href="?state=12321252023">Пойти в портал</a><br>
					2) <a href="?state=12321252024">С криком &quot;Всех положу, ублюдки!!!"&quot; распахнуть дверь из комнаты. </a><br>';
				if ($_SESSION['quest1_count_money']==1) {} else echo '3) <a href="?state=12321252021">Пересчитать деньги</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321252023':
			{
				$_SESSION['quest1_step']=13;
				/*QuoteTable('open');
				if ($_SESSION['quest1_bad_portal']==1)
				{
				echo '<font size=3 color=#F0F0F0>Только вы было направились к порталу, как услышали странное шуршание. Вы повернулись к умерщвленному вами охраннику, и обнаружили… один скелет!!! А рядом - сотни маленьких коричневых жуков! Кажется, их привлекла кровь охранника, и останавливаться на поглощении трупа они вовсе не собираются. Напротив, в предвкушении свежего мяса орда насекомых окружает вас, алчно шурша. Похоже, придется показать им, кто тут босс! ';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'<a href="?boy">Начать бой с жуками</a><br>';
				QuoteTable('close');
				}
				else*/
				{
				$_SESSION['quest1_money_girl']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Только вы было направились к порталу, как услышали тихий "щелк" у себя под ногами. Опустив взгляд, вы обнаружили, что наступили на потайную кнопку, а оглядевшись - что на вас нацелено штук двадцать стрел! Смекнув, что если вы отпустите плиту, что станете чем-то похожим на ежа, вы решили немного подумать… Повозившись немного, вы поняли, что около 90 монет как раз уравновешивают плиту… Свои кровные тратить не хочется, и вы решили класть только монеты девушки. Но у вас, вроде бы, есть не только деньги!';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				 echo'
					1) <a href="?state=1232125202321">Пошарить по карманам</a><br>
					2) <a href="?state=1232125202322">Банза-ай!!! *прыгнуть в портал*</a><br>';
				QuoteTable('close');
				}
				break;
			}
			case '1232125202322':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Дико завопив, вы со всей силы прыгнули навстречу свободе, но одна из стрел успела зацепить вас. Очень, очень больно, но, к счастью, не смертельно! Проклятье! Кажется, еще одна стрела зацепила ремень, но котором висели трофеи! Сзади что-то звякнуло.<br> На той стороне портала, в поле около стен Гильдии, вы обнаружили мешок со своими пожитками, которые уже считали безвозвратно утерянными, и записку &quot;Еще раз спасибо!&quot;';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$_SESSION['quest1_exit']=50;
				echo'<a href="?exit">Квест пройден!</a><br>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo 'Вы заработали 90 золотых монет<br>';
				list($item_name) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=8"));
				echo 'Вы получили предмет: '.$item_name.'<br>';
				echo 'Вы получили '.$get_exp.' очков опыта<br>';
				QuoteTable('close');
				break;
			}
			case '1232125202321':
			{
				$_SESSION['quest1_step']=14;

				if ($_SESSION['quest1_take_shlem']==0 AND $_SESSION['quest1_take_weapon']==0)
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Исследовав свои карманы, вы нашли там только связку ключей. Что ж, похоже, она тянет монеты на тридцать три. Что положить на плиту?';
				QuoteTable('close');                }
				elseif ($_SESSION['quest1_take_shlem']>=1 AND $_SESSION['quest1_take_weapon']==0)
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Исследовав свои карманы, вы нашли там связку ключей и узорный шлем из камеры девушки. Похоже, что ключи весят как шлем плюс монет двенадцать. А вместе они - монет шестьдесят. Что положить на плиту?';
				QuoteTable('close');
				}
				elseif ($_SESSION['quest1_take_shlem']==0 AND $_SESSION['quest1_take_weapon']>=1)
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Исследовав свои карманы, вы нашли там связку ключей и оружие с трупа охранника. Похоже, что оружие весит как ключи плюс монет десять. А вместе они - монет восемьдесят. Что положить на плиту?';
				QuoteTable('close');
				}
				elseif ($_SESSION['quest1_take_shlem']>=1 AND $_SESSION['quest1_take_weapon']>=1)
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Исследовав свои карманы, вы нашли там связку ключей, узорный шлем из камеры девушки и оружие с трупа охранника. Похоже, что шлем вместе с десятью монетами весит как связка ключей, оружие и шлем вместе - семьдесят четыре монеты, а ключи с пятнадцатью монетами - как оружие с шестью. Что положить на плиту?';
				QuoteTable('close');
				}
				elseif (!isset($gotovo) AND !isset($portal))
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Что будем делать??';
				QuoteTable('close');
				}
				if (isset($weapon))
				{
					//myquery("DELETE FROM game_items WHERE ident='Мистический меч' AND user_id='$user_id' AND used=''");
					$_SESSION['quest1_take_weapon']=2;
					$_SESSION['quest1_ves_plita']+=46;
				}
				if (isset($weapon_m))
				{
					//myquery("DELETE FROM game_items WHERE ident='Мистический меч' AND user_id='$user_id' AND used=''");
					$_SESSION['quest1_take_weapon']=1;
					$_SESSION['quest1_ves_plita']-=46;
				}
				if (isset($shlem))
				{
					//myquery("DELETE FROM game_items WHERE ident='Узорный шлем' AND user_id='$user_id' AND used=''");
					$_SESSION['quest1_take_shlem']=2;
					$_SESSION['quest1_ves_plita']+=27;
				}
				if (isset($shlem_m))
				{
					//myquery("DELETE FROM game_items WHERE ident='Узорный шлем' AND user_id='$user_id' AND used=''");
					$_SESSION['quest1_take_shlem']=1;
					$_SESSION['quest1_ves_plita']-=27;
				}
				if (isset($keys))
				{
					$_SESSION['quest1_have_key']=2;
					$_SESSION['quest1_ves_plita']+=37;
				}
				if (isset($keys_m))
				{
					$_SESSION['quest1_have_key']=1;
					$_SESSION['quest1_ves_plita']-=37;
				}
				if (isset($money))
				{
					/*if($_SESSION['quest1_get_gp']>0)*/
					$_SESSION['quest1_get_gp']--;
					$_SESSION['quest1_money_girl']++;
					$_SESSION['quest1_ves_plita']++;

					/*elseif ($char['GP']>0)
					{
						myquery("UPDATE game_users SET GP=GP-1,CW=CW-'".(1*money_weight)."' WHERE user_id='$user_id'");
						$sel = myquery("SELECT * FROM game_users WHERE user_id='$user_id'");
						$char = mysql_fetch_array($sel);
						list($char_map_name,$char_map_xpos,$char_map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$user_id'"));
						list($last_active) = mysql_fetch_array(myquery("SELECT last_active FROM game_users_active WHERE user_id='$user_id'"));
						$char['map_name']=$char_map_name;
						$char['map_xpos']=$char_map_xpos;
						$char['map_ypos']=$char_map_ypos;
						$char['last_active']=$last_active;
					}
					$_SESSION['quest1_ves_plita']+=1;
					else if(($_SESSION['quest1_get_gp']<=0)and ($char['GP']<=0))
					{
						$_SESSION['quest1_ves_plita']-=1;
						echo '<br>';
						QuoteTable('open');
						echo 'У вас кончились деньги.';
						echo '<br>';
						QuoteTable('close');
					}*/
				}
				if (isset($money_m))
				{
					$_SESSION['quest1_get_gp']+=1;
					$_SESSION['quest1_money_girl']-=1;
					$_SESSION['quest1_ves_plita']-=1;
					/*if($_SESSION['quest1_get_gp']=0) $_SESSION['quest1_get_gp']+=1;
					elseif ($char['GP']>0)
					{
						myquery("UPDATE game_users SET GP=GP+1,CW=CW+'".(1*money_weight)."' WHERE user_id='$user_id'");
						$sel = myquery("SELECT * FROM game_users WHERE user_id='$user_id'");
						$char = mysql_fetch_array($sel);
						list($char_map_name,$char_map_xpos,$char_map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$user_id'"));
						list($last_active) = mysql_fetch_array(myquery("SELECT last_active FROM game_users_active WHERE user_id='$user_id'"));
						$char['map_name']=$char_map_name;
						$char['map_xpos']=$char_map_xpos;
						$char['map_ypos']=$char_map_ypos;
						$char['last_active']=$last_active;
					/*}*/
				  /*  $_SESSION['quest1_ves_plita']-=1;
					$_SESSION['quest1_money_girl']-=1;*/

					/*if(($_SESSION['quest1_get_gp']<=0)and ($char['GP']<=0))
					{
						$_SESSION['quest1_ves_plita']-=1;
						echo '<br>';
						QuoteTable('open');
						echo 'У вас кончились деньги.';
						echo '<br>';
						QuoteTable('close');
					}
					}*/
				}
				if (isset($gotovo))
				{
					if ($_SESSION['quest1_ves_plita']<86)
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>Вы положили слишком мало веса на плиту, и со всех сторон в вас полетели стрелы...';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
						QuoteTable('close');
						QuoteTable('open');
						$_SESSION['quest1_exit']=1;
						echo'<a href="?exit">Квест провален</a><br>';
						QuoteTable('close');
						break;
					}
					else
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>Зажмурившись, и мысленно приготовившись к протыканию стрелами, вы убрали ногу с плиты. Ничего не произошло. Восславив Илуватара, вы было направились к порталу, как услышали странное шуршание. Вы повернулись к умерщвленному вами охраннику, и обнаружили… один скелет!!! А рядом - сотни маленьких коричневых жуков! Кажется, их привлекла кровь охранника, и останавливаться на поглощении трупа они вовсе не собираются. Напротив, в предвкушении свежего мяса орда насекомых окружает вас, алчно шурша. Похоже, придется показать им, кто тут босс! ';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo'<a href="?boy">Начать бой с жуками</a><br>';
						QuoteTable('close');
						/*QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>Зажмурившись, и мысленно приготовившись к протыканию стрелами, вы убрали ногу с плиты. Ничего не произошло. Восславив Илуватара, вы шагнули в портал. На той его стороне, в поле около стен Гильдии, вы обнаружили мешок со своими пожитками, которые уже считали безвозвратно утерянными, и записку &quot;Еще раз спасибо!&quot;';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						$_SESSION['quest1_exit']=99;
						echo '<font size=3 color=#F0F0F0>ПОЗДРАВЛЯЮ!<br>';
						echo'<a href="?exit">Квест пройден!</a><br>';
						QuoteTable('close');*/
						break;
					}
				}
				if (isset($portal))
				{
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>Дико завопив, вы со всей силы прыгнули навстречу свободе, но одна из стрел успела зацепить вас. Очень, очень больно, но, к счастью, не смертельно! Проклятье! Кажется, еще одна стрела зацепила ремень, но котором висели трофеи! Сзади что-то звякнуло.<br> На той стороне портала, в поле около стен Гильдии, вы обнаружили мешок со своими пожитками, которые уже считали безвозвратно утерянными, и записку &quot;Еще раз спасибо!&quot;';
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					$_SESSION['quest1_exit']=50;
					echo'<a href="?exit">Квест пройден!</a><br>';
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo 'Вы заработали 90 золотых монет<br>';
					list($item_name) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=8"));
					echo 'Вы получили предмет: '.$item_name.'<br>';
					echo 'Вы получили '.$get_exp.' очков опыта<br>';
					QuoteTable('close');
					break;
				}
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_take_weapon']==1)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&weapon">Положить меч охранника</a><br>';
				}
				if ($_SESSION['quest1_take_weapon']==2)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&weapon_m">Убрать меч охранника</a><br>';
				}
				if ($_SESSION['quest1_have_key']==1)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&keys">Положить связку ключей</a><br>';
				}
				if ($_SESSION['quest1_have_key']==2)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&keys_m">Убрать связку ключей</a><br>';
				}
				if ($_SESSION['quest1_take_shlem']==1)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&shlem">Положить узорный шлем</a><br>';
				}
				if ($_SESSION['quest1_take_shlem']==2)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&shlem_m">Убрать узорный шлем</a><br>';
				}
				if (/*$char['GP']+*/$_SESSION['quest1_get_gp']>0)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&money">Положить монету</a><br>';
				}
				if ($_SESSION['quest1_money_girl']>0)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&money_m">Убрать монету</a><br>';
				}
				$i++;
				echo '<br>';
				echo $i.') <a href="?state=1232125202321&gotovo">Готово! Убрать ногу</a><br>';
				$i++;
				echo $i.') <a href="?state=1232125202321&portal">Банза-ай!!! *Прыгнуть в портал*</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321252024':
			{
				$_SESSION['quest1_step']=15;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы оказались в помещении стражи. Шесть мускулистых воинов с мечами бросают на вас ироничные и -  ой-ей - кровожадные взгляды. Будет больно… ';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321253':
			{
				$_SESSION['quest1_step']=16;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Я дам тебе 200 монет - все, что у меня есть! Это немало, ты так не считаешь?</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				 echo'
					1) <a href="?state=12321252">Да, конечно, сейчас я освобожу вас.</a><br>
					2) <a href="?state=123212531">Хм, я бы за свою жизнь заплатил побольше.. Надеюсь, намек понятен?</a><br>
					3) <a href="?state=12321252024">Ну вас, леди, я буду свою шкуру спасать! *выйти из  помещения*</a><br>';
				QuoteTable('close');
				break;
			}
			case '999999999':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Оставшись после боя без сил вы бросили все найденные сокровища и из последних сил доползли до города';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">Закончить квест</a><br>';
				QuoteTable('close');
				break;
			}
			case '123212531':
			{
				$_SESSION['quest1_step']=16;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Да в тебе нет ни капли сострадания! Но Моргот с тобой, в моем положении торговаться не приходится.. Отдам последнее - 230 монет… Этого тебе хватит?!</B>';
				$_SESSION['quest1_money_girl']=230;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				 echo'
					1) <a href="?state=12321252">Да, конечно, сейчас я освобожу вас.</a><br>
					2) <a href="?state=1232125311">Этого мне хватит на кружечку пива в таверне. Елы-палы, неужели вы совсем не цените свою жизнь?</a><br>
					3) <a href="?state=12321252024">Ну вас, леди, я буду свою шкуру спасать! *выйти из  помещения*</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232125311':
			{
				$_SESSION['quest1_step']=16;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Твоя жадность не знает границ!!! Скажи спасибо, что я не подниму сейчас шума, на который прибежит вся охрана этого места! И что, так уж и быть, выведу тебя отсюда! Но я не отказываюсь от своих слов, я вновь предлагаю тебе 200 монет! Ты согласен?</B>';
				$_SESSION['quest1_money_girl']=200;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				 echo'
					1) <a href="?state=12321252">Да, конечно, сейчас я освобожу вас.</a><br>
					2) <a href="?state=12321252024">Ну вас, леди, я буду свою шкуру спасать! *выйти из  помещения*</a><br>';
				QuoteTable('close');
				break;
			}
			case '12322':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Что?! Сейчас я покажу тебе, как отвлекать меня от дел, сволочь!! - взревел охранник, распахнул дверь и ударил вас ногой в живот, а затем пару раз огрел дубинкой. Пока вы приходили в себя, он вышел и закрыл за собой дверь.</B>';
				myquery("UPDATE game_users SET HP=HP-10 WHERE user_id='$user_id'");
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">Исследовать стены.</a><br>
				2) <a href="?state=1232">Привлечь внимание охранников</a><br>
				3) <a href="?state=1233">Распустить рубашку на нитки, свить веревку и удавиться.</a><br>';
				QuoteTable('close');
				break;
			}
			case '12323':
			{
				$_SESSION['quest1_step']=19;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Похоже, вы заинтересовали охранника. Он подошел поближе и шепотом спросил:<br>
		- <B>И сколько же ты готов заплатить?</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=123231">Да сколько угодно, только выпусти меня!</a><br>
				2) <a href="?state=123232">10 монет, думаю, хватит.</a><br>
				3) <a href="?state=123233">Я дам тебе целых 50 монет.</a><br>
				4) <a href="?state=123234">100 монет. По-моему, это неплохая сделка.</a><br>';
				QuoteTable('close');
				break;
			}
			case '123231':
			{
				$_SESSION['quest1_step']=19;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Ха, нашел дурака! Нет уж, говори, сколько?!</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=123231">Да сколько угодно, только выпусти меня!</a><br>
				2) <a href="?state=123232">10 монет, думаю, хватит.</a><br>
				3) <a href="?state=123233">Я дам тебе целых 50 монет.</a><br>
				4) <a href="?state=123234">100 монет. По-моему, это неплохая сделка.</a><br>';
				QuoteTable('close');
				break;
			}
			case '123232':
			{
				$_SESSION['quest1_step']=19;
				QuoteTable('open');
				if ($char['GP']>10)
				{
					echo '<font size=3 color=#F0F0F0>       <B> - Да, давай</B>, - отвечает охранник. Вы передаете ему через решетку десять золотых.
		<br>- <B>А-ха-ха-ха-ха!</B> - расхохотался гнусный тип. -<B> Неужели ты думаешь, что я готов был выпустить тебя за жалкие 10 монет???</B> <br> И он уносит ваши кровные в соседнюю комнату.' ;
					myquery("UPDATE game_users SET GP=GP-10,CW=CW-'".(10*money_weight)."' WHERE user_id='$user_id'");
					setGP($user_id,-10,59);
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					1) <a href="?state=123231">Да сколько угодно, только выпусти меня!</a><br>
					2) <a href="?state=123232">10 монет, думаю, хватит.</a><br>
					3) <a href="?state=123233">Я дам тебе целых 50 монет.</a><br>
					4) <a href="?state=123234">100 монет. По-моему, это неплохая сделка.</a><br>';
					QuoteTable('close');
				}
				else
				{
					$_SESSION['quest1_step']=4;
					echo '<font size=3 color=#F0F0F0>        <B>Что? Одурачить меня захотелось, сволочь?</B> - взревел охранник, распахнул дверь и немилосердно избил вас своей дубинкой. Пока вы приходили в себя, он вышел и закрыл за собой дверь. ';
					myquery("UPDATE game_users SET HP=HP-10 WHERE user_id=$user_id");
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					1) <a href="?state=1231">Исследовать стены.</a><br>
					2) <a href="?state=1232">Привлечь внимание охранников</a><br>
					3) <a href="?state=1233">Распустить рубашку на нитки, свить веревку и удавиться.</a><br>';
					QuoteTable('close');
				}
				break;
			}
			case '123233':
			{
				$_SESSION['quest1_step']=20;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Что ж, сумма неплохая… Вот что: добавь-ка к ним еще 15 - и ты свободен!</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1232331">Пошел ты!</a><br>
				2) <a href="?state=1232332">Ладно, делать нечего.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232331':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Что? Ну тогда получи, сволочь</B> - взревел охранник, распахнул дверь и немилосердно избил вас своей дубинкой. Пока вы приходили в себя, он вышел и закрыл за собой дверь. ';
				myquery("UPDATE game_users SET HP=HP-10 WHERE user_id=$user_id");
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">Исследовать стены.</a><br>
				2) <a href="?state=1232">Привлечь внимание охранников</a><br>
				3) <a href="?state=1233">Распустить рубашку на нитки, свить веревку и удавиться.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232332':
			{
				$_SESSION['quest1_step']=21;
				QuoteTable('open');
				if ($char['GP']>65)
				{
					echo '<font size=3 color=#F0F0F0> - <B>Ха, весьма разумно с твоей стороны</B>, - сказал охранник, отпирая решетки. - <B>Что ж, я тебя освободил. Счастливого пути наружу</B>, - он гнусно расхохотался и вышел из помещения. Вы поняли, что об этой части плана как-то не задумывались. И тут вы заметили, что стражник забыл в замке связку ключей…';
					myquery("UPDATE game_users SET GP=GP-65,CW=CW-'".(65*money_weight)."' WHERE user_id=$user_id");
					setGP($user_id,-65,59);
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					<a href="?state=12323321">Взять ключи</a><br>';
					QuoteTable('close');
				}
				else
				{
					$_SESSION['quest1_step']=4;
					echo '<font size=3 color=#F0F0F0> - <B>Что? Одурачить меня захотелось, сволочь? </B>- взревел охранник, распахнул дверь и немилосердно избил вас своей дубинкой. Пока вы приходили в себя, он вышел и закрыл за собой дверь. ';
					myquery("UPDATE game_users SET HP=HP-10 WHERE user_id=$user_id");
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					1) <a href="?state=1231">Исследовать стены.</a><br>
					2) <a href="?state=1232">Привлечь внимание охранников</a><br>
					3) <a href="?state=1233">Распустить рубашку на нитки, свить веревку и удавиться.</a><br>';
					QuoteTable('close');
				}
				break;
			}
			case '12323321':
			{
				$_SESSION['quest1_step']=11;
				$_SESSION['quest1_have_key']=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы вынимаете из скважины ключи и вдруг слышите, как кто-то окликает вас по имени, голос исходит из соседней камеры. Вы заглядываете туда, и обнаруживаете ту самую девушку, которую тащили бородатый и двое его прихвостней. Она обращается к вам.
	   <br> - <B>Пожалуйста, освободи меня! Эти бандиты держат меня тут как заложницу и хотят убить, коли за меня не заплатят выкуп! Если ты меня выпустишь, я щедро вознагражу тебя и помогу выбраться отсюда! У тебя ведь есть ключи?</B>

';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				if ($_SESSION['quest1_have_key']==1)
				{
					 echo'
					1) <a href="?state=12321252">Да, конечно, сейчас я освобожу вас.</a><br>
					2) <a href="?state=12321253">Да, взял, но прежде хотелось бы уточнить, что вы подразумеваете под словом &quot;щедро&quot;?</a><br>
					3) <a href="?state=12321252024">Ну вас, леди, я буду свою шкуру спасать! *выйти из  помещения*</a><br>';
				}
				else
				{
					echo'<a href="?state=12321251">Секунду! *пойти и взять ключи*</a><br>';
				}
				QuoteTable('close');
				break;
			}
			case '123234':
			{
				$_SESSION['quest1_step']=21;
				QuoteTable('open');
				if ($char['GP']>100)
				{
					echo '<font size=3 color=#F0F0F0> - <B>Очень неплохая</B>, - осклабился охранник, отпирая решетки. - <B>Что ж, я тебя освободил. Счастливого пути наружу</B>, - он гнусно расхохотался и вышел из помещения. Вы поняли, что об этой части плана как-то не задумывались. И тут вы заметили, что стражник забыл в замке связку ключей… ';
					myquery("UPDATE game_users SET GP=GP-100,CW=CW-'".(100*money_weight)."' WHERE user_id=$user_id");
					setGP($user_id,-100,59);
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					<a href="?state=12323321">Взять ключи</a><br>';
					QuoteTable('close');
				}
				else
				{
					$_SESSION['quest1_step']=4;
					echo '<font size=3 color=#F0F0F0> - <B>Что? Одурачить меня захотелось, сволочь?</B> - взревел охранник, распахнул дверь и немилосердно избил вас своей дубинкой. Пока вы приходили в себя, он вышел и закрыл за собой дверь. ';
					myquery("UPDATE game_users SET HP=HP-10 WHERE user_id=$user_id");
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					1) <a href="?state=1231">Исследовать стены.</a><br>
					2) <a href="?state=1232">Привлечь внимание охранников</a><br>
					3) <a href="?state=1233">Распустить рубашку на нитки, свить веревку и удавиться.</a><br>';
					QuoteTable('close');
				}
				break;
			}
			case '12324':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Когда охранник подошел к решетке, вы схватились за сердце и, безумно мыча, выпучив глаза, начали кататься по полу. Стражник трясущимися руками отпер дверь и в замешательстве склонился над вами; воспользовавшись этим, вы что есть силы ударили его промеж глаз. Несчастный повалился на пол без чувств. Что делать с ним?';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=123241">Размозжить ему череп об пол.</a><br>
				2) <a href="?state=1232122">Снять с пояса ключи.</a><br>
				3) <a href="?state=1232123">Обыскать тело. </a><br>
				4) <a href="?state=1232124">Взять меч.</a><br>
				5) <a href="?state=1232125">Отойти.</a><br>';
				QuoteTable('close');
				break;
			}
			case '123241':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы хватаете бедного охранника за горло и начинаете изо всех сил бить головой о каменный пол, пока все ее содержимое вперемешку с осколками черепа не оказываются снаружи. Зато теперь он не встанет.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1232121">Надругаться над трупом.</a><br>
				2) <a href="?state=1232122">Снять с пояса ключи.</a><br>
				3) <a href="?state=1232123">Обыскать тело. </a><br>
				4) <a href="?state=1232124">Взять меч.</a><br>
				5) <a href="?state=1232125">Отойти.</a><br>';
				QuoteTable('close');
				break;
			}
			case '12325':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>-<B> Что?! Это я - жирный боров?! Ну, сейчас ты у меня получишь, гнида!!</B> - с этими воплями охранник врывается к вам в камеру и жестоко избивает дубинкой, а когда вы уже падаете на пол, то и ногами. Пока вы валяетесь, сплевывая кровавую слюну, он выходит и запирает за собой дверь. ';
				myquery("UPDATE game_users SET HP=HP-30 WHERE user_id=$user_id");
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">Исследовать стены.</a><br>
				2) <a href="?state=1232">Привлечь внимание охранников</a><br>
				3) <a href="?state=1233">Распустить рубашку на нитки, свить веревку и удавиться.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1233':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы целый день вили веревку, и к вечеру работа была завершена. Обвязав петлю вокруг шеи, вы привязали второй конец веревки к каком-то крюку, торчащему из потолка, встали на ведро, предназначенное видимо, для туалета, и спрыгнули с него… Фу, какой бесславный конец…';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '13':
			{
				$_SESSION['quest1_step']=22;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>Конечно-конечно</B>, - протараторил бородатый, но в его голосе вы услышали издевательские нотки. - <B>А я валар The_Elf</B>, - ухмыльнулся он во весь рот. - <B>А это</B>, - он махнул рукой на двоих, держащих девушку, - <B>валары Zander и blazevic.. Поймали взбесившегося бота, ведем Артасу… Еще вопросы есть,<I> Страж?</I></B> - он особо выделил слово &quot;страж&quot;.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=131">- *грозно* Я повторяю в последний раз, я Страж Гильдии! И если вы продолжите в таком духе, то… - вы вынимаете камень для пращи, что завалялся у вас в рюкзаке. - Моим людям уже все известно по этому Палантиру, и через пять минут они будут здесь!</a><br>
				2) <a href="?state=132">- Извините, я, конечно же, пошутил.. Позвольте представиться…</a><br>
				3) <a href="?state=133">- Мое почтение, валары! Вопросов больше нет, извините за беспокойство.</a><br>';
				QuoteTable('close');
				break;
			}
			case '131':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>За пять минут твой труп уже успеет поостыть!</B> - с этими словами бородатый неожиданно ловко выхватил свой меч и отрубил вам голову. Какая досада.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '132':
			{
				$_SESSION['quest1_step']=3;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>А мы - специальный отдел стражников Арды!</B> - чопорно заявил бородатый и махнул у вас перед лицом какой-то блестящей штукой. -<B> Она</B>, - тут он указал на девушку, -<B> нарушала законы. А теперь идите своей дорогой и не мешайте проведению операции!</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=121">Ох, простите, пожалуйста, конечно, я удаляюсь.</a><br>
				2) <a href="?state=122">Что?! Да нет никакого специального отдела! Смерть самозванцам!</a><br>
				3) <a href="?state=123">Значит, мы коллеги, - махнуть перед ним пряжкой от старого пояса.</a><br>';
				QuoteTable('close');
				break;
			}
			case '133':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Посмеиваясь, валары удалились. А вы так и остались стоять, никак не придя в себя от удивления…';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '14':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы пошли своим путем, а трое мужчин с девушкой - своим.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '2':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Трое повыхватывали оружие и ожидали вас в боевых позах. Завязался бой, и последнее, что вы помните - сильный удар по голове…Когда вы очнулись, никого рядом уже не было..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '3':
			{
				$_SESSION['quest1_step']=23;
				$ra = mt_rand(1,8);
				$_SESSION['quest1_wind_might']=mt_rand(1,5);
				$_SESSION['quest1_luk_kol']=5;
				$_SESSION['quest1_luk_up']=0;
				$_SESSION['quest1_luk_right']=0;
				$_SESSION['quest1_luk_up_need']=0;
				$_SESSION['quest1_luk_right_need']=0;
				//направление ветра
				switch ($ra)
				{
					case 1:
					{
					$_SESSION['quest1_wind_move']="N";
					$_wind = "дует с севера";
					$_SESSION['quest1_luk_up_need']=10+$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=0;
					break;
					}
					case 2:{ $_SESSION['quest1_wind_move']="NE"; $_wind = "дует с северо-востока";
					$_SESSION['quest1_luk_up_need']=10+$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=$_SESSION['quest1_wind_might']-1;
					break;
					}
					case 3:{ $_SESSION['quest1_wind_move']="E"; $_wind = "дует с востока";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=$_SESSION['quest1_wind_might']-1;
					break;
					}
					case 4:{ $_SESSION['quest1_wind_move']="SE"; $_wind = "дует с юго-востока";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=$_SESSION['quest1_wind_might']-1;
					break;
					}
					case 5:{ $_SESSION['quest1_wind_move']="S"; $_wind = "дует с юга";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=0;
					break;
					}
					case 6:{ $_SESSION['quest1_wind_move']="SW"; $_wind = "дует с юго-запада";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=1-$_SESSION['quest1_wind_might'];
					break;
					}
					case 7:{ $_SESSION['quest1_wind_move']="W"; $_wind = "дует с запада";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=1-$_SESSION['quest1_wind_might'];
					break;
					}
					case 8:{ $_SESSION['quest1_wind_move']="NW"; $_wind = "дует с северо-запада";
					$_SESSION['quest1_luk_up_need']=10+$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=1-$_SESSION['quest1_wind_might'];
					break;
					}
				}

//Разбор полетов 0
			$_SESSION['quest1_luk_up']=12;


			if ($_SESSION['quest1_luk_up']>$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#FFB400><b>перелетела</b></font> ";}
			if ($_SESSION['quest1_luk_up']<$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#3F00FF><b>недолетела</b></font> ";}
			if ($_SESSION['quest1_luk_up']==$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#3F00FF><b>долетела</b></font> ";}
			if ($_SESSION['quest1_luk_right']<$_SESSION['quest1_luk_right_need'])
			{ $gor="<b>левее</b>.";}
			if ($_SESSION['quest1_luk_right']>$_SESSION['quest1_luk_right_need'])
			{ $gor="<b>правее</b>.";}
			if ($_SESSION['quest1_luk_right']==$_SESSION['quest1_luk_right_need'])
			{ $gor=" <b>ровно по направлению к противнику</b>.";}
			switch(abs(abs($_SESSION['quest1_luk_up'])-abs($_SESSION['quest1_luk_up_need'])))
			{
				case 0:{$dVer=" ";break;}
				case 1:{$dVer=" <b>чуть-чуть</b> ";break;}
				case 2:case 3:{$dVer=" <b>немного</b> ";break;}
				case 4:case 5:case 6:{$dVer=" <b>значительно</b> ";break;}
				case 7:case 8:case 9:case 10:{$dVer=" <b>сильно</b> ";break;}
				default:{$dVer=" <b>абсолютно</b> ";break;}
			}
			switch(abs(abs($_SESSION['quest1_luk_right'])-abs($_SESSION['quest1_luk_right_need'])))
			{
				case 0: {$dGor=" ";break;}
				case 1:{$dGor=" <b>чуть-чуть</b> ";break;}
				case 2:case 3:{$dGor=" <b>немного</b> ";break;}
				case 4:case 5:case 6:{$dGor=" <b>значительно</b> ";break;}
				case 7:case 8:case 9:case 10:{$dGor=" <b>намного</b> ";break;}
				default:{$dGor=" <b>очень намного</b> ";break;}
			}

			/*if(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])==0)
				{$dGor=" ";}
			elseif(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])<7)
				{$dGor=$dVer;}
			elseif(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])>10)
				{$dGor=" <b>очень намного</b> ";}
			else {$dGor=" <b>намного</b> ";}*/

			$_SESSION['quest1_luk_up']=0;


//Конец Разбора полетов 0

				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы вынимаете свой лук, но обнаруживаете, что стрел осталось всего шесть. Что ж, придется тщательнее целиться… Противники на севере от вас, причем удаляются, учтите это при каждом следующем выстреле. А ветер, кажется '.$_wind .', баллов эдак '.$_SESSION['quest1_wind_might'].'. <br> Вы выпустили пробную стрелу, подняв прицел на 18 градусов, но <font color=#FF0000>промахнулись<font color=#F0F0F0>. Стрела'.$dVer.$vert.'и упала'.$dGor.$gor.'<br><font color=#F0F0F0> Выстрелив, вы снова целитесь параллельно земле.';
				QuoteTable('close');
//Вывод состояния Лука
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" поднят на ";} else {$vsmes=" опущен на ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" направо ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" налево ";};
		echo '<font size=3 color=#F0F0F0> В колчане: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> стрел.';
		echo '<br>';
		echo 'Ваш прицел'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> градусов.';
		echo '<br>';
		echo 'Ваш прицел сдвинут'.$gsmes.'на <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> градусов.';

		QuoteTable('close');
//Конец Вывода состояния Лука
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">Поднять прицел на 1,5 градуса</a><br>
				2) <a href="?state=32">Опустить прицел на 1,5 градуса</a><br>
				3) <a href="?state=33">Сдвинуть направо на 5 градусов</a><br>
				4) <a href="?state=34">Сдвинуть налево на 5 градусов</a><br>
				5) <a href="?state=35">Стрелять</a><br>';
				QuoteTable('close');
				break;
			}
			case '31':
			{
			   if($_SESSION['quest1_luk_up']<=30)
			   {
				$_SESSION['quest1_luk_up']++;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы подняли прицел';

				QuoteTable('close');
				} else {QuoteTable('open'); echo '<font size=3 color=#F0F0F0>Если поднимите прицел еще, то наверняка промахнетесь!'; QuoteTable('close');}

//Вывод состояния Лука
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" поднят на ";} else {$vsmes=" опущен на ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" направо ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" налево ";};
		echo '<font size=3 color=#F0F0F0> В колчане: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> стрел.';
		echo '<br>';
		echo 'Ваш прицел'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> градусов.';
		echo '<br>';
		echo 'Ваш прицел сдвинут'.$gsmes.'на <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> градусов.';

		QuoteTable('close');
//Конец Вывода состояния Лука

				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">Поднять прицел на 1,5 градуса</a><br>
				2) <a href="?state=32">Опустить прицел на 1,5 градуса</a><br>
				3) <a href="?state=33">Сдвинуть направо на 5 градусов</a><br>
				4) <a href="?state=34">Сдвинуть налево на 5 градусов</a><br>
				5) <a href="?state=35">Стрелять</a><br>';
				QuoteTable('close');
				break;
			}
			case '32':
			{
		   if($_SESSION['quest1_luk_up']>=-60)
		   {
				$_SESSION['quest1_luk_up']--;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы опустили прицел';

				QuoteTable('close');
		   } else {QuoteTable('open'); echo '<font size=3 color=#F0F0F0>Куда уж ниже, и так в землю смотрите!'; QuoteTable('close');}

//Вывод состояния Лука
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" поднят на ";} else {$vsmes=" опущен на ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" направо ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" налево ";};
		echo '<font size=3 color=#F0F0F0> В колчане: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> стрел.';
		echo '<br>';
		echo 'Ваш прицел'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> градусов.';
		echo '<br>';
		echo 'Ваш прицел сдвинут'.$gsmes.'на <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> градусов.';

		QuoteTable('close');
//Конец Вывода состояния Лука

				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">Поднять прицел на 1,5 градуса</a><br>
				2) <a href="?state=32">Опустить прицел на 1,5 градуса</a><br>
				3) <a href="?state=33">Сдвинуть направо на 5 градусов</a><br>
				4) <a href="?state=34">Сдвинуть налево на 5 градусов</a><br>
				5) <a href="?state=35">Стрелять</a><br>';
				QuoteTable('close');
				break;
			}
			case '33':
			{
		   if($_SESSION['quest1_luk_right']<=9)
		   {
				$_SESSION['quest1_luk_right']++;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы сдвинули прицел вправо';

				QuoteTable('close');
		   } else {QuoteTable('open'); echo '<font size=3 color=#F0F0F0>В этом нет смысла!'; QuoteTable('close');}
//Вывод состояния Лука
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" поднят на ";} else {$vsmes=" опущен на ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" направо ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" налево ";};
		echo '<font size=3 color=#F0F0F0> В колчане: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> стрел.';
		echo '<br>';
		echo 'Ваш прицел'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> градусов.';
		echo '<br>';
		echo 'Ваш прицел сдвинут'.$gsmes.'на <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> градусов.';

		QuoteTable('close');
//Конец Вывода состояния Лука

				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">Поднять прицел на 1,5 градуса</a><br>
				2) <a href="?state=32">Опустить прицел на 1,5 градуса</a><br>
				3) <a href="?state=33">Сдвинуть направо на 5 градусов</a><br>
				4) <a href="?state=34">Сдвинуть налево на 5 градусов</a><br>
				5) <a href="?state=35">Стрелять</a><br>';
				QuoteTable('close');
				break;
			}
			case '34':
			{
		   if($_SESSION['quest1_luk_right']>=-9)
		   {
				$_SESSION['quest1_luk_right']-=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы сдвинули прицел влево';

				QuoteTable('close');
		   } else {QuoteTable('open'); echo '<font size=3 color=#F0F0F0>Абсолютно бессмысленно!'; QuoteTable('close');}
//Вывод состояния Лука
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" поднят на ";} else {$vsmes=" опущен на ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" направо ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" налево ";};
		echo '<font size=3 color=#F0F0F0> В колчане: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> стрел.';
		echo '<br>';
		echo 'Ваш прицел'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> градусов.';
		echo '<br>';
		echo 'Ваш прицел сдвинут'.$gsmes.'на <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> градусов.';

		QuoteTable('close');
//Конец Вывода состояния Лука

				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">Поднять прицел на 1,5 градуса</a><br>
				2) <a href="?state=32">Опустить прицел на 1,5 градуса</a><br>
				3) <a href="?state=33">Сдвинуть направо на 5 градусов</a><br>
				4) <a href="?state=34">Сдвинуть налево на 5 градусов</a><br>
				5) <a href="?state=35">Стрелять</a><br>';
				QuoteTable('close');
				break;
			}
			case '35':
			{
				$_SESSION['quest1_luk_kol']--;
				//$_SESSION['quest1_luk_up']=max(0,$_SESSION['quest1_luk_up']);
				//$_SESSION['quest1_luk_right']=max(0,$_SESSION['quest1_luk_right']);
				if ($_SESSION['quest1_luk_kol']>0)
				{
					$_SESSION['quest1_step']=23;
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>Вы выстрелили. В колчане осталось '.$_SESSION['quest1_luk_kol'].' стрел.<br>';
					QuoteTable('close');
					if ($_SESSION['quest1_luk_up']==$_SESSION['quest1_luk_up_need'] AND
						$_SESSION['quest1_luk_right']==$_SESSION['quest1_luk_right_need'])
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>Вам <font size=3 color=#00CF00>удалось<font size=3 color=#F0F0F0> поразить одного противника, но остальные смекнули в чем дело и упали в траву. Один из них пополз дальше, увлекая за собой девушку, а второй, обнажив меч - ятаган - двигается к вам. <br>';
						QuoteTable('close');
						$_SESSION['quest1_step']=24;
						QuoteTable('open');
						echo'
						1) <a href="?state=311">Ретироваться</a><br>
						2) <a href="?state=312&boy">Вступить в бой</a><br>';
						QuoteTable('close');
					}
					else
					{
//Разбор полетов
						QuoteTable('open');

			if ($_SESSION['quest1_luk_up']>$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#FFB400><b>перелетела</b></font> ";}
				elseif ($_SESSION['quest1_luk_up']<$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#3F00FF><b>недолетела</b></font> ";}
			elseif ($_SESSION['quest1_luk_up']==$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#3F00FF><b>долетела</b></font> ";}
			if ($_SESSION['quest1_luk_right']<$_SESSION['quest1_luk_right_need'])
			{ $gor="<b>левее</b>.";}
				elseif ($_SESSION['quest1_luk_right']>$_SESSION['quest1_luk_right_need'])
			{ $gor="<b>правее</b>.";}
			elseif ($_SESSION['quest1_luk_right']==$_SESSION['quest1_luk_right_need'])
			{ $gor=" <b>ровно по направлению к противнику</b>.";}
			switch(abs(abs($_SESSION['quest1_luk_up'])-abs($_SESSION['quest1_luk_up_need'])))
			{
				case 0: {$dVer=" ";break;}
				case 1:{$dVer=" <b>чуть-чуть</b> ";break;}
				case 2:case 3:{$dVer=" <b>немного</b> ";break;}
				case 4:case 5:case 6:{$dVer=" <b>значительно</b> ";break;}
				case 7:case 8:case 9:case 10:{$dVer=" <b>сильно</b> ";break;}
				default:{$dVer=" <b>абсолютно</b> ";break;}
			}
			switch(abs(abs($_SESSION['quest1_luk_right'])-abs($_SESSION['quest1_luk_right_need'])))
			{
				case 0: {$dGor=" ";break;}
				case 1:{$dGor=" <b>чуть-чуть</b> ";break;}
				case 2:case 3:{$dGor=" <b>немного</b> ";break;}
				case 4:case 5:case 6:{$dGor=" <b>значительно</b> ";break;}
				case 7:case 8:case 9:case 10:{$dGor=" <b>намного</b> ";break;}
				default:{$dGor=" <b>очень намного</b> ";break;}
			}

			/*if(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])<7)
				{$dGor=$dVer;}
			elseif(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])>10)
				{$dGor=" <b>очень намного</b> ";}
			else {$dGor=" <b>намного</b> ";}*/


			echo '<font size=3 color=#F0F0F0>Вы <font color=#FF0000>промахнулись<font color=#F0F0F0>. Стрела'.$dVer.$vert.'и упала'.$dGor.$gor;

//Конец Разбор полетов
//Изменение нужного вертикального смещения
				switch ($_SESSION['quest1_wind_move'])
				{
					case "N":case"NE":case"NW":
					{
					$_SESSION['quest1_luk_up_need']=10+$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					break;
					}
					case "E":case"W":
					{
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_luk_kol'];
					break;
					}
					case "SE":case"S":case"SW":
					{
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					break;
					}
				}
//Конец Изменения нужного вертикального смещения
						$_SESSION['quest1_luk_up']=0;
						$_SESSION['quest1_luk_right']=0;
						QuoteTable('close');

//Вывод состояния Лука
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" поднят на ";} else {$vsmes=" опущен на ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" направо ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" налево ";};
		echo '<font size=3 color=#F0F0F0> В колчане: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> стрел.';
		echo '<br>';
		echo 'Ваш прицел'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> градусов.';
		echo '<br>';
		echo 'Ваш прицел сдвинут'.$gsmes.'на <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> градусов.';

		QuoteTable('close');
//Конец Вывода состояния Лука

						echo '<br><br><br>';
						QuoteTable('open');
						echo'
						1) <a href="?state=31">Поднять прицел на 1,5 градуса</a><br>
						2) <a href="?state=32">Опустить прицел на 1,5 градуса</a><br>
						3) <a href="?state=33">Сдвинуть направо на 5 градусов</a><br>
						4) <a href="?state=34">Сдвинуть налево на 5 градусов</a><br>
						5) <a href="?state=35">Стрелять</a><br>';
						QuoteTable('close');
					}
				}
				else
				{
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>У вас закончились стрелы. Ваши действия: <br>';
					$_SESSION['quest1_search_ohr']=0;
					$_SESSION['quest1_have_key']=0;
					$_SESSION['quest1_step']=1;
					$_SESSION['quest1_need_key']=0;
					$_SESSION['quest1_search_room']=0;
					$_SESSION['quest1_count_money']=0;
					$_SESSION['quest1_bad_portal']=0;
					$_SESSION['quest1_take_shlem']=0;
					$_SESSION['quest1_take_weapon']=0;
					$_SESSION['quest1_ves_plita']=0;
					$_SESSION['quest1_get_gp']=0;
					$_SESSION['quest1_searh_trup']=0;
					$_SESSION['quest1_money_girl']=0;
					$_SESSION['quest1_wind_move']=0;
					$_SESSION['quest1_wind_might']=0;
					QuoteTable('close');
					QuoteTable('open');
					echo'
					1) <a href="?state=01">С криком &quot;Эй, вы что делаете?!&quot; побежать к месту действия.</a><br>
					2) <a href="?state=02">Побежать, выхватив меч, с криком &quot;Прочь от девушки, нечестивые псы!&quot;</a><br>
					4) <a href="?state=04">Пригнуться и последовать за странной компанией</a><br>
					5) <a href="?state=05">Пожать плечами и продолжить путь</a>';
					QuoteTable('close');
				}
				break;
			}
			case '311':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы позорно сбежали с поля боя';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '320':
			{
				$_SESSION['quest1_step']=26;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы одержали победу над одним из конвоиров девушки, но второй вместе с ней уже скрылся за холмом.<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=321">Броситься в погоню.</a><br>
				2) <a href="?state=322&boy">Махнуть рукой и уйти</a><br>';
				QuoteTable('close');
				break;
			}
			case '322':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы вытерли меч о траву, вложили его в ножны и продолжили свой путь.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '321':
			{
				$_SESSION['quest1_step']=27;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Забежав за холм, вы обнаружили на обратной стороне его массивные створки дверей, уводящий под холм, а так же двух стражников, вовсе не настроенных на дружескиую беседу… Что ж, где один, там и два… <br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=3211">Убежать, пока они не сообразили, в чем дело.</a><br>
				2) <a href="?state=3212&boy">Выхватить меч и храбро сражаться</a><br>';
				QuoteTable('close');
				break;
			}
			case '32120':
			{
				$_SESSION['quest1_step']=29;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы ловко уложили парней у входа, и, отчасти удивляясь самому себе, отперли двери ключом, взятым с одного из трупов, вошли внутрь.<br>
Вы оказались в помещении средней освещенности, но успели заметить, как тот самый, третий, с булавой, тащит бедную девушку вниз по лестнице и входит в дверь. Все ближе и ближе…
<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=32121">Повернуть назад, пока не поздно</a><br>
				2) <a href="?state=32122">Последовать за ними</a><br>';
				QuoteTable('close');
				break;
			}
			case '32121':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Проанализировав обстановку, вы поняли, что за той дверью может оказаться как один этот парень, так и десяток разъяренных балрогов, так что вы благоразумно решили не рисковать..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '32122':
			{
				$_SESSION['quest1_step']=30;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Спустившись по лестнице и отворив дверь, вы вовсе не обрадовались открывшейся картине. В овальной комнате, за парой столов, сидели пять человек, при мечах и в доспехах, а тот, с булавой, был шестым. Оправившись от удивления, они уже начали поднимать оружие. Да, будет жарко…<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=321221&boy">Ворваться в комнату и попытаться всех перерезать.</a><br>
				2) <a href="?state=321222">Остаться в дверном проеме.</a><br>
				3) <a href="?state=321223">Сказать &quot;Э-э, простите, я, кажется, ошибся дверью&quot; и, захлопнув дверь, быстро выбежатьиз комнаты на улицу.</a><br>';
				QuoteTable('close');
				break;
			}
			case '321223':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Выйдя на улицу и проанализировав обстановку, вы поняли, что вам не по силам справиться со всеми разбойниками, так что вы благоразумно решили не рисковать..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212210':
			{
				$_SESSION['quest1_step']=32;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>После долгого и кровопролитного боя последний из супостатов, наконец, рухнул на пол с продырявленной грудью. Да, славная была битва! В комнате остается еще одна дверь. Отступать поздно, вы входите в нее, и оказываетесь в помещение наподобие тюрьмы - с двумя камерами, столиком и толстым надсмотрщиком с небольшим мечом за ним. Пф, какой он вам противник после пяти головорезов в доспехах!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">Умертвить бедолагу</a><br>';
				QuoteTable('close');
				break;
			}
			case '321222':
			{
				$_SESSION['quest1_step']=34;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Очень умно! Теперь они смогут нападать только по одному!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">Первый пошел!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212222':
			{
				$_SESSION['quest1_step']=36;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Очень умно! Теперь они смогут нападать только по одному!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">Следующий!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212223':
			{
				$_SESSION['quest1_step']=38;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Очень умно! Теперь они смогут нападать только по одному!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">Еще кандидат к Мандосу?!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212224':
			{
				$_SESSION['quest1_step']=40;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Очень умно! Теперь они смогут нападать только по одному!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">Хэ, мне начинает это нравиться!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212225':
			{
				$_SESSION['quest1_step']=42;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Очень умно! Теперь они смогут нападать только по одному!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">Если бы каждый раз платили по сто монет…</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212226':
			{
				$_SESSION['quest1_step']=44;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Очень умно! Теперь они смогут нападать только по одному!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">Ну давай, с булавой, покажи, какой ты крутой!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212227':
			{
				$_SESSION['quest1_step']=32;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>И вот, наконец, последний из противников, выронив свою булаву, упал замертво. В комнате остается еще одна дверь. Отступать поздно, вы входите в нее, и оказываетесь в помещение наподобие тюрьмы - с двумя камерами, столиком и толстым надсмотрщиком с небольшим мечом за ним. Пф, какой он вам противник после пяти головорезов в доспехах!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">Умертвить бедолагу</a><br>';
				QuoteTable('close');
				break;
			}
			case '4':
			{
				$_SESSION['quest1_step']=46;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Проследив за девушкой и тремя громилами, вы обнаружили, что те вошли в массивные двери в холме. Но у дверей стоят двое стражников, незамеченным не проскочить. Как же быть?<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=41">Наплевать и уйти</a><br>
				2) <a href="?state=42">Подождать ночи</a><br>
				3) <a href="?state=43">Обойти холм вокруг</a><br>';
				QuoteTable('close');
				break;
			}
			case '41':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Не дожидаясь, пока двое у дверей заметят вас и примутся кромсать своими двуручниками, вы ушли..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '42':
			{
				$_SESSION['quest1_step']=46;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы залегли в траве и стали ждать. Когда стемнело, стража сменилась.. Хм, эти выглядят послабее, да и не с двуручниками, а с обычными мечами.. Что делать?<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=421">Напасть на них</a><br>
				2) <a href="?state=43">Обойти холм вокруг</a><br>';
				QuoteTable('close');
				break;
			}
			case '421':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Пока вы возились с одним, второй успел поднять тревогу. Из недр холма выбежало еще человек десять, которые без труда расправились с вами.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '43':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_code_zamok']='';
				echo '<font size=3 color=#F0F0F0>Прошагав вокруг холма, вы обнаружили, похоже, запасной вход. Проблема в том, что он заперт каким-то странным механизмом. В три столба идут кнопки: в первом - семь цветов радуги, затем какие-то символы,  связанные с природой, потом снова цвета, и в последнем - различные виды оружия. Вы проверили - пока не нажмешь кнопку из первого столбца, не нажимаются кнопки из второго, и так далее. Похоже, нужно ввести особую комбинацию. Вверху, над дверью, написано: "Онкарс оннечо листвео, кка нилкик ишан, ровикю гевордун гробеныёна!" Очевидно, это как-то должно напоминать входящим о коде. Но что бы это значило?<br>';
				QuoteTable('close');
				QuoteTable('open');
		echo '<font size=3 color=#F0F0F0>';
				$ar = array("<font color=#FF0000>Красный</font>","<font color=#FFB400>Оранжевый</font>","<font color=#EDFE30>Желтый</font>","<font color=#00CF00>Зеленый</font>","<font color=#35EEEC>Голубой</font>","<font color=#3F10CE>Синий</font>","<font color=#EE3FEC>Фиолетовый</font>");
				shuffle($ar);
				$_SESSION['quest1_array_color1']=$ar;
				for ($i=0;$i<count($ar);$i++)
				{
					echo '<font color=#F0F0F0>';
					echo ($i+1).') <a href="?state=4310&i='.$i.'">'.$ar[$i].'</a><br>';
				}
		echo '<font color=#F0F0F0>';
				echo'8) <a href="?state=431"><font color=#F0F0F0>Уйти</a><br>';
				QuoteTable('close');
				break;
			}
			case '431':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы решили не ломать голову над этой чепухой и продолжили свой путь.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '4310':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_color1']=$_SESSION['quest1_array_color1'][$i];
				if ($_SESSION['quest1_array_color1'][$i]=="<font color=#FF0000>Красный</font>") $_SESSION['quest1_code_zamok'].='1';
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы выбрали '.$_SESSION['quest1_array_color1'][$i];
				QuoteTable('close');
				echo '<font size=3 color=#F0F0F0>Прошагав вокруг холма, вы обнаружили, похоже, запасной вход. Проблема в том, что он заперт каким-то странным механизмом. В три столба идут кнопки: в первом - семь цветов радуги, затем какие-то символы,  связанные с природой, потом снова цвета, и в последнем - различные виды оружия. Вы проверили - пока не нажмешь кнопку из первого столбца, не нажимаются кнопки из второго, и так далее. Похоже, нужно ввести особую комбинацию. Вверху, над дверью, написано: "Онкарс оннечо листвео, кка нилкик ишан, ровикю гевордун гробеныёна!" Очевидно, это как-то должно напоминать входящим о коде. Но что бы это значило?<br>';
				QuoteTable('close');
				QuoteTable('open');

				$ar = array("Солнце","Луна","Звезда","Дерево","Гора","Река","Цветок","Глаза");
				shuffle($ar);
				$_SESSION['quest1_array_priroda']=$ar;
				for ($i=0;$i<count($ar);$i++)
				{
					echo ($i+1).') <a href="?state=43100&i='.$i.'">'.$ar[$i].'</a><br>';
				}
				echo'9) <a href="?state=431">Уйти</a><br>';
				QuoteTable('close');
				break;
			}
			case '43100':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_priroda']=$_SESSION['quest1_array_priroda'][$i];
				if ($_SESSION['quest1_array_priroda'][$i]=="Луна") $_SESSION['quest1_code_zamok'].='1';
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы выбрали '.$_SESSION['quest1_array_priroda'][$i];
				QuoteTable('close');
				echo '<font size=3 color=#F0F0F0>Прошагав вокруг холма, вы обнаружили, похоже, запасной вход. Проблема в том, что он заперт каким-то странным механизмом. В три столба идут кнопки: в первом - семь цветов радуги, затем какие-то символы,  связанные с природой, потом снова цвета, и в последнем - различные виды оружия. Вы проверили - пока не нажмешь кнопку из первого столбца, не нажимаются кнопки из второго, и так далее. Похоже, нужно ввести особую комбинацию. Вверху, над дверью, написано: "Онкарс оннечо листвео, кка нилкик ишан, ровикю гевордун гробеныёна!" Очевидно, это как-то должно напоминать входящим о коде. Но что бы это значило?<br>';
				QuoteTable('close');
				QuoteTable('open');

				echo '<font size=3 color=#F0F0F0>';
				$ar = array("<font color=#FF0000>Красный</font>","<font color=#FFB400>Оранжевый</font>","<font color=#EDFE30>Желтый</font>","<font color=#00CF00>Зеленый</font>","<font color=#35EEEC>Голубой</font>","<font color=#3F10CE>Синий</font>","<font color=#EE3FEC>Фиолетовый</font>");
				shuffle($ar);
				$_SESSION['quest1_array_color2']=$ar;
				for ($i=0;$i<count($ar);$i++)
				{
					echo '<font color=#F0F0F0>';
					echo ($i+1).') <a href="?state=431000&i='.$i.'">'.$ar[$i].'</a><br>';
				}
		echo '<font color=#F0F0F0>';
				echo'8) <a href="?state=431"><font color=#F0F0F0>Уйти</a><br>';
				QuoteTable('close');
				break;
			}
			case '431000':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_color2']=$_SESSION['quest1_array_color2'][$i];
				if ($_SESSION['quest1_array_color2'][$i]=="<font color=#FF0000>Красный</font>") $_SESSION['quest1_code_zamok'].='1';
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы выбрали '.$_SESSION['quest1_array_color2'][$i];
				QuoteTable('close');
				echo '<font size=3 color=#F0F0F0>Прошагав вокруг холма, вы обнаружили, похоже, запасной вход. Проблема в том, что он заперт каким-то странным механизмом. В три столба идут кнопки: в первом - семь цветов радуги, затем какие-то символы,  связанные с природой, потом снова цвета, и в последнем - различные виды оружия. Вы проверили - пока не нажмешь кнопку из первого столбца, не нажимаются кнопки из второго, и так далее. Похоже, нужно ввести особую комбинацию. Вверху, над дверью, написано: "Онкарс оннечо листвео, кка нилкик ишан, ровикю гевордун гробеныёна!" Очевидно, это как-то должно напоминать входящим о коде. Но что бы это значило?<br>';
				QuoteTable('close');
				QuoteTable('open');

				$ar = array("Меч","Щит","Булава","Копье","Лук","Топор","Арбалет","Дубина");
				shuffle($ar);
				$_SESSION['quest1_array_weapon']=$ar;
				for ($i=0;$i<count($ar);$i++)
				{
					echo ($i+1).') <a href="?state=4310000&i='.$i.'">'.$ar[$i].'</a><br>';
				}
				echo'9) <a href="?state=431">Уйти</a><br>';
				QuoteTable('close');
				break;
			}
			case '4310000':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_weapon']=$_SESSION['quest1_array_weapon'][$i];
				if ($_SESSION['quest1_array_weapon'][$i]=="Меч") $_SESSION['quest1_code_zamok'].='1';
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы выбрали '.$_SESSION['quest1_array_weapon'][$i];
				QuoteTable('close');
				echo '<font size=3 color=#F0F0F0>Прошагав вокруг холма, вы обнаружили, похоже, запасной вход. Проблема в том, что он заперт каким-то странным механизмом. В три столба идут кнопки: в первом - семь цветов радуги, затем какие-то символы,  связанные с природой, потом снова цвета, и в последнем - различные виды оружия. Вы проверили - пока не нажмешь кнопку из первого столбца, не нажимаются кнопки из второго, и так далее. Похоже, нужно ввести особую комбинацию. Вверху, над дверью, написано: "Онкарс оннечо листвео, кка нилкик ишан, ровикю гевордун гробеныёна!" Очевидно, это как-то должно напоминать входящим о коде. Но что бы это значило?<br>';
				QuoteTable('close');
				QuoteTable('open');

				echo'1) <a href="?state=432">Попробовать код "'.$_SESSION['quest1_color1'].'"-"'.$_SESSION['quest1_priroda'].'"-"'.$_SESSION['quest1_color2'].'"-"'.$_SESSION['quest1_weapon'].'"</a><br>';
				echo'2) <a href="?state=431">Уйти</a><br>';
				QuoteTable('close');
				break;
			}
			case '432':
			{
				if ($_SESSION['quest1_code_zamok']=='1111')
				{
					$_SESSION['quest1_step']=48;
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>Вы ввели код, в механизме что-то заскрипело, и секунд через 10 дверь отворилась. Вы входите внутрь и тут же спотыкаетесь в полумраке о непонятно зачем тут стоящую кирку. Ух, кажется, шума никто не заметил. Вы видите, что наверх ведет какая-то лестница, а напротив ее имеется дверь, из-за которой раздаются неясные звуки. Заглянув на лестницу, вы обнаруживаете сверху пару стражников. Надо быть осторожнее…';
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'1) <a href="?state=4301">Распахнуть дверь и войти в комнату</a><br>';
					echo'2) <a href="?state=4302">Заглянуть в замочную скважину</a><br>';
					QuoteTable('close');
				}
				else
				{
					$_SESSION['quest1_step']=46;
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>Ничего не происходит';
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'1) <a href="?state=43">Сделать еще одну попытку</a><br>';
					echo'2) <a href="?state=431">Уйти</a><br>';
					QuoteTable('close');
				}
				break;
			}
			case '4301':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Отворив дверь, вы вовсе не обрадовались открывшейся картине. В овальной комнате за парой столов сидели пять человек, при мечах и в доспехах, а тот, с булавой, что тащил девушку, был шестым. Оправившись от удивления, они уже начали поднимать оружие. Вы понимаете, что вам с ними не справиться…';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '4302':
			{
				$_SESSION['quest1_step']=49;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>В овальной комнате, за парой столов, сидели пять человек, при мечах и в доспехах. А дверь напротив шестой, с булавой, затаскивал девушку. Через некоторое время он вышел оттуда, и, похоже, с чувством выполненного долга приложился к стоявшей на одном из столов кружке. Вы понимаете, что с шестерыми вам не справиться, но прикинув размеры холма, догадываетесь, что стены комнаты, куда увели девушку, не такие уж толстые. Прихватив удачно подвернувшуюся кирку вы вышли из холма и направились к той его стороне, которая, по вашему мнению, является стеной той комнаты. Сложность заключается в том, что кирка уже не новая, долго не выдержит.. А копать вам около 0.8 метра. Вы можете ударить сильно, средне или слабо, причем более двух раз подряд одним ударом пользоваться нельзя, а то может возникнуть резонанс и весь холм обрушится к балрогам морготовым, как и в случае, если слишком долго колотить несчастную стену. <br>Если условно принять, что у кирки 100% прочности, то за один сильный удар вы копаете 0,22 метра и снимаете 28% прочности, за средний - 0,07 метра и 8% прочности, а за слабый - 0,01 метр и 1% прочности. Вперед!';
				$_SESSION['quest1_kirka']=100;
				$_SESSION['quest1_distance']=800;
				$_SESSION['quest1_last_udar']=0;
				$_SESSION['quest1_prelast_udar']=0;
				$_SESSION['quest1_nom_udar']=0;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'1) <a href="?state=43020&step=1">Ударить сильным</a><br>';
				echo'2) <a href="?state=43020&step=2">Ударить средним</a><br>';
				echo'3) <a href="?state=43020&step=3">Ударить слабым</a><br>';
				echo'4) <a href="?state=43024">Бросить кирку и уйти</a><br>';
				QuoteTable('close');
				break;
			}
			case '43020':
			{
				$_SESSION['quest1_step']=50;
				if (isset($step) AND $step>=1 AND $step<=3)
				{
					$_SESSION['quest1_nom_udar']++;
					if ($step==1)
					{
						 $_SESSION['quest1_kirka']-=28;
						 $_SESSION['quest1_distance']-=220;
					}
					elseif ($step==2)
					{
						 $_SESSION['quest1_kirka']-=8;
						 $_SESSION['quest1_distance']-=70;
					}
					elseif ($step==3)
					{
						 $_SESSION['quest1_kirka']-=1;
						 $_SESSION['quest1_distance']-=10;
					}
					$break = 0;
					$rand = mt_rand(0,100);
					if ($_SESSION['quest1_nom_udar']==11 AND $rand<=3) $break=1;
					if ($_SESSION['quest1_nom_udar']==12 AND $rand<=15) $break=1;
					if ($_SESSION['quest1_nom_udar']==13 AND $rand<=30) $break=1;
					if ($_SESSION['quest1_nom_udar']==14 AND $rand<=50) $break=1;
					if ($_SESSION['quest1_nom_udar']==15 AND $rand<=70) $break=1;
					if ($_SESSION['quest1_nom_udar']==16 AND $rand<=75) $break=1;
					if ($_SESSION['quest1_nom_udar']==17 AND $rand<=80) $break=1;
					if ($_SESSION['quest1_nom_udar']==18 AND $rand<=85) $break=1;
					if ($_SESSION['quest1_nom_udar']==19 AND $rand<=90) $break=1;
					if ($_SESSION['quest1_nom_udar']==20 AND $rand<=99) $break=1;

					if (($_SESSION['quest1_prelast_udar']==$step AND $_SESSION['quest1_last_udar']==$step) OR $break==1)
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>Раздался ужасный грохот, и весь холм - ого! - провалился внутрь себя. Вы бросили кирку и, беспечно насвистывая и поглядывая в небо, удалились.';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
						QuoteTable('close');
						QuoteTable('open');
						$_SESSION['quest1_exit']=1;
						echo'<a href="?exit">Квест провален</a><br>';
						QuoteTable('close');
					}
					elseif ($_SESSION['quest1_kirka']>=0 AND $_SESSION['quest1_distance']<=0)
					{
						$_SESSION['quest1_step']=51;
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>Последний удар и - ваша кирка больше не встретила сопротивления. Да, вам удалось! Вы, довольный собой, влезаете в комнату и начинаете отряхиваться, и тут только замечаете, что вы не одни. Есть еще толстый охранник, который с отвисшей челюстью и вытаращенными, полными безграничного удивления глазами, дрожащей рукой шарит у пояса в поисках, очевидно, оружия.';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo'<a href="?state=430201&boy">Выхватить меч</a><br>';
						QuoteTable('close');
					}
					elseif ($_SESSION['quest1_kirka']<=0)
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>О, нет! Вы сломали кирку! Похоже, больше шансов спасти таинственную девушку у вас нет..';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
						QuoteTable('close');
						QuoteTable('open');
						$_SESSION['quest1_exit']=1;
						echo'<a href="?exit">Квест провален</a><br>';
						QuoteTable('close');
					}
					else
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>В овальной комнате, за парой столов, сидели пять человек, при мечах и в доспехах. А дверь напротив шестой, с булавой, затаскивал девушку. Через некоторое время он вышел оттуда, и, похоже, с чувством выполненного долга приложился к стоявшей на одном из столов кружке. Вы понимаете, что с шестерыми вам не справиться, но прикинув размеры холма, догадываетесь, что стены комнаты, куда увели девушку, не такие уж толстые. Прихватив удачно подвернувшуюся кирку вы вышли из холма и направились к той его стороне, которая, по вашему мнению, является стеной той комнаты. Сложность заключается в том, что кирка уже не новая, долго не выдержит.. А копать вам около 0.8 метра. Вы можете ударить сильно, средне, или слабо, причем более двух раз подряд одним ударом пользоваться нельзя, а то может возникнуть резонанс и весь холм обрушится к балрогам морготовым. Если условно принять, что у кирки 100% прочности, то за один сильный удар вы копаете 0,22 метра и снимаете 28% прочности, за средний - 0,07 метра и 8% прочности, а за слабый - 0,01 метр и 1% прочности. Вперед!';
						QuoteTable('close');
						echo '<br>';
						if ($_SESSION['quest1_nom_udar']>=2)
						{
				$_SESSION['quest1_prelast_udar'] = $_SESSION['quest1_last_udar'];
						}
						QuoteTable('open');
						echo 'Состояние: прочность кирки <font color=#FF0000>'.(/*100-*/$_SESSION['quest1_kirka']).'<font color=#F0F0F0>%, толщина стены <font color=#FF0000>'./*round((800-*/$_SESSION['quest1_distance']/*)/1000,2)*/.'<font color=#F0F0F0> см.';
						QuoteTable('close');
						echo '<br>';
						$_SESSION['quest1_last_udar']=$step;
						QuoteTable('open');
						echo'1) <a href="?state=43020&step=1">Ударить сильным</a><br>';
						echo'2) <a href="?state=43020&step=2">Ударить средним</a><br>';
						echo'3) <a href="?state=43020&step=3">Ударить слабым</a><br>';
						echo'4) <a href="?state=43024">Бросить кирку и уйти</a><br>';
						QuoteTable('close');

					}
				}
				break;
			}
			case '43024':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вы оставили в покое злополучный холм и пошли совей дорогой.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '5':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Вспомнив известную народную мудрость "Меньше знаешь - дольше проживешь", Вы решили не связываться со странной компанией и пошли совей дорогой.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>Вы провалили квест. Вы можете попробовать пройти этот квест еще раз через некоторое время';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">Квест провален</a><br>';
				QuoteTable('close');
				break;
			}
			case '777':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>Наконец, от тучи жуков остались только пятна на полу и - фу! - ужасно мерзкий запах! Вы поспешили скорее покинуть это неприятное место и шагнули в портал. На той его стороне, в поле около стен Гильдии, вы обнаружили мешок со своими пожитками, которые уже считали безвозвратно утерянными, и записку &quot;Еще раз спасибо!&quot;';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$_SESSION['quest1_exit']=99;
				echo '<font size=3 color=#F0F0F0>ПОЗДРАВЛЯЮ!<br>';
				echo'<a href="?exit">Квест пройден!</a><br>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				if ((isset($_SESSION['quest1_get_gp']) AND $_SESSION['quest1_get_gp']!=0)OR(isset($_SESSION['quest1_take_weapon']) AND $_SESSION['quest1_take_weapon']==1)OR(isset($_SESSION['quest1_take_shlem']) AND $_SESSION['quest1_take_shlem']==1))
				{
					if (isset($_SESSION['quest1_get_gp']) AND $_SESSION['quest1_get_gp']!=0)
					{
						echo 'Вы заработали '.$_SESSION['quest1_get_gp'].' золотых монет<br>';
					}
					if (isset($_SESSION['quest1_take_weapon']) AND $_SESSION['quest1_take_weapon']==1)
					{
						list($item_name) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=4"));
						echo 'Вы получили предмет: '.$item_name.'<br>';
					}
					if (isset($_SESSION['quest1_take_shlem']) AND $_SESSION['quest1_take_shlem']==1)
					{
						list($item_name) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=8"));
						echo 'Вы получили предмет: '.$item_name.'<br>';
					}
				}
				echo 'Вы получили '.$get_exp.' очков опыта<br>';
				QuoteTable('close');
				break;
			}
		}
		echo '</p>';
		OpenTable('close');
	}
	else
	{
		$sost = mysql_fetch_array(myquery("SELECT sost,finish FROM game_quest_users WHERE user_id=$user_id AND quest_id=1 AND (last_time>=$last_time OR sost>0)"));
		if ($sost['sost']==2)
		{
			//закрыли браузер во время боя с ботом и потом зависли
			//надо обнулить состояние и пусть начинает квест заново, если finish==0 или закончить квест
			if ($sost['finish']==0)
			{
				myquery("UPDATE game_quest_users SET sost=0,last_time=0 WHERE user_id=$user_id AND quest_id=1");   
			}
			ForceFunc($user_id,5);
			setLocation("../act.php");
		}
	}
}

//OpenTable('title');
//echo '<a href="?return">В начало квеста</a>';
//OpenTable('close');

//OpenTable('title');
//echo '<a href="?exit_from_quest">Выйти из квеста</a>';
//OpenTable('close');
?>