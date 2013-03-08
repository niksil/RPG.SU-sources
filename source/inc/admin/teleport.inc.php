<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['teleport'] >= 1 AND ($char['clan_id']==1 OR $char['map_name']==18 OR $char['map_name']==5))
{
    $result=myquery("select map_name, map_xpos, map_ypos from game_users_map where user_id='$user_id' LIMIT 1");
    list($map_name,$map_xpos,$map_ypos)=mysql_fetch_array($result);
    if (!isset($teleport_submit) AND !isset($teleport_town))
    {
        echo'<center>
        <form action="" method="post" autocomplete="off">
        <table border="0" width="500"><tr bgcolor="#006699" align="center"><td><font size="1" face="Verdana" color="#000000">Карта</a></td><td><font size="1" face="Verdana" color="#000000">Позиция "Х"</a></td><td><font size="1" face="Verdana" color="#000000">Позиция "Y"</a></td><td><font size="1" face="Verdana" color="#000000">Действие</a></td></tr>
        <tr bgcolor="#333333" align="center"><td>';

        if ($adm['teleport'] == 2)
        {
            echo'<select name="map">';
            $result = myquery("SELECT * FROM game_maps ORDER BY name");
            while($map=mysql_fetch_array($result))
            {
                echo '<option value='.$map['id'].'';
                if ($char['map_name']==$map['id']) echo ' SELECTED';
                echo '>'.$map['name'].'</option>';
            }
            echo '</select>';
        }

        echo'</td><td><input name="new_map_xpos" type="text" value="'.$map_xpos.'" size="2" maxlength="2"></td><td><input name="new_map_ypos" type="text" value="'.$map_ypos.'" size="2" maxlength="2"></td><td><input name="teleport_submit" type="submit" value="Телепортироваться"></td></tr>
        </table>
        <br />
        <br />
        <br />
        Телепорт к городу:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select name="sel_town">';
        if ($adm['teleport'] < 2)
        {
            $sel = myquery("SELECT game_gorod.town,game_gorod.rustown,game_map.name AS map_id,game_map.xpos,game_map.ypos,game_maps.name FROM game_gorod,game_map,game_maps WHERE game_map.name=$map_name AND game_map.to_map_name=0 AND game_gorod.rustown<>'' AND game_map.town=game_gorod.town AND game_maps.id=game_map.name ORDER BY BINARY game_gorod.rustown");
        }
        else
        {
            $sel = myquery("SELECT game_gorod.town,game_gorod.rustown,game_map.name AS map_id,game_map.xpos,game_map.ypos,game_maps.name FROM game_gorod,game_map,game_maps WHERE game_gorod.rustown<>'' AND game_map.to_map_name=0 AND game_map.town=game_gorod.town AND game_maps.id=game_map.name ORDER BY BINARY game_gorod.rustown");
        }
        while ($town = mysql_fetch_array($sel))
        {
            echo '<option value="'.$town['town'].'">'.$town['rustown'].' ('.$town['name'].' '.$town['xpos'].','.$town['ypos'].')</option>';
        }
        echo'
        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input name="teleport_town" type="submit" value="Телепортироваться к городу">
        </form>';
    }
    else
    {
        echo'Ты '.echo_sex('телепортировался','телепортировалась').'';
        if (!isset($map) OR $adm['teleport']<2) $map=$map_name;
        if (isset($teleport_submit))
        {
            $result=myquery("update game_users_map set map_name='$map', map_xpos='$new_map_xpos',map_ypos='$new_map_ypos' where user_id='$user_id'");
        }
        elseif (isset($teleport_town))
        {
            $map = mysql_fetch_array(myquery("SELECT xpos,ypos,name FROM game_map WHERE town=".$_POST['sel_town']." AND to_map_name=0"));
            $result=myquery("update game_users_map set map_name='".$map['name']."', map_xpos='".$map['xpos']."',map_ypos='".$map['ypos']."' where user_id='$user_id'");
        }
        echo '<meta http-equiv="refresh" content="1;url=admin.php?option=teleport&opt=main">';
    }
}

if (function_exists("save_debug")) save_debug(); 

?>