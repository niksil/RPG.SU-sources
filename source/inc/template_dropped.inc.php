<?php

if (function_exists("start_debug")) start_debug(); 

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
	setLocation('index.php');
}
else
{
	if (isset($_GET['takeres']))
	{
		$res_id = (int)$_GET['takeres'];
		$result_items = myquery("SELECT craft_resource_market.*,craft_resource.weight,craft_resource.img3,craft_resource.name FROM craft_resource_market,craft_resource WHERE craft_resource_market.col>0 AND craft_resource_market.user_id=0 AND craft_resource_market.town=0 AND craft_resource_market.map_name='" . $char['map_name'] . "' AND craft_resource_market.map_xpos=" . $char['map_xpos'] . " AND craft_resource_market.map_ypos=" . $char['map_ypos'] . " AND craft_resource_market.res_id=craft_resource.id AND craft_resource.id=$res_id");
		if (mysql_num_rows($result_items) > 0)
		{
			$items = mysql_fetch_array($result_items);
			//$alt = $ress['name'].' '.$items['col'].' ед. (вес - '.($items['col']*$ress['weight']).')';
			// '<a href="item.php?inv_option=takeres&id='.$items['id'].'"><img src="http://'.IMG_DOMAIN.'/item/resources/' . $ress['img3'] . '.gif" width="50" height="50" border="0" title="'.$alt.'" alt="'.$alt.'">';
			echo 'Поднять <input type="text" id="col" value="'.$items['col'].'" size="5"> из <b>'.$items['col'].'</b> '.pluralForm($items['col'],'единицы','единиц','единиц').' ресурса &nbsp;&nbsp;<img src="http://'.IMG_DOMAIN.'/item/resources/' . $items['img3'] . '.gif" width="50" height="50" border="0">&nbsp;'.$items['name'].' (<i>Вес 1 единицы ресурса: '.$items['weight'].'</i>)&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Поднять ресурс" onClick="location.href=\'item.php?inv_option=takeres&id='.$items['id'].'&col=\'+document.getElementById(\'col\').value+\'\'">';
		}
	}
	else
	{
		$result_items = myquery("SELECT craft_resource_market.*,craft_resource.weight,craft_resource.img3,craft_resource.name FROM craft_resource_market,craft_resource WHERE craft_resource_market.col>0 AND craft_resource_market.user_id=0 AND craft_resource_market.town=0 AND craft_resource_market.map_name='" . $char['map_name'] . "' AND craft_resource_market.map_xpos=" . $char['map_xpos'] . " AND craft_resource_market.map_ypos=" . $char['map_ypos'] . " AND craft_resource_market.res_id=craft_resource.id ORDER BY craft_resource.name");
		if (mysql_num_rows($result_items) > 0)
		{
			while ($items = mysql_fetch_array($result_items))
			{
				$alt = $items['name'].' '.$items['col'].' ед. (вес - '.($items['col']*$items['weight']).')';
				echo '<a href="?takeres='.$items['res_id'].'"><img src="http://'.IMG_DOMAIN.'/item/resources/' . $items['img3'] . '.gif" width="50" height="50" border="0" title="'.$alt.'" alt="'.$alt.'">';
			}
		}
		

		$result_items = myquery("SELECT game_items.id, game_items_factsheet.img, game_items_factsheet.name,game_items_factsheet.type FROM game_items, game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.user_id=0 AND game_items.map_name='" . $char['map_name'] . "' AND game_items.map_xpos=" . $char['map_xpos'] . " AND game_items.map_ypos=" . $char['map_ypos'] . " AND (game_items_factsheet.type=97 OR game_items_factsheet.type<90) AND game_items.priznak=2 ORDER BY game_items_factsheet.type");
		if (mysql_num_rows($result_items) > 0)
		{
			while ($items = mysql_fetch_array($result_items))
			{
				switch ($items['name'])
				{
					case 'Сундук с сокровищами':
						$alt='Ты '.echo_sex('нашел','нашла').' сундук с сокровищем! (ты получишь деньги)';break;
					case 'Кадка воды':
						$alt="Ты ".echo_sex('нашел','нашла')." кадку воды (ты пополнишь запас энергии)";break;
					case 'Кусок мяса':
						$alt="Ты ".echo_sex('нашел','нашла')." кусок мяса (ты пополнишь запас жизненных сил)";break;
					case 'Магический эликсир':
						$alt="Ты ".echo_sex('нашел','нашла')." магический эликсир (ты пополнишь запас маны)";break;
					case 'Бутылка с голубым зельем':
						$alt="Ты ".echo_sex('нашел','нашла')." бутылку с неизвестным зельем  (пей на свой риск!)";break;
					case 'Бутылка с бордовым зельем':
						$alt="Ты ".echo_sex('нашел','нашла')." бутылку с неизвестным зельем  (пей на свой риск!)";break;
					case 'Неизвестная бутыль':
						$alt="Ты ".echo_sex('нашел','нашла')." бутылку с неизвестным зельем  (пей на свой риск!)";break;
					case 'Ампула с эликсиром':
						$alt="Ты ".echo_sex('нашел','нашла')." бутылку с неизвестным зельем  (пей на свой риск!)";break;
					case 'Зелье зарядки артефактов':
						$alt="Зелье концентрированной эссенции - заряжает артефакты";break;
					case 'Сундук ключника':
						$alt="Сундук содержит сокровища старого ключника, его можно открыть только ключом от сундука ключника";break;
					default:
						$alt = "Поднять предмет!";
					break;
				}
				switch ($items['type'])
				{
					case 1:
						$items['img']='unident/sword3';break;
					case 5:
						$items['img']='unident/armour3';break;
					case 3:
						$items['img']='unident/art3';break;
					case 8:
						$items['img']='unident/belt3';break;
					case 6:
						$items['img']='unident/helmet3';break;
					case 7:
						$items['img']='unident/magic3';break;
					case 2:
						$items['img']='unident/ring3';break;
					case 4:
						$items['img']='unident/shield3';break;
                    default:
                        $alt=$items['name'];break;
				}
				echo '<a href="item.php?inv_option=take&id='.$items['id'].'"><img src="http://'.IMG_DOMAIN.'/item/' . $items['img'] . '.gif" width="50" height="50" border="0" alt="'.$alt.'" title="'.$alt.'">';
			}
		}
		$maze_check=myquery("SELECT maze FROM game_maps WHERE id=".$char['map_name']."");
		if (mysql_num_rows($maze_check)>0)
		{
			$maze=mysql_result(($maze_check),0,0);
			if ($maze==1)
			{
				$type = mysql_result(myquery("SELECT type FROM game_maze WHERE map_name=".$char['map_name']." AND xpos=".$char['map_xpos']." AND ypos=".$char['map_ypos'].""),0,0);
				if ($type>2 AND $type<11)
				{
					if ($type==3 OR $type==4)
					{
						$item_img = "other/sunduk.gif";
						$alt = "Неизвестный сундук";
					}
					else
					{
						$ra = mt_rand(2,5);
						$item_img = "other/bottle$ra.gif";
						$alt = "Неизвестная бутыль";
					}
					echo '<a href="item.php?inv_option=take"><img src="http://'.IMG_DOMAIN.'/item/' . $item_img . '" width="30" height="30" border="0" alt="'.$alt.'" title="'.$alt.'">';
				}
			}
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>