<?

if (function_exists("start_debug")) start_debug(); 

if ($char['name'] == 'The_Elf' or $char['name'] == 'blazevic' or $char['name'] == 'Zander' or $char['name'] == 'Victor' or $char['name'] == 'High_Elf' or $char['name'] == 'Stream_Dan' or $char['name'] == 'mrHawk')
{
	if (isset($del))
	{
		$del=myquery("delete from game_admins where user_id='$del'");
		echo'Удалено';
	}

	if (isset($edit))
	{
		if (!isset($see))
		{
			echo'<center><form action="" method="post">
			<font size="1" face="Verdana" color="#ffffff">';

			echo'<table width=70% border=0 align=left>';

			$kto=myquery("select * from game_admins where user_id=$edit");
			while($adm=mysql_fetch_array($kto))
			{
				$name=myquery("select name from game_users where user_id='".$adm['user_id']."'");
				if (!mysql_num_rows($name)) $name=myquery("select name from game_users_archive where user_id='".$adm['user_id']."'");
				list($name)=mysql_fetch_array($name);

echo'<tr><td align=right>Имя</td><td>'.$name.'</td></tr>';
echo'<tr><td align=right>Онлайн</td><td><input name="online" type="checkbox" value="1" checked></td></tr>';
echo'<tr><td align=right>Телепорт</td><td><input name="teleport" type="checkbox" value="1"'; if($adm['teleport']>=1) echo'checked'; echo'><input name="teleport" type="checkbox" value="2"'; if($adm['teleport']==2) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Новости</td><td><input name="news" type="checkbox" value="1"'; if($adm['news']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Чат</td><td><input name="chat" type="checkbox" value="1"'; if($adm['chat']==1) echo'checked'; echo'></td></tr>';

echo'<tr><td align=right>Законы</td><td><input name="zakon" type="checkbox" value="1"'; if($adm['zakon']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Помощь</td><td><input name="help" type="checkbox" value="1"'; if($adm['help']==1) echo'checked'; echo'></td></tr>';


echo'<tr><td align=right>Статистика</td><td><input name="stat" type="checkbox" value="1"'; if($adm['stat']==1) echo'checked'; echo'><td></td></tr>';
echo'<tr><td align=right>Бан</td><td><input name="ban" type="checkbox" value="1"'; if($adm['ban']>=1) echo'checked'; echo'><input name="ban" type="checkbox" value="2"'; if($adm['ban']>=2) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Снять бан</td><td><input name="unban" type="checkbox" value="1"'; if($adm['unban']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Печать</td><td><input name="pech" type="checkbox" value="1"'; if($adm['pech']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Снять печать</td><td><input name="unpech" type="checkbox" value="1"'; if($adm['unpech']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Наказания игрока</td><td><input name="nakaz" type="checkbox" value="1"'; if($adm['nakaz']==1) echo'checked'; echo'></td></tr>';
//echo'<tr><td align=right>Лабиринт</td><td><input name="lab" type="checkbox" value="1"'; if($adm['lab']==1) echo'checked'; echo'></td></tr>';
//echo'<tr><td align=right>Из лабиринта</td><td><input name="unlab" type="checkbox" value="1"'; if($adm['unlab']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Удалить игрока</td><td><input name="delu" type="checkbox" value="1"'; if($adm['del']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>NPC</td><td><input name="npc" type="checkbox" value="1"'; if($adm['npc']>=1) echo'checked'; echo'><input name="npc" type="checkbox" value="2"'; if($adm['npc']==2) echo'checked'; echo'><input name="npc" type="checkbox" value="3"'; if($adm['npc']==3) echo'checked'; echo'><input name="npc" type="checkbox" value="4"'; if($adm['npc']==4) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Редактор карт</td><td><input name="map" type="checkbox" value="1"'; if($adm['map']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Редактор предметов</td><td><input name="items" type="checkbox" value="1"'; if($adm['items']>=1) echo'checked'; echo'><input name="items" type="checkbox" value="2"'; if($adm['items']==2) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Редактор городов</td><td><input name="gorod" type="checkbox" value="1"'; if($adm['gorod']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Редактор торговцев</td><td><input name="shop" type="checkbox" value="1"'; if($adm['shop']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Лог боя</td><td><input name="log_war" type="checkbox" value="1"'; if($adm['log_war']>=1) echo'checked'; echo'><input name="log_war" type="checkbox" value="2"'; if($adm['log_war']==2) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Лог админов</td><td><input name="log_adm" type="checkbox" value="1"'; if($adm['log_adm']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Управление магами</td><td><input name="mag" type="checkbox" value="1"'; if($adm['mag']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Навыки</td><td><input name="spets" type="checkbox" value="1"'; if($adm['spets']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Чтение почты</td><td><input name="pm" type="checkbox" value="1"'; if($adm['pm']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Таверна</td><td><input name="tavern" type="checkbox" value="1"'; if($adm['tavern']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Управление игроками</td><td><input name="users" type="checkbox" value="1"'; if($adm['users']>=1) echo'checked'; echo'><input name="users" type="checkbox" value="2"'; if($adm['users']==2) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Форум</td><td><input name="forum" type="checkbox" value="1"'; if($adm['forum']>=1) echo'checked'; echo'><input name="forum" type="checkbox" value="2"'; if($adm['forum']==2) echo'checked';
echo'></td></tr>';
echo'<tr><td align=right>Поиск предметов</td><td><input name="search_items" type="checkbox" value="1"'; if($adm['search_items']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Поиск игроков</td><td><input name="search_users" type="checkbox" value="1"'; if($adm['search_users']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Логи боев за сегодня</td><td><input name="log_war_today" type="checkbox" value="1"'; if($adm['log_war_today']==1) echo' checked'; echo'></td></tr>';
echo'<tr><td align=right>Управление медалями</td><td><input name="medal" type="checkbox" value="1"'; if($adm['medal']==1) echo' checked'; echo'></td></tr>';
echo'<tr><td align=right>Кони</td><td><input name="koni" type="checkbox" value="1"'; if($adm['koni']==1) echo' checked'; echo'></td></tr>';
echo'<tr><td align=right>Ресурсы</td><td><input name="resource" type="checkbox" value="1"'; if($adm['resource']==1) echo' checked'; echo'></td></tr>';
echo'<tr><td align=right>Шахты</td><td><input name="mine" type="checkbox" value="1"'; if($adm['mine']==1) echo'checked'; echo'></td></tr>';
echo'<tr><td align=right>Бот (чат)</td><td><input name="bot_chat" type="checkbox" value="1"'; if($adm['bot_chat']==1) echo' checked'; echo'></td></tr>';
echo'<tr><td align=right>Бот (бой)</td><td><input name="bot_combat" type="checkbox" value="1"'; if($adm['bot_combat']==1) echo' checked'; echo'></td></tr>';
echo'<tr><td align=right>Деньги в банке</td><td><input name="bank" type="checkbox" value="1"'; if($adm['bank']==1) echo' checked'; echo'></td></tr>';
echo'<tr><td align=right>Квесты</td><td><input name="quest" type="checkbox" value="1"'; if($adm['quest']==1) echo' checked'; echo'></td></tr>';
echo'<tr><td align=right>Режим тени</td><td><input name="hide" type="checkbox" value="1"'; if($adm['hide']>=1) echo' checked'; echo'><input name="hide" type="checkbox" value="2"'; if($adm['hide']==2) echo' checked'; echo'></td></tr>';
echo'<tr><td align=right>Личный рейтинг</td><td><input name="lr" type="checkbox" value="1"'; if($adm['lr']==1) echo' checked'; echo'></td></tr>';
		}


echo'<tr><td align=right><input name="submit" type="submit" value="Сохранить">
<input name="see" type="hidden" value="">
</form></td></tr>';
echo'</table>';
}
else
{
if(!isset($online)) $online='0';
if(!isset($teleport)) $teleport='0';
if(!isset($news)) $news='0';
if(!isset($zakon)) $zakon='0';
if(!isset($help)) $help='0';
if(!isset($stat)) $stat='0';
if(!isset($ban)) $ban='0';
if(!isset($unban)) $unban='0';
if(!isset($pech)) $pech='0';
if(!isset($unpech)) $unpech='0';
if(!isset($lab)) $lab='0';
if(!isset($unlab)) $unlab='0';
if(!isset($delu)) $delu='0';
if(!isset($npc)) $npc='0';
if(!isset($map)) $map='0';
if(!isset($items)) $items='0';
if(!isset($nakaz)) $nakaz='0';
if(!isset($gorod)) $gorod='0';
if(!isset($shop)) $shop='0';
if(!isset($log_war)) $log_war='0';
if(!isset($log_adm)) $log_adm='0';
if(!isset($mag)) $mag='0';
if(!isset($spets)) $spets='0';
if(!isset($pm)) $pm='0';
if(!isset($tavern)) $tavern='0';
if(!isset($users)) $users='0';
if(!isset($forum)) $forum='0';
if(!isset($search_items)) $search_items='0';
if(!isset($search_users)) $search_users='0';
if(!isset($log_war_today)) $log_war_today='0';
if(!isset($medal)) $medal='0';
if(!isset($koni)) $koni='0';
if(!isset($chat)) $chat='0';
if(!isset($resource)) $resource='0';
if(!isset($mine)) $mine='0';
if(!isset($bot_chat)) $bot_chat='0';
if(!isset($bot_combat)) $bot_combat='0';
if(!isset($bank)) $bank='0';
if(!isset($quest)) $quest='0';
if(!isset($hide)) $hide='0';
if(!isset($lr)) $lr='0';

$up=myquery("update game_admins set
online='$online',
teleport='$teleport',
news='$news',
zakon='$zakon',
help='$help',
stat='$stat',
ban='$ban',
unban='$unban',
pech='$pech',
unpech='$unpech',
lab='$lab',
nakaz='$nakaz',
unlab='$unlab',
del='$delu',
npc='$npc',
map='$map',
items='$items',
gorod='$gorod',
shop='$shop',
log_war='$log_war',
log_adm='$log_adm',
mag='$mag',
pm='$pm',
spets='$spets',
users='$users',
tavern='$tavern',
search_items='$search_items',
search_users='$search_users',
log_war_today='$log_war_today',
medal='$medal',
forum='$forum',
koni='$koni',
chat='$chat',
resource='$resource',
bank='$bank',
quest='$quest',
bot_chat='$bot_chat',
bot_combat='$bot_combat',
hide='$hide',
mine='$mine',
lr='$lr'
where user_id='$edit'");
echo '<br>Готово';
}


}
if(!isset($edit) and !isset($new))
{
	$ktoo=myquery("select user_id from game_admins");
	while($stat=mysql_fetch_array($ktoo))
	{
		$name=myquery("select name from game_users where user_id='".$stat['user_id']."'");
		if (!mysql_num_rows($name)) $name=myquery("select name from game_users_archive where user_id='".$stat['user_id']."'");
		list($name)=mysql_fetch_array($name);
		echo '<center><b>'.$name.'</b> - [<a href=?opt=main&option=prava&edit='.$stat['user_id'].'>Редактировать</a>] [<a href=?opt=main&option=prava&del='.$stat['user_id'].'>Удалить</a>]<br>';
	}
	echo'<a href="?opt=main&option=prava&new">Добавить</a>';
}

if(isset($new))
{
	if (!isset($see))
	{
		echo'<div id="content" onclick="hideSuggestions();"><center><form action="" method="post">
		<font size="1" face="Verdana" color="#ffffff">
		Ник: <input name="name_v" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div> <input name="submit" type="submit" value="Добавить">
		<input name="see" type="hidden" value="">
		</form></div><script>init();</script>';
	}
	else
	{
		$sel=myquery("select user_id from game_users where name='$name_v'");
		if (!mysql_num_rows($sel)) $sel=myquery("select user_id from game_users_archive where name='$name_v'");
		list($us)=mysql_fetch_array($sel);
		$update=myquery("insert into game_admins (user_id) values ('$us')");
		echo '<br>Готово';
	}
}
}

if (function_exists("save_debug")) save_debug(); 

?>