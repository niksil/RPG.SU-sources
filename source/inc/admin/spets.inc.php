<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['spets'] >= 1)
{
    if(isset($new))
    {
        echo'<center><b>���������� ������ ������</b><br><br>';
        echo'�� ������ �������� �����: <br>
		<a href="admin.php?opt=main&option=spets&new&tp_new=0">����</a>, <a href="admin.php?opt=main&option=spets&new&tp_new=1">����</a>, <a href="admin.php?opt=main&option=spets&new&tp_new=3">������</a>, <a href="admin.php?opt=main&option=spets&new&tp_new=2">���������</a>, <a href="admin.php?opt=main&option=spets&new&tp_new=4">�������</a>, <a href="admin.php?opt=main&option=spets&new&tp_new=5">������</a>, <a href="admin.php?opt=main&option=spets&new&tp_new=6">���</a>, <a href="admin.php?opt=main&option=spets&new&tp_new=7">�����</a>, <a href="admin.php?opt=main&option=spets&new&tp_new=8">���������</a></center>';

        if (isset($tp_new))
        {
            if ($tp_new==0)
               $type='����';
            elseif ($tp_new==3)
                $type='������';
            elseif ($tp_new==4)
                $type='�������';
            elseif ($tp_new==6)
                $type='���';
            elseif ($tp_new==8)
                $type='���������';
            elseif ($tp_new==1)
                $type='����';
            elseif ($tp_new==2)
                $type='���������';
            elseif ($tp_new==5)
                $type='������';
            elseif ($tp_new==7)
                $type='�����';

            if (!isset($save))
            {
                echo'<center><form action="" method="post">
                <table border="0">
                <tr><td>���:</td><td><input name="type" value="'.$type.'" type="text" size="20" readonly="true"></td></tr>
                <tr><td>�������:</td><td><input name="level" value="" type="text" size="2"></td></tr>
                <tr><td>�������� (��������, ������������):</td><td><input name="mode" value="" type="text" size="40"></td></tr>';

                echo'<tr><td><font size=2 color=#80FF00><b>����, ������ ��� �������:</b></font></td><td></td></tr>
                <tr><td align="right">������:</td><td><input name="indx" value="" type="text" size="4">&plusmn;<input name="deviation" value="" type="text" size="4"></td></tr>
                <tr><td align="right">����:</td><td><input name="indx_mp" value="" type="text" size="4">&plusmn;<input name="indx_mp_deviation" value="" type="text" size="4"></td></tr>
                <tr><td align="right">�������:</td><td><input name="indx_stm" value="" type="text" size="4">&plusmn;<input name="indx_stm_deviation" value="" type="text" size="4"></td></tr>
                <tr><td>�������� ������</td><td><select name="sv"><option>�����</option><option>������</option><option>�������</option></select></td></tr>        ';
                echo'<tr><td><font size=2 color=#0080FF><b>�������:</b></font></td><td></td></tr>';
                echo '<tr><td align="right">����:</td><td><input name="mana" value="" type="text" size="4">&plusmn;<input name="mana_deviation" value="" type="text" size="4"></td></tr>';
                echo '<tr><td align="right">��������:</td><td><input name="hp" value="" type="text" size="4">&plusmn;<input name="hp_deviation" value="" type="text" size="4"></td></tr>';
                echo '<tr><td align="right">�������:</td><td><input name="stm" value="" type="text" size="4">&plusmn;<input name="stm_deviation" value="" type="text" size="4"></td></tr>';

                echo '<tr><td>&nbsp;</td></tr>';

                echo
                        '<tr><td><input name="save" type="submit" value="��������"></td></tr>
                <input name="save" type="hidden" value="">
                </table>
                </form>';
            }
            else
            {
                if(!isset($indx)) $indx='0';
                if(!isset($deviation)) $deviation='0';
                if(!isset($indx_mp)) $indx_mp='0';
                if(!isset($indx_mp_deviation)) $indx_mp_deviation='0';
                if(!isset($indx_stm)) $indx_stm='0';
                if(!isset($indx_stm_deviation)) $indx_stm_deviation='0';
                if(!isset($level)) $level='0';
                if(!isset($mana)) $mana='0';
                if(!isset($hp)) $hp='0';
                if(!isset($stm)) $stm='0';
                if(!isset($mana_deviation)) $mana_deviation='0';
                if(!isset($hp_deviation)) $hp_deviation='0';
                if(!isset($stm_deviation)) $stm_deviation='0';
                if(!isset($mode)) $mode='';

                $name = ''.$type.', '.$level.' �������';
                $check = myquery("SELECT * FROM game_spets WHERE name='$name'");
                If (mysql_num_rows($check)>0)
                   echo '<br><center><font color=ff0000 size=2 face=verdana><b>����� '.$name.' ��� ������ � ���� ������! ����� ����� �������� ������!</b></font></center>';
                else
                {
                    $war = -5;
                    $music = -5;
                    $cook = -5;
                    $art = -5;
                    $explor = -5;
                    $craft = -5;
                    $card = -5;
                    $pet = -5;
                    $unknow = -5;
                    $tip=-1;

                    $img = $sv;

                    if ($type=='����')
                    {
                        $war = $level;
                        $tip = 0;
                        $seluser=myquery("(SELECT user_id FROM game_users WHERE skill_war>=$level) UNION (SELECT user_id FROM game_users_archive WHERE skill_war>=$level)");
                        while (list($usrid) = mysql_fetch_array($seluser))
                        {
                            $up=myquery("INSERT INTO game_spets_item
                            (user_id,name,indx,deviation,mode,ident,weight,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                            VALUES ('$usrid','$name','$indx','$deviation','$mode','0','0','$img','$mana','$hp','$stm','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");
                        }
                    }
                    elseif ($type=='����')
                    {
                        $music = $level;
                        $tip = 1;
                        $seluser=myquery("(SELECT user_id FROM game_users WHERE skill_music>=$level) UNION (SELECT user_id FROM game_users_archive WHERE skill_music>=$level)");
                        while (list($usrid) = mysql_fetch_array($seluser))
                        {
                            $up=myquery("INSERT INTO game_spets_item
                            (user_id,name,indx,deviation,mode,ident,weight,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                            VALUES ('$usrid','$name','$indx','$deviation','$mode','0','0','$img','$mana','$hp','$stm','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");
                        }
                    }
                    elseif ($type=='���������')
                    {
                        $cook = $level;
                        $tip = 2;
                        $seluser=myquery("(SELECT user_id FROM game_users WHERE skill_cook>=$level) UNION (SELECT user_id FROM game_users_archive WHERE skill_cook>=$level)");
                        while (list($usrid) = mysql_fetch_array($seluser))
                        {
                            $up=myquery("INSERT INTO game_spets_item
                            (user_id,name,indx,deviation,mode,ident,weight,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                            VALUES ('$usrid','$name','$indx','$deviation','$mode','0','0','$img','$mana','$hp','$stm','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");
                        }
                    }
                    elseif ($type=='������')
                    {
                        $art = $level;
                        $tip = 3;
                        $seluser=myquery("(SELECT user_id FROM game_users WHERE skill_art>=$level) UNION (SELECT user_id FROM game_users_archive WHERE skill_art>=$level)");
                        while (list($usrid) = mysql_fetch_array($seluser))
                        {
                            $up=myquery("INSERT INTO game_spets_item
                            (user_id,name,indx,deviation,mode,ident,weight,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                            VALUES ('$usrid','$name','$indx','$deviation','$mode','0','0','$img','$mana','$hp','$stm','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");
                        }
                    }
                    elseif ($type=='�������')
                    {
                        $explor = $level;
                        $tip = 4;
                        $seluser=myquery("(SELECT user_id FROM game_users WHERE skill_explor>=$level) UNION (SELECT user_id FROM game_users_archive WHERE skill_explor>=$level)");
                        while (list($usrid) = mysql_fetch_array($seluser))
                        {
                            $up=myquery("INSERT INTO game_spets_item
                            (user_id,name,indx,deviation,mode,ident,weight,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                            VALUES ('$usrid','$name','$indx','$deviation','$mode','0','0','$img','$mana','$hp','$stm','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");
                        }
                    }
                    elseif ($type=='������')
                    {
                        $craft = $level;
                        $tip = 5;
                        $seluser=myquery("(SELECT user_id FROM game_users WHERE skill_craft>=$level) UNION (SELECT user_id FROM game_users_archive WHERE skill_craft>=$level)");
                        while (list($usrid) = mysql_fetch_array($seluser))
                        {
                            $up=myquery("INSERT INTO game_spets_item
                            (user_id,name,indx,deviation,mode,ident,weight,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                            VALUES ('$usrid','$name','$indx','$deviation','$mode','0','0','$img','$mana','$hp','$stm','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");
                        }
                    }
                    elseif ($type=='���')
                    {
                        $card = $level;
                        $tip = 6;
                        $seluser=myquery("(SELECT user_id FROM game_users WHERE skill_card>=$level) UNION (SELECT user_id FROM game_users_archive WHERE skill_card>=$level)");
                        while (list($usrid) = mysql_fetch_array($seluser))
                        {
                            $up=myquery("INSERT INTO game_spets_item
                            (user_id,name,indx,deviation,mode,ident,weight,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                            VALUES ('$usrid','$name','$indx','$deviation','$mode','0','0','$img','$mana','$hp','$stm','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");
                        }
                    }
                    elseif ($type=='�����')
                    {
                        $pet = $level;
                        $tip = 7;
                        $seluser=myquery("(SELECT user_id FROM game_users WHERE skill_pet>=$level) UNION (SELECT user_id FROM game_users_archive WHERE skill_pet>=$level)");
                        while (list($usrid) = mysql_fetch_array($seluser))
                        {
                            $up=myquery("INSERT INTO game_spets_item
                            (user_id,name,indx,deviation,mode,ident,weight,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                            VALUES ('$usrid','$name','$indx','$deviation','$mode','0','0','$img','$mana','$hp','$stm','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");
                        }
                    }
                    elseif ($type=='���������')
                    {
                        $unknow = $level;
                        $tip = 8;
                        $seluser=myquery("(SELECT user_id FROM game_users WHERE skill_uknow>=$level) UNION (SELECT user_id FROM game_users_archive WHERE skill_uknow>=$level)");
                        while (list($usrid) = mysql_fetch_array($seluser))
                        {
                            $up=myquery("INSERT INTO game_spets_item
                            (user_id,name,indx,deviation,mode,ident,weight,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                            VALUES ('$usrid','$name','$indx','$deviation','$mode','0','0','$img','$mana','$hp','$stm','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");
                        }
                    }

                    $up=myquery("INSERT INTO game_spets
                    (name,indx,deviation,mode,war,music,cook,art,explor,craft,card,pet,unknow,img,mana,hp,stm,tip,mana_deviation,hp_deviation,stm_deviation,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation)
                    VALUES ('$name','$indx','$deviation','$mode','$war','$music','$cook','$art','$explor','$craft','$card','$pet','$unknow','$img','$mana','$hp','$stm','$tip','$mana_deviation','$hp_deviation','$stm_deviation','$indx_mp','$indx_mp_deviation','$indx_stm','$indx_stm_deviation')");

                    $log=myquery("INSERT INTO game_log_adm (adm,time,dei) VALUES ('".$char['name']."','".date("j.m.Y H:i")."','������� �����:  '".$name."'");
                    echo'<br><br><center><font color=ff0000 size=2 face=verdana><b>�����: '.$name.' ��������<b></font>';
					echo '<meta http-equiv="refresh" content="1;url=admin.php?option=spets&opt=main">';
                }//If (mysql_num_rows($check)>0)
            }//if (!isset($save))
        }//if (isset($tp_new))

        $img_fon = 'http://'.IMG_DOMAIN.'/race_table/human/table';
        echo'</td><td background="'.$img_fon.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img_fon.'_lb.gif"></td><td background="'.$img_fon.'_mb.gif"></td><td width="1" height="1"><img src="'.$img_fon.'_rb.gif"></td></tr></table>';
        {if (function_exists("save_debug")) save_debug(); exit;}
    }//        if(isset($new))

	echo'<CENTER><a href="admin.php?opt=main&option=spets&new">�������� �����</a>';

	echo'<br><hr><a href="admin.php?opt=main&option=spets&tip=0">����</a> |
	<a href="admin.php?opt=main&option=spets&tip=1">����</a> |
	<a href="admin.php?opt=main&option=spets&tip=2">���������</a> |
	<a href="admin.php?opt=main&option=spets&tip=3">������</a> |
	<a href="admin.php?opt=main&option=spets&tip=4">�������</a> |
	<a href="admin.php?opt=main&option=spets&tip=5">������</a> |
	<a href="admin.php?opt=main&option=spets&tip=6">���</a> |
	<a href="admin.php?opt=main&option=spets&tip=7">�����</a> |
	<a href="admin.php?opt=main&option=spets&tip=8">���������</a>';

    if (isset($tip) AND !isset($new))
    {
        $spets=myquery("select * from game_spets where tip='$tip' order by id ASC");
        echo'<hr><table border=0><tr><td rowspan="2"><b><font color=#FFFFFF>���</td><td rowspan="2"><b><font color=#FFFFFF>��������</td><td rowspan="2"><b><font color=#FFFFFF>�������</td><td rowspan="2"><b><font color=#FFFFFF>���</td><td colspan="3" align="center"><b><font color=#FFFFFF>����</td><td colspan="3" align="center"><b><font color=#FFFFFF>������</td><td rowspan="2"></td></tr>
        <tr><td><b><font color=#FFFFFF>�����</td><td><b><font color=#FFFFFF>����</td><td><b><font color=#FFFFFF>�������</td><td><b><font color=#FFFFFF>����</td><td><b><font color=#FFFFFF>�����</td><td><b><font color=#FFFFFF>�������</td></td><td></tr>';
        while($item=mysql_fetch_array($spets))
        {
            if ($item['war']>0)
               $level = $item['war'];
            elseif ($item['music']>0)
                $level = $item['music'];
            elseif ($item['cook']>0)
                $level = $item['cook'];
            elseif ($item['art']>0)
                $level = $item['art'];
            elseif ($item['explor']>0)
                $level = $item['explor'];
            elseif ($item['craft']>0)
                $level = $item['craft'];
            elseif ($item['card']>0)
                $level = $item['card'];
            elseif ($item['pet']>0)
                $level = $item['pet'];
            elseif ($item['unknow']>0)
                $level = $item['unknow'];

            echo'<tr><td>'.$item['name'].'</td><td>'.$item['mode'].'</td><td>'.$level.'</td><td>'.$item['img'].'</td><td align="right"><font color=#FF80FF>'.$item['indx'].'&plusmn;'.$item['deviation'].'</td><td align="right"><font color=#FF00FF>'.$item['indx_mp'].'&plusmn;'.$item['indx_mp_deviation'].'</td><td align="right"><font color=#FF0080>'.$item['indx_stm'].'&plusmn;'.$item['indx_stm_deviation'].'</td><td align="right"><font color=#80FF80>'.$item['mana'].'&plusmn;'.$item['mana_deviation'].'</td><td align="right"><font color=#80FF00>'.$item['hp'].'&plusmn;'.$item['hp_deviation'].'</td><td align="right"><font color=#00FF00>'.$item['stm'].'&plusmn;'.$item['stm_deviation'].'</td>';
			echo'<td><a href="admin.php?opt=main&option=spets&edit='.$item['id'].'">�������������</a></td>';
			echo'<td><a href="admin.php?opt=main&option=spets&del='.$item['id'].'">�������</a></td>';
            echo'</tr>';
        }
        echo'</table>';

        $img_fon = 'http://'.IMG_DOMAIN.'/race_table/human/table';
        echo'</td><td background="'.$img_fon.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img_fon.'_lb.gif"></td><td background="'.$img_fon.'_mb.gif"></td><td width="1" height="1"><img src="'.$img_fon.'_rb.gif"></td></tr></table>';
        {if (function_exists("save_debug")) save_debug(); exit;}
    }

    if(isset($del) and $adm['spets'] >= 1)
    {
        $usr=myquery("select name,indx,deviation,mode,war,music,cook,art,explor,craft,card,pet,unknow,img,mana,tip from game_spets where id='$del'");
        if (mysql_num_rows($usr))
        {
            list($name,$indx,$deviation,$mode,$war,$music,$cook,$art,$explor,$craft,$card,$pet,$unknow,$img,$mana,$tip)=mysql_fetch_array($usr);
            if (!isset($delete))
            {
                echo'<center><form action="" method="post">'.$name.'&nbsp;&nbsp;<input name="delete" type="submit" value="�������">
                <input name="delete" type="hidden" value="">
                </form>';
            }
            else
            {
                $up=myquery("DELETE FROM game_spets where id='$del'");
                $up=myquery("DELETE FROM game_spets_item where name='$name'");

                $log=myquery("INSERT INTO game_log_adm (adm,time,dei) VALUES ('".$char['name']."','".date("j.m.Y H:i")."','������ �����:  '".$name."'");

                echo'<br><br><center><font color=ff0000 size=2 face=verdana><b>�����: '.$name.' ������<b></font>';
				echo '<meta http-equiv="refresh" content="1;url=admin.php?option=spets&opt=main">';
            }
        }

        $img_fon = 'http://'.IMG_DOMAIN.'/race_table/human/table';
        echo'</td><td background="'.$img_fon.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img_fon.'_lb.gif"></td><td background="'.$img_fon.'_mb.gif"></td><td width="1" height="1"><img src="'.$img_fon.'_rb.gif"></td></tr></table>';
        {if (function_exists("save_debug")) save_debug(); exit;}
    }

    if(isset($edit) and $adm['spets'] >= 1)
    {
        $usr=myquery("select name,indx,deviation,mode,war,music,cook,art,explor,craft,card,pet,unknow,img,mana,hp,stm,mana_deviation,hp_deviation,stm_deviation,tip,indx_mp,indx_mp_deviation,indx_stm,indx_stm_deviation from game_spets where id='$edit'");

        if (mysql_num_rows($usr))
        {
            list($name,$indx,$deviation,$mode,$war,$music,$cook,$art,$explor,$craft,$card,$pet,$unknow,$img,$mana,$hp,$stm,$mana_deviation,$hp_deviation,$stm_deviation,$tip,$indx_mp,$indx_mp_deviation,$indx_stm,$indx_stm_deviation)=mysql_fetch_array($usr);

            if ($tip == 0)
            {
                $level = $war;
            }
            elseif ($tip == 1)
            {
                $level = $music;
            }
            elseif ($tip == 2)
            {
                $level = $cook;
            }
            elseif ($tip == 3)
            {
                $level = $art;
            }
            elseif ($tip == 4)
            {
                $level = $explor;
            }
            elseif ($tip == 5)
            {
                $level = $craft;
            }
            elseif ($tip == 6)
            {
                $level = $card;
            }
            elseif ($tip == 7)
            {
                $level = $pet;
            }
            elseif ($tip == 8)
            {
                $level = $unknow;
            }
            if (!isset($save))
            {
                echo'<center><form action="" method="post">
                <table border="0">
                <tr><td>���:</td><td><input name="type" value="'.$name.'" type="text" size="20" readonly="true"></td></tr>
                <tr><td>�������:</td><td><input name="level" value="'.$level.'" type="text" size="2" readonly="true"></td></tr>
                <tr><td>��������:</td><td><input name="mode1" value="'.$mode.'" type="text" size="40"></td></tr>';

                echo'<tr><td><font size=2 color=#80FF00><b>����, ������ ��� �������:</b></font></td><td></td></tr>
                <tr><td align="right">������:</td><td><input name="indx1" value="'.$indx.'" type="text" size="4">&plusmn;<input name="deviation1" value="'.$deviation.'" type="text" size="4"></td></tr>
                <tr><td align="right">����:</td><td><input name="indx_mp1" value="'.$indx_mp.'" type="text" size="4">&plusmn;<input name="indx_mp_deviation1" value="'.$indx_mp_deviation.'" type="text" size="4"></td></tr>
                <tr><td align="right">�������:</td><td><input name="indx_stm1" value="'.$indx_stm.'" type="text" size="4">&plusmn;<input name="indx_stm_deviation1" value="'.$indx_stm_deviation.'" type="text" size="4"></td></tr>
                <tr><td>�������� ������</td><td><select name="sv">';

                if ($img=='�����')
                    echo '<option selected>';
                else
                    echo '<option>';
                echo '�����</option>';

                if ($img=='������')
                    echo '<option selected>';
                else
                    echo '<option>';
                echo '������</option>';

                if ($img=='�������')
                    echo '<option selected>';
                else
                    echo '<option>';
                echo '�������</option></select></td></tr>';

                echo'<tr><td><font size=2 color=#0080FF><b>�������:</b></font></td><td></td></tr>';
                echo '<tr><td align="right">����:</td><td><input name="mana1" value="'.$mana.'" type="text" size="4">&plusmn;<input name="mana_deviation1" value="'.$mana_deviation.'" type="text" size="4"></td></tr>';
                echo '<tr><td align="right">�����:</td><td><input name="hp1" value="'.$hp.'" type="text" size="4">&plusmn;<input name="hp_deviation1" value="'.$hp_deviation.'" type="text" size="4"></td></tr>';
                echo '<tr><td align="right">�������:</td><td><input name="stm1" value="'.$stm.'" type="text" size="4">&plusmn;<input name="stm_deviation1" value="'.$stm_deviation.'" type="text" size="4"></td></tr>';

                echo '<tr><td>&nbsp;</td></tr>';

                echo
                '<tr><td><input name="save" type="submit" value="���������"></td></tr>
                <input name="save" type="hidden" value="">
                </table>
                </form>';
            }
            else
            {
                if(!isset($indx)) $indx='0';
                if(!isset($deviation)) $deviation='0';
                if(!isset($indx_mp)) $indx_mp='0';
                if(!isset($indx_mp_deviation)) $indx_mp_deviation='0';
                if(!isset($indx_stm)) $indx_stm='0';
                if(!isset($indx_stm_deviation)) $indx_stm_deviation='0';
                if(!isset($mana)) $mana='0';
                if(!isset($hp)) $hp='0';
                if(!isset($stm)) $stm='0';
                if(!isset($mana_deviation)) $mana_deviation='0';
                if(!isset($hp_deviation)) $hp_deviation='0';
                if(!isset($stm_deviation)) $stm_deviation='0';
                if(!isset($mode)) $mode='';

                $img1 = $sv;

                $up=myquery("UPDATE game_spets SET
                indx='$indx1',deviation='$deviation1',mode='$mode1',img='$img1',mana='$mana1',hp='$hp1',stm='$stm1',mana_deviation='$mana_deviation1',hp_deviation='$hp_deviation1',stm_deviation='$stm_deviation1',indx_mp='$indx_mp1',indx_mp_deviation='$indx_mp_deviation1',indx_stm='$indx_stm1',indx_stm_deviation='$indx_stm_deviation1' where id='$edit'");
                $up=myquery("UPDATE game_spets_item SET
                indx='$indx1',deviation='$deviation1',mode='$mode1',img='$img1',mana='$mana1',hp='$hp1',stm='$stm1',mana_deviation='$mana_deviation1',hp_deviation='$hp_deviation1',stm_deviation='$stm_deviation1',indx_mp='$indx_mp1',indx_mp_deviation='$indx_mp_deviation1',indx_stm='$indx_stm1',indx_stm_deviation='$indx_stm_deviation1' where name='$name'");

                $log=myquery("INSERT INTO game_log_adm (adm,time,dei) VALUES ('".$char['name']."','".date("j.m.Y H:i")."','������� �����:  '".$name."'");

                echo'<br><br><center><font color=ff0000 size=2 face=verdana><b>�����: '.$name.' ��������<b></font>';
                echo '<meta http-equiv="refresh" content="1;url=admin.php?option=spets&opt=main">';
            }
        }
    }

    $img_fon = 'http://'.IMG_DOMAIN.'/race_table/human/table';
    echo'</td><td background="'.$img_fon.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img_fon.'_lb.gif"></td><td background="'.$img_fon.'_mb.gif"></td><td width="1" height="1"><img src="'.$img_fon.'_rb.gif"></td></tr></table>';
    {if (function_exists("save_debug")) save_debug(); exit;}
}


if (function_exists("save_debug")) save_debug(); 

?>