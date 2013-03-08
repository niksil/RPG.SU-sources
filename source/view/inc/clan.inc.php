<?

if (function_exists("start_debug")) start_debug(); 

echo'<br><center><select id="clan">';

$clan=(int)$clan;

$result = myquery("SELECT * from game_clans where raz='0' order by clan_id");
$map=mysql_fetch_array($result);
while($map=mysql_fetch_array($result))
{
	echo '<option value="'.$map['clan_id'].'"';
	if ($map['clan_id']==$clan) echo ' selected';
	echo '>'.$map['nazv'].'</option>';
}


echo '</select><input type="button" value="&nbsp;&nbsp;&nbsp;ок&nbsp;&nbsp;&nbsp;" onClick="location.href=\'?clan=\'+document.getElementById(\'clan\').value"><br><br>';

if($clan==0) $clan=1;

$clan=myquery("select * from game_clans where clan_id=$clan and raz=0 and clan_id!=0");
if ($clan==false OR mysql_num_rows($clan)==0)
{
	echo 'Клан не найден';
}
else
{


//        echo("\n\$reg:".);

/*
        $live_res=myquery("SELECT UNIX_TIMESTAMP(`reg_time`) AS `reg`, UNIX_TIMESTAMP(`unreg_time`) AS `unreg` FROM `game_clans` WHERE clan_id = ".$clan['clan_id'].";");
        $live_res = mysql_fetch_array($live_res);
//        print_r($live_res);
*/

	$clan=mysql_fetch_array($clan);
	$kol=myquery("(SELECT user_id FROM game_users WHERE clan_id='".$clan['clan_id']."') UNION (SELECT user_id FROM game_users_archive WHERE clan_id='".$clan['clan_id']."')");
        $kol=mysql_num_rows($kol);

        $query = "SELECT UNIX_TIMESTAMP(`reg_time`) AS `reg`, UNIX_TIMESTAMP(`unreg_time`) AS `unreg` FROM `game_clans` WHERE clan_id = ".((string)$clan['clan_id']).";";
        $live_res=myquery($query);
        $live_res = mysql_fetch_array($live_res);
        $live_reg = date("j.m.Y", $live_res['reg']);

/*
        $live_reg = getdate($live_res['reg']);
        $mon = array(1 => "Январь", "Февраль", "Март", "Апрель", "Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь");
*/

/*
    $old=myquery("(SELECT * FROM game_clans_vozrast WHERE clan_id='".$clan['clan_id']."' Order By id Limit 1)");
    $old=mysql_fetch_array($old); 
    $m=(int)($old['month']-1);
*/

/*
	echo ("<pre>");
        print_r($clan);
	echo ("</pre>");
*/
	echo '<table width=600 border=0><tr><td valign=top width=50%>';
	echo '<font size=2 face=verdana><img src="http://'.IMG_DOMAIN.'/clan/'.$clan['clan_id'].'.gif" title="Значок клана"> '.$clan['nazv'].' ('.$kol.' человек)';
	echo '<br><img src="http://'.IMG_DOMAIN.'/clan/'.$clan['clan_id'].'_logo.gif" title="Логотип клана">';
	echo '</td><td valign=top><font size=2 face=verdana>№ '.$clan['clan_id'].'</font>
	<br><br><font size=2 face=verdana>Сайт клана: <a href="'.$clan['site'].'">'.$clan['site'].'</a></font>

        <br><br><font size=2 face=verdana>Дата создания клана: <b>'.$live_reg/*$live_reg['mday']." ".$mon[$live_reg['mon']]." ".$live_reg['year']*/.' г.</b></font> 

	<br><br><font size=2 face=verdana>Рейтинг клана: <b>'.$clan['raring'].'</b></font>
	<br><br><font size=2 face=verdana>Количество побед в битвах: <b>'.$clan['wins'].'</b></font>
	<br><br><font size=2 face=verdana>Заслуги клана: <b><ol>';
	$selzaslugi = myquery("SELECT * FROM game_clans_zaslugi WHERE clan_id=".$clan['clan_id']." ORDER BY id ASC");
	if ($selzaslugi!=false)
	{
		while ($zas = mysql_fetch_array($selzaslugi))
		{
			echo '<li>'.$zas['zaslugi'].'</li>';
		}
	}
	echo '</ol></font>
	<br><font size=2 face=verdana>Склонность клана: ';
	if ($clan['sklon']==0) echo '&nbsp;&nbsp;Без склонности';
	if ($clan['sklon']==1) echo '<img src="http://'.IMG_DOMAIN.'/sklon/neutral.gif" border="0">&nbsp;&nbsp;<span style="color:#D0D0D0;font-weight:800;">Нейтральная</span>';
	if ($clan['sklon']==2) echo '<img src="http://'.IMG_DOMAIN.'/sklon/light.gif" border="0">&nbsp;&nbsp;<span style="color:#FFFFC0;font-weight:800;">Светлая</span>';
	if ($clan['sklon']==3) echo '<img src="http://'.IMG_DOMAIN.'/sklon/dark.gif" border="0">&nbsp;&nbsp;<span style="color:#969696;font-weight:800;">Темная</span>';
	echo '</font>';

	echo '</td></tr><tr><td colspan=2 align="center">';
	echo '<div align="justify"><font color=white>'.$clan['opis'].'</font></div></td></tr><td align="center">';
	echo '<font color=ffff00>';
	echo '<b><br>Глава клана:</b>';
	echo '</font></td><td align="center">';
	echo '<font color=ffff00>';
	echo'<b><br>Заместители главы клана:</b>';
	echo '</font></td></tr>';

	//Глава клана
	echo '<td valign="middle"><font color=ff0000 size=3>';
	$cl=myquery("select user_id,name,race,clevel,clan_id from game_users where user_id='".$clan['glava']."'");
	if (!mysql_num_rows($cl)) $cl=myquery("select user_id,name,race,clevel,clan_id from game_users_archive where user_id='". $clan['glava']."'");
	while ($users = mysql_fetch_array($cl))
	{
		list($rating,$zvanie) = mysql_fetch_array(myquery("SELECT clan_rating,clan_zvanie FROM game_users_data WHERE user_id='".$users['user_id']."'"));
		echo'<a href="http://'.DOMAIN.'/view/?userid='.$users["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a><b>'.$users['name'].'</b> ('.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$users['race'].""),0,0).' '.$users['clevel'].' уровня)';
		echo '</font>';
		if ($rating!='0')
		   echo '<br>   Рейтинг в клане - <b>'.$rating.'</b>';
		if ($zvanie!='')
		   echo '<br>   Звание в клане - <b>'.$zvanie.'</b>';
	}
	echo '</font></td><td>';

	//zam1
	if ($clan['zam1']!=$clan['glava'])
	{
		$cl=myquery("select user_id,name,race,clevel,clan_id from game_users where user_id=". $clan['zam1']."");
		if (!mysql_num_rows($cl)) $cl=myquery("select user_id,name,race,clevel,clan_id from game_users_archive where user_id='". $clan['zam1']."'");
		while ($users = mysql_fetch_array($cl))
		{
			list($rating,$zvanie) = mysql_fetch_array(myquery("SELECT clan_rating,clan_zvanie FROM game_users_data WHERE user_id='".$users['user_id']."'"));
			echo '<font color=ff0000>';
			echo '<br><a href="http://'.DOMAIN.'/view/?userid='.$users["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a><b>'.$users['name'].'</b> ('.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$users['race'].""),0,0).' '.$users['clevel'].' уровня)';
			echo '</font>';
			if ($rating!='0')
			   echo '<br>   Рейтинг в клане - <b>'.$rating.'</b>';
			if ($zvanie!='')
			   echo '<br>   Звание в клане - <b>'.$zvanie.'</b>';
		}
	}

	//zam2
	if ($clan['zam2']!=$clan['glava'] AND $clan['zam2']!=$clan['zam1'])
	{
		$cl=myquery("select user_id,name,race,clevel,clan_id from game_users where user_id=".$clan['zam2']."");
		if (!mysql_num_rows($cl)) $cl=myquery("select user_id,name,race,clevel,clan_id from game_users_archive where user_id='". $clan['zam2']."'");
		while ($users = mysql_fetch_array($cl))
		{
			list($rating,$zvanie) = mysql_fetch_array(myquery("SELECT clan_rating,clan_zvanie FROM game_users_data WHERE user_id='".$users['user_id']."'"));
			echo '<font color=ff0000>';
			echo '<br><a href="http://'.DOMAIN.'/view/?userid='.$users["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a><b>'.$users['name'].'</b> ('.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$users['race'].""),0,0).' '.$users['clevel'].' уровня)';
			echo '</font>';
			if ($rating!='0')
			   echo '<br>   Рейтинг в клане - <b>'.$rating.'</b>';
			if ($zvanie!='')
			   echo '<br>   Звание в клане - <b>'.$zvanie.'</b>';
		}
	}

	//zam3
	if ($clan['zam3']!=$clan['glava'] AND $clan['zam3']!=$clan['zam1'] AND $clan['zam3']!=$clan['zam2'])
	{
		$cl=myquery("select user_id,name,race,clevel,clan_id from game_users where user_id=". $clan['zam3']."");
		if (!mysql_num_rows($cl)) $cl=myquery("select user_id,name,race,clevel,clan_id from game_users_archive where user_id='". $clan['zam3']."'");
		while ($users = mysql_fetch_array($cl))
		{
			list($rating,$zvanie) = mysql_fetch_array(myquery("SELECT clan_rating,clan_zvanie FROM game_users_data WHERE user_id='".$users['user_id']."'"));
			echo '<font color=ff0000>';
			echo '<br><a href="http://'.DOMAIN.'/view/?userid='.$users["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a><b>'.$users['name'].'</b> ('.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$users['race'].""),0,0).' '.$users['clevel'].' уровня)</b>';
			echo '</font>';
			if ($rating!='0')
				echo '<br>   Рейтинг в клане - <b>'.$rating.'</b>';
			if ($zvanie!='')
				echo '<br>   Звание в клане - <b>'.$zvanie.'</b>';
		}
	}

	echo '</td></tr><tr><td colspan=2>';
	echo '<font color=ffff00 size=2>';
	echo'<br><center>Состав клана:<br>';
	echo '</font></td></tr><tr><td colspan=2>';
	if (!isset($_GET['sort']))
	{
		$sort_order='clevel DESC';
	}
	else
	{
		$sort=(int)$_GET['sort'];
		if($sort==2) $sort_order='clevel DESC';
		elseif($sort==1) $sort_order='name ASC';
		else $sort_order='clevel DESC';
	} 

	echo '<table width=100%>
	<tr><td align=center width=25%><a href=?clan='.$clan['clan_id'].'&sort=1>Имя</a></td><td align=center width=25%><a href=?clan='.$clan['clan_id'].'&sort=2>Уровень</a></td><td align=center>Звание<td align=center>Рейтинг</td></tr>';

	$cl=myquery("(select game_users.user_id,game_users.name,game_users.race,game_users.clevel,game_users.clan_id,game_users_data.clan_rating,game_users_data.clan_zvanie,game_har.name AS race_name from game_users,game_users_data,game_har where game_users.clan_id='".$clan['clan_id']."' and game_users.clan_id!='0' and game_users.user_id<>'". $clan['glava']."' and game_users.user_id<>'". $clan['zam1']."' and game_users.user_id<>'". $clan['zam2']."' and game_users.user_id<>'". $clan['zam3']."' AND game_users_data.user_id=game_users.user_id AND game_har.id=game_users.race) UNION (select game_users_archive.user_id,game_users_archive.name,game_users_archive.race,game_users_archive.clevel,game_users_archive.clan_id,game_users_data.clan_rating,game_users_data.clan_zvanie,game_har.name AS race_name from game_users_archive,game_users_data,game_har where game_users_archive.clan_id='".$clan['clan_id']."' and game_users_archive.clan_id!='0' and game_users_archive.user_id<>'". $clan['glava']."' and game_users_archive.user_id<>'". $clan['zam1']."' and game_users_archive.user_id<>'". $clan['zam2']."' and game_users_archive.user_id<>'". $clan['zam3']."' AND game_users_data.user_id=game_users_archive.user_id AND game_har.id=game_users_archive.race) order by $sort_order");
	if ($cl!=false and mysql_num_rows($cl)>0)
	{
		while ($users = mysql_fetch_array($cl))
		{
			echo'<tr><td><a href="http://'.DOMAIN.'/view/?userid='.$users["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>'.$users['name'].'</td><td>('.$users["race_name"].' '.$users['clevel'].' уровня)</td>';
			echo '<td align=center>';
			if ($users["clan_zvanie"]!='')
				echo '<b>'.$users["clan_zvanie"].'</b>';
			echo '</td><td align=center>';
			if ($users["clan_rating"]!='0')
				echo '<b>'.$users["clan_rating"].'</b>';
			echo '</td></tr>';
		}
	}
	echo '</table>
	</td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>