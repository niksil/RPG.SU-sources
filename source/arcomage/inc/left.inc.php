<?php

if (function_exists("start_debug")) start_debug(); 

OpenTable('title');
echo'
<div id="user_left" style="position:relative;">
<table width=100% height=100% cellspacing=0 cellpadding=0>
<tr><td><img src="http://'.IMG_DOMAIN.'/arcomage/Bricks.jpg" border=0 alt="@"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/arcomage/Gems.jpg" border=0 alt="*"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><img src="http://'.IMG_DOMAIN.'/arcomage/Monsters.jpg" border=0 alt="$"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;<img src="http://'.IMG_DOMAIN.'/arcomage/Tower1.gif" width=78 height=109 border=0 alt="Башня"></td></tr>
<tr><td>&nbsp;<img src="http://'.IMG_DOMAIN.'/arcomage/ramka.jpg" width=78 border=0></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;<img src="http://'.IMG_DOMAIN.'/arcomage/Wall.gif" width=78 height=68 border=0 alt="Стена"></td></tr>
<tr><td>&nbsp;<img src="http://'.IMG_DOMAIN.'/arcomage/ramka.jpg" width=78 border=0></td></tr>
<tr><td>&nbsp;</td></tr>
</table>';
//кирпичи
$bricks = (string)$charboy['bricks'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; left:'.(60-$k*13).'; top:55; "><font size=3 face="Arial" color=#000000><b>'.$numb.'</b></font></span>';
	$k++;
}
$bricks = (string)$charboy['bricks_add'];
$k=0;
for ($i=1;$i<=strlen($bricks);$i++)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; left:'.(5+$k*11).'; top:37; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
//драгоценности
$bricks = (string)$charboy['gems'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; left:'.(60-$k*13).'; top:140; "><font size=3 face="Arial" color=#000000><b>'.$numb.'</b></font></span>';
	$k++;
}
$bricks = (string)$charboy['gems_add'];
$k=0;
for ($i=1;$i<=strlen($bricks);$i++)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; left:'.(5+$k*11).'; top:122; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
//звери
$bricks = (string)$charboy['monsters'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; left:'.(60-$k*13).'; top:225; "><font size=3 face="Arial" color=#000000><b>'.$numb.'</b></font></span>';
	$k++;
}
$bricks = (string)$charboy['monsters_add'];
$k=0;
for ($i=1;$i<=strlen($bricks);$i++)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; left:'.(5+$k*11).'; top:207; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
//башня и стена
$bricks = (string)$charboy['tower'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; left:'.(58-$k*22).'; top:395; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
$bricks = (string)$charboy['wall'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; left:'.(58-$k*22).'; top:504; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
echo'</div>';
OpenTable('close');

if (function_exists("save_debug")) save_debug(); 

?>