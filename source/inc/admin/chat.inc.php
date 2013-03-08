<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['chat'] >= 1)
{
	echo '<script type="text/javascript" src="chat/chat.js" ></script>';
	if(!isset($edit) and !isset($delete))
	{
		echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
		echo "<tr bgcolor=#333333<td>Время</td><td>Автор</td><td>Сообщение</td><td></td></tr>";
		$qw=myquery("SELECT * FROM game_log WHERE too=0 AND town<9000 order BY id DESC");
		while($ar=mysql_fetch_array($qw))
		{
			if ($ar['fromm']==-1) 
			{
				$fromm = '[Нафаня]';
			}
			else 
			{
				$fromm = get_user("name",$ar['fromm']);
			}
			echo'<tr>
			<td>'.date("H:i:s d.m.y",$ar['date']).'</td>
			<td>'.$fromm.'</td>
			<td><script>document.write(parseMessage("'.iconv("UTF-8","Windows-1251//IGNORE",$ar['message']).'"));</script></td>
			<td><a href=admin.php?opt=main&option=chat&delete='.$ar['id'].'>Удалить сообщение</a></td>
			</tr>';
		}
		echo'</table>';
	}

	if(isset($delete))
	{
		echo'Сообщение удалено';
		$up=myquery("delete from game_log where id='$delete'");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=chat">';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>