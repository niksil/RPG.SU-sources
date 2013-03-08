<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['unlab'] >= 1)
{

if (!isset($see)){
echo'<div id="content" onclick="hideSuggestions();"><center><form action="" method="post">
<table border="0"><tr bgcolor="#006699" align="center"><td><font size="1" face="Verdana" color="#000000">Игрок</a></td><td><font size="1" face="Verdana" color="#000000">Карта</a></td><td><font size="1" face="Verdana" color="#000000">"Х"</a></td><td><font size="1" face="Verdana" color="#000000">"Y"</a></td><td><font size="1" face="Verdana" color="#000000">Действие</a></td></tr>
<tr bgcolor="#333333" align="center"><td><input name="name" type="text" value="" size="25" maxlength="50" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td><td>';


echo'<select name="map">';
$result = myquery("SELECT * FROM game_maps ORDER BY name");
while($map=mysql_fetch_array($result))
{
echo '<option value='.$map['id'].'>'.$map['name'].'</option>';
}
echo '</select>';

echo'</td><td><input name="map_xpos" type="text" value="" size="2" maxlength="2"></td><td><input name="map_ypos" type="text" value="" size="2" maxlength="2"></td><td><input name="submit" type="submit" value="Переместить"></td></tr>
<input name="see" type="hidden" value="">
</table>
</form></div><script>init();</script>';
}
else
        {
        echo'Ты '.echo_sex('переместил','переместила').' '.$name.'!';
		list($uid) = mysql_fetch_array(myquery("(SELECT user_id FROM game_users WHERE name='$name') UNION (SELECT user_id FROM game_users_archive WHERE name='$name')"));
        $result=myquery("update game_users_map set map_name='$map', map_xpos='$map_xpos',map_ypos='$map_ypos' where user_id='$uid'");
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Переместил из лабиринта игрока: <b>".$name."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        echo '<meta http-equiv="refresh" content="1;url=admin.php?option=unlab&opt=main">';
        }


}

if (function_exists("save_debug")) save_debug(); 

?>