<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['forum'] >= 2)
{

	if (isset($kat))
    {
        $sel=myquery("select * from forum_kat where id='$kat'");
        $f=mysql_fetch_array($sel);
        if (!isset($save))
        {
                echo'<center><form action="" method="post">
                <table border="0" width="100%">
                <tr><td>Категория</td><td>';

                echo'<select name="main_id">';
                $res=myquery("select id,name from forum_main where id='".$f['main_id']."' limit 1");
                $o=mysql_fetch_array($res);
                echo'<option value='.$o['id'].'>'.$o['name'].'</option>';


                $res=myquery("select id,name from forum_main order by id");
                while($o=mysql_fetch_array($res))
                {
                echo '<option value="'.$o['id'].'">'.$o['name'].'</option>';
                }
                echo'</select></td></tr>';

                echo'</td></tr>
                <tr><td>Название</td><td><input name="name" value="'.$f['name'].'" type="text" size="50"></td></tr>
                <tr><td>Текст</td><td><input name="text" value="'.$f['text'].'" type="text" size="50"></td></tr>
                <tr><td>Модератор</td><td><input name="moder" value="'.$f['moder'].'" type="text" size="20"></td></tr>
                <tr><td>Форум админов</td><td><input name="cl" type="checkbox" value="1"'; if($f['clan']==1) echo'checked';
				echo'></td></tr>';

                echo'<tr><td>Только для клана:</td><td><select name="clan">';
                $res=myquery("select clan_id, nazv from game_clans where clan_id='".$f['clan']."' and clan_id!='1' limit 1");
                if (mysql_num_rows($res))
                {
					$o=mysql_fetch_array($res);
					echo'<option value='.$o['clan_id'].'>'.$o['nazv'].'</option>';
                }
                else echo'<option></option>';



                $res=myquery("select clan_id, nazv from game_clans where raz='0' and clan_id!='1'");
                while($o=mysql_fetch_array($res))
                {
					echo '<option value='.$o['clan_id'].'>'.$o['nazv'].'</option>';
                }
                echo'</select></td></tr>

                <tr><td>&nbsp;</td><td></td></tr>
                <tr><td><input name="save" type="submit" value="Сохранить"></td></tr>
                <input name="save" type="hidden" value="">
                </table>
                </form>';
                }
                else
                {
                echo'<center>Сохранено!<br><br>';
				if (isset($cl) and $cl='1') $clan='1';

				$up=myquery("update forum_kat set main_id='$main_id', name='$name',text='$text',moder='$moder',clan='$clan' where id='$kat'");

                $res=myquery("select id,name from forum_main where id='$main_id' limit 1");
                $o=mysql_fetch_array($res);

				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 'Изменил категорию форума: <b>".$o['name']."</b>.<br> Установил название:".$name."<br>
				 Текст: ".$text."<br>
				 Модератора: ".$moder."<br>
				 Для клана: ".$clan."<br>
				 ',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
				echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=forum">';
                }


        }




	if (isset($kateg))
    {
        if (!isset($save))
                {
                echo'<center><form action="" method="post">
                <table border="0" width="100%">
                <tr><td>Категория</td><td>';

                echo'<select name="main_id">';
                $res=myquery("select id,name from forum_main order by id");
                while($o=mysql_fetch_array($res))
                {
                echo '<option value="'.$o['id'].'">'.$o['name'].'</option>';
                }
                echo'</select></td></tr>';

                echo'</td></tr>
                <tr><td>Название</td><td><input name="name" value="" type="text" size="50"></td></tr>
                <tr><td>Текст</td><td><input name="text" value="" type="text" size="50"></td></tr>
                <tr><td>Модератор</td><td><input name="moder" value="" type="text" size="20"></td></tr>
                <tr><td>Форум админов</td><td><input name="cl" type="checkbox" value="1"></td></tr>';

                echo'<tr><td>Только для клана:</td><td><select name="clan">';
                echo '<option></option>';
                $res=myquery("select clan_id, nazv from game_clans where raz='0' and clan_id!='1'");
                while($o=mysql_fetch_array($res))
                {
                echo '<option value='.$o['clan_id'].'>'.$o['nazv'].'</option>';
                }
                echo'</select></td></tr>
                <tr><td>&nbsp;</td><td></td></tr>
                <tr><td><input name="save" type="submit" value="Добавить"></td></tr>
                <input name="save" type="hidden" value="">
                </table>
                </form>';
                }
                else
                {
					echo'<center>Сохранено!';
					if (isset($cl) and $cl='1') $clan='1';
					$up=myquery("INSERT INTO forum_kat (main_id,name,text,moder,clan) VALUES ('$main_id','$name','$text','$moder','$clan')");
					echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=forum">';
					$res=myquery("select id,name from forum_main where id='$main_id' limit 1");
					$o=mysql_fetch_array($res);

					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
					 VALUES (
					 '".$char['name']."',
					 'Добавил новую категорию форума: <b>".$o['name']."</b>.<br> Установил название:".$name."<br>
					 Текст: ".$text."<br>
					 Модератора: ".$moder."<br>
					 Для клана: ".$clan."<br>
					 ',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
					echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=forum">';
                }


        }




if (!isset($kat) and !isset($kateg))
{
    $select=myquery("select * from forum_main order by id");
    while ($row=mysql_fetch_array($select))
    {
        echo '<ul>'.$row['name'];
        $sel=myquery("select * from forum_kat where main_id='".$row['id']."' order by id");
        while($f=mysql_fetch_array($sel))
        {
			echo '<li><a href="?opt=main&option=forum&kat='.$f['id'].'">'.$f['name'].'</a><br>';
        }
        echo '</ul>';
    }
}
echo'<hr><center><a href="?opt=main&option=forum&kateg">Добавить категорию</a> | <a href="?opt=main&option=forum">Главная</a>';
}

if (function_exists("save_debug")) save_debug(); 

?>