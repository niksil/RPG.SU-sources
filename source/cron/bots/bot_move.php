<?php
error_reporting(E_ALL);

R_gentime();
function R_microtime()
{
		list($usec, $sec) = explode(' ', microtime());
		return (double) $usec + (double) $sec;
};
function R_gentime()
{
		global $R_gentime;
		if (isset ($R_gentime))
		return number_format (R_microtime() - $R_gentime, 3);
		$R_gentime = R_microtime();  
};


require_once("../../inc/engine.inc.php");
require_once("../../inc/xray.inc.php");

$max_round=2;
	$xmax=1;
	$ymax=1;
	$curtime = time();
	$debug=1;
	print "Time: ".$curtime."\n";
	/* Magnet-moving; agressive=2 */

	$npc_move=mysql_query("SELECT game_npc.id as npcid,game_npc_template.agressive,sign(game_users_map.map_xpos-game_npc.xpos) as sigx,sign(game_users_map.map_ypos-game_npc.ypos) as sigy,game_users_map.user_id,
	min(abs(game_npc.xpos-game_users_map.map_xpos)+abs(game_npc.ypos-game_users_map.map_ypos)) as vect,
	game_users_map.map_xpos,game_users_map.map_ypos,game_npc.xpos,game_npc.ypos
	FROM game_npc,game_users_map,view_active_users,game_npc_template
	WHERE  game_users_map.map_name=game_npc.map_name AND game_users_map.user_id=view_active_users.user_id
	AND abs(game_npc.xpos-game_users_map.map_xpos)+abs(game_npc.ypos-game_users_map.map_ypos) <".$max_round."
	AND game_npc.id NOT IN (SELECT user_id FROM combat_users WHERE npc>0)
	AND game_npc_template.agressive='2'
	AND (game_npc.time_kill+game_npc_template.respawn)<UNIX_TIMESTAMP()
	AND game_npc.prizrak=0
	AND game_npc.npc_quest_id=0
	AND game_npc.for_user_id=0
	AND game_npc.stay=0
	AND game_npc_template.canmove=1
	AND game_npc_template.npc_id=game_npc.npc_id
	GROUP BY game_npc.id");

	while($npc_goto=mysql_fetch_array($npc_move))
	{
			if($debug) print( "NPC (npc_id:".$npc_goto['npcid'].", agr:".$npc_goto['agressive'].",x: ".$npc_goto['xpos']." + ".$npc_goto['sigx']." , y: ".$npc_goto['ypos']." + ".$npc_goto['sigy']." ) -> user_id ( ".$npc_goto['user_id'].", x:".$npc_goto['map_xpos']." , y: ".$npc_goto['map_ypos']." )\n");
			mysql_query("UPDATE game_npc SET xpos=xpos+$xmax*".$npc_goto['sigx']." ,ypos=ypos+$ymax*".$npc_goto['sigy']."  WHERE id=".$npc_goto['npcid']." ");
			if($debug) print("UPDATE game_npc SET xpos=xpos+$xmax*".$npc_goto['sigx']." ,ypos=npc_ypos+$ymax*".$npc_goto['sigy']."  WHERE id=".$npc_goto['npcid']." ");
			if($debug) print "\n";
	}

	/* Magnet-moving; agressive=1 */

	$npc_move=mysql_query("SELECT game_npc.id as npcid,game_npc_template.agressive,sign(game_users_map.map_xpos-game_npc.xpos) as sigx,sign(game_users_map.map_ypos-game_npc.ypos) as sigy,game_users_map.user_id,
	min(abs(game_npc.xpos-game_users_map.map_xpos)+abs(game_npc.ypos-game_users_map.map_ypos)) as vect,
	game_users_map.map_xpos,game_users_map.map_ypos,game_npc.xpos,game_npc.ypos
	FROM game_npc,game_users_map,view_active_users,game_npc_template
	WHERE game_users_map.map_name=game_npc.map_name AND game_users_map.user_id=view_active_users.user_id
	AND abs(game_npc.xpos-game_users_map.map_xpos)+abs(game_npc.ypos-game_users_map.map_ypos) <".$max_round."
	AND (game_npc_template.npc_level + game_npc_template.agressive_level) < view_active_users.clevel
	AND game_npc.id NOT IN (SELECT user_id FROM combat_users WHERE npc>0)
	AND game_npc_template.agressive='1'
	AND (game_npc.time_kill+game_npc_template.respawn)<UNIX_TIMESTAMP()
	AND game_npc.prizrak=0
	AND game_npc.npc_quest_id=0
	AND game_npc.for_user_id=0
	AND game_npc.stay=0
	AND game_npc_template.canmove=1
	AND game_npc_template.npc_id=game_npc.npc_id
	GROUP BY game_npc.id");

	while($npc_goto=mysql_fetch_array($npc_move))
	{
			if($debug) print( "NPC (npc_id:".$npc_goto['npcid'].", agr:".$npc_goto['agressive'].",x: ".$npc_goto['xpos']." + ".$npc_goto['sigx']." , y: ".$npc_goto['ypos']." + ".$npc_goto['sigy']." ) -> user_id ( ".$npc_goto['user_id'].", x:".$npc_goto['map_xpos']." , y: ".$npc_goto['map_ypos']." )\n");
			mysql_query("UPDATE game_npc SET xpos=xpos+$xmax*".$npc_goto['sigx']." ,ypos=ypos+$ymax*".$npc_goto['sigy']."  WHERE id=".$npc_goto['npcid']." ");
			if($debug) print("UPDATE game_npc SET xpos=xpos+$xmax*".$npc_goto['sigx']." ,ypos=ypos+$ymax*".$npc_goto['sigy']."  WHERE id=".$npc_goto['npcid']." ");
			if($debug) print "\n";
	}


	/* Random-moving; agressive=1 or agressive=0 */

	$npc_move=mysql_query("SELECT game_npc.npc_id,game_npc.id as npcid,game_npc.xpos,game_npc.ypos,max(game_map.xpos) as maxxpos,max(game_map.ypos) as maxypos
	FROM game_npc,game_map,game_npc_template
	WHERE game_npc.npc_id NOT IN (SELECT user_id FROM combat_users WHERE npc>0)
	AND game_map.name=game_npc.map_name
	AND ( game_npc_template.agressive='1' OR game_npc_template.agressive='0' )
	AND (game_npc.time_kill+game_npc_template.respawn)<UNIX_TIMESTAMP()
	AND game_npc.prizrak=0
	AND game_npc.npc_quest_id=0
	AND game_npc.for_user_id=0
	AND game_npc.stay=0
	AND game_npc_template.canmove=1
	AND game_npc_template.npc_id=game_npc.npc_id
	GROUP BY game_npc.id");


	while($npc_goto=mysql_fetch_array($npc_move))
	{
	$x_rand=mt_rand(-1,1);
	$y_rand=mt_rand(-1,1);
	$xnew=$npc_goto['xpos']+$xmax*$x_rand;
	$ynew=$npc_goto['ypos']+$ymax*$y_rand;
	if($xnew<0) $xnew=0;
	if($ynew<0) $ynew=0;
	if($xnew>$npc_goto['maxxpos']) $xnew=$npc_goto['maxxpos'];
	if($ynew>$npc_goto['maxypos']) $ynew=$npc_goto['maxypos'];

			if($debug) print( "NPC (npc_id:".$npc_goto['npcid'].",x: ".$xnew." , y: ".$ynew." )\n");
			mysql_query("UPDATE game_npc SET xpos=".$xnew." ,ypos=".$ynew."  WHERE id=".$npc_goto['npcid']." ");
			if($debug) print("UPDATE game_npc SET xpos=".$xnew." ,ypos=".$ynew."  WHERE id=".$npc_goto['npcid']." ");
			if($debug) print "\n";
	}


			if($debug) print "\n\n Working Time: ".R_gentime()."\n";
	 
		
?>