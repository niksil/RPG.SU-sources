<?
$dirclass = "../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

$SEL = myquery("SELECT game_users_archive.user_id,game_users_archive.name,game_users_archive.clevel,game_users_archive.EXP FROM game_users_archive,game_users_data WHERE game_users_archive.user_id=game_users_data.user_id AND game_users_archive.clan_id=0 AND game_users_archive.clevel<=7 AND game_users_data.last_visit<".(time()-30.4*4*24*60*60)." ORDER BY game_users_archive.user_id ASC");

myquery("SET wait_timeout=1800");

$num = 0;
while ($usr=mysql_fetch_array($SEL))
{
	// Формула накопленного опыта
	$level=$usr['clevel'];
	$i=0;
	$allexp=$usr['EXP'];
	for($i;$i<=$level-1;$i++)
	{
		if ($i == 0)
		{
			$exp = 200;
		}
		else
		{
			$exp = $i*($i+1)*200;
		}
		$allexp+=$exp;
	}
	if ($allexp>=22600)
	{
		continue;
	}
	$num++;
    mysql_query("SET AUTOCOMMIT=0");
    mysql_query("START TRANSACTION");
	admin_delete_user($usr['user_id']);
    mysql_query("COMMIT");    
    mysql_query("SET AUTOCOMMIT=1");
	echo $num.'. '.$usr['user_id'].'&nbsp;&nbsp;&nbsp;'.$usr['name'].'&nbsp;&nbsp;&nbsp;'.$usr['clevel'].'&nbsp;&nbsp;&nbsp;'.$usr['EXP'].'<br />';
} 

?>