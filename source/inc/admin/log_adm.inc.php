<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['log_adm'] >= 1)
{
    echo'<table border=0 cellspacing=2 cellpadding=2 width=100%>';
    if (!isset($page)) $page=1;
    if (!isset($dei)) $dei='';
    if (!isset($min_time)) $min_time='';
    if (!isset($max_time)) $max_time='';
    $page=(int)$page;
    if ($page<1)
    {
        $result=myquery("select * from game_log_adm order by cur_time DESC");
    }
    else
    {
        $line=30;
        if ($dei!='')
        {
            if ($min_time!='' AND $max_time!='')
            {
                $result0=myquery("select count(*) from game_log_adm where adm='".$dei."' AND cur_time>=$min_time AND cur_time<=$max_time order by cur_time DESC");
                $result=myquery("select * from game_log_adm where adm='".$dei."' AND cur_time>=$min_time AND cur_time<=$max_time order by cur_time DESC limit ".(($page-1)*$line).", $line");
            }
            else
            {
                $result0=myquery("select count(*) from game_log_adm where adm='".$dei."' order by cur_time DESC");
                $result=myquery("select * from game_log_adm where adm='".$dei."' order by cur_time DESC limit ".(($page-1)*$line).", $line");
            }
        }
        else
        {
            if ($min_time!='' AND $max_time!='')
            {
                $result0=myquery("select count(*) from game_log_adm where cur_time>=$min_time AND cur_time<=$max_time ORDER by cur_time DESC");
                $result=myquery("select * from game_log_adm where cur_time>=$min_time AND cur_time<=$max_time ORDER by cur_time DESC limit ".(($page-1)*$line).", $line");
            }
            else
            {
                $result0=myquery("select count(*) from game_log_adm order by cur_time DESC");
                $result=myquery("select * from game_log_adm order by cur_time DESC limit ".(($page-1)*$line).", $line");
            }
        } 
        $allpage=ceil(mysql_result($result0,0,0)/$line);
        if ($page>$allpage) $page=$allpage;
        if ($page<1) $page=1;
    }
	while($log=mysql_fetch_array($result))
	{
		echo '<tr><td width=150 bgcolor=#00003E>'.date("d-m-Y",$log['cur_time']).'  (' . date("H:i:s",$log['cur_time']) . ') </td><td width=100 bgcolor=#460000>' . $log['adm'] . '</td><td>' . $log['dei'] . '</td></tr>';
	}
	echo'</table><br><br><br>';
    
    if($page>=1)
    {
        $href = '?option=log_adm&opt=main&min_time='.$min_time.'&max_time='.$max_time.'&dei='.$dei.'&';
	    echo'<center>Страница: ';
        show_page($page,$allpage,$href);
    }
    

    echo '<br><br><a href="?opt=main&option=log_adm&page=0">Показать весь лог действий</a><br>';

    echo '<table width=100%><tr><td width=50%>';
    $sel = myquery("SELECT DISTINCT adm FROM game_log_adm ORDER BY adm ASC");
    while (list($map) = mysql_fetch_array($sel))
    {
    	echo '<br><a href="?opt=main&option=log_adm&dei='.$map.'">Лог действий: '.$map.'</a>';
    }
    echo '</td><td width=50%><table>';

	$year_sel = myquery("SELECT DISTINCT year FROM game_log_adm");
	while(list($year)=mysql_fetch_array($year_sel))
	{
    	$min_time = mktime(0,0,0,1,1,$year);
    	$max_time = mktime(23,59,59,12,31,$year);
        if ($dei!='')
        {
     	    echo '<tr><td colspan=3><a href="?opt=main&option=log_adm&dei='.$dei.'&min_time='.$min_time.'&max_time='.$max_time.'">Лог действий '.$dei.' за '.$year.' год</a></td></tr>';
        }
        else
        {
     	    echo '<tr><td colspan=3><a href="?opt=main&option=log_adm&min_time='.$min_time.'&max_time='.$max_time.'">Лог действий админов за '.$year.' год</a></td></tr>';
        }
		$month_sel = myquery("SELECT DISTINCT month FROM game_log_adm WHERE year=$year");
		while(list($month)=mysql_fetch_array($month_sel))
		{
			$end_month=0;
    		if (checkdate($month,31,$year)) $end_month=31;
    		elseif (checkdate($month,30,$year)) $end_month=30;
    		elseif (checkdate($month,29,$year)) $end_month=29;
    		elseif (checkdate($month,28,$year)) $end_month=28;
    		$min_time = mktime(0,0,0,$month,1,$year);
    		$max_time = mktime(23,59,59,$month,$end_month,$year);
            if ($dei!='')
            {
			    echo '<tr><td></td><td colspan=2>&nbsp;&nbsp;<a href="?opt=main&dei='.$dei.'&option=log_adm&min_time='.$min_time.'&max_time='.$max_time.'">Лог действий '.$dei.' за '.$month.' мес. '.$year.' года</a></td></tr>';
            }
            else
            {
			    echo '<tr><td></td><td colspan=2>&nbsp;&nbsp;<a href="?opt=main&option=log_adm&min_time='.$min_time.'&max_time='.$max_time.'">Лог действий админов за '.$month.' мес. '.$year.' года</a></td></tr>';
            }
		}
	}
    echo '</table></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>