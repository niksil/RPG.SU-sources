<?php
function combat_setFunc($user_id,$func_id,$combat_id,$hod=0)
{
	myquery("INSERT combat_users_state (user_id,state,combat_id,hod) VALUES ('$user_id','".$func_id."','".$combat_id."',$hod) ON DUPLICATE KEY UPDATE state='".$func_id."',combat_id='".$combat_id."',hod=$hod");
	return 1;
}

function combat_getFunc($user_id,&$combat_id=0)
{
	$sel_rid = myquery("SELECT state,combat_id FROM combat_users_state WHERE user_id = '".$user_id."' ");
	if(mysql_num_rows($sel_rid)==0)
	{
		return 0; 
	}
	else
	{
		$arr_rid = mysql_fetch_array($sel_rid);
		$combat_id = $arr_rid['combat_id'];
		return $arr_rid['state'];
	}
}

function combat_delFunc($user_id)
{
	myquery("DELETE FROM combat_users_state WHERE user_id='$user_id'");
	return 1;
}

function ClearCombat($combat_id)
{
	myquery("DELETE FROM combat_users WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat_actions WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat_lose_user WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat_users_exp WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat_locked WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat_users_state WHERE combat_id=$combat_id AND state NOT IN (3,4,7,8,9)");
}

function ClearCombatUser($user_id)
{
	myquery("DELETE FROM combat_users WHERE user_id=$user_id");
	myquery("DELETE FROM combat_actions WHERE user_id=$user_id");
	myquery("DELETE FROM combat_users_exp WHERE user_id=$user_id");
}
	
//Функция проверяет есть ли среди игроков-участников боя активные, если нет - то всем игрокам ставится вылет по тайму и бой удаляется
function check_boy($combat_id)
{
	global $user_id;
	if (!isset($user_id)) $user_id=0;
	$kol_out = mysql_result(myquery("SELECT COUNT(*) FROM combat_users WHERE combat_id=$combat_id AND npc=0 AND time_last_active<".(time()-270).""),0,0);
	$kol = mysql_result(myquery("SELECT COUNT(*) FROM combat_users WHERE combat_id=$combat_id AND npc=0"),0,0);
	if ($kol==0) return 0;
	if ($kol==$kol_out)
	{
		//все игроки боя вылетели по тайму, бой ни у кого не грузится
		//ставим всем игрокам state=8 и очищаем бой
		$Combat = new Combat($combat_id,$user_id);
		foreach ($Combat->all AS $key=>$value)
		{
			if ($Combat->all[$key]['npc']==0)
			{
				$Combat->user_out($key);
			}   
		}
		$Combat->clear_combat();
		return 1;
	}
	return 0;
}
?>