<?

if (function_exists("start_debug")) start_debug(); 

$result = myquery("SELECT * FROM game_clans WHERE clan_id<>1 AND raz=0 ORDER BY raring DESC LIMIT 10");
echo '
<table cellpadding="0" cellspacing="4" border=0>
<tr>
<td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>10 кланов по рейтингу</b></font><br></td><td></td>
<td colspan="2"><font size="2" color="#eeeeee">Клан</font></td>
<td width="220"><font size="2" color="#eeeeee">Глава</font></td>
<td width="120"><font size="2" color="#eeeeee">Рейтинг</font></td>
</tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	echo'
	<tr>
	<td></td>
	<td><font size="2" color="#bbbbbb">' . $i . '</font></td>
	<td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?clan='.$player["clan_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif" border=0 alt="Инфо"></a></td>
	<td><a href="http://'.DOMAIN.'/view/?clan='.$player["clan_id"].'" target="_blank">'.$player['nazv'].'</a></td>';
	$s = myquery("SELECT name FROM game_users WHERE user_id=".$player['glava']."");
	if (!mysql_num_rows($s)) $s=myquery("SELECT name FROM game_users_archive WHERE user_id=".$player['glava']."");
	echo'<td>' . mysqlresult($s,0,0) . '</font></td><td>' . $player['raring'] . '</td></tr>';
}
echo '</table><br>';

$result = myquery("SELECT * FROM game_clans AS a WHERE clan_id<>1 AND raz=0");
$arr = Array();
while ($clan = mysql_fetch_array($result))
{
	$arr[$clan['clan_id']] = mysqlresult(myquery("SELECT COUNT(*) FROM game_users WHERE clan_id=".$clan['clan_id'].""),0,0)+mysqlresult(myquery("SELECT COUNT(*) FROM game_users_archive WHERE clan_id=".$clan['clan_id'].""),0,0);
}
arsort($arr);

echo '
<table cellpadding="0" cellspacing="4" border=0>
<tr>
<td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>10 кланов по кол-ву игроков</b></font><br></td><td></td>
<td colspan="2"><font size="2" color="#eeeeee">Клан</font></td>
<td width="220"><font size="2" color="#eeeeee">Глава</font></td>
<td width="120"><font size="2" color="#eeeeee">Кол-во игроков</font></td>
</tr>';
$i=0;
foreach ($arr as $key => $val) {
	$i++;
	if ($i>10) break;
	//echo "$key = $val\n";
	$player = mysql_fetch_array(myquery("SELECT * FROM game_clans WHERE clan_id=$key"));
	echo'
	<tr>
	<td></td>
	<td><font size="2" color="#bbbbbb">' . $i . '</font></td>
	<td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?clan='.$player["clan_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif" border=0 alt="Инфо"></a></td>
	<td><a href="http://'.DOMAIN.'/view/?clan='.$player["clan_id"].'" target="_blank">'.$player['nazv'].'</a></td>';
	$s = myquery("SELECT name FROM game_users WHERE user_id=".$player['glava']."");
	if (!mysql_num_rows($s)) $s=myquery("SELECT name FROM game_users_archive WHERE user_id=".$player['glava']."");
	echo'<td>' . mysqlresult($s,0,0) . '</font></td><td>' . $val . '</td></tr>';
}
echo '</table><br>';


$result = myquery("SELECT * FROM game_clans AS a WHERE clan_id<>1 AND raz=0");
$arr = Array();
while ($clan = mysql_fetch_array($result))
{
	$all = mysqlresult(myquery("SELECT COUNT(*) FROM game_users WHERE clan_id=".$clan['clan_id'].""),0,0)+mysqlresult(myquery("SELECT COUNT(*) FROM game_users_archive WHERE clan_id=".$clan['clan_id'].""),0,0);
	$sumlevel1 = mysqlresult(myquery("SELECT SUM(clevel) FROM game_users WHERE clan_id=".$clan['clan_id'].""),0,0);
	$sumlevel2 = mysqlresult(myquery("SELECT SUM(clevel) FROM game_users_archive WHERE clan_id=".$clan['clan_id'].""),0,0);
	if ($all==0)
	{
		$arr[$clan['clan_id']] = 0;
	}
	else
	{
		$arr[$clan['clan_id']] = round(($sumlevel1+$sumlevel2)/$all,2);
	}
}
arsort($arr);

echo '
<table cellpadding="0" cellspacing="4" border=0>
<tr>
<td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>10 кланов по среднему уровню игроков</b></font><br></td><td></td>
<td colspan="2"><font size="2" color="#eeeeee">Клан</font></td>
<td width="220"><font size="2" color="#eeeeee">Глава</font></td>
<td width="120"><font size="2" color="#eeeeee">Сред. уровень</font></td>
</tr>';
$i=0;
foreach ($arr as $key => $val) {
	$i++;
	if ($i>10) break;
	//echo "$key = $val\n";
	$player = mysql_fetch_array(myquery("SELECT * FROM game_clans WHERE clan_id=$key"));
	echo'
	<tr>
	<td></td>
	<td><font size="2" color="#bbbbbb">' . $i . '</font></td>
	<td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?clan='.$player["clan_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif" border=0 alt="Инфо"></a></td>
	<td><a href="http://'.DOMAIN.'/view/?clan='.$player["clan_id"].'" target="_blank">'.$player['nazv'].'</a></td>';
	$s = myquery("SELECT name FROM game_users WHERE user_id=".$player['glava']."");
	if (!mysql_num_rows($s)) $s=myquery("SELECT name FROM game_users_archive WHERE user_id=".$player['glava']."");
	echo'<td>' . mysqlresult($s,0,0) . '</font></td><td>' . $val . '</td></tr>';
}
echo '</table><br>';



if (function_exists("save_debug")) save_debug(); 

?>