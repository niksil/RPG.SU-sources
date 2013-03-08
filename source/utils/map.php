<?php
$dirclass="../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

if (!isset($view)) $view='';
$map = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='Средиземье'"),0,0);

if ($view=='status')
{
	$online_range = time() - 300;
	$result = myquery("SELECT user_id FROM game_users_map WHERE map_name='$map' and user_id in (SELECT user_id FROM view_active_users)");
	$online_number = mysql_num_rows($result);

	$result = myquery("SELECT COUNT( * )
	FROM game_npc, game_npc_template
	WHERE game_npc.time_kill + game_npc_template.respawn < unix_timestamp( )
	AND prizrak =0  AND game_npc.map_name='$map'
	AND game_npc.npc_id=game_npc_template.npc_id and game_npc.view=1");
	$online_bot = mysql_result($result,0,0);

	$result = myquery("SELECT id FROM game_shop WHERE map='$map'");
	$online_shop = mysql_num_rows($result);


	//$result=myquery("select name from game_map where name='$map' and town!='' and to_map_name=''");
	//$online_town = mysql_num_rows($result);

	$result=myquery("select name from game_map where name='$map' and town!='' and to_map_name!=''");
	$online_per = mysql_num_rows($result);

	$result = myquery("SELECT game_users.user_id FROM game_users,game_users_func WHERE game_users.user_id IN (SELECT user_id FROM game_users_map WHERE  map_name='$map') and game_users.user_id=game_users_func.user_id AND game_users_func.func_id='1' and game_users.user_id in (SELECT user_id FROM game_users_active WHERE last_active>$online_range)");
	$online_war = mysql_num_rows($result);

	echo'time='.date('H').'&online='.$online_number.'&bot='.$online_bot.'&shop='.$online_shop.'&town=17&per='.$online_per.'&war='.$online_war.'';
}




if ($view=='users')
{
	$result = myquery("SELECT view_active_users.*,game_har.name AS race_name,game_users_map.map_name,game_users_map.map_xpos,game_users_map.map_ypos FROM view_active_users,game_users_map,game_har WHERE game_har.id=view_active_users.race AND view_active_users.user_id=game_users_map.user_id AND game_users_map.map_name='$map' AND view_active_users.user_id NOT IN (SELECT user_id FROM game_obelisk_users WHERE time_end>".time()." AND type=5) and (view_active_users.MS_VOR<5 OR view_active_users.delay_reason IN (2,3,11,12,14,15,16,17,18))");
	$online_number = mysql_num_rows($result);
	echo'num='.$online_number.'&';
	$i=1;
	while($char=mysql_fetch_array($result))
	{
		echo'name'.$i.'='.$char['name'].'&hp'.$i.'='.$char['HP'].'&hp_max'.$i.'='.$char['HP_MAX'].'&mp'.$i.'='.$char['MP'].'&mp_max'.$i.'='.$char['MP_MAX'].'&stm'.$i.'='.$char['STM'].'&stm_max'.$i.'='.$char['STM_MAX'].'&str'.$i.'='.$char['STR'].'&ntl'.$i.'='.$char['NTL'].'&vit'.$i.'='.$char['VIT'].'&x'.$i.'='.$char['map_xpos'].'&y'.$i.'='.$char['map_ypos'].'&race'.$i.'='.$char['race_name'].'&clevel'.$i.'='.$char['clevel'].'&vsadnik'.$i.'='.$char['vsadnik'].'&clan'.$i.'='.$char['clan_id'].'&';
		$i++;
	}
}

if ($view=='bots')
{
	$result = myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.map_name='$map' and (game_npc.time_kill+game_npc_template.respawn)<unix_timestamp() and game_npc.view=1 and game_npc_template.npc_exp_max<=200 order by game_npc.xpos ASC, game_npc.ypos asc");
	$online_number = mysql_num_rows($result);
	echo'num='.$online_number.'&';
	$i=1;
	while($char=mysql_fetch_array($result))
	{
		echo'name'.$i.'='.$char['npc_name'].'&hp'.$i.'='.$char['HP'].'&hp_max'.$i.'='.$char['npc_max_hp'].'&str'.$i.'='.$char['npc_str'].'&exp'.$i.'='.$char['npc_exp_max'].'&x'.$i.'='.$char['xpos'].'&y'.$i.'='.$char['ypos'].'&race'.$i.'='.$char['npc_race'].'&';
		$i++;
	}
}




if ($view=='shops')
{
	$result = myquery("SELECT * FROM game_shop WHERE map='$map' AND view=1");
	$online_number = mysql_num_rows($result);
	echo'num='.$online_number.'&';
	$i=1;
	while($char=mysql_fetch_array($result))
	{
		echo'name'.$i.'='.$char['name'].'&text'.$i.'='.$char['text'].'&x'.$i.'='.$char['pos_x'].'&y'.$i.'='.$char['pos_y'].'&prod'.$i.'='.$char['prod'].'&remont'.$i.'='.$char['remont'].'&ident'.$i.'='.$char['ident'].'&shlem'.$i.'='.$char['shlem'].'&oruj'.$i.'='.$char['oruj'].'&dosp'.$i.'='.$char['dosp'].'&shit'.$i.'='.$char['shit'].'&pojas'.$i.'='.$char['pojas'].'&mag'.$i.'='.$char['mag'].'&ring'.$i.'='.$char['ring'].'&artef'.$i.'='.$char['artef'].'&';
		$i++;
	}
}


if ($view=='towns')
{
	$result=myquery("select town, xpos, ypos from game_map where name='$map' and town!=0 and to_map_name=0 and to_map_xpos=0 and to_map_ypos=0");
	$online_number = mysql_num_rows($result);
	$i=1;
	$ii=1;
	while($char=mysql_fetch_array($result))
	{
	$tw=$char['town'];
	$sel=myquery("select * from game_gorod where town='$tw' and view='1'");
	$gor=mysql_fetch_array($sel);

	if ($gor['rustown']!='')
		{
		echo'name'.$ii.'='.$gor['rustown'].'&x'.$ii.'='.$char['xpos'].'&y'.$ii.'='.$char['ypos'].'&';
		$ii++;
		}
	$i++;
	}
	echo'num='.$ii.'&';
}


if ($view=='per')
{
	$result=myquery("select town, xpos, ypos from game_map where name='$map' and town!=0 and to_map_name!=0");
	$online_number = mysql_num_rows($result);
	echo'num='.$online_number.'&';
	$i=1;
	while($char=mysql_fetch_array($result))
	{
		$tw=$char['town'];
		$sel=myquery("select * from game_obj where town='$tw'");
		$gor=mysql_fetch_array($sel);
		if ($gor['view']==1)
		{
			echo'name'.$i.'='.$gor['name'].'&x'.$i.'='.$char['xpos'].'&y'.$i.'='.$char['ypos'].'&';
			$i++;
		}
	}
}

if ($view=='wars')
{
	$online_range = time() - 300;
	$result = myquery("SELECT view_active_users.name,game_users_map.map_xpos,game_users_map.map_ypos FROM view_active_users,game_users_func,game_users_map WHERE view_active_users.user_id = game_users_map.user_id AND game_users_map.map_name='$map' and view_active_users.user_id=game_users_func.user_id AND game_users_func.func_id='1'");
	if ($result!=false AND mysql_num_rows($result)>0)
	{
		$online_number = mysql_num_rows($result);
		echo'num='.$online_number.'&';
		$i=1;
		while($char=mysql_fetch_array($result))
		{
			echo'name'.$i.'=Битва&x'.$i.'='.$char['map_xpos'].'&y'.$i.'='.$char['map_ypos'].'&user_name'.$i.'='.$char['name'].'&';
			$i++;
		}
	}
}
?>