<?php
if (isset($_GET['id']))
{
	if (isset($_GET['untake']))
	{
		//������� � ����
        $ves_minus = 0;
        if ($char['vsadnik']!=0)
        {
            $ves_minus = mysqlresult(myquery("SELECT ves FROM game_vsadnik WHERE id=".$char['vsadnik'].""),0,0);
        }
		myquery("UPDATE game_users SET vsadnik=0,CC=CC-$ves_minus WHERE user_id=$user_id");
		myquery("UPDATE game_users_horses SET used=0 WHERE id=".$_GET['id']."");
	}
	if (isset($_GET['take']))
	{
		//�������� �� ����
		$id_horse = mysqlresult(myquery("SELECT horse_id FROM game_users_horses WHERE id=".$_GET['id'].""),0,0);
		$ves = mysqlresult(myquery("SELECT ves FROM game_vsadnik WHERE id=".$id_horse.""),0,0);
		if ($char['vsadnik']!=0)
		{
			$ves_minus = mysqlresult(myquery("SELECT ves FROM game_vsadnik WHERE id=".$char['vsadnik'].""),0,0);
			$ves-=$ves_minus;
		}
		myquery("UPDATE game_users SET vsadnik=$id_horse,CC=CC+$ves WHERE user_id=$user_id");
		myquery("UPDATE game_users_horses SET used=1 WHERE id=".$_GET['id']."");
		myquery("UPDATE game_users_horses SET used=0 WHERE user_id=$user_id AND id<>".$_GET['id']."");
	}
	if (isset($_GET['eat']))
	{
		//������ ����
		$kon = mysql_fetch_array(myquery("SELECT * FROM game_users_horses WHERE id=".$_GET['id'].""));
		if ($kon['golod']>0)
		{
			switch ($kon['golod'])
			{
				case 0: $state= '�����'; $k = 0; break;
				case 1: $state= '������ ��������'; $k = 1; break;
				case 2: $state= '��������'; $k = 2; break;
				case 3: $state= '����� ��������'; $k = 3; break;
				case 4: $state= '������������'; $k = 4; break;
				default: $state= '���������'; $k = 10; break;
			} 
			$koni = mysql_fetch_array(myquery("select * from game_vsadnik where id='".$kon['horse_id']."'"));
			$gp_eat = round($k*$koni['price_eat']*0.75,2);
			if ($char['GP']>=$gp_eat)
			{
				$up=myquery("UPDATE game_users SET GP=GP-$gp_eat,CW=CW-'".($gp_eat*money_weight)."' WHERE user_id=$user_id LIMIT 1");
				setGP($user_id,-$gp_eat,62);
				myquery("UPDATE game_users_horses SET golod=0 WHERE id=".$_GET['id']."");
			}
		}
	}
}
echo '<center><b><font color="white" size="2">'.$templ['name'].'</font></b></center><br /><br />';
echo '� ���� ������� �������:<br />';
$sel = myquery("SELECT game_users_horses.*,game_vsadnik.nazv,game_vsadnik.price_eat,game_vsadnik.life_horse FROM game_users_horses,game_vsadnik WHERE game_users_horses.user_id=$user_id AND game_users_horses.horse_id=game_vsadnik.id");
echo '<table width="90%" border="1" cellspacing="2" cellpadding="5">
<tr><td style="text-align:center;font-weight:800;color:white">��������</td><td style="text-align:center;font-weight:800;color:white">�������� �����</td><td style="text-align:center;font-weight:800;color:white">��������� ������</td><td>&nbsp;</td></tr>';
while ($row = mysql_fetch_array($sel))
{
	switch ($row['golod'])
	{
		case 0: $state= '�����'; $k = 0; break;
		case 1: $state= '������ ��������'; $k = 1; break;
		case 2: $state= '��������'; $k = 2; break;
		case 3: $state= '����� ��������'; $k = 3; break;
		case 4: $state= '������������'; $k = 4; break;
		default: $state= '���������'; $k = 10; break;
	}
	$gp = round($k*$row['price_eat']*0.75,2); 
	echo '<tr><td>'.$row['nazv'].'';
	if ($row['used']==1)
	{
		echo '<br /><i>(�������)</i>';
	}
	echo '</td><td>'.($row['life_horse']-$row['life']).' / '.$row['life_horse'].' ������� ���.</td><td>'.$state.'</td><td>';
	//������ ��������
	if ($row['golod']>0)
	{
		echo '<a href="town.php?option='.$option.'&part4&add='.$build_id.'&id='.$row['id'].'&eat">��������� (����: '.$gp.' �����)</a><br /><br />';
	}
	if ($row['used']==0)
	{
		echo '<a href="town.php?option='.$option.'&part4&add='.$build_id.'&id='.$row['id'].'&take">��������</a>';
	}
	else
	{
		echo '<a href="town.php?option='.$option.'&part4&add='.$build_id.'&id='.$row['id'].'&untake">�������� � '.$templ['name'].'</a>';
	}
	echo '&nbsp;</td></tr>';
}
echo '</table><br /><br /><br />';
$max_horse = 1;
if ($build_id==6) $max_horse = 2;
if ($build_id==7) $max_horse = 3;
if ($build_id==8) $max_horse = 4;
echo '���������� ��������, ������� �� ������ ��������� - '.$max_horse.'<br /><br /><br />';
?>