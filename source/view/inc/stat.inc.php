<center><h3><font color=#FF0000 face=verdana>Статистика обновляется каждые 12 часов</font></h3></center>
<table><tr><td width=200></td><td>
<ol>
<li><a href="?stat&vid=1">12 последних добавленных предметов
<li><a href="?stat&vid=2">Последние зарегистрировавшиеся, статистика по расам
<li><a href="?stat&vid=3">Статистика по NPC
<li><a href="?stat&vid=6">Статистика по посещению игры
<li><a href="?stat&vid=7">Статистика по тавернам
<li><a href="?stat&vid=10">Статистика по рынкам
<li><a href="?stat&vid=13">Статистика по магазинам
<li><a href="?stat&vid=17">Статистика по клиентам магазинов
<li><a href="?stat&vid=21">Статистика по боям
</ol>
</td></tr></table>
<?php
require_once('../../inc/engine.inc.php');
if (function_exists("start_debug")) start_debug(); 

/*
Параметр vid:
1 - 12 последних добавленных предметов
2 - Последние зарегистрировавшиеся, статистика по расам
3 - 5 последних добавленных NPC
4 - 5 популярных, сильных NPC
5 - 5 игроков выигрывающие и проигрывающие ботам
6 - Статистика по посещению игры
7 - 5 популярных и богатых таверен
8 - 5 голодных игроков
9 - 5 популярных и прибыльных блюд
10 - 5 популярных и прибыльных рынков
11 - 5 популярных и прибыльных продавцов
12 - 5 популярных и прибыльных предметов
13 - 5 популярных и прибыльных магазинов по продажам
14 - 5 магазинов больше всего купивших предметов
15 - 5 магазинов больше всего отремонтировавших предметов
16 - 5 магазинов больше всего идентифицировавших предметов
17 - 5 игроков больше всего купивших предметов
18 - 5 игроков больше всего продавших предметов
19 - 5 игроков больше всего отремонтировавших предметов
20 - 5 игроков больше всего идентифицирующих предметов
21 - 5 игроков самых частых атакующих и защищающихся игроков
22 - 5 игроков самых частых выигрывающих и проигрывающих игроков
23 - 5 кланов самых частых выигрывающих и проигрывающих игроков
24 - 5 игроков, заработавшие опыт и золото с побед
25 - 5 игроков, выигрывающих битвы у большего и меньшего уровня
26 - 5 игроков, проигрывающих битвы у большего и меньшего уровня
27 - 5 игроков, вылетающих по тайму
*/

/*
Соответствие кодов действиям игроков
1.        Присоединился к игроку
2.        Проиграл боту
3.        Вылетел по таймауту
4.        Выиграл по тайму
5.        Убил бота
6.        Проиграл битву
7.        Выиграл битву
8.        Напал на бота
9.        Покупка на рынке
10.        Куплен предмет
11.        Продан предмет
12.        Идентифицирован предмет
13.        Отремонтирован предмет
14.        Обед в таверне
15.        Зарядил артефакт
16.        Напал на игрока
*/
function DbConnectStat()
{

	$db_stat = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
	mysql_select_db(PHPRPG_DB_NAME,$db_stat) or die(mysql_error());
}

function PrintTable($Param,$Title)
{

	if ($Param=='open')
	{
		echo '
		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
		<td width="10">&nbsp;</td>
		<td valign=top width="385">';
	}

	if ($Param=='cell_open')
	{
	echo '
	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="8" height="8"><img src="http://'.IMG_DOMAIN.'/nav/2_01.jpg" width="8" height="8"></td>
			<td background="http://'.IMG_DOMAIN.'/nav/2_02.jpg"></td>
			<td width="8" height="8"><img src="http://'.IMG_DOMAIN.'/nav/2_04.jpg" width="8" height="8"></td>
		</tr>
		<tr>
			<td background="http://'.IMG_DOMAIN.'/nav/2_05.jpg"></td>
			<td bgcolor="#000000"><div align="center">'.$Title.'</div>
				<table width="95%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
					  <td width="5" height="6"><img src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
					  <td background="http://'.IMG_DOMAIN.'/nav/1_09.jpg"></td>
					  <td width="7" height="6"><img src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td>
					</tr>
					<tr>
					  <td width="5" background="http://'.IMG_DOMAIN.'/nav/1_17.jpg"></td>
					  <td height="100%" bgcolor="313131">
					  <p align="center">
					  <table width="90%" height="100%"  border="0" align="center" cellpadding="1" cellspacing="1">';
	}

	if ($Param=='cell_close')
	{
					  echo'
					  </table>
					  </p></td>
					  <td width="5" background="http://'.IMG_DOMAIN.'/nav/1_15.jpg"></td>
					</tr>
					<tr>
					  <td width="5" height="8"><img src="http://'.IMG_DOMAIN.'/nav/1_19.jpg" width="5" height="8"></td>
					  <td background="http://'.IMG_DOMAIN.'/nav/1_20.jpg"></td>
					  <td width="7" height="8"><img src="http://'.IMG_DOMAIN.'/nav/1_22.jpg" width="7" height="8"></td>
					</tr>
				</table>
			</td>
			<td background="http://'.IMG_DOMAIN.'/nav/2_07.jpg"></td>
		</tr>
		<tr>
			<td width="8" height="8"><img src="http://'.IMG_DOMAIN.'/nav/2_10.jpg" width="8" height="8"></td>
			<td background="http://'.IMG_DOMAIN.'/nav/2_11.jpg"></td>
			<td width="8" height="8"><img src="http://'.IMG_DOMAIN.'/nav/2_13.jpg" width="8" height="8"></td>
		</tr>
	</table>';
	}

	if ($Param=='medium')
	{
		echo '
		</td>
		<td width="10">&nbsp;</td>
		<td valign=top width="385">';
	}

	if ($Param=='close')
	{
		echo '
		</td>
		<td width="10">&nbsp;</td>
		</tr>
		</table>';
	}
}

if(!isset($vid)) {$vid=1;}

if (isset($stat))
{
echo '<br><center>';

//12 Последних добавленных предметов
if ($vid==1)
{
	DbConnect();
	echo'
	<table width="780" border="0" cellspacing="0" cellpadding="0">
	<tr>
			<td width="8" height="8"><img src="http://'.IMG_DOMAIN.'/nav/2_01.jpg" width="8" height="8"></td>
			<td background="http://'.IMG_DOMAIN.'/nav/2_02.jpg"></td>
			<td width="8" height="8"><img src="http://'.IMG_DOMAIN.'/nav/2_04.jpg" width="8" height="8"></td>
	</tr>
	<tr>
		<td background="http://'.IMG_DOMAIN.'/nav/2_05.jpg"></td>
		<td bgcolor="#000000"><div align="center"><span class="style1"><font size=1>12 Последних добавленных предметов:</font></span></div>';
			$nom=0;
			$it=myquery("SELECT * FROM game_items_factsheet WHERE view='1' order by id DESC limit 12");
			while($item=mysql_fetch_array($it))
			{
				$nom++;
				If ($nom==5) $nom=1;
				if ($nom==1)
						echo '<table height="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>';
				echo'
				<table height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>';
				echo'<td width="5" height="6"><img src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
					<td background="http://'.IMG_DOMAIN.'/nav/1_09.jpg"></td>
					<td width="7" height="6"><img src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td><tr>
					<td width="5" background="http://'.IMG_DOMAIN.'/nav/1_17.jpg"></td>
					<td width="180" height="100%" bgcolor="313131">
							<p align="center"><font size=1><a href="http://'.DOMAIN.'/info/?&item='.$item['id'].'">
							<img src="http://'.IMG_DOMAIN.'/item/'.$item['img'].'.gif" align="left" border=0></a>'.$item['name'].'';
							if ($item['race'] != 0) echo '<br><font color=#FFFF80>Раса: ['.mysqlresult(myquery("SELECT name FROM game_har WHERE id=".$item['race'].""),0,0).']</font>';
							echo '<br>['.$item['oclevel'].']';
							echo'</font></p>
							 </td>
					<td  width="5" background="http://'.IMG_DOMAIN.'/nav/1_15.jpg"></td>
				</tr>
				<tr>
							 <td width="5" height="8"><img src="http://'.IMG_DOMAIN.'/nav/1_19.jpg" width="5" height="8"></td>
							 <td background="http://'.IMG_DOMAIN.'/nav/1_20.jpg"></td>
					<td width="7" height="8"><img src="http://'.IMG_DOMAIN.'/nav/1_22.jpg" width="7" height="8"></td>';
				echo '
				</tr>
				</table>
				</td>';
				if ($nom==4) echo '</table>';
				else echo '<td>';
			}

	echo'
			</td>
			<td width="5" background="http://'.IMG_DOMAIN.'/nav/2_07.jpg"></td>
	</tr>
	<tr>
			<td width="8" height="8"><img src="http://'.IMG_DOMAIN.'/nav/2_10.jpg" width="8" height="8"></td>
			<td background="http://'.IMG_DOMAIN.'/nav/2_11.jpg"></td>
			<td width="8" height="8"><img src="http://'.IMG_DOMAIN.'/nav/2_13.jpg" width="8" height="8"></td>
	</tr>
	</table>';
}






if ($vid==2)
{
	DbConnect();
	PrintTable('open','');
	//6 последних зарегистрировавшихся
	PrintTable('cell_open','6 последних зарегистрировавшихся');

	$game=myquery("(SELECT name,race,user_id from game_users) UNION (SELECT name,race,user_id from game_users_archive) order by user_id DESC limit 6");
	while($elf=mysql_fetch_array($game))
	{
		echo'<tr><td><b>'.$elf['name'].'</b></td><td>'.mysqlresult(myquery("SELECT name FROM game_har WHERE id=".$elf['race'].""),0,0).'</td></tr>';
	}

	PrintTable('cell_close','');
	PrintTable('medium','');
	//Статистика по расам
	PrintTable('cell_open','Статистика по расам');

	$sel_race = myquery("SELECT id,name FROM game_har WHERE disable=0");
	while (list($id,$race) = mysql_fetch_array($sel_race))
	{
		$full=myquery("(SELECT user_id from game_users where race = '$id') UNION (SELECT user_id from game_users_archive where race = '$id')");
		$rows=mysql_num_rows($full);
		echo"<tr><td><b>$race</b></td><td>$rows</td></tr>";
	}

	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==3)
{
	DbConnect();
	PrintTable('open','');
	//5 последних добавленных NPC
	echo '<br>
	<table width="780" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="8" height="8"><img src="http://'.IMG_DOMAIN.'/nav/2_01.jpg" width="8" height="8"></td>
			<td background="http://'.IMG_DOMAIN.'/nav/2_02.jpg"></td>
			<td width="8"><img src="http://'.IMG_DOMAIN.'/nav/2_04.jpg" width="8" height="8"></td>
		</tr>
		<tr>
			<td width="8" background="http://'.IMG_DOMAIN.'/nav/2_05.jpg"></td>
			<td bgcolor="#000000"><div align="center">5 последних добавленных NPC:</div>
				<table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
					  <td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
					  <td background="http://'.IMG_DOMAIN.'/nav/1_09.jpg"></td>
					  <td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td>
					</tr>
					<tr>
					  <td width="5" background="http://'.IMG_DOMAIN.'/nav/1_17.jpg"></td>
					  <td height="100%" bgcolor="313131">
					  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';
	$game=myquery("SELECT game_npc.*,game_npc_template.* from game_npc,game_npc_template WHERE game_npc.view=1 AND game_npc_template.npc_id=game_npc.npc_id order by id DESC limit 5");
	while($elf=mysql_fetch_array($game))
	{
		echo'<tr><td><b>'.$elf['npc_name'].'</b>&nbsp;</td><td>Жизни - <font color=#FF8080>'.$elf['npc_max_hp'].'</font>&nbsp;</td><td>Уровень - <font color=#FF8080>'.$elf['npc_level'].'</font>&nbsp;</td><td>Опыт - <font color=#FF8080>'.$elf['EXP'].'</font>&nbsp;</td><td>Мон. - <font color=#FF8080>'.$elf['npc_gold'].'</font>&nbsp;</td><td>Карта - <font color=#FF8080>'.@mysqlresult(@myquery("SELECT name FROM game_maps WHERE id=".$elf['map_name'].""),0,0).' </font>&nbsp;</td><td>X - <font color=#FF8080>'.$elf['xpos'].'</font>&nbsp;</td><td>Y - <font color=#FF8080>'.$elf['ypos'].'</font></td></tr>';
						}
	PrintTable('cell_close','');
	PrintTable('close','');
}


if ($vid==3)
{
	DbConnectStat();
	PrintTable('open','');
	//5 самых популярных NPC (их больше всего убивают игроки)
	PrintTable('cell_open','5 самых популярных NPC (их больше всего убивают игроки)');
	$game = myquery("SELECT * FROM game_stat_view WHERE stat_id=5 AND npc_id<>0");
	while ($elf = mysql_fetch_array($game))
	{
		$npc = mysql_fetch_array(myquery("SELECT gamerpgsu.game_npc_template.*,gamerpgsu.game_npc.* FROM gamerpgsu.game_npc,gamerpgsu.game_npc_template WHERE gamerpgsu.game_npc.id='".$elf['npc_id']."' AND gamerpgsu.game_npc.npc_id=gamerpgsu.game_npc_template.npc_id"));
		echo'<tr><td><b>'.$npc['npc_name'].'</b></td><td>'.@mysqlresult(@myquery("SELECT name FROM gamerpgsu.game_maps WHERE id=".$npc['map_name'].""),0,0).'</td><td>убит '.$elf['LOSE'].' раз</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 самых сильных NPC (они больше всего убивают игроков)
	PrintTable('cell_open','5 самых сильных NPC (они больше всего убивают игроков)');
	$game = myquery("SELECT * FROM game_stat_view WHERE stat_id=2 AND npc_id<>0");
	while ($elf = mysql_fetch_array($game))
	{
		$npc = mysql_fetch_array(myquery("SELECT gamerpgsu.game_npc_template.*,gamerpgsu.game_npc.* FROM gamerpgsu.game_npc,gamerpgsu.game_npc_template WHERE gamerpgsu.game_npc.id='".$elf['npc_id']."' AND gamerpgsu.game_npc.npc_id=gamerpgsu.game_npc_template.npc_id"));
		echo'<tr><td><b>'.$npc['npc_name'].'</b></td><td>'.@mysqlresult(@myquery("SELECT name FROM gamerpgsu.game_maps WHERE id=".$npc['map_name'].""),0,0).'</td><td>убил '.$elf['WIN'].' раз</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==3)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, чаще всего убивающих NPC
	PrintTable('cell_open','5 игроков, чаще всего убивающих NPC');
	$game = myquery("SELECT * FROM game_stat_view WHERE stat_id=5 AND user_id<>0");
	while ($elf = mysql_fetch_array($game))
	{
		$sel1 = myquery("(SELECT clevel,name,race,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT clevel,name,race,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].' ['.$usr['clevel'].']</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>'.mysqlresult(myquery("SELECT name FROM gamerpgsu.game_har WHERE id=".$usr['race'].""),0,0).'</td><td>убил '.$elf['npc_kill'].' ботов</td></tr>';
		}
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, чаще всего проигрывающих NPC
	PrintTable('cell_open','5 игроков, чаще всего проигрывающих NPC');
	$game = myquery("SELECT * FROM game_stat_view WHERE stat_id=2 AND user_id<>0");
	while ($elf = mysql_fetch_array($game))
	{
		$sel1 = myquery("(SELECT clevel,name,race,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT clevel,name,race,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].' ['.$usr['clevel'].']</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>'.mysqlresult(myquery("SELECT name FROM gamerpgsu.game_har WHERE id=".$usr['race'].""),0,0).'</td><td>Был убит '.$elf['npc_kill'].' раз</td></tr>';
		}
	}

	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==6)
{
	DbConnect();
	PrintTable('open','');
	//Количество заходов в игру за последние 24 дня
	PrintTable('cell_open','Количество заходов в игру за последние 24 дня');
	$result = myquery("SELECT DISTINCT day, COUNT(*) AS refcount FROM game_activity GROUP BY day ORDER BY day DESC limit 24");
	$i = 0;
	while ($report = mysql_fetch_array($result))
	{
		if ($i == 0)
		{
			echo '<tr><td><font size="1" color="#dddddd">'.$report['day'].'</font></td><td><font size="1" color="#dddddd"><div align="right">'.$report['refcount'].'</div></font></td></tr>';
			$i = 1;
		}
		elseif ($i == 1)
		{
			echo '<tr><td><font size="1" color="#999999">'.$report['day'].'</font></td><td><font size="1" color="#999999"><div align="right">'.$report['refcount'].'</div></font></td></tr>';
			$i = 0;
		}
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	//Количество заходов в игру по часам суток
	PrintTable('cell_open','Количество заходов в игру по часам суток');
	$result = myquery("SELECT DISTINCT hour, COUNT(*) AS refcount FROM game_activity GROUP BY hour ORDER BY refcount DESC");
	$i = 0;
	while ($report = mysql_fetch_array($result))
	{
		if ($i == 0)
		{
			echo '<tr><td><font size="1" color="#dddddd">'.$report['hour'].'</font></td><td><font size="1" color="#dddddd"><div align="right">'.$report['refcount'].'</div></font></td></tr>';
			$i = 1;
		}
		elseif ($i == 1)
		{
			echo '<tr><td><font size="1" color="#999999">'.$report['hour'].'</font></td><td><font size="1" color="#999999"><div align="right">'.$report['refcount'].'</div></font></td></tr>';
			$i = 0;
		}
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==7)
{
	DbConnectStat();
	PrintTable('open','');
	//5 самых богатых таверен
	PrintTable('cell_open','5 самых богатых таверен');
	$game=myquery("SELECT * from gamerpgsu.game_tavern order by dohod DESC limit 5");
	while($elf=mysql_fetch_array($game))
	{
		$town=$elf['town'];
		$town_select = myquery("select rustown from gamerpgsu.game_gorod where town='$town'");
		list($rustown)=mysql_fetch_array($town_select);
		$name = @mysqlresult(@myquery("(SELECT name FROM gamerpgsu.game_users WHERE user_id='".$elf['vladel']."') UNION (SELECT name FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['vladel']."')"),0,0);
		echo'<tr><td><b>'.$rustown.'</b></td><td>'.$name.'</td><td>'.$elf['dohod'].'</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	//5 самых популярных таверен
	PrintTable('cell_open','5 самых популярных таверен');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=14 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		$vladel = @mysqlresult(@myquery("SELECT vladel FROM gamerpgsu.game_tavern WHERE town = '".$elf['town_id']."'"),0,0);
		$name = @mysqlresult(@myquery("(SELECT name FROM gamerpgsu.game_users WHERE user_id='".$vladel."') UNION (SELECT name FROM gamerpgsu.game_users_archive WHERE user_id='".$vladel."')"),0,0);
		echo'<tr><td><b>'.$rustown.'</b></td><td>'.$name.'</td><td>Обед '.$elf['kol'].' раз на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==7)
{
	DbConnectStat();
	echo'
	<table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
	<td width="10">&nbsp;</td>
	<td valign=top align=center>';

	//5 самых голодных игроков
	PrintTable('cell_open','5 самых голодных игроков');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=14 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."'");
		if (!mysql_num_rows($sel1))
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."'");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Обед '.$elf['kol'].' раз</td><td>на '.$elf['summa'].' зол.</td></tr>';
		}
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==7)
{
	DbConnectStat();
	PrintTable('open','');
	//5 самых популярных блюд
	PrintTable('cell_open','5 самых популярных блюд');
	$game=myquery("SELECT * FROM gamerpgsu.game_stat_view WHERE stat_id=14 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		$name = @mysqlresult(@myquery("SELECT item FROM gamerpgsu.game_tavern_shop WHERE id = '".$elf['eat_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>'.$rustown.'</td><td>Съели '.$elf['kol'].' раз на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	//5 самых прибыльных блюд
	PrintTable('cell_open','5 самых прибыльных блюд');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=14 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		$name = @mysqlresult(@myquery("SELECT item FROM gamerpgsu.game_tavern_shop WHERE id = '".$elf['eat_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>'.$rustown.'</td><td>Съели '.$elf['kol'].' раз на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==10)
{
	DbConnectStat();
	PrintTable('open','');
	//5 самых популярных рынков
	PrintTable('cell_open','5 самых популярных рынков');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		echo'<tr><td><b>'.$rustown.'</b></td><td>Куплено '.$elf['kol'].' предметов</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	//5 самых прибыльных рынков
	PrintTable('cell_open','5 самых прибыльных рынков');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		echo'<tr><td><b>'.$rustown.'</b></td><td>Куплено '.$elf['kol'].' предметов</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==10)
{
	DbConnectStat();
	PrintTable('open','');
	//5 самых популярных продавцов
	PrintTable('cell_open','5 самых популярных продавцов');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."'");
		if (!mysql_num_rows($sel1))
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."'");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Куплено '.$elf['kol'].' предметов</td><td>на '.$elf['summa'].' зол.</td></tr>';
		}
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 самых прибыльных продавцов
	PrintTable('cell_open','5 самых прибыльных продавцов');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."'");
		if (!mysql_num_rows($sel1))
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."'");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Куплено '.$elf['kol'].' предметов</td><td>на '.$elf['summa'].' зол.</td></tr>';
		}
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==10)
{
	DbConnectStat();
	PrintTable('open','');
	//5 самых популярных предметов на рынке
	PrintTable('cell_open','5 самых популярных предметов на рынке');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=5 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		 echo'<tr><td><b>'.$elf['item_name'].'</b></td><td>Куплен '.$elf['kol'].' раз</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 самых прибыльных предметов на рынке
	PrintTable('cell_open','5 самых дорогих предметов на рынке');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=6 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		echo'<tr><td><b>'.$elf['item_name'].'</b></td><td>Куплен '.$elf['kol'].' раз</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==13)
{
	DbConnectStat();
	PrintTable('open','');
	//5 cамых популярных магазинов по продажам
	PrintTable('cell_open','5 cамых популярных магазинов по продажам');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=10 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>Продано '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 самых прибыльных магазинов по продажам
	PrintTable('cell_open','5 самых прибыльных магазинов по продажам');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=10 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>Продано '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==13)
{
	DbConnectStat();
	PrintTable('open','');
	//5 магазинов, больше всего купивших предметов (кол)
	PrintTable('cell_open','5 магазинов, больше всего купивших предметов (кол)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=11 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>Куплено '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 магазинов, больше всего купивших предметов (сумма)
	PrintTable('cell_open','5 магазинов, больше всего купивших предметов (сумма)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=11 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>Куплено '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==13)
{
	DbConnectStat();
	PrintTable('open','');
	//5 магазинов, больше всего отремонтировавших предметов (кол)
	PrintTable('cell_open','5 магазинов, больше всего отремонтировавших предметов (кол)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=13 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>Ремонт '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 магазинов, больше всего отремонтировавших предметов (сумма)
	PrintTable('cell_open','5 магазинов, больше всего отремонтировавших предметов (сумма)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=13 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>Ремонт '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==13)
{
	DbConnectStat();
	PrintTable('open','');
	//5 магазинов, больше всего идентифицирующих предметов (кол)
	PrintTable('cell_open','5 магазинов, больше всего идентифицирующих предметов (кол)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=12 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>Идент. '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 магазинов, больше всего идентифицирующих предметов (сумма)
	PrintTable('cell_open','5 магазинов, больше всего идентифицирующих предметов (сумма)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=12 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>Идент. '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==17)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, больше всего купивших предметов (кол)
	PrintTable('cell_open','5 игроков, больше всего купивших предметов (кол)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=10 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Купил '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, больше всего купивших предметов (сумма)
	PrintTable('cell_open','5 игроков, больше всего купивших предметов (сумма)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=10 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Купил '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==17)
{
	PrintTable('open','');
	//5 игроков, больше всего продавших предметов (кол)
	PrintTable('cell_open','5 игроков, больше всего продавших предметов (кол)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=11 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Продал '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, больше всего продавших предметов (сумма)
	PrintTable('cell_open','5 игроков, больше всего продавших предметов (сумма)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=11 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Продал '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==17)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, больше всего ремонтирующих предметов (кол)
	PrintTable('cell_open','5 игроков, больше всего ремонтирующих предметов (кол)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=13 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Ремонт '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, больше всего ремонтирующих предметов (сумма)
	PrintTable('cell_open','5 игроков, больше всего ремонтирующих предметов (сумма)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=13 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Ремонт '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==17)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, больше всего идентифицирующих предметов (кол)
	PrintTable('cell_open','5 игроков, больше всего идентифицирующих предметов (кол)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=12 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Идент. '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, больше всего идентифицирующих предметов (сумма)
	PrintTable('cell_open','5 игроков, больше всего идентифицирующих предметов (сумма)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=12 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Идент. '.$elf['kol'].' пред.</td><td>на '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, чаще всего нападающих на других игроков
	PrintTable('cell_open','5 игроков, чаще всего нападающих на других игроков');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=16 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Нападение - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, на которых чаще всего нападают
	PrintTable('cell_open','5 игроков, на которых чаще всего нападают');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=16 AND substat_id=2 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Напали - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, чаще всего выигрывающих битвы у игроков
	PrintTable('cell_open','5 игроков, чаще всего выигрывающих битвы у игроков');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Выиграл битвы - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, чаще всего проигрывающих другим игрокам
	PrintTable('cell_open','5 игроков, чаще всего проигрывающих другим игрокам');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=6 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Проиграл битвы - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 кланов, чаще всего выигрывающих битвы у игроков
	PrintTable('cell_open','5 кланов, чаще всего выигрывающих битвы у игроков');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=6 AND substat_id=2 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("(SELECT nazv FROM gamerpgsu.game_clans WHERE clan_id='".$elf['clan_id']."')"),0,0);
		echo'<tr><td><b><img src="http://'.IMG_DOMAIN.'/clan/'.$elf['clan_id'].'.gif">'.$name.'</b></td><td>Клан выиграл битвы - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 кланов, чаще всего проигрывающих другим игрокам
	PrintTable('cell_open','5 кланов, чаще всего проигрывающих другим игрокам');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=2 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("(SELECT nazv FROM gamerpgsu.game_clans WHERE clan_id='".$elf['clan_id']."')"),0,0);
		echo'<tr><td><b><img src="http://'.IMG_DOMAIN.'/clan/'.$elf['clan_id'].'.gif">'.$name.'</b></td><td>Клан проиграл битвы - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, больше всего заработавших опыта с битв
	PrintTable('cell_open','5 игроков, больше всего заработавших опыта с битв');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=3 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Выиграл - '.$elf['kol'].' раз.</td><td>Получил - '.$elf['summa'].' опыта.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, больше всего заработавших золота с битв
	PrintTable('cell_open','5 игроков, больше всего заработавших золота с битв');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Выиграл - '.$elf['kol'].' раз.</td><td>Заработал - '.$elf['summa'].' зол.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, больше всего выигрывающих битв у игроков меньшего уровня
	PrintTable('cell_open','5 игроков, больше всего выигрывающих битв у игроков меньшего уровня');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=5 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Выиграл - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, больше всего выигрывающих битв у игроков большего уровня
	PrintTable('cell_open','5 игроков, больше всего выигрывающих битв у игроков большего уровня');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=6 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Выиграл - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, больше всего проигравших битв игрокам меньшего уровня
	PrintTable('cell_open','5 игроков, больше всего проигравших битв игрокам меньшего уровня');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=6 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Проиграл - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 игроков, больше всего проигравших битв игрокам большего уровня
	PrintTable('cell_open','5 игроков, больше всего проигравших битв игрокам большего уровня');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=6 AND substat_id=4");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Проиграл - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 игроков, чаще всего вылетающих из битв по тайму
	PrintTable('cell_open','5 игроков, чаще всего вылетающих из битв по тайму');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=3");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>Вылетел по таймауту - '.$elf['kol'].' раз.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	PrintTable('close','');
}

echo '</center><br>';
}


if (function_exists("save_debug")) save_debug(); 

?>