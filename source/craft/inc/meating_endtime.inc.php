<?php
$meating_level = getCraftLevel($user_id,9);

$kol_res_in = 0;
$kol_res_out = 1;
$res_id_out = $id_resource_olencorpse;
$res_id_in = 0;
$add_query = '';

mt_srand(make_seed());
//if ($rand<=$chance_olenjily)
//{
	$chance_olenkoja = 10+2*$meating_level;
	$chance_olenkosti = 25+2*$meating_level;
	$chance_olenjily = 30+2*$meating_level;
	$max_chance=100;
	if ($chance_olenkoja+$chance_olenkosti+$chance_olenjily>100)
	{
		$max_chance=$chance_olenkoja+$chance_olenkosti+$chance_olenjily;	
	}
	$rand = mt_rand(0,$max_chance);
	if ($rand>0 AND $rand<=$chance_olenkoja)
	{
		//получаем оленью кожу
		$kol_res_in = 1;
		$res_id_in = $id_resource_olenkoja;
	}
	elseif ($rand>$chance_olenkoja AND $rand<=$chance_olenkosti+$chance_olenkoja)
	{
		//получаем оленью кость
		$kol_res_in = 1;
		$res_id_in = $id_resource_olenkosti;
	}
	elseif ($rand>$chance_olenkosti+$chance_olenkoja AND $rand<=$chance_olenjily+$chance_olenkosti+$chance_olenkoja)
	{
		//получаем оленьи жилы
		$kol_res_in = 1;
		$res_id_in = $id_resource_olenjily;
	}
//}
$res_out = mysql_fetch_array(myquery("SELECT weight,name FROM craft_resource WHERE id=$res_id_out"));
$res_in = mysql_fetch_array(myquery("SELECT weight,name FROM craft_resource WHERE id=$res_id_in"));
$change_weight = $res_in['weight']*$kol_res_in-$res_out['weight']*$kol_res_out;
$prov=mysql_result(myquery("select count(*) from game_wm where user_id=$user_id and type=1"),0,0);
$kol = mysqlresult(myquery("SELECT col FROM craft_resource_user WHERE user_id=$user_id AND res_id=".$res_id_out.""),0,0);
if ($kol>1)
{
	myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-1) WHERE user_id=$user_id AND res_id=$res_id_out");
}
else
{
	myquery("DELETE FROM craft_resource_user WHERE user_id=$user_id AND res_id=$res_id_out");
}
myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_out, 0, -1, ".time().", $user_id, 'z')");     
mt_srand(make_seed());
$mes='Израсходован ресурс: <i>'.$res_out['name'].'</i> в количестве 1 ед.<br />';
myquery("UPDATE game_items SET item_uselife=item_uselife-".(mt_rand(100,250)/100)." WHERE user_id=$user_id AND used=21 AND priznak=0");
list($id_item,$cur_uselife) = mysql_fetch_array(myquery("SELECT id,item_uselife FROM game_items WHERE priznak=0 AND user_id=$user_id AND used=21"));
if ($cur_uselife<=0)
{
	$Item = new Item($id_item);
	$Item->down();
} 
$add_query.=',CW=CW+'.$change_weight.'';
if ((($char['CC']-$char['CW'])>$change_weight OR $prov>0) AND $kol_res_in>0)
{  
	myquery("insert into craft_resource_user (user_id, res_id, col) values ($user_id, $res_id_in, $kol_res_in) ON DUPLICATE KEY UPDATE col=col+$kol_res_in");
	myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_in, 0, 1, ".time().", $user_id, 'z')");
	setCraftTimes($user_id,9,1,1);
	$mes.='<br />Получен предмет(ресурс): <i>'.$res_in['name'].'</i> в количестве '.$kol_res_in.' ед.';
}
else
{
	echo '<br /><b>Неудачная попытка работы в разделочном цеху.</b><br />';
}
$option = 18;
//$option=19;
$url = 'lib/town.php?option='.$option.'&part4&add=22&mes='.$mes;
setLocation($url);
//echo'<meta http-equiv="refresh" content="10;url='.$url.'">';
exit_from_craft($add_query, 1);
?>