<?php
//ob_start('ob_gzhandler',9);
$dirclass="../class";
require_once('../inc/engine.inc.php');
include('../inc/xray.inc.php');
include('../inc/lib.inc.php');


$keyword = $_GET['keyword'];
if(ob_get_length()) ob_clean();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/xml');
function getSuggestions($keyword)
{
	DbConnect();
	$patterns = array('/\s+/', '/"+/', '/%+/');
	$replace = array('');
	$keyword = preg_replace($patterns,$replace,$keyword);
	if ($keyword!='' AND preg_match('/^[ _a-z-A-Z-0-9]*$/i', $keyword))
	{
		$keyword = mysql_escape_string($keyword);
		$query = "SELECT name FROM craft_resource WHERE name LIKE '".$keyword."%' ORDER BY BINARY name";
	}
	else
	{
		$query = "SELECT name FROM craft_resource WHERE name=''";
	}
	$result = myquery($query);
	$output = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	$output.= '<response>';
	if ($result!=false)
	{
		if (mysql_num_rows($result))
		{
			while ($row = mysql_fetch_array($result))
			{
				$output.='<name>'.iconv("Windows-1251","UTF-8//IGNORE",$row['name']).'</name>';
			}
		}
	}
	$output.='</response>';
	mysql_close();
	return $output;
}   
echo getSuggestions(iconv("UTF-8","Windows-1251//IGNORE",$keyword));
?>