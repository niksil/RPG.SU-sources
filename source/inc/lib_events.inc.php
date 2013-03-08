<?php


error_reporting (E_ALL);

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
    setLocation('index.php');
}
else
{


$result_events = myquery("SELECT task, requested, due FROM game_events WHERE (assigned_id = '0' AND due < " . time() . ") ORDER BY priority, requested LIMIT 2");

while ($event=mysql_fetch_array($result_events))
{

switch ($event['task'])
{
    case 'clear_chat':
        $online_range = time() - 300;
        $result_delete = myquery("DELETE FROM game_chat WHERE post_time < $online_range");
	$result_delete = myquery("DELETE FROM game_battles WHERE post_time < $online_range");
        $next_update = time() + 60;
        $result_update = myquery("UPDATE game_events SET requested = '" . time() . "', due = '" . $next_update . "' WHERE task = '" . $event['task'] . "' LIMIT 1");
    break;

    case 'optimize_chat':
        $result = myquery("OPTIMIZE TABLE game_chat");
        $next_update = time() + (60 * 20);
        $result_update = myquery("UPDATE game_events SET requested = '" . time() . "', due = '" . $next_update . "' WHERE task = '" . $event['task'] . "' LIMIT 1");
    break;


    case 'stamina_recovery':
        $event_cycles = round((time() - $event['due']) / (60 * 5)) + 1;
		$result_stamina_up = myquery("UPDATE game_users SET STM = CEILING(STM + 8 * DEX / 3) * $event_cycles WHERE (STM/STM_MAX) > 0.6 AND STM != STM_MAX and func!='boy' and func!='wait' and func!='boy_npc' and func!='wait_npc'");
        $result_stamina_up = myquery("UPDATE game_users SET STM = CEILING(STM + 7 * DEX / 3) * $event_cycles WHERE (STM/STM_MAX) > 0.3 AND (STM/STM_MAX) <= 0.6 AND STM != STM_MAX and func!='boy' and func!='wait' and func!='boy_npc' and func!='wait_npc'");
        $result_stamina_up = myquery("UPDATE game_users SET STM = CEILING(STM + 6 * DEX / 3) * $event_cycles WHERE (STM/STM_MAX) > 0.15 AND (STM/STM_MAX) <= 0.3 AND STM != STM_MAX and func!='boy' and func!='wait' and func!='boy_npc' and func!='wait_npc'");
        $result_stamina_up = myquery("UPDATE game_users SET STM = CEILING(STM + 5 * DEX / 3) * $event_cycles WHERE (STM/STM_MAX) >= 0 AND (STM/STM_MAX) <= 0.15 AND STM != STM_MAX and func!='boy' and func!='wait' and func!='boy_npc' and func!='wait_npc'");
        
        $result_health_up = myquery("UPDATE game_users SET STM = CEILING(STM + DEX / 8) * $event_cycles WHERE func='boy' OR func='wait' OR func='boy_npc' OR func='wait_npc'");

		$result_stamina_flat = myquery("UPDATE game_users SET STM = STM_MAX WHERE STM > STM_MAX");

        $next_update = time() + 60;
        $result_update = myquery("UPDATE game_events SET requested = '" . time() . "', due = '" . $next_update . "' WHERE task = '" . $event['task'] . "' LIMIT 1");
        break;


    case 'health_recovery':
        $event_cycles = round((time() - $event['due']) / (60 * 5)) + 1;
        $result_health_up = myquery("UPDATE game_users SET HP = ROUND(HP + DEX / 8) * $event_cycles WHERE DEX>=0");
        $result_health_flat = myquery("UPDATE game_users SET HP = HP_MAX WHERE HP > HP_MAX");

        $next_update = time() + 60;
        $result_update = myquery("UPDATE game_events SET requested = '" . time() . "', due = '" . $next_update . "' WHERE task = '" . $event['task'] . "' LIMIT 1");
        break;
		
		
	case 'mana_recovery':
	
		$event_cycles = round((time() - $event['due']) / (60 * 5)) + 1;
        $result_health_up = myquery("UPDATE game_users SET MP = ROUND(MP + NTL / 4) * $event_cycles WHERE NTL>=0");
        $result_health_flat = myquery("UPDATE game_users SET MP = MP_MAX WHERE MP > MP_MAX");
	    $next_update = time() + 60;
        $result_update = myquery("UPDATE game_events SET requested = '" . time() . "', due = '" . $next_update . "' WHERE task = '" . $event['task'] . "' LIMIT 1");
        break;

    case 'optimize_sessions':
        $result_optimize = myquery("OPTIMIZE TABLE game_sessions");
		$result_optimize = myquery("OPTIMIZE TABLE game_users");
		$result_optimize = myquery("OPTIMIZE TABLE game_map");
		$result_optimize = myquery("OPTIMIZE TABLE game_pm");
        $next_update = time() + (60 * 60 * 24);
        $result_update = myquery("UPDATE game_events SET requested = '" . time() . "', due = '" . $next_update . "' WHERE task = '" . $event['task'] . "' LIMIT 1");
        break;
}
}
}
?>