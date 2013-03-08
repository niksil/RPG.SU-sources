<?php                           
//Крон для запуска каждые 3 недели

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

myquery("DELETE FROM game_cron_log WHERE cron='every_3week' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_3week','Начало',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='', timecron=".time()." WHERE id=$idcronlog");
myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");
?>