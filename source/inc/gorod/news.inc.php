<?

if (function_exists("start_debug")) start_debug(); 

$magi=myquery("select * from game_tavern where town=$town and vladel='".$user_id."'");
if (mysql_num_rows($magi) AND $town>0)
{
	$img='http://'.IMG_DOMAIN.'/race_table/hobbit/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center;" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';

	if (!isset($_POST['savetownnews']))
	{
		echo "<form name=frm method=post>";

		$sel_town = myquery("SELECT news FROM game_gorod WHERE town=$town");
		list($news) = mysql_fetch_array($sel_town);
        $news=stripslashes($news);

		echo'<center><b>Изменение городской новости:<br>';
		echo '<table>
		<tr><td>Новость:</td><td><textarea name="news2" cols="52" class="input" rows="10">'.$news.'</textarea></td></tr>
		<tr><td colspan="2" align="center"><input name="savetownnews" type="submit" value="Сохранить"></td></tr>
		</table>';
	}
	else
	{
		$news2=htmlspecialchars(mysql_escape_string($news2));
		//$news2=''.$news2.'  <br>Маг города - '.$char['name'].'';

		$result=myquery("update game_gorod set news='$news2' where town=$town");
		echo'<center>Городская новость изменена<br><meta http-equiv="refresh" content="1;url=town.php">';
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>