<?
$dirclass="../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect(); 

if (!isset($_GET['day']))
{
    $day = 2;
}
else
{
    $day = $_GET['day'];
}
$cur_time=time()-24*$day*60*60;
echo 'Начало<br>';
$sel = myquery("SELECT name FROM game_users WHERE user_id IN
(
SELECT user_id from game_users_data where (FROM_UNIXTIME(rego_time , '%Y %D %M' ) LIKE '%2006%15%Oct%') OR (FROM_UNIXTIME(rego_time , '%Y %D %M' ) LIKE '%2006%16%Oct%')
)
AND user_id IN
(
SELECT user_id FROM game_users_data WHERE last_visit>=$cur_time
)");
$count = 0;
while (list($name) = mysql_fetch_array($sel))
{
    $count++;
    echo $count.'. '.$name.'<br>';
}

echo 'Завершено';
?>