<?php
/*
$dir = '../../web/';
$dirclass = $dir.'class/';
include($dir.'inc/xray.inc.php');
include($dir.'inc/lib.inc.php');
require_once($dir.'inc/engine.inc.php');

// seed with microseconds
function make_seed()
{
	list($usec, $sec) = explode(' ', microtime());
	return (float) $sec + ((float) $usec * 100000);
}
mt_srand(make_seed());

//Выполним обсчет ходов боев

require_once($dir.'inc/combat/combat.inc.php');

include($dir.'combat/inc/template.inc.php');
$sel = myquery("SELECT combat.* FROM combat,combat_users WHERE combat_users.combat_id=combat.id AND combat_users.npc_id=0 GROUP BY combat_users.combat_id HAVING count(combat_users.user_id)>1");
$curtime = time();
while ($boy = mysql_fetch_array($sel))
{
	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=".$boy['map_name'].""));
	$timeout = 180;
	if ($map['name']=='Арена Хаоса' or $map['dolina']==1)
	{
		$timeout = 240;
	}
	if (domain_name=='localhost')
	{
		$mess = "Обсчет боя №".$boy['id']."";
		myquery("INSERT INTO game_log (message,date,too,fromm) VALUES ('".iconv("Windows-1251","UTF-8//IGNORE",$mess)."',".time().",612,-1)");
	}
	if ($boy['type']==3 AND $boy['hod']<3 AND ($boy['last_hod']-time()+$timeout)>5)
	{
		continue;
	}
	$dont_hod = 0;
	$sel_users = myquery("SELECT * FROM combat_users WHERE npc_id=0 AND combat_id=".$boy['id']."");
	if ($sel_users!=false AND mysql_num_rows($sel_users)>0)
	{
		while ($combat_users=mysql_fetch_array($sel_users))
		{
			if (($curtime-$combat_users['last_active'])>=($timeout+20))
			{
				//вылетел по тайму
				user_out($combat_users['user_id'],0,$map);
			}
			elseif ($combat_users['make_hod']==0)
			{
				//еще не сходил 
				$dont_hod++; 
			}
		}
		if ($dont_hod==0)
		{
			$turncount_flag = 99;
			$npc_id = $boy['npc_id'];
			$decrease = 0.7;
			include($dir.'combat/inc/wait.inc.php');
		}
	}
}
*/
?>