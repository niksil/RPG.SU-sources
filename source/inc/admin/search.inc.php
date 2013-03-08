<?
if ($adm['search_items'] >= 1)
{
?>
	<script type="text/javascript">
	/* URL to the PHP page called for receiving suggestions for a keyword*/
	var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
	var startSearch = 1;
	</script>
	<?
    echo '<link href="suggest/suggest.css" rel="stylesheet" type="text/css">';
    echo '<script type="text/javascript" src="suggest/suggest.js"></script>';
    echo '<div id="content" onclick="hideSuggestions();"><center>Поиск предметов:</center><br><br>';
    echo '<center><font size="1" face="Verdana" color="#ffffff">Поиск: <input id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
	<input name="" type="button" value="Найти" type="text" value="" size="20" maxlength="40" onClick="location.href=\'admin.php?opt=main&option=search&name_v=\'+document.getElementById(\'keyword\').value"></div><script>init();</script>';

    if (isset($item_name))
        $name_v = $item_name;

    if ($adm['search_items'] == 1)
    {
        if (isset($_POST['searchmap']))
        {
            $select = myquery("SELECT * FROM game_items WHERE map_name=" . $_POST['map'] .
                " AND user_id=0 ORDER BY user_id");
            if (mysql_num_rows($select))
            {
                list($namemap) = mysql_fetch_array(myquery(
                    "SELECT name FROM game_maps WHERE id=" . $_POST['map'] . ""));
                echo '<br><hr><br><b><font size="3" color="#bbbbbb">Предметы на карте ' . $namemap .
                    '</font></b><br><br>';
                echo '<table>';
                while ($user = mysql_fetch_array($select))
                {
                    echo '<tr><td><img src="http://' . IMG_DOMAIN . '/item/' . $user['img'] .
                        '.gif" width="30" height="30"></td><td>' . $user['ident'] .
                        '</td><td>Лежит на карте: ' . $namemap . ' x-' . $user['map_xpos'] . ', y-' . $user
                        ['map_ypos'] . '</td></tr>';
                }
                echo '</table>';
            }
        }
        elseif (isset($_REQUEST['searchuser']))
        {
            echo '<br><br><center><b><font face="Verdana" size=2>Предметы игрока: ' . $_POST
                ['user'] . '</b></center><SCRIPT language=javascript src="http://' . DOMAIN .
                '/combat/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 100; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
            $prov = myquery("select * from game_users where name='" . $_POST['user'] .
                "' limit 1");
            $user = mysql_fetch_array($prov);
            echo '<form action="" method="post">';
            echo '<table cellpadding="0" cellspacing="5" border="0">';
            echo '<tr><td><br>Надетые предметы:</td></tr><tr><td>';
            $sel = myquery("select * from game_items where user_id='" . $user['user_id'] .
                "' and used!='' and type!='wm'");
            while ($it = mysql_fetch_array($sel))
            {
?><a onmousemove=movehint(event) onmouseover="showhint('<?php
                echo '<center><font color=#0000FF>' . $it['ident'] . '</font>';
                if ($it['curse'] != '')
                {
                    echo '<hr><font color=#000000>' . $it['curse'] . '</font>';
                }
?>','<?php echo '<font color=000000>';
                if ($it['race'] <> '')
                    echo 'Только для расы: <font color=ff0000><b>' . $it['race'] . '</b></font><br>';
                if ($it["type"] != 'wm')
                    echo 'Тип: ' . $it["type"] . '<br>';
                if ($it["curse"] != '')
                    echo $it["curse"] . '<br>';
                if ($it['type'] == 'Оружие' or $it['type'] == 'Артефакт')
                    echo 'Урон: ' . $it['indx'] . '&nbsp;&plusmn;&nbsp;' . $it['deviation'] .
                        '<br>Прочность: ' . $it['item_uselife'] . '%<br>';
                if ($it['type'] == 'Щит')
                    echo 'Защита: ' . $it['indx'] . '<br>';
                echo 'Вес: ' . $it['weight'] . '<br><br>Предмет повышает:<br>';
                if ($it['dstr'] < > '0')
                    echo 'Силу на: ' . $it['dstr'] . '<br>';
                if ($it['dntl'] < > '0')
                    echo 'Интелект на: ' . $it['dntl'] . '<br>';
                if ($it['dpie'] < > '0')
                    echo 'Ловкость на: ' . $it['dpie'] . '<br>';
                if ($it['dvit'] < > '0')
                    echo 'Защиту на: ' . $it['dvit'] . '<br>';

                if ($it['ddex'] < > '0')
                    echo 'Выносливость на: ' . $it['ddex'] . '<br>';
                if ($it['dspd'] < > '0')
                    echo 'Мудрость на: ' . $it['dspd'] . '<br>';
                if ($it['hp_p'] < > '0')
                    echo 'Жизни на: ' . $it['hp_p'] . '<br>';
                if ($it['mp_p'] < > '0')
                    echo 'Ману на: ' . $it['mp_p'] . '<br>';
                if ($it['stm_p'] < > '0')
                    echo 'Энергию на: ' . $it['stm_p'] . '<br>';

                if ($it['cc_p'] < > '0')
                    echo 'Перенос предметов на: ' . $it['cc_p'] . '<br>';
                if ($it['modif'] < > '')
                    echo '<br><font color=ff0000><b>Модифицирован:</b></font> ' . $it['modif'];
?>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?
                echo '<img src="http://' . IMG_DOMAIN . '/item/' . $it['img'] .
                    '.gif" width="30" height="30" border="0" align="top"></a><input type="checkbox" name="array_item[]" value="' .
                    $it['id'] . '">&nbsp;&nbsp;';
            }
            echo '</td></tr>';

            echo '<tr><td><br>Предметы в рюкзаке:</td></tr><tr><td>';
            $sel = myquery("select * from game_items where user_id='" . $user['user_id'] .
                "' and used='' and type!='wm'");
            while ($it = mysql_fetch_array($sel))
            {
?><a onmousemove=movehint(event) onmouseover="showhint('<?php
                echo '<center><font color=#0000FF>' . $it['ident'] . '</font>';
                if ($it['curse'] != '')
                {
                    echo '<hr><font color=#000000>' . $it['curse'] . '</font>';
                }
?>','<?php
                echo '<font color=000000>';
                if ($it['race'] < > '')
                    echo 'Только для расы: <font color=ff0000><b>' . $it['race'] . '</b></font><br>';
                if ($it["type"] != 'wm')
                    echo 'Тип: ' . $it["type"] . '<br>';
                if ($it["curse"] != '')
                    echo $it["curse"] . '<br>';
                if ($it['indx'] < > '0')
                    if ($it['type'] == 'Оружие' or $it['type'] == 'Артефакт')
                        echo 'Урон: ' . $it['indx'] . '&nbsp;&plusmn;&nbsp;' . $it['deviation'] .
                            '<br>Прочность: ' . $it['item_uselife'] . '%<br>';
                if ($it['type'] == 'Щит')
                    echo 'Защита: ' . $it['indx'] . '<br>';
                echo 'Вес: ' . $it['weight'] . '<br><br>Предмет повышает:<br>';
                if ($it['dstr'] < > '0')
                    echo 'Силу на: ' . $it['dstr'] . '<br>';
                if ($it['dntl'] < > '0')
                    echo 'Интелект на: ' . $it['dntl'] . '<br>';
                if ($it['dpie'] < > '0')
                    echo 'Ловкость на: ' . $it['dpie'] . '<br>';
                if ($it['dvit'] < > '0')
                    echo 'Защиту на: ' . $it['dvit'] . '<br>';
                if ($it['ddex'] < > '0')
                    echo 'Выносливость на: ' . $it['ddex'] . '<br>';
                if ($it['dspd'] < > '0')
                    echo 'Мудрость на: ' . $it['dspd'] . '<br>';
                if ($it['hp_p'] < > '0')
                    echo 'Жизни на: ' . $it['hp_p'] . '<br>';
                if ($it['mp_p'] < > '0')
                    echo 'Ману на: ' . $it['mp_p'] . '<br>';
                if ($it['stm_p'] < > '0')
                    echo 'Энергию на: ' . $it['stm_p'] . '<br>';
                if ($it['cc_p'] < > '0')
                    echo 'Перенос предметов на: ' . $it['cc_p'] . '<br>';
                if ($it['modif'] < > '')
                    echo '<br><font color=ff0000><b>Модифицирован:</b></font> ' . $it['modif'];
?>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?php
                echo '<img src="http://' . IMG_DOMAIN . '/item/' . $it['img'] .
                    '.gif" width="30" height="30" border="0" align="top"></a><input type="checkbox" name="array_item[]" value="' .
                    $it['id'] . '">&nbsp;&nbsp;';
            }
            echo '<tr><td><br>Ресурсы:</td></tr><tr><td>';
            $sel = myquery("select * from craft_resource_user where user_id='" . $user[
                'user_id'] . "'");
            while ($it = mysql_fetch_array($sel))
            {
                $ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=" . $it
                    ['res_id'] . ""));
?><a onmousemove=movehint(event) onmouseover="showhint('<?php
                echo '<center><font color=#0000FF>' . $ress['name'] . '</font>';
?>','<?php
                echo '<font color=000000>';
                echo 'Вес: ' . ($ress['weight'] * $it['col']) . '<br>';
?>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?php
                echo '<img src="http://' . IMG_DOMAIN . '/item/resources/' . $ress['img2'] .
                    '.gif" width="30" height="30" border="0" align="top"></a>&nbsp;&nbsp;';
            }
            echo '</td></tr>';
            echo '</table><input type="hidden" name="usrid" value="' . $user['user_id'] .
                '">';
            echo '<br><br><input type="submit" name="delete" value="Удалить отмеченные предметы игрока">';
            echo '<br><br><input type="submit" name="take" value="Удалить отмеченные предметы игрока c возвратом денег">';
            echo '</form>';
        }
        elseif (isset($_REQUEST['delete']) or isset($_REQUEST['take']))
        {
            echo '<center>Вы выбрали следующие предметы';
            echo '<form action="" method="POST">';
            $ar = $_REQUEST['array_item'];
            for ($i = 0; $i < sizeof($ar); $i++)
            {

                $selit = myquery("SELECT * FROM game_items WHERE user_id=" . $_REQUEST['usrid'] .
                    " AND id = " . $ar[$i] . "");
                if (mysql_num_rows($selit))
                {
                    $it = mysql_fetch_array($selit);
                    echo '<br><img src="http://' . IMG_DOMAIN . '/item/' . $it['img'] .
                        '.gif" width="30" height="30" border="0" align="top">' . $it['ident'] . '';
                    echo '<input type="hidden" name="array_items[]" value="' . $it['id'] . '">';
                }
            }
            echo '<br><br><input type="submit" name="delete_items" value="Удалить отмеченные предметы игрока">';
            echo '<br><br><input type="submit" name="take_items" value="Удалить отмеченные предметы игрока c возвратом денег">';
            echo '</form>';
        }
        elseif (isset($_POST['delete_items']) or isset($_POST['take_items']))
        {
            include ("$dirclass/class_item.php");
            $ar_it = $_REQUEST['array_items'];
            for ($i = 0; $i < sizeof($ar_it); $i++)
            {
                $item_obj = new Inventory();
                $deleteitem = $ar_it[$i];
                list($usrid, $sub, $ident) = mysql_fetch_array(myquery(
                    "SELECT user_id,used,ident FROM game_items WHERE id=$deleteitem"));
                list($usertime) = mysql_fetch_array(myquery(
                    "SELECT last_active FROM game_users_active WHERE user_id=$usrid"));
                $ar = array();
                $ar['user_id'] = $usrid;
                $ar['map_name'] = 0;
                $item_obj->char = $ar;
                $item_obj->item = $deleteitem;
                $item_obj->used = $sub;
                $item_obj->user_time = $usertime;

                $item_obj->Init();
                $item_obj->UnEquip();
                $item_obj->DeleteItem();
                echo '<br>Предмет <b>' . $ident . '</b> успешно удален';
                if (isset($_POST['take_items']))
                {
                    list($item_cost) = mysql_fetch_array(myquery(
                        "SELECT item_cost FROM game_items_factsheet WHERE name='" . $ident . "'"));
                    myquery("UPDATE game_users SET GP=GP+$item_cost,CW=CW+" . ($item_cost *
                        money_weight) . " WHERE user_id=$usrid");
                }
            }
            echo '<br>Удаление закончено.';
        }
        elseif (isset($name_v)) //общий поиск
        {
            $select = myquery("SELECT * FROM game_items WHERE ident='" . $name_v .
                "' AND user_id!=0 ORDER BY user_id");
            if (mysql_num_rows($select))
            {
                echo '<br><hr><br><b><font size="3" color="#bbbbbb">Предметы у игроков</font></b><br><br>';
                echo '<table>';
                $nom = 0;
                while ($user = mysql_fetch_array($select))
                {
                    $sel = myquery("SELECT name,clan_id FROM game_users WHERE user_id='" . $user[
                        'user_id'] . "'");
                    if (!mysql_num_rows($sel))
                        $sel = myquery("SELECT name,clan_id FROM game_users_archive WHERE user_id='" . $user
                            ['user_id'] . "'");
                    list($name, $clan_id) = mysql_fetch_array($sel);
                    $nom++;
                    if ($nom == 6)
                        $nom = 1;
                    if ($nom == 1)
                        echo '<tr>';
                    echo '<td>';
                    echo '<font size="2" color="#bbbbbb">';
                    if ($clan_id != '0')
                        echo '<img src="http://' . IMG_DOMAIN . '/clan/' . $clan_id . '.gif"> ';
                    echo '<a href="http://' . DOMAIN . '/view/?name=' . $name .
                        '" target="_blank"><img src="http://' . IMG_DOMAIN .
                        '/nav/i.gif" border=0 alt="Инфо"></a>';
                    echo '' . $name . '</font><br>';
                    echo '</td>';
                    if ($nom == 5)
                        echo '</tr>';
                }
                echo '</table>';
            }

            $select = myquery("SELECT * FROM game_items WHERE ident='" . $name_v .
                "' AND user_id=0 ORDER BY user_id");
            if (mysql_num_rows($select))
            {
                echo '<br><hr><br><b><font size="3" color="#bbbbbb">Предметы на земле</font></b><br><br>';
                echo '<table>';
                while ($user = mysql_fetch_array($select))
                {
                    echo '<tr><td>';
                    echo '<font size="2" color="#bbbbbb">';
                    echo 'Лежит на карте: ' . @mysql_result(@myquery(
                        "SELECT name FROM game_maps WHERE id=" . $user['map_name'] . ""), 0, 0) . ' x-' .
                        $user['map_xpos'] . ', y-' . $user['map_ypos'] . '';
                    echo '</font><br>';
                    echo '</td></tr>';
                }
                echo '</table>';
            }

            $select_old_items = myquery(
                "SELECT DISTINCT town FROM game_items_old WHERE ident='" . $name_v . "'");
            if (mysql_num_rows($select_old_items))
            {
                echo '<br><hr><br><b><font size="3" color="#bbbbbb">Предметы на рынке</font></b><br><br>';
                {
                    echo '<table>';
                    while (list($town) = mysql_fetch_array($select_old_items))
                    {
                        $select = myquery("SELECT * FROM game_items_old WHERE ident='" . $name_v .
                            "' AND town='" . $town . "'");
                        $kol_items = mysql_num_rows($select);
                        $select = myquery("SELECT rustown FROM game_gorod WHERE town='" . $town . "'");
                        list($rustown) = mysql_fetch_array($select);
                        if ($kol_items != 0)
                        {
                            echo '<tr><td>';
                            echo '<font size="2" color="#bbbbbb">';
                            echo '' . $kol_items . ' предметов на рынке в городе: ' . $rustown . '.<br>';
                            echo '</font><br>';
                            echo '</td></tr>';
                        }
                    }
                    echo '</table>';
                }
            }
        }
        else
        {
            echo '<br><hr><br><center><table cellpadding="10"><tr>
            
            <td>Показать предметы, лежащие на карте:<form name="smap" method="POST"><table>';
            $selmap = myquery("SELECT id,name FROM game_maps ORDER BY BINARY name");
            while ($map = mysql_fetch_array($selmap))
            {
                echo '<tr><td><input type="radio" name="map" value="' . $map['id'] . '">' . $map
                    ['name'] . '</td></tr>';
            }
            echo '</table><input type="submit" name="searchmap" value="Выполнить поиск"></form></td>
            
            <td valign="top">Показать все предметы, находящиеся у игрока:<form name="suser" method="POST"><input type="text" size="50" name="user"><br><br><input type="submit" name="searchuser" value="Выполнить поиск"></form></td></tr></table>';
        }
    }
}
?>