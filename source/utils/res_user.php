<?php
$dirclass = "../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

echo '<table border=1 cellspacing=2 cellpadding=2>';
$sel = myquery("SELECT * FROM craft_resource");
while ($res=mysql_fetch_array($sel))
{
	$col_user = mysqlresult(myquery("SELECT SUM(col) FROM craft_resource_user WHERE res_id=".$res['id']." AND user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1) AND user_id NOT IN (SELECT user_id FROM game_users_archive WHERE clan_id=1) GROUP BY res_id"),0,0);
	$col_market = mysqlresult(myquery("SELECT SUM(col) FROM craft_resource_market WHERE res_id=".$res['id']." AND user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1) AND user_id NOT IN (SELECT user_id FROM game_users_archive WHERE clan_id=1) GROUP BY res_id"),0,0);
	if ($col_user+$col_market>0)
	{
		echo '<tr><td><img src="/images/item/resources/'.$res['img3'].'.gif">&nbsp;&nbsp;'.$res['name'].'</td><td>'.($col_user+$col_market).'</td></tr>';
		/*
		$s = myquery("SELECT a.user_id AS user_id,SUM(IFNULL(a.col,0)+IFNULL(b.col,0)) AS col FROM craft_resource LEFT JOIN (craft_resource_user AS a) ON (a.res_id=".$res['id'].") LEFT JOIN (craft_resource_market AS b) ON (a.user_id=b.user_id AND b.res_id=".$res['id'].") WHERE a.user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1) AND a.user_id NOT IN (SELECT user_id FROM game_users_archive WHERE clan_id=1) GROUP BY user_id ORDER BY col DESC LIMIT 15");
		while ($us = mysql_fetch_array($s))
		{
			echo '<tr><td>'.get_user('name',$us['user_id']).'</td><td>'.$us['col'].'</td></tr>';
		}
		*/
		
	}
}                                                                
echo "</table><br /><br />";

  
?>