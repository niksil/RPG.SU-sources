<?php
//ob_start('ob_gzhandler',9);
$dirclass="../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();
require('../inc/lib_session.inc.php');


if (function_exists("start_debug")) start_debug(); 

$result=myquery("SELECT * FROM game_admins WHERE user_id=".$user_id." LIMIT 1");
$adm=mysql_fetch_array($result);
if ($adm['map'] != 1)
{
	header('Location: index.php');
	{if (function_exists("save_debug")) save_debug(); exit;}
}
else
{

require('../inc/template_header.inc.php');

$img='http://'.IMG_DOMAIN.'/race_table/human/table';
echo'<style type="text/css">@import url("../style/global.css");</style><table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';


echo '<b><font color=ff0000 size=2 face=verdana>'.GAME_NAME.' :: Редактор карт</font></b><br>';

if (!isset($option)) $option = '';

switch ($option)
{
	case 'delete_map_now':
		echo '<font color="#eeeeee"><br>Удаление Карты</font><br>';
		$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id=$map_selected"),0,0);
		$result = myquery("DELETE FROM game_map WHERE name='$map_selected'") or die('Database Error: ' . mysql_error() . '<br>');
		$result = myquery("DELETE FROM game_maps WHERE id='$map_selected'") or die('Database Error: ' . mysql_error() . '<br>');
		echo 'Карта "<font color="#eeeeee">' . $map_name . '</font>" удалена.<br>';
		$result = myquery("OPTIMIZE TABLE game_map") or die('Database Error: ' . mysql_error() . '<br>');
		echo 'Все карты оптимизированы<br>';
		break;

	case 'delete_map':
		echo '<font color="#eeeeee"><br>Удаление Карты</font><br>';
		$result = myquery("SELECT * FROM game_maps WHERE maze=0 ORDER BY name") or die('Database Error: ' . mysql_error() . '<br>');
		if (mysql_num_rows($result) != 0)
		{
			echo '
			<form method="post" action="'.PHP_SELF.'">
			<input type="hidden" name="option" value="delete_map_now">
			<table cellpadding="0" cellspacing="4" border="0">';
			while ($map = mysql_fetch_array($result))
			{
				echo '<tr><td><input type="radio" name="map_selected" value="' . $map['id'] . '"></td><td>' . $map['name'] . '</td></tr>';
			}
			echo '
			<tr><td colspan="2"><div align="right"><input type="submit" value="Удалить" class="inputbutton"></div></td></tr>
			</table>
			</form>';
		}
		else
		{
			echo 'Ошибка<br>';
		}
		break;

	case 'edit_sector_now':
		if(is_array($sector))
		{
			foreach ($sector as $x => $x_array)
			{
				foreach ($x_array as $y => $y_array)
				{
					$sel=myquery("SELECT type_id FROM game_map_type WHERE type_name='".$y_array['type']."'");
					if ($sel!=false AND mysql_num_rows($sel)>0)
					{
						list($typeid) = mysql_fetch_array($sel);
					}
					else
					{
						myquery("INSERT INTO game_map_type (type_name) VALUES ('".$y_array['type']."')");
						$typeid=mysql_insert_id();
					}
					$sql = "UPDATE game_map
							SET type = '$typeid'
							   ,subtype = '{$y_array['subtype']}'
							   ,move_ul = '{$y_array['ul']}'
							   ,move_dl = '{$y_array['dl']}'
							   ,move_up = '{$y_array['up']}'
							   ,move_dn = '{$y_array['dn']}'
							   ,move_ur = '{$y_array['ur']}'
							   ,move_dr = '{$y_array['dr']}'
							WHERE (name = '{$map_selected}' AND xpos = {$x} AND ypos = {$y})";
					myquery($sql) or die('Database Error: ' . mysql_error() . '<br>');
				}
			}
		}
		else
		{
			$sel=myquery("SELECT type_id FROM game_map_type WHERE type_name='".$type."'");
			if ($sel!=false AND mysql_num_rows($sel)>0)
			{
				list($typeid) = mysql_fetch_array($sel);
			}
			else
			{
				myquery("INSERT INTO game_map_type (type_name) VALUES ('".$type."')");
				$typeid=mysql_insert_id();
			}
			$result = myquery("UPDATE game_map SET move_up='$move_up', move_ur='$move_ur', move_dr='$move_dr', move_dn='$move_dn', move_dl='$move_dl', move_ul='$move_ul', type='$typeid', subtype='$subtype' WHERE (name='$map_selected' AND xpos=$xpos AND ypos=$ypos)") or die('Database Error: ' . mysql_error() . '<br>');
			$edit_sector_message = 'Гекса (' . $xpos . ', ' . $ypos . ')</b> изменена.<br>';
		}


	case 'edit_sector':
		function SectorEditImageString($x, $y, $direction, $info, $imageOnly = False)
		{
			$sector_edit_image_string = '<img src="http://'.IMG_DOMAIN.'/admin/sector_edit_';

			switch ($info)
			{
				case '9':
					if ($direction == 'up' || $direction == 'dn')
					{
						$sector_edit_image_string .= 'vert_no.png" width="7" height="13"';
					}
					else
					{
						$sector_edit_image_string .= 'horz_no.png" width="12" height="8"';
					}
					break;

				case '0':
					if ($direction == 'up' || $direction == 'dn')
					{
						$sector_edit_image_string .= $direction . '.png" width="7" height="13"';
					}
					else
					{
						$sector_edit_image_string .= $direction . '.png" width="12" height="8"';
					}
					break;

				default:
					if ($direction == 'up' || $direction == 'dn')
					{
						$sector_edit_image_string .= 'vert_' . $info . '.png" width="7" height="13"';
					}
					else
					{
						$sector_edit_image_string .= 'horz_' . $info . '.png" width="12" height="8"';
					}
					break;
			}

			$sector_edit_image_string .= ' border="0" alt="' . $info . '" name="sector_edit_' . $x . '_' . $y . '_' . $direction . '"';

			if (!$imageOnly)
			{
				$sector_edit_image_string .= ' onClick="cycleDirection(' . $x . ',' . $y . ',\'' . $direction . '\')"';
			}

			$sector_edit_image_string .= '>';

			return $sector_edit_image_string;
		}
		$map = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id=$map_selected"),0,0);
		echo '<font color="#eeeeee">' . $map . ' - Гекса (' . $xpos . ', ' . $ypos . ')</font><br><br>';

		if (!empty($edit_sector_message))
		{
			echo $edit_sector_message;
		}

		echo '
		<form method="post" action="' . PHP_SELF . '" name="sector_edit_form">
		<input type="hidden" name="option" value="edit_sector_now"/>
		<input type="hidden" name="map_selected" value="' . $map_selected .'"/>
		<input type="hidden" name="xpos" value="' . $xpos . '"/>
		<input type="hidden" name="ypos" value="' . $ypos . '"/>
		';

		$sql = "
			SELECT *
			FROM game_map
			WHERE name = '$map_selected'
			AND (
		";
		if($xpos%2)
		{
			$sql .= "
			  (xpos=".($xpos-2)." AND ypos BETWEEN ".($ypos-1)." AND ".($ypos+1).") OR
			  (xpos=".($xpos-1)." AND ypos BETWEEN ".($ypos-2)." AND ".($ypos+1).") OR
			  (xpos=".($xpos)."   AND ypos BETWEEN ".($ypos-2)." AND ".($ypos+2).") OR
			  (xpos=".($xpos+1)." AND ypos BETWEEN ".($ypos-2)." AND ".($ypos+1).") OR
			  (xpos=".($xpos+2)." AND ypos BETWEEN ".($ypos-1)." AND ".($ypos+1).")
			";
		}
		else
		{
			$sql .= "
			  (xpos=".($xpos-2)." AND ypos BETWEEN ".($ypos-1)." AND ".($ypos+1).") OR
			  (xpos=".($xpos-1)." AND ypos BETWEEN ".($ypos-1)." AND ".($ypos+2).") OR
			  (xpos=".($xpos)."   AND ypos BETWEEN ".($ypos-2)." AND ".($ypos+2).") OR
			  (xpos=".($xpos+1)." AND ypos BETWEEN ".($ypos-1)." AND ".($ypos+2).") OR
			  (xpos=".($xpos+2)." AND ypos BETWEEN ".($ypos-1)." AND ".($ypos+1).")
			";
		}
		$sql .= "
			)
			LIMIT 19";
			$db_sectors = myquery($sql) or die('Database Error: ' . mysql_error() . '<br>');
			while($db_sector = mysql_fetch_assoc($db_sectors)) 
			{
				$typename = mysql_result(myquery("SELECT type_name FROM game_map_type WHERE type_id=".$db_sector['type'].""),0,0);
				$display_sectors[intval($db_sector['xpos'])][intval($db_sector['ypos'])] = array
				(
				'type' => $typename,
				'subtype' => $db_sector['subtype'],
				'ul' => $db_sector['move_ul'],
				'dl' => $db_sector['move_dl'],
				'up' => $db_sector['move_up'],
				'dn' => $db_sector['move_dn'],
				'ur' => $db_sector['move_ur'],
				'dr' => $db_sector['move_dr']
				);
				echo '
<input type="hidden" id="sector_' . $db_sector['xpos'] . '_' . $db_sector['ypos'] . '_type" name="sector[' . $db_sector['xpos'] . '][' . $db_sector['ypos'] . '][type]" value="' . $typename . '"/>
<input type="hidden" id="sector_' . $db_sector['xpos'] . '_' . $db_sector['ypos'] . '_subtype" name="sector[' . $db_sector['xpos'] . '][' . $db_sector['ypos'] . '][subtype]" value="' . $db_sector['subtype'] . '"/>
<input type="hidden" id="sector_' . $db_sector['xpos'] . '_' . $db_sector['ypos'] . '_ul" name="sector[' . $db_sector['xpos'] . '][' . $db_sector['ypos'] . '][ul]" value="' . $db_sector['move_ul'] . '"/>
<input type="hidden" id="sector_' . $db_sector['xpos'] . '_' . $db_sector['ypos'] . '_dl" name="sector[' . $db_sector['xpos'] . '][' . $db_sector['ypos'] . '][dl]" value="' . $db_sector['move_dl'] . '"/>
<input type="hidden" id="sector_' . $db_sector['xpos'] . '_' . $db_sector['ypos'] . '_up" name="sector[' . $db_sector['xpos'] . '][' . $db_sector['ypos'] . '][up]" value="' . $db_sector['move_up'] . '"/>
<input type="hidden" id="sector_' . $db_sector['xpos'] . '_' . $db_sector['ypos'] . '_dn" name="sector[' . $db_sector['xpos'] . '][' . $db_sector['ypos'] . '][dn]" value="' . $db_sector['move_dn'] . '"/>
<input type="hidden" id="sector_' . $db_sector['xpos'] . '_' . $db_sector['ypos'] . '_ur" name="sector[' . $db_sector['xpos'] . '][' . $db_sector['ypos'] . '][ur]" value="' . $db_sector['move_ur'] . '"/>
<input type="hidden" id="sector_' . $db_sector['xpos'] . '_' . $db_sector['ypos'] . '_dr" name="sector[' . $db_sector['xpos'] . '][' . $db_sector['ypos'] . '][dr]" value="' . $db_sector['move_dr'] . '"/>
			';
		}
		mysql_free_result($db_sectors);

		echo '
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
		';

		for ($x=-2;$x<=2;$x++)
		{
			$x_sector=$xpos+$x;
			echo '
	<td>
			';
#            if ($x_sector != $xpos)
			if ($x_sector%2 == 0)
			{
				echo '
	  <img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="56" height="29" border="0"><br>
				';
			}

#            foreach ($y_sector_array as $y_sector => $values_array)
			for($y=-2;$y<=2;$y++)
			{
				$y_sector=$ypos+$y;

				if($x_sector%2)
				{
					$y_delta = 0;
				}
				else
				{
					$y_delta = 1;
				}
				if(key_exists($x_sector, $display_sectors) && key_exists($y_sector, $display_sectors[$x_sector]))
				{
					echo '
			<table cellpadding="0" cellspacing="0" border="0">
			  <tr>
				<td>
				  <img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="12" height="14" border="0"><br>
				  ' . SectorEditImageString($x_sector, $y_sector, 'ul', $display_sectors[$x_sector][$y_sector]['ul']) . '<br>
				  <img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="12" height="14" border="0"><br>
				  ' . SectorEditImageString($x_sector, $y_sector, 'dl', $display_sectors[$x_sector][$y_sector]['dl']) . '<br>
				  <img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="12" height="14" border="0"><br>
				</td>
				<td>
				  <div align="center">
				  ' . SectorEditImageString($x_sector, $y_sector, 'up', $display_sectors[$x_sector][$y_sector]['up']) . '<br>
				  <img src="http://'.IMG_DOMAIN.'/map/' . $display_sectors[$x_sector][$y_sector]['type'] . $display_sectors[$x_sector][$y_sector]['subtype'] . '.jpg" width="32" height="32" border="0" alt="(' . ($x_sector) . ', ' . ($y_sector) . ')" name="sector_edit_' . ($x_sector) . '_' . ($y_sector) . '_tile"><br>
				  ' . SectorEditImageString($x_sector, $y_sector, 'dn', $display_sectors[$x_sector][$y_sector]['dn']) . '<br>
				  </div>
				</td>
				<td>
				  <img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="12" height="14" border="0"><br>
				  ' . SectorEditImageString($x_sector, $y_sector, 'ur', $display_sectors[$x_sector][$y_sector]['ur']) . '<br>
				  <img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="12" height="14" border="0"><br>
				  ' . SectorEditImageString($x_sector, $y_sector, 'dr', $display_sectors[$x_sector][$y_sector]['dr']) . '<br>
				  <img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="12" height="14" border="0"><br>
				</td>
			  </tr>
			</table>
					';
				}
				else
				{
					echo '
			<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="56" height="58" border="0">
					';
				}
			}

#            if($x_sector != $xpos)
			if($x_sector%2 != 0)
			{
				echo '
	  <img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="56" height="29" border="0"><br>
				';
			}
			echo '
	</td>
			';
		}

		echo '
	</td>
	<td width="400">

	  <div align="right">
	  <font size="2">Гекса (<span id="x_sector_display">' . $xpos . '</span>,<span id="y_sector_display">' . $ypos . '</span>):<br>
	  <a href="javascript:saveFocus()">Сохранить (<span id="x_sector_display">' . $xpos . '</span>,<span id="y_sector_display">' . $ypos . '</span>)</a><br>
	  <a href="javascript:cancelFocus();">Отменить (<span id="x_sector_display">' . $xpos . '</span>,<span id="y_sector_display">' . $ypos . '</span>)</a><br>
	  <a href="javascript:matchSectors();">Сбросить (<span id="x_sector_display">' . $xpos . '</span>,<span id="y_sector_display">' . $ypos . '</span>)</a><br>
	  </font>
	  </div>

	</td>
  </tr>
</table>

<script language="Javascript">
<!--
function focusOn(x, y)
{
	document.sector_edit_form.xpos.value = x;
	document.sector_edit_form.ypos.value = y;
	span_array = document.all.x_sector_display;
	for (i=0;i<span_array.length;i++)
	{
		span_array[i].innerText = x;
	}
	span_array = document.all.y_sector_display;
	for (i=0;i<span_array.length;i++)
	{
		span_array[i].innerText = y;
	}
}

function cycleDirection(x, y, direction)
{
	sector_input = eval("document.sector_edit_form.sector_" + x + "_" + y + "_" + direction);
	sector_image = eval("document.sector_edit_" + x + "_" + y + "_" + direction);

	if (direction == "up" || direction == "dn")
	{
		sector_image_direction = "vert";
	}
	else
	{
		sector_image_direction = "horz";
	}
	switch(sector_input.value)
	{
		case "9":
			break;

		case "30":
			sector_input.value = 0;
			sector_image.src = "http://'.IMG_DOMAIN.'/admin/sector_edit_" + direction + ".png";
			break;

		default:
			sector_input.value++;
			sector_image.src = "http://'.IMG_DOMAIN.'/admin/sector_edit_" + sector_image_direction + "_" + sector_input.value + ".png";
			break;
	}
	sector_image.alt = sector_input.value;
}

function cycleTile()
{
	x = parseInt(document.sector_edit_form.xpos.value);
	y = parseInt(document.sector_edit_form.ypos.value);
	tile_image = eval("document.tile_" + x + "_" + y);
	tile_type = eval("document.sector_edit_form.sector_" + x + "_" + y + "_type");
	tile_subtype = eval("document.sector_edit_form.sector_" + x + "_" + y + "_subtype");
	switch(tile_subtype.value)
	{
		case "":
			tile_subtype.value = "1";
			break;
		case "20":
			tile_subtype.value = "";
			break;
		default:
			tile_subtype.value = parseInt(tile_subtype.value) + 1;
			break;
	}
	tile_image.src = "http://'.IMG_DOMAIN.'/tile_" + tile_type.value + tile_subtype.value + ".png";
}

function saveFocus()
{
	document.sector_edit_form.submit();
}

function cancelFocus()
{
location.href="' . PHP_SELF . '?map_selected=' . $map_selected . '&option=edit_sector&xpos="  + document.sector_edit_form.xpos.value + "&ypos=" + document.sector_edit_form.ypos.value;
}

function matchSectors()
{
	x = parseInt(document.sector_edit_form.xpos.value);
	y = parseInt(document.sector_edit_form.ypos.value);
	direction_array = new Array ("ul","dl","up","dn","ur","dr");
	for (var direction in direction_array)
	{
		sector_input = eval("document.sector_edit_form.sector_" + x + "_" + y + "_" + direction_array[direction]);
		sector_image = eval("document.sector_edit_" + x + "_" + y + "_" + direction_array[direction]);
		switch(direction_array[direction])
		{
			case "ul":
				sector_image_direction = "horz";
				adjacent_direction = "dr";
				adjacent_x = x-1;
				adjacent_y = y-1;
				if (x % 2 == 0) // x is even
				{
					adjacent_y++;
				}
				break;
			case "dl":
				sector_image_direction = "horz";
				adjacent_direction = "ur";
				adjacent_x = x-1;
				adjacent_y = y;
				if (x % 2 == 0) // x is even
				{
					adjacent_y++;
				}
				break;
			case "up":
				sector_image_direction = "vert";
				adjacent_direction = "dn";
				adjacent_x = x;
				adjacent_y = y-1;
				break;
			case "dn":
				sector_image_direction = "vert";
				adjacent_direction = "up";
				adjacent_x = x;
				adjacent_y = y+1;
				break;
			case "ur":
				sector_image_direction = "horz";
				adjacent_direction = "dl";
				adjacent_x = x+1;
				adjacent_y = y-1;
				if (x % 2 == 0) // x is even
				{
					adjacent_y++;
				}
				break;
			case "dr":
				sector_image_direction = "horz";
				adjacent_direction = "ul";
				adjacent_x = x+1;
				adjacent_y = y;
				if (x % 2 == 0) // x is even
				{
					adjacent_y++;
				}
				break;
			default:
				// lets screw things up "to avoid problems"
				adjacent_direction = "";
				adjacent_x = "";
				adjacent_y = "";
				break;
		}
		adjacent_input = eval("document.sector_edit_form.sector_" + adjacent_x + "_" + adjacent_y + "_" + adjacent_direction);
		adjacent_image = eval("document.sector_edit_" + adjacent_x + "_" + adjacent_y + "_" + adjacent_direction);
		if(sector_input && adjacent_input) // have to figure out if ther is an existing object / input
		{
			sector_input.value = adjacent_input.value;
			switch(sector_input.value)
			{
				case "0":
					sector_image.src = "http://'.IMG_DOMAIN.'/sector_edit_" + direction_array[direction] + ".png";
					break;

				default:
					sector_image.src = "http://'.IMG_DOMAIN.'/sector_edit_" + sector_image_direction + "_" + sector_input.value + ".png";
					break;
			}
		}
	}
}
</script>
<div align="right"><input type="submit" value="Редактировать гексу" class="inputbutton"></div>
</form>
		';
		break;

	case 'edit_map_now':
	$map = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id=".$map_selected.""),0,0);
	echo '<br>Карта: <font color="#ff0000"><b>' . $map . '</b></font><br>';
	$result = myquery("SELECT MAX(game_map.xpos) AS xmax, MAX(game_map.ypos) AS ymax,game_map_type.type_name AS type_name FROM game_map,game_map_type WHERE game_map.name='$map_selected' AND game_map.type=game_map_type.type_id GROUP BY game_map.name") or die('Database Error: ' . mysql_error() . '<br>');
	$result_array = mysql_fetch_array($result);
	$xmax = $result_array['xmax'] + 1;
	$ymax = $result_array['ymax'] + 1;
	$typename = $result_array['type_name'];
	echo '<br>Размер: x-' . $xmax . ', y-' . $ymax . '<br><br><table cellpadding="0" cellspacing="0" border="0"><tr>';
	$map_selected_plus = str_replace (' ', '+', $map_selected);
	$sel_map = myquery("SELECT * FROM game_map WHERE name='$map_selected' ORDER BY xpos ASC, ypos ASC");
	$cur_x = -1;
	while ($smap = mysql_fetch_array($sel_map))
	{
		if ($cur_x!=$smap['xpos'])
		{
			echo '<td>';
			if ($smap['xpos'] % 2 == 0)
			{
				echo '<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="32" height="32" border="0"><br>';
			}
			$cur_x = $smap['xpos'];
		}
		$sector_string = "<a onClick=\"edit_win = window.open('" . PHP_SELF . "?option=edit_sector&map_selected=$map_selected_plus&xpos=".$smap['xpos']."&ypos=".$smap['ypos']."', 'Editing_Window', 'height=500,width=620,resizable=yes,scrollbars=yes'); edit_win.focus();\">" . '<img src="http://'.IMG_DOMAIN.'/map/' . $typename . $smap['subtype'] . '.jpg" width="32" height="32" border="0" alt="(' . $smap['xpos'] . ', ' . $smap['ypos'] . ')"></a><br>';
		echo $sector_string;
	}
	echo '</tr></table>';
	break;

	case 'save_map_param_now':
		$result = myquery("SELECT * FROM game_maps WHERE id = '$map_id'");

		if (mysql_num_rows($result) != 0)
		{
			$map=mysql_fetch_array($result);
			if (isset($arena)) $arena1='1';if (!isset($arena)) $arena1='0';
			if (isset($dolina)) $dolina1='1';if (!isset($dolina)) $dolina1='0';
			if (isset($not_exp)) $not_exp1='1';if (!isset($not_exp)) $not_exp1='0';
			if (isset($not_gp)) $not_gp1='1';if (!isset($not_gp)) $not_gp1='0';
			if (isset($not_win)) $not_win1='1';if (!isset($not_win)) $not_win1='0';
			if (isset($not_lose)) $not_lose1='1';if (!isset($not_lose)) $not_lose1='0';
			if (isset($boy_type1)) $boy_type11='1';if (!isset($boy_type1)) $boy_type11='0';
			if (isset($boy_type2)) $boy_type21='1';if (!isset($boy_type2)) $boy_type21='0';
			if (isset($boy_type3)) $boy_type31='1';if (!isset($boy_type3)) $boy_type31='0';
			if (isset($boy_type4)) $boy_type41='1';if (!isset($boy_type4)) $boy_type41='0';
			if (isset($boy_type5)) $boy_type51='1';if (!isset($boy_type5)) $boy_type51='0';
			if (isset($boy_type6)) $boy_type61='1';if (!isset($boy_type6)) $boy_type61='0';
			if (isset($boy_type7)) $boy_type71='1';if (!isset($boy_type7)) $boy_type71='0';
			myquery("UPDATE game_maps SET
			arena='$arena1',
			dolina='$dolina1',
			not_exp='$not_exp1',
			not_gp='$not_gp1',
			not_win='$not_win1',
			not_lose='$not_lose1',
			boy_type1='$boy_type11',
			boy_type2='$boy_type21',
			boy_type3='$boy_type31',
			boy_type4='$boy_type41',
			boy_type5='$boy_type51',
			boy_type6='$boy_type61',
			boy_type7='$boy_type71' WHERE id='$map_id'");
			echo 'Параметры карты "<font color="#ff0000"><b>' . $map['name'] . '</b></font>" сохранены!<br>
			';
		}
		else
		{
			echo'Карта не найдена!';
		}
		break;

	case 'edit_map_param_now':
		echo '<br><font color="#eeeeee">Редактирование параметров карты</font><br>';
		$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=".$map_selected.""));
		echo '<br>Карта: <font color="#ff0000"><b>' . $map['name'] . '</b></font><br>';
		echo '<br>
<form method="post" action="' . PHP_SELF . '">
<input type="hidden" name="option" value="save_map_param_now">
<input type="hidden" name="map_id" value="'.$map_selected.'">
<table cellpadding="0" cellspacing="4" border="0">
	<tr>
	<td colspan=2><input name="dolina" type="checkbox" value="'.$map['dolina'].'"'; if ($map['dolina']==1) echo ' checked'; echo'> Долина смерти (Все ограничения на атаку снимаются)</td>
	</tr>

	<tr>
	<td colspan=2><input name="arena" type="checkbox" value="'.$map['arena'].'"'; if ($map['arena']==1) echo ' checked'; echo'> Арена (После смерти перемещается на карту "Средиземье")</td>
	</tr>

	<tr>
	<td colspan=2><input name="not_exp" type="checkbox" value="'.$map['not_exp'].'"'; if ($map['not_exp']==1) echo ' checked'; echo'> Не давать на карте опыт за победу</td>
	</tr>

	<tr>
	<td colspan=2><input name="not_gp" type="checkbox" value="'.$map['not_gp'].'"'; if ($map['not_gp']==1) echo ' checked'; echo'> Не давать на карте деньги за победу</td>
	</tr>

	<tr>
	<td colspan=2><input name="not_win" type="checkbox" value="'.$map['not_win'].'"'; if ($map['not_win']==1) echo ' checked'; echo'> Не давать на карте очки WIN за победу</td>
	</tr>

	<tr>
	<td colspan=2><input name="not_lose" type="checkbox" value="'.$map['not_lose'].'"'; if ($map['not_lose']==1) echo ' checked'; echo'> Не давать на карте очки LOSE за проигрыш</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type1" type="checkbox" value="'.$map['boy_type1'].'"'; if ($map['boy_type1']==1) echo ' checked'; echo'> На карте разрешен бой "Обычный бой"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type2" type="checkbox" value="'.$map['boy_type2'].'"'; if ($map['boy_type2']==1) echo ' checked'; echo'> На карте разрешен бой "Дуэль"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type3" type="checkbox" value="'.$map['boy_type3'].'"'; if ($map['boy_type3']==1) echo ' checked'; echo'> На карте разрешен бой "Общий бой"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type4" type="checkbox" value="'.$map['boy_type4'].'"'; if ($map['boy_type4']==1) echo ' checked'; echo'> На карте разрешен бой "Клановый бой"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type5" type="checkbox" value="'.$map['boy_type5'].'"'; if ($map['boy_type5']==1) echo ' checked'; echo'> На карте разрешен бой "Все против всех"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type6" type="checkbox" value="'.$map['boy_type6'].'"'; if ($map['boy_type6']==1) echo ' checked'; echo'> На карте разрешен бой "Бой склонностей"</td>
	  </tr>

	<tr>
	<td colspan=2><input name="boy_type7" type="checkbox" value="'.$map['boy_type7'].'"'; if ($map['boy_type7']==1) echo ' checked'; echo'> На карте разрешен бой "Бой рас"</td>
	  </tr>

	<tr>
	<td>
	</td>
	</tr>

  <tr>
	<td colspan="2"><br><div align="left"><input type="submit" value="Сохранить параметры карты" class="inputbutton"></div></td>
  </tr>
</table>
</form>
		';
		break;

	case 'edit_map_param':
		echo '<br>
<font color="#eeeeee">Редактирование параметров карты</font><br>
		';
		$result = myquery("SELECT * FROM game_maps WHERE maze=0 ORDER BY name");
		if (mysql_num_rows($result) != 0)
		{
			echo '
<form method="post" action="' . PHP_SELF . '">
<input type="hidden" name="option" value="edit_map_param_now">
<table cellpadding="0" cellspacing="4" border="0">
			';
			while ($map = mysql_fetch_array($result))
			{
				echo '
  <tr>
	<td>
	  <input type="radio" name="map_selected" value="' . $map['id'] . '"></td><td>' . $map['name'] . '
	</td>
  </tr>
				 ';
			}
		echo '
  <tr>
	<td colspan="2">
	  <div align="right"><input type="submit" value="Редактировать" class="inputbutton"></div>
	</td>
  </tr>
</table>
</form>
		';
		}
		else
		{
			echo 'Ошибка!!!<br>';
		}
		break;

	case 'edit_map':
		echo '<br><font color="#eeeeee">Редактирование гекс карты</font><br>';
		$result = myquery("SELECT * FROM game_maps WHERE maze=0 ORDER BY name");
		if (mysql_num_rows($result) != 0)
		{
			echo '
			<form method="post" action="' . PHP_SELF . '">
			<input type="hidden" name="option" value="edit_map_now">
			<table cellpadding="0" cellspacing="4" border="0">
			';
			while ($map = mysql_fetch_array($result))
			{
				if ($map['maze']==1) continue;
				echo '
				<tr>
				<td>
					<input type="radio" name="map_selected" value="' . $map['id'] . '"></td><td>' . $map['name'] . '
				</td>
				</tr>
				 ';
			}
		echo '
			<tr>
			<td colspan="2">
				<div align="right"><input type="submit" value="Редактировать" class="inputbutton"></div>
			</td>
			</tr>
			</table>
			</form>
			';
		}
		else
		{
			echo 'Ошибка!!!<br>
			';
		}
		break;

	case 'create_map_now':
		echo '<font color="#eeeeee"><br>Создание новой карты</font><br>';
		$result = myquery("SELECT * FROM game_maps WHERE name = '$map_name_new'");

		if (mysql_num_rows($result) == 0)
		{
			if (isset($arena)) $arena1='1';if (!isset($arena)) $arena1='0';
			if (isset($dolina)) $dolina1='1';if (!isset($dolina)) $dolina1='0';
			if (isset($not_exp)) $not_exp1='1';if (!isset($not_exp)) $not_exp1='0';
			if (isset($not_gp)) $not_gp1='1';if (!isset($not_gp)) $not_gp1='0';
			if (isset($not_win)) $not_win1='1';if (!isset($not_win)) $not_win1='0';
			if (isset($not_lose)) $not_lose1='1';if (!isset($not_lose)) $not_lose1='0';
			if (isset($boy_type1)) $boy_type11='1';if (!isset($boy_type1)) $boy_type11='0';
			if (isset($boy_type2)) $boy_type21='1';if (!isset($boy_type2)) $boy_type21='0';
			if (isset($boy_type3)) $boy_type31='1';if (!isset($boy_type3)) $boy_type31='0';
			if (isset($boy_type4)) $boy_type41='1';if (!isset($boy_type4)) $boy_type41='0';
			if (isset($boy_type5)) $boy_type51='1';if (!isset($boy_type5)) $boy_type51='0';
			if (isset($boy_type6)) $boy_type61='1';if (!isset($boy_type6)) $boy_type61='0';
			if (isset($boy_type7)) $boy_type71='1';if (!isset($boy_type7)) $boy_type71='0';
			myquery("INSERT INTO game_maps (name,arena,dolina,not_exp,not_gp,not_win,not_lose,boy_type1,boy_type2,boy_type3,boy_type4,boy_type5,boy_type6,boy_type7) VALUES ('$map_name_new','$arena1','$dolina1','$not_exp1','$not_gp1','$not_win1','$not_lose1','$boy_type11','$boy_type21','$boy_type31','$boy_type41','$boy_type51','$boy_type61','$boy_type71')");
			$map_id = mysql_insert_id();
			for ($x = 0; $x < $map_width; $x++)
			{
				for ($y = 0; $y < $map_height; $y++)
				{
					$move_up = '0'; $move_ur = '0'; $move_dr = '0'; $move_dn = '0'; $move_dl = '0'; $move_ul = '0';

					if ($x == 0)
					{
						$move_ul = '9'; $move_dl = '9';
					}
					if ($x == $map_width - 1)
					{
						$move_ur = '9'; $move_dr = '9';
					}

					if ($y == 0)
					{
						$move_up = '9';
					}
					if ($y == $map_width - 1)
					{
						$move_dn = '9';
					}
					if (($y == 0) && ($x % 2 == 1))
					{
						$move_ul = '9'; $move_ur = '9';
					}
					if (($y == $map_height - 1) && ($x % 2 == 0))
					{
						$move_dl = '9'; $move_dr = '9';
					}
					if (!isset($odna))
					{
					$type1=''.$type.'_';
					$x1=''.$x.'_';
					$y1=''.$y.'';
					}
					else
					{
					$type1=$type; $x1=''; $y1='';
						   }
					$sel = myquery("SELECT type_id FROM game_map_type WHERE type_name='".$type1."'");
					if ($sel!=false AND mysql_num_rows($sel))
					{
						list($typeid) = mysql_fetch_array($sel);
					}
					else
					{
						myquery("INSERT INTO game_map_type (type_name) VALUES ('".$type1."')");
						$typeid=mysql_insert_id();
					}
					$result = myquery("INSERT game_map VALUES ('$map_id', $x, $y, '$move_up', '$move_ur', '$move_dr', '$move_dn', '$move_dl', '$move_ul', '$typeid', '$x1$y1', '','','','')");
				}
			}

			echo 'Карта "<font color="#ff0000"><b>' . $map_name_new . '</b></font>" создана!<br>
			';
		}
		else
		{
			echo 'Ошибка - такая карта уже существует<br>
			';
		}
		break;

	case 'create_map':
		echo '<br>
<font color="#eeeeee">Создание новой карты</font><br>
<form method="post" action="' . PHP_SELF . '">
<input type="hidden" name="option" value="create_map_now">
<table cellpadding="0" cellspacing="4" border="0">
  <tr>
	<td>Название карты (анг или рус):</td>
	<td><input type="text" name="map_name_new" size="30" maxlength="30" class="input"></td>
  </tr>
<tr>
	<td>Название файлов с гексами (анг):</td>
	<td><input type="text" name="type" size="14" maxlength="10" class="input"> <input name="odna" type="checkbox" value="odna"> - все гексы одним файлом</td>
  </tr>
  <tr>
	<td>Длина(x):</td>
	<td><input type="text" name="map_width" value="10" size="4" maxlength="2" class="input"></td>
  </tr>
  <tr>
	<td>Ширина(y):</td>
	<td><input type="text" name="map_height" value="5" size="4" maxlength="2" class="input"></td>
  </tr>


	<tr>
	<td colspan=2><input name="dolina" type="checkbox" value="dolina"> Долина смерти (Все ограничения на атаку снимаются)</td>
	</tr>

	<tr>
	<td colspan=2><input name="arena" type="checkbox" value="arena"> Арена (После смерти перемещается на карту "Средиземье")</td>
	</tr>

	<tr>
	<td colspan=2><input name="not_exp" type="checkbox" value="not_exp"> Не давать на карте опыт за победу</td>
	</tr>

	<tr>
	<td colspan=2><input name="not_gp" type="checkbox" value="not_gp"> Не давать на карте деньги за победу</td>
	</tr>

	<tr>
	<td colspan=2><input name="not_win" type="checkbox" value="not_win"> Не давать на карте очки WIN за победу</td>
	</tr>

	<tr>
	<td colspan=2><input name="not_lose" type="checkbox" value="not_lose"> Не давать на карте очки LOSE за проигрыш</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type1" type="checkbox" value="boy_type1"> На карте разрешен бой "Обычный бой"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type2" type="checkbox" value="boy_type2"> На карте разрешен бой "Дуэль"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type3" type="checkbox" value="boy_type3"> На карте разрешен бой "Общий бой"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type4" type="checkbox" value="boy_type4"> На карте разрешен бой "Клановый бой"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type5" type="checkbox" value="boy_type5"> На карте разрешен бой "Все против всех"</td>
	</tr>

	<tr>
	<td colspan=2><input name="boy_type6" type="checkbox" value="boy_type6"> На карте разрешен бой "Бой склонностей"</td>
	</tr>

	<tr>
	<td>
	</td>
	</tr>

  <tr>
	<td colspan="2"><br><div align="left"><input type="submit" value="Создать карту" class="inputbutton"></div></td>
  </tr>
</table>
</form>
		';
		break;

case 'optimize':
$start_time = StartTiming();
$result = myquery("OPTIMIZE TABLE game_map");
$result = myquery("OPTIMIZE TABLE game_users");
$result = myquery("OPTIMIZE TABLE game_gorod");
$result = myquery("OPTIMIZE TABLE game_obj");
$result = myquery("OPTIMIZE TABLE game_items");
$result = myquery("OPTIMIZE TABLE game_pm");
$result = myquery("OPTIMIZE TABLE combat");
$result = myquery("OPTIMIZE TABLE combat_history");
$result = myquery("OPTIMIZE TABLE combat_users");
$result = myquery("OPTIMIZE TABLE combat_users_exp");
$result = myquery("OPTIMIZE TABLE game_battles");
$exec_time = StopTiming($start_time);
echo 'Все карты оптимизированы за <b><font color=ff0000>' . $exec_time . '</font></b> м/сек';


break;

case 'geks':
	if (!isset($see))
	{
		echo'<form action="" method="post">Гекса: <select name="map">';
		$result = myquery("SELECT * FROM game_maps ORDER BY name desc");
		while($map=mysql_fetch_array($result))
		{
			echo '<option value='.$map['id'].'>'.$map['name'].'</option>';
		}
		echo '</select> 
		<input type=text size=2 name=map_xpos> <input type=text size=2 name=map_ypos> 
		<input type=submit value=Редактировать><input name="see" type="hidden" value=""><br>';

		echo'<br><hr><b>Существующие города:<b><br><table>';
		$i=0;
		$result = myquery("SELECT game_map.xpos,game_map.ypos,game_map.name as map_id,game_gorod.name as gorod_name,game_gorod.town,game_gorod.rustown,game_maps.name as maps_name FROM game_map,game_maps,game_gorod WHERE game_map.town=game_gorod.town AND game_map.name=game_maps.id AND game_map.town!=0 and game_map.to_map_name=0 ORDER BY game_map.name ASC,game_map.xpos ASC,game_map.ypos ASC");
		while($map=mysql_fetch_array($result))
		{
			$i++;
			if ($i%2==0) echo '<tr bgcolor=#000033>';
			else echo '<tr bgcolor=#330000>';
			echo '<td><b>'.$map['gorod_name'].'</b></td><td>'.$map['rustown'].'</td><td>'.$map['maps_name'].': '.$map['xpos'].','.$map['ypos'].'</td><td><a href="?option=do&del='.$map['map_id'].'&x='.$map['xpos'].'&y='.$map['ypos'].'">Удалить</a></td></tr>';
		}

		echo'</table><br><hr>Существующие проходы:<br><table>';
		$i=0;
		$result = myquery("SELECT game_map.xpos,game_map.ypos,game_map.name as map_id,game_obj.name as obj_name,game_obj.town,game_maps.name as maps_name FROM game_map,game_maps,game_obj WHERE game_map.town=game_obj.id AND game_map.name=game_maps.id AND game_map.town!=0 and game_map.to_map_name!=0 ORDER BY game_map.name ASC,game_map.xpos ASC,game_map.ypos ASC");
		while($map=mysql_fetch_array($result))
		{
			$i++;
			if ($i%2==0) echo '<tr bgcolor=#000033>';
			else echo '<tr bgcolor=#330000>';
			echo '<td><b>'.$map['town'].'</b></td><td>'.$map['obj_name'].'</td><td>'.$map['maps_name'].': '.$map['xpos'].','.$map['ypos'].'</td><td><a href="?option=do&del='.$map['map_id'].'&x='.$map['xpos'].'&y='.$map['ypos'].'">Удалить</a></td></tr>';
		}
		echo '</table>'; 
	}
	else
	{
		$sel=myquery("select * from game_map where name='$map' and xpos='$map_xpos' and ypos='$map_ypos' limit 1");
		if(mysql_num_rows($sel) and $map_xpos!='' and $map_ypos!='')
		{
			$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id=".$map.""),0,0);
			echo'<br>Гекса: '.$map_name.' ('.$map_xpos.','.$map_ypos.')';
			echo'<br>Действия: <a href="?option=do&gorod='.$map.'&x='.$map_xpos.'&y='.$map_ypos.'">Разместить город</a>, <a href="?option=do&vhod='.$map.'&x='.$map_xpos.'&y='.$map_ypos.'">Разместить проход</a><br>';
		}
		else
		{
			echo'Гекса не найдена';
		}
	}
break;


case 'do':
	if (isset($gorod) and isset($x) and isset($y))
	{
		if (!isset($see))
		{
			$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id=$gorod"),0,0);
			echo'<form action="" method="post">Гекса: '.$map_name.' ('.$x.','.$y.')';
			echo' <select name="town">';
			$result = myquery("SELECT town,rustown,name FROM game_gorod ORDER BY rustown");
			while($map=mysql_fetch_array($result))
			{
				echo '<option value='.$map['town'].'>'.$map['name'].' ('.$map['rustown'].')</option>';
			}
			echo '</select> <input type=submit value=Сохранить><input name=see type=hidden></form>';
		}
		else
		{
			$result = mysql_fetch_array(myquery("SELECT town,rustown,name FROM game_gorod WHERE town='$town'"));
			echo'Город <font color=#FF0000>'.$result['name'].' ('.$result['rustown'].')</font> добавлен';
			$up=myquery("update game_map set town='$town' where name='$gorod' and xpos='$x' and ypos='$y'");
		}
	}

	if (isset($vhod) and isset($x) and isset($y))
	{
		if (!isset($see))
		{
			$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id=$vhod"),0,0);
			echo'<form action="" method="post">Гекса: '.$map_name.' ('.$x.','.$y.')';
			echo' <select name="town">';
			$result = myquery("SELECT * FROM game_obj ORDER BY town ASC");
			while($map=mysql_fetch_array($result))
			{
				echo '<option value='.$map['id'].'>'.$map['town'].' ('.$map['name'].')</option>';
			}
			echo '</select> поставить проход в:
			<select name="to_map_name">';
			$result = myquery("SELECT * FROM game_maps ORDER BY name");
			while($map=mysql_fetch_array($result))
			{
				echo '<option value='.$map['id'].'>'.$map['name'].'</option>';
			}
			echo '</select>

			x-<input type=text size=2 name=to_map_xpos> y-<input type=text size=2 name=to_map_ypos>
			<input type=submit value=Сохранить><input name=see type=hidden></form>';
		}
		else
		{
			$result = mysql_fetch_array(myquery("SELECT town,name,id FROM game_obj WHERE id='$town'"));
			echo'Проход <font color=#CC0000>'.$result['town'].' ('.$result['name'].')</font> добавлен';
			$up=myquery("update game_map set town='$town',to_map_name='$to_map_name',to_map_xpos='$to_map_xpos',to_map_ypos='$to_map_ypos'  where name='$vhod' and xpos='$x' and ypos='$y'");
		}
	}

	if (isset($del) and isset($x) and isset($y))
	{
		echo'Гекса '.$x.','.$y.' очищена!';
		$up=myquery("update game_map set town='',to_map_name='',to_map_xpos='',to_map_ypos='' where name='$del' and xpos='$x' and ypos='$y' limit 1");
	}
break;



default:
	echo '<br><a href="' . PHP_SELF . '?option=create_map">Создать карту</a>
	<br><a href="' . PHP_SELF . '?option=edit_map">Редактировать гексы карты</a>
	<br><a href="' . PHP_SELF . '?option=edit_map_param">Редактировать параметры карты</a>
	<br><a href="' . PHP_SELF . '?option=delete_map">Удалить карту</a>
	<br><a href="' . PHP_SELF . '?option=geks">Редактировать гекcу</a>
	<br><a href="' . PHP_SELF . '?option=optimize">Оптимизация</a>';
}

echo '<br><font size="1">';

if (!empty($option))
{
	if ($option == 'edit_sector' || $option == 'edit_sector_now' )
	{
		echo '<a href="javascript:self.close()">[Закрыть]</a>';
	}
	elseif ($option == 'edit_map_now')
	{
		echo '<a href="' . PHP_SELF . '?option=edit_map_now&map_selected=' . $map_selected_plus . '">[Обновить]</a> <a href="' . PHP_SELF . '">[На главную]</a>';
	}
	else
	{
		echo '<br><a href="' . PHP_SELF . '">[На главную]</a>';
	}
}

echo '</font>';
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

}

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{                                                   
	show_debug();                
}
if (function_exists("save_debug")) save_debug(); 

?>