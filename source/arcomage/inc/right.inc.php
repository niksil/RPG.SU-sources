<?php

if (function_exists("start_debug")) start_debug(); 

$layer=myquery("select * from arcomage_users where user_id!='$user_id' AND arcomage_id='".$charboy['arcomage_id']."'");
$playerboy=mysql_fetch_array($layer);

OpenTable('title');
echo'
<div style="position:relative;">
<table width=100% height=100% cellspacing=0 cellpadding=0>
<tr><td align="right"><img src="http://'.IMG_DOMAIN.'/arcomage/Bricks.jpg" border=0 alt="@"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="right"><img src="http://'.IMG_DOMAIN.'/arcomage/Gems.jpg" border=0 alt="*"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="right"><img src="http://'.IMG_DOMAIN.'/arcomage/Monsters.jpg" border=0 alt="$"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="right"><img src="http://'.IMG_DOMAIN.'/arcomage/Tower1.gif" width=78 height=109 border=0 alt="Башня"></td></tr>
<tr><td align="right"><img src="http://'.IMG_DOMAIN.'/arcomage/ramka.jpg" width=78 border=0></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="right"><img src="http://'.IMG_DOMAIN.'/arcomage/Wall.gif" width=78 height=68 border=0 alt="Стена"></td></tr>
<tr><td align="right"><img src="http://'.IMG_DOMAIN.'/arcomage/ramka.jpg" width=78 border=0></td></tr>
<tr><td>&nbsp;</td></tr>
</table>';
//кирпичи
$bricks = (string)$playerboy['bricks'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; right:'.(5+$k*13).'; top:55; "><font size=3 face="Arial" color=#000000><b>'.$numb.'</b></font></span>';
	$k++;
}
$bricks = (string)$playerboy['bricks_add'];
$k=0;
for ($i=1;$i<=strlen($bricks);$i++)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; right:'.(63-$k*11).'; top:37; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
//драгоценности
$bricks = (string)$playerboy['gems'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; right:'.(5+$k*13).'; top:140; "><font size=3 face="Arial" color=#000000><b>'.$numb.'</b></font></span>';
	$k++;
}
$bricks = (string)$playerboy['gems_add'];
$k=0;
for ($i=1;$i<=strlen($bricks);$i++)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; right:'.(63-$k*11).'; top:122; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
//звери
$bricks = (string)$playerboy['monsters'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; right:'.(5+$k*13).'; top:225; "><font size=3 face="Arial" color=#000000><b>'.$numb.'</b></font></span>';
	$k++;
}
$bricks = (string)$playerboy['monsters_add'];
$k=0;
for ($i=1;$i<=strlen($bricks);$i++)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; right:'.(63-$k*11).'; top:207; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
//башня и стена
$bricks = (string)$playerboy['tower'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; right:'.(3+$k*22).'; top:393; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
$bricks = (string)$playerboy['wall'];
$k=0;
for ($i=strlen($bricks);$i>0;$i--)
{
	$numb = substr($bricks,$i-1,1);
	echo'<span style="position:absolute; right:'.(3+$k*22).'; top:498; "><font size=3 face="Arial" color=#ffffff><b>'.$numb.'</b></font></span>';
	$k++;
}
echo'</div>';
OpenTable('close');

if (function_exists("save_debug")) save_debug(); 

?>