<?php

function ImageItem($img,$item_id,$kleymo=-1,$align="",$title="",$alt="")
{
	//$img - полный url картинки предмета
	//$item_id - ID предмета из game_items
	//$kleymo - признак клейма, если не передается, то признак достается запросом из БД
	if ($kleymo==-1)
	{
		list($kleymo)=mysql_fetch_array(myquery("SELECT kleymo FROM game_items WHERE id=$item_id"));
	} 
	if ($kleymo==1 OR $kleymo==2)
	{
		//$url = urlencode('http://'.IMG_DOMAIN.'/item/'.$img.'.gif');
		$url = urlencode($img);
		echo '<img src="http://'.DOMAIN.'/utils/mixitem.php?img='.$url.'" border="0"';
		if ($align!="") echo ' align="'.$align.'"';
		if ($title!="") echo ' title="'.$title.'"';
		if ($alt!="") echo ' alt="'.$alt.'"';
		echo ' width="50" height="50" >';
	}
	else
	{
		echo '<img src="http://'.IMG_DOMAIN.'/item/'.$img.'.gif" border="0"';
		if ($align!="") echo ' align="'.$align.'"';
		if ($title!="") echo ' title="'.$title.'"';
		if ($alt!="") echo ' alt="'.$alt.'"';
		echo ' width="50" height="50" >';
	}
}

function getCW($user)
{
	$cw = 0;
	//исключим отсюда преметы для движка квестов - они обрабатываются отдельно
	$cw+=mysql_result(myquery("SELECT SUM(game_items_factsheet.weight) FROM game_items,game_items_factsheet WHERE game_items.user_id=$user AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.type!=95"),0,0);
	$cw+=mysql_result(myquery(" SELECT sum( craft_resource.weight * craft_resource_user.col ) FROM craft_resource, craft_resource_user WHERE craft_resource_user.user_id =$user AND craft_resource.id = craft_resource_user.res_id "),0,0);
	//inq! предметы от движка квестов - вес для них хранится отдельно
	$cw+=mysql_result(myquery("SELECT SUM(game_items.item_uselife) FROM game_items,game_items_factsheet WHERE game_items.user_id=$user AND game_items_factsheet.type=95 AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id"),0,0);
	
	return $cw;
}

function wm_str($type)
{
	$tp_new_str = '';
	$tp_img = '';
	switch ($type)
	{
		case '1':
		{
			$tp_new_str='Свиток бесконечной энергии';
			$tp_img = 'svitki/sbesener';
			break;
		}
		case '2':
		{
			$tp_new_str='Свиток телепорта';
			$tp_img = 'svitki/steleport';
			break;
		}
		case '3':
		{
			$tp_new_str='Свиток полного востановления';
			$tp_img = 'svitki/sbolvost';
			break;
		}
		case '4':
		{
			$tp_new_str='Свиток малого востановления';
			$tp_img = 'svitki/sbolvost';
			break;
		}
		case '5':
		{
			$tp_new_str='Свиток разрешения';
			$tp_img = 'svitki/sbesener';
			break;
		}
	}
	$ar = array(ucfirst($tp_new_str),$tp_img);
	return $ar;
}

function type_str($type)
{
	$tp_new_str = '';
	switch ($type)
	{
		case 1:
		{
			$tp_new_str='Оружие';
			break;
		}

		case 2:
		{
			$tp_new_str='Кольцо';
			break;
		}

		case 3:
		{
			$tp_new_str='Артефакт';
			break;
		}

		case 4:
		{
			$tp_new_str='Щит';
			break;
		}

		case 5:
		{
			$tp_new_str='Доспех';
			break;
		}

		case 6:
		{
			$tp_new_str='Шлем';
			break;
		}

		case 7:
		{
			$tp_new_str='Магия';
			break;
		}

		case 8:
		{
			$tp_new_str='Пояс';
			break;
		}

		case 9:
		{
			$tp_new_str='Ожерелье';
			break;
		}

		case 10:
		{
			$tp_new_str='Перчатки';
			break;
		}

		case 11:
		{
			$tp_new_str='Обувь';
			break;
		}

		case 12:
		{
			$tp_new_str='Свиток';
			break;
		}

		case 13:
		{
			$tp_new_str='Эликсир';
			break;
		}

		case 14:
		{
			$tp_new_str='Поножи';
			break;
		}

		case 15:
		{
			$tp_new_str='Наручи';
			break;
		}

		case 16:
		{
			$tp_new_str='Украшение';
			break;
		}

		case 17:
		{
			$tp_new_str='Маг.книги';
			break;
		}

		case 18:
		{
			$tp_new_str='Лук';
			break;
		}

		case 19:
		{
			$tp_new_str='Метат.предмет';
			break;
		}

		case 20:
		{
			$tp_new_str='Схема изготовления предмета';
			break;
		}

		case 21:
		{
			$tp_new_str='Стрелы';
			break;
		}

		case 22:
		{
			$tp_new_str='Руна амулета';
			break;
		}

		case 23:
		{
			$tp_new_str='Комплект предметов';
			break;
		}
		case 24:
		{
			$tp_new_str='Инструмент';
			break;
		}

		case 98:
		{
			$tp_new_str='Часть тела монстра (квест Гильдии охотников за монстрами)';
			break;
		}
		
		case 97:
		{
			$tp_new_str='Прочее';
			break;
		}
		
		case 95:
		{
			$tp_new_str='Квестовые предметы';
			break;
		}
		
		default:
		{
			$tp_new_str='Неопознанно('.$type.')';
			break;
		}
	}
	return $tp_new_str;
}

function PrintInv($userid,$from_view)
{
	global $option;
	/*
	list($race) = mysql_fetch_array(myquery("(SELECT race FROM game_users WHERE user_id='$userid') UNION (SELECT race FROM game_users_archive WHERE user_id='$userid')"));
	switch($race)
	{
		case 'Гном':
		{
			$img['file']='inv/gnom.gif';
			$img['width']=330;
			$img['height']=466;
			//позиция оружия в правой руке
			$img[1]['posx']=89;
			$img[1]['posy']=95;
			//позиция колца №1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//позиция артефакта №1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//позиция щита или оружия в левой руке
			$img[4]['posx']=239;
			$img[4]['posy']=205;
			//позиция доспеха
			$img[5]['posx']=173;
			$img[5]['posy']=188;
			//позиция шлема
			$img[6]['posx']=173;
			$img[6]['posy']=59;
			//позиция магии
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//позиция пояса
			$img[8]['posx']=173;
			$img[8]['posy']=244;
			//позиция амулета
			$img[9]['posx']=173;
			$img[9]['posy']=130;
			//позиция перчаток
			$img[10]['posx']=89;
			$img[10]['posy']=148;
			//позиция ботинок
			$img[11]['posx']=173;
			$img[11]['posy']=328;
			//позиция кармана 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//позиция кармана 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//позиция кармана 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//позиция кармана 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//позиция кармана 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//позиция артефакта №2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//позиция кольца №2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//позиция кольца №3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case 'Орк':
		{
			$img['file']='inv/orc.gif';
			$img['width']=330;
			$img['height']=466;
			//позиция оружия в правой руке
			$img[1]['posx']=82;
			$img[1]['posy']=79;
			//позиция колца №1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//позиция артефакта №1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//позиция щита или оружия в левой руке
			$img[4]['posx']=257;
			$img[4]['posy']=191;
			//позиция доспеха
			$img[5]['posx']=189;
			$img[5]['posy']=128;
			//позиция шлема
			$img[6]['posx']=189;
			$img[6]['posy']=4;
			//позиция магии
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//позиция пояса
			$img[8]['posx']=189;
			$img[8]['posy']=184;
			//позиция амулета
			$img[9]['posx']=189;
			$img[9]['posy']=72;
			//позиция перчаток
			$img[10]['posx']=82;
			$img[10]['posy']=132;
			//позиция ботинок
			$img[11]['posx']=189;
			$img[11]['posy']=308;
			//позиция кармана 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//позиция кармана 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//позиция кармана 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//позиция кармана 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//позиция кармана 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//позиция артефакта №2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//позиция кольца №2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//позиция кольца №3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case 'Назгул':
		{
			$img['file']='inv/nazgul.gif';
			$img['width']=330;
			$img['height']=466;
			//позиция оружия в правой руке
			$img[1]['posx']=85;
			$img[1]['posy']=83;
			//позиция колца №1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//позиция артефакта №1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//позиция щита или оружия в левой руке
			$img[4]['posx']=214;
			$img[4]['posy']=149;
			//позиция доспеха
			$img[5]['posx']=155;
			$img[5]['posy']=198;
			//позиция шлема
			$img[6]['posx']=156;
			$img[6]['posy']=66;
			//позиция магии
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//позиция пояса
			$img[8]['posx']=156;
			$img[8]['posy']=263;
			//позиция амулета
			$img[9]['posx']=155;
			$img[9]['posy']=133;
			//позиция перчаток
			$img[10]['posx']=85;
			$img[10]['posy']=136;
			//позиция ботинок
			$img[11]['posx']=194;
			$img[11]['posy']=329;
			//позиция кармана 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//позиция кармана 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//позиция кармана 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//позиция кармана 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//позиция кармана 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//позиция артефакта №2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//позиция кольца №2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//позиция кольца №3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case 'Хоббит':
		{
			$img['file']='inv/hobbit.gif';
			$img['width']=330;
			$img['height']=466;
			//позиция оружия в правой руке
			$img[1]['posx']=79;
			$img[1]['posy']=102;
			//позиция колца №1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//позиция артефакта №1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//позиция щита или оружия в левой руке
			$img[4]['posx']=249;
			$img[4]['posy']=231;
			//позиция доспеха
			$img[5]['posx']=179;
			$img[5]['posy']=155;
			//позиция шлема
			$img[6]['posx']=179;
			$img[6]['posy']=30;
			//позиция магии
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//позиция пояса
			$img[8]['posx']=179;
			$img[8]['posy']=212;
			//позиция амулета
			$img[9]['posx']=179;
			$img[9]['posy']=99;
			//позиция перчаток
			$img[10]['posx']=79;
			$img[10]['posy']=155;
			//позиция ботинок
			$img[11]['posx']=179;
			$img[11]['posy']=314;
			//позиция кармана 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//позиция кармана 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//позиция кармана 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//позиция кармана 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//позиция кармана 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//позиция артефакта №2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//позиция кольца №2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//позиция кольца №3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case 'Человек':
		{
			$img['file']='inv/humman.gif';
			$img['width']=330;
			$img['height']=466;
			//позиция оружия в правой руке
			$img[1]['posx']=102;
			$img[1]['posy']=195;
			//позиция колца №1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//позиция артефакта №1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//позиция щита или оружия в левой руке
			$img[4]['posx']=247;
			$img[4]['posy']=195;
			//позиция доспеха
			$img[5]['posx']=180;
			$img[5]['posy']=136;
			//позиция шлема
			$img[6]['posx']=180;
			$img[6]['posy']=16;
			//позиция магии
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//позиция пояса
			$img[8]['posx']=180;
			$img[8]['posy']=192;
			//позиция амулета
			$img[9]['posx']=180;
			$img[9]['posy']=81;
			//позиция перчаток
			$img[10]['posx']=102;
			$img[10]['posy']=142;
			//позиция ботинок
			$img[11]['posx']=180;
			$img[11]['posy']=338;
			//позиция кармана 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//позиция кармана 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//позиция кармана 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//позиция кармана 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//позиция кармана 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//позиция артефакта №2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//позиция кольца №2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//позиция кольца №3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case 'Гоблин':
		{
			$img['file']='inv/goblin.gif';
			$img['width']=330;
			$img['height']=466;
			//позиция оружия в правой руке
			$img[1]['posx']=78;
			$img[1]['posy']=95;
			//позиция колца №1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//позиция артефакта №1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//позиция щита или оружия в левой руке
			$img[4]['posx']=261;
			$img[4]['posy']=212;
			//позиция доспеха
			$img[5]['posx']=186;
			$img[5]['posy']=148;
			//позиция шлема
			$img[6]['posx']=186;
			$img[6]['posy']=33;
			//позиция магии
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//позиция пояса
			$img[8]['posx']=186;
			$img[8]['posy']=204;
			//позиция амулета
			$img[9]['posx']=186;
			$img[9]['posy']=92;
			//позиция перчаток
			$img[10]['posx']=78;
			$img[10]['posy']=148;
			//позиция ботинок
			$img[11]['posx']=186;
			$img[11]['posy']=317;
			//позиция кармана 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//позиция кармана 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//позиция кармана 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//позиция кармана 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//позиция кармана 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//позиция артефакта №2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//позиция кольца №2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//позиция кольца №3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case 'Тролль':
		{
			$img['file']='inv/troll.gif';
			$img['width']=330;
			$img['height']=466;
			//позиция оружия в правой руке
			$img[1]['posx']=88;
			$img[1]['posy']=230;
			//позиция колца №1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//позиция артефакта №1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//позиция щита или оружия в левой руке
			$img[4]['posx']=249;
			$img[4]['posy']=70;
			//позиция доспеха
			$img[5]['posx']=150;
			$img[5]['posy']=148;
			//позиция шлема
			$img[6]['posx']=150;
			$img[6]['posy']=29;
			//позиция магии
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//позиция пояса
			$img[8]['posx']=150;
			$img[8]['posy']=208;
			//позиция амулета
			$img[9]['posx']=150;
			$img[9]['posy']=91;
			//позиция перчаток
			$img[10]['posx']=88;
			$img[10]['posy']=177;
			//позиция ботинок
			$img[11]['posx']=150;
			$img[11]['posy']=334;
			//позиция кармана 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//позиция кармана 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//позиция кармана 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//позиция кармана 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//позиция кармана 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//позиция артефакта №2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//позиция кольца №2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//позиция кольца №3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		default:
		{
			$img['file']='inv/elf.gif';
			$img['width']=330;
			$img['height']=466;
			//позиция оружия в правой руке
			$img[1]['posx']=90;
			$img[1]['posy']=184;
			//позиция колца №1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//позиция артефакта №1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//позиция щита или оружия в левой руке
			$img[4]['posx']=224;
			$img[4]['posy']=210;
			//позиция доспеха
			$img[5]['posx']=167;
			$img[5]['posy']=147;
			//позиция шлема
			$img[6]['posx']=195;
			$img[6]['posy']=25;
			//позиция магии
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//позиция пояса
			$img[8]['posx']=167;
			$img[8]['posy']=204;
			//позиция амулета
			$img[9]['posx']=180;
			$img[9]['posy']=93;
			//позиция перчаток
			$img[10]['posx']=90;
			$img[10]['posy']=131;
			//позиция ботинок
			$img[11]['posx']=200;
			$img[11]['posy']=343;
			//позиция кармана 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//позиция кармана 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//позиция кармана 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//позиция кармана 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//позиция кармана 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//позиция артефакта №2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//позиция кольца №2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//позиция кольца №3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
	};
	*/
	$img['file']='inv/inv.gif';
	$img['width']=226;
	$img['height']=382;
	//позиция оружия в правой руке
	$img[1]['posx']=62;
	$img[1]['posy']=166;
	//позиция кольца №1
	$img[2]['posx']=10;
	$img[2]['posy']=10;
	//позиция артефакта
	$img[3]['posx']=10;
	$img[3]['posy']=166;
	//позиция щита или оружия в левой руке
	$img[4]['posx']=166;
	$img[4]['posy']=166;
	//позиция доспеха
	$img[5]['posx']=114;
	$img[5]['posy']=114;
	//позиция шлема
	$img[6]['posx']=114;
	$img[6]['posy']=10;
	//позиция магии
	$img[7]['posx']=10;
	$img[7]['posy']=218;
	//позиция пояса
	$img[8]['posx']=114;
	$img[8]['posy']=166;
	//позиция ожерелья
	$img[9]['posx']=114;
	$img[9]['posy']=62;
	//позиция перчаток
	$img[10]['posx']=166;
	$img[10]['posy']=114;
	//позиция ботинок
	$img[11]['posx']=114;
	$img[11]['posy']=270;
	//позиция кармана 1
	$img[12]['posx']=166;
	$img[12]['posy']=218;
	//позиция кармана 2
	$img[13]['posx']=166;
	$img[13]['posy']=270;
	//позиция кармана3
	$img[14]['posx']=166;
	$img[14]['posy']=322;
	//позиция украшения 1
	$img[15]['posx']=10;
	$img[15]['posy']=270;
	//позиция украшения 2
	$img[16]['posx']=10;
	$img[16]['posy']=322;
	//позиция штанов
	$img[17]['posx']=114;
	$img[17]['posy']=218;
	//позиция наручей
	$img[18]['posx']=62;
	$img[18]['posy']=114;
	//позиция кольца №2
	$img[19]['posx']=10;
	$img[19]['posy']=62;
	//позиция кольца №3
	$img[20]['posx']=10;
	$img[20]['posy']=114;
	//позиция инструмента
	$img[21]['posx']=62;
	$img[21]['posy']=218;

    if ($from_view!=1)
    {
	?>
	<table><tr><td valign="top">
	<?
    };
	echo '
	<div style="position:relative;">
	<img src="http://'.IMG_DOMAIN.'/'.$img['file'].'" border=0 width='.$img['width'].' height='.$img['height'].' alt="" title="">
	';
	$it_sel = myquery("SELECT * FROM game_items WHERE user_id='$userid' AND used>0 AND used<=21");
	while($it = mysql_fetch_array($it_sel))
	{
        $used = $it['used'];
        echo '
        <span style="position:absolute; left:'.$img[$used]['posx'].'; top:'.$img[$used]['posy'].'; ">';
		$Item = new Item($it['id']);
		if($from_view!=1)
		{
			$str_begin = '<a href="http://'.DOMAIN.'/item.php?inv_option=unequip&id='.$it['id'].''.((isset($_GET['house'])) ? '&house&option='.$option.'' : '').'"';
			$Item->hint($it['id'],1,$str_begin);
			$alt="Снять предмет";
		}
		else
		{
			$alt=''.$Item->getFact('name').'';
		}
		ImageItem($Item->img,0,$Item->item['kleymo'],"",$alt,$alt);
		if($from_view!=1)
		{
			echo '</a>';
		}
        echo '</span>';
	}

	echo'</div>';
}


?>