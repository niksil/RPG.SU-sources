<?Php
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require_once('../inc/xray.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/lib_session.inc.php');

if (function_exists("start_debug")) start_debug(); 

if (!isset($_GET['id']))
{
	setLocation("../act.php");
	exit();
}
if (!isset($_SESSION['katorga_checksum_href']))
{
    setLocation("../act.php");
    exit();
}
if (!isset($_SESSION['katorga_checksum_href']))
{
    setLocation("../act.php");
    exit();
}
if ($_GET['id']!=$_SESSION['katorga_checksum_href'])
{
	setLocation("../act.php");
	exit();
}

$prisoner=myquery("SELECT * FROM game_prison WHERE user_id='$user_id'");
if(!mysql_num_rows($prisoner))
{
	setLocation("../act.php");
	exit();
}
$prisoner = mysql_fetch_array($prisoner);

if($char['map_name']==666)
{
	if($char['map_xpos']==1 AND $char['map_ypos']==1)
		$option='work';
	elseif ($char['map_xpos']==0 AND $char['map_ypos']==0)
		$option='exit';
	elseif ( ($char['map_xpos']==0 AND $char['map_ypos']==1) OR ($char['map_xpos']==1 AND $char['map_ypos']==0) OR ($char['map_xpos']==1 AND $char['map_ypos']==2) OR ($char['map_xpos']==2 AND $char['map_ypos']==0) OR ($char['map_xpos']==2 AND $char['map_ypos']==1))
		$option='run';
	else 
	{
		echo 'FATAL ERROR 1784';
		$st='Это был игрок: '.$char['name'].'. Попытка взлома механизма Каторги.';	
		//$name='Inquisitor_I';
		//$id=mysql_result(myquery("SELECT user_id FROM game_users WHERE name='$name'"),0,0);
		myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (612, 'От движка квестов', 'Они нажали куда нельзя!!!', '".$st."', '0','".time()."')") or die(mysql_error());	
		exit();
	}
}
else
{
	echo 'FATAL ERROR: 1759';
	$st='Это был игрок: '.$char['name'].'. Попытка взлома механизма Каторги.';	
	//$name='Inquisitor_I';
	//$id=mysql_result(myquery("SELECT user_id FROM game_users WHERE name='$name'"),0,0);
	myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (612, 'От движка квестов', 'Они нажали куда нельзя!!!', '".$st."', '0','".time()."')") or die(mysql_error());	
	exit();
}

include("inc/actions.inc.php");
$_SESSION['katorga_checksum_href'] = '';
//echo '<meta http-equiv="refresh" content="1;url=../../act.php">';

if (function_exists("save_debug")) save_debug(); 

?>