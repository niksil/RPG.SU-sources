<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['ban'] >= 2)
{
	echo '<center>';
	if (isset($new))
	{
		if (isset($add))
		{
			if (isset($min_ip) and isset($max_ip) and isset($time) and isset($reason) and $time>=0)
			{
				$min=ip2number($min_ip);
				$max=ip2number($max_ip);
				if ($min==0 or $max==0)
				{
					echo 'Ip ������� �������!';
				}
				else
				{
					if ($time==0) 
					{
						$ban=-1;
					}
					else
					{
						$ban=time()+$time*60;
					}
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
						VALUES (
						 '".$char['name']."',
						 '������� ��������: <b>".$min_ip."-".$max_ip."</b>',
						 '".time()."',
						 '".$da['mday']."',
						 '".$da['mon']."',
						 '".$da['year']."')")
							 or die(mysql_error());
					myquery("Insert Into game_ban (user_id,time,ip,adm,za,type) values ($min,$ban,$max,'".$char['name']."','".$reason."','1')");
					echo '�������� �������!';
				}
			}
			else
			{
				echo '�������� ������ ������� �������!';
			}
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bandiap">';
		}
		else
		{
			echo '<b>�������� ��������</b><br><br>';
			echo '<form method="post" action="admin.php?opt=main&option=bandiap&new&add">';
			echo '<table cellspacing="5" cellpadding="0" border="0">';
			echo '<tr align="center"><td width="150">������ �������:</td><td width="300"><input name="min_ip" type="text" size=20></td></tr>';
			echo '<tr align="center"><td>������� �������:</td><td><input name="max_ip" type="text" size=20></td></tr>';
			echo '<tr align="center"><td>����� ���� (������):</td><td><input name="time" type="text" value="0" size=20></td></tr>';
			echo '<tr align="center"><td>�����������:</td><td><textarea name="reason" cols="70" class="input" rows="8"></textarea></td></tr>';	
			echo '</table>';
			echo '<br><br><input name="" type="submit" value="��������">';			
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="" type="button" value="�����" onClick="location.href=\'admin.php?opt=main&option=bandiap\'">';
			echo '</form>';
			echo '<br><br><i>��� ������� ���� "0", �������� ������� ��������!</i>';
		}
	}
	elseif (isset($del))
	{
		list($min_ip,$max_ip)=mysql_fetch_array(myquery("Select user_id, ip From game_ban Where id=$del"));
		$min=number2ip($min_ip);
		$max=number2ip($max_ip);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				VALUES (
				 '".$char['name']."',
				 '������ ��� ���������: <b>".$min."-".$max."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
		myquery("Delete from game_ban Where id=$del");
		echo '��� ��������� �����!';
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bandiap">';
	}
	else
	{
		echo "<a href=admin.php?opt=main&option=bandiap&new>�������� ��������</a></br/>";
		$check_bans=myquery("Select * From game_ban Where type=1 and (time>".time()." or time=-1) Order by id desc");
		if (mysql_num_rows($check_bans)>0)
		{
			echo '<br><br><b>������ ��������� ����������</b><br><br>';
			echo '<table border="1"><tr align="center">
			<td width="50">�����</td>
			<td width="200">��������</td>			
			<td width="120">����� ���������</td>
			<td width="250">�������</td>
			<td width="70">��������</td>
			</td>';
			while($ban=mysql_fetch_array($check_bans))
			{
				echo '<tr align="center">';
				echo '<td>'.$ban['adm'].'</td>';
				echo '<td>'.number2ip($ban['user_id']).'-'.number2ip($ban['ip']).'</td>';
				if ($ban['time']!=-1) echo '<td>'.date("H:i d.m.Y",$ban['time']).'</td>';
				else echo'<td><b>��������</b></td>';
				echo '<td>'.$ban['za'].'</td>';
				echo '<td><input type="button"  style="width: 80px" onClick="location.href=\'admin.php?opt=main&option=bandiap&del='.$ban['id'].'\'" value="�������"></td>';
				echo '</tr>';
			}
			echo '</table><br>';
		}
	}
	echo '</center>';
}

if (function_exists("save_debug")) save_debug(); 

?>