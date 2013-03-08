<?php
$id_item_pila = 482;//id предмета "пила"
$id_item_klin = 484;//id предмета КЛИН из энциклопедии
$id_item_knife = 479;//id предмета РАЗДЕЛОЧНЫЙ НОЖ 
$id_item_orujeinik = 480;//id предмета НАБОР ОРУЖЕЙНИКА
$id_item_founder = 481;//id предмета КОВШ ЛИТЕЙЩИКА
$id_item_topor = 483;//id предмета ТОПОР ЛЕСОРУБА
$id_item_kaylo = 485;//id предмета КАЙЛО КАМЕНОТЕСА
$id_item_kirka = 486;//id предмета КИРКА

$id_resource_doska = 56;//id ресурса "ДОСКА"
$id_resource_brevno = 49;//id ресурса БРЕВНО
$id_resource_blok = 50;//id ресурса КАМЕННЫЙ БЛОК
$id_resource_strela = 57;//id ресурса ЧЕРЕНКИ ДЛЯ СТРЕЛ
$id_resource_topor = 58;//id ресурса РУКОЯТИ ДЛЯ ТОПОРОВ
$id_resource_kopye = 59;//id ресурса ДРЕВКИ ДЛЯ КОПИЙ
$id_resource_olencorpse = 68;//id ресурса ТУША ОЛЕНЯ
$id_resource_olenkoja = 60;//id ресурса ОЛЕНЬЯ КОЖА
$id_resource_olenkosti = 61;//id ресурса ОЛЕНЬИ КОСТИ
$id_resource_olenjily = 62;//id ресурса ОЛЕНЬИ ЖИЛЫ
$id_resource_water = 25;//id ресурса ВОДА
$id_resource_coal = 8;//id ресурса УГОЛЬ
$id_resource_iron_ore = 1;//id ресурса Железная руда
$id_resource_copper_ore = 2;//id ресурса Медная руда
$id_resource_silver_nugget = 11;//id ресурса Серебрянный самородок
$id_resource_mithril_ore = 12;//id ресурса Мифрильная руда
$id_resource_gold_nugget = 51;//id ресурса Золотой самородок
$id_resource_iron_bullion = 63;//id ресурса Железный слиток
$id_resource_copper_bullion = 64;//id ресурса Медный слиток
$id_resource_silver_bullion = 66;//id ресурса серебрянный слиток
$id_resource_mithril_bullion = 65;//id ресурса мифрильный слиток
$id_resource_gold_bullion = 67;//id ресурса Золотой слиток
$id_resource_izumrud = 53;//id ресурса Изумруд
$id_resource_rubin = 54;//id ресурса Рубин
$id_resource_almaz = 55;//id ресурса Алмаз

$id_resource_for_founder = "".$id_resource_iron_ore.",".$id_resource_copper_ore.",".$id_resource_silver_nugget.",".$id_resource_mithril_ore.",".$id_resource_gold_nugget; //id ресурсов, которые можно положить в плавильную печь

function craft_setFunc($user_id,$func_id)
{

	$sel_race = myquery("INSERT IGNORE craft_user_func (user_id,func_id,func_sub_id,time_stamp) VALUES ('$user_id','".$func_id."','0','".time()."')");
	return 1;
}

function craft_getFunc($user_id)
{
	$sel_rid = myquery("SELECT func_id,time_stamp FROM craft_user_func WHERE user_id = '".$user_id."' ");
	if(mysql_num_rows($sel_rid)==0)
	{
		return 0; 
	}
	else
	{
		$arr_rid = mysql_fetch_array($sel_rid);
		//if ($arr_rid['time_stamp']<time())
		//{
		//    return 0;
		//}
		return $arr_rid['func_id'];
	}
}


function craft_DelFunc($user_id)
{
	myquery("DELETE FROM craft_user_func WHERE user_id='$user_id'");
	return 1;
}
?>