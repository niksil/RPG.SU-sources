<?php
//Крон для запуска каждую минуту

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

//ID карты Туманные Горы
define('id_map_tuman',820);

$maze_id = "(691,692,804,".id_map_tuman.")";

echo 'Обновление энергии0<br>';
$event_cycles = 1;
$result_stamina_up = myquery("UPDATE game_users,game_users_map,game_users_func,game_users_active
SET game_users.STM = game_users.STM + CEILING(8 * game_users.DEX / 3) * $event_cycles 
WHERE (game_users.STM/game_users.STM_MAX) > 0.6 
AND game_users.STM != game_users.STM_MAX
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_active.user_id=game_users.user_id
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)");

echo 'Обновление энергии1<br>';

$result_stamina_up = myquery("UPDATE game_users,game_users_map,game_users_func,game_users_active
SET game_users.STM = game_users.STM + CEILING(7 * game_users.DEX / 3) * $event_cycles 
WHERE (game_users.STM/game_users.STM_MAX) > 0.3 
AND (game_users.STM/game_users.STM_MAX) <= 0.6
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_active.user_id=game_users.user_id
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)");

echo 'Обновление энергии2<br>';

$result_stamina_up = myquery("UPDATE game_users,game_users_map,game_users_func,game_users_active 
SET game_users.STM = game_users.STM + CEILING(6 * game_users.DEX / 3) * $event_cycles 
WHERE (game_users.STM/game_users.STM_MAX) > 0.15 
AND (game_users.STM/game_users.STM_MAX) <= 0.3
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_active.user_id=game_users.user_id
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)");

echo 'Обновление энергии3<br>';

$result_stamina_up = myquery("UPDATE game_users,game_users_map,game_users_func,game_users_active
SET game_users.STM = game_users.STM + CEILING(5 * game_users.DEX / 3) * $event_cycles 
WHERE (game_users.STM/game_users.STM_MAX) <= 0.15 
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_active.user_id=game_users.user_id
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)");

echo 'Обновление энергии4<br>';

//$result_stamina_up = myquery("UPDATE game_users SET STM = STM + CEILING(DEX / 8) * $event_cycles WHERE STM>= AND func='boy' OR func='wait' OR func='boy_npc' OR func='wait_npc'");
//echo 'Обновление энергии5<br>';

//$result_stamina_flat = myquery("UPDATE game_users SET STM = STM_MAX WHERE STM > STM_MAX");
echo 'Обновление энергии6<br>';

myquery("UPDATE combat_users,combat
SET combat_users.STM = LEAST(combat_users.STM + 1,combat_users.STM_MAX)
WHERE combat_users.STM < combat_users.STM_MAX
AND combat_users.combat_id=combat.combat_id
AND combat.map_name NOT IN ".$maze_id."");
//myquery("UPDATE combat_users SET STM = STM_MAX WHERE STM > STM_MAX");

echo 'Обновление жизни<br>';
$event_cycles = 1;
$result_health_up = myquery("UPDATE game_users,game_users_map,game_users_active
SET game_users.HP = game_users.HP + ROUND(game_users.DEX / 6) * $event_cycles
WHERE game_users.DEX>=0
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_active.user_id=game_users.user_id
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)");
//$result_health_flat = myquery("UPDATE game_users SET HP = HP_MAX WHERE HP > HP_MAX");

//myquery("UPDATE combat_users SET HP = HP + ROUND(DEX / 6) * $event_cycles WHERE DEX>=0");
//myquery("UPDATE combat_users SET HP = HP_MAX WHERE HP > HP_MAX");

echo 'Обновление маны<br>';
$event_cycles = 0.5;
$result_mana_up = myquery("UPDATE game_users,game_users_map,game_users_active
SET game_users.MP = game_users.MP + ROUND(game_users.NTL / 8) * $event_cycles
WHERE game_users.NTL>=0
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_active.user_id=game_users.user_id
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)");
//$result_mana_flat = myquery("UPDATE game_users SET MP = MP_MAX WHERE MP > MP_MAX");

//myquery("UPDATE combat_users SET MP = MP + ROUND(NTL / 8) * $event_cycles WHERE NTL>=0");
//myquery("UPDATE combat_users SET MP = MP_MAX WHERE MP > MP_MAX");
move_teleport(1);
?>