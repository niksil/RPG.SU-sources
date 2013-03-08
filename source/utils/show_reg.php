<?
$dirclass = "../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();
?>
Просмотр зарегистрированных игроков за опр.дату:<br />
<form action="" method="get">
<input type="text" name="data" value="
<?
if (isset($_GET['data']))
{
    echo $_GET['data'];
}
else
{
    echo '0000-00-00';
}
?>
" size="15" maxsize="10">Введите дату регистрации в формате ГГГГ-ММ-ДД<br />
<input type="submit" value="Показать список игроков" name="submit">
</form>
<?
if (isset($_GET['data']))
{
    $sql = myquery("SELECT user_id,name,clevel FROM game_users WHERE user_id IN (SELECT user_id FROM game_users_data WHERE DATE_FORMAT(FROM_UNIXTIME( `rego_time` ),'%Y-%m-%d')='".$_GET['data']."') ORDER BY name");
    echo '<table cellspacing="2" cellpadding="2" border="1">';
    echo '<tr><td>№</td><td>ID</td><td>Имя</td><td>Уровень</td></tr>';
    $i = 0;
    while ($row = mysql_fetch_array($sql))
    {
        $i++;
        echo '<tr><td>'.$i.'</td><td>'.$row['user_id'].'</td><td>'.$row['name'].'</td><td>'.$row['clevel'].'</td></tr>';
    }
    echo '<table>';
}