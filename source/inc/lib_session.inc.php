<?php
require_once("engine.inc.php");
if (!empty($_SESSION['user_id']))
{
	unset($_SESSION['user_id']);
}

function destroy_all($par="",$loc="index.php")
{
	if ($loc=="index.php") $loc="http://".DOMAIN."/index.php?param=$par";
	mysql_close();
	session_unset();
	unset($_SESSION);
	setcookie("rpgsu_login",0,time()-84000000,"/");
	setcookie("rpgsu_pass",0,time()-84000000,"/");
	setcookie("rpgsu_sess",0,time()-84000000,"/");
	setcookie("rpgsu_admin",0,time()-84000000,"/");
	setLocation($loc);
	die();
}

if (!isset($_COOKIE['rpgsu_sess']))
{
	destroy_all("1");
}
session_id($_COOKIE['rpgsu_sess']);
session_start();

if (isset($_SESSION['user_id']))
{
	$user_id = $_SESSION['user_id'];
	$user_time_old = $_SESSION['user_time'];
	$user_time_min = time() - PHPRPG_SESSION_EXPIRY;
	$user_time = time();
	if ((isset($_COOKIE['rpgsu_admin']) AND $_COOKIE['rpgsu_admin']==1) OR !isset($_COOKIE['rpgsu_admin']))
	{
		$res = myquery("SELECT hide,privat FROM game_admins WHERE user_id=".$user_id."");
		setcookie("rpgsu_admin", mysql_num_rows($res),0,"/");
		if (mysql_num_rows($res)>0)
		{
			$user_time_min-=25*60;
			$adm=mysql_fetch_array($res);
			if ($adm['hide']!=0)
			{
				if ($adm['privat']!=1)
				{
					$user_time = time();
				}
				else
				{
					$user_time = time()-300;
				}
			}
		}
	}
	if ($user_time_old < $user_time_min)
	{
		destroy_all("2");
	}

	$user_host = HostIdentify();
	$old_host = $_SESSION['user_host_ip'];
	if ($old_host!=$user_host AND DOMAIN!=TEST_SERVER)
	{
		destroy_all("3");
	}

	myquery("UPDATE game_users_active SET last_active=$user_time WHERE user_id='$user_id'");
 
	$_SESSION['user_time']=$user_time;

	//$user_host=mt_rand(0,time());
	//if ($user_id==9665) $user_host = 0;
	
	// Теперь игрок должен быть в листинге активных
	list($IP,$pol,$nav_geksa_view) = mysql_fetch_array(myquery("SELECT work_IP,sex,geksa_view FROM game_users_data WHERE user_id=$user_id"));
	$nav_geksa_view++;

	$result = myquery("SELECT view_active_users.*, game_users_map.map_name, game_users_map.map_xpos,game_users_map.map_ypos  FROM view_active_users,game_users_map WHERE game_users_map.user_id=view_active_users.user_id AND view_active_users.user_id=$user_id");
	if($result==false OR mysql_num_rows($result)==0)
	{
		$result = myquery("SELECT game_users.*, game_users_map.map_name, game_users_map.map_xpos,game_users_map.map_ypos,game_users_active_delay.delay,game_users_active_delay.delay_reason  FROM game_users,game_users_map,game_users_active_delay WHERE game_users.user_id=game_users_active_delay.user_id AND game_users_map.user_id=game_users.user_id AND game_users.user_id=$user_id");
	}
	$char = mysql_fetch_assoc($result);
	if (isset($_GET['teleport_map_name']) AND isset($_GET['teleport_map_xpos']) AND isset($_GET['teleport_map_ypos']) AND ($char['clan_id']==1 OR $user_id==1 OR DOMAIN==TEST_SERVER))
	{
		$up = myquery("UPDATE game_users_map SET map_name='".$_GET['teleport_map_name']."', map_xpos='".$_GET['teleport_map_xpos']."', map_ypos='".$_GET['teleport_map_ypos']."' WHERE user_id='".$char['user_id']."'");
		$char['map_name']=$_GET['teleport_map_name'];
		$char['map_xpos']=$_GET['teleport_map_xpos'];
		$char['map_ypos']=$_GET['teleport_map_ypos'];
	}
	$char['func_id']=getFunc($user_id);	
	$char['last_active']=$user_time;
	
	if ($char['HP']==0)
	{
		$char['HP']==1;
		myquery("UPDATE game_users SET HP=1 WHERE user_id=$user_id");
	}

	if (isset($_COOKIE['rpgsu_login']) AND ($char['user_name']!=$_COOKIE['rpgsu_login']))
	{
	   destroy_all("4");
	}
	elseif (isset($_COOKIE['rpgsu_login']) AND (md5($char['user_pass'])!=$_COOKIE['rpgsu_pass']))
	{
		destroy_all("5");
	}
	else
	{
		setcookie("rpgsu_login", $char['user_name'],0,"/");
		setcookie("rpgsu_pass",md5($char['user_pass']),0,"/");
	}
    }
else
{
	destroy_all("6");
}

if (!isset($char))
{
	destroy_all("7");
}
//выкинем забаненых
$userban=myquery("select type,time,za from game_ban where user_id=$user_id and (time>".time()." OR time=-1)");
if (mysql_num_rows($userban))
{
	$userban=mysql_fetch_array($userban);
	if ($userban['type']<2)
	{
		destroy_all("8","index.php?error=ban&id=$user_id&time=".($userban['time']-time())."");
	}
}

if (close_game==1 AND $char['clan_id']!=1 AND $char['name']!='mrHawk')
{
	mysql_close();
	session_unset();
	unset($_SESSION);
	setcookie("rpgsu_login",0,time()-84000000,"/");
	setcookie("rpgsu_pass",0,time()-84000000,"/");
	setcookie("rpgsu_sess",0,time()-84000000,"/");
	setcookie("rpgsu_admin",0,time()-84000000,"/");
	die('<center><h3>Ведутся работы на сервере. В скором времени работоспособность будет возобновлена</h3></center>'); 
}

// seed with microseconds
function make_seed()
{
	global $user_id;
	return hexdec(substr(md5(microtime()+$user_id*1000), -8)) & 0x7fffffff;
}
mt_srand(make_seed());

if ((($_SERVER['PHP_SELF']=="/act.php")AND((!isset($_GET['func']))OR($_GET['func']!='boy')))OR(($_SERVER['PHP_SELF']=="/lib/town.php")AND((!isset($_GET['option']))OR($_GET['option']!=12))))
{
	$check_turnir = myquery("SELECT * FROM game_turnir_users WHERE user_id=$user_id");
	if (mysql_num_rows($check_turnir)>0)
	{
		$tur = mysql_fetch_array($check_turnir);
		if ($tur['from_boy']==0)
		{
			setLocation('http://'.DOMAIN.'/lib/town.php?option=12'); 
		}
		else
		{
			setLocation('http://'.DOMAIN.'/act.php?func=boy');
		}
		die();
	}
}
// Видимо тут уже игрок в игре
if (defined("MODULE_ID"))
{
	$result_func=checkFunc($user_id,MODULE_ID);
	if (!defined("NO_FUNC_CHECK"))
	{
		if($result_func==0)
		{
			$last_str=getRedirectFunc($user_id);
			setLocation('http://'.DOMAIN.'/'.$last_str.'');
			die();
		}
	}
}

$map_name=$char['map_name'];
$filelist = ",/combat.php,/arcomage.php,/chat/chat_online.php,/chat/chat_ajax.php,/combat/ajax.php,/chat/chat.php,/chat/chat_update.php,/admin.php,/lib/map_editor.php,/main.php,/lib/town.php,";

if (strpos($filelist,PHP_SELF)==false)
{
	//сначала проверяем на автонападение ботов
	if ($char['func_id']!=func_craft AND $char['func_id']!=func_combat AND $char['func_id']!=func_arcomage AND (close_combat!=1 OR $user_id==1) AND $char['hide']==0)
	{
		$result = myquery("SELECT game_npc.id FROM game_npc,game_npc_template WHERE game_npc.npc_id=game_npc_template.npc_id AND game_npc.map_name =".$char['map_name']." AND game_npc.xpos =".$char['map_xpos']." AND game_npc.ypos =".$char['map_ypos']." AND game_npc_template.agressive IN ('2','1') AND game_npc.time_kill+game_npc_template.respawn<UNIX_TIMESTAMP() AND (game_npc.for_user_id=0 OR game_npc.for_user_id=$user_id) ORDER BY game_npc.HP");
		if (mysql_num_rows($result) > 0)
		{
			while ($npc = mysql_fetch_array($result))
			{
				$Npc=new Npc($npc['id']);
				$Npc->check_aggro($char);
			}
		}
	}
}
?>