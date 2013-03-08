<?php

if (function_exists("start_debug")) start_debug(); 

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
    setLocation('index.php');
}
else
{

echo '<table cellpadding="0" cellspacing="0" border="0"><tr>
<td><img src="http://'.IMG_DOMAIN.'/nav/quote_ul.gif" width="15" height="7" border="0" alt=""></td>
<td background="http://'.IMG_DOMAIN.'/nav/quote_tp.gif" colspan="3"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
<td><img src="http://'.IMG_DOMAIN.'/nav/quote_ur.gif" width="15" height="7" border="0" alt=""></td>
</tr><tr><td background="http://'.IMG_DOMAIN.'/nav/quote_lt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
<td><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="32" height="16" border="0"><br>';


$map_name = $char['map_name'];
$map = $char['map_name'];
$xpos = $char['map_xpos'];
$ypos = $char['map_ypos'];
$br='<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="32" height="32" border="0"><br>';

function move($pos)
{
	global $x;
	global $y;
	global $s;
	global $char;
	global $mov;

	$result = myquery("SELECT $pos, type, subtype FROM game_map WHERE xpos='$x' AND name='".$char['map_name']."' AND ypos='$y' limit 1");
    	$map_adj = mysql_fetch_array($result); 
    	$tile_image = 'http://'.IMG_DOMAIN.'/map/' . $map_adj['type'] . $map_adj['subtype'] . '.jpg';  
    	if ($map_adj[''.$pos.''] == '6' or ($char['clevel'] < $map_adj[''.$pos.''] and $map_adj[''.$pos.''] != 'N'))
    	{
	echo '<img src="' . $tile_image . '" width="32" height="32" border="0" alt="';
	if ($map_adj[''.$pos.''] == '6') echo 'Нельзя пройти';
	if ($char['clevel'] < $map_adj[''.$pos.''] and $map_adj[''.$pos.''] != '6' and $map_adj[''.$pos.''] != 'N') echo 'Требуется '.$map_adj[''.$pos.''].' уровень';
	echo '"><br>';
    	}
    	else
    	{
       	echo '<a href="move.php?move='.$pos.'"><img src="' . $tile_image . '" width="32" height="32" border="0" alt="(' . $x . ', ' . $y . ')"></a><br>';
        }
}


if ($xpos % 2 == 0)
{
    $ynew = $ypos;
}
else
{
    $ynew = $ypos - 1;
}
$result = myquery("SELECT move_up, move_ur, move_dr, move_dn, move_dl, move_ul, type, subtype FROM game_map WHERE xpos='$xpos' AND name='$map' AND ypos='$ypos' limit 1");
$map_now = mysql_fetch_array($result);



if ($map_now['move_ul'] != 'N')
{
$x = $xpos - 1; $y = $ynew;
move('move_ul');    
}
else
{
echo $br;
}


if ($map_now['move_dl'] != 'N')
{
$x = $xpos - 1; $y = $ynew +  1;
move('move_dl'); 
}
else
{
echo $br;
}

echo '<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="32" height="16" border="0"><br></td><td>';



if ($map_now['move_up'] != 'N')
{
$x = $xpos; $y = $ypos - 1;
move('move_up'); 
}
else
{
echo $br;
}

if ($map_now['type'] == '') 
	{
	echo '<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="32" height="32" border="0" alt=""><br>';
	}
	else
	{
	echo '<img src="http://'.IMG_DOMAIN.'/map/' . $map_now['type'] . $map_now['subtype'] . '.jpg" width="32" height="32" border="0" alt="Ваша позиция"><br>';
	}

if ($map_now['move_dn'] != 'N')
{
$x = $xpos; $y = $ypos + 1;
move('move_dn'); 
}
else
{
echo $br;
}

echo '</td><td><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="32" height="16" border="0"><br>';

if ($map_now['move_ur'] != 'N')
{
$x = $xpos + 1; $y = $ynew;
move('move_ur'); 
}
else
{
echo $br;
}

if ($map_now['move_dr'] != 'N')
{
$x = $xpos + 1; $y = $ynew + 1;
move('move_dr'); 
}
else
{
echo $br;
}

echo '<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="32" height="16" border="0"><br></td>
<td background="http://'.IMG_DOMAIN.'/nav/quote_rt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
</tr><tr><td><img src="http://'.IMG_DOMAIN.'/nav/quote_dl.gif" width="15" height="7" border="0" alt=""></td>
<td background="http://'.IMG_DOMAIN.'/nav/quote_bt.gif" colspan="3"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
<td><img src="http://'.IMG_DOMAIN.'/nav/quote_dr.gif" width="15" height="7" border="0" alt=""></td>
</tr></table><br>

<center>Карта: <b>'.$map_name.'</b><br>Позиция <font color="#ff0000"> ' . $xpos . ' </font>, <font color="#ff0000">' . $ypos . '</font>';

}

if (function_exists("save_debug")) save_debug(); 

?>