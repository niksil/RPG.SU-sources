<?php
//Крон для запуска каждые 4 часа


require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

move_teleport(240);

myquery("DELETE FROM game_cron_log WHERE cron='every_4hour' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_4hour','Начало',".time().")");
$idcronlog = mysql_insert_id();
myquery("UPDATE game_cron_log SET step='Создание обелиска', timecron=".time()." WHERE id=$idcronlog");
 
$new = myquery("SELECT type FROM game_obelisk WHERE time_begin=0 AND type NOT IN (SELECT harka FROM game_obelisk_users WHERE type=0 AND time_end>".time().")");
if (mysql_num_rows($new)>0)
{
    jump_random_query($new);
    $ob = mysql_fetch_assoc($new);
    $harka = $ob['type'];
    $hour = mt_rand(-210*60,+210*60);
    $time_begin = time()+5*60*60+$hour;
    $time_end = $time_begin+24*60*60;
    $map = mt_rand(0,1);
    if ($map == 0) $map_name = 5;
    else $map_name = 18;
    list($max_xpos,$max_ypos) = mysql_fetch_array(myquery("SELECT xpos,ypos FROM game_map WHERE name=$map_name ORDER BY xpos DESC, ypos DESC LIMIT 1"));
    $map_xpos = mt_rand(1,$max_xpos-1);
    $map_ypos = mt_rand(1,$max_ypos-1);
    myquery("UPDATE game_obelisk SET time_begin=$time_begin, time_end=$time_end, map_xpos=$map_xpos, map_ypos=$map_ypos, map_name=$map_name, user_id=0 WHERE type='$harka'");
}
myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");
?>