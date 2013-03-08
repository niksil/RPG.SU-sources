<?
$dirclass = "../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

echo '<table>
<tr><td>old user_id</td><td>old name</td><td>cur name</td>';

$sel = myquery("(SELECT user_id,name FROM old_game_users) UNION (SELECT user_id,name FROM old_game_users_archive)");
while (list($id,$name) = mysql_fetch_array($sel))
{
$check = myquery("(SELECT user_id,name FROM game_users WHERE name='".$name."') UNION (SELECT user_id,name FROM game_users_archive WHERE name='".$name."')");
if (!mysql_num_rows($check))
{
$check_id = myquery("(SELECT user_id,name FROM game_users WHERE user_id='".$id."') UNION (SELECT user_id,name FROM game_users_archive WHERE user_id='".$id."')");
if (mysql_num_rows($check_id))
{
$cur = mysql_fetch_array($check_id);
echo '<tr><td>'.$id.'</td><td>'.$name.'</td><td>'.$cur['name'].'</td>';
}
else
{
echo '<tr><td>'.$id.'</td><td>'.$name.'</td>';
}
}
}


echo '</table><br><br><br>Завершено';
?>