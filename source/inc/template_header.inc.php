<?php
if (function_exists("start_debug")) start_debug(); 
if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
	setLocation('index.php');
}
?>
<html>
<head>
<? 
require_once('engine.inc.php');
echo"<title>".GAME_NAME."</title>";
?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="description" content="">
<meta name="Keywords" content="">
<script language="JavaScript" type="text/javascript" src="js/cookies.js"></script>
<style type="text/css">@import url("style/global.css");</style>
</head>