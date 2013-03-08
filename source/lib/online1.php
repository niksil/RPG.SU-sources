<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (isset($teleport_map_name) AND isset($teleport_map_xpos) AND isset($teleport_map_ypos) AND ($char['clan_id']==1 OR $user_id==9665))
{
    $up = myquery("UPDATE game_users_map SET map_name='".$teleport_map_name."', map_xpos='".$teleport_map_xpos."', map_ypos='".$teleport_map_ypos."' WHERE user_id='".$char['user_id']."'");
}

$up=myquery("update game_users set delay_reason=24 WHERE user_id='".$char['user_id']."'");
$current_time = time();
$online_range = $current_time - 300;
$result = mysql_result(myquery("SELECT COUNT(*) FROM game_users_active WHERE last_active>$online_range"),0,0);
$online_number = $result;

$npc_online = time();
$result = myquery("SELECT * FROM game_npc WHERE npc_time+respawn<$npc_online AND prizrak='0'");
$online_bot=mysql_num_rows($result);

OpenTable('title');
echo '<div align="right"><img src="http://'.IMG_DOMAIN.'/nav/online.gif" align=right><font face="Verdana" size="2" color="#f3f3f3"><b>Игроков в игре: </font><font face="Verdana" size="2" color=ff0000>' . $online_number . '</b></font>
<font face="Verdana" size="2" color="#f3f3f3"><b>Ботов в игре: </font><font face="Verdana" size="2" color=ff0000>' . $online_bot . '</b></font></div>';
echo '<table cellpadding="15" cellspacing="0" border="0"><tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td valign="top">';

list($map_name) = mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id='".$char['map_name']."'"));

$qq=myquery("select DISTINCT map_name from game_users_map where user_id in (SELECT user_id FROM game_users_active WHERE last_active>$online_range) order by map_name DESC");
while($h=mysql_fetch_array($qq))
{
	$map = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id=".$h['map_name'].""),0,0);
	$map_id = @mysql_result(@myquery("SELECT id FROM game_maps WHERE id=".$h['map_name'].""),0,0);
	echo '<a href=# onClick=\'expand( "d'.$map_id.'", "d'.$map_id.'" ); load( "d'.$map_id.'", "funct.php?map='.$map_id.'" );\'><li><b>'.$map.'</b></li></a><br>';
	echo '<div id="d'.$map_id.'" '; if ($map_name!=$map) echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
	//echo '<div id="d'.$map_id.'" style=\'display: none;\'><i>Загрузка</i></div>';
}
echo '</td></tr></table>';
OpenTable('close');

if ($char['clan_id']==1)
{
    $qqq=myquery("select user_id,name,clan_id,race,boy,clevel,MS_VOR,delay_reason,func,HP from game_users where user_id in (SELECT user_id FROM game_users_active WHERE last_active>($online_range-300)) and hide=1 ORDER BY name ASC");
	if (mysql_num_rows($qqq))
	{
		echo'<b>Стража, находящаяся в &quot;невидимках&quot;</b><br><br><br>';
		OpenTable('title');
	    while($player=mysql_fetch_array($qqq))
	    {
			list($player_map_name,$player_map_xpos,$player_map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='".$player['user_id']."'"));
			$player['map_name']=$player_map_name;
			$player['map_xpos']=$player_map_xpos;
			$player['map_ypos']=$player_map_ypos;
	        if ($char['clan_id']==1 OR $user_id==9665)
	        {
				echo '<a href="?func=online&teleport_map_name='.$player["map_name"].'&teleport_map_xpos='.$player["map_xpos"].'&teleport_map_ypos='.$player["map_ypos"].'"><img src="http://'.IMG_DOMAIN.'/nav/show.gif" border="0"></a>';
	        }
	        echo '<a href="http://'.DOMAIN.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>&nbsp;';
			echo'<a href="?func=pm&pm=write&new&komu='.$player["name"].'" title="Написать личное сообщение"><img src="http://'.IMG_DOMAIN.'/pm/new_pm.gif" border="0" alt="Написать личное сообщение"></a>&nbsp;';
	        if ($player['clan_id']!='0') echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$player['clan_id'].'.gif">&nbsp;';
	        $type='';
	        echo '' . $player['name'] . ' (' . mysql_result(myquery("SELECT name FROM game_har WHERE id=".$player['race'].""),0,0) . ' ' . $player['clevel'] . ' уровня)   ';
	        $map_name=mysql_result(myquery("SELECT name FROM game_maps WHERE id='".$player['map_name']."'"),0,0);
            echo $map_name.' x-' . $player['map_xpos'] . ' y-' . $player['map_ypos'] . '';
	        if ($player['delay_reason']==2 or $player['delay_reason']==5 or $player['func']=='boy' or $player['func']=='wait') echo' <font size=1 color=ff0000 face=verdana><b>'.get_delay_reason($player['delay_reason']).'</b></font>';
	        echo'<br>';
	    }
		OpenTable('close');
	}
}

echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo '</td></tr></table>';
?>

<script language="JavaScript" type="text/javascript">
p = new Array( );
function ge( a ) { if( document.all ) return document.all[a]; else return document.getElementById( a ); }
function load( pp, str ) { if( p[pp] ) return; p[pp] = 1; parent.game.xssa.location.href = str; }
function expand( a, b ) { if( ge( b ).style ) dsp = ge( b ).style.display; else dsp = ge( b ).display; if( dsp == 'none' ) { if( ge( b ).style ) dsp = ge( b ).style.display = ''; else dsp = ge( b ).display = ''; }
else { if( ge( b ).style ) ge( b ).style.display = 'none'; else ge( b ).display = 'none';}}
</script>

<?
echo'<iframe name="xssa" src="funct.php?map='.$char['map_name'].'" height="0" width="0"></iframe>';

if (function_exists("save_debug")) save_debug(); 

?>