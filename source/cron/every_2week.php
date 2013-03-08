<?php
//Крон для запуска каждые 2 недели

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

myquery("DELETE FROM game_cron_log WHERE cron='every_2week' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_2week','Начало',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='Пополнение запасов на складах торговцев', timecron=".time()." WHERE id=$idcronlog");
myquery("UPDATE game_shop SET
oruj_store_current=oruj_store_max,
dosp_store_current=dosp_store_max,
shit_store_current=shit_store_max,
mag_store_current=mag_store_max,
pojas_store_current=pojas_store_max,
artef_store_current=artef_store_max,
ring_store_current=ring_store_max,
shlem_store_current=shlem_store_max,
svitki_store_current=svitki_store_max,
eliksir_store_current=eliksir_store_max,
schema_store_current=schema_store_max,
luk_store_current=luk_store_max,
amulet_store_current=amulet_store_max,
naruchi_store_current=naruchi_store_max,
shtan_store_current=shtan_store_max,
perch_store_current=perch_store_max,
ukrash_store_current=ukrash_store_max,
boots_store_current=boots_store_max,
magic_books_store_current=magic_books_store_max,
instrument_store_current=instrument_store_max,
others_store_current=others_store_max
");

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");
?>