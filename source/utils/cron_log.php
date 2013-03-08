<?php
$dirclass="../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect(); 
?>
<table>
<tr>
	<td>id</td><td>cron</td><td>step</td><td>timecron</td>
</tr>
<?php

$sel = myquery("SELECT * FROM  `game_cron_log` ORDER BY `timecron` DESC LIMIT 300 ");

while ($res = mysql_fetch_array($sel))
{
    echo("<tr><td>".$res['id']."</td><td>".$res['cron']."</td><td>".$res['step']."</td><td>".date("d.m.Y H:i:s",$res['timecron'])."</td></tr>\n");
}

?></table>