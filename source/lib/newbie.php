<?php

if (function_exists("start_debug")) start_debug();

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

$money = 400;

if ($char['clevel']>5) $money = 0;

$img='http://'.IMG_DOMAIN.'/race_table/human/table';
echo'<center><table width=50% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';
if (!isset($do_exit))
{
	echo '<form action="" method="post"><br><center><font face=Verdana,Tahoma size=2 color=#FF0000><b>Поздравляю! Ты '.echo_sex('закончил','закончила').' обучение в Гильдии Новичков! Теперь ты можешь смело выходить в бескрайний мир Средиземья! Для этого тебе надо выйти через Врата Гильдии!<br>';
    echo '<img src="http://'.IMG_DOMAIN.'/portal/gate.jpg" border=0>';
    echo '<br><br><br><a href=act.php?do_exit>Направиться к Вратам Гильдии Новичков!</a><br><br></form>';
}
else
{
    echo '<center><img src="http://'.IMG_DOMAIN.'/portal/middle-earth.jpg" border=0>';
    echo '<br><center><font face=Verdana,Tahoma size=2><b>Ты '.echo_sex('подошел','подошла').' к вратам Гильдии, у которой стоял Стражник! Он поздравил тебя с окончанием обучения в Гильдии Новичков, выдал тебе подъемные в размере '.$money.' монет и открыл перед тобой врата, за которыми ты '.echo_sex('увидел','увидела').' прекрасный мир Средиземья';
    //$map = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name LIKE 'Средиземье'"),0,0);
    $map = 5;
    $map_query = myquery("SELECT * FROM game_map where name='$map' ORDER BY xpos DESC, ypos DESC LIMIT 1");
    $map_result = mysql_fetch_array($map_query, MYSQL_ASSOC);
    $xrandmap = mt_rand(0, $map_result['xpos']);
    $yrandmap = mt_rand(0, $map_result['ypos']);
    $update=myquery("update game_users set GP=GP+".$money.",CW=CW+'".($money*money_weight)."' where user_id='$user_id'");
    setGP($user_id,$money,58);
    $update=myquery("update game_users_map set map_name='$map', map_xpos='$xrandmap', map_ypos='$yrandmap' where user_id='$user_id'");
    $stats=myquery("INSERT INTO game_stats_timemarker (id,user_id,time_stamp,reason) VALUES ('' , '$user_id', '".time()."', '1')");

    echo '<br><br><br><input type="button" value="Вперед, навстречу приключениям!!!" onClick=location.replace("act.php")><br><br>';
}
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

if (function_exists("save_debug")) save_debug();

?>