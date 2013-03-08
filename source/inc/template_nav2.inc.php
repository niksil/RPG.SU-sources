<?php

if (function_exists("start_debug")) start_debug(); 

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
	setLocation('index.php');
}
else
{

	
list($maze,$game_maps_name)=mysql_fetch_array(myquery("SELECT maze,name FROM game_maps WHERE id=".$char['map_name'].""));
if ($maze==1)
{
	include("inc/template_nav_maze.inc.php");
	return;
}
	
$images_npc = 'http://'.IMG_DOMAIN.'/nav/action_attacknpc.gif';
$images_town = 'http://'.IMG_DOMAIN.'/nav/at.gif';
$images_user = 'http://'.IMG_DOMAIN.'/nav/i.gif';

$ms_vsadnik=8;
$vsadnik=5;

$ms_vsadnik2=15;
$vsadnik2=9;

$char_vsadnik=0;
if ($char['vsadnik']>0)
{
	$vs = myquery("SELECT vsad FROM game_vsadnik WHERE id='".$char['vsadnik']."' LIMIT 1");
	if ($vs!=false AND mysql_num_rows($vs)>0)
	{
		list($char_vsadnik) = mysql_fetch_array($vs);
	}
}

$par = 0;
if ($char['MS_VSADNIK']>=$ms_vsadnik2 AND $char_vsadnik>=$vsadnik2 AND $char['dvij']>=3)
{
	$par = 3;
}
elseif ($char['MS_VSADNIK']>=$ms_vsadnik AND $char_vsadnik>=$vsadnik AND $char['dvij']>=2)
{
	$par = 2;
}
elseif ($char['MS_VSADNIK']>0 AND $char_vsadnik>0 AND $char['dvij']>=1)
{
	$par = 1;
}

if ($char['map_name']==26) $par=0;
if ($char['map_name']==33) $par=0;
if ($char['map_name']==666) $par=0;


//QuoteTable('open');
	$img='http://'.IMG_DOMAIN.'/race_table/human/table';
	$width='100%';
	$height='100%';

	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="'.$width.'" height="'.$height.'">';


function check_map($type_map)
{
	global $map_adj;
	global $map_row;
	global $char;
	global $new_x;
	global $new_y;
	global $nav_geksa_view;

	$curr=$map_adj[$new_x][$new_y];

	$border_width=1;
	if ($nav_geksa_view==1) $border_width = 0;
	
	$tile_image = "http://".IMG_DOMAIN."/map/" . $type_map . $curr['subtype'] . ".jpg";    
	echo '<table bgcolor="#2D2D00" cellspacing=0 cellpadding=0 style="width:32px;height:32px;border:0px;" background="" title="('.$new_x.' ,'.$new_y.')"><tr><td background="'.$tile_image.'" align="center" valign="middle">';
	
	$border_style="";
	$icon = '<img border="0" width="'.(32-2*$border_width).'" height="'.(32-2*$border_width).'" src="http://'.IMG_DOMAIN.'/nav/spacer.gif"'; 
	if (((isset($map_row[$new_x][$new_y]) AND $map_row[$new_x][$new_y]==0))OR(!isset($map_row[$new_x][$new_y])))
	{
		$flag = "";
		if ((isset($map_row[$new_x-1][$new_y-1])) AND $map_row[$new_x-1][$new_y-1]==1 AND $new_x%2!=0)
		{
			$flag.="1";
			$border_style=' style="border:2px ridge #FF0000;"';    
		}    	
		if ((isset($map_row[$new_x-1][$new_y])) AND $map_row[$new_x-1][$new_y]==1)
		{
			$flag.="2"; 
			$border_style=' style="border:2px ridge #FF0000;"';    
		}        
		if ((isset($map_row[$new_x-1][$new_y+1])) AND $map_row[$new_x-1][$new_y+1]==1 AND $new_x%2==0)
		{
			$flag.="3"; 
			$border_style=' style="border:2px ridge #FF0000;"';    
		}        
		if ((isset($map_row[$new_x][$new_y-1])) AND $map_row[$new_x][$new_y-1]==1)
		{
			$flag.="4"; 
			$border_style=' style="border:2px ridge #FF0000;"';    
		}        
		if ((isset($map_row[$new_x][$new_y])) AND $map_row[$new_x][$new_y]==1)
		{
			$flag.="5"; 
			$border_style=' style="border:2px ridge #FF0000;"';    
		}        
		if ((isset($map_row[$new_x][$new_y+1])) AND $map_row[$new_x][$new_y+1]==1)
		{
			$flag.="6"; 
			$border_style=' style="border:2px ridge #FF0000;"';    
		}        
		if ((isset($map_row[$new_x+1][$new_y-1])) AND $map_row[$new_x+1][$new_y-1]==1 AND $new_x%2!=0)
		{
			$flag.="7"; ;
			$border_style=' style="border:2px ridge #FF0000;"';    
		}        
		if ((isset($map_row[$new_x+1][$new_y])) AND $map_row[$new_x+1][$new_y]==1)
		{
			$flag.="8"; ;
			$border_style=' style="border:2px ridge #FF0000;"';    
		}        
		if ((isset($map_row[$new_x+1][$new_y+1])) AND $map_row[$new_x+1][$new_y+1]==1 AND $new_x%2==0)
		{
			$flag.="9"; 
			$border_style=' style="border:2px ridge #FF0000;"';    
		}  
		$border_style="";
		$dir = "";
		//echo $flag;
		switch ($flag)
		{
			case "6": $dir="down/"; break;
			case "4": $dir="up/"; break;
			case "12": $dir="left/"; break;
			case "23": $dir="left/"; break;
			case "3": $dir="left_down/"; break;
			case "1": $dir="left_up/"; break;
			case "2":
			{
				if ($new_x%2!=0)
				{
					$dir="left_down/";
				}
				else
				{
					$dir="left_up/";
				}
			}
			break;
			case "14": $dir="up_left_up/"; break;
			case "24": $dir="up_left_up/"; break;
			case "48": $dir="up_right_up/"; break;
			case "47": $dir="up_right_up/"; break;
			case "7": $dir="right_up/"; break;
			case "78": $dir="right/"; break;
			case "8":
			{
				if ($new_x%2!=0)
				{
					$dir="right_down/";
				}
				else
				{
					$dir="right_up/";
				}
			}
			break;
			case "9": $dir="right_down/"; break;
			case "89": $dir="right/"; break;
			case "68": $dir="down_right_down/"; break;
			case "69": $dir="down_right_down/"; break;
			case "26": $dir="down_left_down/"; break;
			case "36": $dir="down_left_down/"; break;
		} 
		
		$icon = '<img border="0" width="32" height="32" src="http://'.IMG_DOMAIN.'/map_icon/'.$dir.'spacer.gif"';      
		if ($curr['user_id']!=NULL)
		{
			//$icon = '<img width="32" height="32" border="0" src="http://'.IMG_DOMAIN.'/map_icon/'.$dir.'user.gif"';
		}
		if ($curr['userid']!=NULL)
		{
			$icon = '<img width="32" height="32" border="0" src="http://'.IMG_DOMAIN.'/map_icon/'.$dir.'shahta.gif"';
		}
		if ($curr['npcid']!=NULL)
		{
			$icon = '<img width="32" height="32" border="0" src="http://'.IMG_DOMAIN.'/map_icon/'.$dir.'monstr.gif"';
		}
		if ($curr['shopid']!=NULL)
		{
			$icon = '<img width="32" height="32" border="0" src="http://'.IMG_DOMAIN.'/map_icon/'.$dir.'torgovec.gif"';
		}
		if ($curr['combid']!=NULL)
		{
			$icon = '<img width="32" height="32" border="0" src="http://'.IMG_DOMAIN.'/map_icon/'.$dir.'boy1.gif"';
		}
		if ($curr['town']>0 AND $curr['to_map_name']==0 AND $curr['rustown']!='' AND $curr['rustown']!=NULL)
		{
			$icon = '<img width="32" height="32" border="0" src="http://'.IMG_DOMAIN.'/map_icon/'.$dir.'gorod.gif"';
		}
	}
	else
	{
		if (isset($map_row[$new_x][$new_y]) AND $map_row[$new_x][$new_y]==1)
		{
			echo '<a href="move.php?x='.$new_x.'&y='.$new_y.'">';
			$border_style=' style="border:'.$border_width.'px solid #414141;"';
		}

		if ($curr['user_id']!=NULL)
		{
			$icon = '<img width="'.(32-2*$border_width).'" height="'.(32-2*$border_width).'" border="0" src="http://'.IMG_DOMAIN.'/map_icon/user.gif"';
		}
		if ($curr['userid']!=NULL)
		{
			$icon = '<img width="'.(32-2*$border_width).'" height="'.(32-2*$border_width).'" border="0" src="http://'.IMG_DOMAIN.'/map_icon/shahta.gif"';
		}
		if ($curr['npcid']!=NULL)
		{
			$icon = '<img width="'.(32-2*$border_width).'" height="'.(32-2*$border_width).'" border="0" src="http://'.IMG_DOMAIN.'/map_icon/monstr.gif"';
		}
		if ($curr['shopid']!=NULL)
		{
			$icon = '<img width="'.(32-2*$border_width).'" height="'.(32-2*$border_width).'" border="0" src="http://'.IMG_DOMAIN.'/map_icon/torgovec.gif"';
		}
		if ($curr['combid']!=NULL)
		{
			$icon = '<img width="'.(32-2*$border_width).'" height="'.(32-2*$border_width).'" border="0" src="http://'.IMG_DOMAIN.'/map_icon/boy2.gif"';
		}
		if ($curr['town']>0 AND $curr['to_map_name']==0 AND $curr['rustown']!='' AND $curr['rustown']!=NULL)
		{
			$icon = '<img width="'.(32-2*$border_width).'" height="'.(32-2*$border_width).'" border="0" src="http://'.IMG_DOMAIN.'/map_icon/gorod.gif"';
		}
	}
	
	echo $icon.$border_style.'>';
	if (isset($map_row[$new_x][$new_y]) AND $map_row[$new_x][$new_y]==1)
	{
		echo '</a>';
	}
	echo '</td></tr></table>'; 
}


$map_name = $char['map_name'];
$map = $map_name;
$xpos = $char['map_xpos'];
$ypos = $char['map_ypos'];
$br='<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="32" height="32" border="0">';

$selmapmax = myquery("SELECT xpos,ypos,type FROM game_map WHERE name=$map ORDER BY xpos DESC, ypos DESC LIMIT 1");
$xmap_max = mysql_result($selmapmax,0,0);
$ymap_max = mysql_result($selmapmax,0,1);  
$type_map = mysql_result($selmapmax,0,2);  
if ($type_map>0)
{
	list($type_map) = mysql_fetch_array(myquery("SELECT type_name FROM game_map_type WHERE type_id=$type_map")); 
	$sel = myquery("SELECT * FROM view_map_info WHERE name=$map AND xpos>=".($xpos-$par-$nav_geksa_view)." AND xpos<=".($xpos+$par+$nav_geksa_view)." AND ypos>=".($ypos-$par-$nav_geksa_view)." AND ypos<=".($ypos+$par+$nav_geksa_view)."");
	$map_adj = array();
	while ($map_now = mysql_fetch_assoc($sel))
	{
		$map_adj[$map_now['xpos']][$map_now['ypos']] = $map_now;
	}

	$map_row = array();
	$loc_par = $par;
	$loc_par++;
	$loc_par++;
	$x0=0;
	$y0=0;
	for ($x=-$loc_par+1;$x<=+$loc_par-1;$x++)
	{
		$new_x = $xpos+$x;
		for ($y=-$loc_par+1;$y<=+$loc_par-1;$y++)
		{
			$delta_y = 0;
			if ($xpos%2!=0)
			{
				if ($new_x>$xpos)
				{
					if ($new_x%2==0)
					{
						$delta_y = -1;
					}
				}
				if ($new_x<$xpos)
				{
					if ($new_x%2==0)
					{
						$delta_y = -1;
					}
				}
			}
			$new_y = $ypos+$y+$delta_y;
			$map_row[$new_x][$new_y]=0;
			if( ( abs($x0-$x) <= ($loc_par-1) ) && ( abs($y0-$y) <= ($loc_par-1) ) and $new_x>=0 and $new_y>=0 and $new_x<=$xmap_max AND $new_y<=$ymap_max)
			{
				if((abs($x-$x0)<1+2*($y-$y0+$loc_par-1)) && (($x0%2==0 && $y<0) || ($x0%2==1 && $y>0)))
				{
					$map_row[$new_x][$new_y]=1;    
				}
				elseif(($y==0) && (abs($x)<$loc_par))
				{
					$map_row[$new_x][$new_y]=1;
				}
				elseif( ( abs($x-$x0)<2*($loc_par-1+1)-2*($y-$y0) ) && ( ($x0%2==0 && $y>0) || ($x0%2==1 && $y<0) ) )
				{
					$map_row[$new_x][$new_y]=1;     
				}
			}
		}
	} 

	if (!isset($nav_geksa_view)) $nav_geksa_view = 2;
	$par = $par+$nav_geksa_view;
	$par++;
	echo '<table cellspacing=0 cellpadding=0><tr>';
	for ($x=-$par+1;$x<=+$par-1;$x++)
	{
		echo '<td style="width:32px">';
		if ($x%2==0) echo '<table style="width:32px;height:16px;border: 0px solid black;"><tr><td></td></tr></table>';
		$new_x = $xpos+$x;
		for ($y=-$par+1;$y<=+$par-1;$y++)
		{
			$delta_y = 0;
			if ($xpos%2!=0)
			{
				if ($new_x>$xpos)
				{
					if ($new_x%2==0)
					{
						$delta_y = -1;
					}
				}
				if ($new_x<$xpos)
				{
					if ($new_x%2==0)
					{
						$delta_y = -1;
					}
				}
			}
			$new_y = $ypos+$y+$delta_y;
			if( ( abs($x0-$x) <= ($par-1) ) && ( abs($y0-$y) <= ($par-1) ) and $new_x>=0 and $new_y>=0 and $new_x<=$xmap_max AND $new_y<=$ymap_max)
			{
				if ($new_x==$xpos AND $new_y==$ypos)
				{
					$tile_image = "http://".IMG_DOMAIN."/map/" . $type_map . $map_adj[$new_x][$new_y]['subtype'] . ".jpg";
					echo '<table bgcolor="#2D2D00" cellspacing=0 cellpadding=0 style="width:32px;height:32px;border:0px solid black;" background="" title="('.$new_x.' ,'.$new_y.')"><tr><td background="'.$tile_image.'" align="center" valign="middle"><img border="0" width="28" height="28" src="http://'.IMG_DOMAIN.'/nav/spacer.gif" style="border:2px solid #111111;"></td></tr></table>'; 	
				}
				else
				{
					if((abs($x-$x0)<1+2*($y-$y0+$par-1)) && (($x0%2==0 && $y<0) || ($x0%2==1 && $y>0)))
					{
						check_map($type_map);
					}
					elseif(($y==0) && (abs($x)<$par))
					{
						check_map($type_map);
					}
					elseif( ( abs($x-$x0)<2*($par-1+1)-2*($y-$y0) ) && ( ($x0%2==0 && $y>0) || ($x0%2==1 && $y<0) ) )
					{
						check_map($type_map);
					}
					else
					{
						echo '<table style="width:32px;height:32px;border: 0px solid black;"><tr><td></td></tr></table>'; 
					}
				}
			}
			else
				echo '<table style="width:32px;height:32px;border: 0px solid black;"><tr><td></td></tr></table>'; 
		}
		if ($x%2!=0) echo '<table style="width:32px;height:16px;border: 0px solid black;"><tr><td></td></tr></table>';
		echo '</td>';
	} 
	echo '</tr></table>'; 
}
//QuoteTable('close');
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';


echo '<br><center>Карта: <font color="#ff0000">'.$game_maps_name.'</font><br>Позиция: <font color="#ff0000"> ' . $xpos . ' </font>, <font color="#ff0000">' . $ypos . '</font>';

}

if (function_exists("save_debug")) save_debug(); 

?>
