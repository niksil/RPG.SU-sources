<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['nakaz'] >= 1)
{
	if (isset($delete)) 
	{
		echo 'Наказание игрока удалено<br>';
		$nakaz = mysql_fetch_array(myquery("SELECT * FROM game_nakaz WHERE id='$delete'"));
		$del = $nakaz['user_id'];
		$name = get_user('name',$del);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Удалил наказание игрока: <b>".$name."</b> (Закон №".$nakaz['id_zakon']." поставлен ".$nakaz['date_nak']." администратором ".get_user('name',$nakaz['adm']).")',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		$up = myquery("DELETE FROM game_nakaz WHERE id='$delete'");
	}
	 if (!isset($sort)) 
	{   
		$sort = 'id';
		$napr = 'DESC';
	}
	$query = "SELECT * FROM game_nakaz ORDER BY ".$sort." ".$napr."";
	$query1 = "SELECT COUNT(*) FROM game_nakaz ORDER BY ".$sort." ".$napr."";
	if (!isset($page)) $page=1;
	$page=(int)$page;
	$line=25;
	$result = myquery($query1);
	$allpage=ceil(@mysql_result($result,0,0)/$line);
	if ($page>$allpage) $page=$allpage;
	if ($page<1) $page=1;
	$query.=" limit ".(($page-1)*$line).", $line";
	$result = myquery($query);
	echo '<table>
	<tr>
			<td><a href="admin.php?opt=main&option=nakaz&napr=ASC&sort=user_id">Игрок</a></td>
			<td><a href="admin.php?opt=main&option=nakaz&napr=ASC&sort=id_zakon">№ закона</a></td>
			<td><a href="admin.php?opt=main&option=nakaz&napr=DESC&sort=date_nak">Дата</a></td>
			<td><a href="admin.php?opt=main&option=nakaz&napr=DESC&sort=date_zak">Длит.</a>        </td>
			<td><a href="admin.php?opt=main&option=nakaz&napr=ASC&sort=adm">Админ</a></td>
			<td><a href="admin.php?opt=main&option=nakaz&napr=ASC&sort=text">Описание бана</a></td>
			</td><td>Удал.</td>
	</tr>';

	$i=0;
	while ($nakaz = mysql_fetch_array($result))
	{
		$i++;
		$color = '#585858';
		if ($i%2==0) $color='#2D2D2D';
			$name = get_user('name',$nakaz['user_id']);
			echo '<tr>
					<td bgcolor='.$color.'>'.$name.'</td>
					<td bgcolor='.$color.'>'.mysqlresult(myquery("SELECT name FROM game_zakon WHERE id=".$nakaz['id_zakon'].""),0,0).'</td>
					<td bgcolor='.$color.'>'.$nakaz['date_nak'].'</td>
					<td bgcolor='.$color.'>';
					if ($nakaz['nakaz']=='ban')
					{
						$bantime = '';
						if ($nakaz['date_zak']/360<1) $bantime = ''.round($nakaz['date_zak']/60,0).' мин.';
						elseif ($nakaz['date_zak']/8640<1) 
						{
							$hour = floor($nakaz['date_zak']/360);
							$minute = round(($nakaz['date_zak']-$hour*360)/60,0);
							$bantime = ''.$hour.' час. '.$minute.' мин.';
						}
						else 
						{
							$day = floor($nakaz['date_zak']/8640); 
							$nakaz['date_zak'] = $nakaz['date_zak']-$day*8640;
							$hour = floor($nakaz['date_zak']/360);
							$minute = round(($nakaz['date_zak']-$hour*360)/60,0);
							$bantime = ''.$day.' дн. '.$hour.' час. '.$minute.' мин.';
						}
						echo $bantime;
					}
					elseif ($nakaz['nakaz']=='prison')
					{
						echo ''.$nakaz['date_zak'].' обор.';
					}
					echo '</td>
					<td bgcolor='.$color.'>'.get_user('name',$nakaz['adm']).'</td>
					<td bgcolor='.$color.'>'.$nakaz['text'].'</td>
					<td bgcolor='.$color.'><a href="admin.php?opt=main&option=nakaz&delete='.$nakaz['id'].'"><img src="http://'.IMG_DOMAIN.'/nav/show.gif" border="0" width="20"></a></td>
					</tr>';
	}

	echo '</table>';
	$href="admin.php?opt=main&option=nakaz&sort=$sort&napr=$napr&";
	echo'<center>Страница: ';
	show_page($page,$allpage,$href);
}

if (function_exists("save_debug")) save_debug(); 

?>