<?php
//Крон для запуска каждый час

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

$db = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());

function jump_random_query(&$query)
{
	$all = @mysql_num_rows($query);
	if ($all>0)
	{
		$r = mt_rand(0,$all-1);
		mysql_data_seek($query,$r);
	}
	return 0;
}

for ($i=0;$i<3;$i++)
{
	//раскидаем по земле разные бутылечки
	$maze = myquery("SELECT * FROM game_maps WHERE maze=1");
	jump_random_query($maze);
	$maze = mysql_fetch_assoc($maze);
	
	echo $maze['name'];
	
	list($dim_x) = mysql_fetch_array(myquery("SELECT xpos FROM game_maze WHERE map_name=".$maze['id']." ORDER BY xpos DESC LIMIT 1"));
	list($dim_y) = mysql_fetch_array(myquery("SELECT ypos FROM game_maze WHERE map_name=".$maze['id']." ORDER BY ypos DESC LIMIT 1"));
	
	$str_query = "SELECT xpos,ypos FROM game_maze WHERE (move_up+move_down+move_left+move_right)<=1 AND map_name=".$maze['id']." AND (xpos<>0 AND ypos<>0) AND (xpos<>$dim_x AND ypos<>$dim_y)";
	$sel = myquery($str_query);
	echo $str_query.'<br>'.mysql_num_rows($sel);
	jump_random_query($sel);
	$maplab = mysql_fetch_assoc($sel);
	$map_xpos = $maplab['xpos'];
	$map_ypos = $maplab['ypos'];
	
	$already = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=0 AND map_name='".$maze['id']."' AND ident='Зелье зарядки артефактов'"),0,0);
	if ($already<8)
	{
		$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('Зелье зарядки артефактов','0','0','other/phial','".$maze['id']."','$map_xpos','$map_ypos','Прочее')");
	}
}

?>