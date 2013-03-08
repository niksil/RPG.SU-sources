<?php
//Крон для запуска каждую неделю
require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

myquery("DELETE FROM game_cron_log WHERE cron='every_1week' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_1week','Начало',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='', timecron=".time()." WHERE id=$idcronlog");
myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");

//myquery("TRUNCATE TABLE `game_bot_chat_resp`;");
myquery("UPDATE `game_bot_chat_resp` SET `count` = `count` - 2");
myquery("DELETE FROM `game_bot_chat_resp` WHERE `count` <= 0");

?>