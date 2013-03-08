<?php
if ($typ=="j") $type=1;
if ($typ=="o") $type=2;
if ($typ=="c") $type=3;
if ($typ=="p") $type=4;

$mes = @mysql_result(@myquery("SELECT text FROM game_bot_chat WHERE type='$type' ORDER BY RAND(), LENGTH(type) DESC LIMIT 1"),0,0);

?>