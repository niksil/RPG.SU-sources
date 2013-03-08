<?
$dirclass = "../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

if (isset($_GET['boy']))
{
	$for_hod = 0;
	if (isset($_GET['hod']))
	{
		$for_hod = $_GET['hod'];
	}
	echo show_combat_log($_GET['boy'],$for_hod);
}
?>