<?php
$eliksir = CreateArrayForCraftEliksir();
if ($rab['eliksir']>=0 AND $rab['eliksir']<sizeof($eliksir))
{
	//зелье приготовлено
	$i = $rab['eliksir'];
	$change_weight = 0;
	//добавл€ем эликсир
	$check = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND item_id=".$eliksir[$i]['item_id']." AND used=0");
	if (!mysql_num_rows($check))
	{
		myquery("INSERT INTO game_items (user_id,item_id,priznak,ref_id,count_item) VALUES ($user_id,".$eliksir[$i]['item_id'].",0,0,1)");
	}
	else
	{
		list($id_items) = mysql_fetch_array($check);
		myquery("UPDATE game_items SET count_item=count_item+1 WHERE id=$id_items");
	}
	$change_weight=$change_weight+$eliksir[$i]['weight'];
	myquery("delete from craft_build_rab where user_id=$user_id"); 
	//колба удал€етс€ при начале варки
	//list($kolba_weight)=mysql_fetch_array(myquery("SELECT weight FROM game_items_factsheet WHERE id=".kolba_item_id." LIMIT 1"));
	//$change_weight=$change_weight-$kolba_weight;
	//myquery("DELETE FROM game_items WHERE user_id=$user_id AND used=0 AND ref_id=0 AND item_id=".kolba_item_id." AND priznak=0 LIMIT 1");
	//ресурсы удал€ют€ при начале варки
	/*
	for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
	{
		$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$eliksir[$i]['resource'][$j]['id'].""));
		list($kol) = mysql_fetch_array(myquery("SELECT col FROM craft_resource_user WHERE user_id=$user_id AND res_id=".$eliksir[$i]['resource'][$j]['id'].""));
		$change_weight=$change_weight-($eliksir[$i]['resource'][$j]['kol']*$ress['weight']);
		if ($kol>$eliksir[$i]['resource'][$j]['kol'])
		{
			myquery("UPDATE craft_resource_user SET col=col-".$eliksir[$i]['resource'][$j]['kol']." WHERE user_id=$user_id AND res_id=".$eliksir[$i]['resource'][$j]['id']."");
		}
		else
		{
			myquery("DELETE FROM craft_resource_user WHERE user_id=$user_id AND res_id=".$eliksir[$i]['resource'][$j]['id']."");
		}
	}
	*/
	$add_query = ',CW=CW+'.$change_weight.'';
	setCraftTimes($user_id,2,1,1);
	$mes='“ы успешно '.echo_sex('приготовил','приготовила').' зелье: <font color=red size=2>'.$eliksir[$i]['name'].'</b></font>';
	
}
else
{
	$mes='¬роде бы '.echo_sex('должен','должна').' сварить элексир. Ќо у теб€ что-то не получилось, и ты ничего не '.echo_sex('сварил','сварила').'.';
}
if (isset($_GET['house']))
{
	$option = 18;
	//$option=19;
	$url = 'lib/town.php?option='.$option.'&part4&add=21&mes='.$mes;
	setLocation($url);
}
else
{
	$url = 'quest/alchemist.php?begin&mes='.$mes;
	setLocation($url);
}
?>
