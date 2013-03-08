<?

if (function_exists("start_debug")) start_debug(); 

if ($build_user==$user_id)
{
	//echo '<table border=1 cellspacing=2 cellpadding=1>';
	//$sel_stat = myquery("SELECT DISTINCT user FROM craft_stat WHERE build_id=$build_id AND type='z' ORDER BY user");
	//$nom = 0;
	//while (list($stat_user)=mysql_fetch_array($sel_stat))
	//{
	//	$nom++;
	//	echo '<tr';
	//	if ($nom%3==0) echo ' bgcolor="#580000"';
	//	if ($nom%3==1) echo ' bgcolor="#000058"';
	//	if ($nom%3==2) echo ' bgcolor="#005800"';
	//	echo'><td>';
	//	$sel_user = myquery("SELECT name,clevel,clan_id,race FROM game_users WHERE user_id=$stat_user");
	//	if (!mysql_num_rows($sel_user)) $sel_user = myquery("SELECT name,clevel,clan_id,race FROM game_users_archive WHERE user_id=$stat_user"); 
	//	$usr = mysql_fetch_array($sel_user);
	//	echo '<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif">'.$usr['name'].' ('.$usr['race'].' '.$usr['clevel'].' уровня)'; 
	//	echo '</td><td>';
		$sel_res = myquery("SELECT res_id,SUM(vip) as summa FROM craft_stat WHERE type='z' GROUP BY res_id ");
		while ($res=mysql_fetch_array($sel_res))
		{
			$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$res['res_id'].""));
			echo '<img src="http://'.IMG_DOMAIN.'/item/resources/'.$ress['img2'].'.gif" align="middle" border="0" width=30 height=30>'.$ress['name'].' - '.$res['summa'].' единиц.<br>';
		}
	//	echo '</td></tr>';
	//}
	//echo '</table>';
}

if (function_exists("save_debug")) save_debug(); 

?>