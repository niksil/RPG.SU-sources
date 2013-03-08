<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['users'] > 1)
{
    if(!isset($edit) and !isset($new) and !isset($delete))
    {
        echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
        echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=brak&new>Добавить запись о браке</a></td></tr>";
        echo "<tr bgcolor=#333333><td>Супруг 1</td><td>Супруг 2</td><td>Статус</td><td>Дата регистрации</td><td></td></tr>";
        $qw=myquery("SELECT * FROM game_users_brak order BY status ASC");
        while($ar=mysql_fetch_array($qw))
        {
            $selname = myquery("SELECT name FROM game_users WHERE user_id=".$ar['user1']."");
            if (!mysql_num_rows($selname)) $selname = myquery("SELECT name FROM game_users_archive WHERE user_id=".$ar['user1']."");
            list($name1) = mysql_fetch_array($selname);
            $selname = myquery("SELECT name FROM game_users WHERE user_id=".$ar['user2']."");
            if (!mysql_num_rows($selname)) $selname = myquery("SELECT name FROM game_users_archive WHERE user_id=".$ar['user2']."");
            list($name2) = mysql_fetch_array($selname);
            echo'<tr>
			<td>'.$name1.'</td>
            <td>'.$name2.'</td>
            <td>'.$ar['status'].'</td><td>'.$ar['datareg'].'</td>
            <td><a href=admin.php?opt=main&option=brak&delete='.$ar['id'].'>Удалить запись</a></td>
            </tr>';
        }
        echo'</table>';
    }

    if(isset($new))
    {
        if (!isset($save))
        {
            echo'<form autocomplete="off" action="" method="post">
            Супруг 1: <input type=text name=name1 value="" size=50><br>
            Супруг 2: <input type=text name=name2 value="" size=50><br>
            Дата регистрации (формат 02.05.2007): <input type=text name=datareg value="'.date("d.m.Y",time()).'" size=10 maxsize=10><br>
            <input name="save" type="submit" value="Добавить запись"><input name="save" type="hidden" value="">';
        }
        else
        {
             echo'Запись о браке добавлена';
            $selname = myquery("SELECT user_id FROM game_users WHERE name='$name1'");
            if (!mysql_num_rows($selname)) $selname = myquery("SELECT user_id FROM game_users_archive WHERE name='$name1'");  
            list($user1) = mysql_fetch_array($selname);
            $selname = myquery("SELECT user_id FROM game_users WHERE name='$name2'");  
            if (!mysql_num_rows($selname)) $selname = myquery("SELECT user_id FROM game_users_archive WHERE name='$name2'");  
            list($user2) = mysql_fetch_array($selname);
            if ($user1>0 AND $user2>0)
            {
                myquery("DELETE FROM game_users_brak WHERE user1=$user1 OR user2=$user2 OR user1=$user2 OR user2=$user1");
                myquery("INSERT INTO game_users_brak (user1,user2,datareg,status) VALUES ($user1,$user2,'$datareg',1)");
			    $da = getdate();
			    $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			     VALUES (
			     '".$char['name']."',
			     'Добавил запись о браке игрока: <b>".$name1."</b> и <b>".$name2."</b>',
			     '".time()."',
			     '".$da['mday']."',
			     '".$da['mon']."',
			     '".$da['year']."')")
				     or die(mysql_error());
            }
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=brak">';
        }
    }

    if(isset($delete))
    {
        echo'Запись о браке удалена';
        $brak = mysql_fetch_array(myquery("SELECT * FROM game_users_brak WHERE id=$delete"));
        $selname = myquery("SELECT name FROM game_users WHERE user_id=".$brak['user1']."");
        if (!mysql_num_rows($selname)) $selname = myquery("SELECT name FROM game_users_archive WHERE user_id=".$brak['user1']."");
        list($name1) = mysql_fetch_array($selname);
        $selname = myquery("SELECT name FROM game_users WHERE user_id=".$brak['user2']."");
        if (!mysql_num_rows($selname)) $selname = myquery("SELECT name FROM game_users_archive WHERE user_id=".$brak['user2']."");
        list($name2) = mysql_fetch_array($selname);
        myquery("DELETE FROM game_users_brak WHERE id=$delete");
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Удалил запись о браке игрока: <b>".$name1."</b> и <b>".$name2."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=brak">';
    }

}

if (function_exists("save_debug")) save_debug(); 

?>