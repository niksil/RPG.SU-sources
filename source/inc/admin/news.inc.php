<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['news'] >= 1)
{
	echo "<table border=0 cellspacing=3 cellpadding=3 align=left width=\"100%\">";
	echo "<tr><td colspan=4><a href=admin.php?opt=main&option=add_news>Добавить новость</a> | ";
	if (!isset($old))
	{
		echo'<a href=admin.php?opt=main&option=news&old>Архив удаленных новостей</a>';
	}
	else
	{
		echo'<a href=admin.php?opt=main&option=news>Новости</a>';
	}
	echo"</td></tr>";
	echo "<tr bgcolor=#333333><td>Ник</td><td>Тема</td><td>Текст</td><td>Дата</td><td>Действие</td></tr>";
	if(!isset($old))
	{
        $items=myquery("SELECT count(*) FROM game_news where status='0' ORDER BY id DESC");
        if (!isset($page)) $page=1;
        $page=(int)$page;
        $line=10;
        $allpage=ceil(mysql_result($items,0,0)/$line);
        if ($page>$allpage) $page=$allpage;
        if ($page<1) $page=1;

		$q=myquery("SELECT * FROM game_news where status='0' ORDER BY id DESC limit ".(($page-1)*$line).", $line");
		while($ar=mysql_fetch_array($q))
		{
			$us=myquery("SELECT * FROM game_users WHERE user_id='$ar[id_user]'");
			if (!mysql_num_rows($us)) $us=myquery("SELECT * FROM game_users_archive WHERE user_id='$ar[id_user]'");
			$us=mysql_fetch_array($us);
			echo "<tr><td>$us[name]</td><td>$ar[theme]</td><td>$ar[text]</td><td>$ar[created]</td><td><a href=admin.php?opt=main&option=edit_news&id=$ar[id]>Редактировать</a> | <a href=admin.php?opt=main&option=del_news&id=$ar[id]>Удалить</a></td></tr>";
		}
        echo '<tr><td colspan="5">';
        $href = 'admin.php?opt=main&option=news&';
	    echo'<center>Страница: ';
        show_page($page,$allpage,$href);
		echo'</td></tr>';
	}
	if (isset($old))
	{
        $items=myquery("SELECT count(*) FROM game_news where status='1' ORDER BY id DESC");
        if (!isset($page)) $page=1;
        $page=(int)$page;
        $line=10;
        $allpage=ceil(mysql_result($items,0,0)/$line);
        if ($page>$allpage) $page=$allpage;
        if ($page<1) $page=1;

		$q=myquery("SELECT * FROM game_news where status='1' ORDER BY id DESC limit ".(($page-1)*$line).", $line");
		while($ar=mysql_fetch_array($q))
		{
			$us=myquery("SELECT * FROM game_users WHERE user_id='$ar[id_user]'");
			if (!mysql_num_rows($us)) $us=myquery("SELECT * FROM game_users_archive WHERE user_id='$ar[id_user]'");
			$us=mysql_fetch_array($us);
			echo "<tr><td>$us[name]</td><td>$ar[theme]</td><td>$ar[text]</td><td>$ar[created]</td></tr>";
		}
        echo '<tr><td colspan="5">';
        $href = 'admin.php?opt=main&option=news&old&';
	    echo'<center>Страница: ';
        show_page($page,$allpage,$href);
		echo'</td></tr>';
	}
	echo "</table>";
	echo "<br><br><br>";
}

if (function_exists("save_debug")) save_debug(); 

?>