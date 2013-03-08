<?
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '12');
}
else
{
	die();
}
require('../inc/lib_session.inc.php');
require_once('../inc/combat/combat.inc.php');     


if (headers_sent()) die();

if(ob_get_length()) ob_clean();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/plain; charset=utf-8;');

$boy=0;
$sub_func=combat_getFunc($user_id,$boy); 
if ($sub_func==9)
{
	echo 'pass';
	die();
}

if ($sub_func==5 OR $sub_func==6)
{
	if (isset($_GET['call_clan']))
	{
		$call = mysql_result(myquery("SELECT call_clan FROM combat_users WHERE combat_id='$boy' AND user_id='$user_id' ORDER BY call_clan DESC LIMIT 1"),0,0);
		if ($call==0)
		{
			$map = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id=".$char['map_name'].""),0,0);
			$online_range=time()-300;
			$sel = myquery("SELECT view_active_users.user_id,view_active_users.name FROM view_active_users,game_users_func WHERE view_active_users.clan_id='".$char['clan_id']."' AND view_active_users.user_id IN (SELECT user_id FROM game_users_map WHERE map_name='".$char['map_name']."') AND view_active_users.user_id=game_users_func.user_id AND game_users_func.func_id!='1'");
			while ($clans = mysql_fetch_array($sel))
			{
				$name = $clans['user_id'];
				$names = $clans['name'];
				$theme = 'Помощь в бою';
				$post = 'Призываю всех друзей-соклановцев помочь мне в бою. Я нахожусь - '.$map.'  X-'.$char['map_xpos'].'  Y-'.$char['map_ypos'].'';
				$post1 = 'Я нахожусь - '.$map.'  X-'.$char['map_xpos'].'  Y-'.$char['map_ypos'].'';
				$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time) VALUES ('$name', '$user_id', '$theme', '$post', '0','1',".time().")");
				$chat_mess = '<br><center>ПОМОЩЬ В БОЮ ('.$post1.') от игрока <font size=2 color=#000000><b><u>'.$char['name'].'</u></b></font> !&nbsp;&nbsp;&nbsp;&nbsp;</center><br>';
				$say = myquery("insert into game_log (town,fromm,too,message,date,ptype) values ('0',0,'$name','".iconv("Windows-1251","UTF-8//IGNORE",$chat_mess)."','".time()."',1)");
			}
			myquery("UPDATE combat_users SET call_clan='1' WHERE combat_id='$boy' AND user_id='$user_id'");
			echo 'call_clan';
			die();
		}
	}
	
	if (isset($_GET['call_pass']))
	{
		$sel = myquery("SELECT game_users.name FROM combat_users,game_users WHERE combat_users.combat_id='$boy' AND combat_users.user_id=game_users.user_id AND combat_users.pass=1");
		if (mysql_num_rows($sel))
		{
			$ret_str ='<div style="width:100%;text-align:right;font-weight:800;color:#FFFF00;text-decoration:underline;">Отказались от боя:</div>';
			while (list($name) = mysql_fetch_array($sel))
			{
				$ret_str.='<div style="color:#00FFFF;font-size:11px;">'.$name.'</div>';
			}
			echo iconv('windows-1251','utf-8//IGNORE',$ret_str);
		}
		else
		{
			echo 'nopass';
		}
		die();
	}

	//Проверим на автозавершение боя по отказу от боя всех участников
	$count_users = mysql_result(myquery("SELECT COUNT(*) FROM combat_users WHERE HP>0 AND combat_id='$boy'"),0,0);
	$count_pass_users = mysql_result(myquery("SELECT COUNT(*) FROM combat_users WHERE HP>0 AND combat_id='$boy' AND pass=1"),0,0);
	if ($count_users==$count_pass_users)
	{
		$sel = myquery("SELECT user_id FROM combat_users WHERE combat_id=$boy");
		while (list($comb_user_id) = mysql_fetch_array($sel))
		{
			combat_setFunc($comb_user_id,10,$boy);
		}
		echo 'pass';
		die();
	}

	$sel = myquery("SELECT combat_users.name,game_maps.name AS map FROM combat_users,combat,game_maps WHERE combat.combat_id=combat_users.combat_id AND combat.map_name=game_maps.id AND combat_users.combat_id=$boy AND combat_users.join=1");
	$ret_str = '';
	if (mysql_num_rows($sel))
	{
		$ret_str.='<div style="width:100%;text-align:right;font-weight:800;color:#8080FF;text-decoration:underline;">Вступают:</div>';
		while (list($name_new_user,$map) = mysql_fetch_array($sel))
		{
			if ($map=='Арена Хаоса')
			{
				$name_new_user = '******';
			}
			$ret_str.='<div style="color:#80FF80;font-size:11px;">'.$name_new_user.'</div>';
		}
		echo iconv('windows-1251','utf-8//IGNORE',$ret_str);
	}
	else
	{
		echo 'nobody';
	}
	die();
}
?>