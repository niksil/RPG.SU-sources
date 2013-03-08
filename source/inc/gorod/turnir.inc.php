<?php

if (function_exists("start_debug")) start_debug(); 

function start_group($tur)
{
	global $char;
	//все места заняты - начинаем бой
	//создаем бой
	$ins = myquery("insert into combat (hod,combat_type,map_name,map_xpos,map_ypos,start_time,time_last_hod,turnir_type) values (1,'9','".$char['map_name']."','".$char['map_xpos']."','".$char['map_ypos']."',".time().",".time().",".$tur['format'].")");
	$uid = mysql_insert_id();
	$time_hod = time();
	myquery("DELETE FROM game_combats_log WHERE boy=$uid");
	$log=myquery("insert into game_combats_log (boy,hod,time,type,map_name,map_xpos,map_ypos) values ($uid,0,".time().",9,'".$char['map_name']."','".$char['map_xpos']."','".$char['map_ypos']."')");

	//закидываем туда игроков
	$sel_users = myquery("SELECT game_users_active_host.host_more,game_users_active.host,game_turnir_users.side,view_active_users.*,game_users_data.sex FROM game_turnir_users,view_active_users,game_users_data,game_users_active,game_users_active_host WHERE game_users_data.user_id=game_turnir_users.user_id AND game_turnir_users.user_id=view_active_users.user_id AND game_turnir_users.turnir_id=".$tur['id']." AND game_users_active.user_id=view_active_users.user_id AND game_users_active_host.user_id=view_active_users.user_id");
	while ($us=mysql_fetch_array($sel_users))
	{
		myquery("DELETE FROM combat_users WHERE user_id=".$us['user_id']."");
		myquery("DELETE FROM combat_users_exp WHERE user_id=".$us['user_id']."");
		myquery("insert into combat_users (
			combat_id,user_id,npc,time_last_active,name,clevel,side,
			HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,
			MS_KULAK,MS_WEAPON,MS_ART,MS_PARIR,MS_LUK,MS_SWORD,MS_AXE,MS_SPEAR,MS_THROW,
			HP_MAX,MP_MAX,STM_MAX,
			lucky,pol,avatar,sklon,race,clan_id,HP_start,hod_start,not_gp) values (
			$uid,".$us['user_id'].",0,".$time_hod.",'".$us['name']."',".$us['clevel'].",".$us['side'].",
			".$us['HP'].",".$us['MP'].",".$us['STM'].",".$us['STR'].",".$us['DEX'].",".$us['SPD'].",".$us['VIT'].",".$us['NTL'].",".$us['PIE'].",
			".$us['MS_KULAK'].",".$us['MS_WEAPON'].",".$us['MS_ART'].",".$us['MS_PARIR'].",".$us['MS_LUK'].",'".$us['MS_SWORD']."','".$us['MS_AXE']."','".$us['MS_SPEAR']."','".$us['MS_THROW']."',
			".$us['HP_MAX'].",".$us['MP_MAX'].",".$us['STM_MAX'].",
			'".$us['lucky']."','".$us['sex']."','".$us['avatar']."','".$us['sklon']."','".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$us['race'].""),0,0)."',".$us['clan_id'].",".$us['HP_MAX'].",1,1)");
		combat_setFunc($us['user_id'],5,$uid);
		set_delay_reason_id($us['user_id'],46);
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">Начинается турнирный групповой бой</span>");
		myquery("INSERT INTO game_log (`message`,`date`,`fromm`,`too`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,".$us['user_id'].")");
		myquery("DELETE FROM game_combats_users WHERE (boy=$uid AND user_id=".$us['user_id'].")");
		myquery("INSERT INTO game_combats_users (boy,user_id) VALUES ($uid,".$us['user_id'].")");
		myquery("INSERT INTO combat_lose_user (combat_id,user_id,host,host_more) VALUES ($uid,".$us['user_id'].",".$us['host'].",'".$us['host_more']."')");
		$sel_arco = myquery("SELECT * FROM arcomage_users WHERE user_id=".$us['user_id']."");
		if (mysql_num_rows($sel_arco)>0)
		{
			$arco = mysql_fetch_array($sel_arco);
			myquery("DELETE FROM arcomage WHERE id='".$arco['arcomage_id']."'");
			myquery("DELETE FROM arcomage_users_cards WHERE arcomage_id='".$arco['arcomage_id']."'");
			myquery("DELETE FROM arcomage_history WHERE arcomage_id='".$arco['arcomage_id']."'");
			myquery("DELETE FROM arcomage_users WHERE arcomage_id='".$arco['arcomage_id']."'");
		}
		myquery("DELETE FROM arcomage_call WHERE user_id=".$us['user_id']."");
		ForceFunc($us['user_id'],1);
	}                     
	myquery("DELETE game_turnir,game_turnir_users FROM game_turnir,game_turnir_users WHERE (game_turnir.id=".$tur['id'].") AND (game_turnir.id=game_turnir_users.turnir_id) ");
	setLocation("http://".DOMAIN."/combat.php"); 
}

function start_chaos($tur)
{
	global $char;
	//все места заняты - начинаем бой
	//создаем бой
	$ins = myquery("insert into combat (hod,combat_type,map_name,map_xpos,map_ypos,start_time,time_last_hod,turnir_type) values (1,'11','".$char['map_name']."','".$char['map_xpos']."','".$char['map_ypos']."',".time().",".time().",".$tur['format'].")");
	$uid = mysql_insert_id();
	$time_hod = time();
	myquery("DELETE FROM game_combats_log WHERE boy=$uid");
	$log=myquery("insert into game_combats_log (boy,hod,time,type,map_name,map_xpos,map_ypos) values ($uid,0,".time().",11,'".$char['map_name']."','".$char['map_xpos']."','".$char['map_ypos']."')");

	//закидываем туда игроков рандомно
	$sel_users = myquery("SELECT game_users_active_host.host_more,game_users_active.host,game_turnir_users.side,view_active_users.*,game_users_data.sex FROM game_turnir_users,view_active_users,game_users_data,game_users_active,game_users_active_host WHERE game_users_data.user_id=game_turnir_users.user_id AND game_turnir_users.user_id=view_active_users.user_id AND game_turnir_users.turnir_id=".$tur['id']." AND game_users_active.user_id=view_active_users.user_id AND game_users_active_host.user_id=view_active_users.user_id ORDER BY RAND()");
	$user_side = 2;
	while ($us=mysql_fetch_array($sel_users))
	{
		$user_side = 3-$user_side;
		myquery("DELETE FROM combat_users WHERE user_id=".$us['user_id']."");
		myquery("DELETE FROM combat_users_exp WHERE user_id=".$us['user_id']."");
		myquery("insert into combat_users (
			combat_id,user_id,npc,time_last_active,name,clevel,side,
			HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,
			MS_KULAK,MS_WEAPON,MS_ART,MS_PARIR,MS_LUK,MS_SWORD,MS_AXE,MS_SPEAR,MS_THROW,
			HP_MAX,MP_MAX,STM_MAX,
			lucky,pol,avatar,sklon,race,clan_id,HP_start,hod_start,not_gp) values (
			$uid,".$us['user_id'].",0,".$time_hod.",'".$us['name']."',".$us['clevel'].",".$user_side.",
			".$us['HP'].",".$us['MP'].",".$us['STM'].",".$us['STR'].",".$us['DEX'].",".$us['SPD'].",".$us['VIT'].",".$us['NTL'].",".$us['PIE'].",
			".$us['MS_KULAK'].",".$us['MS_WEAPON'].",".$us['MS_ART'].",".$us['MS_PARIR'].",".$us['MS_LUK'].",'".$us['MS_SWORD']."','".$us['MS_AXE']."','".$us['MS_SPEAR']."','".$us['MS_THROW']."',
			".$us['HP_MAX'].",".$us['MP_MAX'].",".$us['STM_MAX'].",
			'".$us['lucky']."','".$us['sex']."','".$us['avatar']."','".$us['sklon']."','".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$us['race'].""),0,0)."',".$us['clan_id'].",".$us['HP_MAX'].",1,1)");
		combat_setFunc($us['user_id'],5,$uid);
		set_delay_reason_id($us['user_id'],44);
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">Начинается турнирный хаотичный бой</span>");
		myquery("INSERT INTO game_log (`message`,`date`,`fromm`,`too`,ptype) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,".$us['user_id'].",1)");
		myquery("DELETE FROM game_combats_users WHERE (boy=$uid AND user_id=".$us['user_id'].")");
		myquery("INSERT INTO game_combats_users (boy,user_id) VALUES ($uid,".$us['user_id'].")");
		myquery("INSERT INTO combat_lose_user (combat_id,user_id,host,host_more) VALUES ($uid,".$us['user_id'].",".$us['host'].",'".$us['host_more']."')");
		$sel_arco = myquery("SELECT * FROM arcomage_users WHERE user_id=".$us['user_id']."");
		if (mysql_num_rows($sel_arco)>0)
		{
			$arco = mysql_fetch_array($sel_arco);
			myquery("DELETE FROM arcomage WHERE id='".$arco['arcomage_id']."'");
			myquery("DELETE FROM arcomage_users_cards WHERE arcomage_id='".$arco['arcomage_id']."'");
			myquery("DELETE FROM arcomage_history WHERE arcomage_id='".$arco['arcomage_id']."'");
			myquery("DELETE FROM arcomage_users WHERE arcomage_id='".$arco['arcomage_id']."'");
		}
		myquery("DELETE FROM arcomage_call WHERE user_id=".$us['user_id']."");
		ForceFunc($us['user_id'],1);
	}
	myquery("DELETE game_turnir,game_turnir_users FROM game_turnir,game_turnir_users WHERE (game_turnir.id=".$tur['id'].") AND (game_turnir.id=game_turnir_users.turnir_id) ");
	setLocation("http://".DOMAIN."/combat.php"); 
}

if (!isset($from_boy)) 
{
	$from_boy = false;
	echo '<img src="http://'.IMG_DOMAIN.'/gorod/bank/turnir.jpg" alt="">';
	$main_url = "http://".DOMAIN."/lib/town.php?option=".$option;
}
else
{
	$town = 0;
	$option = 0;
	$main_url = "http://".DOMAIN."/act.php?func=boy";
}

echo'<style type="text/css">@import url("../style/global.css");</style>';
if (!$from_boy)
{
	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено пользоваться турниром!';
		echo '<br><br><br><a href="town.php">Выйти в город</a>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
}
if ($town!=0 OR $from_boy)
{
	$img='http://'.IMG_DOMAIN.'/race_table/elf/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top">';
	
	//проверка начала боев без комплекта участников
	$sel = myquery("SELECT * FROM game_turnir WHERE (type=3 OR type=5) AND (timestamp+timeout*60)<UNIX_TIMESTAMP()");
	while ($t = mysql_fetch_array($sel))
	{
		//проверим кол-во игроков с каждой стороны. Должно быть не менее 1 с каждой стороны для группового боя, и не менее 2 для хаотичного
		if ($t['type']==3)
		{
			//групповой бой
			$kol1 = mysqlresult(myquery("SELECT COUNT(*) FROM game_turnir_users WHERE turnir_id=".$t['id']." AND side=1"),0,0);
			$kol2 = mysqlresult(myquery("SELECT COUNT(*) FROM game_turnir_users WHERE turnir_id=".$t['id']." AND side=2"),0,0);
			if ($kol1>0 AND $kol2>0)
			{
				start_group($t);
			}
		}
		else
		{
			//хаотичный бой
			$kol_all = mysqlresult(myquery("SELECT COUNT(*) FROM game_turnir_users WHERE turnir_id=".$t['id'].""),0,0);
			if ($kol_all>=2)
			{
				start_chaos($t);
			}
		}
	}
	
	//удаление просроченных заявок
	myquery("DELETE game_turnir,game_turnir_users FROM game_turnir,game_turnir_users WHERE (game_turnir.timestamp+game_turnir.timeout*60)<UNIX_TIMESTAMP() AND (game_turnir.id=game_turnir_users.turnir_id)");
	
	if (isset($_GET['zayavka']))
	{
		//Форма подачи заявки
		?>
		<script type="text/javascript" language="JavaScript">
		function check_dostup()
		{
			enable = 0;
			
		}
		function select_type()
		{
			type = document.getElementById("sel_type").value;
			document.getElementById("type_duel").style.display="none";
			document.getElementById("type_group").style.display="none";
			document.getElementById("type_group_now").style.display="none";
			document.getElementById("type_chaos").style.display="none";
			document.getElementById("type_chaos_now").style.display="none";
			document.getElementById("type_shadow").style.display="none";
			document.getElementById("submit").disabled=true;
			if (type==1)
			{
				document.getElementById("type_duel").style.display="block";
				document.getElementById("submit").disabled=false;
			}
			if (type==2)
			{
				document.getElementById("type_group").style.display="block";
				document.getElementById("submit").disabled=false;
			}
			if (type==3)
			{
				document.getElementById("type_group_now").style.display="block";
				document.getElementById("submit").disabled=false;
			}
			if (type==4)
			{
				document.getElementById("type_chaos").style.display="block";
				document.getElementById("submit").disabled=false;
			}
			if (type==5)
			{
				document.getElementById("type_chaos_now").style.display="block";
				document.getElementById("submit").disabled=false;
			}
			if (type==99) 
			{
				document.getElementById("type_shadow").style.display="block";
				document.getElementById("submit").disabled=false;
			}
	   }
		</script>
		<div style="height:20px;text-align:center;width:100%"><span style="font-weight:800;color:#C10000;font-size:13px;font-family:Georgia;Verdana;Tahoma;Arial;sans-serif">Заявка на поединок</span><br /><br />В этих поединках за выигрыш количество денег и опыт уменьшены в несколько раз.<br />Победы и поражения в статистику не записываются</div>
		<br><br />
		<br>
		<form name="zayavka" method="post" action="<?=$main_url;?>">
		<div style="width:100%">
		<br>
		<div style="height:100%;width:100%;text-align:center;">
		Выбери тип боя:
		<select id="sel_type" name="sel_type" onchange="select_type()">
		<option value="0"></option>
		<option value="1">Дуэль</option>
		<option value="2">Групповой бой (комплект участников)</option>
		<option value="3">Групповой бой (сколько наберется участников)</option>
		<option value="4">Хаотичный бой (комплект участников)</option>
		<option value="5">Хаотичный бой (сколько наберется участников)</option>
		<option value="99">Бой с тенью</option>
		</select>
		<br>
		<br>
		</div>
		<div id="type_duel" style="display:none;height:100%;width:100%;text-align:center;width:100%">
		<span style="color:#FF0000;font-weight:800;font-size:13px;text-decoration:underline;">"Дуэль"</span><br /><br />Тебе надо указать варианты уровня противника и время ожидания начала боя
		<br /><br /><br />Укажи уровень противника
		<select name="duel_level" id="duel_level" onchange="check_dostup()">
		<option value="0">с <?=$char['clevel'];?> по <?=$char['clevel'];?> уровень</option>
		<option value="1">с <?=$char['clevel']-1;?> по <?=$char['clevel']+1;?> уровень</option>
		<option value="2" selected>с <?=$char['clevel']-2;?> по <?=$char['clevel']+2;?> уровень</option>
		</select>
		<br /><br /><br />Укажи вид боя
		<select name="duel_format" id="duel_format" onchange="check_dostup()">
		<option value="1">Кулачный бой</option>
		<option value="2">Бой с оружием</option>
		<option value="3">Магический бой</option>
		<option value="4" selected>Полный бой</option>
		</select>
		<br /><br /><br />
		Укажи время ожидания боя
		<select name="duel_time" id="duel_time" onchange="check_dostup()">
		<option value="1">1 минута</option>
		<option value="3">3 минуты</option>
		<option value="5">5 минут</option>
		<option value="10" selected>10 минут</option>
		<option value="15">15 минут</option>
		<option value="20">20 минут</option>
		</select>
		</div>
		<div id="type_group" style="display:none;height:100%;width:100%;text-align:center;width:100%">
		<span style="color:#FF0000;font-weight:800;font-size:13px;text-decoration:underline;">"Групповой бой (комплект участников)"</span><br /><br />Тебе надо указать варианты уровня противника, количество противников и время ожидания начала боя.<br />Турнирный бой начнется только если наберется полный состав участников по обе стороны боя!
		<br /><br /><br />Укажи уровень противника
		<select name="group_level" id="group_level" onchange="check_dostup()">
		<option value="0">с <?=$char['clevel'];?> по <?=$char['clevel'];?> уровень</option>
		<option value="1">с <?=$char['clevel']-1;?> по <?=$char['clevel']+1;?> уровень</option>
		<option value="2">с <?=$char['clevel']-2;?> по <?=$char['clevel']+2;?> уровень</option>
		</select>
		<br /><br /><br />
		Количество игроков на каждой стороне
		<select name="group_kol" id="group_kol" onchange="check_dostup()">
		<option value="2">по 2 игрока с каждой стороны</option>
		<option value="3" selected>по 3 игрока с каждой стороны</option>
		<option value="4">по 4 игрока с каждой стороны</option>
		<option value="5">по 5 игроков с каждой стороны</option>
		<option value="6">по 6 игроков с каждой стороны</option>
		<option value="7">по 7 игроков с каждой стороны</option>
		</select>
		<br /><br /><br />Укажи вид боя
		<select name="group_format" id="group_format" onchange="check_dostup()">
		<option value="1">Кулачный бой</option>
		<option value="2">Бой с оружием</option>
		<option value="3">Магический бой</option>
		<option value="4" selected>Полный бой</option>
		</select>
		<br /><br /><br />
		Укажи время ожидания боя
		<select name="group_time" id="group_time" onchange="check_dostup()">
		<option value="1">1 минута</option>
		<option value="3">3 минуты</option>
		<option value="5">5 минут</option>
		<option value="10" selected>10 минут</option>
		<option value="15">15 минут</option>
		<option value="20">20 минут</option>
		</select>
		</div>
		<div id="type_group_now" style="display:none;height:100%;width:100%;text-align:center;width:100%">
		<span style="color:#FF0000;font-weight:800;font-size:13px;text-decoration:underline;">"Групповой бой (сколько наберется участников)"</span><br /><br />Тебе надо указать варианты уровня противника, количество противников и время ожидания начала боя.<br />Турнирный бой начнется если с каждой стороны боя будет хотя бы по 1 участнику!
		<br /><br /><br />Укажи уровень противника
		<select name="group_level_now" id="group_level_now" onchange="check_dostup()">
		<option value="0">с <?=$char['clevel'];?> по <?=$char['clevel'];?> уровень</option>
		<option value="1">с <?=$char['clevel']-1;?> по <?=$char['clevel']+1;?> уровень</option>
		<option value="2">с <?=$char['clevel']-2;?> по <?=$char['clevel']+2;?> уровень</option>
		</select>
		<br /><br /><br />
		Количество игроков на каждой стороне
		<select name="group_kol_now" id="group_kol_now" onchange="check_dostup()">
		<option value="2">по 2 игрока с каждой стороны</option>
		<option value="3" selected>по 3 игрока с каждой стороны</option>
		<option value="4">по 4 игрока с каждой стороны</option>
		<option value="5">по 5 игроков с каждой стороны</option>
		<option value="6">по 6 игроков с каждой стороны</option>
		<option value="7">по 7 игроков с каждой стороны</option>
		</select>
		<br /><br /><br />Укажи вид боя
		<select name="group_format_now" id="group_format_now" onchange="check_dostup()">
		<option value="1">Кулачный бой</option>
		<option value="2">Бой с оружием</option>
		<option value="3">Магический бой</option>
		<option value="4" selected>Полный бой</option>
		</select>
		<br /><br /><br />
		Укажи время ожидания боя
		<select name="group_time_now" id="group_time_now" onchange="check_dostup()">
		<option value="1">1 минута</option>
		<option value="3">3 минуты</option>
		<option value="5">5 минут</option>
		<option value="10" selected>10 минут</option>
		<option value="15">15 минут</option>
		<option value="20">20 минут</option>
		</select>
		</div>
		<div id="type_chaos" style="display:none;height:100%;width:100%;text-align:center;width:100%">
		<span style="color:#FF0000;font-weight:800;font-size:13px;text-decoration:underline;">"Хаотичный бой (комплект участников)"</span><br /><br />Тебе надо указать варианты уровня противника, количество участников и время ожидания начала боя.<br />Турнирный бой начнется только если наберется полный состав участников! Перед началом боя игроки будут случайным образом распределены между противоборствующими сторонами.
		<br /><br /><br />Укажи уровень противника
		<select name="chaos_level" id="chaos_level" onchange="check_dostup()">
		<option value="0">с <?=$char['clevel'];?> по <?=$char['clevel'];?> уровень</option>
		<option value="1">с <?=$char['clevel']-1;?> по <?=$char['clevel']+1;?> уровень</option>
		<option value="2">с <?=$char['clevel']-2;?> по <?=$char['clevel']+2;?> уровень</option>
		<option value="3">с <?=$char['clevel']-3;?> по <?=$char['clevel']+3;?> уровень</option>
		<option value="4">с <?=$char['clevel']-4;?> по <?=$char['clevel']+4;?> уровень</option>
		<option value="5">с <?=$char['clevel']-5;?> по <?=$char['clevel']+5;?> уровень</option>
		<option value="6">с <?=$char['clevel']-6;?> по <?=$char['clevel']+6;?> уровень</option>
		<option value="7">с <?=$char['clevel']-7;?> по <?=$char['clevel']+7;?> уровень</option>
		<option value="8">с <?=$char['clevel']-8;?> по <?=$char['clevel']+8;?> уровень</option>
		<option value="9">с <?=$char['clevel']-9;?> по <?=$char['clevel']+9;?> уровень</option>
		<option value="10">с <?=$char['clevel']-10;?> по <?=$char['clevel']+10;?> уровень</option>
		</select>
		<br /><br /><br />
		Количество игроков в бое
		<select name="chaos_kol" id="chaos_kol" onchange="check_dostup()">
		<option value="2">2 игрока</option>
		<option value="3">3 игрока</option>
		<option value="4">4 игрока</option>
		<option value="5" selected>5 игроков</option>
		<option value="6">6 игроков</option>
		<option value="7">7 игроков</option>
		<option value="8">8 игроков</option>
		<option value="9">9 игроков</option>
		<option value="10">10 игроков</option>
		</select>
		<br /><br /><br />Укажи вид боя
		<select name="chaos_format" id="chaos_format" onchange="check_dostup()">
		<option value="1">Кулачный бой</option>
		<option value="2">Бой с оружием</option>
		<option value="3">Магический бой</option>
		<option value="4" selected>Полный бой</option>
		</select>
		<br /><br /><br />
		Укажи время ожидания боя
		<select name="chaos_time" id="chaos_time" onchange="check_dostup()">
		<option value="1">1 минута</option>
		<option value="3">3 минуты</option>
		<option value="5">5 минут</option>
		<option value="10" selected>10 минут</option>
		<option value="15">15 минут</option>
		<option value="20">20 минут</option>
		</select>
		</div>
		<div id="type_chaos_now" style="display:none;height:100%;width:100%;text-align:center;width:100%">
		<span style="color:#FF0000;font-weight:800;font-size:13px;text-decoration:underline;">"Хаотичный бой (сколько наберется участников)"</span><br /><br />Тебе надо указать варианты уровня противника, количество участников и время ожидания начала боя.<br />Турнирный бой начнется если наберется полный хотя бы 2 участника боя! Перед началом боя игроки будут случайным образом распределены между противоборствующими сторонами.
		<br /><br /><br />Укажи уровень противника
		<select name="chaos_level_now" id="chaos_level_now" onchange="check_dostup()">
		<option value="0">с <?=$char['clevel'];?> по <?=$char['clevel'];?> уровень</option>
		<option value="1">с <?=$char['clevel']-1;?> по <?=$char['clevel']+1;?> уровень</option>
		<option value="2">с <?=$char['clevel']-2;?> по <?=$char['clevel']+2;?> уровень</option>
		<option value="3">с <?=$char['clevel']-3;?> по <?=$char['clevel']+3;?> уровень</option>
		<option value="4">с <?=$char['clevel']-4;?> по <?=$char['clevel']+4;?> уровень</option>
		<option value="5">с <?=$char['clevel']-5;?> по <?=$char['clevel']+5;?> уровень</option>
		<option value="6">с <?=$char['clevel']-6;?> по <?=$char['clevel']+6;?> уровень</option>
		<option value="7">с <?=$char['clevel']-7;?> по <?=$char['clevel']+7;?> уровень</option>
		<option value="8">с <?=$char['clevel']-8;?> по <?=$char['clevel']+8;?> уровень</option>
		<option value="9">с <?=$char['clevel']-9;?> по <?=$char['clevel']+9;?> уровень</option>
		<option value="10">с <?=$char['clevel']-10;?> по <?=$char['clevel']+10;?> уровень</option>
		</select>
		<br /><br /><br />
		Количество игроков в бое
		<select name="chaos_kol_now" id="chaos_kol_now" onchange="check_dostup()">
		<option value="2">2 игрока</option>
		<option value="3">3 игрока</option>
		<option value="4">4 игрока</option>
		<option value="5" selected>5 игроков</option>
		<option value="6">6 игроков</option>
		<option value="7">7 игроков</option>
		<option value="8">8 игроков</option>
		<option value="9">9 игроков</option>
		<option value="10">10 игроков</option>
		</select>
		<br /><br /><br />Укажи вид боя
		<select name="chaos_format_now" id="chaos_format_now" onchange="check_dostup()">
		<option value="1">Кулачный бой</option>
		<option value="2">Бой с оружием</option>
		<option value="3">Магический бой</option>
		<option value="4" selected>Полный бой</option>
		</select>
		<br /><br /><br />
		Укажи время ожидания боя
		<select name="chaos_time_now" id="chaos_time_now" onchange="check_dostup()">
		<option value="1">1 минута</option>
		<option value="3">3 минуты</option>
		<option value="5">5 минут</option>
		<option value="10" selected>10 минут</option>
		<option value="15">15 минут</option>
		<option value="20">20 минут</option>
		</select>
		</div>
		<div id="type_shadow" style="display:none;height:100%;width:100%;text-align:center;width:100%">
		<span style="color:#FF0000;font-weight:800;font-size:13px;text-decoration:underline;">"Бой с тенью"</span><br /><br />Бой с противником, который имеет точно такие же характеристики и навыки, как и твои.
		</div>
		<br /><br />
		</div>
		<div style="width:100%;text-align:center;"><input id="submit" type="submit" disabled="true" name="submit_zayavka" value="Подать заявку на поединок"></div>
		</form>
		</div>
		<?
	}
	else
	{
		if (isset($_GET['remove_zayavka']))
		{
			$sel = myquery("SELECT * FROM game_turnir_users WHERE user_id=$user_id");
			while ($tur = mysql_fetch_array($sel))
			{
				myquery("DELETE FROM game_turnir_users WHERE id=".$tur['id']."");
				$check = mysql_result(myquery("SELECT COUNT(*) FROM game_turnir_users WHERE turnir_id=".$tur['turnir_id']." AND user_id>0"),0,0);
				if ($check==0)
				{
					myquery("DELETE FROM game_turnir WHERE id=".$tur['turnir_id']."");
					myquery("DELETE FROM game_turnir_users WHERE turnir_id=".$tur['turnir_id']."");
				}
			}
		}
		if (isset($_GET['agree']))
		{
			$tur = mysql_fetch_array(myquery("SELECT * FROM game_turnir WHERE id=".$_GET['agree'].""));
			if ($tur['type']==1)
			{
				if (($char['clevel']>=$tur['level_min'])AND($char['clevel']<=$tur['level_max']))
				{
					if (($tur['timestamp']+60*$tur['timeout'])>=time())
					{
						$seluser = myquery("SELECT user_id FROM game_turnir_users WHERE turnir_id=".$tur['id']."");
						if (mysql_num_rows($seluser)==1)
						{
							//начинаем турнир - дуэль
							list($prot_id)=mysql_fetch_array($seluser);
							$player = mysql_fetch_array(myquery("SELECT * FROM view_active_users WHERE user_id=$prot_id"));
							myquery("DELETE game_turnir,game_turnir_users FROM game_turnir,game_turnir_users WHERE (game_turnir.id=".$tur['id'].") AND (game_turnir.id=game_turnir_users.turnir_id) ");
							attack_user($char,$player,8,$tur['format']);
						}	
					}                    
				}
			}
			elseif (($tur['type']==2 OR $tur['type']==3)and(isset($_GET['side'])))
			{
				if (($_GET['side']==1)OR($_GET['side']==2))
				{
					if (($char['clevel']>=$tur['level_min'])AND($char['clevel']<=$tur['level_max']))
					{
						if (($tur['timestamp']+60*$tur['timeout'])>=time())
						{
							$kol_on_side = mysqlresult(myquery("SELECT COUNT(*) FROM game_turnir_users WHERE turnir_id=".$tur['id']." AND side=".(int)$_GET['side'].""),0,0);
							if ($kol_on_side<$tur['kol'])
							{
								myquery("INSERT IGNORE INTO game_turnir_users (turnir_id,side,user_id,from_boy) VALUES ('".$tur['id']."','".(int)$_GET['side']."','$user_id','$from_boy')");	
							}
							$kol_all = mysqlresult(myquery("SELECT COUNT(*) FROM game_turnir_users WHERE turnir_id=".$tur['id'].""),0,0);
							if ($kol_all==2*$tur['kol'])
							{
								start_group($tur);
							}
						}
					}                    
				}        
			}
			elseif ($tur['type']==4 OR $tur['type']==5)
			{
				if (($char['clevel']>=$tur['level_min'])AND($char['clevel']<=$tur['level_max']))
				{
					if (($tur['timestamp']+60*$tur['timeout'])>=time())
					{
						$kol_on_side = mysqlresult(myquery("SELECT COUNT(*) FROM game_turnir_users WHERE turnir_id=".$tur['id'].""),0,0);
						if ($kol_on_side<$tur['kol'])
						{
							myquery("INSERT IGNORE INTO game_turnir_users (turnir_id,side,user_id,from_boy) VALUES (".$tur['id'].",1,$user_id,'$from_boy')");    
						}
						$kol_all = mysqlresult(myquery("SELECT COUNT(*) FROM game_turnir_users WHERE turnir_id=".$tur['id'].""),0,0);
						if ($kol_all==$tur['kol'])
						{
							start_chaos($tur);
						}
					}
				}                    
			}
		}
		if (isset($_POST['submit_zayavka']))
		{
			//подаем заявку
			if (isset($_POST['sel_type']) AND $_POST['sel_type']>=1 AND $_POST['sel_type']<=99)
			{
				if ($_POST['sel_type']==1)
				{
					if (isset($_POST['duel_level']) AND $_POST['duel_level']>=0 AND $_POST['duel_level']<=2)
					{
						if (isset($_POST['duel_time']) AND ($_POST['duel_time']==1 OR $_POST['duel_time']==3 OR $_POST['duel_time']==5 OR $_POST['duel_time']==10 OR $_POST['duel_time']==15 OR $_POST['duel_time']==20))
						{
							if (isset($_POST['duel_format']) AND ($_POST['duel_format']==1 OR $_POST['duel_format']==2 OR $_POST['duel_format']==3 OR $_POST['duel_format']==4))
							{
								myquery("INSERT INTO game_turnir (type,level_min,level_max,kol,timeout,timestamp,format,map) VALUES (1,".($char['clevel']-$_POST['duel_level']).",".($char['clevel']+$_POST['duel_level']).",1,".$_POST['duel_time'].",".time().",".$_POST['duel_format'].",".$char['map_name'].")");
								$turnir_id = mysql_insert_id();
								//myquery("INSERT INTO game_turnir_users (turnir_id,side,user_id) VALUES ($turnir_id,1,$user_id),($turnir_id,2,0)");
								if ($from_boy)
								{
									$fr = 1;
								}
								else
								{
									$fr = 0;
								}
								myquery("INSERT INTO game_turnir_users (turnir_id,side,user_id,from_boy) VALUES ($turnir_id,1,$user_id,$fr)");
							}
						}
					}
				}
				if ($_POST['sel_type']==2)
				{
					if (isset($_POST['group_time']) AND ($_POST['group_time']==1 OR $_POST['group_time']==3 OR $_POST['group_time']==5 OR $_POST['group_time']==10 OR $_POST['group_time']==15 OR $_POST['group_time']==20))
					{
						if (isset($_POST['group_level']) AND $_POST['group_level']>=0 AND $_POST['group_level']<=2)
						{
							if (isset($_POST['group_format']) AND ($_POST['group_format']==1 OR $_POST['group_format']==2 OR $_POST['group_format']==3 OR $_POST['group_format']==4))
							{
								myquery("INSERT INTO game_turnir (type,level_min,level_max,kol,timeout,timestamp,format,map) VALUES (2,".($char['clevel']-$_POST['group_level']).",".($char['clevel']+$_POST['group_level']).",".$_POST['group_kol'].",".$_POST['group_time'].",".time().",".$_POST['group_format'].",".$char['map_name'].")");
								$turnir_id = mysql_insert_id();
								if ($from_boy)
								{
									$fr = 1;
								}
								else
								{
									$fr = 0;
								}
								$str_values = "($turnir_id,1,$user_id,$fr)";
								for ($i=2;$i<=$_POST['group_kol'];$i++)
								{
									//$str_values.=",($turnir_id,1,0)";
								}
								for ($i=1;$i<=$_POST['group_kol'];$i++)
								{
									//$str_values.=",($turnir_id,2,0)";
								}
								myquery("INSERT INTO game_turnir_users (turnir_id,side,user_id,from_boy) VALUES $str_values");
							}
						}
					}
				}
				if ($_POST['sel_type']==3)
				{
					if (isset($_POST['group_time_now']) AND ($_POST['group_time_now']==1 OR $_POST['group_time_now']==3 OR $_POST['group_time_now']==5 OR $_POST['group_time_now']==10 OR $_POST['group_time_now']==15 OR $_POST['group_time_now']==20))
					{
						if (isset($_POST['group_level_now']) AND $_POST['group_level_now']>=0 AND $_POST['group_level_now']<=2)
						{
							if (isset($_POST['group_format_now']) AND ($_POST['group_format_now']==1 OR $_POST['group_format_now']==2 OR $_POST['group_format_now']==3 OR $_POST['group_format_now']==4))
							{
								myquery("INSERT INTO game_turnir (type,level_min,level_max,kol,timeout,timestamp,format,map) VALUES (3,".($char['clevel']-$_POST['group_level_now']).",".($char['clevel']+$_POST['group_level_now']).",".$_POST['group_kol_now'].",".$_POST['group_time_now'].",".time().",".$_POST['group_format_now'].",".$char['map_name'].")");
								$turnir_id = mysql_insert_id();
								if ($from_boy)
								{
									$fr = 1;
								}
								else
								{
									$fr = 0;
								}
								$str_values = "($turnir_id,1,$user_id,$fr)";
								for ($i=2;$i<=$_POST['group_kol_now'];$i++)
								{
									//$str_values.=",($turnir_id,1,0)";
								}
								for ($i=1;$i<=$_POST['group_kol_now'];$i++)
								{
									//$str_values.=",($turnir_id,2,0)";
								}
								myquery("INSERT INTO game_turnir_users (turnir_id,side,user_id,from_boy) VALUES $str_values");
							}
						}
					}
				}
				if ($_POST['sel_type']==4)
				{
					if (isset($_POST['chaos_time']) AND ($_POST['chaos_time']==1 OR $_POST['chaos_time']==3 OR $_POST['chaos_time']==5 OR $_POST['chaos_time']==10 OR $_POST['chaos_time']==15 OR $_POST['chaos_time']==20))
					{
						if (isset($_POST['chaos_level']) AND $_POST['chaos_level']>=0 AND $_POST['chaos_level']<=10)
						{
							if (isset($_POST['chaos_format']) AND ($_POST['chaos_format']==1 OR $_POST['chaos_format']==2 OR $_POST['chaos_format']==3 OR $_POST['chaos_format']==4))
							{
								myquery("INSERT INTO game_turnir (type,level_min,level_max,kol,timeout,timestamp,format,map) VALUES (4,".($char['clevel']-$_POST['chaos_level']).",".($char['clevel']+$_POST['chaos_level']).",".$_POST['chaos_kol'].",".$_POST['chaos_time'].",".time().",".$_POST['chaos_format'].",".$char['map_name'].")");
								$turnir_id = mysql_insert_id();
								if ($from_boy)
								{
									$fr = 1;
								}
								else
								{
									$fr = 0;
								}
								$str_values = "($turnir_id,1,$user_id,$fr)";
								for ($i=2;$i<=$_POST['chaos_kol'];$i++)
								{
									//$str_values.=",($turnir_id,1,0)";
								}
								for ($i=1;$i<=$_POST['chaos_kol'];$i++)
								{
									//$str_values.=",($turnir_id,2,0)";
								}
								myquery("INSERT INTO game_turnir_users (turnir_id,side,user_id,from_boy) VALUES $str_values");
							}
						}
					}
				}
				if ($_POST['sel_type']==5)
				{
					if (isset($_POST['chaos_time_now']) AND ($_POST['chaos_time_now']==1 OR $_POST['chaos_time_now']==3 OR $_POST['chaos_time_now']==5 OR $_POST['chaos_time_now']==10 OR $_POST['chaos_time_now']==15 OR $_POST['chaos_time_now']==20))
					{
						if (isset($_POST['chaos_level_now']) AND $_POST['chaos_level_now']>=0 AND $_POST['chaos_level_now']<=10)
						{
							if (isset($_POST['chaos_format_now']) AND ($_POST['chaos_format_now']==1 OR $_POST['chaos_format_now']==2 OR $_POST['chaos_format_now']==3 OR $_POST['chaos_format_now']==4))
							{
								myquery("INSERT INTO game_turnir (type,level_min,level_max,kol,timeout,timestamp,format,map) VALUES (5,".($char['clevel']-$_POST['chaos_level_now']).",".($char['clevel']+$_POST['chaos_level_now']).",".$_POST['chaos_kol_now'].",".$_POST['chaos_time_now'].",".time().",".$_POST['chaos_format_now'].",".$char['map_name'].")");
								$turnir_id = mysql_insert_id();
								if ($from_boy)
								{
									$fr = 1;
								}
								else
								{
									$fr = 0;
								}
								$str_values = "($turnir_id,1,$user_id,$fr)";
								for ($i=2;$i<=$_POST['chaos_kol_now'];$i++)
								{
									//$str_values.=",($turnir_id,1,0)";
								}
								for ($i=1;$i<=$_POST['chaos_kol_now'];$i++)
								{
									//$str_values.=",($turnir_id,2,0)";
								}
								myquery("INSERT INTO game_turnir_users (turnir_id,side,user_id,from_boy) VALUES $str_values");
							}
						}
					}
				}
				if ($_POST['sel_type']==99)
				{
					//и сразу начинаем бой с тенью
					//создадим шаблон бота
					$level = $char['clevel']+1;
					$clevel = $char['clevel'];
					$new_clevel=$clevel*($clevel+1)*200;
					if ($clevel == 0) $new_clevel=200;
					$exp = $new_clevel*0.1/($level*$level);
					if ($level>5) $exp=0;
					myquery("INSERT INTO game_npc_template (npc_name,npc_img,npc_race,npc_max_hp,npc_max_mp,npc_str,npc_dex,npc_pie,npc_vit,npc_spd,item,npc_ntl,agressive,npc_level,npc_exp_max,to_delete) VALUES ('Тень игрока ".$char['name']."','ghost','Тень','".$char['HP_MAX']."','".$char['MP_MAX']."','".$char['STR']."','".$char['DEX']."','".$char['PIE']."','".$char['VIT']."','".$char['SPD']."','тенью оружия','".$char['NTL']."','0',0,$exp,1)");
					$npc_id = mysql_insert_id();    
					myquery("INSERT INTO game_npc (npc_id,prizrak,for_user_id,map_name,xpos,ypos,HP,MP,view,EXP,stay) VALUES ($npc_id,'1',$user_id,".$char['map_name'].",".$char['map_xpos'].",".$char['map_ypos'].",".$char['HP_MAX'].",'".$char['MP_MAX']."',0,$exp,3)");
					
					$npc_id = mysql_insert_id();
					attack_npc($char,$npc_id,0,1);
				}
			}
		}
		$check = mysql_result(myquery("SELECT COUNT(*) FROM game_turnir_users WHERE user_id=$user_id"),0,0);
		if ($from_boy)
		{
			echo '<table width="100%"><tr bgcolor=#333333><td style="color:#F0E68C">Турнирные поединки:</td></tr></table>';
		}
		if ($check==0)
		{
			?>
			<div style="height:20px;text-align:center;width:100%"><input type="button" value="Подать заявку" style="padding:3px;border:3px groove darkblue;background-color:#282828;color:white;" onclick="location.replace('<?=$main_url?>&amp;zayavka')"></div>
			<?
		}
		else
		{
			?>
			<div style="height:20px;text-align:center;width:100%"><input type="button" value="Отказаться от поединка" style="padding:3px;border:3px groove darkblue;background-color:#282828;color:white;" onclick="location.replace('<?=$main_url;?>&amp;remove_zayavka')"></div>
			<?
		}
		echo '
		<meta http-equiv="refresh" content="20">
		<hr>
		<div style="text-align:right;"><input type="button" value="Обновить" onclick="location.replace(\''.$main_url.'\')"></div>
		<br />';
		$sel = myquery("SELECT game_turnir.*,game_turnir_users.side,game_users.user_id,game_users.name,game_users.clevel,game_users.clan_id FROM game_turnir,game_turnir_users,game_users WHERE game_turnir.id=game_turnir_users.turnir_id AND game_turnir_users.user_id=game_users.user_id ORDER BY game_turnir.type,game_turnir.id,game_turnir_users.side,game_users.clevel,game_users.name");
        // AND game_turnir.map=".$char['map_name']."
		$ar = array();
		$curtur = 0;
		$i=0;
		while ($tur = mysql_fetch_array($sel))
		{
			if ($curtur!=$tur['id'])
			{
				if ($i>0)
				{
					for ($ind=$ar[$i]['side1_kol'];$ind<0;$ind--)
					{
						$ar[$i]['side1'].=($ar[$i]['kol']+1-$ind).') <br />'; 
					}
					if ($tur['type']!=4 AND $tur['type']!=5)
					{
						for ($ind=$ar[$i]['side2_kol'];$ind<0;$ind--)
						{
							$ar[$i]['side2'].=($ar[$i]['kol']+1-$ind).') <br />'; 
						}
					}
				}
				$i++;
				$ar[$i]['turnir_id']=$tur['id'];
				$ar[$i]['type']=$tur['type'];
				$ar[$i]['level_min']=$tur['level_min'];
				$ar[$i]['level_max']=$tur['level_max'];
				$ar[$i]['kol']=$tur['kol'];
				$ar[$i]['timestamp']=$tur['timestamp']; 
				$ar[$i]['format']=$tur['format']; 
				$ar[$i]['timeout']=$tur['timeout'];
				$ar[$i]['side1_kol']=$tur['kol'];
				$ar[$i]['side2_kol']=$tur['kol'];
				$ar[$i]['side1'] = '';
				$ar[$i]['side2'] = '';
				$curtur = $tur['id'];
			}
			$ar[$i]['side'.$tur['side']].=''.($ar[$i]['kol']+1-$ar[$i]['side'.$tur['side'].'_kol']).') '.$tur['name'].' ['.$tur['clevel'].']<a href="http://'.DOMAIN.'/view/?userid='.$tur['user_id'].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border="0"></a>';
			if ($tur['clan_id']!=0) $ar[$i]['side'.$tur['side']].='&nbsp;<img src="http://'.IMG_DOMAIN.'/clan/'.$tur['clan_id'].'.gif">';
			$ar[$i]['side'.$tur['side']].='<br />';
			$ar[$i]['side'.$tur['side'].'_kol']--;
		}
		if ($i>0)
		{
			for ($ind=1;$ind<=$ar[$i]['side1_kol'];$ind++)
			{
				$ar[$i]['side1'].=''.($ar[$i]['kol']-$ar[$i]['side1_kol']+$ind).') <br />'; 
			}
			if ($tur['type']!=4 AND $tur['type']!=5)
			{
				for ($ind=1;$ind<=$ar[$i]['side2_kol'];$ind++)
				{
					$ar[$i]['side2'].=''.($ar[$i]['kol']-$ar[$i]['side2_kol']+$ind).') <br />'; 
				}
			}
		}
		//die($i.'<br /><pre>'.print_r($ar,true).'</pre>');
		if (count($ar)>0)
		{
			echo '<table width="100%" cellspacing="0" cellpadding="2" border="1">';
			for ($i = 1; $i<=count($ar); $i++)
			{
				if (($ar[$i]['timestamp']+60*$ar[$i]['timeout'])<time()) continue;
				//поединок
				if ($ar[$i]['type']==1)
				{
					//дуэль
					if ($ar[$i]['side2_kol']>0)
					{
						echo '<tr><td colspan="4" align="center" valign="middle"><b>Дуэль</b></td></tr>
						<tr><td colspan="2" width="135" align="center"> вид : ';
						if ($ar[$i]['format']==1) echo 'Кулачный бой';
						if ($ar[$i]['format']==2) echo 'Бой с оружием';
						if ($ar[$i]['format']==3) echo 'Магический бой';
						if ($ar[$i]['format']==4) echo 'Полный бой';
						echo '</td><td align="center">Уровни: с '.$ar[$i]['level_min'].' по '.$ar[$i]['level_max'].'</td><td align="right" width="135">До начала боя: <span style="font-weight:900;color:white">'.date("i:s",($ar[$i]['timestamp']+60*$ar[$i]['timeout'])-time()).'</span></td></tr>';
						echo '<tr style="height:3px;border:0px;background-color:#C0C0C0"><td colspan="4"></td></tr>';
						echo '<tr><td align="center">';
						if ($check==0 AND $char['clevel']>=$ar[$i]['level_min'] AND $char['clevel']<=$ar[$i]['level_max']) echo '<input type="button" value="Вступить" onclick="location.replace(\''.$main_url.'&amp;agree='.$ar[$i]['turnir_id'].'\')">'; else echo '&nbsp;';
						echo '</td><td colspan="3" align="center" valign="middle" style="font-weight:800;color:#C0FFC0;">'.$ar[$i]['side1'].'</td></tr>';
					}
				}    
				if ($ar[$i]['type']==2)
				{
					//групповой бой
					if ($ar[$i]['side1_kol']>0 OR $ar[$i]['side2_kol']>0)
					{
						echo '
						<tr><td colspan="4" align="center" valign="middle"><b>Групповой бой (комплект участников) - '.$ar[$i]['kol'].' х '.$ar[$i]['kol'].'</b></td></tr>
						<tr><td colspan="4">
							<table cellspacing=1 width="100%">
								<tr><td width=135 align=left> вид : ';
								
								if ($ar[$i]['format']==1) echo 'Кулачный бой';
								if ($ar[$i]['format']==2) echo 'Бой с оружием';
								if ($ar[$i]['format']==3) echo 'Магический бой';
								if ($ar[$i]['format']==4) echo 'Полный бой';
						
								echo '</td><td align="center">Уровни: с '.$ar[$i]['level_min'].' по '.$ar[$i]['level_max'].'</td><td width=135 align=right>До начала боя: <span style="font-weight:900;color:white">'.date("i:s",($ar[$i]['timestamp']+60*$ar[$i]['timeout'])-time()).'</span></td></tr>
							</table>
						</td></tr>
						<tr style="height:3px;border:0px;background-color:#C0C0C0"><td colspan="4"></td></tr>
						<tr><td colspan="2" width="50%" align="left" valign="bottom" style="padding-left:25px;padding-top:10px;padding-bottom:10px;font-weight:800;color:#C0FFC0;">';
							echo trim($ar[$i]['side1']);
							if ($check==0 AND $char['clevel']>=$ar[$i]['level_min'] AND $char['clevel']<=$ar[$i]['level_max']) 
								echo '<br /><br /><input type="button" value="Вступить" onclick="location.replace(\''.$main_url.'&amp;agree='.$ar[$i]['turnir_id'].'&amp;side=1\')">'; 
							else 
								echo '
						</td><td colspan="2" width="50%" align="left" valign="bottom" style="padding-left:25px;padding-top:10px;padding-bottom:10px;font-weight:800;color:#FFFFC0;">';
							echo trim($ar[$i]['side2']);
							if ($check==0 AND $char['clevel']>=$ar[$i]['level_min'] AND $char['clevel']<=$ar[$i]['level_max']) 
								echo '<br /><br /><input type="button" value="Вступить" onclick="location.replace(\''.$main_url.'&amp;agree='.$ar[$i]['turnir_id'].'&amp;side=2\')">'; 
							else 
								echo '';
						echo '</td></tr>';
					}
				}    
				if ($ar[$i]['type']==3)
				{
					//групповой бой
					if ($ar[$i]['side1_kol']>0 OR $ar[$i]['side2_kol']>0)
					{
						echo '
						<tr><td colspan="4" align="center" valign="middle"><b>Групповой бой (сколько наберется участников) - '.$ar[$i]['kol'].' х '.$ar[$i]['kol'].'</b></td></tr>
						<tr><td colspan="4">
							<table cellspacing=1 width="100%">
								<tr><td width=135 align=left> вид : ';
								
								if ($ar[$i]['format']==1) echo 'Кулачный бой';
								if ($ar[$i]['format']==2) echo 'Бой с оружием';
								if ($ar[$i]['format']==3) echo 'Магический бой';
								if ($ar[$i]['format']==4) echo 'Полный бой';
						
								echo '</td><td align="center">Уровни: с '.$ar[$i]['level_min'].' по '.$ar[$i]['level_max'].'</td><td width=135 align=right>До начала боя: <span style="font-weight:900;color:white">'.date("i:s",($ar[$i]['timestamp']+60*$ar[$i]['timeout'])-time()).'</span></td></tr>
							</table>
						</td></tr>
						<tr style="height:3px;border:0px;background-color:#C0C0C0"><td colspan="4"></td></tr>
						<tr><td colspan="2" width="50%" align="left" valign="bottom" style="padding-left:25px;padding-top:10px;padding-bottom:10px;font-weight:800;color:#C0FFC0;">';
							echo trim($ar[$i]['side1']);
							if ($check==0 AND $char['clevel']>=$ar[$i]['level_min'] AND $char['clevel']<=$ar[$i]['level_max']) 
								echo '<br /><br /><input type="button" value="Вступить" onclick="location.replace(\''.$main_url.'&amp;agree='.$ar[$i]['turnir_id'].'&amp;side=1\')">'; 
							echo '</td><td colspan="2" width="50%" align="left" valign="bottom" style="padding-left:25px;padding-top:10px;padding-bottom:10px;font-weight:800;color:#FFFFC0;">';
							echo trim($ar[$i]['side2']);
							if ($check==0 AND $char['clevel']>=$ar[$i]['level_min'] AND $char['clevel']<=$ar[$i]['level_max']) 
								echo '<br /><br /><input type="button" value="Вступить" onclick="location.replace(\''.$main_url.'&amp;agree='.$ar[$i]['turnir_id'].'&amp;side=2\')">'; 
						echo '</td></tr>';
					}
				}    
				if ($ar[$i]['type']==4)
				{
					//хаотичный бой
					if ($ar[$i]['side1_kol']>0 OR $ar[$i]['side2_kol']>0)
					{
						echo '
						<tr><td colspan="4" align="center" valign="middle"><b>Хаотичный бой (комплект участников) - '.$ar[$i]['kol'].'</b><br />Участники турнира распределяются по командам случайно перед началом боя</td></tr>
						<tr><td colspan="4">
							<table cellspacing=1 width="100%">
								<tr><td width=135 align=left> вид : ';
								
								if ($ar[$i]['format']==1) echo 'Кулачный бой';
								if ($ar[$i]['format']==2) echo 'Бой с оружием';
								if ($ar[$i]['format']==3) echo 'Магический бой';
								if ($ar[$i]['format']==4) echo 'Полный бой';
						
								echo '</td><td align="center">Уровни: с '.$ar[$i]['level_min'].' по '.$ar[$i]['level_max'].'</td><td width=135 align=right>До начала боя: <span style="font-weight:900;color:white">'.date("i:s",($ar[$i]['timestamp']+60*$ar[$i]['timeout'])-time()).'</span></td></tr>
							</table>
						</td></tr>
						<tr style="height:3px;border:0px;background-color:#C0C0C0"><td colspan="4"></td></tr>
						<tr><td colspan="4" width="50%" align="left" valign="bottom" style="padding-left:25px;padding-top:10px;padding-bottom:10px;font-weight:800;color:#C0FFC0;">';
							echo trim($ar[$i]['side1']);
							if ($check==0 AND $char['clevel']>=$ar[$i]['level_min'] AND $char['clevel']<=$ar[$i]['level_max']) 
								echo '<br /><br /><input type="button" value="Вступить" onclick="location.replace(\''.$main_url.'&amp;agree='.$ar[$i]['turnir_id'].'\')">'; 
							else 
								echo '
						</td>';
						echo '</td></tr>';
					}
				}    
				if ($ar[$i]['type']==5)
				{
					//хаотичный бой
					if ($ar[$i]['side1_kol']>0 OR $ar[$i]['side2_kol']>0)
					{
						echo '
						<tr><td colspan="4" align="center" valign="middle"><b>Хаотичный бой (сколько наберется участников) - '.$ar[$i]['kol'].'</b><br />Участники турнира распределяются по командам случайно перед началом боя</td></tr>
						<tr><td colspan="4">
							<table cellspacing=1 width="100%">
								<tr><td width=135 align=left> вид : ';
								
								if ($ar[$i]['format']==1) echo 'Кулачный бой';
								if ($ar[$i]['format']==2) echo 'Бой с оружием';
								if ($ar[$i]['format']==3) echo 'Магический бой';
								if ($ar[$i]['format']==4) echo 'Полный бой';
						
								echo '</td><td align="center">Уровни: с '.$ar[$i]['level_min'].' по '.$ar[$i]['level_max'].'</td><td width=135 align=right>До начала боя: <span style="font-weight:900;color:white">'.date("i:s",($ar[$i]['timestamp']+60*$ar[$i]['timeout'])-time()).'</span></td></tr>
							</table>
						</td></tr>
						<tr style="height:3px;border:0px;background-color:#C0C0C0"><td colspan="4"></td></tr>
						<tr><td colspan="4" width="50%" align="left" valign="bottom" style="padding-left:25px;padding-top:10px;padding-bottom:10px;font-weight:800;color:#C0FFC0;">';
							echo trim($ar[$i]['side1']);
							if ($check==0 AND $char['clevel']>=$ar[$i]['level_min'] AND $char['clevel']<=$ar[$i]['level_max']) 
								echo '<br /><br /><input type="button" value="Вступить" onclick="location.replace(\''.$main_url.'&amp;agree='.$ar[$i]['turnir_id'].'\')">'; 
							else 
								echo '
						</td>';
						echo '</td></tr>';
					}
				}    
			}
			echo '</table>';
		}
		else
		{
			echo '<center>Поединков нет</center>';
		}
		echo '<br /><br /><u>Примечание</u><br />Кулачный бой - в бою можно использовать только удары кулаком<br />Бой с оружием - в бою можно использовать различное оружие, артефакты и щиты<br />Магический бой - в бою можно использовать только магию<br />Полный бой - в бою можно использовать всё';
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	if (!$from_boy) echo '<center><br><input type="button" value="Выйти в город" onClick=location.replace("town.php")>';
}

if (function_exists("save_debug")) save_debug(); 

?>
