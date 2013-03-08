<?
echo'
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<TR><TD width=80><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/1.gif" width=80></TD>
<TD vAlign=top width=18 background="http://'.IMG_DOMAIN.'/nav1/spacer.gif">
<IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/2.gif" width=18></TD>
<TD vAlign=top width=22 background="http://'.IMG_DOMAIN.'/nav1/3.gif">
<IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></TD>
<TD width="100%" background="http://'.IMG_DOMAIN.'/nav1/3.gif" align=center vAlign=center>

<table cellSpacing=0 cellPadding=0 width="100%" border=0><tr>
<td><a href="http://'.DOMAIN.'/act.php?func=main" target="game">Игра</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>
<td><a href="http://'.DOMAIN.'/act.php?func=inv" target="game">Инвентарь</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>
<td><a href="http://'.DOMAIN.'/act.php?func=hero" target="game">Характеристики</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';

$stroitel=$char['stroitel'];  //временно
if ($stroitel>=1)
{
	echo '<td><a href="http://'.DOMAIN.'/act.php?func=main&act=build" target="game">Строительство</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';
}

/*
$map = mysql_result(myquery("SELECT game_maps.name FROM game_maps,game_users_map WHERE game_maps.id = game_users_map.map_name AND game_users_map.user_id=$user_id)"),0,0);
if ($map=='Гильдия новичков')
*/
if ($char['clevel']<5)
{
	echo '<td><a style="font-size:15px;color:#FF0000" href="http://'.DOMAIN.'/act.php?func=help_newbie" target="game">Прочти меня!</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';
}
else
{
	echo '<td><a href="http://'.DOMAIN.'/act.php?func=boy" target="game">Бои</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';
}

echo '<td><a href="http://'.DOMAIN.'/act.php?func=online" target="game">Онлайн</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';

echo '<td><a href="http://'.DOMAIN.'/act.php?func=jurnal" target="game">Журнал</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';

echo'<td><a href="http://'.DOMAIN.'/act.php?func=jaloba" target="game">Письмо админам</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';

$result = myquery("SELECT hide,privat FROM game_admins WHERE user_id=".$user_id."");
if (mysql_num_rows($result))
{
	echo'<td><nobr><a href="http://'.DOMAIN.'/admin.php" target="_blank">Admin</a>';
	list($hide,$adprivat) = mysql_fetch_array($result);
	if ($hide>0)
	{
		if (isset($privat))
		{
			$privat=(int)$privat;
			$adprivat = $privat;
			$up=myquery("update game_admins set privat='$privat' where user_id=".$user_id." limit 1");
			$up=myquery("update game_users set hide='$privat' where user_id=".$user_id." limit 1");
		}
		if (!isset($func)) $func='main';
		if ($adprivat==1)
		{
			echo'&nbsp;<a href="http://'.DOMAIN.'/act.php?func='.$func.'&privat=0"><img src="http://'.IMG_DOMAIN.'/nav/ball_red.jpg" width=14 height=14 border="0" title="выйти из тени"></a>';
		}
		else
		{
			echo'&nbsp;<a href="http://'.DOMAIN.'/act.php?func='.$func.'&privat=1"><img src="http://'.IMG_DOMAIN.'/nav/ball_green.jpg" width=14 height=14 border="0" title="войти в тень"></a>';
		}
	}
	echo'</td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';
}

echo'<td><a href="http://'.DOMAIN.'/logout.php" target="game">Выход</a></td></tr></table>
</TD>
<TD vAlign=top width=22 background="http://'.IMG_DOMAIN.'/nav1/3.gif">
<IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22></TD>
<TD vAlign=top width=18 background="http://'.IMG_DOMAIN.'/nav1/spacer.gif"><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/6.gif" width=18></TD>
<TD width=80><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/7.gif" width=80></TD>
</TR>
</TABLE>';
?>