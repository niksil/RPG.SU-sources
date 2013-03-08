<?

if (function_exists("start_debug")) start_debug(); 

echo'<style type="text/css">@import url("../style/global.css");</style>';

$img='http://'.IMG_DOMAIN.'/race_table/orc/table';
echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top">';

echo'<img src="http://'.IMG_DOMAIN.'/gorod/bibl/main.jpg" width=460><br>';

echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>
Библиотека Тол-Сириона временно закрыта - идет пересортировка и заклинание книг от внешних воздействий
';

if (function_exists("save_debug")) save_debug(); 

?>