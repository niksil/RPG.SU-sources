<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

include_once('inc/template.inc.php');
require_once('inc/template_header.inc.php');

function count_all_exp(&$EXP_NEW,&$gp)
{
	GLOBAL $char, $user_id;
	// ������� ������������ �����
	$level=$char['clevel'];
	$i=1;
	$gp = 0;
	$EXP_NEW=$char['EXP']+get_exp_from_level($level);
	for($i;$i<=$level;$i++)
	{
		if ($i == 10)
		{
			$gp+=300;
		}
		elseif ($i == 20)
		{
			$gp+=500;
		}
		elseif ($i == 30)
		{
			$gp+=1000;
		}
		elseif ($i == 40)
		{
			$gp+=1500;
		}
		else
		{
			if ($i<10)
			{
				$gp+=50;
			}
			else
			{
				$gp+=floor(($i-1)/10)*2*50;
			}
		}
	}
	return '';
}

$dostup = -1; // -1 - ��� �� ���������
              // 0 - ������ � ��������� ������
              // 1 - ���������� ���������
              // 2 - ��������� �� ����

function check_obnul($param)
{
	GLOBAL $char, $user_id, $dostup;
    if ($char['clevel']<5) return;
	if ($dostup==-1)
	{
        $dostup = 0;
        if ($char['clevel']>=5)
        {
		    $obnul = mysql_result(myquery("SELECT obnul FROM game_users_data WHERE user_id=$user_id"),0,0);
            if ($obnul>0)
            {
                $dostup = 1;
            }
            else
            {
                $dostup = 2;
            }
		    //��������, �� ����� �� ����� �� �������
		    $prison_check=mysql_num_rows(myquery("SELECT * FROM game_prison WHERE user_id='$user_id'"));
		    //���� ����� �� �������, �� ����� ��� ������ �� �����
		    if($prison_check>0)
		    {
			    $dostup = 0;
		    }
        }
	}
	if ($dostup>0)
	{
		if (isset($_POST['make_obnul2']) AND $param == 0)
		{
			do_obnul($user_id,$dostup);
			echo '<div style="padding:10px;align:center;font-weight:700;color:#FFFF00;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">���� �������� ������ �������! ����������! ������ �� ������ ������ ������� ������ �����!</div>'; 
			$result = myquery("SELECT * FROM game_users WHERE user_id=$user_id LIMIT 1");
			$char = mysql_fetch_array($result);
			list($char_map_name,$char_map_xpos,$char_map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$user_id'"));
			list($IP) = mysql_fetch_array(myquery("SELECT work_IP FROM game_users_data WHERE user_id='$user_id'"));
			$char['map_name']=$char_map_name;
			$char['map_xpos']=$char_map_xpos;
			$char['map_ypos']=$char_map_ypos;
			$char['last_active']=$_SESSION['user_time'];
		}
		elseif ($param == 1 AND !isset($_POST['make_obnul']))
		{
			echo '<script language="JavaScript" type="text/javascript">
			function show_hide_obnul()
			{
				div = document.getElementById("obn");
				if (div.style.display=="none")
				{
					div.style.display = "block";
				}
				else
				{
					div.style.display = "none";
				}
			}
			</script>';
			echo '<div><a href="#" onClick="show_hide_obnul();">���� �������� ��������� ���������</a></div>';
			echo '<div id="obn" style="display:none;">';
			QuoteTable('open');
			echo '<div style="padding:10px;align:center;font-weight:400;color:#FF8080;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">���� �������� "���������" ������ ���������. ����� "���������" ���� ������� � �������������� ����� �������� �� ���������� ������ (0 ������). ��� �������� ����� � ���� �����. ���� � ���� ���� ����, �� ���� ������ �������� ��� ���������, � ������ ���� ������. � ���� ����� ������� �� ���������� ������� �����, ������� �� ������ �������� � ���������� ��� ��������� �������';
			//$obnul = mysqlresult(myquery("SELECT obnul FROM game_users_data WHERE user_id=$user_id"),0,0); 
			if ($dostup==2)//������� �����
			{
				$allexp = 0;
				$gp=0;
				count_all_exp($allexp,$gp);
                $shtraf=floor($allexp*0.1);
				echo '<div style="padding:10px;align:center;font-weight:400;color:#00FFFF;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">�� ��������� �� '.echo_sex('������','������').' ������ ���������: '.$shtraf.' ������ �����</div>';
			}
			echo '<br /><br /><center><form action="" method="post" name="form_obnul"><input type="submit" name="make_obnul" value="��, � ���� ������� ��������� ������ ���������" style="padding:5px;font-size:13px;color:white;font-weight:900;font-family:Verdana;"></form>';
			echo '</div>';
			QuoteTable('close');
			echo '</div>';
			echo '<br />';
		}
		elseif ($param == 1 AND isset($_POST['make_obnul']))
		{
			QuoteTable('open');
			echo '<div style="padding:10px;align:center;font-weight:400;color:#FF8080;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">���� �������� "���������" ������ ���������. ����� "���������" ���� ������� � �������������� ����� �������� �� ���������� ������ (0 ������). ��� �������� ����� � ���� �����. ���� � ���� ���� ����, �� ���� ������ �������� ��� ���������, � ������ ���� ������. � ���� ����� ������� �� ���������� ������� �����, ������� �� ������ �������� � ���������� ��� ��������� �������.';
            //$obnul = mysqlresult(myquery("SELECT obnul FROM game_users_data WHERE user_id=$user_id"),0,0); 
            if ($dostup==2)//������� �����
            {
                $allexp = 0;
                $gp=0;
                count_all_exp($allexp,$gp);
                $shtraf=floor($allexp*0.1);
				echo '<div style="padding:10px;align:center;font-weight:400;color:#00FFFF;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">�� ��������� �� '.echo_sex('������','������').' ������ ���������: '.$shtraf.' ������ �����</div>';
			}
			echo '<center><div style="font-weight:700;font-size:16px;color:red;height:55px;"><br>�� '.echo_sex('�����','������').' ������� ��������� ���������. <br />������������� �� �� ������ ��� �������?<br></div>';
			echo '<form action="" method="post" name="form_obnul" class="button"><input type="submit" name="make_obnul2" value="��, � ������������� ���� ������� ��������� ������ ���������" style="padding:5px;font-size:13px;color:white;font-weight:900;font-family:Verdana;"></form>';
			echo '</div>';
			QuoteTable('close');
			echo '<br />';
		}
	}
}

if ($char['clevel']>=5) check_obnul(0);

echo'<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top" width="172"> ';

OpenTable('title');
echo'<table width="172" border="0" cellspacing="0" cellpadding="0"><tr><td>';
echo '<div align="center"><font color="#FFFF00"><b>����: <font color=ff0000>' . mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0) . '</font></b><br><font color="#FFFF00"><b>���� ID: <font color=ff0000>' . $char['user_id'] . '</font></b><br><br>
<img src="http://'.IMG_DOMAIN.'/avatar/' . $char['avatar'] . '" border="0" alt="' . $char['name'] . '"></b>
</div><td>';
echo'</td></tr></table>';
OpenTable('close');

$sel = myquery("SELECT * FROM game_users_brak WHERE (status=1 AND (user1='".$char["user_id"]."' OR user2='".$char["user_id"]."'))");
if (mysql_num_rows($sel))
{
	OpenTable('title');
	$usr = mysql_fetch_array($sel);
	if ($char['user_id']==$usr['user1'])
		$usr_id = $usr['user2'];
	else
		$usr_id = $usr['user1'];

	$selec = myquery("SELECT name FROM game_users WHERE user_id='".$usr_id."'");
	if (!mysql_num_rows($selec)) $selec = myquery("SELECT name FROM game_users_archive WHERE user_id='".$usr_id."'");
	list($name1) = mysql_fetch_array($selec);
	list($last_active1) = mysql_fetch_array(myquery("SELECT last_active FROM game_users_active WHERE user_id='$usr_id'"));

	echo'<table width="172" border="0" cellspacing="0" cellpadding="0">';
	echo '<tr><td><FONT color="#FFFF00" size=1 face="Tahoma"><img src="http://'.IMG_DOMAIN.'/item/ring/9.gif" width=30 height=30 align="right">�� �������� � ����� � ������� '.$name1.'</FONT></td></tr>';
	if ((time()-$last_active1)<=300)
	{
		if (isset($teleport) AND $teleport==$usr_id)
		{
			$sel = myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$usr_id'");
			list($map,$posx,$posy) = mysql_fetch_array($sel);
			if ($map == $char['map_name'])
			{
				list($maze)=mysql_fetch_array(myquery("SELECT maze FROM game_maps WHERE id=$map"));
				if ($maze==1)
				{
					echo '����� ��������� ������������� ����������� �����������';
				}
				else
				{
				$up = myquery("UPDATE game_users_map SET map_name='$map',map_xpos='$posx',map_ypos='$posy' WHERE user_id='".$char['user_id']."'");
				echo'�� '.echo_sex('������������','�������������').' � '.$name1.'';
			}
		}
		}
		echo'<tr><td><a href="act.php?func=hero&teleport='.$usr_id.'">�������� � '.$name1.'</a></td></tr>';
	}
	echo'</table>';
	OpenTable('close');
}


OpenTable('title');
echo'<table cellpadding="0" cellspacing="0" border="0" width="172"><tr><td>
<font color="#FFFF00"><b>��������������:</b></font><br><br>
<table cellpadding="2" cellspacing="0" border="0" width=100%>

<tr><td><img src="http://'.IMG_DOMAIN.'/har/sil.gif" alt="����"> ����: </td><td align=right>' . $char['STR'] . ''; if ($char['STR']>$char['STR_MAX']) echo '(+'.($char['STR']-$char['STR_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/har/int.gif" alt="���������"> ���������: </td><td align=right>' . $char['NTL'] . ''; if ($char['NTL']>$char['NTL_MAX']) echo '(+'.($char['NTL']-$char['NTL_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/har/lov.gif" alt="��������"> ��������: </td><td align=right>' . $char['PIE'] . ''; if ($char['PIE']>$char['PIE_MAX']) echo '(+'.($char['PIE']-$char['PIE_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/har/vit.gif" alt="������"> ������: </td><td align=right>' . $char['VIT'] . ''; if ($char['VIT']>$char['VIT_MAX']) echo '(+'.($char['VIT']-$char['VIT_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/har/dex.gif" alt="������������"> ������������: </td><td align=right>' . $char['DEX'] . ''; if ($char['DEX']>$char['DEX_MAX']) echo '(+'.($char['DEX']-$char['DEX_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/har/mud.gif" alt="��������"> ��������: </td><td align=right>' . $char['SPD'] . ''; if ($char['SPD']>$char['SPD_MAX']) echo '(+'.($char['SPD']-$char['SPD_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/har/ud.gif" alt="�����"> �����: </td><td align=right>' . $char['lucky'] . ''; if ($char['lucky']>$char['lucky_max']) echo '(+'.($char['lucky']-$char['lucky_max']).')'; echo '</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/har/win1.gif" alt="�����"> �����: </td><td align=right>' . $char['win'] . '</td></tr>';
//<tr><td><img src="http://'.IMG_DOMAIN.'/har/lose1.gif" alt="���������"> ���������: </td><td align=right>' . $char['lose'] . '</td></tr>
echo'<tr><td><img src="http://'.IMG_DOMAIN.'/har/win2.gif" alt="����� � ��� �����"> ����� � ��� �����: </td><td align=right>' . $char['arcomage_win'] . '</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/har/lose2.gif" alt="��������� � ��� �����"> ��������� � ��� �����: </td><td align=right>' . $char['arcomage_lose'] . '</td></tr>
';
if ($char['maze_win']>0)
{
	echo'
	<tr><td>&nbsp;</td></tr>
	<tr style="color:#FF00FF;font-weight:700;"><td><img src="http://'.IMG_DOMAIN.'/har/los.jpg" alt="�������� ����������">�������� ����������: </td><td style="text-align:right;">' . $char['maze_win'] . '</td></tr>
	';
}

echo '<tr><td>&nbsp;</td></tr>
<tr><td>�� ���. �������������: </td><td align=right>' . $char['bound'] . '</td></tr>

<tr><td>�� ���. �������:</td><td align=right> ' . $char['exam'] . '</td></tr>
</table>';
echo'</td></tr></table>';
OpenTable('close');

OpenTable('title');

echo '<table cellpadding="0" cellspacing="0" align="center" border="0" width="150">';
echo '<tr><td colspan=2><font size=1 color="#FFFF00"><b>������:</b></font></td></tr>';
if ($char['skill_war']!='0') echo '<tr><td width="56">����</td><td width="108"><div align="right">' . $char['skill_war'] . '</div></td></tr>';
if ($char['skill_music']!='0') echo '<tr><td width="56">����</td><td width="108"><div align="right">' . $char['skill_music'] . '</div></td></tr>';
if ($char['skill_cook']!='0') echo '<tr><td width="56">���������</td><td width="108"><div align="right">' . $char['skill_cook'] . '</div></td></tr>';
if ($char['skill_art']!='0') echo '<tr><td width="56">������</td><td width="108"><div align="right">' . $char['skill_art'] . '</div></td></tr>';
if ($char['skill_explor']!='0') echo '<tr><td width="56">�������</td><td width="108"><div align="right">' . $char['skill_explor'] . '</div></td></tr>';
if ($char['skill_craft']!='0') echo '<tr><td width="56">������</td><td width="108"><div align="right">' . $char['skill_craft'] . '</div></td></tr>';
if ($char['skill_card']!='0') echo '<tr><td width="56">���</td><td width="108"><div align="right">' . $char['skill_card'] . '</div></td></tr>';
if ($char['skill_pet']!='0') echo '<tr><td width="56">�����</td><td width="108"><div align="right">' . $char['skill_pet'] . '</div></td></tr>';
if ($char['skill_uknow']!='0') echo '<tr><td width="56">���������</td><td width="108"><div align="right">' . $char['skill_uknow'] . '</div></td>';
echo '</table>';


echo '<table cellpadding="1" cellspacing="1" align="center" border="0" width="150">';
echo '<tr><td colspan=2><font size=1 color="#FFFF00"><b>�������������:</b></font></td></tr>';
if ($char['MS_KULAK']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/kulak.gif" alt="������ ��������� ���"></td><td width="108"><div align="right">�������� ���: ' . $char['MS_KULAK'] . '</div></td></tr>';
if ($char['MS_WEAPON']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/weapon.gif" alt="������� �������� ������"></td><td width="108"><div align="right">������� �������� ������: ' . $char['MS_WEAPON'] . '</div></td></tr>';
if ($char['MS_LUK']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/luk.gif" alt="������ ����������� ������"></td><td width="108"><div align="right">������ ����������� ������: ' . $char['MS_LUK'] . '</div></td></tr>';
if ($char['MS_THROW']!=0) echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/throw.gif" alt="������ ������������ ������"></td><td width="108"><div align="right">������ ������������ ������: ' . $char['MS_THROW'] . '</div></td></tr>';
if ($char['MS_SWORD']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/rub.gif" alt="������ �������� ������"></td><td width="108"><div align="right">������ �������� ������: ' . $char['MS_SWORD'] . '</div></td></tr>';
if ($char['MS_AXE']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/drob.gif" alt="������ ��������� ������"></td><td width="108"><div align="right">������ ��������� ������: ' . $char['MS_AXE'] . '</div></td></tr>';
if ($char['MS_SPEAR']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/kol.gif" alt="������ �������� ������"></td><td width="108"><div align="right">������ �������� ������: ' . $char['MS_SPEAR'] . '</div></td></tr>';
if ($char['MS_ART']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/art.gif" alt="������� ����������"></td><td width="108"><div align="right">������� ����������: ' . $char['MS_ART'] . '</div></td></tr>';
if ($char['MS_VSADNIK']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/koni.gif" alt="�������� ����"></td><td width="108"><div align="right">�������� ����: ' . $char['MS_VSADNIK'] . '</div></td></tr>';
if ($char['MS_KUZN']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/kuzn.jpg" alt="������"></td><td width="108"><div align="right">������: ' . $char['MS_KUZN'] . '</div></td></tr>';
if ($char['MS_PARIR']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/parir.gif" alt="������ �����������"></td><td width="108"><div align="right">������ �����������: ' . $char['MS_PARIR'] . '</div></td></tr>';
if ($char['MS_LEK']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/lekar.gif" alt="������"></td><td width="108"><div align="right">������: ' . $char['MS_LEK'] . '</div></td></tr>';
if ($char['MS_VOR']!='0') echo '<tr><td width="20"><img src="http://'.IMG_DOMAIN.'/har/vor.gif" alt="���"></td><td width="108"><div align="right">���: ' . $char['MS_VOR'] . '</div></td></tr>';
echo'</table>';

echo '<table cellpadding="1" cellspacing="1" align="center" border="0" width="150">';
echo '<tr><td colspan=2><font size=1 color="#FFFF00"><b>������:</b></font></td></tr>';
$sel_craft = myquery("SELECT * FROM game_users_crafts WHERE user_id=$user_id AND (profile=1 OR craft_index<=2)");
if (mysql_num_rows($sel_craft)>0)
{
	
	while ($cr = mysql_fetch_array($sel_craft))
	{
		$craft_level = CraftSpetsTimeToLevel($cr['craft_index'],$cr['times']);
		echo '<tr><td align="left">'.get_craft_name($cr['craft_index']).': </td><td align="right" title="�������(����.���.)">' . $craft_level . ' ( '.$cr['times'].' )</td></tr>';
	}
}
$guild_test = myquery("SELECT * From game_users_guild Where user_id=$user_id");
if (mysql_num_rows($guild_test)==1) 
{
	$guild = mysql_fetch_array($guild_test);
	echo '<tr><td align="left">������: </td><td align="right" title="�������(����.���.)">' . $guild['guild_lev'] . ' ( '.$guild['guild_times'].' )</td></tr>';
}
echo'</table>';


OpenTable('close');
echo'<td valign="top" width="100%" height="100%">';

OpenTable('title');

$new_clevel = get_new_level($char['clevel']);

echo'<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td> ';
echo'<img src="http://'.IMG_DOMAIN.'/nav/xar.gif" align=right><b>������ � ���� <font color="#ffff00">' . $char['clevel'] . '</font> �������, ��� ���������� ������ �����: <font color="#ffff00">'.$new_clevel.'</font> exp</b>';

if ($char['clevel']>=5) check_obnul(1);

//��������, ��������� �� �� ������ �����-���� ��������:
$check_elik=myquery("Select * From game_obelisk_users Where user_id=$user_id Order by type, time_end");
if (mysql_num_rows($check_elik)>0)
{
	echo '<br><b><font color="#ffff00">�� ���������� ��� �������� ��������� ���������:</font></b>';
	while ($elik=mysql_fetch_array($check_elik))
	{
		switch ($elik['type'])
		{
			case 2:
					switch ($elik['harka'])
					{
						case "HP_MAX":
								$mes="������� ������ ������ ";
								break;
						case "MP_MAX":
								$mes="������� ���� ������ ";
								break;
						case "STM_MAX":
								$mes="������� ������� ������ ";
								break;
						case "STM":
								$mes="������� ������� ������ ";
								break;
						case "CC":
								$mes="������������ ��� ������ ";
								break;
						case "STR":
								$mes="���� �������� ";
								break;
						case "SPD":
								$mes="�������� �������� ";
								break;
						case "NTL":
								$mes="��������� ������ ";
								break;
						case "PIE":
								$mes="�������� �������� ";
								break;
						case "PIE":
								$mes="�������� �������� ";
								break;		
						case "VIT":
								$mes="������ �������� ";
								break;	
						case "DEX":
								$mes="������������ �������� ";
								break;	
						case "LUCKY":
								$mes="����� �������� ";
								break;	
					}
					$mes=$mes."�� <b>".$elik['value']."</b> ".pluralForm($elik['value'],'�������','�������','������')." �� <b>".date("H:i d.m.Y",$elik['time_end'])."</b>";
					break;
			case 3:
					$mes="�������� �������� �������� ����������� �� <b>".date("H:i d.m.Y",$elik['time_end'])."</b>";
					break;
			case 4:
					$mes="�������� �������� �������� ����������� �� <b>".date("H:i d.m.Y",$elik['time_end'])."</b>";
					break;
			case 5:
					$mes="�������� �������� ����������� ����������� �� <b>".date("H:i d.m.Y",$elik['time_end'])."</b>";
					break;
		}
		if (isset ($mes)) echo '<br>'.$mes;
	}
	echo "<br>";
}

if ($char['vsadnik']>0) 
{
	echo'<b><br /><font color="#ffff00"><b>�� ������ �� ������� ��������: '.mysql_result(myquery("SELECT nazv FROM game_vsadnik WHERE id=".$char['vsadnik'].""),0,0).'!</b></font><br>';
	$sel_golod = myquery("SELECT game_users_horses.golod,game_users_horses.life,game_vsadnik.life_horse FROM game_users_horses,game_vsadnik WHERE game_users_horses.user_id=$user_id AND game_users_horses.horse_id=".$char['vsadnik']." AND game_vsadnik.id=".$char['vsadnik']."");
	if ($sel_golod!=false AND mysql_num_rows($sel_golod)>0)
	{
		$horse = mysql_fetch_array($sel_golod);
		switch ($horse['golod'])
		{
			case 0: $state='�����'; break;
			case 1: $state='������ ��������'; break;
			case 2: $state='��������'; break;
			case 3: $state='����� ��������'; break;
			case 4: $state='������������'; break;
			default: $state='���������'; break;
		}
		echo '��������� ������ �������: <b>'.$state.'</b><br />';
		echo '�������: '.($horse['life_horse']-$horse['life']).' / '.$horse['life_horse'].'<br />';
	}
}

$result=myquery("select * from game_spets_item where user_id='$user_id'");
if (mysql_num_rows($result))
{
	echo '<br /><font color="#ffff00">������ �� ��������� ��������:<br>
	<table border="0">';
	while($spets=mysql_fetch_array($result))
	{
		echo'<tr><td><b>'.$spets['mode'].' ('.$spets['name'].')</b></td><td><font color="#00FF00">'.$spets['img'].'</font>:</td><td>';
		if ($spets['indx']!=0) echo'&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF00FF">'.$spets['indx'].'&plusmn;'.$spets['deviation'].'</font> ������';
		if ($spets['indx_mp']!=0) echo', <font color="#FF00FF">'.$spets['indx_mp'].'&plusmn;'.$spets['indx_mp_deviation'].'</font> ����';
		if ($spets['indx_stm']!=0) echo', <font color="#FF00FF">'.$spets['indx_stm'].'&plusmn;'.$spets['indx_stm_deviation'].'</font> �������';

		echo'</font></td><td><font color="#0080C0">�������</font>';
		if ($spets['mana']!=0) echo'&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF00FF">'.$spets['mana'].'&plusmn;'.$spets['mana_deviation'].'</font> ����';
		if ($spets['hp']!=0) echo', <font color="#FF00FF">'.$spets['hp'].'&plusmn;'.$spets['hp_deviation'].'</font> �����';
		if ($spets['stm']!=0) echo', <font color="#FF00FF">'.$spets['stm'].'&plusmn;'.$spets['stm_deviation'].'</font> �������';
		echo'</td></tr>';
	}
	echo'</table>';
}

echo'</td></tr>';

echo '<tr><td><br><br><a href="http://'.DOMAIN.'/view/?exp" target="_blank">������� �����</a>  |  <a href="http://'.DOMAIN.'/info/?nv=-1" target="_blank">������� �������</a></td><tr>';

echo'</table>';

//echo '<br><br><center><iframe style="width:909px;height:605px;border:0px none;" src="act.php?func=spell_book"></iframe>';

OpenTable('close');

echo'</td><td valign="top" width="200"><table border=0 width=172 cellspacing="0" cellpadding="0"><tr><td>';
include('inc/template_stats.inc.php');
echo'</td></tr></table></td></tr></table>';
set_delay_reason_id($user_id,22);


if (function_exists("save_debug")) save_debug(); 

?>