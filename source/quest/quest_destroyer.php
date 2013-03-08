<?php
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require_once('../inc/xray.inc.php');
require_once('../inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '15');
}
else
{
	die();
}
require_once('../inc/lib_session.inc.php');
$quest_id=24;
$book_id=2;

include("quest_bookgame.inc.php");
?>