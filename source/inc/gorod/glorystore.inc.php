<?

if (function_exists("start_debug")) start_debug(); 


//$price=0; $price_har=0;
if ($town!=0)
{
	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������������ �������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	function check_lr ($user_id, $i, $cost=0) //��������, ���� �� � ������ �������
	{
		if ($cost==0) list($cost)=mysql_fetch_array(myquery("SELECT cost From game_lr_services Where game_lr_services.serv_id=$i"));
		list($check)=mysql_fetch_array(myquery("SELECT user_rating From game_users_data Where user_id=$user_id"));
												 
		if (mysql_num_rows(myquery("Select * from game_lr_services_hist where user_id=$user_id"))>0)
		{
			list($lr_old)=mysql_fetch_array(myquery("Select sum(lr) from game_lr_services_hist where user_id=$user_id"));
			$check=$check-$lr_old;
		} 
		
		if ($check-$cost<0) //����� ��� ����������! ����� ���� � ������� ������
		{
			$cost=-1;
		} 
		return $cost;
	}
	
	$img='http://'.IMG_DOMAIN.'/race_table/human/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';
	
	list($lr)=mysql_fetch_array(myquery("SELECT user_rating From game_users_data Where user_id=$user_id"));
	if (mysql_num_rows(myquery("Select * from game_lr_services_hist where user_id=$user_id"))>0)
	{
		list($lr_old)=mysql_fetch_array(myquery("Select sum(lr) from game_lr_services_hist where user_id=$user_id"));
		$lr=max($lr-$lr_old,0);
	}
	
	if (isset($agree))	//����� ������������� ������� ������
	{
		if (isset($name)) $attr=$name;
		elseif (isset($_POST['town'])) $attr=$_POST['town'];
		elseif (isset($_POST['prof'])) $attr=$_POST['prof'];
		elseif (isset($_POST['race'])) $attr=$_POST['race'];
		elseif (isset($_POST['clan']) and isset($_POST['kol']) and $_POST['kol']>0) $attr=$_POST['clan'].';'.(int)$_POST['kol'];
		else $attr='';
		echo '<center><b>�� ������������� ������ �������� ������ ������? </b><br />
			  <br /><input type="button" onClick="location.href=\'town.php?option='.$option.'&action='.$action.'&attr='.$attr.'\'" value="��, � ������ ���� �����">
			  <br /><br />
			  <input type="button" onClick="location.href=\'town.php?option='.$option.'\'" value="���, � ������"><br /><br /></center>
			 ';
	}
	
	elseif (isset($action) AND $action=='new_name') // ��������� ����� ������
	{
		$id=1;
		$cost=check_lr($user_id, $id);
		list($name)=mysql_fetch_array(myquery("Select name From game_users Where user_id=$user_id"));
		if ($cost==-1) //����� ��� ����������! ����� ���� � ������� ������
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ������� �������� �� ������� ������ ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif (!isset($attr) or $attr=='') //����� ������ �� ���
		{
			echo'<center><font face=verdana color=ff0000 size=2>�� �� ����� ����� ������� ���!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}	
	
		elseif (ereg("[^a-zA-Z�-��-�_]+", $attr)) //����� ��� ������������ �������
		{
			echo'<center><font face=verdana color=ff0000 size=2>�������� ������� ��� �������� ������������ �������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif (strlen($attr)<5 or strlen($attr)>16) //������������ �����
		{
			echo'<center><font face=verdana color=ff0000 size=2>�������� ������� ��� ������������ �����!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif (mysql_num_rows(myquery("Select * From game_users Where name like '$attr' Union Select* From game_users_archive Where name like '$attr'"))>0) //��� ��� ������������!
		{
			echo'<center><font face=verdana color=ff0000 size=2>�������� ������� ��� ��� ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		else //������ ���
		{
			myquery("Update game_users Set name='$attr' Where user_id=$user_id");
			myquery("Insert into game_lr_services_hist (user_id, serv_id, lr, value) Values ($user_id, $id, $cost, '$name')");
			echo'<center><font face=verdana color=white size=2><b>���� ������� ��� ��������!</b></font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
		}
	}
	elseif (isset($action) AND $action=='new_medal') //������ ������ ������ �����
	{
		$id=2;
		$cost=check_lr($user_id, $id);
		list($medal_id, $medal_name)=mysql_fetch_array(myquery("SELECT game_medal.id, game_medal.nazv From game_lr_services Join game_medal On game_lr_services.name=game_medal.nazv Where game_lr_services.serv_id=$id"));
		if ($cost==-1) //����� ��� ����������! ����� ���� � ������� ������
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ������� �������� �� ������� ������ ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif (mysql_num_rows(myquery("Select * From game_medal_users Where medal_id=$medal_id"))>0) //� ������ ��� ���� ������ �����
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ���� '.$medal_name.'!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		else
		{
			$date=date("d.m.Y");
			myquery("Insert into game_medal_users (user_id, medal_id, zachto) Values ($user_id, $medal_id, '$date')");
			myquery("Insert into game_lr_services_hist (user_id, serv_id, lr, value) Values ($user_id, $id, $cost, $medal_id)");
			echo'<center><font face=verdana color=white size=2><b>��� ������ '.$medal_name.'!</b></font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
		}
		
	}
	
	elseif (isset($action) AND $action=='new_town')	//����� ������ ���������� ������
	{
		$id=3;
		$cost=check_lr($user_id, $id);
		$free_square = 100-(int)mysqlresult(myquery("SELECT SUM(square) FROM houses_users WHERE town_id=$attr"),0,0); //���������� ��������� ����� ����� � �������� ������
		list($town_id)=mysql_fetch_array(myquery("Select town_id FROM houses_users WHERE user_id=$user_id"));
		list($town_name)=mysql_fetch_array(myquery("Select rustown From game_gorod Where game_gorod.town=$attr"));
		if ($cost==-1) //����� ��� ����������! ����� ���� � ������� ������
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ������� �������� �� ������� ������ ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif ((mysql_num_rows(myquery("Select * FROM houses_users WHERE user_id=$user_id"))==0)) //� ������ ��� ����
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ����!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif ($town_id==$attr) //����� ����� ����� ��������
		{
			echo'<center><font face=verdana color=ff0000 size=2>�� ����� � ��������� ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif ((mysql_num_rows(myquery("Select * FROM houses_users WHERE user_id=$user_id and square<=$free_square and square<>0"))==0)) //� ��������� ������ �� ������� �����
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ���������, � ��������� ���� ������ ��� ������������ ����� ��������� �����!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		else
		{
			myquery("Update houses_users Set town_id=$attr where user_id=$user_id");
			myquery("Insert into game_lr_services_hist (user_id, serv_id, lr, value) Values ($user_id, $id, $cost, $town_id)");
			echo'<center><font face=verdana color=white size=2><b>��� ��� ��������� � '.$town_name.'!</b></font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
		}
		
	}
	
	elseif (isset($action) AND $action=='del_prof')	//��������� ���������
	{
		$id=4;
		$cost=check_lr($user_id, $id);
		if ($cost==-1) //����� ��� ����������! ����� ���� � ������� ������
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ������� �������� �� ������� ������ ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif ((mysql_num_rows(myquery("Select * from game_users_crafts where user_id=$user_id and profile=1 and craft_index=$attr"))==0)) //� ������ ��� ������ ���������
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ������ � ������ ���������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		else
		{
			myquery("UPDATE game_users_crafts SET profile=0 Where user_id=$user_id and craft_index=$attr");
			myquery("Insert into game_lr_services_hist (user_id, serv_id, lr, value) Values ($user_id, $id, $cost, $attr)");
			echo'<center><font face=verdana color=white size=2><b>��������� ������!</b></font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
		}
		
	}
	
	elseif (isset($action) AND $action=='new_race')	//����� ����
	{
		$id=5;
		$cost=check_lr($user_id, $id);
		list($user_race)=mysql_fetch_array(myquery("Select race from game_users where user_id=$user_id"));
		if ($cost==-1) //����� ��� ����������! ����� ���� � ������� ������
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ������� �������� �� ������� ������ ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif ($attr==$user_race) // ���� ���������
		{
			echo'<center><font face=verdana color=ff0000 size=2>���� ���� ��������� � ���������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		else
		{
			$check=myquery("Select t1.id From game_items as t1 Join game_items_factsheet as t2 on t1.item_id=t2.id Where t1.user_id=$user_id and t1.used>0 and t2.race<>$attr and t2.race<>0");
			while (list($item_id)=mysql_fetch_array($check))	
			{
				$Item = new Item();
				$Item->down($item_id);
			}
			list($hp, $hp_max, $mp, $mp_max, $stm, $stm_max, $gp, $str, $dex, $vit, $ntl, $pie, $spd)=mysql_fetch_array(myquery("SELECT t1.hp-t2.hp, t1.hp_max-t2.hp_max, t1.mp-t2.mp, t1.mp_max-t2.mp_max, t1.stm-t2.stm, t1.stm_max-t2.stm_max, t1.gp-t2.gp, t1.str-t2.str, t1.dex-t2.dex, t1.vit-t2.vit, t1.ntl-t2.ntl, t1.pie-t2.pie, t1.spd-t2.spd FROM game_har as t1, game_har as t2 WHERE t2.id=$user_race and t1.id=$attr"));
			myquery("Update game_users Set race=$attr, hp=hp+$hp, hp_max=hp_max+$hp_max, mp=mp+$mp, mp_max=mp_max+$mp_max, stm=stm+$stm, stm_max=stm_max+$stm_max, gp=gp+$gp, str=str+$str, vit=vit+$vit, dex=dex+$dex, spd=spd+$spd, pie=pie+$pie, ntl=ntl+$ntl, spd_max=spd_max+$spd, vit_max=vit_max+$vit, str_max=str_max+$str, pie_max=pie_max+$pie, ntl_max=ntl_max+$ntl, dex_max=dex_max+$dex where user_id=$user_id");	
			myquery("Insert into game_lr_services_hist (user_id, serv_id, lr, value) Values ($user_id, $id, $cost, $user_race)");
			echo'<center><font face=verdana color=white size=2><b>���� ��������!</b></font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
		}
		
	}
	
	elseif (isset($action) AND $action=='add_clan')	//������������ �������� �� ���� �����
	{
		if ($attr=='') //����� ��� ������������ ������
		{
			echo'<center><font face=verdana color=ff0000 size=2>������� ������������ ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		else
		{
			$id=6;
			$number=strpos($attr,";");
			$clan_id=substr($attr,0,$number);
			$kol=substr($attr,$number+1);
			$cost=check_lr($user_id, $id, $kol);
			if ($cost==-1) //����� ��� ����������! � ���� ��� ��������
			{
				echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ������������ ���������� ������� ��������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
			}
			elseif ($cost==0)
			{
					echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� �� ���������� ���������?</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
			}
			else
			{
				myquery("Update game_clans Set raring=raring+$cost Where clan_id=$clan_id");	
				myquery("Insert into game_lr_services_hist (user_id, serv_id, lr, value) Values ($user_id, $id, $cost, $clan_id)");
				echo'<center><font face=verdana color=white size=2><b>������� ����������!</b></font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
			}
		}		
	}
	
	elseif (isset($id)) //���������� ���� ��� ����� ������ �� ��������� ������
	{
		list($cost)=mysql_fetch_array(myquery("SELECT cost From game_lr_services Where game_lr_services.serv_id=$id"));
		echo '<font size=2 color="lightblue"><b>��������� ������: '.$cost.' '.pluralForm($cost,'�������','�������','������').' ������� ��������</b></font><br/><br/><br/>';
		switch ($id)
		{
			case 1:
			
				echo '<font face=verdana color=white size=2><b>������� ����� ������� ���:</font><br/><br/>
					  <form method="post" action="town.php?option='.$option.'&agree&action=new_name">
					  <input type="text" maxlength="16" name="name"><br/><br/>
					  <input type="submit" value="�������� ���">
					  </form>
					  <br/><font face=verdana color=white size=1><b>(������� ��� ����� ��������� ���� ������� ��������, ��������� � ���� ������������� � ��������� �� 5 �� 16 ��������)</font><br/><br/>
					';
				break;
			
			case 2:
				list($medal_name, $medal_img)=mysql_fetch_array(myquery("SELECT game_medal.nazv, game_medal.image From game_lr_services Join game_medal On game_lr_services.name=game_medal.nazv Where game_lr_services.serv_id=$id"));
				echo '<font face=verdana color=white size=2><b>'.$medal_name.'</font><br/><br/>
				      <img src="http://'.IMG_DOMAIN.'/medal/'.$medal_img.'">	
                      <form method="post" action="town.php?option='.$option.'&agree&action=new_medal"><br/>
				      <input type="submit" value="�������� ������">
				  	  </form>
					  <br/>
					 ';
				break;
				
			case 3:
				$town_list=myquery("SELECT distinct game_gorod.town, game_gorod.rustown
								    FROM game_gorod JOIN game_map ON game_gorod.town = game_map.town
								    WHERE game_gorod.race =0 AND game_gorod.clan =0 AND game_gorod.rustown <> '' AND game_map.name IN ( 5, 18 ) AND game_map.to_map_name=0
								    Order By Binary game_gorod.rustown");
				echo '<font face=verdana color=white size=2><b>�������� �����, � ������� �� ������� ����, �� ������:</font><br/>';
				echo '<form method="post" action="town.php?option='.$option.'&agree&action=new_town"><br/>';
				echo '<select name="town">';
				while ($town=mysql_fetch_array($town_list))
				{
				    echo '<option value='.$town['town'].'>'.$town['rustown'].'</option>';
				 }
				 echo '</select>';
				 echo '<br/><br/><input type="submit" value="������� ����� ����������"></form>';
				 echo '<br/><font face=verdana color=white size=1><b>(��� ����� � ��������� ����� ���������� � ��������� �����)</font><br/><br/>';
				break;
				
			case 4:
				$prof_test=myquery("Select t1.craft_index, t2.name from game_users_crafts as t1 Join game_craft_prof as t2 on t1.craft_index=t2.prof_id
				                    where t1.user_id=$user_id and t1.profile=1 and t1.craft_index not in (1,2)");
				if (mysql_num_rows($prof_test)>0)
				{	
					echo '<font face=verdana color=white size=2><b>�������� ������c��, ������� �� ������ ������, �� ������:</font><br/>';
					echo '<form method="post" action="town.php?option='.$option.'&agree&action=del_prof"><br/>';
					echo '<select name="prof">';
					while ($prof=mysql_fetch_array($prof_test))
					{
						echo '<option value='.$prof['craft_index'].'>'.$prof["name"].'</option>';
					 }
					 echo '</select>';
					 echo '<br/><br/><input type="submit" value="������ ���������"></form>';
					 echo '<br/><font face=verdana color=white size=1><b>(��� ���������� �� ��������� ��������� ����� ��������� � ��� ������� �� ������ ������� ������� ����������)</font><br/><br/>';
				}
				else
				{
					echo '<font face=verdana color=red size=2><b>�� �� ������ �� ����� ���������!</font><br/><br/>';
				}
				break;
			case 5:
				list($user_race)=mysql_fetch_array(myquery("Select race from game_users where user_id=$user_id"));
				$race_list=myquery("Select id, name from game_har where id<>$user_race and disable=0");
				echo '<font face=verdana color=white size=2><b>�������� ����, ������� �� ������ ��������, �� ������:</font><br/>';
				echo '<form method="post" action="town.php?option='.$option.'&agree&action=new_race"><br/>';
				echo '<select name="race">';
				while ($race=mysql_fetch_array($race_list))
				{
					echo '<option value='.$race['id'].'>'.$race['name'].'</option>';
				}
			    echo '</select>';
				echo '<br/><br/><input type="submit" value="������� ����"></form>';
				break;
				
			case 6:
				$clan_list=myquery("Select clan_id, nazv From game_clans where raz=0");
				echo '<font face=verdana color=white size=2><b>�������� ����, ������� �������� �� ������ ��������, �� ������ � ������� ���������� ������������ ��������:</font><br/>';
				echo '<form method="post" action="town.php?option='.$option.'&agree&action=add_clan"><br/>';
				echo '<select name="clan">';
				while ($clan=mysql_fetch_array($clan_list))
				{
					echo '<option value='.$clan['clan_id'].'>'.$clan['nazv'].'</option>';
				 }
			    echo '</select><br/><br/>';
				echo '<input type="text" maxlength="3" name="kol">';
				echo '<br/><br/><input type="submit" value="����������� �������"></form>';
				break;
				
			case 7:
				echo '<font face=verdana color=white size=1><b>������� �������� ���������� ����� �� ������ ������� ������ ����� ��������� � ���� ����������, ���������������� ��������� �������:</font><br/>';
				echo '<a href="/forum/?act=topic&id=5470&page=n" target="_blank">������� �������� ����</a>';
				break;
		}	
		
	}
	else
	{
	echo'<center>
	     <font face=verdana color=ff0000 size=2><b>����� �����</b></font><br/><br/>
		 <font face=verdana color=white size=2><b>������ ����� ����� ����� �������� ��������� �������������� �� ����������� �������</font><br/><br/>
		 <font size=2 color="lightblue">� ���� ����: <u>'.$lr.'</u> '.pluralForm($lr,'�������','�������','������').' ������� ��������</font><br/><br/><br/>
		 <font face=verdana color=white size=2>������ ������������ �����:</b><br/><br/>
		 <table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
		 <td width="40"><b>�</b></td>
		 <td width="300"><b>�������� ������</b></td>
		 <td width="150"><b>��������� � ��</b></td></tr>
		 ';
		 $check=myquery("Select * from game_lr_services Order By cost");
		 $i=1;
		 while ($lr_serv=mysql_fetch_array($check))
		 {
			 echo '<tr align="center"><td>'.$i.'</td><td>';
			 if ($lr<$lr_serv['cost'] or $lr==0) echo $lr_serv['name'];
			 else echo '<a href="town.php?option='.$option.'&id='.$lr_serv['serv_id'].'">'.$lr_serv['name'].'</a>';
			 echo '</td><td>'.$lr_serv['cost'].'</td></tr>';
			 $i++;
		 }	 
		 echo '</table> </center>
		';
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}
if (function_exists("save_debug")) save_debug(); 

?>