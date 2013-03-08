
Просмотр зарегистрированных игроков за опр.дату:<br />
<form action="" method="post" name="forma">
<input type="text" name="data" value="
<?
if (isset($_REQUEST['data']))
{
    echo $_REQUEST['data'];
}
else
{
    echo date("Y-m-d");
}
?>
" size="15" maxsize="10">Введите дату регистрации в формате ГГГГ-ММ-ДД<br />
<input type="submit" value="Показать список игроков" name="submit">
</form>
<script type="text/javascript">
function del_user(name)
{
    fr = document.getElementById("delid_user");
    fr.src = '/admin.php?opt=main&option=users_del&see&name='+name;
}
</script>
<?
if (isset($_REQUEST['data']))
{
    if (!isset($_REQUEST['order']))
    {
        $order = 'user_id';
    }
    else
    {
        $order = $_REQUEST['order'];
    }
    $sql = myquery("SELECT game_users.user_id,game_users.name,game_users.clevel,game_users_data.rego_time,game_users_data.last_visit,game_users_active.last_active,game_users_active.host,IFNULL(game_invite.user_id,0) AS invitor FROM (game_users,game_users_data,game_users_active) LEFT JOIN game_invite ON (game_invite.invite_id=game_users.user_id) WHERE DATE_FORMAT(FROM_UNIXTIME( `rego_time` ),'%Y-%m-%d')='".$_REQUEST['data']."' AND game_users.user_id=game_users_data.user_id AND game_users_active.user_id=game_users.user_id ORDER BY $order");
    echo '<table cellspacing="2" cellpadding="2" border="1">';
    echo '<tr><td>№</td><td><a href="http://'.DOMAIN.'/admin.php?opt=main&option=show_reg&data='.$_REQUEST['data'].'&order=user_id">ID</a></td><td><a href="http://'.DOMAIN.'/admin.php?opt=main&option=show_reg&data='.$_REQUEST['data'].'&order=name">Имя</a></td><td><a href="http://'.DOMAIN.'/admin.php?opt=main&option=show_reg&data='.$_REQUEST['data'].'&order=clevel">Уровень</a></td><td><a href="http://'.DOMAIN.'/admin.php?opt=main&option=show_reg&data='.$_REQUEST['data'].'&order=host">IP</a></td><td><a href="http://'.DOMAIN.'/admin.php?opt=main&option=show_reg&data='.$_REQUEST['data'].'&order=rego_time">рег</a></td><td><a href="http://'.DOMAIN.'/admin.php?opt=main&option=show_reg&data='.$_REQUEST['data'].'&order=last_visit">Посл.вход</a></td><td>в игре</td><td>id ref</td><td></td></tr>';
    $i = 0;
    while ($row = mysql_fetch_array($sql))
    {
        $i++;
        if($row['last_active']-$row['last_visit']<0)
        {        	$hours=0;
           	$minutes=0;
        }
        else
        {
			$hours=floor(($row['last_active']-$row['last_visit'])/3600);
			$minutes=floor(($row['last_active']-$row['last_visit'])/60-$hours*60);
		}
        echo '<tr><td>'.$i.'</td><td>'.$row['user_id'].'</td><td>'.$row['name'].'</td><td>'.$row['clevel'].'</td><td>'.number2ip($row['host']).'</td><td>'.date("H:i",$row['rego_time']).'</td><td>'.date("Y-m-d H:i",$row['last_visit']).'</td><td>'.$hours.':'.$minutes.'</td><td>'.$row['invitor'].'</td><td><input type="button" onClick="del_user(\''.$row['name'].'\')" value="Удалить игрока"></td></tr>';
    }
    echo '</table>';
    echo '<iframe id="delid_user" name="dele_user" src="" style="width:100%;height:50px"></iframe>';
}
else
{// Отображаем общую таблицу дата - количество регистраций
    $sql = myquery("SELECT DATE_FORMAT( FROM_UNIXTIME( game_users_data.rego_time ) , '%Y-%m-%d' ) AS rego, count( game_users_data.user_id ) AS cnt, count( game_invite.id ) AS inv_cnt, ( SELECT count(game_stats_timemarker.id) FROM game_stats_timemarker WHERE DATE_FORMAT( FROM_UNIXTIME( game_users_data.rego_time ) , '%Y-%m-%d' )=DATE_FORMAT( FROM_UNIXTIME( game_stats_timemarker.time_stamp ) , '%Y-%m-%d' ) AND game_stats_timemarker.reason=1) as exit_cnt FROM game_users_data LEFT JOIN game_invite ON game_invite.invite_id = game_users_data.user_id GROUP BY rego ORDER BY rego DESC LIMIT 14");
    echo '<table cellspacing="2" cellpadding="2" border="1">';
    echo '<tr><td>№</td><td>Дата</td><td>Количество регистраций</td><td>Приглашенных</td><td>Выходы из ГН</td></tr>';
    $i = 0;
    while ($row = mysql_fetch_array($sql))
    {
        $i++;
        echo '<tr><td>'.$i.'</td><td>'.$row['rego'].'</td><td>'.$row['cnt'].'</td><td>'.$row['inv_cnt'].'</td><td>'.$row['exit_cnt'].'</td></tr>';
    }
    echo '</table>';

}