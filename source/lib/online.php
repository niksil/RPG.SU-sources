<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}
if (isset($teleport_map_name) AND isset($teleport_map_xpos) AND isset($teleport_map_ypos) AND ($char['clan_id']==1 OR $user_id==9665))
{
	$up = myquery("UPDATE game_users_map SET map_name=".$teleport_map_name.", map_xpos=".$teleport_map_xpos.", map_ypos=".$teleport_map_ypos." WHERE user_id='".$char['user_id']."'");
}

set_delay_reason_id($char['user_id'],24);
$current_time = time();
$online_range = $current_time - 300;
$result = mysql_result(myquery("SELECT COUNT(*) FROM view_active_users"),0,0);
$online_number = $result;

$result = myquery("SELECT COUNT( * )
FROM game_npc, game_npc_template
WHERE game_npc.time_kill + game_npc_template.respawn < unix_timestamp( )
AND prizrak =0 
AND game_npc.npc_id=game_npc_template.npc_id");
$online_bot=mysql_result($result,0,0);

echo '
<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top">';
OpenTable('title');

echo '<div align="right" style="position:relative;">';
if ($char['map_name']==18)
{
	echo '<img style="z-index:100;position:absolute;right:0%;top:25px;" src="http://'.IMG_DOMAIN.'/map/sz_gerb_100.gif" height="100" weight="100" align=right>&nbsp;';
}
elseif ($char['map_name']==5)
{
	echo '<img style="z-index:100;position:absolute;right:0%;top:25px;" src="http://'.IMG_DOMAIN.'/map/bel_gerb_100.gif" height="100" weight="100" align=right>&nbsp;';
}
else
{
	echo '<img src="http://'.IMG_DOMAIN.'/nav/online.gif" align=right>&nbsp;';
}
echo '<font face="Verdana" size="2" color="#f3f3f3"><b>Игроков в игре: </font><font face="Verdana" size="2" color=ff0000>' . $online_number . '</b></font>
<font face="Verdana" size="2" color="#f3f3f3"><b>Ботов в игре: </font><font face="Verdana" size="2" color=ff0000>' . $online_bot . '</b></font></div>';

echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 100; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';

echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top">';

list($map_name) = mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id='".$char['map_name']."'"));
$qq=myquery("SELECT DISTINCT game_maps.name,game_maps.id FROM game_users_map,view_active_users,game_maps WHERE game_maps.id=game_users_map.map_name AND game_users_map.user_id=view_active_users.user_id ORDER BY BINARY game_maps.name ASC");
while($h=mysql_fetch_array($qq))
{
	echo '<li>';
	$map = $h['name'];
	$map_id = $h['id'];
	if ($map_id==18)
	{
		echo '<img src="http://'.IMG_DOMAIN.'/map/sz_gerb_20.gif" height="20" weight="20">&nbsp;';
	}
	if ($map_id==5)
	{
		echo '<img src="http://'.IMG_DOMAIN.'/map/bel_gerb_20.gif" height="20" weight="20">&nbsp;';
	}
	echo '<a name="map'.$map_id.'" href="#map'.$map_id.'" onClick="expand(\'d'.$map_id.'\',\'d'.$map_id.'\',\'d'.$map_id.'\',\'funct.php?map='.$map_id.'\');"><b>'.$map.'</b></a></li><br>';

	echo '<div id="d'.$map_id.'" '; if ($map_name!=$map) echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
}
echo '</td></tr></table>';
OpenTable('close');

echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo '</td></tr></table>';
?>

<script type="text/javascript">
p = new Array();
function ge(a) 
{ 
	if( document.all ) 
		return document.all[a]; 
	else 
		return document.getElementById( a ); 
}
function load(pp,str) 
{ 
	if( p[pp] ) 
		return; 
	p[pp] = 1; 
	parent.game.xssa.location.href = str; 
 }
function expand(a,b,pp,str) 
{ 
	if( ge( b ).style ) 
		dsp = ge( b ).style.display; 
	else 
		dsp = ge( b ).display; 
	if( dsp == 'none' ) 
	{ 
		if( ge( b ).style ) 
			dsp = ge( b ).style.display = 'block'; 
		else 
			dsp = ge( b ).display = '';
	}
	else 
	{ 
		if( ge( b ).style ) 
			ge( b ).style.display = 'none'; 
		else 
			ge( b ).display = 'none';
	}
	load(pp,str)
}
</script>

<?
echo'<iframe name="xssa" src="http://'.DOMAIN.'/funct.php?map='.$char['map_name'].'" style="width:0px;height:0px;border:none;"></iframe>';

//}
//else
//{
/*?>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100%" height="100%">
<param name="movie" value="utils/map.swf">
<param name="quality" value="high">
<embed src="utils/map.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="100%" height="100%"></embed>
</object>
<?*/
//
//}

if (function_exists("save_debug")) save_debug(); 

?>