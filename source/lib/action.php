<?php
if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (function_exists("start_debug")) start_debug(); 


if (!empty($option))
{
	if ($char['hide']==1) {if (function_exists("save_debug")) save_debug(); exit;}

	switch ($option)
	{
		case 'attack':
		{
			$id=(int)$id;
			$online_range = time()-300;
			$result = myquery("SELECT view_active_users.*,IFNULL(combat_users.combat_id,0) as boy FROM view_active_users LEFT JOIN combat_users ON (view_active_users.user_id=combat_users.user_id) WHERE view_active_users.user_id=$id and view_active_users.user_id<>".$char['user_id']." and view_active_users.user_id IN (SELECT user_id FROM game_users_map WHERE  map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' and map_name='".$char['map_name']."')");
			if (!mysql_num_rows($result)) {if (function_exists("save_debug")) save_debug(); exit;}
			$player = mysql_fetch_array($result);
			
			if (!isset($type)) $type=1;
			if ($type<1 or $type>7) {if (function_exists("save_debug")) save_debug(); exit;}  
			
			$map = mysql_fetch_array(myquery("select * from game_maps where id=".$char['map_name'].""));
			$t = $type;
			$reas = check_attack($char,$player,$t,$map);
			if ($reas==1)
			{
				attack_user($char,$player,$type);
			}
			else
			{
				$loc = "act.php?errror=".urlencode($reas)."";
				setLocation($loc);
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
		}
		break;

		case 'join':
		{
			if (isset($id))
			{
				$id=(int)$id;
				$online_range = time()-300;
				$prov=myquery("SELECT view_active_users.*,IFNULL(combat_users.combat_id,0) as boy FROM view_active_users LEFT JOIN combat_users ON (view_active_users.user_id=combat_users.user_id) where view_active_users.user_id='$id' and view_active_users.user_id IN (SELECT user_id FROM game_users_map WHERE  map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' and map_name='".$char['map_name']."') ");
				if (mysql_num_rows($prov))
				{
					$player=mysql_fetch_array($prov);
					$sel_host = myquery("SELECT host FROM game_users_active WHERE user_id='$user_id'");
					$host = mysql_result($sel_host,0,0);
					$sel_host = myquery("SELECT host_more FROM game_users_active_host WHERE user_id='$user_id'");
					$host_more = mysql_result($sel_host,0,0);
					$last_active2 = mysql_result(myquery("SELECT last_active FROM game_users_active WHERE user_id='$id'"),0,0);
					$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=".$char['map_name'].""));
					if ($player['boy']!=0  AND $last_active2>$online_range)
					{
						list($type) = mysql_fetch_array(myquery("SELECT combat_type FROM combat WHERE combat_id='".$player['boy']."'"));
						if ($type<0 or $type>7) die();
						$error='';
						$select=myquery("select npc from combat_users where combat_id='".$player['boy']."' and npc>0");
						if (mysql_num_rows($select)>0)
						{
							$error='npc';
						}
						$side=0;
						if (!isset($_GET['svitok']) OR $map['dolina']==1 OR $map['id']==map_coliseum)
						{
							$svit = 0;
						}
						else
						{
							$svit=(int)$_GET['svitok'];
						}
						$join=0;
						$alt='';
						$av_svit='';
						$reas = check_join($char,$player,$join,$alt,$av_svit);
						if ($reas==1)
						{
							if ($svit>0)
							{ 
								if (strpos($av_svit,','.$svit.',')!==false)
								{
									join_attack_user($char,$player,$svit);
								}
							}                        
							elseif ($join!=99) 
							{
								join_attack_user($char,$player,0);
							}
						}
						else
						{
							if ($error=='') $error=$alt;
							$loc = "act.php?errror=$error";
							echo '<script>location.replace("'.$loc.'");</script>';
							{if (function_exists("save_debug")) save_debug(); exit;}
						}
					}
					else
					{
						$loc = "act.php?errror=";
						echo '<script>location.replace("'.$loc.'");</script>';
						{if (function_exists("save_debug")) save_debug(); exit;}
					}
				}
			}
		}
		break;

		case 'arcomage':
		{
			$id=(int)$id;
			$result = myquery("SELECT view_active_users.*,game_users_func.func_id 
			FROM view_active_users,game_users_func
			WHERE view_active_users.user_id=$id 
			AND view_active_users.user_id=game_users_func.user_id
			AND view_active_users.user_id<>".$char['user_id']." 
			AND view_active_users.user_id IN (SELECT user_id FROM game_users_map WHERE  map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' and map_name='".$char['map_name']."') ");
			if (!mysql_num_rows($result)) {if (function_exists("save_debug")) save_debug(); exit;}
			$player = mysql_fetch_array($result);
			list($last_active1,$host1) = mysql_fetch_array(myquery("SELECT last_active,host FROM game_users_active WHERE user_id='$user_id'"));
			list($last_active2,$host2) = mysql_fetch_array(myquery("SELECT last_active,host FROM game_users_active WHERE user_id='$id'"));
			if (($host1!=$host2 AND $player['func_id']=='5')OR(DOMAIN==TEST_SERVER))
			{
				arcomage_user($char,$player,0);
			}
			$loc = "act.php?errror=";
			echo '<script>location.replace("'.$loc.'");</script>';
			if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
			{
				show_debug();
			}
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		break;
	}

	if (isset($error))
	{
		$loc = "act.php?errror=$error";
	}
	else
	{
		$loc = "act.php";
	}
	setLocation($loc);
}

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}

if (function_exists("save_debug")) save_debug(); 
?>