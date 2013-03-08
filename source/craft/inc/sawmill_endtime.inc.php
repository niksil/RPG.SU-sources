<?php
//ЛЕСОПИЛКА - ОКОНЧАНИЕ
$add_query = '';
$sawmill_level = getCraftLevel($user_id,7);
$add_doska = 1;
$add_strela = 2;
$add_topor = 1;
$add_kopye = 1;
if ($sawmill_level>=4)
{
	$add_doska++; $add_kopye++; $add_strela++; $add_strela++; $add_topor++;
}
if ($sawmill_level>=9)
{
	$add_doska++; $add_kopye++; $add_strela++; $add_strela++; $add_topor++;
}
if ($sawmill_level>=14)
{
	$add_doska++; $add_kopye++; $add_strela++; $add_strela++; $add_topor++;
}
if ($sawmill_level>=19)
{
	$add_doska++; $add_kopye++; $add_strela++; $add_strela++; $add_topor++;
}

$kol_res_in = 0;
$kol_res_out = 1;
$res_id_out = 0;
$res_id_in = 0;
switch ($rab['eliksir'])
{
	case '1':
	{
		//распилка бревна на доски
		setCraftTimes($user_id,7,1,1);
		$kol_res_in = $add_doska;
		$res_id_in = $id_resource_doska;
		$res_id_out = $id_resource_brevno;
	}
	break;
	
	case '2':
	{
		//приготовление черенков стрел из досок
		$kol_res_in = $add_strela;
		$res_id_in = $id_resource_strela;
		$res_id_out = $id_resource_doska;
	}
	break;
	
	case '3':
	{
		//приготовление рукоятей топоров из досок
		$kol_res_in = $add_topor;
		$res_id_in = $id_resource_topor;
		$res_id_out = $id_resource_doska;
	}
	break;
	
	case '4':
	{
		//приготовление древок копий из досок
		$kol_res_in = $add_kopye;
		$res_id_in = $id_resource_kopye;
		$res_id_out = $id_resource_doska;
	}
	break;
	
	default: exit; break;
}  
$res_out = mysql_fetch_array(myquery("SELECT weight,name FROM craft_resource WHERE id=$res_id_out"));
$res_in = mysql_fetch_array(myquery("SELECT weight,name FROM craft_resource WHERE id=$res_id_in"));
$change_weight = $res_in['weight']*$kol_res_in-$res_out['weight'];
$prov=mysql_result(myquery("select count(*) from game_wm where user_id=$user_id and type=1"),0,0);
if ($char['CC']-$char['CW']>$change_weight OR $prov>0)
{
	list($kol) = mysql_fetch_array(myquery("SELECT col FROM craft_resource_user WHERE user_id=$user_id AND res_id=".$res_id_out.""));
	if ($kol>1)
	{
		myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-1) WHERE user_id=$user_id AND res_id=$res_id_out");
	}
	else
	{
		myquery("DELETE FROM craft_resource_user WHERE user_id=$user_id AND res_id=$res_id_out");
	}
	myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_out, 0, -1, ".time().", $user_id, 'z')"); 
	myquery("insert into craft_resource_user (user_id, res_id, col) values ($user_id, $res_id_in, $kol_res_in) ON DUPLICATE KEY UPDATE col=col+$kol_res_in");
	myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_in, 0, 1, ".time().", $user_id, 'z')"); 
	$add_query.=',CW=CW+'.$change_weight.'';
	mt_srand(make_seed());
	myquery("UPDATE game_items SET item_uselife=item_uselife-".(mt_rand(400,600)/100)." WHERE user_id=$user_id AND used=21 AND priznak=0");
	list($pila_id,$pila) = mysql_fetch_array(myquery("SELECT id,item_uselife FROM game_items WHERE user_id=$user_id AND used=21 AND priznak=0"));
	if ($pila<=0)
	{
		$Item = new Item($pila_id);
		$Item->down();    
	}
	//setCraftTimes($user_id,7,1,1);
	$mes='Израсходован ресурс: <i>'.$res_out['name'].'</i> в количестве 1 ед. <br/>Получен предмет(ресурс): <i>'.$res_in['name'].'</i> в количестве '.$kol_res_in.' ед.';
}
else
{
	$mes='Неудачная попытка работы на лесопилке. Проверь, хватает ли у тебя места для новых предметов в инвентаре!';
}

if ($rab['add']>0)	
{
	$option = 18;
	//$option=19;
	$url = 'lib/town.php?option='.$option.'&part4&add='.$rab['add'].'&mes='.$mes;
}
else
{
	$url = 'act.php?func=main&act=01&sawmill&mes='.$mes;
}
setLocation($url);
exit_from_craft($add_query,1);
if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}
{if (function_exists("save_debug")) save_debug(); exit;}
?>