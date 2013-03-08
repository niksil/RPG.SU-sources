<?php

if (function_exists("start_debug")) start_debug(); 

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
    setLocation('index.php');
}
else
{

echo '
<table cellpadding="0" cellspacing="0" border="0" bordercolor=#0000B3>
<tr>
<td><img src="http://'.IMG_DOMAIN.'/nav/quote_ul.gif" width="15" height="7" border="0" alt=""></td>
<td background="http://'.IMG_DOMAIN.'/nav/quote_tp.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
<td><img src="http://'.IMG_DOMAIN.'/nav/quote_ur.gif" width="15" height="7" border="0" alt=""></td>
</tr>
<tr>
<td background="http://'.IMG_DOMAIN.'/nav/quote_lt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
<td>';

$map_name = $char['map_name'];
$map = $map_name;
$xpos = $char['map_xpos'];
$ypos = $char['map_ypos'];

$sel_maze = myquery("SELECT * FROM game_maze WHERE map_name=".$char['map_name']." AND (xpos>=".($xpos-1).") AND (xpos<=".($xpos+1).") AND (ypos>=".($ypos-1).") AND (ypos<=".($ypos+1).") ORDER BY xpos ASC, ypos ASC");

for ($y=$ypos-1;$y<=$ypos+1;$y++)
{
    for ($x=$xpos-1;$x<=$xpos+1;$x++)
    {
        $draw_maze=0;
        $img='';
        for ($row=0;$row<=mysql_num_rows($sel_maze)-1;$row++)
        {
            mysql_data_seek($sel_maze,$row);
            $maze = mysql_fetch_assoc($sel_maze); 
            if ($maze['xpos']==$x AND $maze['ypos']==$y)
            {
				//$sum = (1-$maze['move_up'])+2*(1-$maze['move_down'])+4*(1-$maze['move_left'])+8*(1-$maze['move_right']);
				//if ($maze['type']==1) $sum.='_enter';
				//if ($maze['type']==2) $sum.='_exit';
				$img = '<img src="http://'.IMG_DOMAIN.'/map/Maze/SQUARE/'.$maze['img'].'.gif" width="32" height="32" border="0">';
				$draw_maze = 1;
				$find_maze = $maze;
            }
        }
        if ($draw_maze==0)
        {
            $img = '<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="32" height="32" border="0">';
        }
        else
        {
            if ($y==$ypos-1 AND $x==$xpos)
            {
                if ($find_maze['move_down']==1) $img = '<a href="move.php?toxpos='.$x.'&toypos='.$y.'">'.$img.'</a>';
            }
            if ($y==$ypos AND $x==$xpos-1)
            {
                if ($find_maze['move_right']==1) $img = '<a href="move.php?toxpos='.$x.'&toypos='.$y.'">'.$img.'</a>';
            }
            if ($y==$ypos AND $x==$xpos+1)
            {
                if ($find_maze['move_left']==1) $img = '<a href="move.php?toxpos='.$x.'&toypos='.$y.'">'.$img.'</a>';
            }
            if ($y==$ypos+1 AND $x==$xpos)
            {
                if ($find_maze['move_up']==1) $img = '<a href="move.php?toxpos='.$x.'&toypos='.$y.'">'.$img.'</a>';
            }
        }
        echo $img;
    }
    echo '<br>';
}

echo '</td>
<td background="http://'.IMG_DOMAIN.'/nav/quote_rt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
</tr>
<tr>
<td><img src="http://'.IMG_DOMAIN.'/nav/quote_dl.gif" width="15" height="7" border="0" alt=""></td>
<td background="http://'.IMG_DOMAIN.'/nav/quote_bt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
<td><img src="http://'.IMG_DOMAIN.'/nav/quote_dr.gif" width="15" height="7" border="0" alt=""></td>
</tr>
</table>
<br>

<center>Карта: <font color="#ff0000">'.@mysql_result(@myquery("SELECT name FROM game_maps WHERE id=".$char['map_name'].""),0,0).'</font><br>Позиция: <font color="#ff0000"> ' . $xpos . ' </font>, <font color="#ff0000">' . $ypos . '</font>';
}

if (function_exists("save_debug")) save_debug(); 

?>