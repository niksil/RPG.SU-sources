<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['online'] >= 1)
{
	$current_time = time();
	if (!isset($sort)) $sort='game_users_active.host';
	$online_range = $current_time - 300;
    $result = myquery("
SELECT view_active_users.user_id,view_active_users.name AS name,game_har.name as race_name,view_active_users.clevel,
game_users_func.func_id,view_active_users.GP,view_active_users.EXP,view_active_users.hide,game_users_map.map_name,game_maps.name AS map_name,
game_users_map.map_xpos,game_users_map.map_ypos,game_users_active.host,game_users_active_host.host_more,view_active_users.delay_reason,game_users_data.email,IFNULL(combat_users.combat_id,0) as boy 
FROM (game_maps,view_active_users,game_users_map,game_users_active_host,game_users_active,game_users_data,game_har,game_users_func) LEFT JOIN combat_users ON (view_active_users.user_id=combat_users.user_id) 
WHERE game_users_map.user_id = view_active_users.user_id 
AND game_users_active.user_id= view_active_users.user_id
AND game_users_active_host.user_id = view_active_users.user_id
AND game_maps.id=game_users_map.map_name 
AND view_active_users.user_id= game_users_data.user_id
AND view_active_users.race=game_har.id 
AND view_active_users.user_id=game_users_func.user_id
ORDER BY game_users_active.host ");
	$online_number = mysql_num_rows($result);

	echo '<center>В игре '.$online_number.' человек</center>
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<tr><td valign="top"><tr bgcolor="#006699"><td width="50"><font size="1" face="Verdana" color="#000000"><a href="?opt=main&option=ip&sort=game_users.name" target="main">Ник</a></font></td><td width="50"><font size="1" face="Verdana" color="#000000"><a href="?opt=main&option=ip&sort=game_users.race" target="main">Раса</a></font></td><td width="10"><font size="1" face="Verdana" color="#000000"><a href="?opt=main&option=ip&sort=game_users.clevel" target="main">Ур.</a></font></td><td width="160"><font size="1" face="Verdana" color="#000000"><a href="?opt=main&option=ip&sort=game_users_map.map_name" target="main">Позиция</a></font></td><td width="50"><font size="1" face="Verdana" color="#000000"><a href="?opt=main&option=ip&sort=game_users_active.host" target="main">Хост</a></font></td><td width="50"><font size="1" face="Verdana" color="#000000">Хост(доп.)</font></td><td width="50"><font size="1" face="Verdana" color="#000000">reason</font></td>';
	echo '<td width="50"><font size="1" face="Verdana" color="#000000">func_id</font></td>';
	if ($adm['users']>=1)
	{
		 echo '
		 <td width="40"><font size="1" face="Verdana" color="#000000"><a href="?opt=main&option=ip&sort=game_users.GP" target="main">GP</a></font></td>
		 <td width="50"><font size="1" face="Verdana" color="#000000"><a href="?opt=main&option=ip&sort=game_users.EXP" target="main">EXP</a></font></td>
		 <td width="150"><font size="1" face="Verdana" color="#000000"><a href="?opt=main&option=ip&sort=game_users_data.email" target="main">e-mail</a></font></td>
		 <td width="50"><font size="1" face="Verdana" color="#000000">Ред</font></td><td width="50"><font size="1" face="Verdana" color="#000000">Уд.бой</font></td>';
         if (isset($_GET['del_combat']))
         {
             myquery("UPDATE combat_users SET HP=0 WHERE user_id=".$_GET['del_combat']."");
         }
	}
	echo '</tr>';
	while ($act = mysql_fetch_array($result))
	{
        $flagPath = ''; 
        $flagPathMore = '';
        /*
		$countryName = '';
		$flagPath = '';
		$countryNameMore = '';
		$flagPathMore = '';
		$resc=myquery("SELECT short, country_name FROM countries WHERE start_ip<".$act['host']." AND stop_ip>".$act['host']." LIMIT 1"); 
		// Разбираем результат 
		if (mysql_num_rows($resc)==1) 
		{ 
			$row = mysql_fetch_array($resc); 
			$flagPath = "flag/".strtolower($row['short']).".gif";
			$countryName = strtolower($row['country_name']);
		} 
        if ($act['host_more']!='')
        {
		    $resc=myquery("SELECT short, country_name FROM countries WHERE start_ip<".ip2number($act['host_more'])." AND stop_ip>".ip2number($act['host_more'])." LIMIT 1"); 
		    // Разбираем результат 
		    if (mysql_num_rows($resc)==1) 
		    { 
			    $row = mysql_fetch_array($resc); 
			    $flagPathMore = "flag/".strtolower($row['short']).".gif";
			    $countryNameMore = strtolower($row['country_name']);
		    } 
        }
		*/		
		echo '<tr bgcolor="#333333" valign="middle">
<td><font size="1" face="Verdana" color="#ffffff"><a href=http://'.DOMAIN.'/view/?userid='.$act['user_id'].' target="_blank">'.$act['name'].'</a></font></td>
<td><font size="1" face="Verdana" color="#ffffff">'.$act['race_name'].'</font></td>
<td><font size="1" face="Verdana" color="#ffffff">'.$act['clevel'].'</font></td>
<td><font size="1" face="Verdana" color="#ffffff">'.$act['map_name'].': x-'.$act['map_xpos'].' y-'.$act['map_ypos'].'</font></td>
<td><font size="1" face="Verdana" color="#ffffff">';
if (!empty($flagPath))
{
	echo '<img src=http://'.IMG_DOMAIN.'/'.$flagPath.' border="0" title="'.$countryName.'" height="15">&nbsp;';
}
echo number2ip($act['host']).' </font></td>
<td><font size="1" face="Verdana" color="#ffffff">';
if (!empty($flagPathMore))
{
	echo '<img src=http://'.IMG_DOMAIN.'/'.$flagPathMore.' border="0" title="'.$countryNameMore.'" height="15">&nbsp;';
}
echo $act['host_more'].'</font></td>
<td><font size="1" face="Verdana" color="#ff0000">'.get_delay_reason($act['delay_reason']).'</font></td>
<td><font size="1" face="Verdana" color="#ffffff">'.$act['func_id'].'</font></td>';
	if ($adm['users']>=1)
	{
	    echo '
	    <td><font size="1" face="Verdana" color="#ffffff">'.$act['GP'].'</font></td>
	    <td><font size="1" face="Verdana">'.$act['EXP'].'</font></td>
	    <td><font size="1" face="Verdana" color="#ffffff">'.$act['email'].'</font></td>
		<td><font size="1" face="Verdana" color="#ffffff"><a href="admin.php?opt=main&option=users&name_v='.$act['name'].'"><img width="20" height="20" src="http://'.IMG_DOMAIN.'/nav/show.gif" border="0"></a></font></td>';
        if ($act['boy']!=0)
        {
            echo '<td><font size="1" face="Verdana" color="#ffffff"><a href="admin.php?opt=main&option=ip&sort='.$sort.'&del_combat='.$act['user_id'].'"><img width="20" height="20" src="http://'.IMG_DOMAIN.'/nav/action_notattack.gif" border="0"></a></font></td>';
        }
        else
        {
            echo '<td>&nbsp;</td>';
        }
	}
	echo '</tr>';
}
echo '</table><br>';
echo'<br><a href="?opt=main&option=ip&sort='.$sort.'">Обновить</a><br><br>';
}

if (function_exists("save_debug")) save_debug(); 

?>