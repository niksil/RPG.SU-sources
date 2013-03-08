<?php
//Крон для запуска каждые 6 часов

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

move_teleport(360);

myquery("DELETE FROM game_cron_log WHERE cron='every_6hour' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_6hour','Начало',".time().")");
$idcronlog = mysql_insert_id();
myquery("UPDATE game_cron_log SET step='Очистка чатов городов', timecron=".time()." WHERE id=$idcronlog");
 
//очистим чаты городов
$time1 = time()-24*60*60;
myquery("DELETE FROM game_log WHERE date<$time1 AND town<>0");
/*
myquery("UPDATE game_cron_log SET step='Раскидываем сундуки с золотом', timecron=".time()." WHERE id=$idcronlog");
//раскидаем по картам сундуки с золотом
$rand_map = mt_rand(1,20);

if ($rand_map<=6)
{
    $map_name = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='Средиземье'"),0,0);
    $map_xpos = mt_rand(0,53);
    $map_ypos = mt_rand(0,49);
}
elseif ($rand_map<=15)
{
    $map_name = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='Белерианд'"),0,0);
    $map_xpos = mt_rand(0,45);
    $map_ypos = mt_rand(0,39);
}
else
{
    $map_name = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='Гильдия новичков'"),0,0);
    $map_xpos = mt_rand(0,10);
    $map_ypos = mt_rand(0,5);
}


$money = mt_rand(100,150);
list($item_id)=mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='Сундук с сокровищами'"));
myquery("INSERT INTO game_items (item_id,item_cost,map_name,map_xpos,map_ypos,priznak) VALUES ($item_id,'$money','$map_name','$map_xpos','$map_ypos',2)");
*/
myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");

?>