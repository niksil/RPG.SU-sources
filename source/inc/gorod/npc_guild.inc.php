<?php

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	if (isset($town_id) AND $town_id!=$town)
	{
	echo'�� ���������� � ������ ������!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="�����" onClick=location.href="act.php">&nbsp;&nbsp;&nbsp;';
	}

	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������� � �������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	//�������� ���� �������
	echo'<b>������� ��������� �� ���������</b>';
	
	// �������� �� ������� ���������
	$test = myquery("SELECT * From game_users_guild Where user_id='$user_id'");
	
	$f=0; //����
	$prof_cost=1000; //��������� ��������� ���������
	if (mysql_num_rows($test)==1) $guild = mysql_fetch_array($test); //���������� ���������� ������� � ������
	
	if (isset($_GET['quest_now'])) 
	{
		$f=1;
		echo '<p align="left">����� ������� ����������� �� ��� �������.<br />
		<i>- ��� ����� ����� �������� �� ���������. � ������� �� ���� ������������ �� ����������� ������ ������ ��������. ��, </i>- ���� ����� �������� ������ �������, <i>- �� ���������� � ��� �������� ���������...� ��������� ������. <b>'.$prof_cost.'</b> ����� � �� �������������� � ���.</i></p><br />
		<input type="submit" value="��������� ������" onclick="location.replace(\'town.php?option='.$option.'&part1&new_guild\')"></input>&nbsp;&nbsp;&nbsp;
		<input type="submit" value="������������ � ����" onclick="location.replace(\'town.php?option='.$option.'&quest_later\')"></input>';
	}
	
	if (isset($_GET['new_guild'])) 
	{
		$test = myquery("SELECT * From game_users_guild Where user_id='$user_id'");
		if (mysql_num_rows($test)==0)
		{
			$test2 = myquery("SELECT * From game_users Where user_id=$user_id and GP>$prof_cost");
			if (mysql_num_rows($test2)==0)
			{
				echo '<p align="left">����� ������� ������������ ����������:</br>
					 <i>- �� ���� �������� �������? �� ������ ��� � ���� ������, �� �� ���� ������ ������������ ��� �����!</p>';
					 $f=1;
			}	
			else
			{
				myquery("INSERT INTO game_users_guild (user_id) VALUES ($user_id)");
				myquery("UPDATE game_users SET GP=GP-$prof_cost,CW=CW-".($prof_cost*money_weight)." WHERE user_id=$user_id");
				setGP($user_id,-$prof_cost,106); 
				echo '<br /><br /><b>����������� � ����������� ����������� � ������� � ��������� ����� ���������!</b>';
				$f=1;
				$guild=0;
			}
		}
	}
	
	if (isset($_GET['quest_later'])) 
	{
		$f=1;
	}
	
	if (mysql_num_rows($test)==0 and $f!=1 and $town==12)
	{
		echo '<center><br />���� �� ���� ��������� �� ��������� ��������, ��� � ����� ������� ��������� ���� ��� ��� ����� �����������<br /><br />
		<ul><li><a href="town.php?option='.$option.'&quest_now">���������� � ������ ������� ���������</a></li><br />
		<li><a href="town.php?option='.$option.'&quest_later">��������������� ������������ ��������</a></li></ul></center>';
	}
	
	
	// �������� ������� �������
	echo'<table border="0" cellpadding="8" cellspacing="1" style="border-collapse: collapse" width="96%" bgcolor="111111"><tr><td></td></tr></table>';
	
	
	$sel = myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.npc_quest_guild='$town' AND game_npc.npc_quest_end_time>".time()." AND game_npc_template.npc_id=game_npc.npc_id");
	if (mysql_num_rows($sel)>0)
	{
		$npc = mysql_fetch_array($sel);
		$npc_exp = floor($npc['npc_exp_max']*1.2);
		$end_time = $npc['npc_quest_end_time']-time();
		echo'� ������ ������ � ����� ������� ��������� ����� �� ���� ��������:<br><br>';
		QuoteTable('open');
		$player=$npc;
		$quest_id=$npc['npc_quest_id'];

		echo'<font face="Verdana" size="2" color="#f3f3f3"><b>'.$player['npc_name'].'</b></font></div>';
		echo '<img src="http://'.IMG_DOMAIN.'/npc/'.$player['npc_img'].'.gif" border="0" align="left">';

		echo '<table cellpadding="2" cellspacing="0" width="100%" border="0">';
		/*
		if ($player['npc_max_hp']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = number_format($player['npc_hp'] / $player['npc_max_hp'] * 100, 0);
		}
		if ($bar_percentage >= '100')
		{
			$bar_percentage = '100';
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_green.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= '0')
		{
			$bar_percentage = '0';
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_green.gif" width="' . $bar_percentage . '" height="7" border="0">';
		}
		echo '
		<tr>
		<td>��������</td>
		<td width=70% align=right>' . $player['npc_hp'] . ' / ' . $player['npc_max_hp'] . '</td>
		</tr>
		<tr>
		<td colspan="2"><div align="right"><img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'
		. $append_string . '<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"><br><img
		src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></div></td>
		</tr>
		';

		if ($player['npc_max_mp']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = number_format($player['npc_mp'] / $player['npc_max_mp'] * 100, 0);
		}
		if ($bar_percentage >= '100')
		{
			$bar_percentage = '100';
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_orange.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= '0')
		{
			$bar_percentage = '0';
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_orange.gif" width="' . $bar_percentage . '" height="7" border="0">';
		}
		echo '
		<tr>
		<td>����</td>
		<td width=70% align=right>' . $player['npc_mp'] . ' / ' . $player['npc_max_mp'] . '</td>
		</tr>
		<tr>
		<td colspan="2"><div align="right"><img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'
		. $append_string . '<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"><br><img
		src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></div></td>
		</tr>
		';
		*/
		echo '<tr>
		<td>����</td><td><div align="right">'.$player['npc_str'].'&plusmn;'.$player['npc_str_deviation'].'</td></tr>
		<tr><td>��������</td><td><div align="right">'.$player['npc_pie'].'&plusmn;'.$player['npc_pie_deviation'].'</td></tr>
		<tr><td>������</td><td><div align="right">'.$player['npc_vit'].'&plusmn;'.$player['npc_vit_deviation'].'</td></tr>
		<tr><td>������������</td><td><div align="right">'.$player['npc_dex'].'&plusmn;'.$player['npc_dex_deviation'].'</td></tr>
		<tr><td>��������</td><td><div align="right">' . $player['npc_spd'] . '&plusmn;'.$player['npc_spd_deviation'].'</td></tr>
		<tr><td>���������</td><td><div align="right">'.$player['npc_ntl'].'&plusmn;'.$player['npc_ntl_deviation'].'</td></tr>
		<tr><td>���� �� ������</td><td><div align="right">' . $player['npc_exp_max'] . '</td></tr></table>';
		QuoteTable('close');

		QuoteTable('open');
		echo'<font size=2 color=#F0F0F0><div align="justify">�� ������ ����� � ��� ������� �� �������� ����� �������! ������� � ��� ����� ����, ��� ������ ������� � ������� ��� ��� ������ (��� ����� ������ ����� ����) ��� �������������� ���������� �������! � �������� ������ �� ��������� ����� ������ �� ����� ���� <b><font color=#FF0000>'.$npc_exp.'</font></b> ����� �����, � ����� ��������� �������� ������� ���� �� ����� ���������. �� �� ������ ������������ ����, ��� �������� �� ������ ������ - ������ ������� �������� ����������� - ������� �������� ��� �������������� �������� ����� �������. � ���! ����, � ���� ������ 1 ������� � ���� ������� ����� �������� �� ����, ���������� �� �� � �������� ����, ��� �������� �� ������ ������.';
		QuoteTable('close');

		QuoteTable('open');
		$min = floor($end_time/60);
		$sec = $end_time-$min*60;
		echo '<font size=2 color=#FFFF80>������� ������������� ��� '.$min.'  ���. '.$sec.' ���.</font>';
		QuoteTable('close');

		$quest_users = mysql_result(myquery("SELECT COUNT(*) FROM game_quest_users WHERE quest_id='$quest_id' AND sost='".$npc['npc_id']."'"),0,0);
		QuoteTable('open');
		if ($quest_users==0) echo '<font size=2 color=#FFFF80>��� �� ���� ����� �� ��������� �� ���� �������.</font>';
		else echo '<font size=2 color=#FFFF80>�� ���� �������� ��� �������� '.$quest_users.' ������.</font>';
		QuoteTable('close');

		$sel_quest = myquery("SELECT sost FROM game_quest_users WHERE user_id='$user_id' AND quest_id='$quest_id'");
		if (mysql_num_rows($sel_quest)>0)
		{
			list($sost) = mysql_fetch_array($sel_quest);

			if ($sost==$npc['npc_id'])
			{
				$sel_item = myquery("SELECT item_uselife FROM game_items WHERE user_id='$user_id' AND used=0 AND item_for_quest='$quest_id' AND priznak=0");
				if (mysql_num_rows($sel_item)>0)
				{
					if (!isset($take_head))
					{
						QuoteTable('open');
						echo'<font size=2 color=#F0F0F0><div align="center">����������! �� '.echo_sex('����','�����').' ������� "'.$npc['npc_name'].'" � �� �������� ��� ��������!<br>';
						echo'<form action="" method="post"><input type="submit" name="take" value="������ ����� ���� �������"><input type="hidden" name="take_head"><input name="town_id" type="hidden" value="'.$town.'">';
						QuoteTable('close');
					}
					else
					{
						QuoteTable('open');
						$it = mysql_fetch_array($sel_item);
						$proc = $it['item_uselife']/100;
						$npc_exp = floor($npc_exp*$proc);
						echo'<div align="center"><font size=2 color=#F0F0F0>����������! ��� ���� "'.$npc_exp.'" ����� ����� �� ������� �������!<br>';
						echo'<br>*** <font size=2 color=#00FF00>�� ��������� � �������� ����� <font color=#FF0000>'.$npc_exp.'</font> ����� </font> ***';
						myquery("UPDATE game_npc SET npc_quest_guild=0,npc_quest_end_time=0,npc_quest_id=0 WHERE npc_quest_guild=$town");
						myquery("UPDATE game_users SET EXP=EXP+$npc_exp WHERE user_id=$user_id");
						setEXP($user_id,$npc_exp,5);
						myquery("DELETE FROM game_items WHERE item_for_quest=$quest_id");
						myquery("DELETE FROM game_quest_users WHERE quest_id=$quest_id");
						myquery("INSERT INTO game_npc_guild_log (user_id,user_name,time_end,vremya,exp) VALUES ($user_id,'".$char['name']."',".time().",'".date("d-m-Y H:i",time())."',$npc_exp)");
						
						// ������� ���������
						$r = mt_rand(1,17);
						if($r==12) $r=18;
						if($r==13) $r=19;
						if($r==14) $r=20;
						$it = myquery("SELECT item_uselife,id FROM game_items WHERE user_id=$user_id AND item_uselife<100 AND used=$r AND priznak=0");
						if (mysql_num_rows($it))
						{
							if ($r==1) $rem=25; 
                               else $rem=8; 
							if (isset($guild)) $rem=$rem+$guild['guild_lev'];
							$it = mysql_fetch_array($it);
							$uslife = min(100,$it['item_uselife']+$rem*$proc);
							myquery("UPDATE game_items SET item_uselife=$uslife WHERE user_id=$user_id AND id = ".$it['id']." AND priznak=0");
							echo'<br>*** <font size=2 color=#00FF00>��� ������ ��������� ������� ���� �� ����� ���������</font> ***';
						}
						
						
						// ��������� ������ ���������, ���� ���� ����
						if (isset($guild))
						{
							$guild['guild_times']++;
							myquery("UPDATE game_users_guild SET guild_times=guild_times+1 WHERE user_id=$user_id");
							$i=$guild['guild_times'];
							$j=0;
							do 
							{
								$j++;
								$i=$i-$j*5-5;
							} while ($i > 0);
							if ($i==0 and $j<27) 
							{
								$guild['guild_lev']++;
								myquery("UPDATE game_users_guild SET guild_lev=guild_lev+1 WHERE user_id=$user_id");
							}
						}
						
						QuoteTable('close');
					}
				}
				else
				{
					QuoteTable('open');
					echo'<font size=2 color=#F0F0F0><div align="justify">�� ��� '.echo_sex('����','�����').' ������� �� �������� �������, � �� ���� ��� �� ��� ���������! ������� � ��� ����� ����, ��� ������ ������� � ������� ��� ��� ������ (��� ����� ������ ����� ����) � �������� �������������� ���������� �������!';
					QuoteTable('close');
				}
			}
			else
			{
				QuoteTable('open');
				echo'<font size=2 color=#F0F0F0><div align="justify">�� ��� '.echo_sex('����','�����').' ������� �� �������� �������, �� �� '.echo_sex('����','������').' ����� ���. ��� �� ����� ����� �����! ������ �������� ������� � ������ ������� �������!';
				QuoteTable('close');
			}
		}
		else
		{
			if (!isset($take_quest))
			{
				QuoteTable('open');
				echo'<form action="" method="post"><input type="submit" name="take" value="����� ������� �� �������� ������� "'.$npc_exp.'""><input type="hidden" name="take_quest"><input name="town_id" type="hidden" value="'.$town.'">';
				QuoteTable('close');
			}
			else
			{
				QuoteTable('open');
				echo'<font size=2 color=#F0F0F0><div align="justify">����! �� ��������� ��������! ����� � ����� ������� ����! � �� ������ - �� '.echo_sex('������','������').' �������� ��� �������������� '.echo_sex('������','������').'! (����� �� ������ �� ��������)!';
				myquery("INSERT INTO game_quest_users(quest_id,user_id,sost) VALUES ('$quest_id','$user_id','".$npc['npc_id']."')");
				QuoteTable('close');
			}
		}
	}
	else
	{
		$sel_npc_other = myquery("SELECT game_npc.*,game_npc_template.npc_name FROM game_npc,game_npc_template WHERE game_npc.npc_quest_id>0 AND game_npc.npc_quest_end_time>".time()." AND game_npc.npc_id=game_npc_template.npc_id");
		if (mysql_num_rows($sel_npc_other)>0)
		{
			echo'<hr>������, �� ������ � ����� ������� ������� ��� ��� ���� ������. ������� �����!<hr>';
			if (isset($guild)) $info_cost=200-$guild['guild_lev']*7.6;
			else $info_cost=200;
			if (!isset($know_quest_where))
			{
				QuoteTable('open');
				echo'<font size=2 color=#F0F0F0><div align="justify">�� �� ������ ���������� � ����� ����������� � ����� �������� ������� ��� �� ��������� �������. ������ ��� ����� ������ ��� ���� '.$info_cost.' '.pluralForm($info_cost,'������','������','�����').'';
				QuoteTable('close');
				QuoteTable('open');
				echo'<form action="" method="post"><input type="submit" name="take_info" value="��������� �� ����������"><input type="hidden" name="know_quest_where"><input name="town_id" type="hidden" value="'.$town.'">';
				QuoteTable('close');
			}
			else
			{
				if ($char['GP']>=$info_cost)
				{
					QuoteTable('open');
					echo'<font size=2 color=#F0F0F0><div align="justify">������� ��� �������������� �������� ������ ��������������� ������:<br><br><center><b>��� ������ ���� ����������:</b></center><br><br>';
					myquery("UPDATE game_users SET GP=GP-200,CW=CW-'".($info_cost*money_weight)."' WHERE user_id='$user_id'");
					setGP($user_id,$info_cost,47);
					echo'<table width=100% cellspacing=2 cellpadding=2>';
					echo '<tr><th>�����</th><th>������</th><th>���.�����</th></tr>';
					while ($npc_other = mysql_fetch_array($sel_npc_other))
					{
						$town_id = $npc_other['npc_quest_guild'];
						$end_time = $npc_other['npc_quest_end_time']-time();
						$npc_name = $npc_other['npc_name'];
						$min = floor($end_time/60);
						$sec = $end_time-$min*60;
						list($rustown) = mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town='$town_id'"));
						$map = mysql_fetch_array(myquery("SELECT * FROM game_map WHERE town='$town_id' AND to_map_name=0"));
						list($map_name) = mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"));
						echo '<tr><td>'.$rustown.'</td><td>'.$npc_name.'</td><td>'.$min.' ���. '.$sec.' ���.</td></tr>';
						//echo '<tr><td>���-�� � '.$map_name.'</td><td>'.$npc_name.'</td><td>'.$min.' ���. '.$sec.' ���.</td></tr>';
					}
					echo'</table>';
					QuoteTable('close');
				}
				else
				{
					QuoteTable('open');
					echo'<font size=2 color=#F0F0F0><div align="justify">� ���� ��� 200 ����� ��� ������ ����� �����.';
					QuoteTable('close');
				}
			}
		}
		else
		{
			echo'<hr>������, �� ������ ������ ������� ��������� �� ��������� ������� � ����� � ��������� �� ����� ������� ����� ��������. ������� �����!<hr>';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>