<?php
//Удаление печатей
$user=myquery("delete from game_chat_nakaz where date_zak<=".time());

//Удаление бана
$user=myquery("delete from game_ban where time<=".time());
?>