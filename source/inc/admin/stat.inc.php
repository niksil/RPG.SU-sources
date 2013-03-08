<?php
//header("Expires: Mon, 6 Dec 1977 00:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
//header("Cache-Control: no-cache, must-revalidate");
//header("Pragma: no-cache");

if (function_exists("start_debug")) start_debug(); 

if ($adm['stat'] != 1)
{
    setLocation('index.php');
    {if (function_exists("save_debug")) save_debug(); exit;}
}
else
{
    function GetBrowser($HTTP_USER_AGENT)
    {
        global $BROWSER_VER, $BROWSER_AGENT, $BROWSER_TYPE;

        if (preg_match('/MSIE ([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
            $BROWSER_VER = $log_version[1];
            $BROWSER_AGENT = 'Internet Explorer';
            $BROWSER_TYPE = 'Browser';
        } elseif (preg_match( '/Opera ([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
            $BROWSER_VER = $log_version[1];
            $BROWSER_AGENT = 'Opera';
            $BROWSER_TYPE = 'Browser';
        } else if (preg_match('/Netscape6 ([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
            $BROWSER_VER = $log_version[1];
            $BROWSER_AGENT = 'Netscape';
            $BROWSER_TYPE = 'Browser';
        } else if (preg_match('/Netscape/', $HTTP_USER_AGENT, $log_version)) {
            $BROWSER_VER = '';
            $BROWSER_AGENT = 'Netscape';
            $BROWSER_TYPE = 'Browser';
        } else if (preg_match('/Nav/', $HTTP_USER_AGENT) || preg_match('/Gold/', $HTTP_USER_AGENT) || preg_match('/X11/', $HTTP_USER_AGENT)) {
            $BROWSER_VER = 'Other';
            $BROWSER_AGENT = "Netscape";
            $BROWSER_TYPE = 'Browser';
        } elseif (preg_match( '/Mozilla ([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
            $BROWSER_VER = $log_version[1];
            $BROWSER_AGENT = 'Mozilla';
            $BROWSER_TYPE = 'Browser';
        } else if (preg_match('/Lynx ([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
            $BROWSER_VER = $log_version[1];
            $BROWSER_AGENT = 'Lynx';
            $BROWSER_TYPE = 'Browser';
        } else if (preg_match('/WebTV/', $HTTP_USER_AGENT)) {
            $BROWSER_VER = '';
            $BROWSER_AGENT = 'WebTV';
            $BROWSER_TYPE = 'Browser';
        } else if (preg_match('/Konqueror/', $HTTP_USER_AGENT)) {
            $BROWSER_VER = '';
            $BROWSER_AGENT = 'Konqueror';
            $BROWSER_TYPE = 'Browser';
        } elseif (preg_match( '/QWK.Mon ([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
            $BROWSER_VER = $log_version[1];
            $BROWSER_AGENT = 'Qwkmon.com';
            $BROWSER_TYPE = 'Bot';
        } elseif (preg_match( '/DeckIt ([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
            $BROWSER_VER = $log_version[1];
            $BROWSER_AGENT = 'Deck-It';
            $BROWSER_TYPE = 'Browser';
        } else if (preg_match('/Acoon/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Acoon.de'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/AltaVista/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'AltaVista'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Anzwers/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Anzwers.com.au'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/euroseek/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'EuroSeek.com'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/ArchitextSpider/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Excite'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/fido/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'PlanetSearch.com'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Fireball/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Fireball.de'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/GAIS/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Seed.net.tw'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Google/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Googlebot.com'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Gulliver/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'NortherLight.com'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Infoseek/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Infoseek'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/lwp-trivial/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Search4Free.com'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Lycos/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Lycos'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Nokia8110/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Nokia 7110'; $BROWSER_TYPE = 'WAP';
        } else if (preg_match('/Scooter/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'AltaVista'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Search.at/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Search.at'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Slurp/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Hotbot.com'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Fireball/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'SwissSearch (Search.ch)'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Informant/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'The Informant (informant.dartmouth.edu)'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/Ultraseek/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Infoseek'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/WebCrawler/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'WebCrawler.com'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/WiseWire/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'WiseWire.com'; $BROWSER_TYPE = 'Bot';
        } else if (preg_match('/bot/', $HTTP_USER_AGENT) || preg_match('/Spider/', $HTTP_USER_AGENT)) { $BROWSER_VER = ''; $BROWSER_AGENT = 'Bot';
        } else { $BROWSER_VER = ''; $BROWSER_AGENT = 'Unknown'; $BROWSER_TYPE = 'Unknown';
        }

    }

    function GetPlatform($HTTP_USER_AGENT)
    {
        global $BROWSER_PLATFORM;
        if (strstr($HTTP_USER_AGENT, 'Win')) {
            $BROWSER_PLATFORM = 'Windows';
        } else if (strstr($HTTP_USER_AGENT, 'Mac') || strstr($HTTP_USER_AGENT, 'PCC')) {
            $BROWSER_PLATFORM = 'Mac';
        } else if (strstr($HTTP_USER_AGENT,'Linux')) {
            $BROWSER_PLATFORM='Linux';
        } else if (strstr($HTTP_USER_AGENT,'Unix')) {
            $BROWSER_PLATFORM = 'Unix';
        } else if (strstr($HTTP_USER_AGENT,'FreeBSD')) {
            $BROWSER_PLATFORM = 'FreeBSD';
        } else if (strstr($HTTP_USER_AGENT,'SunOS')) {
            $BROWSER_PLATFORM = 'SunOS';
        } else if (strstr($HTTP_USER_AGENT,'IRIX')) {
            $BROWSER_PLATFORM = 'IRIX';
        } else if (strstr($HTTP_USER_AGENT,'BeOS')) {
            $BROWSER_PLATFORM = 'BeOS';
        } else if (strstr($HTTP_USER_AGENT,'OS/2')) {
            $BROWSER_PLATFORM = 'OS/2';
        } else if (strstr($HTTP_USER_AGENT,'AIX')) {
            $BROWSER_PLATFORM = 'AIX';
        } else {
            $BROWSER_PLATFORM='Other';
        }
    }

    require('inc/template_header.inc.php');

    echo '<center>Просмотр статистики';

    echo '<form action="" method="post">
    [<a href="?opt=main&option=stat"> Всего</a>  | <a href="?opt=main&option=stat&type=unique">Последние посещения</a> | <a href="?opt=main&option=stat&&type=toprefer">Топ посетителей</a> | <a href="?opt=main&option=stat&type=host">Статистика хостов</a> | <a href="?opt=main&option=stat&type=agent">Браузеры и ОС</a> | <a href="?opt=main&option=stat&type=daily">Хиты по дням</a>]</center><br>
    Просмотр статистики по игроку: <input id="name_v" type="text" size=30>&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Детально" onClick="location.href=\'admin.php?opt=main&option=stat&name_v=\'+document.getElementById(\'name_v\').value">&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Кратко" onClick="location.href=\'admin.php?opt=main&option=stat&name_v=\'+document.getElementById(\'name_v\').value+\'&priv\'">&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Пересечения игрока" onClick="location.href=\'admin.php?opt=main&option=stat&name_v=\'+document.getElementById(\'name_v\').value+\'&cros\'"><br>
    Просмотр статистики по IP: <input id="ip_v" type="text" size=20>&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Детально" onClick="location.href=\'admin.php?opt=main&option=stat&ip_v=\'+document.getElementById(\'ip_v\').value">&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Кратко" onClick="location.href=\'admin.php?opt=main&option=stat&ip_v=\'+document.getElementById(\'ip_v\').value+\'&priv\'"><br>
	Просмотр статистики по динамическому IP: <input id="ip_v1" type="text" size=20>&nbsp;&nbsp;&nbsp;<input id="ip_v2" type="text" size=20>&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Детально" onClick="location.href=\'admin.php?opt=main&option=stat&ip_v1=\'+document.getElementById(\'ip_v1\').value+\'&ip_v2=\'+document.getElementById(\'ip_v2\').value">&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Кратко" onClick="location.href=\'admin.php?opt=main&option=stat&ip_v1=\'+document.getElementById(\'ip_v1\').value+\'&ip_v2=\'+document.getElementById(\'ip_v2\').value+\'&priv\'"><br>
    Просмотр статистики по одинаковым IP: <input name="" type="button" value="Смотреть" onClick="location.href=\'admin.php?opt=main&option=stat&ip\'"></form><br><br><center>';

    if (isset($_REQUEST['type']))
    {
        $type = $_REQUEST['type'];
    }
    else
    {
        $type='';
    }

    switch (@$type) 
    {

        case 'daily':
			$result = myquery("SELECT DISTINCT day, COUNT(*) AS refcount FROM game_activity GROUP BY day ORDER BY day DESC");
			echo '
			Хиты по дням<br>
			<table cellspacing="5" cellpadding="0" border="0">
			<tr><td><font size="2" color="#eeeeee">Дата</font></td><td><font size="2" color="#eeeeee"><div align="right">Хиты</div></font></td></tr>';
			$i = 0;
			while ($report = mysql_fetch_array($result))
			{
				if ($i == 0)
				{
					echo '<tr><td><font size="1" color="#dddddd">' . $report['day'] . '</font></td><td><font size="1" color="#dddddd"><div align="right">' . $report['refcount'] . '</div></font></td></tr>';
					$i = 1;
				}
				elseif ($i == 1)
				{
					echo '<tr><td><font size="1" color="#999999">' . $report['day'] . '</font></td><td><font size="1" color="#999999"><div align="right">' . $report['refcount'] . '</div></font></td></tr>';
					$i = 0;
				}
			}
			echo '</table>';
		break;

        case 'agent':
			$result = myquery("SELECT DISTINCT agent, COUNT(*) AS refcount FROM game_activity GROUP BY host ORDER BY refcount DESC");
			echo '
			Статистика браузеров и ОС<br>
			<table cellspacing="5" cellpadding="0" border="0">
			<tr><td><font size="2" color="#eeeeee">Браузеры и ОС</font></td><td><font size="2" color="#eeeeee"><div align="right">Хиты</div></font></td></tr>';
			$i = 0;
			while ($report = mysql_fetch_array($result))
			{
				if ($i == 0)
				{
					echo '<tr><td><font size="1" color="#dddddd">' . $report['agent'] . '</font></td><td><font size="1" color="#dddddd"><div align="right">' . $report['refcount'] . '</div></font></td></tr>';
					$i = 1;
				}
				elseif ($i == 1)
				{
					echo '<tr><td><font size="1" color="#999999">' . $report['agent'] . '</font></td><td><font size="1" color="#999999"><div align="right">' . $report['refcount'] . '</div></font></td></tr>';
					$i = 0;
				}
			}
			echo '</table>';
		break;

        case 'host':
			$result = myquery("SELECT DISTINCT host, COUNT(*) AS refcount FROM game_activity GROUP BY host ORDER BY refcount DESC");
			echo '
			Статистика хостов<br>
			<table cellspacing="5" cellpadding="0" border="0">
			<tr><td><font size="2" color="#eeeeee">Хост</font></td><td><font size="2" color="#eeeeee"><div align="right">Хиты</div></font></td></tr>';
			$i = 0;
			while ($report = mysql_fetch_array($result))
			{
				if ($i == 0)
				{
					echo '<tr><td><font size="1" color="#dddddd">' . number2ip($report['host']) . '</font></td><td><font size="1" color="#dddddd"><div align="right">' . $report['refcount'] . '</div></font></td></tr>';
					$i = 1;
				}
				elseif ($i == 1)
				{
					echo '<tr><td><font size="1" color="#999999">' . number2ip($report['host']) . '</font></td><td><font size="1" color="#999999"><div align="right">' . $report['refcount'] . '</div></font></td></tr>';
					$i = 0;
				}
			}
			echo '</table>';
		break;

        case 'toprefer':
			$result = myquery("SELECT DISTINCT name, COUNT(*) AS refcount FROM game_activity GROUP BY name ORDER BY refcount DESC, name");
			echo '
			Топ посетителей<br>
			<table cellspacing="5" cellpadding="0" border="0">
			<tr><td><font size="2" color="#eeeeee">Больше всего посетили</font></td><td><font size="2" color="#eeeeee"><div align="right">хитов</div></font></td></tr>';
			$i = 0;
			while ($report = mysql_fetch_array($result))
			{
				if ($report['name'] != '')
				{

				if ($i == 0)
				{
					echo '<tr><td><font size="1" color="#dddddd">' . $report['name'] . '</font></td><td><font size="1" color="#dddddd"><div align="right">' . $report['refcount'] . '</div></font></td></tr>';
					$i = 1;
				}
				elseif ($i == 1)
				{
					echo '<tr><td><font size="1" color="#999999">' . $report['name'] . '</font></td><td><font size="1" color="#999999"><div align="right">' . $report['refcount'] . '</div></font></td></tr>';
					$i = 0;
				}

				}
			}
			echo '</table>';
		break;

        case 'del':
		    //$update=myquery("delete from game_activity");
	    break;

        case 'unique':
			$result = myquery("SELECT COUNT(*) FROM game_activity");
			$count = mysql_result($result,0,0);
			echo "Последние посещения: $count<br><br>";

			$query = "SELECT * FROM game_activity ORDER BY time DESC";
			$query1 = "SELECT COUNT(*) FROM game_activity ORDER BY time DESC";

        default:
        if (@$type != 'unique')
        {
            $result = myquery("SELECT COUNT(*) AS hits FROM game_activity");
            $countarray = mysql_fetch_array($result);
            $count = $countarray['hits'];
            echo "Всего посещений: $count<br><br>";
		    if(isset($name_v) and $name_v!='')
		    {
			    if (isset($priv))
				{
					$query = "SELECT max(day) AS day, max(time) AS time, host, name FROM game_activity WHERE name='$name_v' GROUP BY name, host";
				}
				elseif (isset($cros))
				{
					$query = "SELECT max(t1.day) AS day, max(t1.time) AS time, t1.host, t1.name 
							FROM game_activity as t1
							Join game_activity as t2 On t1.host=t2.host and t2.name='$name_v' and t1.name<>'$name_v'
							GROUP BY name, host";
				}
				else
				{
					$query = "SELECT * FROM game_activity WHERE name='$name_v' ORDER BY time DESC";
				}
		    }
		    elseif(isset($ip_v) and $ip_v!='')
		    {
			    $ip = ip2number($ip_v);
			    if (isset($priv))
				{
					$query = "SELECT max(day) AS day, max(time) AS time, host, name FROM game_activity WHERE host='$ip' GROUP BY name, host";
				}
				else
				{
					$query = "SELECT * FROM game_activity WHERE host='$ip' ORDER BY time DESC";
				}
		    }
			elseif(isset($ip_v1) and $ip_v1!='' and isset($ip_v2) and $ip_v2!='')
		    {
			    $ip1 = ip2number($ip_v1);
				$ip2 = ip2number($ip_v2);
			    if (isset($priv))
				{
					$query = "SELECT max(day) AS day, max(time) AS time, host, name FROM game_activity WHERE host>='$ip1' and host<='$ip2' GROUP BY name, host";
				}
				else
				{
					$query = "SELECT * FROM game_activity WHERE host>='$ip1' and host<='$ip2' ORDER BY time DESC";
				}
		    }
		    elseif (isset($ip))
		    {
			    if(isset($mhost) and $mhost!='')
			    {
				    echo 'Детализация мультов по ip:'.long2ip($mhost);
				    echo '<table cellspacing="0" cellpadding="2" border="1">';
				    echo '<tr style="color:white;text-weight:800;text-align:center;"><td>Имя</td><td>Последний хост</td><td>Дата последнего захода</td><td>Доп.Хост</td></tr>';
				    $query = "SELECT unif.*
                    FROM
                    (
                    (SELECT game_activity_mult.host_more,game_users.clevel,game_users_active.host,game_users_data.last_visit,game_activity_mult.name,game_users.clan_id,game_users.user_id FROM game_activity_mult,game_users,game_users_data,game_users_active WHERE game_users.user_id=game_users_data.user_id AND game_users.user_id=game_users_active.user_id AND game_users.name=game_activity_mult.name AND game_activity_mult.host='".$mhost."' GROUP BY game_activity_mult.host,game_activity_mult.host_more,game_activity_mult.name)
                    UNION
                    (SELECT game_activity_mult.host_more,game_users_archive.clevel,game_users_active.host,game_users_data.last_visit,game_activity_mult.name,game_users_archive.clan_id,game_users_archive.user_id FROM game_activity_mult,game_users_archive,game_users_data,game_users_active WHERE game_users_archive.user_id=game_users_data.user_id AND game_users_archive.user_id=game_users_active.user_id AND game_users_archive.name=game_activity_mult.name AND game_activity_mult.host='".$mhost."' GROUP BY game_activity_mult.host,game_activity_mult.host_more,game_activity_mult.name)
                    ) as unif
                    ORDER BY unif.clevel DESC";
				    $sel_ip = myquery($query);

				    if (!isset($page)) $page=1;
				    $page=(int)$page;
				    $line=30;
				    $allpage=ceil(mysql_num_rows($sel_ip)/$line);
				    if ($page>$allpage) $page=$allpage;
				    if ($page<1) $page=1;

				    $query.=" limit ".(($page-1)*$line).", $line";
				    $sel_ip = myquery($query);
				    while ($ch_ip = mysql_fetch_array($sel_ip))
				    {
					    echo '<tr><td>';
					    if($ch_ip['clan_id']>0) echo'<img src="/images/clan/'.$ch_ip['clan_id'].'.gif">';
					    echo '<a href="/view/?userid='.$ch_ip['user_id'].'" target="_blank">'.$ch_ip['name'].'</a> ['.$ch_ip['clevel'].']</td><td>'.long2ip($ch_ip['host']).'</td><td>'.date('d.m.Y : H:i:s', $ch_ip['last_visit']).'</td><td>'.$ch_ip['host_more'].'</td></tr>';

				    }
		            $href = "?opt=main&option=stat&mhost=".$mhost."&ip";
			    }
			    else
			    {
				    echo 'Проверка игроков по IP';
				    echo '<table cellspacing="0" cellpadding="2" border="1">';
				    echo '<tr style="color:white;text-weight:800;text-align:center;"><td>Хост</td><td>Доп. Хост</td><td>Количество разных игроков</td></tr>';
				    $query = "SELECT count(mult.name) as cnt,host,host_more FROM (SELECT host,host_more,name FROM game_activity_mult GROUP BY host,host_more,name) AS mult GROUP BY mult.host,mult.host_more HAVING count( mult.name ) >1 ORDER BY cnt DESC";
				    $sel_ip = myquery($query);

				    if (!isset($page)) $page=1;
				    $page=(int)$page;
				    $line=30;
				    $allpage=ceil(mysql_num_rows($sel_ip)/$line);
				    if ($page>$allpage) $page=$allpage;
				    if ($page<1) $page=1;

				    $query.=" limit ".(($page-1)*$line).", $line";
				    $sel_ip = myquery($query);
				    while ($ch_ip = mysql_fetch_array($sel_ip))
				    {
					    echo '<tr><td><a href="admin.php?opt=main&option=stat&mhost='.$ch_ip['host'].'&ip">'.number2ip($ch_ip['host']).'</a></td><td>'.$ch_ip['host_more'].'</td><td>'.$ch_ip['cnt'].'</td></tr>';

				    }
		            $href = "?opt=main&option=stat&ip";
			    }
			    echo '</table>';
	            echo'<center>Страница: ';
                show_page($page,$allpage,$href);
		    }
		    else
		    {
			    $query = "SELECT * FROM game_activity ORDER BY time DESC";
		    }
        }

        if (!isset($_GET['ip']))
        {
            $result = myquery($query);
            if ($result!=false AND mysql_num_rows($result))
            { 
                if (!isset($page)) $page=1;
                $page=(int)$page;
                $line=25;
                $allpage=ceil(mysql_num_rows($result)/$line);
                if ($page>$allpage) $page=$allpage;
                if ($page<1) $page=1;

                $query.=" limit ".(($page-1)*$line).", $line";
                $result = myquery($query);
				
				echo 'Последние посещения<br>
					  <table cellspacing="1" cellpadding="0" border="1">';
				if (isset($priv) or isset($cros))
				{
					echo'<tr align="center">
					<td width="120"><font size="2" color="#eeeeee">Игрок</font></td>
					<td width="80"><font size="2" color="#eeeeee">Хост</font></td>
					<td width="100"><font size="2" color="#eeeeee">Дата</font></font></td>
					<td width="70"><font size="2" color="#eeeeee">Время</font></font></td>
					</tr>';
					
					$i = 0;
					while ($report = mysql_fetch_array($result))
					{
						$analyseDate = explode("-", $report['day']);
						$reportDay = $analyseDate[2] . ' ';
						switch ($analyseDate[1])
						{
							case '01':
								$reportDay .= 'Янв'; break;
							case '02':
								$reportDay .= 'Фев'; break;
							case '03':
								$reportDay .= 'Мар'; break;
							case '04':
								$reportDay .= 'Апр'; break;
							case '05':
								$reportDay .= 'Май'; break;
							case '06':
								$reportDay .= 'Июн'; break;
							case '07':
								$reportDay .= 'Июл'; break;
							case '08':
								$reportDay .= 'Авг'; break;
							case '09':
								$reportDay .= 'Сен'; break;
							case '10':
								$reportDay .= 'Окт'; break;
							case '11':
								$reportDay .= 'Ноя'; break;
							case '12':
								$reportDay .= 'Дек'; break;
						}
						$reportDay .= ' ' . $analyseDate[0];
						$reportHour = date("H:i",$report['time']);

						if ($i == 0)
						{
							echo '<tr align="center"><td><font size="1" color="#dddddd">' . $report['name'] . '</font></td><td><font size="1" color="#dddddd">' . number2ip($report['host']). '</font></td><td><font size="1" color="#dddddd">' . $reportDay . '</font></td><td><font size="1" color="#dddddd">' . $reportHour . '</font></td></tr>';
							$i = 1;
						}
						elseif ($i == 1)
						{
							echo '<tr align="center"><td><font size="1" color="#999999">' . $report['name'] . '</font></td><td><font size="1" color="#999999">' . number2ip($report['host']) . '</font></td><td><font size="1" color="#999999">' . $reportDay . '</font></td><td><font size="1" color="#999999">' . $reportHour . '</font></td></tr>';
							$i = 0;
						}
					}
				}
				else
				{
				
					echo '
					<tr align="center">
					<td><font size="2" color="#eeeeee">Дата</font></td>
					<td><font size="2" color="#eeeeee">Время</font></td>
					<td><font size="2" color="#eeeeee">ОС</font></font></td>
					<td><font size="2" color="#eeeeee">Тип</font></td>
					<td><font size="2" color="#eeeeee">Браузер</font></td>
					<td><font size="2" color="#eeeeee"><div align="center">Версия</div></font></td>
					<td><font size="2" color="#eeeeee">Хост</font></td>
					<td><font size="2" color="#eeeeee">Линк</font></td>
					<td><font size="2" color="#eeeeee">Игрок</font></td>
					</tr>';
					$i = 0;
					while ($report = mysql_fetch_array($result))
					{
						GetBrowser($report['agent']);
						GetPlatform($report['agent']);

						$analyseDate = explode("-", $report['day']);
						$reportDay = $analyseDate[2] . ' ';
						switch ($analyseDate[1])
						{
							case '01':
								$reportDay .= 'Янв'; break;
							case '02':
								$reportDay .= 'Фев'; break;
							case '03':
								$reportDay .= 'Мар'; break;
							case '04':
								$reportDay .= 'Апр'; break;
							case '05':
								$reportDay .= 'Май'; break;
							case '06':
								$reportDay .= 'Июн'; break;
							case '07':
								$reportDay .= 'Июл'; break;
							case '08':
								$reportDay .= 'Авг'; break;
							case '09':
								$reportDay .= 'Сен'; break;
							case '10':
								$reportDay .= 'Окт'; break;
							case '11':
								$reportDay .= 'Ноя'; break;
							case '12':
								$reportDay .= 'Дек'; break;
						}
						$reportDay .= ' ' . $analyseDate[0];
						$reportHour = date("H:i",$report['time']);

						if ($i == 0)
						{
							echo '<tr align="center"><td><font size="1" color="#dddddd">' . $reportDay . '</font></td><td><font size="1" color="#dddddd">' . $reportHour . '</font></td><td><font size="1" color="#dddddd">' . $BROWSER_PLATFORM . '</font></td><td><font size="1" color="#dddddd">' . $BROWSER_TYPE . '</font></td><td><font size="1" color="#dddddd">' . $BROWSER_AGENT . '</font></td><td><font size="1" color="#dddddd"><div align="center">' . $BROWSER_VER . '</div></font></td><td><font size="1" color="#dddddd">' . number2ip($report['host']). '</font></td><td><font size="1" color="#dddddd">' . $report['ref'] . '</font></td><td><font size="1" color="#dddddd">' . $report['name'] . '</font></td></tr>';
							$i = 1;
						}
						elseif ($i == 1)
						{
							echo '<tr align="center"><td><font size="1" color="#999999">' . $reportDay . '</font></td><td><font size="1" color="#999999">' . $reportHour . '</font></td><td><font size="1" color="#999999">' . $BROWSER_PLATFORM . '</font></td><td><font size="1" color="#999999">' . $BROWSER_TYPE . '</font></td><td><font size="1" color="#999999">' . $BROWSER_AGENT . '</font></td><td><font size="1" color="#999999"><div align="center">' . $BROWSER_VER . '</div></font></td><td><font size="1" color="#999999">' . number2ip($report['host']) . '</font></td><td><font size="1" color="#999999">' . $report['ref'] . '</font></td><td><font size="1" color="#999999">' . $report['name'] . '</font></td></tr>';
							$i = 0;
						}
					}
				}
				echo '</table>';
               
                if (isset($type) AND $type == 'unique')
	                $href = "?opt=main&option=stat&type=unique";
                else
                {
                    if(isset($name_v) and $name_v!='')
                    {
		                $href = "?opt=main&option=stat&name_v=$name_v";
                    }
                    elseif(isset($ip_v) and $ip_v!='')
	                {
		                $href = "?opt=main&option=stat&ip_v=$ip_v";
	                }
					elseif(isset($ip_v1) and $ip_v1!='' and isset($ip_v2) and $ip_v2!='')
	                {
		                $href = "?opt=main&option=stat&ip_v1=$ip_v1&ip_v2=$ip_v2";
	                }
					/*elseif(isset($ip_v1) and $ip_v1!='')
	                {
		                $href = "?opt=main&option=stat&ip_v1=$ip_v1";
	                }*/
	                else
	                {
		                $href = "?opt=main&option=stat";
	                }
					if (isset($priv))
					{
						$href=$href.'&priv';
					}
					elseif (isset($cros))
					{
						$href=$href.'&cros';
					}
                }
                echo'<center>Страница: ';
                show_page($page,$allpage,$href);
            }
        }
    }
}

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
    show_debug();
}

if (function_exists("save_debug")) save_debug(); 

?>