<?

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
    $userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
    if (mysql_num_rows($userban))
    {
        $userr = mysql_fetch_array($userban);
        $min = ceil(($userr['time']-time())/60);
        echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено играть в игру!';
		echo '<br><br><br><a href="town.php">Выйти в город</a>';
        {if (function_exists("save_debug")) save_debug(); exit;}
    }

        $img='http://'.IMG_DOMAIN.'/race_table/human/table';
        echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
        <tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center;" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';

        $sel = myquery("SELECT game_name,game_file FROM game_gorod WHERE town='$town'");
        list($game_name,$game_file) = mysql_fetch_array($sel);
        if (!isset($play))
        {
            if ($game_name!='' AND $game_file!='')
			echo '<a href="town.php?&option='.$option.'&play=1">Играть в игру: '.$game_name.'</a>';
        }
        else
        {
            echo'<font face=verdana size=2>';
            echo'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="460" height="460">
            <param name="movie" value="../utils/games/'.$game_file.'">
            <param name="quality" value="high">
            <embed src="../utils/games/'.$game_file.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="460" height="460"></embed>
    </object><br>';
         }
        echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

}

if (function_exists("save_debug")) save_debug(); 

?>