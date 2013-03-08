<?php
if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
    setLocation('Location: index.php');
}
else
{
$img='http://'.IMG_DOMAIN.'/diary';
?>
<html>
<head>
<?
require_once('../inc/engine.inc.php');
echo"<title>".GAME_NAME." :: Дневники</title>";
?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="Keywords" content="">
<STYLE type=text/css>@import url( style.css );</STYLE>
<STYLE type=text/css>@import url( "../style/global.css" );</STYLE>
</head>
<BODY text=#ffffff vLink=#363636 aLink=#d5ae83 link=#363636 bgColor=#000000 leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?
}
?>