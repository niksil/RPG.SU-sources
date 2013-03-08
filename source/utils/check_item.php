<?
$dirclass = "../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

error_reporting("E_ALL");

echo 'Сверка предметов из энциклопедии и у игроков:<br>';

function PrintError($param,$it,$encik)
{
	if ($it[$param]!=$encik[$param])
	{
		list($name) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id=".$it['user_id'].") UNION (SELECT name FROM game_users_archive WHERE user_id=".$it['user_id'].")"));
		echo'
		<tr><td>'.$it['type'].'</td><td>'.$it['ident'].'</td><td>'.$name.'</td><td>'.$param.'</td><td>'.$encik[$param].'</td><td>'.$it[$param].'</td><td>'.($encik[$param]-$it[$param]).'</td></tr>';
	}
}

echo '<br><br><br>
<table cellspacing="2" cellpadding="2" border="1">
<tr><td>Тип предмета</td><td>Предмет</td><td>У игрока</td><td>Сравнивается</td><td>По энциклопедии</td><td>У игрока</td><td>Разница</td><td></tr>';

$sel = myquery("SELECT * FROM game_items");
while ($it=mysql_fetch_array($sel))
{
	if ($it['type']=='wm')
	{
		continue;
	}
	if ($it['user_id']==0)
	{
		continue;
	}
	$encik = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE name='".$it['ident']."'"));
	PrintError('type',$it,$encik);
	PrintError('indx',$it,$encik);
	PrintError('deviation',$it,$encik);
	PrintError('mode',$it,$encik);
	PrintError('weight',$it,$encik);
	PrintError('ostr',$it,$encik);
	PrintError('ontl',$it,$encik);
	PrintError('opie',$it,$encik);
	PrintError('ovit',$it,$encik);
	PrintError('odex',$it,$encik);
	PrintError('ospd',$it,$encik);
	PrintError('oclevel',$it,$encik);
	PrintError('dstr',$it,$encik);
	PrintError('dntl',$it,$encik);
	PrintError('dpie',$it,$encik);
	PrintError('dvit',$it,$encik);
	PrintError('ddex',$it,$encik);
	PrintError('dspd',$it,$encik);
	PrintError('sv',$it,$encik);
	PrintError('race',$it,$encik);
	PrintError('hp_p',$it,$encik);
	PrintError('mp_p',$it,$encik);
	PrintError('stm_p',$it,$encik);
	PrintError('cc_p',$it,$encik);
}

echo '</table><br><br><br>Завершено';
?>