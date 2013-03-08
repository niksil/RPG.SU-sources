<?php
require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

$db = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());

	myquery ("set character_set_client='cp1251'");
	myquery ("set character_set_results='cp1251'");
	myquery ("set collation_connection='cp1251_general_ci'");

$event_cycles = 1;
$result_stamina_up = myquery("UPDATE game_users,game_users_func
SET game_users.STM = game_users.STM + CEILING(8 * game_users.DEX / 3) * $event_cycles 
WHERE (game_users.STM/game_users.STM_MAX) > 0.6 
AND game_users.STM != game_users.STM_MAX 
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
");

$result_stamina_up = myquery("UPDATE game_users,game_users_func 
SET game_users.STM = game_users.STM + CEILING(7 * game_users.DEX / 3) * $event_cycles 
WHERE (game_users.STM/game_users.STM_MAX) > 0.3 
AND (game_users.STM/game_users.STM_MAX) <= 0.6 
AND game_users.STM != game_users.STM_MAX 
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
");

$result_stamina_up = myquery("UPDATE game_users,game_users_func
SET game_users.STM = game_users.STM + CEILING(6 * game_users.DEX / 3) * $event_cycles 
WHERE (game_users.STM/game_users.STM_MAX) > 0.15 
AND (game_users.STM/game_users.STM_MAX) <= 0.3 
AND game_users.STM != game_users.STM_MAX 
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
");

$result_stamina_up = myquery("UPDATE game_users,game_users_func 
SET game_users.STM = game_users.STM + CEILING(5 * game_users.DEX / 3) * $event_cycles 
WHERE (game_users.STM/game_users.STM_MAX) >= 0 
AND (game_users.STM/game_users.STM_MAX) <= 0.15 
AND game_users.STM != game_users.STM_MAX 
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
");

$result_stamina_up = myquery("UPDATE game_users,game_users_func 
SET game_users.STM = game_users.STM + CEILING(game_users.DEX / 8) * $event_cycles 
WHERE game_users_func.func_id='1'
");

$result_stamina_flat = myquery("UPDATE game_users SET STM = STM_MAX WHERE STM > STM_MAX");
echo 'Обновление энергии<br>';

$event_cycles = 1;
$result_health_up = myquery("UPDATE game_users SET HP = HP + ROUND(DEX / 8) * $event_cycles WHERE DEX>=0");
$result_health_flat = myquery("UPDATE game_users SET HP = HP_MAX WHERE HP > HP_MAX");
echo 'Обновление жизни<br>';

$event_cycles = 1;
$result_mana_up = myquery("UPDATE game_users SET MP = MP + ROUND(NTL / 4) * $event_cycles WHERE NTL>=0");
$result_mana_flat = myquery("UPDATE game_users SET MP = MP_MAX WHERE MP > MP_MAX");
echo 'Обновление маны<br>';

$sel = myquery("select indif FROM game_combats");
while (list($boy) = mysql_fetch_array($sel))
{
  $check = myquery("(SELECT boy FROM game_users WHERE boy='$boy') UNION (SELECT boy FROM game_users_archive WHERE boy='$boy')");
  if (!mysql_num_rows($check))
  {
	$del = myquery("DELETE FROM game_combats WHERE indif='$boy'");
  }
}
echo 'Удаление пустых боев';

$result_events = myquery("SELECT task, requested, due FROM game_events WHERE (assigned_id = '0' AND due < " . time() . ") ORDER BY priority, requested");

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

	case 'optimize_sessions':
		$result_optimize = myquery("REPAIR TABLE `forum_kat` , `forum_main` , `forum_otv` , `forum_topics` , `game_activity` , `game_admins` , `game_ban` , `game_baner` , `game_bank` , `game_battles` , `game_chat` , `game_chat_log` , `game_chat_nakaz` , `game_chat_option` , `game_check` , `game_clans` , `game_combats` , `game_combats_log` , `game_events` , `game_gorod` , `game_har` , `game_help` , `game_items` , `game_items_factsheet` , `game_items_old` , `game_log` , `game_log_adm` , `game_mag` , `game_map` , `game_medal` , `game_medal_users` , `game_nakaz` , `game_news` , `game_npc_template` , `game_npc` , `game_obj` , `game_pm` , `game_pm_deleted` , `game_port` , `game_port_bil` , `game_shop` , `game_spets` , `game_spets_item` , `game_stat_view` , `game_tavern` , `game_tavern_shop` , `game_turnir` , `game_users` , `game_users_archive` , `game_users_brak` , `game_users_npc` , `game_users_online` , `game_users_reg` , `game_vsadnik` , `game_wm` , `game_zakon` , `skazka_archive` , `skazka_ban` , `skazka_story` ");
		$result_optimize = myquery("OPTIMIZE TABLE `forum_kat` , `forum_main` , `forum_otv` , `forum_topics` , `game_activity` , `game_admins` , `game_ban` , `game_baner` , `game_bank` , `game_battles` , `game_chat` , `game_chat_log` , `game_chat_nakaz` , `game_chat_option` , `game_check` , `game_clans` , `game_combats` , `game_combats_log` , `game_events` , `game_gorod` , `game_har` , `game_help` , `game_items` , `game_items_factsheet` , `game_items_old` , `game_log` , `game_log_adm` , `game_mag` , `game_map` , `game_medal` , `game_medal_users` , `game_nakaz` , `game_news` , `game_npc` , `game_obj` , `game_pm` , `game_pm_deleted` , `game_port` , `game_port_bil` , `game_shop` , `game_spets` , `game_spets_item` , `game_stat_view` , `game_tavern` , `game_tavern_shop` , `game_turnir` , `game_users` , `game_users_archive` , `game_users_brak` , `game_users_npc` , `game_users_online` , `game_users_reg` , `game_vsadnik` , `game_wm` , `game_zakon` , `skazka_archive` , `skazka_ban` , `skazka_story` ");
		$next_update = time() + (60 * 60 * 24);
		$result_update = myquery("UPDATE game_events SET requested = '" . time() . "', due = '" . $next_update . "' WHERE task = '" . $event['task'] . "' LIMIT 1");
		break;
}
}
?>