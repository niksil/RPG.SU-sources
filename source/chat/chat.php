<?
//ob_start('ob_gzhandler',9);
$dirclass="../class";
require_once('../inc/engine.inc.php');
include('../inc/xray.inc.php');
include('../inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '8');
}
else
{
	die();
}
include('../inc/lib_session.inc.php');

if (function_exists("start_debug")) start_debug();

$select=myquery("select * from game_chat_option where user_id='$user_id'");
if ($select==false OR !mysql_num_rows($select))
{
	$chato['frame']=220;
	$chato['color']='white';
	$chato['b']=0;
	$chato['i']=0;
	$chato['ref']=1;
	$chato['privat']=0;
	$chato['size']=12;
	$chato['autosc']=0;
}
else
{
	$chato=mysql_fetch_array($select);
}
if ($chato['frame']<220) $chato['frame']=220;
$admin = myquery("SELECT * FROM game_mag WHERE name='".$char['name']."' AND town = 0");

if (!isset($chato['font'])) $chato['font']='helvetica';
if (!isset($chato['color'])) $chato['color']='white';
$_SESSION['chat_color'] = $chato['color'];

function show_main_chat()
{
	global $full;
	global $chato;
	global $admin;
	global $char;
	global $height;

echo '<div style="display:inline;" id="bar">';
echo '<span><span id="menu_smile" style="display:inline;">&nbsp;&nbsp;&nbsp;<span style="cursor:url(\'/images/nav/hand.cur\'), pointer;position:relative;top:4px;" onclick="refresh_smile()"><img src="http://'.DOMAIN.'/chat/img/smiley-on.gif" title="Смайлы"></span>&nbsp;&nbsp;&nbsp;';

//смайлы 
echo '<div id="sel_smile" style="text-align:center;padding:4px;border:1px solid gold;z-index:100;background-color:#2A2A2A;font-size:12px;position:absolute;visibility:hidden;width:310px;height:15px;">
<span onclick="show_table_smile(1)" style="font-size:9px;cursor:url(\'/images/nav/hand.cur\'), pointer;border:1px dotted #585858;background-color:#363636;padding:1px;">смайлы&nbsp;1</span>&nbsp;
<span onclick="show_table_smile(2)" style="font-size:9px;cursor:url(\'/images/nav/hand.cur\'), pointer;border:1px dotted #585858;background-color:#363636;padding:1px;">смайлы&nbsp;2</span>&nbsp;
<span onclick="show_table_smile(3)" style="font-size:9px;cursor:url(\'/images/nav/hand.cur\'), pointer;border:1px dotted #585858;background-color:#363636;padding:1px;">смайлы&nbsp;3</span>&nbsp;
<span onclick="show_table_smile(4)" style="font-size:9px;cursor:url(\'/images/nav/hand.cur\'), pointer;border:1px dotted #585858;background-color:#363636;padding:1px;">смайлы&nbsp;4</span>&nbsp;
<span onclick="show_table_smile(5)" style="font-size:9px;cursor:url(\'/images/nav/hand.cur\'), pointer;border:1px dotted #585858;background-color:#363636;padding:1px;">смайлы&nbsp;5</span>';

if($char['view_smile']=='1')
{             
	for ($i=1;$i<=5;$i++)
	{
		$kolsm = 5;
		echo '<span id="table_smile'.$i.'" style="display:none;"><center><table style="margin-top:10px;border:2px dotted gold;background-color:#363636;width:';
		switch ($i)
		{
			case 1: echo '255'; break;
			case 2: echo '265'; break;
			case 3: echo '300'; break;
			case 4: echo '236'; break;
			case 5: echo '238'; break;
		}
		echo 'px;" cellspacing="3" cellpadding="0" border=0>';
		$dh = opendir('smile/');
		$smile_array=array();
		while($file = readdir($dh))
		{       
			if ($file=='.') continue;
			if ($file=='..') continue;
			if ($file=='.svn') continue;
			$len = strlen($file)-4;
			$ext = substr($file,$len,4); 
			$smile = substr($file,0,$len);
			if ($ext!='.gif') continue; 
			
			$smile_array[]=$smile;
		}
		$kol=0;   
		sort ($smile_array);
		for ($q = 0; $q<sizeof($smile_array); $q++)
		{
			if ($kol==0) echo '<tr>';
			$kol++;
			
			if ($kol<=(($i-1)*25)) continue;
			if ($kol>$i*25 AND $kol>0) continue;
			
			$img = '<td align="center" valign="middle"><a href="#end" style="cursor:url(\'/images/nav/hand.cur\'), pointer;" onClick="sm(\''.$smile_array[$q].'\');hide_all();"><img src="smile/'.$smile_array[$q].'.gif" border="0" alt="Смайл '.$q.'"></a></td>';
			echo $img;
			
			if ($kol%$kolsm==0) echo '</tr><tr>';
		}
		echo '</tr></table></center></span>';
	}                                 
}
echo '</div>';
echo'</span>';

echo'<span id="menu_setup" style="display:inline;"><span style="cursor:url(\'/images/nav/hand.cur\'), pointer;position:relative;top:4px;" onclick="refresh_setup()"><img height="16" src="http://'.DOMAIN.'/chat/img/nast7.gif" title="Настройки"></span>&nbsp;&nbsp;&nbsp;';
echo '<div id="setup" style="padding-left:6px;border:1px solid gold;z-index:100;background-color:#2A2A2A;font-size:12px;position:absolute;visibility:hidden;width:218px;height:120px;">';

echo'<form action="chat_online.php" method="post" target="chat_online" name="form1"><span style="visibility:hidden;"><input type="checkbox" name="b" ';
if ($chato['b']==1) echo" checked";
echo '> Жирный <input type="checkbox" name="i" ';
if ($chato['i']==1) echo" checked";
echo '> Курсив</span><br>&nbsp;<span style="display:none"><input type="checkbox" name="autosc"';
if ($chato['autosc']==1) echo " checked";
echo '>Авто скроллинг<br></span> <input type="checkbox" name="priv" id="priv" title="Показывать в чате сообщения только для меня (обращенные ко мне, приват и мои сообщения)"'; if ($chato['privat']==1) echo " checked"; echo'> Только для меня<br>
Цвет: <SELECT style="color:black;" NAME="col" onchange="color_words(this.value)">

<OPTION VALUE="00ff00" style="background-color: 00ff00;"'; if ($chato['color']=='00ff00') echo ' SELECTED'; echo'>Зелёный</OPTION>
<OPTION VALUE="666666" style="background-color: 666666;"'; if ($chato['color']=='666666') echo ' SELECTED'; echo'>Серый</OPTION>
<OPTION VALUE="ffff00" style="background-color: ffff00;"'; if ($chato['color']=='ffff00') echo ' SELECTED'; echo'>Жёлтый</OPTION>
<OPTION VALUE="0066ff" style="background-color: 0066ff;"'; if ($chato['color']=='0066ff') echo ' SELECTED'; echo'>Голубой</OPTION>
<OPTION VALUE="990099" style="background-color: 990099;"'; if ($chato['color']=='990099') echo ' SELECTED'; echo'>Розовый</OPTION>
<OPTION VALUE="660033" style="background-color: 660033;"'; if ($chato['color']=='660033') echo ' SELECTED'; echo'>Бордовый</OPTION>
<OPTION VALUE="ff0000" style="background-color: ff0000;"'; if ($chato['color']=='ff0000') echo ' SELECTED'; echo'>Красный</OPTION>
<OPTION VALUE="0000ff" style="background-color: 0000ff;"'; if ($chato['color']=='0000ff') echo ' SELECTED'; echo'>Синий</OPTION>

<OPTION VALUE="brown" style="background-color: brown;"'; if ($chato['color']=='brown') echo ' SELECTED'; echo'>brown</OPTION>
<OPTION VALUE="chartreuse" style="background-color: chartreuse;"'; if ($chato['color']=='chartreuse') echo ' SELECTED'; echo'>chartreuse</OPTION>

<OPTION VALUE="chocolate" style="background-color: chocolate;"'; if ($chato['color']=='chocolate') echo ' SELECTED'; echo'>chocolate</OPTION>
<OPTION VALUE="coral" style="background-color: coral;"'; if ($chato['color']=='coral') echo ' SELECTED'; echo'>coral</OPTION>
<OPTION VALUE="cornflowerblue" style="background-color: cornflowerblue;"'; if ($chato['color']=='cornflowerblue') echo ' SELECTED'; echo'>cornflowerblue</OPTION>
<OPTION VALUE="crimson" style="background-color: crimson;"'; if ($chato['color']=='crimson') echo ' SELECTED'; echo'>crimson</OPTION>
<OPTION VALUE="cyan" style="background-color: cyan;"'; if ($chato['color']=='cyan') echo ' SELECTED'; echo'>cyan</OPTION>';
//<OPTION VALUE="darkblue" style="background-color: darkblue;"'; if ($chato['color']=='darkblue') echo ' SELECTED'; echo'>darkblue</OPTION>
echo '
<OPTION VALUE="darkcyan" style="background-color: darkcyan;"'; if ($chato['color']=='darkcyan') echo ' SELECTED'; echo'>darkcyan</OPTION>
<OPTION VALUE="darkgoldenrod" style="background-color: darkgoldenrod;"'; if ($chato['color']=='darkgoldenrod') echo ' SELECTED'; echo'>darkgoldenrod</OPTION>

<OPTION VALUE="darkgray" style="background-color: darkgray;"'; if ($chato['color']=='darkgray') echo ' SELECTED'; echo'>darkgray</OPTION>
<OPTION VALUE="darkgreen" style="background-color: darkgreen;"'; if ($chato['color']=='darkgreen') echo ' SELECTED'; echo'>darkgreen</OPTION>
<OPTION VALUE="darkkhaki" style="background-color: darkkhaki;"'; if ($chato['color']=='darkkhaki') echo ' SELECTED'; echo'>darkkhaki</OPTION>
<OPTION VALUE="darkmagenta" style="background-color: darkmagenta;"'; if ($chato['color']=='darkmagenta') echo ' SELECTED'; echo'>darkmagenta</OPTION>
<OPTION VALUE="darkolivegreen" style="background-color: darkolivegreen;"'; if ($chato['color']=='darkolivegreen') echo ' SELECTED'; echo'>darkolivegreen</OPTION>
<OPTION VALUE="darkorange" style="background-color: darkorange;"'; if ($chato['color']=='darkorange') echo ' SELECTED'; echo'>darkorange</OPTION>
<OPTION VALUE="darkorchid" style="background-color: darkorchid;"'; if ($chato['color']=='darkorchid') echo ' SELECTED'; echo'>darkorchid</OPTION>
<OPTION VALUE="darkred" style="background-color: darkred;"'; if ($chato['color']=='darkred') echo ' SELECTED'; echo'>darkred</OPTION>

<OPTION VALUE="darksalmon" style="background-color: darksalmon;"'; if ($chato['color']=='darksalmon') echo ' SELECTED'; echo'>darksalmon</OPTION>
<OPTION VALUE="darkseagreen" style="background-color: darkseagreen;"'; if ($chato['color']=='darkseagreen') echo ' SELECTED'; echo'>darkseagreen</OPTION>';
//<OPTION VALUE="darkslateblue" style="background-color: darkslateblue;"'; if ($chato['color']=='darkslateblue') echo ' SELECTED'; echo'>darkslateblue</OPTION>
//<OPTION VALUE="darkslategray" style="background-color: darkslategray;"'; if ($chato['color']=='darkslategray') echo ' SELECTED'; echo'>darkslategray</OPTION>
echo '
<OPTION VALUE="darkturquoise" style="background-color: darkturquoise;"'; if ($chato['color']=='darkturquoise') echo ' SELECTED'; echo'>darkturquoise</OPTION>
<OPTION VALUE="darkviolet" style="background-color: darkviolet;"'; if ($chato['color']=='darkviolet') echo ' SELECTED'; echo'>darkviolet</OPTION> 
<OPTION VALUE="deeppink" style="background-color: deeppink;"'; if ($chato['color']=='deeppink') echo ' SELECTED'; echo'>deeppink</OPTION>
<OPTION VALUE="deepskyblue" style="background-color: deepskyblue;"'; if ($chato['color']=='deepskyblue') echo ' SELECTED'; echo'>deepskyblue</OPTION>

<OPTION VALUE="dimgray" style="background-color: dimgray;"'; if ($chato['color']=='dimgray') echo ' SELECTED'; echo'>dimgray</OPTION>
<OPTION VALUE="dodgerblue" style="background-color: dodgerblue;"'; if ($chato['color']=='dodgerblue') echo ' SELECTED'; echo'>dodgerblue</OPTION>
<OPTION VALUE="firebrick" style="background-color: firebrick;"'; if ($chato['color']=='firebrick') echo ' SELECTED'; echo'>firebrick</OPTION>
<OPTION VALUE="forestgreen" style="background-color: forestgreen;"'; if ($chato['color']=='forestgreen') echo ' SELECTED'; echo'>forestgreen</OPTION>
<OPTION VALUE="gold" style="background-color: gold;"'; if ($chato['color']=='gold') echo ' SELECTED'; echo'>gold</OPTION>

<OPTION VALUE="goldenrod" style="background-color: goldenrod;"'; if ($chato['color']=='goldenrod') echo ' SELECTED'; echo'>goldenrod</OPTION>
<OPTION VALUE="gray" style="background-color: gray;"'; if ($chato['color']=='gray') echo ' SELECTED'; echo'>gray</OPTION>
<OPTION VALUE="green" style="background-color: green;"'; if ($chato['color']=='green') echo ' SELECTED'; echo'>green</OPTION>
<OPTION VALUE="greenyellow" style="background-color: greenyellow;"'; if ($chato['color']=='greenyellow') echo ' SELECTED'; echo'>greenyellow</OPTION>
<OPTION VALUE="hotpink" style="background-color: hotpink;"'; if ($chato['color']=='hotpink') echo ' SELECTED'; echo'>hotpink</OPTION>
<OPTION VALUE="indianred" style="background-color: indianred;"'; if ($chato['color']=='indianred') echo ' SELECTED'; echo'>indianred</OPTION>';
//<OPTION VALUE="indigo" style="background-color: indigo;"'; if ($chato['color']=='indigo') echo ' SELECTED'; echo'>indigo</OPTION>
echo '
<OPTION VALUE="khaki" style="background-color: khaki;"'; if ($chato['color']=='khaki') echo ' SELECTED'; echo'>khaki</OPTION>
<OPTION VALUE="lawngreen" style="background-color: lawngreen;"'; if ($chato['color']=='lawngreen') echo ' SELECTED'; echo'>lawngreen</OPTION>
<OPTION VALUE="lightblue" style="background-color: lightblue;"'; if ($chato['color']=='lightblue') echo ' SELECTED'; echo'>lightblue</OPTION>
<OPTION VALUE="lightcoral" style="background-color: lightcoral;"'; if ($chato['color']=='lightcoral') echo ' SELECTED'; echo'>lightcoral</OPTION>

<OPTION VALUE="lightcyan" style="background-color: lightcyan;"'; if ($chato['color']=='lightcyan') echo ' SELECTED'; echo'>lightcyan</OPTION>
<OPTION VALUE="lightgreen" style="background-color: lightgreen;"'; if ($chato['color']=='lightgreen') echo ' SELECTED'; echo'>lightgreen</OPTION>
<OPTION VALUE="lightgrey" style="background-color: lightgrey;"'; if ($chato['color']=='lightgrey') echo ' SELECTED'; echo'>lightgrey</OPTION>
<OPTION VALUE="lightpink" style="background-color: lightpink;"'; if ($chato['color']=='lightpink') echo ' SELECTED'; echo'>lightpink</OPTION>
<OPTION VALUE="lightsalmon" style="background-color: lightsalmon;"'; if ($chato['color']=='lightsalmon') echo ' SELECTED'; echo'>lightsalmon</OPTION>
<OPTION VALUE="lightseagreen" style="background-color: lightseagreen;"'; if ($chato['color']=='lightseagreen') echo ' SELECTED'; echo'>lightseagreen</OPTION>
<OPTION VALUE="lightskyblue" style="background-color: lightskyblue;"'; if ($chato['color']=='lightskyblue') echo ' SELECTED'; echo'>lightskyblue</OPTION>

<OPTION VALUE="lightslategray" style="background-color: lightslategray;"'; if ($chato['color']=='lightslategray') echo ' SELECTED'; echo'>lightslategray</OPTION>
<OPTION VALUE="lightsteelblue" style="background-color: lightsteelblue;"'; if ($chato['color']=='lightsteelblue') echo ' SELECTED'; echo'>lightsteelblue</OPTION>
<OPTION VALUE="lightyellow" style="background-color: lightyellow;"'; if ($chato['color']=='lightyellow') echo ' SELECTED'; echo'>lightyellow</OPTION>
<OPTION VALUE="lime" style="background-color: lime;"'; if ($chato['color']=='lime') echo ' SELECTED'; echo'>lime</OPTION>
<OPTION VALUE="limegreen" style="background-color: limegreen;"'; if ($chato['color']=='limegreen') echo ' SELECTED'; echo'>limegreen</OPTION>
<OPTION VALUE="magenta" style="background-color: magenta;"'; if ($chato['color']=='magenta') echo ' SELECTED'; echo'>magenta</OPTION>
<OPTION VALUE="maroon" style="background-color: maroon;"'; if ($chato['color']=='maroon') echo ' SELECTED'; echo'>maroon</OPTION>

<OPTION VALUE="mediumaquamarine" style="background-color: mediumaquamarine;"'; if ($chato['color']=='mediumaquamarine') echo ' SELECTED'; echo'>mediumaquamarine</OPTION>';
//<OPTION VALUE="mediumblue" style="background-color: mediumblue;"'; if ($chato['color']=='mediumblue') echo ' SELECTED'; echo'>mediumblue</OPTION>
echo '
<OPTION VALUE="mediumorchid" style="background-color: mediumorchid;"'; if ($chato['color']=='mediumorchid') echo ' SELECTED'; echo'>mediumorchid</OPTION>
<OPTION VALUE="mediumpurple" style="background-color: mediumpurple;"'; if ($chato['color']=='mediumpurple') echo ' SELECTED'; echo'>mediumpurple</OPTION>
<OPTION VALUE="mediumseagreen" style="background-color: mediumseagreen;"'; if ($chato['color']=='mediumseagreen') echo ' SELECTED'; echo'>mediumseagreen</OPTION>
<OPTION VALUE="mediumspringgreen" style="background-color: mediumspringgreen;"'; if ($chato['color']=='mediumspringgreen') echo ' SELECTED'; echo'>mediumspringgreen</OPTION>
<OPTION VALUE="mediumturquoise" style="background-color: mediumturquoise;"'; if ($chato['color']=='mediumturquoise') echo ' SELECTED'; echo'>mediumturquoise</OPTION>

<OPTION VALUE="mediumvioletred" style="background-color: mediumvioletred;"'; if ($chato['color']=='mediumvioletred') echo ' SELECTED'; echo'>mediumvioletred</OPTION>';
//<OPTION VALUE="midnightblue" style="background-color: midnightblue;"'; if ($chato['color']=='midnightblue') echo ' SELECTED'; echo'>midnightblue</OPTION>
echo '
<OPTION VALUE="moccasin" style="background-color: moccasin;"'; if ($chato['color']=='moccasin') echo ' SELECTED'; echo'>moccasin</OPTION>
<OPTION VALUE="navajowhite" style="background-color: navajowhite;"'; if ($chato['color']=='navajowhite') echo ' SELECTED'; echo'>navajowhite</OPTION>';
//<OPTION VALUE="navy" style="background-color: navy;"'; if ($chato['color']=='navy') echo ' SELECTED'; echo'>navy</OPTION>
echo '
<OPTION VALUE="olive" style="background-color: olive;"'; if ($chato['color']=='olive') echo ' SELECTED'; echo'>olive</OPTION>
<OPTION VALUE="olivedrab" style="background-color: olivedrab;"'; if ($chato['color']=='olivedrab') echo ' SELECTED'; echo'>olivedrab</OPTION>
<OPTION VALUE="orange" style="background-color: orange;"'; if ($chato['color']=='orange') echo ' SELECTED'; echo'>orange</OPTION>
<OPTION VALUE="orangered" style="background-color: orangered;"'; if ($chato['color']=='orangered') echo ' SELECTED'; echo'>orangered</OPTION>
<OPTION VALUE="orchid" style="background-color: orchid;"'; if ($chato['color']=='orchid') echo ' SELECTED'; echo'>orchid</OPTION>
<OPTION VALUE="palegoldenrod" style="background-color: palegoldenrod;"'; if ($chato['color']=='palegoldenrod') echo ' SELECTED'; echo'>palegoldenrod</OPTION>
<OPTION VALUE="palegreen" style="background-color: palegreen;"'; if ($chato['color']=='palegreen') echo ' SELECTED'; echo'>palegreen</OPTION>
<OPTION VALUE="paleturquoise" style="background-color: paleturquoise;"'; if ($chato['color']=='paleturquoise') echo ' SELECTED'; echo'>paleturquoise</OPTION>

<OPTION VALUE="palevioletred" style="background-color: palevioletred;"'; if ($chato['color']=='palevioletred') echo ' SELECTED'; echo'>palevioletred</OPTION>
<OPTION VALUE="peachpuff" style="background-color: peachpuff;"'; if ($chato['color']=='peachpuff') echo ' SELECTED'; echo'>peachpuff</OPTION>
<OPTION VALUE="peru" style="background-color: peru;"'; if ($chato['color']=='peru') echo ' SELECTED'; echo'>peru</OPTION>
<OPTION VALUE="pink" style="background-color: pink;"'; if ($chato['color']=='pink') echo ' SELECTED'; echo'>pink</OPTION>
<OPTION VALUE="plum" style="background-color: plum;"'; if ($chato['color']=='plum') echo ' SELECTED'; echo'>plum</OPTION>
<OPTION VALUE="powderblue" style="background-color: powderblue;"'; if ($chato['color']=='powderblue') echo ' SELECTED'; echo'>powderblue</OPTION>
<OPTION VALUE="purple" style="background-color: purple;"'; if ($chato['color']=='purple') echo ' SELECTED'; echo'>purple</OPTION>

<OPTION VALUE="red" style="background-color: red;"'; if ($chato['color']=='red') echo ' SELECTED'; echo'>red</OPTION>
<OPTION VALUE="rosybrown" style="background-color: rosybrown;"'; if ($chato['color']=='rosybrown') echo ' SELECTED'; echo'>rosybrown</OPTION>
<OPTION VALUE="royalblue" style="background-color: royalblue;"'; if ($chato['color']=='royalblue') echo ' SELECTED'; echo'>royalblue</OPTION>
<OPTION VALUE="saddlebrown" style="background-color: saddlebrown;"'; if ($chato['color']=='saddlebrown') echo ' SELECTED'; echo'>saddlebrown</OPTION>
<OPTION VALUE="salmon" style="background-color: salmon;"'; if ($chato['color']=='salmon') echo ' SELECTED'; echo'>salmon</OPTION>
<OPTION VALUE="sandybrown" style="background-color: sandybrown;"'; if ($chato['color']=='sandybrown') echo ' SELECTED'; echo'>sandybrown</OPTION>
<OPTION VALUE="seagreen" style="background-color: seagreen;"'; if ($chato['color']=='seagreen') echo ' SELECTED'; echo'>seagreen</OPTION>
<OPTION VALUE="seashell" style="background-color: seashell;"'; if ($chato['color']=='seashell') echo ' SELECTED'; echo'>seashell</OPTION>

<OPTION VALUE="sienna" style="background-color: sienna;"'; if ($chato['color']=='sienna') echo ' SELECTED'; echo'>sienna</OPTION>
<OPTION VALUE="silver" style="background-color: silver;"'; if ($chato['color']=='silver') echo ' SELECTED'; echo'>silver</OPTION>
<OPTION VALUE="skyblue" style="background-color: skyblue;"'; if ($chato['color']=='skyblue') echo ' SELECTED'; echo'>skyblue</OPTION>
<OPTION VALUE="slateblue" style="background-color: slateblue;"'; if ($chato['color']=='slateblue') echo ' SELECTED'; echo'>slateblue</OPTION>
<OPTION VALUE="slategray" style="background-color: slategray;"'; if ($chato['color']=='slategray') echo ' SELECTED'; echo'>slategray</OPTION>
<OPTION VALUE="snow" style="background-color: snow;"'; if ($chato['color']=='snow') echo ' SELECTED'; echo'>snow</OPTION>
<OPTION VALUE="springgreen" style="background-color: springgreen;"'; if ($chato['color']=='springgreen') echo ' SELECTED'; echo'>springgreen</OPTION>
<OPTION VALUE="steelblue" style="background-color: steelblue;"'; if ($chato['color']=='steelblue') echo ' SELECTED'; echo'>steelblue</OPTION>
<OPTION VALUE="tan" style="background-color: tan;"'; if ($chato['color']=='tan') echo ' SELECTED'; echo'>tan</OPTION>

<OPTION VALUE="teal" style="background-color: teal;"'; if ($chato['color']=='teal') echo ' SELECTED'; echo'>teal</OPTION>
<OPTION VALUE="thistle" style="background-color: thistle;"'; if ($chato['color']=='thistle') echo ' SELECTED'; echo'>thistle</OPTION>
<OPTION VALUE="tomato" style="background-color: tomato;"'; if ($chato['color']=='tomato') echo ' SELECTED'; echo'>tomato</OPTION>
<OPTION VALUE="turquoise" style="background-color: turquoise;"'; if ($chato['color']=='turquoise') echo ' SELECTED'; echo'>turquoise</OPTION>
<OPTION VALUE="violet" style="background-color: violet;"'; if ($chato['color']=='violet') echo ' SELECTED'; echo'>violet</OPTION>
<OPTION VALUE="wheat" style="background-color: wheat;"'; if ($chato['color']=='wheat') echo ' SELECTED'; echo'>wheat</OPTION>
<OPTION VALUE="white" style="background-color: white;"'; if ($chato['color']=='white') echo ' SELECTED'; echo'>white</OPTION>

<OPTION VALUE="yellow" style="background-color: yellow;"'; if ($chato['color']=='yellow') echo ' SELECTED'; echo'>yellow</OPTION>
<OPTION VALUE="yellowgreen" style="background-color: yellowgreen;"'; if ($chato['color']=='yellowgreen') echo ' SELECTED'; echo'>yellowgreen</OPTION>

</SELECT><br>
Шрифт: <SELECT NAME="font">

<OPTION style="font-size:8pt;font-family:Arial;" VALUE="Arial"'; if ($chato['font']=='Arial') echo ' SELECTED'; echo'>Arial</OPTION>
<OPTION style="font-size:8pt;font-family:Tahoma;" VALUE="Tahoma"'; if ($chato['font']=='Tahoma') echo ' SELECTED'; echo'>Tahoma</OPTION>
<OPTION style="font-size:8pt;font-family:Verdana;" VALUE="Verdana"'; if ($chato['font']=='Verdana') echo ' SELECTED'; echo'>Verdana</OPTION>
<OPTION style="font-size:8pt;font-family:Times;" VALUE="Times"'; if ($chato['font']=='Times') echo ' SELECTED'; echo'>Times</OPTION>
<OPTION style="font-size:8pt;font-family:Courier;" VALUE="Courier"'; if ($chato['font']=='Courier') echo ' SELECTED'; echo'>Courier</OPTION>
<OPTION style="font-size:8pt;font-family:Garamond;" VALUE="Garamond"'; if ($chato['font']=='Garamond') echo ' SELECTED'; echo'>Garamond</OPTION>
<OPTION style="font-size:8pt;font-family:Helvetica;" VALUE="Helvetica"'; if ($chato['font']=='Helvetica') echo ' SELECTED'; echo'>Helvetica</OPTION>
<OPTION style="font-size:8pt;font-family:Georgia;" VALUE="Georgia"'; if ($chato['font']=='Georgia') echo ' SELECTED'; echo'>Georgia</OPTION>
<OPTION style="font-size:8pt;font-family:Sans;" VALUE="Sans"'; if ($chato['font']=='Sans') echo ' SELECTED'; echo'>Sans</OPTION>
<OPTION style="font-size:8pt;font-family:Courier New;" VALUE="Courier New"'; if ($chato['font']=='Courier New') echo ' SELECTED'; echo'>Cour.New</OPTION>

</SELECT>&nbsp;&nbsp;
<SELECT NAME="size">

<OPTION VALUE="10"'; if ($chato['size']==10) echo ' SELECTED'; echo'>10</OPTION>
<OPTION VALUE="11"'; if ($chato['size']==11) echo ' SELECTED'; echo'>11</OPTION>
<OPTION VALUE="12"'; if ($chato['size']==12) echo ' SELECTED'; echo'>12</OPTION>
<OPTION VALUE="13"'; if ($chato['size']==13) echo ' SELECTED'; echo'>13</OPTION>
<OPTION VALUE="14"'; if ($chato['size']==14) echo ' SELECTED'; echo'>14</OPTION>
<OPTION VALUE="15"'; if ($chato['size']==15) echo ' SELECTED'; echo'>15</OPTION>
<OPTION VALUE="16"'; if ($chato['size']==16) echo ' SELECTED'; echo'>16</OPTION>
<OPTION VALUE="17"'; if ($chato['size']==17) echo ' SELECTED'; echo'>17</OPTION>
<OPTION VALUE="18"'; if ($chato['size']==18) echo ' SELECTED'; echo'>18</OPTION>
<OPTION VALUE="19"'; if ($chato['size']==19) echo ' SELECTED'; echo'>19</OPTION>

</SELECT><br>
Скорость обновления: <input type="text" size="3" maxlength="3" name="ref" value="'.$chato['ref'].'"><br />
Высота чата: <input type="text" size="3" maxlength="5" name="fram" value="'.$chato['frame'].'"> пикс.
<input type="button" onclick="hide_all();this.form.submit();" value="&nbsp;OK&nbsp;"><input type="hidden" name="submn" value=""><br></form>';
echo '</div>';
echo '</span>';

echo '<div id="menu_extra" style="display:inline;"><span title="Дополнительные возможности" style="cursor:url(\'/images/nav/hand.cur\'), pointer;position:relative;top:4px;" onclick="refresh_extra()"><img height="16" src="http://'.DOMAIN.'/chat/img/dop.gif" title="Дополнительно"></span>&nbsp;&nbsp;&nbsp;';
echo '<span id="extra" style="padding-left:7px;border:1px solid gold;z-index:100;background-color:#2A2A2A;font-size:12px;position:absolute;visibility:hidden;width:228px;height:208px;">';
echo'<br><center><a href="#" onClick="open_chat();hide_all();">Чат в новом окне</a></center>';
echo'<br><center><a href="#" onClick="clear_chat();hide_all();">Очистить окно чата</a></center>';
echo'<br><center><a href="#" onClick="refresh_chat();hide_all();">Обновить окно чата</a></center>';
if (mysql_num_rows($admin))
{
	$adm = mysql_fetch_array($admin);
	if ($adm['slep']=='1')
	{
		echo'<span style="cursor:url(\'/images/nav/hand.cur\'), pointer;color:white;" onClick="pe(\'#slep:время:имя\');hide_all();">Cлепота: #slep:время:имя</span><br>';
	}
	if ($adm['mol']=='1')
	{
		echo'<span style="cursor:url(\'/images/nav/hand.cur\'), pointer;color:white;" onClick="pe(\'#mol:время:имя\');hide_all();">Молчание: #mol:время:имя</span><br>';
	}
	if ($adm['obn']=='1')
	{
		echo'<span style="cursor:url(\'/images/nav/hand.cur\'), pointer;color:white;" onClick="pe(\'#obn:\');hide_all();">Обновление: #obn:</span><br>';
	}
	if ($adm['slep']=='1')
	{
		echo'<span style="cursor:url(\'/images/nav/hand.cur\'), pointer;color:white;" onClick="pe(\'#eat:имя:комментарий\');hide_all();">Съесть: #eat:имя:комментарий</span><br>';
	}
	echo'<span style="cursor:url(\'/images/nav/hand.cur\'), pointer;color:white;" onClick="pe(\'#bot:слово:предложение\');hide_all();">Обучение бота: <br>#bot:слово:предложение</span><br>';
	//echo'<br><center><a href="http://'.DOMAIN.'/freechat/index.php" target="_blank" onclick="hide_all();">Чат стражей</a></center><br>';
}
echo '</span>';
echo'</div>';

echo '<div id="show_sound" style="cursor:url(\'/images/nav/hand.cur\'), pointer;display:none;position:relative;top:4px;" onclick="swap_sound()"><img id="img_sound" src="http://'.DOMAIN.'/chat/img/';
if (isset($_COOKIE['chat_sound']) AND $_COOKIE['chat_sound']='off') echo 'sound-off';
else echo 'sound-on';
echo'.gif"></div>';

echo'<span id="menu_bbcode" style="display:inline;"><span style="cursor:url(\'/images/nav/hand.cur\'), pointer;position:relative;top:4px;" onclick="refresh_bbcode()"><img src="http://'.DOMAIN.'/chat/img/ch-active.gif" title="BB code"></span>';
echo '<div id="bbcode" style="border:1px solid gold;z-index:100;background-color:#2A2A2A;position:absolute;visibility:hidden;width:185px;height:41px;"><span style="padding-left:8px;">';

echo '<br>&nbsp;&nbsp;&nbsp;<img src="img/bt_strong.gif" onclick="pe(\'[b][/b]\',1);hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">&nbsp;';
echo '&nbsp;<img src="img/bt_em.gif" onclick="pe(\'[i][/i]\',1);hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">&nbsp;';
echo '&nbsp;<img src="img/bt_ins.gif" onclick="pe(\'[u][/u]\',1);hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">&nbsp;';
echo '&nbsp;<img src="img/bt_del.gif" onclick="pe(\'[s][/s]\',1);hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">&nbsp;';
echo '&nbsp;<img src="img/bt_mail.gif" onclick="pe(\'[email][/email]\',1);hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">&nbsp;';
echo '&nbsp;<img src="img/bt_m.gif" onclick="pe(\'[me] \',1);hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">&nbsp;</span>';

echo '</div>';
echo '</span>';

echo'&nbsp;<span id="menu_privat" style="display:inline;"><span style="cursor:url(\'/images/nav/hand.cur\'), pointer;position:relative;top:4px;" onclick="refresh_privat()"><img src="http://'.DOMAIN.'/chat/img/ch.gif" title="Выбор привата"></span>';
echo '<div id="privat_mode" style="border:1px solid gold;z-index:100;background-color:#2A2A2A;position:absolute;visibility:hidden;width:185px;height:140px;"><span style="padding-left:8px;">';

echo '<center><i>Сказать в приват:</i></center>';
echo '<table cellspacing=1 border=0><tr><td valign="middle">Клану:</td><td>';
$selclan = myquery("SELECT clan_id, nazv FROM game_clans WHERE raz=0 ORDER BY clan_id");
$i=0;
while (list($cl,$cn)=mysql_fetch_array($selclan))
{
	$i++;
	echo ' <img src="http://'.IMG_DOMAIN.'/clan/'.$cl.'.gif" onclick="priv(\'клану '.$cn.'\');hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">';
	if ($i==5)
	{
		$i=0;
		echo '<br />';
	}
}
echo '</td></tr><tr><td>Склон-ти:</td><td>';
$img_sklon = 'http://'.IMG_DOMAIN.'/sklon/neutral.gif';
echo ' <img src="'.$img_sklon.'" onclick="priv(\'Нейтральной склонности\');hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">';
$img_sklon = 'http://'.IMG_DOMAIN.'/sklon/light.gif';
echo ' <img src="'.$img_sklon.'" onclick="priv(\'Светлой склонности\');hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">';
$img_sklon = 'http://'.IMG_DOMAIN.'/sklon/dark.gif';
echo ' <img src="'.$img_sklon.'" onclick="priv(\'Тёмной склонности\');hide_all();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;">';
echo '</td></tr></table>';

echo '</div>';
echo '</span>';

echo '</span>';
echo '</div>';
}

echo'
<html>
<head>
<title>Чат игры</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="Keywords" content="">
<style type="text/css">@import url("http://'.DOMAIN.'/style/global.css");</style>
<style type="text/css">@import url("chat.css");</style>
</head>';
if (!isset($chato['ref'])) $chato['ref']=1;
if (!isset($chato['size'])) $chato['size']=13;
$height = $chato['frame'];//-53-14-14;
//if (isset($full)) {$height="100%"; $chato['frame']=500;}
?>
<script type="text/javascript" src="../js/json.js" ></script>
<script type="text/javascript" src="../js/cookies.js" ></script>
<script language="JavaScript" type="text/javascript">
/* variables that establish how often to access the server */
var updateInterval = <? echo $chato['ref']*1000; ?>; // how many miliseconds to wait to get new message
var max_rows_onscreen = 150; //максимальное количество сообщений на экране
var sel_chat = 0;
<?
if (isset($_COOKIE['chat_sound']))
{
	?>
	var show_sound="<?=$_COOKIE['chat_sound'];?>";
	<?
}
else
{
	?>
	var show_sound="on";
	<?
}
if (isset($_COOKIE['chat_minmax']))
{
	?>
	var minmax="<?=$_COOKIE['chat_minmax'];?>";
	<?
}
else
{
	?>
	var minmax="off";
	<?
}
?>
</script>
<script type="text/javascript" src="chat.js" ></script>
<script language="JavaScript" type="text/javascript">
/* function that appends the new messages to the chat list */
function displayMessages(idArray, colorArray, nameArray, timeArray, messageArray, toArray, pmArray, channelArray, ptypeArray)
{
	// each loop adds a new message
	for(var i=0; i<idArray.length; i++)
	{
		// get the message details
		var color = colorArray[i];
		var time = timeArray[i];
		var name = nameArray[i];
		var message = messageArray[i];
		var to = toArray[i];
		var ptype = ptypeArray[i];
		var pm = pmArray[i];
		var idMessage = idArray[i];
		var channel = channelArray[i];
		var ptype = ptypeArray[i];
		// compose the HTML code that displays the message
		var htmlMessage = "";
		message = message.toString();
		if (message=="#obn:") {htmlMessage="#obn:";}
		else if (message.toString()=="CLEAR") {htmlMessage="CLEAR";}
		else if (message.substr(0,8)=="#delete:") {htmlMessage=trim(message);}
		else
		{
			var me = false;
			if (message.substr(0,4)=="[me]")
			{
				me = true;
			}
			if (!me)
			{
				htmlMessage += "<span id=\"message"+idMessage+"\" class=\"item\" style=\"display:block;font-family:<?=$chato['font'];?>; font-size:<?=$chato['size']?>px;";
				if (message.indexOf('<?php echo $char['name'];?>')>=0)
				{
					 htmlMessage += "background-color:#202020;";
				}
				if (to!="")
				{
					if (to.indexOf('<?php echo $char['name'];?>')!=-1)
					{
						htmlMessage += "background-color:#353535;";
					}
					else
					{
						htmlMessage += "background-color:#292929;";
					}
				}
				htmlMessage += "\">";
			}
			<?
			if (mysql_num_rows($admin))
			{
				?>
				htmlMessage += "<span><img style=\"border:1px dashed red;padding:2px;\" src=\"img/close-whoisbox.gif\" border=\"0\" title=\"Удалить сообщение\" onclick=\"delete_message("+idMessage+")\"></span> ";
				<?
			}
			?>
			if (!me)
			{
				htmlMessage += "<span style=\"cursor:url('/images/nav/hand.cur'), pointer;\" onClick=\"priv('" + name + "')\"><img src=\"img/p.gif\" alt=\"Приват\" title=\"Приват\"></span>&nbsp;";
				htmlMessage += "<span style=\"letter-spacing:-1px;font-size:10px;\">[" + time + "]</span> ";

				if (ptype == 0)
					htmlMessage += "<span style=\"cursor:url('/images/nav/hand.cur'), pointer;\" onClick=\"cha('" + name + "')\">" + name + "</span>" + ": ";
				else if (ptype == 1)
					htmlMessage += "<span style=\"cursor:url('/images/nav/hand.cur'), pointer;\" onClick=\"cha('" + name + "')\">" + name + "</span>" + "<span style=\"color:c0c0c0;\">(лично-><b>"+to+"</b>):</span> ";
				else if (ptype == 2)
					htmlMessage += "<span style=\"cursor:url('/images/nav/hand.cur'), pointer;\" onClick=\"cha('" + name + "')\">" + name + "</span>" + "<span style=\"color:c0c0c0;\">(клану <span style=\"cursor:url('/images/nav/hand.cur'), pointer;\" onClick=\"priv('клану " + to + "')\"><b>"+to+"</b></span>):</span> ";
				else if (ptype == 3)
					htmlMessage += "<span style=\"cursor:url('/images/nav/hand.cur'), pointer;\" onClick=\"cha('" + name + "')\">" + name + "</span>" + "<span style=\"color:c0c0c0;\">(для <span style=\"cursor:url('/images/nav/hand.cur'), pointer;\" onClick=\"priv('" + to + " склонности')\"><b>"+to+"</b></span> склонности):</span> ";
				else
					htmlMessage += "<span style=\"cursor:url('/images/nav/hand.cur'), pointer;\" onClick=\"cha('" + name + "')\">" + name + "</span>" + ": ";

				htmlMessage += "<span style=\"<?php
				if (isset($chato['font'])) echo 'font-family:'.$chato['font'].';';
				?>color:" + color + ";";
				htmlMessage += "\">";
				if (pm>0)
				{
					htmlMessage += "<a href=\"../act.php?func=pm&id=" + pm + "&pm=read\" target=\"game\">" + message.toString() + "</a>";
				}
				else
				{
					str = parseMessage(message.toString());
					htmlMessage += str;
				}
				htmlMessage += "</span>"; 
			}
			else
			{
				message = message.substr(5,message.length-5);
				message = parseMessage(message);
				htmlMessage += "<span style=\"letter-spacing:-1px;font-size:10px;\">[" + time + "]</span> ";
				htmlMessage += "<span style=\"color:"+color+";font-style: italic;font-weight: bold;\">"+"* <span style=\"cursor:url('/images/nav/hand.cur'), pointer;\" onClick=\"cha('" + name + "')\">"+name+"</span>  "+message+"</span>";
			}            
			htmlMessage += "</span>";
		}
		// display the message
		displayMessage(htmlMessage,channel);
	}
	clear_chat_for_memory();
}
function refresh_minmax()
{
	if (minmax=="on")
	{
		elem_chat_table=top.window.frames.chat.document.getElementById("chat_table");
		elem_chat_table.height = "30px";
		img = top.window.frames.chat.document.getElementById("img_minmax");
		img.src="img/maximize.gif";
		img.title="Развернуть окно чата";
		var frameset = top.window.document.getElementById("frame_set");
		<? if (isset($_GET['full']))
		{
			//echo 'frameset.rows="43,*,30";';
		}
		else
		{
			echo 'frameset.rows="*,30";';
		}
		?>
	}
	else
	{
		elem_chat_table=top.window.frames.chat.document.getElementById("chat_table");
		elem_chat_table.height = "<?=$height;?>";
		img = top.window.frames.chat.document.getElementById("img_minmax");
		img.src="img/minimize.gif";
		img.title="Свернуть окно чата";
		var frameset = top.window.document.getElementById("frame_set");
		<? if (isset($_GET['full']))
		{
		}
		else
		{
			echo 'frameset.rows="*,'.$chato['frame'].'";';
		}
		?>
	} 
}
</script>
<?php


$last_message_id = -1;//mysql_result(myquery("SELECT id FROM game_log ORDER BY id DESC LIMIT 1"),0,0)-30;
?>
<script>
var lastMessageID = <?=$last_message_id;?>;
function select_chat(g_chat,c_chat,a_chat)
{
	chat=top.window.frames.chat.window.frames.chat_f.document.getElementById("chat_text");
	chat.style.display=g_chat;
	chat=top.window.frames.chat.window.frames.chat_f.document.getElementById("combat_text");
	chat.style.display=c_chat;
	chat=top.window.frames.chat.window.frames.chat_f.document.getElementById("arcomage_text");
	chat.style.display=a_chat;
}

</script>
<?


function set_height($par)
{
	global $_GET;
	global $height;

	if (isset($_GET['full']))
	{
		return '100%';
	}
	else
	{
		return $height+$par;
	}
}

if ($height=="100%")
{
	?>
	<style type="text/css">
	html,body{
	height100%;
	margin:0px;
	padding:0px
	</style>
	<?
}
//if ($height=="100%")
//{
//	$style=' style="height: 100%"';
//	$style1 = '';
//}
//else
//{
	$style=' style="height:'.$height.'px;"';
	$style1='height:'.($height-32-25).'px;';
//}
echo '
<body onload="init();refresh_sound();refresh_minmax();">
<table '.$style.' id="chat_table" width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td id="chat_online" width="224">
		<table '.$style.' width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td height="38"><img src="http://'.IMG_DOMAIN.'/chat/online.gif" width="224" height="38"></td>
		</tr>
		<tr>
			<td background="http://'.IMG_DOMAIN.'/chat/chat_19.jpg" align=center><iframe name="chat_online" scrolling="yes" style="margin-left:6px;width:202px;background-color:transparent;border: 0 solid rgb(50,30,20)" frameborder="0" height="100%" src="chat_online.php"></iframe></td>
		</tr>
		<tr>
			<td height="25"><img src="http://'.IMG_DOMAIN.'/chat/chat_23.jpg" width="224" height="25"></td>
		</tr>
		</table>
	</td>
	<td id="chat_content">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr >
			<td>
				<table style="height:32px" width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="3"><img src="http://'.IMG_DOMAIN.'/chat/chat_02.jpg" width="6" height="32"></td>
						<td background="http://'.IMG_DOMAIN.'/chat/chat_03.jpg">
<input type="text" id="too" style="text-align: center;" value="Всем" readonly="true" size="10" onClick="hide_all();priv(\'\')">
<input type="text" id="chat_mess" size="64"  onkeydown="handleKey(event);SetMessageInputFocus();" style="color:'.$chato['color'].';" onclick="hide_all();">&nbsp;
<input type="button" id="say" value="Сказать" onClick="hide_all();sendMessage();SetMessageInputFocus();">&nbsp;
<input type="image" src="img/x.gif" id="clea" title="очистить поле ввода фразы" value="X" onClick="hide_all();top.window.frames.chat.document.getElementById(\'chat_mess\').value=\'\';SetMessageInputFocus();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;position:relative;top:4px;">&nbsp;
<input type="image" src="img/Tr.gif" id="translit" title="перевести с транслита на русский" value="Тр." onClick="rusLang();SetMessageInputFocus();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;position:relative;top:4px;"> ';
						show_main_chat();
						echo'
						<img id="img_minmax" src="img/';
						if (isset($_COOKIE['chat_minmax']) AND $_COOKIE['chat_minmax']=='off') echo'maximize';
						else echo 'minimize';
						echo '.gif" onclick="swap_minmax();SetMessageInputFocus();" style="position:absolute;top:8px;right:10px;float:right;"></td><td width="8" align="right" background="http://'.IMG_DOMAIN.'/chat/chat_03.jpg"><img src="http://'.IMG_DOMAIN.'/chat/chat_05.jpg" width="5" height="32"></td>
					</tr>
				</table>
			 </td>
		 </tr>
		 <tr>
			<td valign="top">
				<iframe name="chat_f" width="100%" scrolling="yes" style="'.$style1.'background-color:transparent;border: 1 solid rgb(50,30,20)" frameborder="0" src="chat_talk.php">
				</iframe>
			</td>
		 </tr>
		 <tr >
			<td align="left" background="http://'.IMG_DOMAIN.'/chat/chat_26.jpg" valign=top>
				<table style="height:25px;" width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="http://'.IMG_DOMAIN.'/chat/chat_25.jpg" width="69" height="22"></td>
						<td align="center"">[<a href="../../act.php?func=pm&new" target="game">Почта</a>] [<a href="http://'.DOMAIN.'/diary/" target="_blank">Дневники</a>] [<a href="http://'.DOMAIN.'/forum/" target="_blank">Зал Палантиров</a>] [<a href="http://'.DOMAIN.'/info/" target="_blank">Энциклопедия</a>] <span style="display:none;" id="select_game_chat">[<a href="#" onClick="select_chat(\'block\',\'none\',\'none\');sel_chat=0;">Игровой чат</a>] </span><span style="display:none;" id="select_combat_chat">[<a href="#" onClick="select_chat(\'none\',\'block\',\'none\');sel_chat=1;">Боевой чат</a>] </span><span style="display:none;" id="select_arcomage_chat">[<a href="#" onClick="select_chat(\'none\',\'none\',\'block\');sel_chat=2;">Чат игры 2 Башни</a>] </span></td>
					</tr>
				</table>
			</td>
		 </tr>
	   </table>
	</td>
	<td width="9">
		<table '.$style.' width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr height="15">
		<td width="9" height="32" rowspan="2" align="left" valign="top" background="http://'.IMG_DOMAIN.'/chat/chat_20.jpg"><img src="http://'.IMG_DOMAIN.'/chat/chat_06.jpg" width="9" height="32"></td>
		</tr>
		</table>
	</td>
  </tr>
</table>
<div id="sound_container" style="display:none;"></div>';

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}
echo'</body></html>';
mysql_close();

if (function_exists("save_debug")) save_debug();
?>