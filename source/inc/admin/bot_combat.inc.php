<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['bot_combat'] >= 1)
{
    if(!isset($edit) and !isset($new) and !isset($delete))
    {
        $pm=myquery("SELECT COUNT(*) FROM game_bot_combat");
        if (!isset($page)) $page=1;
        $page=(int)$page;
        $line=25;
        $allpage=ceil(mysql_result($pm,0,0)/$line);
        if ($page>$allpage) $page=$allpage;
        if ($page<1) $page=1;

        echo "<table border=0 cellspacing=3 cellpadding=3>";
        echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bot_combat&new>Добавить запись</a></td></tr>";
        echo "<tr bgcolor=#333333><td>ID</td><td>Фраза</td><td></td></tr>";
        $qw=myquery("SELECT * FROM game_bot_combat order BY id ASC limit ".(($page-1)*$line).", $line");
        while($ar=mysql_fetch_array($qw))
        {
            echo'<tr>
			<td><a href=admin.php?opt=main&option=bot_combat&edit='.$ar['id'].'>'.$ar['id'].'</a></td>
            <td>'.$ar['text'].'</td>
			<td><a href=admin.php?opt=main&option=bot_combat&delete='.$ar['id'].'>Удалить запись</a></td>
            </tr>';
        }
        echo'</table>';
        $href = '?opt=main&option=bot_combat&';
	    echo'<center>Страница: ';
        show_page($page,$allpage,$href);
    }

    if(isset($edit))
    {
        if (!isset($save))
        {
            $qw=myquery("SELECT * FROM game_bot_combat where id='$edit'");
            $ar=mysql_fetch_array($qw);
            echo'<form action="" method="post">
            Текст: <br>(разрешены след.шаблоны-переменные:<br>
            %%name - имя игрока<br>
            %%race - раса игрока<br>
            %%npc - имя NPC<br>
            <textarea name=text cols=60 rows=25>'.$ar['text'].'</textarea><br><br>
            <input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
        }
        else
        {
            echo'Запись изменена';
            $up=myquery("update game_bot_combat set text='".htmlspecialchars($text)."' where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Изменил словарь бота в бою на : <b>".htmlspecialchars($text)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bot_combat">';
        }
    }


    if(isset($new))
    {
        if (!isset($save))
        {
            echo'<form action="" method="post">
            Текст: <br>(разрешены след.шаблоны-переменные:<br>
            %%name - имя игрока<br>
            %%race - раса игрока<br>
            %%npc - имя NPC<br>
            <textarea name=text cols=60 rows=25 value=""></textarea><br><br>
            <input name="save" type="submit" value="Добавить запись"><input name="save" type="hidden" value="">';
        }
        else
        {
            echo'Запись добавлена';
            $up=myquery("insert into game_bot_combat (text) VALUES ('".htmlspecialchars($text)."')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Добавил в словарь бота в бою фразу : <b>".htmlspecialchars($text)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bot_combat">';
        }
    }

    if(isset($delete))
    {
        echo'Запись удалена';
		$text = @mysql_result(@myquery("SELECT text FROM game_bot_combat WHERE id='$delete'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Удалил из словаря бота в бою фразу : <b>".htmlspecialchars($text)."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        $up=myquery("delete from game_bot_combat where id='$delete'");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bot_combat">';
    }

}

if (function_exists("save_debug")) save_debug(); 

?>