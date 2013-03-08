<?php
//ob_start('ob_gzhandler',9);
$dirclass = "../class";
include('../inc/engine.inc.php');
include('../inc/xray.inc.php');
include('../inc/lib.inc.php');
DbConnect();


if (function_exists("start_debug")) start_debug(); 

if (isset($_GET['type'])) { $_GET['type'] = (int)$_GET['type']; };
if (isset($_GET['nv'])) { $_GET['nv'] = (int)$_GET['nv']; };  
if (isset($_GET['race'])) { $_GET['race'] = (int)$_GET['race']; };  
if (isset($_GET['item'])) { $_GET['item'] = (int)$_GET['item']; };  

echo'
<html>
<head>
<style type="text/css">
@import url("info.css");.style2 {color: #CCCCCC}
.style3 {
		color: #FF0000;
		font-weight: bold;
}
.sel_item {
	max-width: 186px;
	background-color: #FFFFC0;
	color: black;
}
</style>';
?>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="Keywords" content="">
<title>Энциклопедия предметов</title>
<script type="text/javascript" language="JavaScript" src="/js/info.js"></script>
<script type="text/javascript" language="JavaScript">
function mouse_over(id)
{
	el = document.getElementById(id);
	if (el)
	{
		el.style.border="1px groove #0000FF";
		//el.style.padding="2px 2px";
	}
}
function mouse_out(id)
{
	el = document.getElementById(id);
	if (el)
	{
		el.style.border="";
		//el.style.padding="0px 0px";
	}
}
</script>
</head>
<body>
<DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>
<div align="center">
<table width="536"  border="0" cellspacing="0" cellpadding="0">
  <tr>
	<th width="55" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_03.jpg" width="55" height="28"></th>
	<th width="174" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_04.jpg" width="174" height="28"></th>
	<th width="18" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_05.jpg" width="18" height="28"></th>
	<th width="238" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_06.jpg" width="238" height="28"></th>
	<th width="15" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_07.jpg" width="17" height="28"></th>
	<th width="36" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_08.jpg" width="34" height="28"></th>
  </tr>
  <tr>
	<th scope="col" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_09.jpg"></th>
	<th align="left" valign="top" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_01.jpg" scope="col">
		<?php
		if (!isset($_GET['nv']))
		{
		?>
				<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
				  <td colspan="2">&nbsp;</td>
				</tr>
				<tr>
				  <td width="48%" scope="col">
					<div align="left"><a href="?type=1">Оружие</a></div>
				  </td>
				  <td width="52%" scope="col">
					<div align="right"><a href="?type=5">Доспехи</a></div>
				  </td>
				</tr>
				<tr>
				  <td scope="col"><div align="left"><a href="?type=4">Щиты</a></div></td>
				  <td scope="col"><div align="right"><a href="?type=2">Кольца</a></div></td>
				</tr>
				<tr>
				  <td scope="col"><div align="left"><a href="?type=7">Магия</a></div></td>
				  <td scope="col"><div align="right"><a href="?type=3">Артефакты</a></div></td>
				</tr>
				<tr>
				  <td scope="col"><div align="left"><a href="?type=8">Пояса</a></div></td>
				  <td scope="col"><div align="right"><a href="?type=6">Шлемы</a></div></td>
				</tr>
				<tr>
				  <td scope="col"><div align="left"><a href="?type=18">Луки</a></div></td>
				  <td scope="col"><div align="right"><a href="?type=21">Стрелы</a></div></td>
				</tr>
				<tr>
				  <td scope="col"><div align="left"><a href="?type=19">Метат.предм.</a></div></td>
				  <td scope="col"><div align="right"><a href="?type=20">Схемы</a></div></td>
				</tr>
                <tr>
                  <td scope="col"><div align="left"><a href="?type=10">Перчатки</a></div></td>
                  <td scope="col"><div align="right"><a href="?type=11">Обувь</a></div></td>
                </tr>
                <tr>
                  <td scope="col"><div align="left"><a href="?type=14">Поножи</a></div></td>
                  <td scope="col"><div align="right"><a href="?type=15">Наручи</a></div></td>
                </tr>
                <tr>
                  <td scope="col"><div align="left"><a href="?type=16">Украшения</a></div></td>
                  <td scope="col"><div align="right"><a href="?type=9">Ожерелья</a></div></td>
                </tr>
                <tr>
                  <td colspan="2"><div align="center"><a href="?type=24">Инструменты</a></div></td>
                </tr>
                <tr>
                  <td colspan="2"><div align="center"><a href="?type=23">Комплекты предметов</a></div></td>
                </tr>
			  </table>
		<?
		}
		else
		{
		?>
			<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
				  <td width="48%" scope="col">
					<div align="left"><a href="?nv=0">Воин</a></div>
				  </td>
				  <td width="52%" scope="col">
					<div align="right"><a href="?nv=1">Бард</a></div>
				  </td>
				</tr>
				<tr>
				  <td scope="col"><div align="left"><a href="?nv=2">Волшебник</a></div></td>
				  <td scope="col"><div align="right"><a href="?nv=3">Лучник</a></div></td>
				</tr>
				<tr>
				  <td scope="col"><div align="left"><a href="?nv=4">Паладин</a></div></td>
				  <td scope="col"><div align="right"><a href="?nv=5">Варвар</a></div></td>
				</tr>
				<tr>
				  <td scope="col"><div align="left"><a href="?nv=6">Вор</a></div></td>
				  <td scope="col"><div align="right"><a href="?nv=7">Друид</a></div></td>
				</tr>
						  <tr>
				  <td scope="col"><div align="left"><a href="?nv=8">Разбойник</a></div></td>
				  <td scope="col"></td>
				</tr>
			  </table>
		<?
		}
		?>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <th scope="col">&nbsp;</th>
		</tr>
		<tr>
		  <th scope="col">
		  <?php
		  if (!isset($_GET['nv']))
		  {
			echo'<a href="?nv=-1">Энциклопедия навыков</a>';
		  }
		  else
		  {
			echo'<a href="?type=1">Энциклопедия предметов</a>';
		  }
		  ?>
		  </th>
		</tr>
		<tr>
		  <th scope="col">
		  <?php
			echo'<a href="/images/info_rpgsu.zip">Offline версия</a>  <a href="/view" target="_blank">&copy; X-ray</a>';
		  ?>
		  </th>
		</tr>
	  </table></th>
	<th scope="col" style="width:18px;height:100%;background-image:url('<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_11.jpg')">&nbsp;</th>
	<th valign="bottom" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_12.jpg" scope="col">
	<div style="color:white;font-size:15px;">Поиск предметов для расы: <br><?php 
	$sel = myquery("SELECT * FROM game_har WHERE disable=0 ORDER BY name");
	echo '<select id="race" class="sel_item">';
	while ($ra = mysql_fetch_array($sel))
	{
		echo '<option value="'.$ra['id'].'"';
		if (isset($_GET['race']) AND $_GET['race']==$ra['id']) echo ' selected';
		echo '>'.$ra['name'].'</option>';
	}
	echo '</select>';
	?><input type="button" value="Найти" onclick="top.location.replace('?race='+document.getElementById('race').value)"></div><br><br>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td scope="col">
		  <div align="right" style="max-width:230px;">
<?php
if (isset($_GET['type']))
{
	echo'<select id="item" class="sel_item">';
	$encik=myquery("select id,name,oclevel from game_items_factsheet where type=".$_GET['type']." AND view<>'2' order by oclevel");
	while ($row = mysql_fetch_array($encik))
	{
		$nm=$row['name'];
		echo'<option value="'.$row['id'].'"';
		if (isset($item) and $item == $row['id']) echo ' selected';
		echo ' title="'.$row['name'].'">['.$row["oclevel"].'] '.$nm.' </option>';
	}
	echo'</select> <input type="button" value="ок" onClick="location.href=\'?type='.$type.'&amp;item=\'+document.getElementById(\'item\').value">';
}
elseif (isset($_GET['race']))
{
	$race = $_GET['race'];
	echo'<select id="item" class="sel_item">';
	$encik=myquery("select id,name,oclevel from game_items_factsheet where race='".$race."' AND view<>'2' order by oclevel");
	while ($row = mysql_fetch_array($encik))
	{
		$nm=$row['name'];
		echo'<option value="'.$row['id'].'" title="'.$row['name'].'">['.$row["oclevel"].'] '.$nm.'</option>';
	}
	echo'</select> <input type="button" value="ок" onClick="location.href=\'?&amp;item=\'+document.getElementById(\'item\').value">';
}
if(!isset($_GET['type']) and !isset($_GET['item']) and !isset($_GET['nv']) and !isset($_GET['race']))
{
	echo'<b>Последние добавленные предметы:</b>';
}
?>
		  </div></td>
	  </tr>
	  <tr>
		<th scope="col">&nbsp;</th>
	  </tr>
	</table></th>
	<th scope="col" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_02.jpg"></th>
	<th scope="col" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_14.jpg"></th>
  </tr>
  <tr>
	<th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_17.jpg" width="55" height="12"></th>
	<th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_18.jpg" width="174" height="12"></th>
	<th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_19.jpg" width="18" height="12"></th>
	<th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_20.jpg" width="238" height="12"></th>
	<th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_56.jpg" width="17" height="12"></th>
	<th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_21.jpg" width="34" height="12"></th>
  </tr>
  <tr>
	<th colspan="6" scope="col"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <th width="9%" height="18" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_22.jpg" width="55" height="18"></th>
		  <th colspan="3" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_23.jpg" scope="col"></th>
		  <th width="9%" height="18" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_24.jpg" width="51" height="18"></th>
		</tr>
		<tr>
		  <th valign="top" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_30.jpg" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_25.jpg" width="55" height="66"></th>
		  <th width="201" valign="top" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_31.jpg" scope="col" align="left">
<?php
if (isset($_GET['item']))
{
	$obzor=myquery("select * from game_items_factsheet where id='".$_GET['item']."' AND view<>'2'");
	while ($row=mysql_fetch_array($obzor))
	{
		echo'
		<table cellpadding="2" cellspacing="2" border="0" width="100%">
		<tr><td valign=top><center>';
		if ($row['view']==1)
		{
			$kol_items = @mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE item_id='".$row["id"]."' AND user_id!=0 AND priznak=0 AND user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1) AND user_id NOT IN (SELECT user_id FROM game_users_archive WHERE clan_id=1)"),0,0);
			$select = myquery("SELECT DISTINCT user_id FROM game_items WHERE item_id='".$row["id"]."' AND user_id!=0 AND priznak=0 AND user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1) AND user_id NOT IN (SELECT user_id FROM game_users_archive WHERE clan_id=1)");
			$kol_users = mysql_num_rows($select);
			$kol_items_house = @mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE item_id='".$row["id"]."' AND user_id!=0 AND priznak=4 AND user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1) AND user_id NOT IN (SELECT user_id FROM game_users_archive WHERE clan_id=1)"),0,0);
			$select = myquery("SELECT DISTINCT user_id FROM game_items WHERE item_id='".$row["id"]."' AND user_id!=0 AND priznak=4 AND user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1) AND user_id NOT IN (SELECT user_id FROM game_users_archive WHERE clan_id=1)");
			$kol_users_house = mysql_num_rows($select);
			$kol_old_items = @mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE priznak=1 AND item_id='".$row["id"]."' AND user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1) AND user_id NOT IN (SELECT user_id FROM game_users_archive WHERE clan_id=1)"),0,0);
			?><span onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b>Где купить?: </b></font>','<?php
			echo '<font color=000000>';
			if ($row['imgbig']!='')
			{
				$ext = substr($row['imgbig'],strlen($row['imgbig'])-4);
				if ($ext=='.gif' OR $ext=='.jpg' OR $ext=='jpeg')
				{
					echo'<img src=http://'.IMG_DOMAIN.'/item/'.$row['imgbig'].'><br>';
				}
				else
				{
					echo'<img src=http://'.IMG_DOMAIN.'/item/'.$row['imgbig'].'.gif><br>';
				}
			}
			if ($kol_items!=0) echo ''.$kol_items.' '.pluralForm($kol_items, "предмет", "предмета", "предметов").' у '.$kol_users.' '.pluralForm($kol_users, "игрока", "игроков", "игроков").'.<br><hr>';
			if ($kol_items_house!=0) echo ''.$kol_items_house.' '.pluralForm($kol_items_house, "предмет", "предмета", "предметов").' в домах у '.$kol_users_house.' '.pluralForm($kol_users_house, "игрока", "игроков", "игроков").'.<br><hr>';
			if ($kol_old_items!=0) echo ''.$kol_old_items.' '.pluralForm($kol_old_items, "предмет", "предмета", "предметов").' на рынках в городах.<br><hr>';
			$i=0;
			$sel = myquery("SELECT shop_id FROM game_shop_items WHERE items_id='".$_GET['item']."'");
			while (list($shop_id) = mysql_fetch_array($sel))
			{
				switch ($row['type'])
				{
					case 1: $type_shop = 'oruj'; break;
					case 2: $type_shop = 'ring'; break;
					case 3: $type_shop = 'artef'; break;
					case 4: $type_shop = 'shit'; break;
					case 5: $type_shop = 'dosp'; break;
					case 6: $type_shop = 'shlem'; break;
					case 7: $type_shop = 'mag'; break;
					case 8: $type_shop = 'pojas'; break;
					case 9: $type_shop = 'amulet'; break;
					case 10: $type_shop = 'perchatki'; break;
					case 11: $type_shop = 'boots'; break;
					case 13: $type_shop = 'eliksir'; break;
					case 12: $type_shop = 'svitki'; break;
					case 16: $type_shop = 'ukrash'; break;
					case 18: $type_shop = 'luk'; break;
					case 20: $type_shop = 'schema'; break;
					case 24: $type_shop = 'instrument'; break;
					default: $type_shop = ''; break;
				}
				if ($type_shop != '')
				{
					$select = myquery("SELECT name,map,pos_x,pos_y FROM game_shop WHERE id='$shop_id' AND `$type_shop`='1' AND view='1'");
					if (mysql_num_rows($select))
					{
						$torg=mysql_fetch_array($select);
						echo 'Продается в магазине: '.$torg['name'].' ('.mysql_result(myquery("SELECT name FROM game_maps WHERE id=".$torg['map'].""),0,0).' x-'.$torg['pos_x'].', y-'.$torg['pos_y'].')<br>';
					}
				}
			}
			echo '</font>';?>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?
			echo '<img src="http://'.IMG_DOMAIN.'/item/'.$row["img"].'.gif" border="0" align=left>';
			?></span><?
		}
		else
		{
			if ($row['imgbig']!='')
			{
				?><span  onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b></b></font>','<?
				echo '<font color=000000>';
				$ext = substr($row['imgbig'],strlen($row['imgbig'])-4,4);
				if ($ext=='.gif' OR $ext=='.jpg' OR $ext=='jpeg')
				{
					echo'<img src=http://'.IMG_DOMAIN.'/item/'.$row['imgbig'].'><br>';
				}
				else
				{
					echo'<img src=http://'.IMG_DOMAIN.'/item/'.$row['imgbig'].'.gif><br>';
				}
				echo '</font>';?>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?
				echo '<img src="http://'.IMG_DOMAIN.'/item/'.$row["img"].'.gif" border="0" align=left>';
				?></span><?
			}
			else
			{
				echo '<img src="http://'.IMG_DOMAIN.'/item/'.$row["img"].'.gif" border="0" align=left>';
			}
		}
		echo'<b>'.$row["name"].'</b><br>Средняя цена: '.$row["item_cost"].'<br>Вес: '.$row["weight"].'<br>';
		if ($row["race"]!=0) echo'<br>Только для расы: <b>'.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$row['race'].""),0,0).'</b><br>';
		if ($row["redkost"]!='') echo'<br>Редкость: <b>'.$row["redkost"].'</b><br>';
		if ($row["curse"]!='') echo'<br>Описание: '.$row["curse"].'<br>';
		if ($row['view']==1)
		{
			$check_shop=myquery("Select * From game_shop_items Where items_id=".$row['id']."");
			if (mysql_num_rows($check_shop)==0)
			{
				echo'<b><br>В продаже отсутствует.</b><br>';
			}
			$check_schema=myquery("Select id From game_items_factsheet Where indx=".$row['id']." and type=20");
			if (mysql_num_rows($check_schema)>0)
			{
				list($schema)=mysql_fetch_array($check_schema);
				echo'Изготавливается по <font color="red"><b><a href="?type=20&item='.$schema.'">схеме</a></font></b>.<br>';
			}
			if ($row["type"]==1 AND $row['in_two_hands']==1)
			{
				echo'<br>Двуручное<br>';
			}
			if ($row["type"]==1 OR $row["type"]==19 OR $row['type']==21)
			{
				echo'<br>Атака: '.$row["indx"].' - '.$row["deviation"].'<br>';
			}
			if ($row["type"]==3)
			{
				echo'<br>Кол-во зарядов: '.$row["item_uselife"].'<br>';
			}
			if ($row["type"]==4)
			{
				echo'<br>Защита: '.$row["indx"].'<br>';
			}
			if ($row["hp_p"]!=0) echo'<br>Повышает жизни на: <b>'.$row["hp_p"].'</b><br>';
			if ($row["mp_p"]!=0) echo'<br>Повышает ману на: <b>'.$row["mp_p"].'</b><br>';
			if ($row["stm_p"]!=0) echo'<br>Повышает энергию на: <b>'.$row["stm_p"].'</b><br>';
			if ($row["cc_p"]!=0) echo'<br>Повышает перенос предметов на: <b>'.$row["cc_p"].'</b><br>';
			if ($row["type"]==1)
			{
				switch ($row["type_weapon"])
				{
					case 0:{echo 'Класс оружия: Без класса<br />';}break;
					case 1:{echo 'Класс оружия: Кулачное<br />';}break;
					case 2:{echo 'Класс оружия: Стрелковое<br />';}break;
					case 3:{echo 'Класс оружия: Рубящее<br />';}break;
					case 4:{echo 'Класс оружия: Дробящее<br />';}break;
					case 5:{echo 'Класс оружия: Колющее<br />';}break;
					case 6:{echo 'Класс оружия: Метательное<br />';}break;
				}
			}
			if ($row["type"]==2 OR $row["type"]==5 OR $row["type"]==6 OR $row["type"]==8)
			{
				switch ($row["def_type"])
				{
					case 0:{echo 'Вид доспеха: одежда<br />';}break;
					case 1:{echo 'Вид доспеха: кожанный<br />';}break;
					case 2:{echo 'Вид доспеха: кольчужный (плетеный)<br />';}break;
					case 3:{echo 'Вид доспеха: латы (пластинчатый)<br />';}break;
				}
			}
			if ($row["type"]==20)
			{
				echo 'Схема для изготовления предмета: <b><font color=red><a href="?type='.mysqlresult(myquery("SELECT type FROM game_items_factsheet WHERE id=".$row['indx'].""),0,0).'&item='.$row['indx'].'">'.mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$row['indx'].""),0,0).'</a></font></b>';
				$response = "<br /><br />Для изготовления по схеме надо:<br />
				<table>
				<tr><td><b>Ресурс</b></td><td><b>Количество</b></td></tr>";
				$selschema = myquery("SELECT * FROM game_items_schema WHERE item_id=".$_GET['item']."");
				while ($schema = mysql_fetch_array($selschema))
				{
					$response.="<tr><td>".mysql_result(myquery("SELECT name FROM craft_resource WHERE id=".$schema['res_id'].""),0,0)."</td><td align=right>".$schema['col']."</td></tr>";
				}
				$response.="</table>";
				echo $response;
				if ($row['oclevel']==1)
				{
					echo '<br />Время работы: 120 минут<br />';
				}
				if ($row['oclevel']==2)
				{
					echo '<br />Время работы: 180 минут<br />';
				}
				if ($row['oclevel']==3)
				{
					echo '<br />Время работы: 240 минут<br />';
				}
				if ($row['oclevel']==4)
				{
					echo '<br />Время работы: 300 минут<br />';
				}
				if ($row['oclevel']==5)
				{
					echo '<br />Время работы: 420 минут<br />';
				}
			}
			if ($row["type"]==21)
			{
				echo 'Используется с предметом: <b><font color=red>'.mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$row['quantity'].""),0,0).'</font></b>';
			}
			if ($row['breakdown']==1)
			{
				echo '<br />Долговечность предмета: '.$row['item_uselife_max'];
			}
		}
		if ($row['personal']==1)
		{
			echo'<br><b><u>ЛИЧНЫЙ предмет без права передачи</u></b><br>';
		}
		if ($row['can_up']==0)
		{
			echo'<br><b>Предмет нельзя одеть</b><br>';
		}
		if ($row['clan_id']>0)
		{
			$nazv = mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$row['clan_id'].""),0,0);
			echo'<br>Только для клана: <b>'.$nazv.'</b><br>';
		}
		echo'</td></tr>';
		echo'</table>';
	}
}
if (isset($_GET['nv']))
{
	if ($_GET['nv']>=0)
	{
		echo '<table width="201"><tr width="201"><td width="71" height="60" valign="center"><b><u><i>Навык</td><td height="60" width="130" valign="center"><b><u><i>Название</td></tr>';
		$select_spet = myquery("SELECT * FROM game_spets WHERE tip='".$_GET['nv']."'");
		while ($spet = mysql_fetch_array($select_spet))
		{
			echo '<tr width="201"><td height="45" valign="center" width="71">'.$spet['name'].'</td><td width="130" height="45" valign="center"><b>'.$spet['mode'].'</td></tr>';
		}
		echo '</table>';
	}
}
if(!isset($_GET['item']) and !isset($_GET['type']) and !isset($_GET['nv']) and !isset($_GET['race']))
{
	$obzor=myquery("select * from game_items_factsheet where view<>'2' order by id DESC limit 5");
	while ($row= mysql_fetch_array($obzor))
	{
		echo'<table cellpadding="2" cellspacing="2" border="0" width="100%">
		<tr><td valign=top><center><a href="?&type='.$row["type"].'&item='.$row["id"].'"><img src="http://'.IMG_DOMAIN.'/item/'.$row["img"].'.gif" width="50" height="50 border="0" align=left></a><b>'.$row["name"].'</b><br>Вес: '.$row["weight"].'<br>';
		if ($row["type"]==1 OR $row["type"]==19 OR $row["type"]==21) echo'Атака: '.$row["indx"].' - '.$row["deviation"].'<br>';
		if ($row["type"]==3) echo'Кол-во зарядов: '.$row["item_uselife"].'<br>';
		if ($row["type"]==4) echo'Защита: '.$row["indx"].'<br>';
		if ($row["race"]!=0) echo'Для расы: <b>'.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$row['race'].""),0,0).'</b>';
		echo'</td></tr>';
		echo'</table>';
	}
}
if(isset($_GET['type']) and !isset($_GET['item']))
{
	$kol=mysql_result(myquery("select count(*) from game_items_factsheet where type=".$_GET['type']." AND view<>'2'"),0,0);
	$n=floor($kol/2);
	$obzor=myquery("select * from game_items_factsheet where type=".$_GET['type']." AND view<>'2' order by oclevel ASC limit 0,$n");
	while ($row = mysql_fetch_array($obzor))
	{
		echo'<div id="item'.$row["id"].'" onMouseOver="mouse_over(\'item'.$row["id"].'\')" onMouseOut="mouse_out(\'item'.$row["id"].'\')"><a href="?type='.$row["type"].'&item='.$row["id"].'">'.$row["name"].'&nbsp;['.$row["oclevel"].']</a></div>';
	}
}
if(isset($_GET['race']) and !isset($_GET['item']))
{
	$race = $_GET['race'];
	$kol=mysql_result(myquery("select count(*) from game_items_factsheet where race=".$race." AND view<>'2'"),0,0);
	$n=floor($kol/2);
	$obzor=myquery("select * from game_items_factsheet where race=".$race." AND view<>'2' order by oclevel ASC limit 0,$n");
	while ($row = mysql_fetch_array($obzor))
	{
		echo'<a href="?type='.$row["type"].'&item='.$row["id"].'">'.$row["name"].'&nbsp;['.$row["oclevel"].']</a><br>';
	}
}
?>
</th>
<th width="3%" height="27" valign="top" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_32.jpg" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_27.jpg" width="27" height="66"></th>
<td width="42%" valign="top" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_33.jpg" scope="col">
<?
if (isset($_GET['item']))
{
	$obzor=myquery("select * from game_items_factsheet where id=".$_GET['item']." and view='1'");
	while ($row= mysql_fetch_array($obzor))
	{
		if ($row['type']==20)
		{
			if ($row['oclevel']==1)
			{
				$row['oclevel']=8;
			}
			if ($row['oclevel']==2)
			{
				$row['oclevel']=12;
			}
			if ($row['oclevel']==3)
			{
				$row['oclevel']=16;
			}
			if ($row['oclevel']==4)
			{
				$row['oclevel']=20;
			}
			if ($row['oclevel']==5)
			{
				$row['oclevel']=24;
			}
		}
		echo'<font face=verdana size=2>';
        if ($row['type']!=23)
        {
            echo '<b>Требует:</b> <br>Уровень: '.$row["oclevel"].'<br>Сила: '.$row["ostr"].' <br>Интеллект: '.$row["ontl"].'<br>Ловкость: '.$row["opie"].'<br>Защита: '.$row["ovit"].'<br>Выносливость: '.$row["odex"].'<br>Мудрость: '.$row["ospd"].'<br>Удачу: '.$row["olucky"].'';
		    if ($row['type']==20)	
		    {
			    if ($row['oclevel']==8)
			    {
				    echo '<br />Уровень "оружейника": 0';
			    }
			    if ($row['oclevel']==12)
			    {
				    echo '<br />Уровень "оружейника": 55';
			    }
			    if ($row['oclevel']==16)
			    {
				    echo '<br />Уровень "оружейника": 85';
			    }
			    if ($row['oclevel']==20)
			    {
				    echo '<br />Уровень "оружейника": 115';
			    }
			    if ($row['oclevel']==24)
			    {
				    echo '<br />Уровень "оружейника": 145';
			    }
		    }
		    if (($row['type']==1 OR $row['type']==18 OR $row['type']==19) AND ($row['type_weapon_need']))
		    {
			    switch ($row['type_weapon'])
			    {
				    case 0:{echo '<br>Эксперт Воинских Умений: '.$row["type_weapon_need"].'';};break;
				    case 1:{echo '<br>Мастер Кулачного Боя: '.$row["type_weapon_need"].'';};break;
				    case 2:{echo '<br>Мастер Стрелкового Оружия: '.$row["type_weapon_need"].'';};break;
				    case 3:{echo '<br>Мастер Рубящего Оружия: '.$row["type_weapon_need"].'';};break;
				    case 4:{echo '<br>Мастер Дробящего Оружия: '.$row["type_weapon_need"].'';};break;
				    case 5:{echo '<br>Мастер Колющего Оружия: '.$row["type_weapon_need"].'';};break;
				    case 6:{echo '<br>Мастер Метательного Оружия: '.$row["type_weapon_need"].'';};break;
			    }
		    }
        }
        else
        {
            echo '<b>Требует предметы:</b> <br>';
            $sel = myquery("SELECT game_items_factsheet.id,game_items_factsheet.name,game_items_factsheet.type FROM game_items_complect,game_items_factsheet WHERE game_items_factsheet.id=game_items_complect.item_id AND game_items_complect.complect_id=".$row['id']."");
            while ($compl = mysql_fetch_array($sel))
            {
                echo type_str($compl['type']).' <a href="?type='.$compl['type'].'&item='.$compl['id'].'">'.$compl['name'].'</a><br />';
            }
        }
		echo '<br><br><br>

		<b>Повышает:</b> <br>Силу на: '.$row["dstr"].' <br>Интеллект на: '.$row["dntl"].'<br>Ловкость на: '.$row["dpie"].'<br>Защиту на: '.$row["dvit"].'<br>Выносливость на: '.$row["ddex"].'<br>Мудрость на: '.$row["dspd"].'<br>Удачу на: '.$row["dlucky"].'';
		if (($row['type']==2 OR $row['type']==5 OR $row['type']==6 OR $row['type']==8) AND $row['def_index']>0)
		{
			echo '<br><br />Защищает область <br />';
			if ($row['type']==2) echo 'плеча';
			if ($row['type']==5) echo 'тела и ног';
			if ($row['type']==6) echo 'головы';
			if ($row['type']==8) echo 'паха';
			echo ' на: '.$row["def_index"].'';
		}
		if ($row['magic_def_index']>0)
		{
			echo '<br><br />Защищает от магических<br />атак';
			echo ' на: '.$row["def_index"].'';
		}
		echo'<br></font>';
	}
}

if(!isset($_GET['item']) and !isset($_GET['type']) and !isset($_GET['nv']) and !isset($_GET['race']))
{
	$obzor=myquery("select * from game_items_factsheet where view<>'2' order by id DESC limit 5,5");
	while ($row= mysql_fetch_array($obzor))
	{
		echo'<table cellpadding="2" cellspacing="2" border="0" width="100%">
		<tr><td valign=top><center><a href="?&type='.$row['type'].'&item='.$row["id"].'"><img src="http://'.IMG_DOMAIN.'/item/'.$row["img"].'.gif" width="50" height="50 border="0" align=left></a><b>'.$row["name"].'</b><br>Вес: '.$row["weight"].'<br>';
		if ($row["type"]==1 OR $row["type"]==19 OR $row["type"]==21) echo'Атака: '.$row["indx"].' - '.$row["deviation"].'<br>';
		if ($row["type"]==3) echo'Кол-во зарядов: '.$row["item_uselife"].'<br>';
		if ($row["type"]==4) echo'Защита: '.$row["indx"].'<br>';
		if ($row["race"]!=0) echo'Для расы: <b>'.mysqlresult(myquery("SELECT name FROM game_har WHERE id=".$row['race'].""),0,0).'</b>';
		echo'</td></tr>';
		echo'</table>';
	}
}

if(isset($_GET['type']) and !isset($_GET['item']))
{
	$type = (int)$_GET['type'];
	$kol=mysql_result(myquery("select count(*) from game_items_factsheet where type=$type AND view<>'2'"),0,0);
	$n=floor($kol/2);
	$obzor=myquery("select * from game_items_factsheet where type=$type AND view<>'2' order by oclevel ASC limit $n, $kol");
	while ($row = mysql_fetch_array($obzor))
	{
		echo'<div id="item'.$row["id"].'" onMouseOver="mouse_over(\'item'.$row["id"].'\')" onMouseOut="mouse_out(\'item'.$row["id"].'\')"><a href="?type='.$row["type"].'&item='.$row["id"].'">'.$row["name"].'&nbsp;['.$row["oclevel"].']</a></div>';
	}
}

if(isset($_GET['race']) and !isset($_GET['item']))
{
	$race = $_GET['race'];
	$kol=mysql_result(myquery("select count(*) from game_items_factsheet where race=".$race." AND view<>'2'"),0,0);
	$n=floor($kol/2);
	echo'<center>';
	$obzor=myquery("select * from game_items_factsheet where race=$race AND race<>'' AND view<>'2' order by oclevel ASC limit $n, $kol");
	while ($row = mysql_fetch_array($obzor))
	{
		echo'<a href="?type='.$row["type"].'&item='.$row["id"].'">'.$row["name"].'&nbsp;['.$row["oclevel"].']</a><br>';
	}
	echo'</center>';
}

if (isset($_GET['nv']))
{
	if ($_GET['nv']>=0)
	{
		echo '<center><table width="100%"><tr><td valign="center" width="33%" height="60"><b><u><i>Вид</td><td valign="center" align="right" height="60"><b><u><i>Урон жизни/<br>маны/<br>энергии</td><td valign="center" align="right" height="60"><b><u><i>Расход маны/<br>жизни/<br>энергии</td></tr>';
		$select_spet = myquery("SELECT * FROM game_spets WHERE tip='".$_GET['nv']."'");
		while ($spet = mysql_fetch_array($select_spet))
		{
			echo '<tr><td valign="center" width="33%" height="45"><b>'.$spet['img'].'</td><td valign="center" align="right" height="45"><font color=#800000>'.$spet['indx'].'&plusmn;'.$spet['deviation'].'</font>/<br><font color=#0000FF>'.$spet['indx_mp'].'&plusmn;'.$spet['indx_mp_deviation'].'</font>/<br><font color=#008000>'.$spet['indx_stm'].'&plusmn;'.$spet['indx_stm_deviation'].'</font></td><td valign="center" align="right" height="45"><font color=#800000>'.$spet['mana'].'&plusmn;'.$spet['mana_deviation'].'</font>/<br><font color=#0000FF>'.$spet['hp'].'&plusmn;'.$spet['hp_deviation'].'</font>/<br><font color=#008000>'.$spet['stm'].'&plusmn;'.$spet['stm_deviation'].'</font></td></tr>';
		}
		echo '</table></center>';
	}
}
?>
</td>
<th valign="top" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_34.jpg" scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_29.jpg" width="51" height="66"></th>
</tr>
<tr>
  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_40.jpg" width="55" height="52"></th>
  <td align="left" valign="bottom" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_41.jpg" scope="col"></td>
  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_32.jpg" width="27" height="52"></th>
  <td align="right" valign="bottom" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_43.jpg" scope="col"></td>
  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_44.jpg" width="51" height="52"></th>
</tr>
<tr>
  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_45.jpg" width="55" height="22"></th>
  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_46.jpg" width="201" height="22"></th>
  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_47.jpg" width="27" height="22"></th>
  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_48.jpg" width="202" height="22"></th>
  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_49.jpg" width="51" height="22"></th>
</tr>
<tr>
  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_50.jpg" width="55" height="37"></th>
  <td colspan="3" valign="top" background="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_53.jpg" scope="col"><div align="center"><font face="Verdana" size="2">
	<?
	$kol=myquery("select count(*) from game_items_factsheet where view<>'2'");
	$num=mysql_result($kol,0,0);
	?>
Всего предметов в базе: <span class="style3"><? echo $num; ?></span></font></div></td>
		  <th scope="col"><img src="<? echo 'http://'.IMG_DOMAIN.'/info';?>/info_55.jpg" width="51" height="37"></th>
		</tr>
	  </table></th>
  </tr>
  </table>

<span class="style2"><font face="Verdana" size="2">X-ray&#8482; (c) 2004-2008</font></span><br>Администрация игры оставляет за собой право в любой момент изменить предметы без уведомления игроков<br />
<?php
include("../lib/banners.php");
?>
</div></body></html>
<?php
mysql_close();
if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}
if (function_exists("save_debug")) save_debug(); 
?>