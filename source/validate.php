<?php 
if (isset($_GET['fieldId']))
{
	$ar = $_GET;
}
else
{
	$ar = $_POST;
}

if (!isset($ar['inputValue']))
{
	{if (function_exists("save_debug")) save_debug(); exit;}
}
if (!isset($ar['fieldId']))
{
	{if (function_exists("save_debug")) save_debug(); exit;}
}
require_once('inc/engine.inc.php');
include('inc/xray.inc.php');
include('inc/lib.inc.php');

DbConnect();

// we'll generate XML output
header('Content-Type: text/xml');
// generate XML header
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
// create the <response> element
echo '<response>';

$result = 0;
if ($ar['fieldId']=='user_pass1')
{
	if (preg_match('/ /', iconv("UTF-8","Windows-1251//IGNORE",$ar['inputValue'])))
	{
		$result = 2;
	} 
}
else
{
	if ($ar['fieldId']!='email')
	{
		$query = "(SELECT * FROM game_users WHERE ".$ar['fieldId']."='".iconv("UTF-8","Windows-1251//IGNORE",$ar['inputValue'])."') UNION (SELECT * FROM game_users_archive WHERE ".$ar['fieldId']."='".iconv("UTF-8","Windows-1251//IGNORE",$ar['inputValue'])."')";
	}
	else
	{
		$query = "SELECT * FROM game_users_data WHERE email='".iconv("UTF-8","Windows-1251//IGNORE",$ar['inputValue'])."'";
	}
	$check = myquery($query);
	if (mysql_num_rows($check))
	{
		$result=1;
	}
	else
	{
		if ($ar['fieldId']=='user_name')
		{
			setlocale (LC_ALL, "ru_RU.CP1251");
			$String_AM = new anti_mate;
			$message = iconv("UTF-8","Windows-1251//IGNORE",$ar['inputValue']);
			$message_filter = $String_AM->filter($message);
			if ($message!=$message_filter)
			{
				$result = 3;
			}
			elseif (!(preg_match('/^[_a-zA-Z--0-9]*$/', $message)))
			{
				$result = 2;
			}   
		}
		if ($ar['fieldId']=='name')
		{
			setlocale (LC_ALL, "ru_RU.CP1251");
			$String_AM = new anti_mate;
			$message = iconv("UTF-8","Windows-1251//IGNORE",$ar['inputValue']);
			$message_filter = $String_AM->filter($message);
			if ($message!=$message_filter)
			{
				$result = 3;
			}
			elseif (!(preg_match('/^[_a-zA-Z--]*$/', $message)))
			{
				$result = 2;
			}   
		}
		if ($ar['fieldId']=='email')
		{
			if (!(preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zzA-Z0-9-]+)*@[a-zzA-Z0-9-]+(\.[a-zzA-Z0-9-]+)*$/', iconv("UTF-8","Windows-1251//IGNORE",$_POST['inputValue']))))
			{
				$result = 2;
			}   
		}
	}
}
echo '<result>'.$result.'</result><fieldid>'.$ar['fieldId'].'</fieldid>';
echo '</response>';
mysql_close();
?>