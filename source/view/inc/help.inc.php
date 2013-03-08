<?

if (function_exists("start_debug")) start_debug(); 

if ($help!='')
{
$help=(int)$help;
$q=myquery("select kateg,name,text from game_help where id='$help'");
$h=mysql_fetch_array($q);
echo '<li><b><a href="?help">'.$h['kateg'].'</a> > '.$h['name'].' </b></li><ol>';
echo'<li>'.$h['text'].'</li>';
}


if ($help=='')
{
echo '<table border=0 width=90%><td><td align=left>';
$q=myquery("select DISTINCT kateg from game_help order by id");
while($h=mysql_fetch_array($q))
	{
	echo'<li><b>'.$h['kateg'].'</li><ol>';
	$qq=myquery("select id, name from game_help where kateg='".$h['kateg']."'");
	while($hh=mysql_fetch_array($qq))
		{
		echo'<li><a href="?help='.$hh['id'].'">'.$hh['name'].'</a></li>';
		}
	echo'</ol>';
	}
echo'</td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>