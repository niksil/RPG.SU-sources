<?
	
require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

    myquery ("set character_set_client='cp1251'");
    myquery ("set character_set_results='cp1251'");
    myquery ("set collation_connection='cp1251_general_ci'");

$db = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());

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
elseif ($rand_map<=18)
{
    $map_name = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='Гильдия новичков'"),0,0);
    $map_xpos = mt_rand(0,10);
    $map_ypos = mt_rand(0,5);
}


$r = mt_rand(1,11);

if ($r==1)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('Кадка воды','0','0','other/water','$map_name','$map_xpos','$map_ypos','Прочее')");
}
elseif ($r==2)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('Кусок мяса','0','0','other/meat','$map_name','$map_xpos','$map_ypos','Прочее')");
}
elseif ($r==3)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('Магический эликсир','0','0','other/mana','$map_name','$map_xpos','$map_ypos','Прочее')");
}
elseif ($r<=5)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('Бутылка с голубым зельем','0','0','other/bottle2','$map_name','$map_xpos','$map_ypos','Прочее')");
}
elseif ($r<=7)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('Бутылка с бордовым зельем','0','0','other/bottle3','$map_name','$map_xpos','$map_ypos','Прочее')");
}
elseif ($r<=9)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('Неизвестная бутыль','0','0','other/bottle4','$map_name','$map_xpos','$map_ypos','Прочее')");
}
elseif ($r<=11)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('Ампула с эликсиром','0','0','other/bottle5','$map_name','$map_xpos','$map_ypos','Прочее')");
}
?>