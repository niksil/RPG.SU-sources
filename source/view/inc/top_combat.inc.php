<?

if (function_exists("start_debug")) start_debug(); 

$result = myquery("(SELECT name,user_id,win,clan_id FROM game_users WHERE clan_id<>1 AND clan_id<>4) UNION (SELECT name,user_id,win,clan_id FROM game_users_archive WHERE clan_id<>1 AND clan_id<>4) ORDER BY win DESC LIMIT 10");
echo'<table cellpadding="0" cellspacing="4" border=0><tr><td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>����� �������</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="120"><font size="2" color="#eeeeee">�����</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="����"></a>';
	if ($player['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif"> ';
echo'' . $player['name'] . '</font></td><td>' . $player['win'] . '</td></tr>';
}
echo'</table><br>';


//���������� �� �����
$sel_race = myquery("SELECT * FROM game_har WHERE disable=0 ORDER BY name");
while ($race = mysql_fetch_array($sel_race))
{
	$rac = '';
	switch ($race['race'])
	{
		case 'Elf':
			$rac = '������';
		break;
		case 'Orc':
			$rac = '�����';
		break;
		case 'Nazgul':
			$rac = '��������';
		break;
		case 'Hobbit':
			$rac = '��������';
		break;
		case 'Human':
			$rac = '�����';
		break;
		case 'Gnom':
			$rac = '������';
		break;
		case 'Goblin':
			$rac = '��������';
		break;
		case 'Troll':
			$rac = '�������';
		break;
	}
	$result = myquery("(SELECT game_users.clan_id, game_users.name, game_users.clevel, game_users.user_id, game_users.exp FROM game_users Join game_users_data On game_users.user_id=game_users_data.user_id WHERE race=".$race['id']." AND clan_id<>1 AND clan_id<>4 AND game_users_data.last_visit>'1247616000') UNION (SELECT game_users_archive.clan_id, game_users_archive.name, game_users_archive.clevel, game_users_archive.user_id, game_users_archive.exp from game_users_archive Join game_users_data On game_users_archive.user_id=game_users_data.user_id WHERE race=".$race['id']." AND clan_id<>1 AND clan_id<>4 AND game_users_data.last_visit>'1247616000') ORDER BY clevel DESC, EXP DESC LIMIT 10");
	echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>10 ������ '.$rac.'</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="120"><font size="2" color="#eeeeee">�������</font></td></tr>';
	for ($i = 1; $player = mysql_fetch_array($result); $i++)
	{
		echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="����"></a>';
			if ($player['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif"> ';
		echo'' . $player['name'] . '</font></td><td>' . $player['clevel'] . '</td></tr>';
	  }
	echo '</table><br>';
}

//���� �� ����������
$result = myquery("(SELECT * FROM game_users WHERE sklon=1 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) UNION (SELECT * FROM game_users_archive WHERE sklon=1 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) ORDER BY win DESC LIMIT 10");
echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>10 ������ ������� ����������� ����������</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="120"><font size="2" color="#eeeeee">���-�� �����</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="����"></a>';
		if ($player['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif"> ';
	echo'' . $player['name'] . '</font></td><td>' . $player['win'] . '</td></tr>';
  }
echo '</table><br>';

$result = myquery("(SELECT * FROM game_users WHERE sklon=2 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) UNION (SELECT * FROM game_users_archive WHERE sklon=2 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) ORDER BY win DESC LIMIT 10");
echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>10 ������ ������� ������� ����������</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="120"><font size="2" color="#eeeeee">���-�� �����</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="����"></a>';
		if ($player['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif"> ';
	echo'' . $player['name'] . '</font></td><td>' . $player['win'] . '</td></tr>';
  }
echo '</table><br>';

$result = myquery("(SELECT * FROM game_users WHERE sklon=3 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) UNION (SELECT * FROM game_users_archive WHERE sklon=3 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) ORDER BY win DESC LIMIT 10");
echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="150"><font face="Verdana" size="3" color="#f3f3f3"><b>10 ������ ������� ������ ����������</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="120"><font size="2" color="#eeeeee">���-�� �����</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.DOMAIN.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="����"></a>';
		if ($player['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif"> ';
	echo'' . $player['name'] . '</font></td><td>' . $player['win'] . '</td></tr>';
  }
echo '</table><br>';


if (function_exists("save_debug")) save_debug(); 

?>