<?

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
    echo'<style type="text/css">@import url("../style/shop.css");</style><center><font face=verdana size=2>';
    $userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
    if (mysql_num_rows($userban))
    {
        $userr = mysql_fetch_array($userban);
        $min = ceil(($userr['time']-time())/60);
        echo '<center><br><br><br>�� ��� �������� ��������� �� '.$min.' �����! ��� ��������� ����������� � ��������!';
		echo '<br><br><br><a href="../gorod.php?&option='.$town.'">����� � �����</a>';
        {if (function_exists("save_debug")) save_debug(); exit;}
    }

    $time_for_check = time()-604800;

    if (!isset($do)) $do='';

    if ($do=='view')
    {
        echo'<form action="" method="post">';

        if (!isset($new_item_cost)) $new_item_cost=0;
        if(isset($see) and $new_item_cost>0)
        //����������� ����� ������
        {
            $it=(int)$it;
            $sct=myquery("select name,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p,town,step,last_price,last_user,max_price from game_items_old where priznak='1' and id='$it'");
            if (mysql_num_rows($sct))
            {
                list($name,$ident,$indx,$deviation,$mode,$weight,$curse,$img,$item_cost,$type,$ostr,$ontl,$opie,$ovit,$odex,$ospd,$oclevel,$dstr,$dntl,$dpie,$dvit,$ddex,$dspd,$sv,$race,$hp_p,$mp_p,$stm_p,$cc_p,$town,$step,$last_price,$last_user,$max_price)=mysql_fetch_array($sct);
                if ($char['GP']<$new_item_cost)
                {
					echo '��������� ������<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
                    {if (function_exists("save_debug")) save_debug(); exit;}
                }
                elseif ($new_item_cost<($last_price+$step))
                {
					echo '�� �������� ��� ��������� ���� ����<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
                    {if (function_exists("save_debug")) save_debug(); exit;}
                }
                elseif ($name == $char['name'] AND $char['clan_id']!=1)
                {
					echo '������ �������� ���� ����<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
                    {if (function_exists("save_debug")) save_debug(); exit;}
                }
                else
                {
                    //$result=myquery("insert into game_items(user_id,ref_id,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p) values ('".$char['user_id']."','0','$ident','$indx','$deviation','$mode','$weight','$curse','$img','0','$type','$ostr','$ontl','$opie','$ovit','$odex','$ospd','$oclevel','$dstr','$dntl','$dpie','$dvit','$ddex','$dspd','$sv','$race','$hp_p','$mp_p','$stm_p','$cc_p')");
                    $up = myquery("UPDATE game_users SET GP=GP-$new_item_cost WHERE user_id='$user_id'");

                    $sell = myquery("SELECT name FROM game_users WHERE user_id='$last_user'");
                    if (!mysql_num_rows($sell))
                    {
                        $sell = myquery("SELECT name FROM game_users_archive WHERE user_id='$last_user'");
                    }
                    list($last_name) = mysql_fetch_array($sell);

                    $town_select = myquery("select rustown from game_gorod where town='$town'");
                    list($rustown)=mysql_fetch_array($town_select);

                    $check = myquery("SELECT user_id FROM game_users WHERE name='$name'");
                    if (!mysql_num_rows($check)) $check = myquery("SELECT user_id FROM game_users WHERE name='$name'");
                    list($user_id_sell)=mysql_fetch_array($check);

                    if ($user_id_sell!=$last_user)
                    {
                        $msg='�� ��� '.$ident.' ������������ �� ������� �� �������� � '.$rustown.' �������� ���� ���� � '.$last_price.' �������. ���� ����� ���������� � ��� �������. ����� ���� �� ��� � '.$new_item_cost.' ����� ����������� ������� '.$char['name'].'.';
                        if ($new_item_cost == $max_price) $msg.=' ���������� ������������ ������ �� ���. ����� ���������.';
                        $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$last_user', '0', '�������', '$msg','0','".time()."')");
                        $up = myquery("UPDATE game_users SET GP=GP+$last_price WHERE user_id=$last_user");
                        $up = myquery("UPDATE game_users_archive SET GP=GP+$last_price WHERE user_id=$last_user");
                    }

                    //$delete=myquery("delete from game_items_old where priznak='1' and id='$it'");
                    $msg='�� ��� ��� '.$ident.' ������������ �� ������� �� �������� � '.$rustown.' ������� '.$char['name'].' ����������� ����� ���� � '.$new_item_cost.' �����.';
                    if ($new_item_cost == $max_price) $msg.=' ���������� ������������ ������ �� ���. ����� ���������.';
					$komu = @mysql_result(@myquery("(SELECT user_id FROM game_users WHERE name='".$name."') UNION (SELECT user_id FROM game_users_archive WHERE name='".$name."')"),0,0);
                    $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$komu', '0', '�������', '$msg','0','".time()."')");

                    $up = myquery("UPDATE game_items_old SET last_user='$user_id', last_price='$new_item_cost' WHERE id='".$it."' and priznak='1'");

                    $msg='�� ��� ���� ����������� ����� ���� � '.$new_item_cost.' �����';
                    if ($new_item_cost == $max_price) $msg.=' ���������� ������������ ������ �� ���. ����� ���������.';
                    echo'<img src=".http://'.IMG_DOMAIN.'/gorod/bank/screen2.jpg"><br><hr>';
                    echo $msg;
					echo '<meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
                    {if (function_exists("save_debug")) save_debug(); exit;}
                }
            }
        }
        echo'<SCRIPT language=javascript src="../combat/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
        echo'<img src="http://'.IMG_DOMAIN.'/gorod/bank/screen1.jpg"><br><hr>';

        if (!isset($page)) $page=1;
        $page=(int)$page;
        if ($page<1) $page=1;
        $line=1;

        $pg=myquery("SELECT COUNT(*) FROM game_items_old where priznak='1' and (max_price=0 OR last_price<max_price) and town='$town' and type!='' and sell_time>'$time_for_check' AND post_to=0  ORDER BY sell_time DESC");
        $allpage=ceil(mysql_result($pg,0,0)/$line);
        if ($page>$allpage) $page=$allpage;

        $href = '?option='.$option.'&do=view&';
	    echo'<center>��������: ';
        show_page($page,$allpage,$href);

        $select=myquery("select * from game_items_old where priznak='1' and town='$town' and type!='' and sell_time>'$time_for_check' and post_to=0 order by sell_time DESC limit ".(($page-1)*$line).", $line");
        if ($select!=false)
        {
        while ($items=mysql_fetch_array($select))
        {
            echo'<table border="0" cellpadding="1"><tr><td></td></tr></table>
            <table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>';
            echo '<table cellpadding="0" cellspacing="4" border="0"><tr><td width=100 valign="left"><div align="center">';
?>
            <a  onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b>����������: </b></font>','<?
            echo '<font color=000000>';
            if ($items['race']<>0) echo'������ ��� ����: <font color=ff0000><b>'.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$items['race'].""),0,0).'</b></font><br>';
            if ($items['oclevel']<>'0')  echo '�������: '.$items['oclevel'].'<br>';
            if ($items['ostr']<>'0')  echo '����: '.$items['ostr'].'<br>';
            if ($items['ontl']<>'0')  echo '��������: '.$items['ontl'].'<br>';
            if ($items['opie']<>'0')  echo '��������: '.$items['opie'].'<br>';
            if ($items['ovit']<>'0')  echo '������: '.$items['ovit'].'<br>';
            if ($items['odex']<>'0')  echo '������������: '.$items['odex'].'<br>';
            if ($items['ospd']<>'0')  echo '��������: '.$items['ospd'].'<br>';
            ?>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?
            echo '<img src="http://'.IMG_DOMAIN.'/item/'.$items['img'].'.gif" width="50" height="50" border="0" alt="">';
            ?></a>
<?
            $left_time = $items['sell_time']+604800;
            $left_time = date('d.m.Y : H:i:s', $left_time);

            echo'<br><font color="#ffff00">' . $items['ident'] . '</font></div><center>';
            echo'��������� �� �������� ��:<br><b>'.$left_time.'</b>';

            echo'</center></td><td width=150 valign="top"><div align="left"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" hspace="40" border="0"><br>
                        ��������: <font size=1 face=verdana color=ff0000><b>'.$items["name"].'</b></font><br>
            ���: ' . $items["type"] . '<br>';
            if ($items['indx']<>'0')
            if ($items['type'] == '������') echo '����: ' . $items['indx'] . '&nbsp;&plusmn;&nbsp;' . $items['deviation'] . '<br>';
            if ($items['type'] == '���') echo '������: ' . $items['indx'] . '<br>';
            echo'���: ' . $items['weight'] . '<br><br>������� ��������:<br>';
            if ($items['dstr']<>'0')  echo '���� ��: '.$items['dstr'].'<br>';
            if ($items['dntl']<>'0')  echo '�������� ��: '.$items['dntl'].'<br>';
            if ($items['dpie']<>'0')  echo '�������� ��: '.$items['dpie'].'<br>';
            if ($items['dvit']<>'0')  echo '������ ��: '.$items['dvit'].'<br>';
            if ($items['ddex']<>'0')  echo '������������ ��: '.$items['ddex'].'<br>';
            if ($items['dspd']<>'0')  echo '�������� ��: '.$items['dspd'].'<br>';

            if ($items['hp_p']<>'0') echo'�������� ����� ��: '.$items['hp_p'].'<br>';
            if ($items['mp_p']<>'0') echo'�������� ���� ��: '.$items['mp_p'].'<br>';
            if ($items['stm_p']<>'0') echo'�������� ������� ��: '.$items['stm_p'].'<br>';
            if ($items['cc_p']<>'0') echo'�������� ������� ��������� ��: '.$items['cc_p'].'<br>';

            echo'<br>';
            echo'<br>';

            echo'<u>��������� ����: <br><font face=verdana color=fff000></u><b>'.$items['item_cost'].'</b></font><br>';

            echo '<u>��� ��������� ������: <br><font face=verdana color=fff000></u><b>'.$items['step'].'</b></font><br>';
            echo '<u>������������ ������: <br><font face=verdana color=fff000></u><b>'; if ($items['max_price']==0) echo '�� �����������'; else echo $items['max_price'];
            echo'</b></font><br>';
            echo'<br>';

            $last = myquery("SELECT name FROM game_users WHERE user_id='".$items['last_user']."'");
            if (!mysql_num_rows($last)) $last = myquery("SELECT name FROM game_users_acrhive WHERE user_id='".$items['last_user']."'");
            list($last_name)=mysql_fetch_array($last);

            echo '<u>��������� ��������: <br><font face=verdana color=fff000></u><b>'.$last_name.'</b></font><br>';
            echo '<u>��������� ����: <br><font face=verdana color=fff000></u><b>'.$items['last_price'].'</b></font><br>';

            echo'<br>';

            if ($items['name']!=$char['name'] OR $char['clan_id']==1)
            {
                echo'����:<input name="new_item_cost" type="text" size="9" maxsize="9"> �����<br><br>';
                echo '<input name="" type="submit" value="��������� ����� ������">';
                echo '<input name="it" type="hidden" value='.$items['id'].'>';
                echo '<input name="see" type="hidden" value="">';
            }
            echo'</div></td><td valign=top>��������:<br>'.$items["opis"].'';

            if ($char['name'] == 'The_Elf' OR $char['name'] == 'blazevic')
                echo'<br><br><br><input type="button" value="������� ���������" onClick=location.replace("town.php?option='.$option.'&do=del&it='.$items['id'].'")>';
			if ($char['name']==$items['name']) echo'<br><br><br><input type="button" value="����� ���� �������" onClick=location.replace("town.php?option='.$option.'&do=del&it='.$items['id'].'")>';

            echo'</td></tr></table></td></tr></table>';
        }
        }
        echo '</form>';
		echo'<br><input type="button" value="�����" onClick=location.replace("town.php?option='.$option.'")> ';

        $href = '?option='.$option.'&do=view&';
	    echo'<center>��������: ';
        show_page($page,$allpage,$href);
        $all=mysql_result($pg,0,0);
        echo'</font><br>(����� ��������� �� ��������: '.$all.')';
        {if (function_exists("save_debug")) save_debug(); exit;}
    }
        
    if ($do=='del')
    {
        $sel=myquery("select name from game_items_old where priznak='1' and id='$it'");
        list($name)=mysql_fetch_array($sel);

        if ($char['name']=='The_Elf' or $char['name']=='blazevic' or $char['name']==$name)
        {
            $sell=myquery("select user_id from game_users where name='$name'");
            if (!mysql_num_rows($sell)) $sell = myquery("SELECT user_id FROM game_users_archive WHERE name='$name'");
            list($userid)=mysql_fetch_array($sell);

            $sct=myquery("select id,name,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p,last_user,last_price from game_items_old where priznak='1' and id='$it'");
            list($id,$name,$ident,$indx,$deviation,$mode,$weight,$curse,$img,$item_cost,$type,$ostr,$ontl,$opie,$ovit,$odex,$ospd,$oclevel,$dstr,$dntl,$dpie,$dvit,$ddex,$dspd,$sv,$race,$hp_p,$mp_p,$stm_p,$cc_p,$last_user,$last_price)=mysql_fetch_array($sct);

            $result=myquery("insert into game_items(id,user_id,ref_id,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p) values ('$id','$userid','0','$ident','$indx','$deviation','$mode','$weight','$curse','$img','0','$type','$ostr','$ontl','$opie','$ovit','$odex','$ospd','$oclevel','$dstr','$dntl','$dpie','$dvit','$ddex','$dspd','$sv','$race','$hp_p','$mp_p','$stm_p','$cc_p')");
            $result=myquery("update game_users set CW=CW+$weight where user_id='$userid'");
            $delete=myquery("delete from game_items_old where priznak='1' and id='$it'");
            $town_select = myquery("select rustown from game_gorod where town='$town'");
            list($rustown)=mysql_fetch_array($town_select);
			$komu = @mysql_result(@myquery("(SELECT user_id FROM game_users WHERE name='".$name."') UNION (SELECT user_id FROM game_users_archive WHERE name='".$name."')"),0,0);
            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$komu', '�������������', '�������', '��� ��� ".$ident." ���� � ������� �� �������� � ".$rustown."! �� ��������� � ��� ���������','0','".time()."')");

            if ($userid!=$last_user)
            {
                $result=myquery("update game_users set GP=GP+$last_price where user_id='$last_user'");
                $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$last_user', '0', '�������', '��� ".$ident." ���� � ������� �� �������� � ".$rustown."! ���� ��������� ������ �� ���� � ������� ".$last_price." ����� ���������� � ��� �������','0','".time()."')");
            }

			echo'���� ���������� ���������<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
            {if (function_exists("save_debug")) save_debug(); exit;}
        }
    }

    if ($do=='new_item')
    {
        if($char['GP']>'30')
        {
            if($char['clevel']>='4')
            {
                echo'<img src="http://'.IMG_DOMAIN.'/gorod/bank/screen2.jpg"><br><hr>';
                $selec=myquery("select * from game_items where user_id='$user_id' and ref_id='0' and used='' and type!='' and type!='wm'");
                while ($row=mysql_fetch_array($selec))
                {
                    $flag = 0;
                    $sel = myquery("SELECT * FROM game_items_factsheet WHERE name='".$row['ident']."'");
                    if (mysql_num_rows($sel))
                    {
                        $ch = mysql_fetch_array($sel);
                        if ($ch['personal']==1) $flag = 1;
                        if (($ch['torg']<18 and $ch['torg']>0) or ($ch['torg2']<18 and $ch['torg2']>0) or ($ch['torg3']<18 and $ch['torg3']>0))
                            $flag = 1;
                    }
                    if ($flag == 0)
                    {
					    echo'<table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><tr><td width=70 align=center><a href=town.php?option='.$option.'&do=confirm&it='.$row["id"].'><img src="http://'.IMG_DOMAIN.'/item/'.$row["img"].'.gif" border="0"></a></td><td>'.$row["ident"].'</td></tr></table>';
                    }
                }
                echo '<br><br><font color=#FF0000>������� �� ������� ���� ��������� ��� �� �������</font> <br><br> (<font size=1 color=#80FFFF>�� ������� ��������� ���������� ������ ��������, ������������� � ��������� ������� � ��������� ����������, ���������� ��� �� ������� ������</font>)';
				echo'<br><br><input type="button" value="�����" onClick=location.replace("town.php?option='.$option.'")>';
                {if (function_exists("save_debug")) save_debug(); exit;}
            }
            else
            {
                echo '���������� � �������� ��������� ������ ����� 4-�� ������';
            }
        }
        else
        {
            echo '����������� � �������� ��������� ��� ������� ����� 30 ����� � ��������. � ��� �� ����� ���!';
        }
    }


    if ($do=='confirm')
    {
        //$it=(int)$it;
        if($char['clevel']>='4' and $char['GP']>'0')
        {
            if (!isset($see))
            {
                echo'<form action="" method="post">';
                $sel=myquery("select * from game_items where id='$it' and user_id='$user_id' and ref_id='0' and used='' and type!='' and type!='wm'");
                while ($items=mysql_fetch_array($sel))
                {
                    $flag = 0;
                    $sel = myquery("SELECT * FROM game_items_factsheet WHERE name='".$items['ident']."'");
                    if (mysql_num_rows($sel))
                    {
                        if ($ch['personal']==1) $flag=1;
                        $ch = mysql_fetch_array($sel);
                        if (($ch['torg']<18 and $ch['torg']>0) or ($ch['torg2']<18 and $ch['torg2']>0) or ($ch['torg3']<18 and $ch['torg3']>0))
                           $flag = 1;
                    }
                    if ($flag==0)
                    {
                        echo'<img src="http://'.IMG_DOMAIN.'/gorod/bank/2.jpg"><table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>';
                        echo '<table cellpadding="0" cellspacing="4" border="0"><tr><td valign="left"><div align="center"><img src="http://'.IMG_DOMAIN.'/item/' . $items['img'] . '.gif" width="50" height="50" border="0" alt=""><br><font color="#ffff00">' . $items['ident'] . '</font></div></td><td valign="top"><div align="left"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" hspace="40" border="0"><br>
                        ���: ' . $items["type"] . '<br>';
                        if ($items['indx']<>'0')
                        if ($items['type'] == '������') echo '����: ' . $items['indx'] . '&nbsp;&plusmn;&nbsp;' . $items['deviation'] . '<br>';
                        if ($items['type'] == '���') echo '������: ' . $items['indx'] . '<br>';
                        echo'���: ' . $items['weight'] . '<br><br>
                        ������� ��������:<br>';
                        if ($items['dstr']<>'0')  echo '���� ��: '.$items['dstr'].'<br>';
                        if ($items['dntl']<>'0')  echo '�������� ��: '.$items['dntl'].'<br>';
                        if ($items['dpie']<>'0')  echo '�������� ��: '.$items['dpie'].'<br>';
                        if ($items['dvit']<>'0')  echo '������ ��: '.$items['dvit'].'<br>';
                        if ($items['ddex']<>'0')  echo '������������ ��: '.$items['ddex'].'<br>';
                        if ($items['dspd']<>'0')  echo '�������� ��: '.$items['dspd'].'<br>';

                        if ($items['hp_p']<>'0')  echo '����� ��: '.$items['hp_p'].'<br>';
                        if ($items['stm_p']<>'0')  echo '������� ��: '.$items['stm_p'].'<br>';
                        if ($items['mp_p']<>'0')  echo '���� ��: '.$items['mp_p'].'<br>';
                        if ($items['cc_p']<>'0')  echo '������� ����� ��: '.$items['cc_p'].'<br>';
                        echo'<br><u><b>��������� ����:</b></u><br><input name="cena" type="text" size=17> �����';
                        echo'<br><u><b>��� ��������� ������:</b></u><br><input name="step" type="text" size=17> �����';
                        echo'<br><u><b>������������ ���� (0 - ���� ������������ ���� �� ���������):</b></u><br><input name="max_price" type="text" size=17> �����<br><br>';
                        echo '<font color="#ffff00"><b><u>�� ������� � �������� �� ������ ������ ��������� 30 �����!</u></b></font><br><br>';
                        echo '��������:<br><textarea name="opis" cols="25" rows="7"></textarea>
                        <br>

                        <input name="" type="submit" value=���������>';
                        echo'</div></td></tr></table>';
                        echo'</td></tr><input name="see" type="hidden" value=""></table></form>';
                    }
                }
				echo'<br><input type="button" value="�����" onClick=location.replace("town.php?option='.$option.'")>';
                {if (function_exists("save_debug")) save_debug(); exit;}
            }
            else
            {
                if ($cena>0 and $cena<=9999 and $step>0 and $step<999)
                {
                    $select=myquery("select id,user_id,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p from game_items where id='$it'");
                    list($id,$user_id,$ident,$indx,$deviation,$mode,$weight,$curse,$img,$item_cost,$type,$ostr,$ontl,$opie,$ovit,$odex,$ospd,$oclevel,$dstr,$dntl,$dpie,$dvit,$ddex,$dspd,$sv,$race,$hp_p,$mp_p,$stm_p,$cc_p)=mysql_fetch_array($select);
                    $personal = mysql_result(myquery("SELECT personal FROM game_items_factsheet WHERE name='".$ident."'"),0,0);
                    if ($personal==0)
                    {
                        if (!isset($race)) $race=0;
                        if (!isset($opis)) $opis='';
                        if (!isset($sv)) $sv='';

                        $user_time = time();
                       $result=myquery("insert into game_items_old (id,name,town,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,opis,race,hp_p,mp_p,stm_p,cc_p,sell_time,priznak,last_price,last_user,max_price,step) values ('$id','".$char['name']."','$town','$ident','$indx','$deviation','$mode','$weight','$curse','$img','$cena','$type','$ostr','$ontl','$opie','$ovit','$odex','$ospd','$oclevel','$dstr','$dntl','$dpie','$dvit','$ddex','$dspd','$sv','$opis','$race','$hp_p','$mp_p','$stm_p','$cc_p','$user_time','1','$cena','$user_id','$max_price','$step')");
                       $delete=myquery("delete from game_items where id='$it'");
                       $result=myquery("update game_users set CW=CW - $weight, GP=GP - 30 where user_id='".$char['user_id']."'");
				       echo'���� ���������� �� �������<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
                    }
                    {if (function_exists("save_debug")) save_debug(); exit;}
                }
                echo'������� ����� � ��� ��������� ������<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
                {if (function_exists("save_debug")) save_debug(); exit;}
            }
        }
    }

    if ($do=='return_all')
    {
        if ($char['name'] == 'The_Elf' OR $char['name'] == 'blazevic')
        {
            $sel=myquery("select * from game_items_old where priznak='1'");
            while ($items=mysql_fetch_array($sel))
            {
                $name = $items['name'];

                $sell=myquery("select user_id from game_users where name='$name'");
                list($userid)=mysql_fetch_array($sell);

                $sct=myquery("select name,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p,last_user,last_price from game_items_old where priznak='1' and id='".$items['id']."'");
                list($name,$ident,$indx,$deviation,$mode,$weight,$curse,$img,$item_cost,$type,$ostr,$ontl,$opie,$ovit,$odex,$ospd,$oclevel,$dstr,$dntl,$dpie,$dvit,$ddex,$dspd,$sv,$race,$hp_p,$mp_p,$stm_p,$cc_p,$last_user,$last_price)=mysql_fetch_array($sct);

                $result=myquery("insert into game_items(user_id,ref_id,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p) values ('$userid','0','$ident','$indx','$deviation','$mode','$weight','$curse','$img','0','$type','$ostr','$ontl','$opie','$ovit','$odex','$ospd','$oclevel','$dstr','$dntl','$dpie','$dvit','$ddex','$dspd','$sv','$race','$hp_p','$mp_p','$stm_p','$cc_p')");
                $result=myquery("update game_users set CW=CW + $weight where user_id='$userid'");
                $delete=myquery("delete from game_items_old where priznak='1' and id='".$items['id']."'");
                $town_select = myquery("select rustown from game_gorod where town='$town'");
                list($rustown)=mysql_fetch_array($town_select);
                $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$name', '�������������', '�������', '��� ��� ".$ident." ���� � ������ �� �������� � ".$rustown."! �� ��������� � ��� ���������','0','".time()."')");
                if ($userid!=$last_user)
                {
                    $result=myquery("update game_users set GP=GP+$last_price where user_id='$last_user'");
                    $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$last_user', '0', '�������', '��� ".$ident." ���� � ������� �� �������� � ".$rustown."! ���� ��������� ������ �� ���� � ������� ".$last_price." ����� ���������� � ��� �������','0','".time()."')");
                }
            }
        }
    }
    echo'<img src="http://'.IMG_DOMAIN.'/gorod/bank/screen1.jpg"><br><hr>';

    echo '
    <table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">
    <tr>';
	echo '<td align=center><a href="town.php?option='.$option.'&do=new_item">��������� �� �����</a> | ';
	echo '<a href="town.php?option='.$option.'&do=view">�����������</a>';
    /*
    if ($char['name'] == 'The_Elf' OR $char['name'] == 'blazevic')
            {
			echo  ' | <a href="town.php?option='.$option.'&do=return_all">������� �Ѩ ����������</a>';
            }
    */
    echo ' | <a href="../gorod.php?&option='.$town.'">�����</a></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>