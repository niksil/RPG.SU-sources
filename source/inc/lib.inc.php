<?php

require_once('lib_attack.inc.php');
require_once('lib_arcomage.inc.php');
require_once('lib_craft.inc.php');
require_once('lib_inv.inc.php');
require_once('lib_admin.inc.php');
require_once('lib_exp.inc.php');


require_once('combat/combat.inc.php');

function get_user($column,$key,$par=0)
{
	if ($par==0)
	{
		$selname = myquery("SELECT `$column` FROM game_users WHERE user_id='$key'");
		if (!mysql_num_rows($selname)) $selname = myquery("SELECT `$column` FROM game_users_archive WHERE user_id='$key'");
	}
	elseif ($par==1)
	{
		$selname = myquery("SELECT `$column` FROM game_users WHERE name='$key'");
		if (!mysql_num_rows($selname)) $selname = myquery("SELECT `$column` FROM game_users_archive WHERE name='$key'");
	}
	if ($selname!=false AND mysql_num_rows($selname)>0)
	{
		return mysql_result($selname,0,0);
	} 
	else
	{
		return "~~~";
	}
}

function mysqlresult($query,$p1=0,$p2=0)
{
	if ($query!=false AND mysql_num_rows($query)>0)
	{
		return mysql_result($query,$p1,$p2);
	}
	else
	{
		return "";
	}
}

function print_sklon($user,$from_chat=0,$ret=0)
{
	if ($from_chat==0)
	{
		if ($ret==0)
		{
			if ($user['clan_id']==1) echo'<img src="http://'.IMG_DOMAIN.'/sklon/admin.gif" border="0" alt="Выс" title="Высшая склонность">&nbsp;';
			elseif ($user['sklon']==1) echo'<img src="http://'.IMG_DOMAIN.'/sklon/neutral.gif" border="0" alt="Нейт" title="Нейтральная склонность">&nbsp;';
			elseif ($user['sklon']==2) echo'<img src="http://'.IMG_DOMAIN.'/sklon/light.gif" border="0" alt="Свет" title="Светлая склонность">&nbsp;';
			elseif ($user['sklon']==3) echo'<img src="http://'.IMG_DOMAIN.'/sklon/dark.gif" border="0" alt="Тьма" title="Темная склонность">&nbsp;';
		}
		else
		{
			if ($user['clan_id']==1) return'<img src="http://'.IMG_DOMAIN.'/sklon/admin.gif" border="0" alt="Выс" title="Высшая склонность">&nbsp;';
			elseif ($user['sklon']==1) return'<img src="http://'.IMG_DOMAIN.'/sklon/neutral.gif" border="0" alt="Нейт" title="Нейтральная склонность">&nbsp;';
			elseif ($user['sklon']==2) return'<img src="http://'.IMG_DOMAIN.'/sklon/light.gif" border="0" alt="Свет" title="Светлая склонность">&nbsp;';
			elseif ($user['sklon']==3) return'<img src="http://'.IMG_DOMAIN.'/sklon/dark.gif" border="0" alt="Тьма" title="Темная склонность">&nbsp;';
		}
	}
	else
	{
		if ($user['clan_id']==1) return '<img width="12" height="12" src="http://'.IMG_DOMAIN.'/sklon/admin.gif" border="0" alt="В" title="Высшая склонность">&nbsp;';
		elseif ($user['sklon']==1) return '<img width="12" height="12" src="http://'.IMG_DOMAIN.'/sklon/neutral.gif" border="0" alt="Н" title="Нейтральная склонность">&nbsp;';
		elseif ($user['sklon']==2) return '<img width="12" height="12" src="http://'.IMG_DOMAIN.'/sklon/light.gif" border="0" alt="С" title="Светлая склонность">&nbsp;';
		elseif ($user['sklon']==3) return '<img width="12" height="12" src="http://'.IMG_DOMAIN.'/sklon/dark.gif" border="0" alt="Т" title="Темная склонность">&nbsp;';
	}
}

function getDelayReasonCraft($craft_index)
{
	switch($craft_index)
	{
		case 1: return 9; break;
		case 2: return 4; break;
		case 4: return 28; break;
		case 5: return 29; break;
		case 6: return 30; break;
		case 7: return 31; break;
	}
	return 0;
}

function get_delay_reason($id)
{
	switch ($id)
	{
		case 1:{return 'Игра';}break;
		case 2:{return 'В городе';}break;
		case 3:{return 'В магазине';}break;
		case 4:{return 'Варит зелья';}break;
		case 5:{return 'На форуме';}break;
		case 6:{return 'В дневнике';}break;
		case 7:{return 'Поднял предмет';}break;
		case 8:{return 'Передвижение по гексам';}break;
		case 9:{return 'Собирает ингредиенты';}break;
		case 10:{return 'Играет в Две Башни';}break;
		case 11:{return 'В бою с тенью';}break;
		case 12:{return 'Ударил бота';}break;
		case 13:{return 'Проходит квест';}break;
		case 14:{return 'В обычном бою';}break;
		case 15:{return 'В дуэли';}break;
		case 16:{return 'В общем бою';}break;
		case 17:{return 'В многоклановом бою';}break;
		case 18:{return 'В бою все против всех';}break;
		case 19:{return 'Снял предмет';}break;
		case 20:{return 'Надел предмет';}break;
		case 21:{return 'Боевой режим';}break;
		case 22:{return 'Характеристики';}break;
		case 23:{return 'Инвентарь';}break;
		case 24:{return 'Смотрит онлайн';}break;
		case 25:{return 'Настройки';}break;
		case 26:{return 'Личная почта';}break;
		case 27:{return 'Смотрит статистику';}break;

		case 28:{return 'Рубит лес';}break;
		case 29:{return 'Добывает камни';}break;
		case 30:{return 'Добывает руду';}break;
		case 31:{return 'Работает на лесопилке';}break;
		case 32:{return 'В своем землевладении';}break;
		case 33:{return 'Работает в разделочном цеху';}break;
		case 34:{return 'Работает в литейне';}break;

		case 43:{return 'В магазине';}break;

		case 44:{return 'В турнирном хаотичном бою';}break;
		case 45:{return 'В бою с тенью';}break;
		case 46:{return 'В турнирном групповом бою';}break;
		case 47:{return 'В турнирной дуэли';}break;
		case 48:{return 'В бою рас';}break;
		case 49:{return 'В бою склонностей';}break;


		//inq! движок квестов - до 50 занято под новую систему
		case 51:{return 'Общается с квестовым персонажем';}break;
		case 52:{return 'Решает головоломку';}break;
		default:
		return $id;
		break;
	}
}

function get_delay_reason_id($user_id)
{
	$sel_rid = myquery("SELECT delay_reason FROM game_users_active_delay WHERE user_id = '".$user_id."' ");
	if(mysql_num_rows($sel_rid)==0)
	{
		return 0;
	}
	else
	{
		$arr_rid = mysql_fetch_array($sel_rid);
		return $arr_rid['delay_reason'];
	}
}

// example: set_delay_reason_id($user_id,1);
function set_delay_reason_id($user_id,$reason_id)
{
	$sel_race = myquery("UPDATE game_users_active_delay SET delay_reason='".$reason_id."' WHERE user_id='".$user_id."' ");
	return 1;
}

// example: get_delay_id($user_id);
function get_delay_id($user_id)
{
	$sel_race = myquery("SELECT delay FROM game_users_active_delay WHERE user_id='".$user_id."' ");
	if ($sel_race!=false AND mysql_num_rows($sel_race)>0)
	{
		return mysql_result($sel_race,0,0);
	}
	return 0;
}

// example: set_delay_id($user_id,1);
function set_delay_id($user_id,$delay_id)
{
	$sel_race = myquery("UPDATE game_users_active_delay SET delay='".$delay_id."' WHERE user_id='".$user_id."' ");
	return 1;
}

// example: set_delay_plus_id($user_id,1);
function set_delay_plus_id($user_id,$delay_id)
{
	$sel_race = myquery("UPDATE game_users_active_delay SET delay=delay+'".$delay_id."' WHERE user_id='".$user_id."' ");
	return 1;
}

// example: set_delay_info($user_id,1000,1);
function set_delay_info($user_id,$delay_id,$reason_id)
{
	$sel_race = myquery("UPDATE game_users_active_delay SET delay='".$delay_id."',delay_reason='".$reason_id."' WHERE user_id='".$user_id."' ");
	return 1;
}

function setLocation($loc)
{
	if (!headers_sent())
	{
		header("Location:".$loc);
	}
	else
	{
		echo '<script>location.replace(\''.$loc.'\')</script>';
	}
}

define('func_combat',       1);
define('func_craft',        2);
define('func_market',       3);
define('func_arcomage',     4);
define('func_game',         5);
define('func_forum',        6);
define('func_diary',        7);
define('func_chat',         8);
define('func_city',         9);
define('func_quest',       10);
define('func_view',        11);
define('func_chat_combat', 12);  
define('func_quest_engine',13);  

function setFunc($user_id,$func_id)
{
	//user_id:
	//id игрока
	//func_id:
	// Переменная имеет вид a.b. Где a - тип функции. b - подтип функции.
	//
	//0.x - ''
	//
	//1.x: - Бой
	//1.0 - wait
	//1.1 - .win
	//1.2 - lose
	//1.3 - draw
	//1.4 - otkaz
	//1.5 - net
	//1.6 - boy
	//1.7 - boy_npc
	//1.8 - wait_npc
	//1.9 - podt
	//1.10 - ojid
	//1.11 - duel
	//
	//2.x: - Крафт
	//2.0 - /inc/craft/craft.inc.php
	//2.1 - Добыча ресурсов
	//2.2 - Алхимия ( элики )
	//2.3 - Строительство ( пока не подключено )
	//
	//3.x: - Не Используется
	//3.0 -
	//
	//4.x - Две Башни
	//4.0 - arcomage
	//1 - arcomage_boy
	//2 - arcomage_wait
	//3 - arcomage_lose
	//4 - arcomage_win
	//5 - arcomage_draw
	//6 - arcomage_ojid
	//7 - arcomage_podt
	//8 - arcomage_otkaz
	//9 - arcomage_net
	//
	//5.x - Act + move
	//
	//6.x - Forum
	//
	//7.x - Diary
	//
	//8.x - Chat
	//
	//9.x - Город
	//
	//10.x - Квест
	//
	//11.x - /view/
	//
	//12.x - Боевой чат
	//
	////inq! 13.x - движок квестов (до интеграции, по крайней мере, пусть будет так)
	// С форума и Дневника нельзя в бой
	// Если джамп

	// Проблема тут в том, что некоторые модули не имеют своего ID и портят func_id, и далее все очень плохо
	if(!isset($func_id) OR $func_id=='' OR $func_id=='0' OR $func_id=='MODULE_ID')
	{
		return 1;
	}

	myquery("UPDATE game_users_func SET func_id='".$func_id."' WHERE user_id='".$user_id."' ");
	return 1;

}

function getFunc($user_id)
{
	$sel_rid = myquery("SELECT func_id FROM game_users_func WHERE user_id = '".$user_id."' ");
	if(mysql_num_rows($sel_rid)==0)
	{
		mysql_query("INSERT INTO game_users_func (`user_id`,`func_id`) VALUES ('".$user_id."','5')");
		return 5;
	}
	else
	{
		$arr_rid = mysql_fetch_array($sel_rid);
		return $arr_rid['func_id'];
	}
}

function getRedirectFunc($user_id)
{
	$arr_func=array("act.php","combat.php","craft.php","non_exist.php","arcomage.php","act.php","/forum/index.php","/diary/","/chat/","/lib/town.php","/quest/quest1.php","non_exist.php","non_exist.php","/quest/quests_engine_chek.php","/quest/quest_artur.php","/quest/quest_destroyer.php","/quest/quest_demon.php","/quest/quest_zombi.php","/quest/quest_dungeon.php");
	$func=getFunc($user_id);
	return $arr_func[$func];
}

function checkFunc($user_id,$func_id,$only_check=0)
{
	//Возвращает 0 если надо сделать редирект
	
	// Заглушка для чата
	if($func_id==8)
	{
		return 1;
	}
	// Заглушка для view
	if($func_id==11)
	{
		return 1;
	}
	// Если код без MODULE_ID
	if($func_id=="MODULE_ID")
	{
		return 1;
	}
	$old_func_id=getFunc($user_id);
	// В пределах одного модуля
	if($old_func_id==$func_id)
	{
		return 1;
	}
	// в Бой можно переходить только из Игры
	if($func_id==1 AND $old_func_id!=5)
	{
		return 0;
	}
	// Разрешаем из Боя -> Боевой чат, но не обновляем func
	if($func_id==12 AND $old_func_id==1)
	{
		return 1;
	}
	
	// Разрешаем из Города -> Крафт, но не обновляем func
	if(($func_id==2) AND ($old_func_id==9))
	{
		return 1;
	}

	// Разрешаем: Магазин <-> Игра
        // Магазин -> Форум и Магазин -> Дневник
	if(($func_id==5 AND $old_func_id==3) OR ($func_id==3 AND $old_func_id==5) OR 
	   ($func_id==6 AND $old_func_id==3) OR ($func_id==7 AND $old_func_id==3))
	{
		return 1;
	}

	// Разрешаем из Крафта и Города и Аркомага и Боя(!) -> Форум и Дневник, но не обновляем func
	// Так как будет баг - Крафт -> Форум -> Игра - и как следствие можно будет бегать
	if(($func_id==6 OR $func_id==7) AND ($old_func_id==2 OR $old_func_id==9 OR $old_func_id==10 OR $old_func_id==4))
	{
		return 1;
	}


	// Форум и Дневник --> Игра
	if(($old_func_id==6 OR $old_func_id==7 OR $old_func_id==9) AND $func_id==5)
	{
		if($only_check==0)
		{
			setFunc($user_id,$func_id);
		}
		return 1;
	}
	// Форум и Дневник и Город --> Дневник и Форум и Город
	if(($old_func_id==6 OR $old_func_id==7) AND ($func_id==6 OR $func_id==7 OR $func_id==9))
	{
		if($only_check==0)
		{
			setFunc($user_id,$func_id);
		}
		return 1;
	}
	// Игра --> ...
	// В состояние Чат и Боевой Чат мы никогда не должны попасть
	if(($old_func_id==5) AND ($func_id!=8 AND $func_id!=12))
	{
		if($only_check==0)
		{
			setFunc($user_id,$func_id);
		}
		return 1;
	}
	// Остальным ничего нельзя
	return 0;
}

function ForceFunc($user_id,$func_id)
{
	setFunc($user_id,$func_id);
	return 1;
}

function get_GP_reason($id)
{
	switch ($id)
	{
		case 1:{return 'Заработок на крафте';}break;
		case 2:{return 'Оплата владельца шахты работнику';}break;
		case 3:{return 'Бонус при регистрации нового игрока по ссылке - "Приглашение"';}break;
		case 4:{return 'Стартовое золото при регистрации';}break;
		case 5:{return 'Найден сундук с золотом';}break;
		case 6:{return 'Найдена ловушка сундука с золотом';}break;
		case 7:{return 'Оплата за идентификацию предмета у торговца';}break;
		case 8:{return 'Продажа предмета торговцу';}break;
		case 9:{return 'Покупка предмета у торговца';}break;
		case 10:{return 'Зарядка артефакта у торговца';}break;
		case 11:{return 'Ремонт предмета у торговца';}break;
		case 12:{return 'Покупка предмета на рынке';}break;
		case 13:{return 'Продажа предмета на рынке';}break;
		case 14:{return 'Налог за выставление предмета на рынок';}break;
		case 15:{return 'оплата за строительство здания';}break;
		case 16:{return 'покупка здания';}break;
		case 17:{return 'продажа здания';}break;
		case 18:{return 'оплата за прохождение телепорта';}break;
		case 19:{return 'возврат денег при удалении вещи из инвентаря администрацией';}break;
		case 20:{return 'изменение золота администрацией';}break;
		case 21:{return 'повышение уровня';}break;
		case 22:{return 'победа над ботом в бою';}break;
		case 23:{return 'проигрыш денег в Аркомаге';}break;
		case 24:{return 'выигрыш денег в Аркомаге';}break;
		case 25:{return 'победа над игроком в бою';}break;
		case 26:{return 'уменьшение денег при смерти в бою (турниры)';}break;
		case 27:{return 'деньги за победу в бою (турниры)';}break;
		case 28:{return 'деньги за прохождение лабиринтов';}break;
		case 29:{return 'изменение денег при обнулении';}break;
		case 30:{return 'вклад денег на свой лицевой счет в банке';}break;
		case 31:{return 'снятие денег с лицевого счета в банке';}break;
		case 32:{return 'такса за открытие лицевого счета';}break;
		case 33:{return 'оплата услуг храма бракосочетания';}break;
		case 34:{return 'оплата при покупке земли в городе и строительстве зданий в нем';}break;
		case 35:{return 'покупка/продажа соток земли через вторичный рынок жилья';}break;
		case 36:{return 'покупка/продажа дома через вторичный рынок жилья';}break;
		case 37:{return 'комиссия при использовании вторичного рынка жилья';}break;
		case 38:{return 'оплата налога на имущество (коммунальных платежей)';}break;
		case 39:{return 'плата за прием игрока в клан';}break;
		case 40:{return 'оплата налога на клан';}break;
		case 41:{return 'оплата регистрации нового клана';}break;
		case 42:{return 'покупка лошади в конюшне';}break;
		case 43:{return 'продажа лошади в конюшне';}break;
		case 44:{return 'оплата за лечение травмы у лекаря';}break;
		case 45:{return 'возврат денег за отклонение открытки при модерации';}break;
		case 46:{return 'оплата отправки открытки';}break;
		case 47:{return 'оплата услуг информационного отдела Гильдии Охотников за монстрами';}break;
		case 48:{return 'покупка ресурса на рынке';}break;
		case 49:{return 'продажа ресурса на рынке';}break;
		case 50:{return 'оплата аренды места на рынке';}break;
		case 51:{return 'покупка билета в морском порту';}break;
		case 52:{return 'оплата за использование Алтаря Великой Силы';}break;
		case 53:{return 'возврат денег за коня при изменении навыка всадника на Алтаре Великой Силы';}break;
		case 54:{return 'плата за пополнение запасов таверны';}break;
		case 55:{return 'покупка еды в таверне';}break;
		case 56:{return 'доходы от таверны';}break;
		case 57:{return 'оплата услуг за пополнение своей таверны через другого тавернщика';}break;
		case 58:{return 'подъемные при окончании Гильдии Новичков';}break;
		case 59:{return 'изменение денег за квест "Спасение прекрасной незнакомки"';}break;
		case 60:{return 'увеличение денег в обучающем квесте Гильдии Новичков';}break;
		case 61:{return 'автоматическая продажа лошади при выставлении на продажу дома с построенной конюшней';}break;
		case 62:{return 'покупка еды для питомца';}break;
		case 63:{return 'плата за пользование государственной лесопилкой';}break;
		case 64:{return 'плата за изучение базовой профессии';}break;
		case 65:{return 'плата за пользование государственной кузней';}break;
		case 66:{return 'плата за пользование государственной алхимической лабораторией';}break;
		//движок квестов - до 80 занято под новую систему
		case 81:{return 'награда за выполнение задания';}break;
		case 82:{return 'плата за клеймение предмета';}break;
		case 83:{return 'плата за снятие клейма с предмета';}break;	
		case 101:{return 'изменение денег за квест "Меч и судьба"';}break;
		case 102:{return 'изменение денег за квест "Разрушитель"';}break;
		case 103:{return 'изменение денег за квест "Тень-демон"';}break;
		case 104:{return 'изменение денег за квест "Зомби"';}break;
		case 104:{return 'изменение денег за квест "Подземелья Черного Замка"';}break;
		case 105:{return 'продажа земли в город';}break;
		case 106:{return 'оплата лицензии в Гильдии охотников за монстрами';}break;
		case 107:{return 'оплата игры в Шатре Азарта';}break;
		default:
		return '';
		break;
	}
}

function setGP($user,$gp,$reason)
{
	//reason:
	//1 - заработок на крафте
	//2 - оплата владельца шахты работнику
	//3 - бонус старому игроку при регистрации нового игрока по ссылке - "Приглашение"
	//4 - стартовое золото при регистрации
	//5 - найден сундук с золотом
	//6 - найдена ловушка сундука с золотом - золото уменьшается
	//7 - оплата за идентификацию предмета у торговца
	//8 - продажа предмета торговцу
	//9 - покупка предмета у торговца
	//10- зарядка артефакта у торговца
	//11- ремонт предмета у торговца
	//12- покупка предмета на рынке
	//13- продажа предмета на рынке
	//14- налог за выставление предмета на рынок
	//15- оплата за строительство здания
	//16- покупка здания
	//17- продажа здания
	//18- оплата за прохождение телепорта
	//19- возврат денег при удалении вещи из инвентаря игрока через админовку
	//20- изменение золота через админский редактор персов
	//21- золото за повышение уровня
	//22 - деньги за убийство бота
	//23 - проигрыш денег в Аркомаге
	//24 - выигрыш денег в Аркомаге
	//25 - деньги за убийство игрока
	//26 - уменьшение денег при смерти игрока в бою (турниры)
	//27 - деньги за победу в бою (турниры)
	//28 - деньги за прохождение лабиринтов
	//29 - изменение денег при обнулении игрока
	//30 - вклад денег на свой лицевой счет в банке из кошелька игрока
	//31 - снятие денег с лицевого счета в банке в кошелек игрока
	//32 - такса за открытие лицевого счета
	//33 - оплата услуг храма бракосочетания
	//34 - оплата при строительстве землевладения и зданий в нем и за покупку лицензии
	//35 - покупка/продажа соток земли через вторичный рынок жилья
	//36 - покупка/продажа дома через вторичный рынок жилья
	//37 - комиссия при использовании вторичного рынка жилья
	//38 - оплата налога на имущество (коммунальных платежей)
	//39 - плата за прием игрока в клан
	//40 - оплата налога на клан
	//41 - оплата регистрации нового клана
	//42 - покупка лошади в конюшне
	//43 - продажа лошади в конюшне
	//44 - оплата за лечение травмы у лекаря
	//45 - возврат денег за отклонение открытки при модерации
	//46 - оплата отправки открытки
	//47 - оплата услуг информационного отдела Гильдии Охотников за монстрами
	//48 - покупка ресурса на рынке
	//49 - продажа ресурса на рынке
	//50 - оплата аренды места на рынке
	//51 - покупка билета в морском порту
	//52 - оплата за использование Алтаря Великой Силы
	//53 - возврат денег за коня при изменении навыка всадника на Алтаре Великой Силы
	//54 - плата за пополнение запасов таверны
	//55 - покупка еды в таверне
	//56 - доходы от таверны
	//57 - оплата услуг за пополнение своей таверны через другого тавернщика
	//58 - подъемные при окончании Гильдии Новичков
	//59 - изменение денег за квест "Спасение прекрасной незнакомки"
	//60 - увеличение денег в обучающем квесте Гильдии Новичков
	//61 - автоматическая продажа лошади при выставлении на продажу дома с построенной конюшней
	//62 - покупка еды для питомца
	//63 - плата за пользование государственной лесопилкой
	//64 - плата за пользование государственной кузней
	//81 - награда за выполнение движкового квеста
	//82 - плата за клеймение предметов
	if ($gp!=0) myquery("INSERT DELAYED INTO game_users_stat_gp (user_id,gp,reason,timestamp) VALUES ($user,$gp,$reason,".time().")");
}

function get_EXP_reason($id)
{
	switch ($id)
	{
		case 1:{return 'победа над ботом в бою';}break;
		case 2:{return 'победа над игроком в бою';}break;
		case 3:{return 'прохождение лабиринта';}break;
		case 4:{return 'изменение опыта администрацией';}break;
		case 5:{return 'выполнение задания в Гильдии Охотников за монстрами';}break;
		case 6:{return 'прохождение квеста "Спасение прекрасной незнакомки"';}break;
		case 7:{return '';}break;
		case 8:{return 'прохождение квеста "Меч и судьба"';}break;
		case 9:{return 'прохождение квеста "Разрушитель"';}break;
		case 10:{return 'прохождение квеста "Тень-демон"';}break;
		case 11:{return 'прохождение квеста "Зомби"';}break;
		case 12:{return 'прохождение квеста "Подземелья Черного Замка"';}break;
		case 13:{return 'прохождение квеста "Помощь Лесничему"';}break;

		default:
		return '';
		break;
	}
}

function setEXP($user,$exp,$reason)
{
	//уменьшение опыта нас интересовать не будет. Только получение
	//***********************************************************************************************************************************
	//reason:
	//1 - опыт за убийство бота
	//2 - опыт за убийство игрока
	//3 - опыт за прохождение лабиринта
	//4 - изменение опыта через админовку
	//5 - за выполнение задания в Гильдии Охотников за монстрами
	//6 - за прохождение квеста "Спасение прекрасной незнакомки"
	//7 -
	if ($exp!=0) myquery("INSERT DELAYED INTO game_users_stat_exp (user_id,exp,reason,timestamp) VALUES ($user,$exp,$reason,".time().")");
}

function echo_sex($male,$female,$par="")
{
	global $pol;
	if ($par=="" OR $par==NULL)
	{
		$l_pol = $pol;
	}
	else
	{
		$l_pol = $par;
	}
	if ($l_pol=='female')
	{
		return $female;
	}
	else
	{
		return $male;
	}
}

function pluralForm($n, $form1, $form2, $form5)
{
	$n = abs($n) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20) return $form5;
	if ($n1 > 1 && $n1 < 5) return $form2;
	if ($n1 == 1) return $form1;
	return $form5;
}

function show_page($page,$allpage,$href)
{
	static $sp_num_on_page = 0;
	if ($allpage>1)
	{
		if($page>1) {
			echo '<a href="'.$href.'&amp;page='.($page-1).'">&lt;Пред.&gt;</a>&nbsp;&nbsp;';
		}
		$mark1 = 0;
		$mark2 = 0;
		for ($i=1;$i<=$allpage;$i++)
		{
			//echo '<span style="display:none;"><a href="'.$href.'&amp;page='.$i.'">'.$i.'</a></span>';
			if (($i>$page+2 AND $i<$allpage-2) OR ($i>2 AND $i<$page-2))
			{
				if ($i<$page AND $mark1==0)
				{
					echo '......, ';
					$mark1 = 1;
				}
				if ($i>$page AND $mark2==0)
				{
					echo '......, ';
					$mark2 = 1;
				}
				continue;
			}
			if ($i==$page)
			   if ($i==$allpage) echo '<b>'.$i.'</b>.' ;
			   else echo '<b>'.$i.'</b>, ';
			elseif ($i<>$allpage) echo '<a href="'.$href.'&amp;page='.$i.'">'.$i.'</a>, ';
			else echo '<a href="'.$href.'&amp;page='.$i.'">'.$i.'</a>.';
		}
		if($page<$allpage) {
			echo '&nbsp;&nbsp;<a href="'.$href.'&amp;page='.($page+1).'">&lt;След.&gt;</a>';
		}
		?>
		<script language="JavaScript" type="text/javascript">
		function loc(href,n)
		{
			p = document.getElementById('newpage' + n).value;
			location.href=href+'&page='+p;
		}
		</script>
		<?
		echo '&nbsp;&nbsp;<select id="newpage'.$sp_num_on_page.'" onChange="loc(\''.$href.'\','.$sp_num_on_page.')">';
		$d=50;
		if ($allpage<=20) $d=5;
		if ($allpage<=40) $d=10;
		if ($allpage<=60) $d=15;
		if ($allpage<=100) $d=20;
		if ($allpage<=120) $d=30;
		if ($allpage<=160) $d=40;
		for ($i=1;$i<=$allpage;$i++)
		{
			if ($i>20)
			{
				if ($i%$d!=0) continue;
			}
			echo '<option value='.$i.'';
			if ($page==$i) echo ' selected';
			echo '>'.$i.'</option>';
		}
		echo '</select>';
		$sp_num_on_page++;
	}
}

function ip2number($ip)
{
	$ip_array = explode(".",$ip);
	if (gettype($ip_array)=="array")
	{
		if (sizeof($ip_array)==4)
		{
			return $ip_array[3]+256*$ip_array[2]+256*256*$ip_array[1]+256*256*256*$ip_array[0];
		}
		else
		{
			return 0;
		}
	}
	else
	{
		return 0;
	}
}

function number2ip($number)
{
	$temp = $number;
	$ip='';
	$ip0 = floor($temp/256/256/256);
	$temp-=($ip0*256*256*256);
	$ip1 = floor($temp/256/256);
	$temp-=($ip1*256*256);
	$ip2 = floor($temp/256);
	$temp-=($ip2*256);
	$ip3=$temp;
	$ip.=$ip0.'.'.$ip1.'.'.$ip2.'.'.$ip3;
	return $ip;
}

function save_stat($par_user_id="",$par_npc_id="",$par_town="",$par_stat_id="",$par_shop_id="",$par_item="",$par_enemy_id="",$par_gp="",$par_clan_id="",$par_exp="",$par_level_user="",$par_level_enemy="")
{

	if (DOMAIN == DOMAIN) return;
	if (DOMAIN == TEST_SERVER)
	{
		$db_stat = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
	}
	mysql_select_db(PHPRPG_DB_NAME,$db_stat) or die(mysql_error());
	/*
	$da = getdate();
	$table_name = "stat_".$da['year'].'_'.$da['mon'];
	$find = 0;
	$db_list = mysql_list_tables(PHPRPG_DB_NAME) or die(mysql_error());
	$i = 0;
	$cnt = mysql_num_rows($db_list) or die(mysql_error());
	while ($i < $cnt)
	{
		if (mysql_tablename($db_list, $i) == $table_name)
		{
			$find = 1;
		}
		$i++;
	}
	if ($find==0)
	{
		$str_query  = "   CREATE TABLE $table_name (
						  user_id mediumint(15) NOT NULL default '0',
						  npc_id mediumint(8) NOT NULL default '0',
						  town int(10) NOT NULL default '0',
						  stat_id int(3) unsigned NOT NULL default '0',
						  shop_id mediumint(9) NOT NULL default '0',
						  item_id int(10) NOT NULL default '0',
						  enemy_id int(15) NOT NULL default '',
						  gp decimal(15,2) unsigned default '0.00',
						  clan_id mediumint(10) NOT NULL default '0',
						  exp mediumint(8) NOT NULL default '0',
						  id int(15) unsigned NOT NULL auto_increment,
						  `time` int(14) NOT NULL default '0',
						  level_user tinyint(3) unsigned NOT NULL default '0',
						  level_enemy tinyint(3) unsigned NOT NULL default '0',
						  PRIMARY KEY  (id),
						  KEY stat_id (stat_id,level_user,level_enemy)
						) ENGINE=MyISAM";
		myquery($str_query,$db_stat) or die(mysql_error());
	}
	$sel_item = myquery("SELECT id FROM game_stat_item WHERE name='$par_item'",$db_stat) or die(mysql_error());
	{
		if ($sel_item!=false AND mysql_num_rows($sel_item)>0)
		{
			list($item_id) = mysql_fetch_array($sel_item);
		}
		else
		{
			myquery("INSERT INTO item (name) VALUES ('$par_item')",$db_stat) or die(mysql_error());
			$item_id = mysql_insert_id();
		}
	}
	*/
	$item_id = $par_item;
	if (gettype($item_id)=='integer')
	{
		list($item_id) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=$item_id"));
	}
	$table_name = 'game_stat';
	$str_query = "INSERT DELAYED INTO $table_name (user_id,npc_id,town,stat_id,shop_id,item_id,enemy_id,gp,clan_id,exp,level_user,level_enemy,time) VALUES ('$par_user_id','$par_npc_id','$par_town','$par_stat_id','$par_shop_id','$item_id','$par_enemy_id','$par_gp','$par_clan_id','$par_exp','$par_level_user','$par_level_enemy',".time().")";
	myquery($str_query);
	DbConnect();
}

function OpenTable($openTableParam, $quoteTableWidth = '100%', $quoteTableHeight = 0)
{
	if ($openTableParam == 'title')
	{
		echo '<table cellpadding="0" cellspacing="2" width="'.$quoteTableWidth.'" border="0"';
		if ($quoteTableHeight!=0) echo ' height="'.$quoteTableHeight.'"';
		echo '><tr><td valign="top" class=m background="http://'.IMG_DOMAIN.'/nav/image_01.jpg" align=left>';
	}
	elseif ($openTableParam == 'close')
	{
		echo '</td></tr></table>';
	}
	else
	{
		echo 'Ошибка OpenTable!';
	}
}

function QuoteTable($quoteTableParam, $quoteTableWidth = '')
{
	if ($quoteTableParam == 'open')
	{
		if ($quoteTableWidth!='')
		{
			$quoteTableWidth = ' width="' . $quoteTableWidth . '"';
		}
		echo '<table cellpadding="0" cellspacing="0"' . $quoteTableWidth . ' border="0">
		<tr><td style="width:15px;"><img src="http://'.IMG_DOMAIN.'/nav/quote_ul.gif" width="15" height="7" border="0" alt=""></td>
		<td background="http://'.IMG_DOMAIN.'/nav/quote_tp.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
		<td style="width:15px;"><img src="http://'.IMG_DOMAIN.'/nav/quote_ur.gif" width="15" height="7" border="0" alt=""></td>
		</tr><tr><td style="width:15px;" background="http://'.IMG_DOMAIN.'/nav/quote_lt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td><td>';
	}
	elseif ($quoteTableParam == 'close')
	{
		echo '</td><td style="width:15px;" background="http://'.IMG_DOMAIN.'/nav/quote_rt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
		</tr>
		<tr><td style="width:15px;"><img src="http://'.IMG_DOMAIN.'/nav/quote_dl.gif" width="15" height="7" border="0" alt=""></td>
		<td background="http://'.IMG_DOMAIN.'/nav/quote_bt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
		<td style="width:15px;"><img src="http://'.IMG_DOMAIN.'/nav/quote_dr.gif" width="15" height="7" border="0" alt=""></td>
		</tr></table>';
	}
	else
	{
		echo 'Ошибка QuoteTable!';
	}
}

function StartTiming()
{
	$micro_time = microtime();
	$time_parts = explode(" ",$micro_time);
	$start_time = $time_parts[1] . substr($time_parts[0],1);
	return $start_time;
}

function StopTiming($startTime)
{
	$micro_time = microtime();
	$time_parts = explode(" ",$micro_time);
	$end_time = $time_parts[1] . substr($time_parts[0],1);
	$exec_time = round($end_time - $startTime, 5)*1000;
	return $exec_time;
}

function HostIdentify()
{
	$str_return = ip2number($_SERVER['REMOTE_ADDR']);
	return $str_return;
}

function HostIdentifyMore()
{
	global $HTTP_CLIENT_IP,$HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_COMING_FROM, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;

   $str_return = '';

   if ($HTTP_CLIENT_IP)
   {
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_CLIENT_IP, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_CLIENT_IP;
		}
	}
	elseif ($HTTP_X_FORWARDED_FOR)
	{
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_X_FORWARDED_FOR, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_X_FORWARDED_FOR;
		}
	}
	elseif ($HTTP_X_FORWARDED)
	{
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_X_FORWARDED, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_X_FORWARDED;
		}
	}
	elseif ($HTTP_FORWARDED_FOR)
	{
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_FORWARDED_FOR, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_FORWARDED_FOR;
		}
	}
	elseif ($HTTP_FORWARDED)
	{
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_FORWARDED, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_FORWARDED;
		}
	}
	elseif ($HTTP_VIA)
	{
		$str_return = $HTTP_VIA . '_' . $HTTP_X_COMING_FROM . '_' . $HTTP_COMING_FROM;
	}
	elseif($HTTP_X_COMING_FROM || $HTTP_COMING_FROM)
	{
		$str_return = $HTTP_X_COMING_FROM . '_' . $HTTP_COMING_FROM;
	}

	return $str_return;
}

function GetGameCalendar_Year($param_data_year,$param_data_month,$param_data_day)
{
	$data_year = (int)$param_data_year;
	$data_month = (int)$param_data_month;
	$data_day = (int)$param_data_day;
	$a = 0;
	if (floor($data_year/4)==($data_year/4)) $a = 1;
	$data_year = $data_year-2000;
	$data_year = ($data_year - floor($data_year/4) - 1)*365+(floor($data_year/4)+1)*366-1;
	if ($data_month == 1) $data_month = $data_day;
	elseif ($data_month == 2) $data_month = $data_day+31;
	elseif ($data_month == 3) $data_month = $data_day+59+$a;
	elseif ($data_month == 4) $data_month = $data_day+90+$a;
	elseif ($data_month == 5) $data_month = $data_day+120+$a;
	elseif ($data_month == 6) $data_month = $data_day+151+$a;
	elseif ($data_month == 7) $data_month = $data_day+181+$a;
	elseif ($data_month == 8) $data_month = $data_day+212+$a;
	elseif ($data_month == 9) $data_month = $data_day+243+$a;
	elseif ($data_month == 10) $data_month = $data_day+273+$a;
	elseif ($data_month == 11) $data_month = $data_day+304+$a;
	elseif ($data_month == 12) $data_month = $data_day+334+$a;

	$data_year = $data_year + $data_month - 1657;
	If (floor($data_year/12)==($data_year/12))
	{
		$data_month = 12;
		$data_year = floor($data_year/12)-1;
	}
	else
	{
		$data_month = $data_year - floor($data_year/12)*12;
		$data_year = floor($data_year/12);
	}
	return $data_year;
}

function GetGameCalendar_Month($param_data_year,$param_data_month,$param_data_day)
{
	$data_year = (int)$param_data_year;
	$data_month = (int)$param_data_month;
	$data_day = (int)$param_data_day;
	$a = 0;
	if (floor($data_year/4)==($data_year/4)) $a = 1;
	$data_year = $data_year-2000;
	$data_year = ($data_year - floor($data_year/4) - 1)*365+(floor($data_year/4)+1)*366-1;
	if ($data_month == 1) $data_month = $data_day;
	elseif ($data_month == 2) $data_month = $data_day+31;
	elseif ($data_month == 3) $data_month = $data_day+59+$a;
	elseif ($data_month == 4) $data_month = $data_day+90+$a;
	elseif ($data_month == 5) $data_month = $data_day+120+$a;
	elseif ($data_month == 6) $data_month = $data_day+151+$a;
	elseif ($data_month == 7) $data_month = $data_day+181+$a;
	elseif ($data_month == 8) $data_month = $data_day+212+$a;
	elseif ($data_month == 9) $data_month = $data_day+243+$a;
	elseif ($data_month == 10) $data_month = $data_day+273+$a;
	elseif ($data_month == 11) $data_month = $data_day+304+$a;
	elseif ($data_month == 12) $data_month = $data_day+334+$a;

	$data_year = $data_year + $data_month - 1657;
	If (floor($data_year/12)==($data_year/12))
	{
		$data_month = 12;
		$data_year = floor($data_year/12)-1;
	}
	else
	{
		$data_month = $data_year - floor($data_year/12)*12;
		$data_year = floor($data_year/12);
	}
	return $data_month;
}

function CheckUser($param_user_name)
{
	$sel = myquery("(SELECT * FROM game_users WHERE name='$param_user_name') UNION (SELECT * FROM game_users_archive WHERE name='$param_user_name') LIMIT 1");
	$user = mysql_fetch_array($sel);

	$str = $user['STR_MAX'];
	$ntl = $user['NTL_MAX'];
	$pie = $user['PIE_MAX'];
	$vit = $user['VIT_MAX'];
	$dex = $user['DEX_MAX'];
	$spd = $user['SPD_MAX'];
	$clevel = $user['clevel'];
	$race = $user['race'];

	$vsego = $str+$ntl+$pie+$vit+$dex+$spd+$user['bound'];

	$sel_race = myquery("SELECT * FROM game_har WHERE id = '".$race."' LIMIT 1");
	$user_race = mysql_fetch_array($sel_race);
	$summa_race = $user_race['str']+$user_race['ntl']+$user_race['pie']+$user_race['vit']+$user_race['dex']+$user_race['spd'];
	$summa = $summa_race;

	$sel_items = myquery("SELECT * FROM game_items WHERE user_id='".$user['user_id']."'");
	while ($user_items = mysql_fetch_array($sel_items))
	{
	  if ($user_items['used']!=0)
	  {
		  $item_fact = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=".$user_items['item_id'].""));
		  $summa=$summa+$item_fact['dstr'];
		  $summa=$summa+$item_fact['dntl'];
		  $summa=$summa+$item_fact['dpie'];
		  $summa=$summa+$item_fact['dvit'];
		  $summa=$summa+$item_fact['ddex'];
		  $summa=$summa+$item_fact['dspd'];
	  }
	}

	$har_level=0;
	for ($i=1;$i<=$clevel;$i++)
	{
		if ($i==11 OR $i==21 OR $i==31)
		{
			$har_level=$har_level+3;
		}
		else
		{
			$har_level=$har_level+2;
		}
	}

	$summa=$summa+$har_level;

	$razn1 = $vsego-$summa;

	if ($param_user_name=='blazevic' OR $param_user_name=='Артас' OR $param_user_name=='The_Elf' OR $param_user_name=='Санкир2' OR $param_user_name=='ban' OR $param_user_name=='Zander')
	{
		$razn1 = 0;
		$razn2 = 0;
	}

	if ($razn1!=0)
	{
		return $razn1;
	}
	else
	{
		return 0;
	}

}

function replace_enter($str)
{
	$str_return = str_replace(chr(13).''.chr(10),'<br>',$str);
	return  $str_return;
}

function return_enter($str)
{
	$str_return = str_replace('<br>',chr(13).''.chr(10),$str);
	return  $str_return;
}

function jump_random_query(&$query)
{
	$all = mysql_num_rows($query);
	if ($all>0)
	{
		$r = mt_rand(0,$all-1);
		mysql_data_seek($query,$r);
		return 1;
	}
	return 0;
}

function kill_horse($id_horse)
{
	$horse = mysql_fetch_array(myquery("SELECT * FROM game_users_horses WHERE id=$id_horse"));
	if ($horse['used']==1)
	{
		$vsad = mysql_fetch_array(myquery("SELECT ves FROM game_vsadnik WHERE id=".$horse['horse_id'].""));
		mysql_query("UPDATE game_users SET vsadnik=0,CC=CC-".$vsad['ves']." WHERE user_id=".$horse['user_id']."");
		mysql_query("UPDATE game_users_archive SET vsadnik=0,CC=CC-".$vsad['ves']." WHERE user_id=".$horse['user_id']."");
	}
	myquery("DELETE FROM game_users_horses WHERE id=$id_horse");
}
?>