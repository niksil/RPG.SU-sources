<?
/*
Группы ресурсов:
1 - Нет группы
2 - Собиратель
3 - Мория 1
4 - Мория 2
5 - Мория 3
6 - Руда
7 - Самородки
8 - Драгоценные камни
9 - Слитки
10 - Скорняк
11 - Стройматериалы

Тип формул:
1 - По группе ресурсов
2 - По типу предмета
3 - По идентификаторам ресурсов
4 - По идентификаторам предметов
*/

if (function_exists("start_debug")) start_debug(); 

//$price=0; $price_har=0;
if ($town!=0)
{
	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено пользоваться алтарем!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	
	$cost=10; //Стоимость игры
	
	function choose_prize ($random, $arr, $type, $kol, $start) //Посмотрим, что же игроку досталось
	{
		$name='';
		/*if ($type==1)
		{
			$res_test=myquery("Select id from craft_resource where craft_resource_type=$arr  
														   Join game_items On game_items.item_id=game_items_factsheet.id
														   Where game_items_factsheet.type=$attr"));
			
		}
		elseif ($type==2)
		{
			list($kol_all_item)=mysql_fetch_array(myquery("Select count(game_items.id) from game_items_factsheet.name 
														   Join game_items On game_items.item_id=game_items_factsheet.id
														   Where game_items_factsheet.type=$attr"));
						   
		    $item=myquery("Select game_items_factsheet.name, count(game_items.id) from game_items_factsheet.name 
						   Join game_items On game_items.item_id=game_items_factsheet.id
						   Where game_items_factsheet.type=$attr");
		    while (list($name_item, $kol_item)=mysql_fetch_array($item))
			{
				$start=$start+round($kol*$kol_item/$kol_all_item);
				if ($random<=$start) {$name=$name_item;}
			}
		}
		else*/if ($type==3)
		{
			$kol_all_res=0;
			$i=0;
			foreach ($arr as $value) 
			{
				$res_user=0;
				$res_market=0;
				$res_user_test=myquery("Select * from craft_resource_user where res_id=$value");
			    $res_market_test=myquery("Select * from craft_resource_market where res_id=$value");
			    if (mysql_num_rows($res_user_test)>0) list($res_user)=mysql_fetch_array(myquery("Select sum(col) from craft_resource_user where res_id=$value"));
			    if (mysql_num_rows($res_market_test)>0) list($res_market)=mysql_fetch_array(myquery("Select sum(col) from craft_resource_market where res_id=$value"));
			    $res[$i]=$res_user+$res_market;
			    $kol_all_res=$kol_all_res+$res[$i];
			    $i++;
			}
			$i=0;
			if ($kol_all_res>0)
			{
				foreach ($arr as $value) 
				{
					$start=$start+round($kol*$res[$i]/$kol_all_res);
					if ($random<=$start) {list($name)=mysql_fetch_array(myquery("Select name from craft_resource where id=$value")); break;}
					$i++;
				}
			}
		}
		elseif ($type==4)
		{
			$kol_all_item=0;
			$i=0;
			foreach ($arr as $value) 
			{
			   $item1=0;
			   $item_test=myquery("Select * from game_items where item_id=$value");
			   if (mysql_num_rows($item_test)>0) list($item1)=mysql_fetch_array(myquery("Select sum(count_item) from game_items where item_id=$value"));
			   $item[$i]=$item1;
			   $kol_all_item=$kol_all_item+$item[$i];
			   $i++;
			}
			$i=0;
			if ($kol_all_item>0)
			{
				foreach ($arr as $value) 
				{
					$start=$start+round($kol*$item[$i]/$kol_all_item);
					//if ($random<=$start) {list($name)=mysql_fetch_array(myquery("Select name from game_items_factsheet where id=$value")); break;}
					$i++;
				}
			}
		}
		return $name;
	}
		
	$img='http://'.IMG_DOMAIN.'/race_table/human/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';
	
	if (isset($play))	//Форма подтверждения для услуг, где не нужны первоначальные проверки
	{
		if ($char['clevel']<12)
		{
			echo'<center><font face=verdana color=ff0000 size=2>Лишь игроки, достигшие 12-ого уровня, могут проверить свою удачу в Шатре Азарта!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif ($char['GP']<$cost)
		{
			echo'<center><font face=verdana color=ff0000 size=2>У Вас нет денег для игры!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif ($char['CC']-$char['CW']<0.01)
		{
			echo'<center><font face=verdana color=ff0000 size=2>У Вас недостаточно места в инвентаре для игры!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		else
		{
			$random=mt_rand(1,10000);
			if ($random<=3500)
			{ 	$name='Уголь'; $type='res';	}
			elseif ($random<=3523)
			{ 	$name='Кольцо начинающего мага';    $type='item';}
			elseif ($random<=3546)
			{	$name='Кольцо начинающего воина';   $type='item';}
			elseif ($random<=3573)
			{	$name='Магическая шапочка новичка';	$type='item';} 
			elseif ($random<=3600)
			{	$name='Шлем корсара';    			$type='item';}
			elseif ($random<=3631)
			{	$name='Магическая накидка новичка'; $type='item';}
			elseif ($random<=3662)
			{	$name='Доспех новичка'; 			$type='item';}
			elseif ($random<=3678)
			{	$name='Пояс удачи'; 				$type='item';}
			elseif ($random<=3774)
			{	$name='Четырехлистник';				$type='item';}
			elseif ($random<=3841)
			{	$name='Бутылка шампанского';  	    $type='item';}
			elseif ($random<=3975)
			{	$name='Хлопушка'; 				    $type='item';}
			elseif ($random<=4109)
			{	$name='Кожаный ремень';				$type='item';}
			elseif ($random<=4202)
			{	$name='Стальной пояс';				$type='item';}
			elseif ($random<=4211)
			{	$name='Пояс лазурного дракона';		$type='item';}
			elseif ($random<=4310)
			{	$name='Щит королей';				$type='item';}
			elseif ($random<=4394)
			{	$name='Кольцо мудрости';			$type='item';}
			elseif ($random<=4496)
			{	$name='Кольцо защиты';		    	$type='item';}
			elseif ($random<=4620)
			{	$name='Кольцо ловкости';			$type='item';}
			elseif ($random<=5120)
			{ 	$name='Туша оленя';                 $type='res';}
			elseif ($random<=5220)
			{ 	$name='Железный клин';              $type='item';}
			elseif ($random<=5258)
			{ 	$name='Эликсир могущества';         $type='item';}
			elseif ($random<=5358)
			{ 	$name='Короткая стрела';            $type='item';}
			elseif ($random<=5458)
			{ 	$name='Острая косточка';            $type='item';}
			elseif ($random<=5508)
			{ 	$name='Алая роза';                  $type='item';}
			elseif ($random<=6008)
			{ 	$name='Малый свиток присоединения к бою';    $type='item';}
			elseif ($random<=6258)
			{ 	$name='Средний свиток присоединения к бою';  $type='item';}
			elseif ($random<=6358)
			{ 	
				$arr=array("11", "51");
				$name=choose_prize($random, $arr, 3, 100, 6259);  
				$type='res';
			}
			elseif ($random<=6410)
			{ 	
				$arr=array("52", "53", "54", "55");
				$name=choose_prize($random, $arr, 3, 50, 6359);
				$type='res';
			}
			elseif ($random<=6515)
			{ 	
				$arr=array("63", "64", "65", "66", "67");
				$name=choose_prize($random, $arr, 3, 100, 6411);  
				$type='res';
			}
			elseif ($random<=6723)
			{ 	
				$arr=array("31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45");
				$name=choose_prize($random, $arr, 3, 200, 6516);  
				$type='res';
			}
			elseif ($random<=9426)
			{ 	
				$arr=array("49", "50", "56", "60");
				$name=choose_prize($random, $arr, 3, 2700, 6724);  
				$type='res';
			}
			elseif ($random<=9531)
			{ 	
				$arr=array("69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81", "82", "83");
				$name=choose_prize($random, $arr, 3, 100, 9427);  
				$type='res';
			}
			elseif ($random<=9591)
			{ 	
				$arr=array("85", "86", "87", "88", "89", "90", "91", "92", "93", "94", "96", "97", "98", "99");
				$name=choose_prize($random, $arr, 3, 50, 9532);  
				$type='res';
			}
			/*elseif ($random<=9592)
			{ 	$name='Янтарь';  $type='res';}*/
			elseif ($random<=9698)
			{ 	
				$arr=array("479", "480", "481", "482", "483", "485", "486", "487");
				$name=choose_prize($random, $arr, 4, 100, 9592);  
				$type='item';
			}
			elseif ($random<=10000)
			{ 	
				$arr=array("319", "321", "322");
				$name=choose_prize($random, $arr, 4, 100, 9699);  
				$type='item';
			}
			if (!isset($name) or !isset($type) or ($name==''))
			{	$name='Уголь';				$type='res';}
			
			
			//Выдача приза
			if ($type=='res')
			{
				list($id, $weight)=mysql_fetch_array(myquery("Select id, weight From craft_resource Where name like '$name'"));
				if (!isset($id) or $id=='')
				{
					$no_prize=1;
				}
				else 
				{
					myquery("insert into craft_resource_user (user_id, res_id, col) values ($user_id, $id, 1) ON DUPLICATE KEY UPDATE col=col+1");
				}
				
			}
			elseif ($type=='item')
			{
				list($id, $weight)=mysql_fetch_array(myquery("Select id, weight From game_items_factsheet Where name like '$name'"));
				if (!isset($id) or $id=='')
				{
					$no_prize=1;
				}
				else
				{
					$Item = new Item();
					$ar = $Item->add_user($id,$user_id);
					$weight=0;
				}
			}
			
			
			if (isset($no_prize) and $no_prize==1)
			{
				echo'<center><font face=verdana color=ff0000 size=2>К сожалению, приз '.$name.' не найден!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
			}
			else
			{
				myquery("Update game_users Set GP=GP-$cost, CW=CW+$weight Where user_id=$user_id");
				setGP($user_id,-$cost,107); 
				echo'<center><font face=verdana color=white size=2><br/><b>Ваш приз: '.$name.'</b><br/><br/></font><meta http-equiv="refresh" content="5;url=town.php?option='.$option.'">';
			}
			
		}
		
	}
	else
	{
		echo'<center>
			 <font face=verdana color=ff0000 size=2><b>Шатёр Азарта</b></font><br/><br/>
			 <b><font face=verdana color=white size=2>Здравствуй, Путник! Здесь ты можешь отдохнуть от окружающего мира и сыграть в увлекательную игру, в которой нет проигравших! <br/>Заплатив <u>'.$cost.'</u> '.pluralForm($cost,'монету','монеты','монет').', ты получаешь случайный приз! <br/>Но кто знает, ЧТО там будет?</font><br/><br/><br/></b>';	
		if ($char['clevel']>=12)
		{
			echo '<input type="button"  style="width: 150px" onClick="location.href=\'town.php?option='.$option.'&play\'" value="Сыграть в игру"><br/><br/></center>';
		}
		else
		{
			echo '<font face=verdana color=ff0000 size=2>Лишь игроки, достигшие 12-ого уровня, могут проверить свою удачу в Шатре Азарта!</font>';
		}
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}
if (function_exists("save_debug")) save_debug(); 

?>