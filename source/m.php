<?php

$img = "/images/map/m.jpg";
$img2 = "/images/map/mg.jpg";

$x0=0;
$y0=0;

if (!isset($par)) $par = 2;

echo '<form action="" method="POST">Размерность: <input type="text" name="par" size="5" value="'.$par.'"><input type="submit" value="Рисовать"></form><br><br>';

echo '<table cellspacing=0 cellpadding=0><tr>';
for ($x=-$par;$x<=$par;$x++)
{
    echo '<td style="width:32px">';
    if ($x%2==0) echo '<table style="width:32px;height:16px;border: 0px solid black;"><tr><td></td></tr></table>';
    for ($j=-$par;$j<=$par;$j++)
    {
	if( ( abs($x0-$x) <= ($par-1) ) && ( abs($y0-$j) <= ($par-1) ) )
	{
		if((abs($x-$x0)<1+2*($j-$y0+$par-1)) && (($x0%2==0 && $j<0) || ($x0%2==1 && $j>0)))
		{
			echo '<table style="width:32px;height:32px;border: 1px solid black;" background="'.$img2.'"><tr><td></td></tr></table>'; 
		}
		elseif(($j==0) && (abs($x)<$par))
		{
			echo '<table style="width:32px;height:32px;border: 1px solid black;" background="'.$img2.'"><tr><td></td></tr></table>'; 
		}
		elseif( ( abs($x-$x0)<2*($par-1+1)-2*($j-$y0) ) && ( ($x0%2==0 && $j>0) || ($x0%2==1 && $j<0) ) )
		{
       		 	echo '<table style="width:32px;height:32px;border: 1px solid black;" background="'.$img2.'"><tr><td></td></tr></table>'; 	
		}
		else
		{
		        echo '<table style="width:32px;height:32px;border: 1px solid black;" background="'.$img.'"><tr><td></td></tr></table>'; 
		}
	}
	else
	        echo '<table style="width:32px;height:32px;border: 1px solid black;" background="'.$img.'"><tr><td></td></tr></table>'; 

    }
    if ($x%2!=0) echo '<table style="width:32px;height:16px;border: 0px solid black;"><tr><td></td></tr></table>';
    echo '</td>';
} 
echo '</tr></table>'; 
?>
