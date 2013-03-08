<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['lab'] >= 1)
{
	if (!isset($see))
	{
		echo'<div id="content" onclick="hideSuggestions();"><center><form action="" method="post"><br>
		<input name="uname" type="text" value="" size="30" maxlength="50" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div> <input name="submit" type="submit" value="Отправить в лабиринт">
		<input name="see" type="hidden" value="">
		</form></div><script>init();</script>';
	}
	else
    {
        echo'Игрок в лабиринте';
        $map_id = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='Лабиринт'"),0,0);
		list($uid) = mysql_fetch_array(myquery("(SELECT user_id FROM game_users WHERE name='$uname') UNION (SELECT user_id FROM game_users_archive WHERE name='$uname')"));
        $update=myquery("update game_users_map set map_name='$map_id'. map_xpos='0', map_ypos='0' where user_id='$uid'");
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Отправил  игрока: <b>".$uname."</b> в Лабиринт',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=lab">';
    }
}

if (function_exists("save_debug")) save_debug(); 

?>