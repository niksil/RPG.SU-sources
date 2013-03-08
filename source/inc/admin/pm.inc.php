<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['pm'] >= 1)
{
	if (isset($all))
	{
		echo '<center>Последние сообщения: </center><br><br>';
		
		$pm=myquery("select count(*) from game_pm where komu!='1' and otkogo>0 and otkogo!='1' and komu!='612' and otkogo!='612' and view!=3 and clan!=1 order by `time` DESC");
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=25;
		$allpage=ceil(mysql_result($pm,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		$otkogo = 0;
		if (isset($naf)) $otkogo = -100;
		$pm=myquery("select * from game_pm where komu!='1' and otkogo>$otkogo and otkogo!='1' and komu!='612' and otkogo!='612' and view!=3 and clan!=1 order by `time` DESC  limit ".(($page-1)*$line).", $line");
		echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>Кому</td><td>От кого</td><td>Когда</td><td>Тема</td><td>Сообщение</td></tr>';
		while($p=mysql_fetch_array($pm))
		{
			$komu = get_user("name",$p['komu']);
			$otkogo = get_user("name",$p['otkogo']);
 
			$p['post']=str_replace('[quote]','<hr>',$p['post']);
			$p['post']=str_replace('[/quote]','<hr>',$p['post']);

			$p['post']=str_replace('quote]','<hr>',$p['post']);
			$p['post']=str_replace('/quote]','<hr>',$p['post']);

			$p['post']=str_replace('[quote','<hr>',$p['post']);
			$p['post']=str_replace('[/quote','<hr>',$p['post']);

			$p['post']=str_replace('quote','<hr>',$p['post']);
			$p['post']=str_replace('/quote','<hr>',$p['post']);

			$p['post']=str_replace('[b]','<b>',$p['post']);
			$p['post']=str_replace('[/b]','</b>',$p['post']);

			$p['post']=str_replace('<br />','<br>',$p['post']);

			$p['post']=str_replace('[i]','<i>',$p['post']);
			$p['post']=str_replace('[/i]','</i>',$p['post']);

			$p['post']=str_replace('[u]','<u>',$p['post']);
			$p['post']=str_replace('[/u]','</u>',$p['post']);
				echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
		}
		echo'<tr><td colspan=5>';
		$href = 'admin.php?opt=main&option=pm&all&';
		if (isset($naf))
		{
			$href.='naf&';
		}
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
		echo '</td></tr>';
		echo'</table>';
	}

	if (isset($del))
	{
		echo '<center>Последние удаленные сообщения: </center><br><br>';
		
		$pm=myquery("select count(*) from game_pm_deleted where komu!='1' and otkogo>0 and otkogo!='1' and komu!='612' and otkogo!='612' and view!=3 and clan!=1 order by `time` DESC");
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=25;
		$allpage=ceil(mysql_result($pm,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		$otkogo = 0;
		if (isset($naf)) $otkogo = -100;
		$pm=myquery("select * from game_pm_deleted where komu!='1' and otkogo>$otkogo and otkogo!='1' and komu!='612' and otkogo!='612' and view!=3 and clan!=1 order by `time` DESC limit ".(($page-1)*$line).", $line");
		echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>Кому</td><td>От кого</td><td>Когда</td><td>Тема</td><td>Сообщение</td><td></td></tr>';
		while($p=mysql_fetch_array($pm))
		{
			$komu = get_user("name",$p['komu']);
			$otkogo = get_user("name",$p['otkogo']);
			
			$p['post']=str_replace('[quote]','<hr>',$p['post']);
			$p['post']=str_replace('[/quote]','<hr>',$p['post']);

			$p['post']=str_replace('quote]','<hr>',$p['post']);
			$p['post']=str_replace('/quote]','<hr>',$p['post']);

			$p['post']=str_replace('[quote','<hr>',$p['post']);
			$p['post']=str_replace('[/quote','<hr>',$p['post']);

			$p['post']=str_replace('quote','<hr>',$p['post']);
			$p['post']=str_replace('/quote','<hr>',$p['post']);

			$p['post']=str_replace('[b]','<b>',$p['post']);
			$p['post']=str_replace('[/b]','</b>',$p['post']);

			$p['post']=str_replace('[i]','<i>',$p['post']);
			$p['post']=str_replace('[/i]','</i>',$p['post']);

			$p['post']=str_replace('[u]','<u>',$p['post']);
			$p['post']=str_replace('[/u]','</u>',$p['post']);
			echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
		}
		echo'<tr><td colspan=5>';
		$href = 'admin.php?opt=main&option=pm&del&';
		if (isset($naf))
		{
			$href.='naf&';
		}
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
		echo '</td></tr>';
		echo'</table>';
	}

	if (isset($userr))
	{
		echo'<div id="content" onclick="hideSuggestions();"><center>Почта игрока:<br><br><font size="1" face="Verdana" color="#ffffff">Поиск: <input type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
<input name="" type="button" value="Найти" onClick="location.href=\'admin.php?opt=main&option=pm&userr&user_name=\'+document.getElementById(\'keyword\').value"></div><script>init();</script>';

		if(isset($user_name))
		{
			echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>Кому</td><td>От кого</td><td>Когда</td><td>Тема</td><td>Сообщение</td><td></td></tr>';
			$usrid = get_user("user_id",$user_name,1);

			$otkogo = 0;
			if (isset($naf)) $otkogo = -100;
			
			$usr=myquery("select count(*)  from game_pm where komu='$usrid' and komu!='1' and otkogo>$otkogo and komu!='612' and view!=3 order by `time` desc");
			if (!isset($page)) $page=1;
			$page=(int)$page;
			$line=25;
			$allpage=ceil(mysql_result($usr,0,0)/$line);
			if ($page>$allpage) $page=$allpage;
			if ($page<1) $page=1;

			$usr=myquery("select *  from game_pm where komu='$usrid' and komu!='1' and otkogo>$otkogo and komu!='612' and view!=3 order by `time` desc limit ".(($page-1)*$line).", $line");

			while($p=mysql_fetch_array($usr))
			{
				$komu = get_user("name",$p['komu']);
				$otkogo = get_user("name",$p['otkogo']);
				
				$p['post']=str_replace('[quote]','<hr>',$p['post']);
				$p['post']=str_replace('[/quote]','<hr>',$p['post']);

				$p['post']=str_replace('quote]','<hr>',$p['post']);
				$p['post']=str_replace('/quote]','<hr>',$p['post']);

				$p['post']=str_replace('[quote','<hr>',$p['post']);
				$p['post']=str_replace('[/quote','<hr>',$p['post']);

				$p['post']=str_replace('quote','<hr>',$p['post']);
				$p['post']=str_replace('/quote','<hr>',$p['post']);

				$p['post']=str_replace('[b]','<b>',$p['post']);
				$p['post']=str_replace('[/b]','</b>',$p['post']);

				$p['post']=str_replace('[i]','<i>',$p['post']);
				$p['post']=str_replace('[/i]','</i>',$p['post']);

				$p['post']=str_replace('[u]','<u>',$p['post']);
				$p['post']=str_replace('[/u]','</u>',$p['post']);
				echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
			}
			echo'<tr><td colspan=5>';
			$href = 'admin.php?opt=main&option=pm&userr&user_name='.$user_name.'&';
			if (isset($naf))
			{
				$href.='naf&';
			}
			echo'<center>Страница: ';
			show_page($page,$allpage,$href);
			echo '</td></tr>';
			echo'</table>';
		}
	}

	if (isset($userr_del))
	{
		echo'<div id="content" onclick="hideSuggestions();"><center>Удаленная почта игрока:<br><br><font size="1" face="Verdana" color="#ffffff">Поиск: <input type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
<input name="" type="button" value="Найти" onClick="location.href=\'admin.php?opt=main&option=pm&userr_del&user_name=\'+document.getElementById(\'keyword\').value"></div><script>init();</script>';

		if(isset($user_name))
		{
			echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>Кому</td><td>От кого</td><td>Когда</td><td>Тема</td><td>Сообщение</td><td></td></tr>';
			$usrid = get_user("user_id",$user_name,1);
			
			$otkogo = 0;
			if (isset($naf)) $otkogo = -100;
			
			$usr=myquery("select count(*)  from game_pm_deleted where komu='$usrid' and otkogo>$otkogo and komu!='1' and komu!='612' and view!=3 order by `time` desc");
			if (!isset($page)) $page=1;
			$page=(int)$page;
			$line=25;
			$allpage=ceil(mysql_result($usr,0,0)/$line);
			if ($page>$allpage) $page=$allpage;
			if ($page<1) $page=1;

			$usr=myquery("select *  from game_pm_deleted where komu='$usrid' and komu!='1' and otkogo>$otkogo and komu!='612' and view!=3 order by `time` desc limit ".(($page-1)*$line).", $line");

			while($p=mysql_fetch_array($usr))
			{
				$komu = get_user("name",$p['komu']);
				$otkogo = get_user("name",$p['otkogo']);
				
				$p['post']=str_replace('[quote]','<hr>',$p['post']);
				$p['post']=str_replace('[/quote]','<hr>',$p['post']);

				$p['post']=str_replace('quote]','<hr>',$p['post']);
				$p['post']=str_replace('/quote]','<hr>',$p['post']);

				$p['post']=str_replace('[quote','<hr>',$p['post']);
				$p['post']=str_replace('[/quote','<hr>',$p['post']);

				$p['post']=str_replace('quote','<hr>',$p['post']);
				$p['post']=str_replace('/quote','<hr>',$p['post']);

				$p['post']=str_replace('[b]','<b>',$p['post']);
				$p['post']=str_replace('[/b]','</b>',$p['post']);

				$p['post']=str_replace('[i]','<i>',$p['post']);
				$p['post']=str_replace('[/i]','</i>',$p['post']);

				$p['post']=str_replace('[u]','<u>',$p['post']);
				$p['post']=str_replace('[/u]','</u>',$p['post']);
				echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
			}
			echo'<tr><td colspan=5>';
			$href = 'admin.php?opt=main&option=pm&userr_del&user_name='.$user_name.'&';
			if (isset($naf))
			{
				$href.='naf&';
			}
			echo'<center>Страница: ';
			show_page($page,$allpage,$href);
			echo '</td></tr>';
			echo'</table>';
		}
	}

	if (isset($userr_sent))
	{
		echo'<div id="content" onclick="hideSuggestions();"><center>Отправленная почта игрока:<br><br><font size="1" face="Verdana" color="#ffffff">Поиск: <input type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
<input name="" type="button" value="Найти" onClick="location.href=\'admin.php?opt=main&option=pm&userr_sent&user_name=\'+document.getElementById(\'keyword\').value"></div><script>init();</script>';

		if(isset($user_name))
		{
			echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>Кому</td><td>От кого</td><td>Когда</td><td>Тема</td><td>Сообщение</td><td></td></tr>';
			$otkogo = 0;
			if (isset($naf)) $otkogo = -100;
			
			$usrid = get_user("user_id",$user_name,1);
			$usr=myquery("select count(*)  from game_pm where otkogo='$usrid' and komu!='1' and otkogo>$otkogo  and komu!='612' and view=3 order by `time` desc");
			if (!isset($page)) $page=1;
			$page=(int)$page;
			$line=25;
			$allpage=ceil(mysql_result($usr,0,0)/$line);
			if ($page>$allpage) $page=$allpage;
			if ($page<1) $page=1;

			$usr=myquery("select *  from game_pm where otkogo='$usrid' and komu!='1'  and otkogo>$otkogo and komu!='612' and view=3 order by `time` desc limit ".(($page-1)*$line).", $line");

			while($p=mysql_fetch_array($usr))
			{
				$komu = get_user("name",$p['komu']);
				$otkogo = get_user("name",$p['otkogo']);
				
				$p['post']=str_replace('[quote]','<hr>',$p['post']);
				$p['post']=str_replace('[/quote]','<hr>',$p['post']);

				$p['post']=str_replace('quote]','<hr>',$p['post']);
				$p['post']=str_replace('/quote]','<hr>',$p['post']);

				$p['post']=str_replace('[quote','<hr>',$p['post']);
				$p['post']=str_replace('[/quote','<hr>',$p['post']);

				$p['post']=str_replace('quote','<hr>',$p['post']);
				$p['post']=str_replace('/quote','<hr>',$p['post']);

				$p['post']=str_replace('[b]','<b>',$p['post']);
				$p['post']=str_replace('[/b]','</b>',$p['post']);

				$p['post']=str_replace('[i]','<i>',$p['post']);
				$p['post']=str_replace('[/i]','</i>',$p['post']);

				$p['post']=str_replace('[u]','<u>',$p['post']);
				$p['post']=str_replace('[/u]','</u>',$p['post']);
				echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
			}
			echo'<tr><td colspan=5>';
			$href = 'admin.php?opt=main&option=pm&userr_sent&user_name='.$user_name.'&';
			if (isset($naf))
			{
				$href.='naf&';
			}
			echo'<center>Страница: ';
			show_page($page,$allpage,$href);
			echo '</td></tr>';
			echo'</table>';
		}
	}

	echo'<br><hr><table><tr><td>[<a href="?opt=main&option=pm&all">Последние 150 сообщений</a>]</td><td>[<a href="?opt=main&option=pm&del">Последние 150  удаленных сообщений</a>]</td><td>[<a href="?opt=main&option=pm&userr">Почта игрока</a>]</td><td>[<a href="?opt=main&option=pm&userr_del">Удаленная почта игрока</a>]</td><td>[<a href="?opt=main&option=pm&userr_sent">Отправленная почта игрока</a>]</td></tr>
	<tr><td>[<a href="?opt=main&option=pm&all&naf">Последние 150 сообщений cо служебными</a>]</td><td>[<a href="?opt=main&option=pm&del&naf">Последние 150  удаленных сообщений со служебными</a>]</td><td>[<a href="?opt=main&option=pm&userr&naf">Почта игрока со служебными</a>]</td><td>[<a href="?opt=main&option=pm&userr_del&naf">Удаленная почта игрока со служебными</a>]</td><td></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 
?>