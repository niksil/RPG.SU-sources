<?

if (function_exists("start_debug")) start_debug(); 

function print_top($craft_index)
{
	if ($craft_index<=3)
	{
		$result = myquery("(SELECT game_users_crafts.*,game_users.clan_id,game_users.name FROM game_users_crafts,game_users WHERE game_users_crafts.craft_index=$craft_index AND game_users_crafts.times>0 AND game_users_crafts.user_id=game_users.user_id AND game_users.clan_id<>1) UNION (SELECT game_users_crafts.*,game_users_archive.clan_id,game_users_archive.name FROM game_users_crafts,game_users_archive WHERE game_users_crafts.craft_index=$craft_index AND game_users_crafts.times>0 AND game_users_crafts.user_id=game_users_archive.user_id AND game_users_archive.clan_id<>1) ORDER BY times DESC  LIMIT 10");
	}
	else
	{
		$result = myquery("(SELECT game_users_crafts.*,game_users.clan_id,game_users.name FROM game_users_crafts,game_users WHERE game_users_crafts.craft_index=$craft_index AND game_users_crafts.profile=1 AND game_users_crafts.times>0 AND game_users_crafts.user_id=game_users.user_id AND game_users.clan_id<>1) UNION (SELECT game_users_crafts.*,game_users_archive.clan_id,game_users_archive.name FROM game_users_crafts,game_users_archive WHERE game_users_crafts.craft_index=$craft_index AND game_users_crafts.times>0 AND game_users_crafts.profile=1 AND game_users_crafts.user_id=game_users_archive.user_id AND game_users_archive.clan_id<>1) ORDER BY times DESC  LIMIT 10");
	}
	echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>10 лучших игроков в профессии "'.get_craft_name($craft_index).'"</b></font><br></td><td width="50"><font size="2" color="#eeeeee">Ранг</font></td><td width="220"><font size="2" color="#eeeeee">Ник</font></td><td width="120"><font size="2" color="#eeeeee">Уровень</font></td></tr>';
	for ($i = 1; $craft = mysql_fetch_array($result); $i++)
	{
		echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?userid='.$craft["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>';
			if ($craft['clan_id']!=0) echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$craft['clan_id'].'.gif"> ';
		echo'' . $craft['name'] . '</font></td><td>' . floor(CraftSpetsTimeToLevel($craft_index,$craft['times'])) . ' ('.$craft['times'].')</td></tr>';
	}
	echo '</table><br>';
}

$sel = myquery("SELECT DISTINCT craft_index FROM game_users_crafts WHERE profile=1 AND times>0");
while (list($craft_index)=mysql_fetch_array($sel))
{
	print_top($craft_index);
}

$result = myquery("(SELECT game_users.user_id, game_users.clan_id, game_users.name, game_users_guild.guild_times, game_users_guild.guild_lev FROM game_users_guild Join game_users On game_users_guild.user_id=game_users.user_id WHERE game_users.clan_id<>1 AND game_users.clan_id<>4) UNION (SELECT game_users_archive.user_id, game_users_archive.clan_id, game_users_archive.name, game_users_guild.guild_times, game_users_guild.guild_lev FROM game_users_guild Join game_users_archive On game_users_guild.user_id=game_users_archive.user_id WHERE game_users_archive.clan_id<>1 AND game_users_archive.clan_id<>4) ORDER BY guild_times DESC LIMIT 10");
echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>10 лучших наёмников</b></font><br></td><td width="50"><font size="2" color="#eeeeee">Ранг</font></td><td width="220"><font size="2" color="#eeeeee">Ник</font></td><td width="120"><font size="2" color="#eeeeee">Уровень</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>';
		if ($player['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif"> ';
	echo'' . $player['name'] . '</font></td><td>' . $player['guild_lev'] . ' (' . $player['guild_times'] . ')</td></tr>';
}
echo '</table><br>';

if (function_exists("save_debug")) save_debug(); 

?>