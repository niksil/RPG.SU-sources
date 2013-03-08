<?php
//Крон для запуска каждые 3 часа

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

move_teleport(180);

myquery("DELETE FROM game_cron_log WHERE cron='every_3hour' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_3hour','Начало',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='Раскидываем бутылки', timecron=".time()." WHERE id=$idcronlog");
//раскидаем по земле разные бутылечки
$rand_map = mt_rand(1,20);

if ($rand_map<=5)
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


$r = mt_rand(1,11);

if ($r==1)
{
	list($item_id)=mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='Кадка воды'"));
}
elseif ($r==2)
{
	list($item_id)=mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='Кусок мяса'"));
}
elseif ($r==3)
{
	list($item_id)=mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='Магический эликсир'"));
}
elseif ($r<=5)
{
	list($item_id)=mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='Бутылка с голубым зельем'"));
}
elseif ($r<=7)
{
	list($item_id)=mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='Бутылка с бордовым зельем'"));
}
elseif ($r<=9)
{
	list($item_id)=mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='Неизвестная бутыль'"));
}
elseif ($r<=11)
{
	list($item_id)=mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='Ампула с эликсиром'"));
}
$up = myquery("INSERT INTO game_items (item_id,map_name,map_xpos,map_ypos,priznak) VALUES ($item_id,$map_name,$map_xpos,$map_ypos,2)");
/*
myquery("UPDATE game_cron_log SET step='Генерируем лицензию', timecron=".time()." WHERE id=$idcronlog");
myquery("UPDATE game_gorod SET license=0 WHERE license_end<".time()."");
$sel = myquery("SELECT game_gorod.town FROM game_gorod,game_map WHERE game_gorod.rustown!='' AND game_map.name IN (5,18) AND game_map.town=game_gorod.town");
$all = mysql_num_rows($sel);
if ($all>0)
{
	$r = mt_rand(0,$all-1);
	mysql_data_seek($sel,$r);
	$t = mysql_fetch_assoc($sel);
	$start_time = time() + mt_rand(0,5*60*60);
	$end_time = $start_time + 3*60*60;
	myquery("UPDATE game_gorod SET license=1,license_start=$start_time,license_end=$end_time WHERE town=".$t['town']."");
}       
*/

myquery("UPDATE game_cron_log SET step='3Копируем файл гороскопа', timecron=".time()." WHERE id=$idcronlog");

// download xml file for chat bot. Need to delete old before.
/* 
if (!@copy('http://img.ignio.com/r/export/win/xml/daily/com.xml','/home/vhosts/rpg.su/cache/com.xml'))
{
    $errors = error_get_last();
    myquery("UPDATE game_cron_log SET step='COPY ERROR: ".mysql_real_escape_string($errors['type'])." - ".mysql_real_escape_string($errors['message'])."', timecron=".time()." WHERE id=$idcronlog");
}
else
{
    copy('http://img.ignio.com/r/export/win/xml/daily/com.xml','/home/vhosts/rpg.su/cache/com.xml');
}
*/

$url = 'http://img.ignio.com/r/export/win/xml/daily/com.xml';
$file = ''.PATCH.'/cache/com.xml';

if (file_exists($file))
unlink($file);

$ch = curl_init();
if($ch)
{
    $fp = fopen($file, "w");
    if($fp)
    {
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_FILE, $fp);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_exec($ch);
      curl_close($ch);
      fclose($fp);
    }
}

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");

?>