<?php
//Крон для запуска каждый час

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

move_teleport(60);

$da = getdate();
if ($da['mday']==31 and $da['hours']>=15 and $da['hours']<16)
{	
	$npc_id=1058835;
	$templ = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=$npc_id"));
	myquery("INSERT INTO game_npc SET stay=4,npc_id=$npc_id,map_name=18,xpos=28,ypos=21,view=0,dropable=1,HP=".$templ['npc_max_hp'].",MP=".$templ['npc_max_mp'].", EXP=".$templ['npc_exp_max']."");
	$say = 'Зло прорвалось в Средиземье!';
	$say = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">".$say."</b></span>");
	myquery("INSERT INTO game_log (`message`,`date`,`fromm`) VALUES ('".mysql_real_escape_string($say)."',".time().",-1)");
}

myquery("DELETE FROM game_cron_log WHERE cron='every_hour' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_hour','Начало',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='Оптимизация таблиц', timecron=".time()." WHERE id=$idcronlog");
$result = myquery("OPTIMIZE TABLE game_chat");
$result = myquery("OPTIMIZE TABLE game_battles");
$result = myquery("OPTIMIZE TABLE game_log");

myquery("UPDATE game_cron_log SET step='Корректировка опыта за бота', timecron=".time()." WHERE id=$idcronlog");
$sel = myquery("SELECT game_npc.id,game_npc.EXP,game_npc_template.npc_exp_max,game_npc.kill_last_hour FROM game_npc,game_npc_template WHERE game_npc.prizrak=0 AND game_npc_template.npc_exp_max>200 AND game_npc.npc_id=game_npc_template.npc_id");
while ($npc = mysql_fetch_array($sel))
{
	$npc_exp = $npc['EXP'];
	if ($npc['kill_last_hour']<=3) $npc_exp = $npc_exp*1.1;
	elseif ($npc['kill_last_hour']<=7) $npc_exp = $npc_exp*1;
	elseif ($npc['kill_last_hour']<=10) $npc_exp = $npc_exp*0.95;
	elseif ($npc['kill_last_hour']<=13) $npc_exp = $npc_exp*0.90;
	elseif ($npc['kill_last_hour']<=18) $npc_exp = $npc_exp*0.85;
	elseif ($npc['kill_last_hour']<=23) $npc_exp = $npc_exp*0.80;
	elseif ($npc['kill_last_hour']<=28) $npc_exp = $npc_exp*0.75;
	elseif ($npc['kill_last_hour']<=33) $npc_exp = $npc_exp*0.70;
	elseif ($npc['kill_last_hour']<=38) $npc_exp = $npc_exp*0.65;
	elseif ($npc['kill_last_hour']<=43) $npc_exp = $npc_exp*0.60;
	elseif ($npc['kill_last_hour']<=48) $npc_exp = $npc_exp*0.55;
	else $npc_exp = $npc_exp*0.50;
	$npc_exp = min($npc_exp,$npc['npc_exp_max']);
	$npc_exp = max($npc_exp,$npc['npc_exp_max']/2);
	myquery("UPDATE game_npc SET kill_last_hour=0,EXP=".$npc_exp." WHERE id=".$npc['id']."");
}
/*
myquery("UPDATE game_cron_log SET step='Создание бота-призрака', timecron=".time()." WHERE id=$idcronlog");

//Создадим бота-призрака
$online_range = time()-300;
$sel = myquery("SELECT * FROM game_users WHERE user_id in (SELECT user_id FROM game_users_active WHERE last_active>$online_range) AND clan_id<>1");
$kol = mysql_num_rows($sel);
if ($kol>0)
{
	echo 'Всего игроков - '.$kol;
	$r = mt_rand(1,$kol);
	$i=0;
	while ($user=mysql_fetch_array($sel))
	{
		$i++;
		if ($i==$r)
		{
			echo '<br>Создаем призрак игрока - '.$user['name'];
			$file = file("/home/vhosts/rpg.su/web/utils/cron/npc_prizrak.txt");
			srand((double)microtime()*1000000);
			$npc_img = trim($file[mt_rand(0,(count($file)-1))]);

			$sel111=myquery("select npc_id from game_npc order by npc_id DESC limit 1");
			list($nid)=mysql_fetch_array($sel111);
			$n=''.($nid+1).'';

			list($char_map_name) = mysql_fetch_array(myquery("SELECT map_name FROM game_users_map WHERE user_id='".$user['user_id']."'"));
			list($lab) = mysql_fetch_array(myquery("SELECT maze FROM game_maps WHERE id=$char_map_name"));
			if ($lab==0)
			{

			$npc_name = 'Дух '.$user['name'];
			$npc_race = 'Призрак';
			$npc_hp = $user['HP_MAX'];
			$npc_max_hp = $user['HP_MAX'];
			$npc_mp = $user['MP_MAX'];
			$npc_max_mp = $user['MP_MAX'];
			$npc_str = $user['STR'];
			$npc_dex = $user['DEX'];
			$npc_wis = $user['PIE'];
			$npc_basefit = $user['VIT'];
			$npc_basedef = $user['SPD'];
			$npc_exp = $user['SPD']*15;
			$npc_gold = $user['clevel']/4;
			$npc_map_name = $char_map_name;
			$battle_map_query = myquery("SELECT * FROM game_map where name='".$npc_map_name."' ORDER BY xpos DESC, ypos DESC LIMIT 1");
			$battle_map_result = mysql_fetch_array($battle_map_query, MYSQL_ASSOC);
			$xrandmap = mt_rand(0, $battle_map_result['xpos']);
			$yrandmap = mt_rand(0, $battle_map_result['ypos']);
			$npc_xpos = $xrandmap;
			$npc_ypos = $yrandmap;
			$npc_time = time();
			$npc_ntl = $user['NTL'];
			$npc_level = $user['clevel'];
			$npc_item = 'кулаком';
			$the=myquery("SELECT item_id FROM game_items WHERE user_id='".$user['user_id']."' AND used=1 AND priznak=0");
			if (mysql_num_rows($the))
			{
				list($item_id)=mysql_fetch_array($the);
				list($npc_item,$indx)=mysql_fetch_array(myquery("SELECT mode,indx FROM game_items_factsheet WHERE id=$item_id"));
				$npc_str+=$indx+$user['MS_WEAPON'];
			}
			$ins = myquery("INSERT INTO game_npc
			(npc_id, npc_name, npc_race, npc_img, npc_hp, npc_max_hp, npc_mp, npc_max_mp, npc_str, npc_dex, npc_wis, npc_basefit, npc_basedef, npc_exp, npc_gold, npc_map_name, npc_xpos, npc_ypos, npc_time, straj, view, npc_opis, npc_ntl, npc_level, respawn, prizrak, item)
			VALUES
			('$n', '$npc_name', '$npc_race', '$npc_img', '$npc_hp', '$npc_max_hp', '$npc_mp', '$npc_max_mp', '$npc_str', '$npc_dex', '$npc_wis', '$npc_basefit', '$npc_basedef', '$npc_exp', '$npc_gold', '$npc_map_name', '$npc_xpos', '$npc_ypos', '$npc_time', '0', '0', '', '$npc_ntl', '$npc_level', '0', '1', '$npc_item')");
			echo '<br>Призрак создан';
			}
			break;
		}
	}
}
*/

/*
myquery("UPDATE game_cron_log SET step='Очистка таблиц боев', timecron=".time()." WHERE id=$idcronlog");

//почистим таблицы боев
$sel = myquery("SELECT id,npc_id,type FROM combat WHERE npc_id=0");
while ($boy = mysql_fetch_array($sel))
{
	$check = myquery("SELECT user_id FROM game_users WHERE boy='".$boy['id']."'");
	if (!mysql_num_rows($check))
	{
		myquery("DELETE FROM combat WHERE id='".$boy['id']."'");
		if ($boy['npc_id']!=0 OR $boy['type']==1)
		{
			myquery("DELETE FROM combat_history WHERE combat_id='".$boy['id']."'"); 
		}
		myquery("DELETE FROM combat_users WHERE combat_id='".$boy['id']."'");
		myquery("DELETE FROM combat_users_exp WHERE combat_id='".$boy['id']."'");
		myquery("DELETE FROM combat_lose_user WHERE combat_id='".$boy['id']."'");
		myquery("DELETE FROM combat_new_user WHERE combat_id='".$boy['id']."'");
	}
	else
	{
		$out = mysql_result(myquery("SELECT COUNT(*) FROM game_users_active WHERE last_active<".(time()-360)." AND user_id IN (SELECT user_id FROM game_users WHERE boy='".$boy['id']."')"),0,0);
		if ($out==mysql_num_rows($check))
		{
			//все участники зависшего боя в ауте
			myquery("DELETE FROM combat WHERE id='".$boy['id']."'");
			if ($boy['npc_id']!=0 OR $boy['type']==1)
			{
				myquery("DELETE FROM combat_history WHERE combat_id='".$boy['id']."'");
			}
			myquery("DELETE FROM combat_users WHERE combat_id='".$boy['id']."'");
			myquery("DELETE FROM combat_users_exp WHERE combat_id='".$boy['id']."'");
			myquery("DELETE FROM combat_lose_user WHERE combat_id='".$boy['id']."'");
			myquery("DELETE FROM combat_new_user WHERE combat_id='".$boy['id']."'");
			while (list($usrid) = mysql_fetch_array($check))
			{
				combat_setFunc($usrid,func1_lose);
				myquery("update game_users set lose=lose+1,HP=0,MP=0,STM=0 where user_id='$usrid'"); 
				$time = time();
				//$stat = myquery("INSERT DELAYED INTO game_stat (user_id,stat_id,time) VALUES ('".$usrid."','3','$time')");
			}
		}
	}
}

$sel = myquery("SELECT id,combat_id FROM combat_history");
while ($boy = mysql_fetch_array($sel))
{
	$check = myquery("SELECT id FROM combat WHERE id='".$boy['combat_id']."'");
	if (!mysql_num_rows($check)) myquery("DELETE FROM combat_history WHERE id='".$boy['id']."'");
}

$sel = myquery("SELECT id,combat_id FROM combat_users");
while ($boy = mysql_fetch_array($sel))
{
	$check = myquery("SELECT id FROM combat WHERE id='".$boy['combat_id']."'");
	if (!mysql_num_rows($check)) myquery("DELETE FROM combat_users WHERE id='".$boy['id']."'");
}
$sel = myquery("SELECT id,combat_id FROM combat_users_exp");
while ($boy = mysql_fetch_array($sel))
{
	$check = myquery("SELECT id FROM combat WHERE id='".$boy['combat_id']."'");
	if (!mysql_num_rows($check)) myquery("DELETE FROM combat_users_exp WHERE id='".$boy['id']."'");
}
$sel = myquery("SELECT id,combat_id FROM combat_lose_user");
while ($boy = mysql_fetch_array($sel))
{
	$check = myquery("SELECT id FROM combat WHERE id='".$boy['combat_id']."'");
	if (!mysql_num_rows($check)) myquery("DELETE FROM combat_lose_user WHERE id='".$boy['id']."'");
}
$sel = myquery("SELECT id,combat_id FROM combat_new_user");
while ($boy = mysql_fetch_array($sel))
{
	$check = myquery("SELECT id FROM combat WHERE id='".$boy['combat_id']."'");
	if (!mysql_num_rows($check)) myquery("DELETE FROM combat_new_user WHERE id='".$boy['id']."'");
}
*/
myquery("UPDATE game_cron_log SET step='Отслеживаем активность игроков', timecron=".time()." WHERE id=$idcronlog");
$count = mysql_result(myquery("SELECT COUNT(*) FROM game_users_active WHERE last_active>=".(time()-300).""),0,0);
myquery("INSERT INTO game_activity_hour (time,kol) VALUES (".time().",$count)");

myquery("UPDATE game_cron_log SET step='Доставка посылок на склады почтовой службы', timecron=".time()." WHERE id=$idcronlog");
$sel_post = myquery("SELECT * FROM game_items WHERE post_to>0 AND post_var=2 AND priznak=3");
while ($post = mysql_fetch_array($sel_post))
{
	list($town_name) = mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$post['town'].""));
	myquery("UPDATE game_items SET sell_time=".time().",post_var=0 WHERE id=".$post['id']."");
	myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('".$post['post_to']."', '0', 'Тебе пришла посылка', 'На твое имя получена посылка. Ты можешь забрать ее в почтовом отделении на твоей карте в городе $town_name в течении 4 игровых месяцев.', '0','".time()."')");
}

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");
?>