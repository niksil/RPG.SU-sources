<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['search_items']>=1)
{
	?>
	<script type="text/javascript">
	/* URL to the PHP page called for receiving suggestions for a keyword*/
	var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
	var startSearch = 3;
	</script>
	<?
	echo '<div id="content" onclick="hideSuggestions();"><center>Поиск предметов:</center><br><br>';
	echo '<center><font size="1" face="Verdana" color="#ffffff">Поиск: <input id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
	<input name="" type="button" value="Найти" type="text" value="" size="20" maxlength="40" onClick="location.href=\'admin.php?opt=main&option=search&name_v=\'+document.getElementById(\'keyword\').value"></div><script>init();</script>';

	if (isset($item_name)) $name_v = $item_name;

	if ($adm['search_items']==1)
	{
		if (isset($_POST['searchmap']))
		{
			$select = myquery("SELECT game_items_factsheet.name,game_items.map_name,game_items.map_xpos,game_items.map_ypos,game_items_factsheet.img,game_items.kleymo FROM game_items,game_items_factsheet WHERE game_items.map_name=".$_POST['map']." AND game_items.user_id=0 AND game_items.priznak=2 AND game_items.item_id=game_items_factsheet.id");
			if (mysql_num_rows($select))
			{
				list($namemap) = mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$_POST['map'].""));
				echo '<br><hr><br><b><font size="3" color="#bbbbbb">Предметы на карте '.$namemap.'</font></b><br><br>';
				echo '<table>';
				while ($user = mysql_fetch_array($select))
				{
					echo '<tr><td>';
					ImageItem($user['img'],0,$user['kleymo']);
					echo'</td><td>'.$user['name'].'</td><td>Лежит на карте: '.$namemap.' x-'.$user['map_xpos'].', y-'.$user['map_ypos'].'</td></tr>';
				}
				echo '</table>';
			}
		}
		elseif (isset($_REQUEST['searchuser']) AND isset($_POST['user']))
		{
			echo '<br><br><center><b><font face="Verdana" size=2>Предметы игрока: '.$_POST['user'].'</b></center><SCRIPT language=javascript src="http://'.DOMAIN.'/js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 100; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
			$prov = myquery("select * from game_users where name='".$_POST['user']."' limit 1");
			if ($prov==false OR mysql_num_rows($prov)==0) $prov = myquery("select * from game_users_archive where name='".$_POST['user']."' limit 1");            if (mysql_num_rows($prov)>0)
			{
				$user = mysql_fetch_array($prov);
				echo '<form action="" method="post">';
				echo '<table cellpadding="0" cellspacing="5" border="0">';
				echo '<tr><td><br>Надетые предметы:</td></tr><tr><td>';
				$sel = myquery("select id from game_items where user_id='".$user['user_id']."' and used>0 AND priznak=0");
				while ($it = mysql_fetch_array($sel))
				{
					$Item = new Item($it['id']);
					$Item->hint(0,0,'<a ');
					ImageItem($Item->fact['img'],0,$Item->item['kleymo'],"top");
					echo '</a>
					<input type="checkbox" name="array_item[]" value="'.$it['id'].'">&nbsp;&nbsp;';
				}
				echo '</td></tr>';

				echo '<tr><td><br>Предметы в рюкзаке:</td></tr><tr><td>';
				$sel = myquery("select id from game_items where user_id=".$user['user_id']." and used=0 AND priznak=0");
				if ($sel!=false AND mysql_num_rows($sel))
				{
				while ($it = mysql_fetch_array($sel))
				{
					$Item = new Item($it['id']);
					if ($Item->getFact('type')==99) continue;
					$Item->hint(0,0,'<a ');
					ImageItem($Item->fact['img'],0,$Item->item['kleymo'],"top");
					echo '</a>
					<input type="checkbox" name="array_item[]" value="'.$it['id'].'">&nbsp;&nbsp;';
				}
				}
				echo '<tr><td><br>Ресурсы:</td></tr><tr><td>';
				$sel = myquery("select * from craft_resource_user where user_id=".$user['user_id']."");
				if ($sel!=false AND mysql_num_rows($sel)>0)
				{
				while ($it = mysql_fetch_array($sel))
				{
					$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$it['res_id'].""));
	?><a onmousemove=movehint(event) onmouseover="showhint('<?php
					echo '<center><font color=#0000FF>'.$ress['name'].'</font>';
	?>','<?php
					echo '<font color=000000>';
					echo 'Вес: '.($ress['weight']*$it['col']).'<br>';
	?>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?php
					echo '<img src="http://'.IMG_DOMAIN.'/item/resources/'.$ress['img2'].'.gif" width="30" height="30" border="0" align="top"></a>
					<input type="checkbox" name="array_res[]" value="'.$it['id'].'">Уд.<input type="text" name="array_res_col_'.$ress['id'].'" size=5> из <b>'.$it['col'].'</b>&nbsp;&nbsp;';
				}
				}
				echo '</td></tr>';
				echo '</table><input type="hidden" name="usrid" value="'.$user['user_id'].'">';
				echo '<br><br><input type="submit" name="delete" value="Удалить отмеченные предметы игрока">';
				echo '<br><br><input type="submit" name="take" value="Удалить отмеченные предметы игрока c возвратом денег">';
				echo '</form>';
			}
		}
		elseif (isset($_REQUEST['delete']) or isset($_REQUEST['take']))
		{
			echo '<center>Ты '.echo_sex('выбрал','выбрала').' следующие предметы';
			echo '<form action="" method="POST">';
			if (isset($_REQUEST['array_item']))
			{
				$ar = $_REQUEST['array_item'];
				for ($i = 0; $i < sizeof($ar); $i++)
				{
					$selit = myquery("SELECT game_items.id,game_items_factsheet.img,game_items_factsheet.name,game_items.kleymo FROM game_items,game_items_factsheet WHERE game_items.user_id=".$_REQUEST['usrid']." AND game_items.id = ".$ar[$i]." AND game_items.item_id=game_items_factsheet.id");
					if (mysql_num_rows($selit))
					{
						$it = mysql_fetch_array($selit);
						echo '<br>';
						ImageItem($it['img'],0,$it['kleymo'],"top");
						echo $it['name'] . '';
						echo '<input type="hidden" name="array_items[]" value="'.$it['id'].'">';
					}
				}
			}
			if (isset($_REQUEST['array_res']))
			{
				$ar = $_REQUEST['array_res'];
				for ($i = 0; $i < sizeof($ar); $i++)
				{
					$selit = myquery("SELECT * FROM craft_resource_user WHERE user_id=".$_REQUEST['usrid']." AND id = ".$ar[$i]."");
					if (mysql_num_rows($selit))
					{
						$it = mysql_fetch_array($selit);
						$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$it['res_id'].""));
						echo '<br><img src="http://'.IMG_DOMAIN.'/item/resources/'.$ress['img2'].'.gif" width="30" height="30" border="0" align="top">'.$ress['name'] .'('.$_REQUEST['array_res_col_'.$ress['id']].' шт.)';
						echo '<input type="hidden" name="array_ress[]" value="'.$it['id'].'"><input type="hidden" name="array_ress_col_'.$ress['id'].'" value="'.$_REQUEST['array_res_col_'.$ress['id']].'">';
					}
				}
			}
			echo '<br><br><input type="submit" name="delete_items" value="Удалить отмеченные предметы игрока">';
			echo '<br><br><input type="submit" name="take_items" value="Удалить отмеченные предметы игрока c возвратом денег">';
			echo '</form>';
		}
		elseif (isset($_POST['delete_items']) or isset($_POST['take_items']))
		{
			if (isset($_REQUEST['array_items']))
			{
				$ar_it = $_REQUEST['array_items'];
				for ($i = 0; $i < sizeof($ar_it); $i++)
				{
					$deleteitem = $ar_it[$i];
					$Item = new Item($deleteitem);
					$Item->admindelete();
					echo '<br>Предмет <b>' . $Item->getFact('name') . '</b> успешно удален';
					if (isset($_POST['take_items']))
					{
						$item_cost = $Item->getFact('item_cost');
						myquery("UPDATE game_users SET GP=GP+$item_cost,CW=CW+".($item_cost*money_weight)." WHERE user_id=".$Item->getItem('user_id')."");
						myquery("UPDATE game_users_archive SET GP=GP+$item_cost,CW=CW+".($item_cost*money_weight)." WHERE user_id=".$Item->getItem('user_id')."");
						setGP($Item->getItem('user_id'),$item_cost,19);
						$da = getdate();
						$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
						 VALUES (
						 '".$char['name']."',
						 'Удалил предмет: <b>" . $Item->getFact('name') . "</b> у игрока ".$Item->getItem('user_id')."',
						 '".time()."',
						 '".$da['mday']."',
						 '".$da['mon']."',
						 '".$da['year']."')")
							 or die(mysql_error());
					}
				}
			}
			if (isset($_REQUEST['array_ress']))
			{
				$ar_it = $_REQUEST['array_ress'];
				for ($i = 0; $i < sizeof($ar_it); $i++)
				{
					$selit = myquery("SELECT * FROM craft_resource_user WHERE id = " . $ar_it[$i] . "");
					$it = mysql_fetch_array($selit);
					$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=" . $it['res_id'] . ""));
					$col = (int)$_REQUEST['array_ress_col_'.$ress['id']];
					if ($col>0)
					{
						if ($col==$it['col'])
						{
							myquery("DELETE FROM craft_resource_user WHERE id=".$ar_it[$i]."");
						}
						else
						{
							myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-$col) WHERE id=".$ar_it[$i].""); 
						}
						myquery("UPDATE game_users SET CW=CW-".($col*$ress['weight'])." WHERE user_id=".$it['user_id']."");
						myquery("UPDATE game_users_archive SET CW=CW-".($col*$ress['weight'])." WHERE user_id=".$it['user_id']."");
						echo '<br>Ресурс <b>' . $ress['name'] . '</b> успешно удален';
						
						$da = getdate();
						$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
						 VALUES (
						 '".$char['name']."',
						 'Удалил ресурс: <b>" . $ress['name'] . "</b> в кол-ве ".$col." шт. у игрока ".$it['user_id']."',
						 '".time()."',
						 '".$da['mday']."',
						 '".$da['mon']."',
						 '".$da['year']."')")
							 or die(mysql_error());
					}
				}
			}
			echo '<br>Удаление закончено.';
		}
		elseif (isset($name_v)) //общий поиск
		{
			$sel = myquery("SELECT id FROM game_items_factsheet WHERE name='".$name_v."'");
			if ($sel!=false AND mysql_num_rows($sel)>0)
			{
				$item_id = mysql_result($sel,0,0);
				
				$select = myquery("(SELECT game_users.name,game_users.user_id,game_users.clan_id FROM game_items,game_users WHERE game_items.item_id=$item_id AND game_items.user_id!=0 AND game_items.priznak=0 AND game_items.user_id=game_users.user_id ORDER BY game_items.user_id) UNION (SELECT game_users_archive.name,game_users_archive.user_id,game_users_archive.clan_id FROM game_items,game_users_archive WHERE game_items.item_id=$item_id AND game_items.user_id!=0 AND game_items.priznak=0 AND game_items.user_id=game_users_archive.user_id ORDER BY game_items.user_id)");
				if ($select!=false AND mysql_num_rows($select))
				{
					echo '<br><hr><br><b><font size="3" color="#bbbbbb">Предметы у игроков</font></b><br><br>';
					echo '<table>';
					$nom = 0;
					while ($user = mysql_fetch_array($select))
					{
						$nom++;
						if ($nom == 6)
							$nom = 1;
						if ($nom == 1)
							echo '<tr>';
						echo '<td>';
						echo '<font size="2" color="#bbbbbb">';
						if ($user['clan_id'] != '0')
							echo '<img src="http://'.IMG_DOMAIN.'/clan/'.$user['clan_id'].'.gif"> ';
						echo '<a href="http://'.DOMAIN.'/view/?userid='.$user['user_id'].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>';
						echo ''.$user['name'].'</font><br>';
						echo '</td>';
						if ($nom == 5)
							echo '</tr>';
					}
					echo '</table>';
				}

				$select = myquery("SELECT map_name,map_xpos,map_ypos FROM game_items WHERE item_id='" .$item_id."' AND user_id=0 AND priznak=2 ORDER BY map_name");
				if (mysql_num_rows($select))
				{
					echo '<br><hr><br><b><font size="3" color="#bbbbbb">Предметы на земле</font></b><br><br>';
					echo '<table>';
					while ($user = mysql_fetch_array($select))
					{
						echo '<tr><td>';
						echo '<font size="2" color="#bbbbbb">';
						echo 'Лежит на карте: '.@mysql_result(@myquery("SELECT name FROM game_maps WHERE id=".$user['map_name'].""), 0, 0).' x-'.$user['map_xpos'].', y-'.$user['map_ypos'].'';
						echo '</font><br>';
						echo '</td></tr>';
					}
					echo '</table>';
				}

				$select_old_items = myquery("SELECT DISTINCT town FROM game_items WHERE item_id='".$item_id."' AND priznak=1");
				if (mysql_num_rows($select_old_items))
				{
					echo '<br><hr><br><b><font size="3" color="#bbbbbb">Предметы на рынке</font></b><br><br>';
					{
						echo '<table>';
						while (list($town) = mysql_fetch_array($select_old_items))
						{
							$kol_items = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE item_id='".$item_id."' AND priznak=1 AND town='".$town."'"),0,0);
							$select = myquery("SELECT rustown FROM game_gorod WHERE town='".$town."'");
							list($rustown) = mysql_fetch_array($select);
							if ($kol_items != 0)
							{
								echo '<tr><td>';
								echo '<font size="2" color="#bbbbbb">';
								echo ''.$kol_items.' '.pluralForm($kol_items,'предмет','предмета','предметов').' на рынке в городе: '.$rustown.'.<br>';
								echo '</font><br>';
								echo '</td></tr>';
							}
						}
						echo '</table>';
					}
				}
			}
		}
		else
		{
			echo '<br><hr><br><center><table cellpadding="10"><tr>
			
			<td>Показать предметы, лежащие на карте:<form name="smap" method="POST"><table>';
			$selmap = myquery("SELECT id,name FROM game_maps ORDER BY BINARY name");
			while ($map = mysql_fetch_array($selmap))
			{
				echo '<tr><td><input type="radio" name="map" value="'.$map['id'].'">'.$map['name'].'</td></tr>';
			}
			echo '</table><input type="submit" name="searchmap" value="Выполнить поиск"></form></td>
			
			<td valign="top">Показать все предметы, находящиеся у игрока:<form name="suser" method="POST"><input type="text" size="50" name="user"><br><br><input type="submit" name="searchuser" value="Выполнить поиск"></form></td></tr></table>';
		}
	}
}
if (function_exists("save_debug")) save_debug(); 
?>