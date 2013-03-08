<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['bot_chat'] >= 1)
{
    if(!isset($edit) and !isset($new) and !isset($delete))
    {
        echo "<center><a href=\"admin.php?opt=main&option=bot_chat\">Вопрос - ответ</a> | ".
             "<a href=\"admin.php?opt=main&annoy&option=bot_chat\">Надоели вы мне</a></center>";

        if (!isset($annoy))
        {
          $pm=myquery("SELECT COUNT(*) FROM game_bot_chat");
          if (!isset($page)) $page=1;
          $page=(int)$page;
          $line=25;
          $allpage=ceil(mysql_result($pm,0,0)/$line);
          if ($page>$allpage) $page=$allpage;
          if ($page<1) $page=1;

          echo "<table border=0 cellspacing=3 cellpadding=3>";
          echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bot_chat&new>Добавить запись</a></td></tr>";
          echo "<tr bgcolor=#333333><td>ID</td><td>Фраза</td><td>Тип</td><td></td></tr>";
          $qw=myquery("SELECT * FROM game_bot_chat ORDER BY type,id ASC limit ".(($page-1)*$line).", $line");
          while($ar=mysql_fetch_array($qw))
          {
            echo'<tr>
			<td><a href=admin.php?opt=main&option=bot_chat&edit='.$ar['id'].'>'.$ar['id'].'</a></td>
            <td>'.$ar['text'].'</td>
            <td>'.$ar['type'].'</td>
			<td><a href=admin.php?opt=main&option=bot_chat&delete='.$ar['id'].'>Удалить запись</a></td>
            </tr>';
          }
          echo'</table>';
          $href = '?opt=main&option=bot_chat&';
	    echo'<center>Страница: ';
          show_page($page,$allpage,$href);
        } else
        {
          $pm=myquery("SELECT COUNT(*) FROM game_bot_chat_annoy");
          if (!isset($page)) $page=1;
          $page=(int)$page;
          $line=25;
          $allpage=ceil(mysql_result($pm,0,0)/$line);
          if ($page>$allpage) $page=$allpage;
          if ($page<1) $page=1;

          echo "<table border=0 cellspacing=3 cellpadding=3>";
          echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&annoy&option=bot_chat&new>Добавить запись</a></td></tr>";
          echo "<tr bgcolor=#333333><td>ID</td><td>Фраза</td><td></td></tr>";
          $qw=myquery("SELECT * FROM game_bot_chat_annoy ORDER BY id ASC limit ".(($page-1)*$line).", $line");
          while($ar=mysql_fetch_array($qw))
          {
            echo'<tr>
			<td><a href=admin.php?opt=main&option=bot_chat&annoy&edit='.$ar['id'].'>'.$ar['id'].'</a></td>
            <td>'.$ar['text'].'</td>
			<td><a href=admin.php?opt=main&option=bot_chat&annoy&delete='.$ar['id'].'>Удалить запись</a></td>
            </tr>';
          }
          echo'</table>';
          $href = '?opt=main&option=bot_chat&';
	    echo'<center>Страница: ';
          show_page($page,$allpage,$href);
        }
    }

    if (!isset($annoy))
    {
      if(isset($edit))
      {
        if (!isset($save))
        {
            $qw=myquery("SELECT * FROM game_bot_chat where id='$edit'");
            $ar=mysql_fetch_array($qw);
            echo'<form action="" method="post">
            Текст: <br><textarea name=text cols=40 rows=25>'.$ar['text'].'</textarea><br><br>';
            echo'Тип записи: <input type=text name="type" value="'.$ar['type'].'">';

            echo '<br><br>
            <input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value="">';
        }
        else
        {
            echo'Запись изменена';
            $up=myquery("update game_bot_chat set text='".htmlspecialchars($text)."', type='$type' where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Изменил словарь бота в чате на : <b>".htmlspecialchars($text)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bot_chat">';
        }
      }


      if(isset($new))
      {
        if (!isset($save))
        {
            echo'<form action="" method="post">
            Текст: <br><textarea name=text cols=60 rows=25 value=""></textarea><br><br>';
                  echo'Тип записи: <input type=text name="type" value="">';

            echo '<br><br>
            <input name="save" type="submit" value="Добавить запись"><input name="save" type="hidden" value="">';
        }
        else
        {
            echo'Запись добавлена';
            $up=myquery("insert into game_bot_chat (text,type) VALUES ('".htmlspecialchars($text)."','$type')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Добавил в словарь бота в чате фразу : <b>".htmlspecialchars($text)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bot_chat">';
        }
      }

      if(isset($delete))
      {
        echo'Запись удалена';
		$text = @mysql_result(@myquery("SELECT text FROM game_bot_chat WHERE id='$delete'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Удалил из словаря бота в чате фразу : <b>".htmlspecialchars($text)."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        $up=myquery("delete from game_bot_chat where id='$delete'");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bot_chat">';
      }
    }
    else // annoy
    {
      if(isset($edit))
      {
        if (!isset($save))
        {
            $qw=myquery("SELECT * FROM game_bot_chat_annoy where id='$edit'");
            $ar=mysql_fetch_array($qw);
            echo'<form action="" method="post">
            Текст: <br><textarea name=text cols=40 rows=25>'.$ar['text'].'</textarea><br><br>';
            echo '<br><br>
            <input name="save" type="submit" value="Сохранить">
            <input name="annoy" type="hidden" value=""/><input name="save" type="hidden" value="">';
        }
        else
        {
            echo'Запись изменена';
            $up=myquery("update game_bot_chat_annoy set text='".htmlspecialchars($text)."' where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Изменил словарь эмоций бота в чате на : <b>".htmlspecialchars($text)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&annoy&option=bot_chat">';
        }
      }


      if(isset($new))
      {
        if (!isset($save))
        {
            echo'<form action="" method="post">
            Текст: <br><textarea name=text cols=60 rows=25 value=""></textarea><br><br>';

            echo '<br><br>
            <input name="annoy" type="hidden" value=""/> <input name="save" type="hidden" value=""/>
            <input name="save" type="submit" value="Добавить запись">';
        }
        else
        {
            echo'Запись добавлена';
            $up=myquery("insert into game_bot_chat_annoy (text) VALUES ('".htmlspecialchars($text)."')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Добавил в словарь эмоций бота в чате фразу : <b>".htmlspecialchars($text)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&annoy&option=bot_chat">';
        }
      }

      if(isset($delete))
      {
        echo'Запись удалена';
		$text = @mysql_result(@myquery("SELECT text FROM game_bot_chat_annoy WHERE id='$delete'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Удалил из словаря эмоций бота в чате фразу : <b>".htmlspecialchars($text)."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        $up=myquery("delete from game_bot_chat_annoy where id='$delete'");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&annoy&option=bot_chat">';
      }
    }

}

if (function_exists("save_debug")) save_debug(); 

?>