<?
function dohod($dohod, $build_gold)
{
	if ($dohod!='')
	{
		$a=explode("|",$dohod);
		for ($i=0;$i<count($a);$i++)
		{
			$b=explode("-",$a[$i]);
			$select=myquery("select * from craft_resource where id='$b[0]'");
			$build=mysql_fetch_array($select);
			echo "<font color=red>$build[name]</font> - <font color=red>$b[1]</font> ед.";
			echo '<br>';
		}
	}
	if ($build_gold>='1')
	{
		echo "<font color=red>$build_gold</font> золотых";
 	}
	echo'</b>';
}


function treb($dohod)
{
	if ($dohod!='')
	$a=explode("|",$dohod);
	for ($i=0;$i<count($a);$i++)
	{
		$b=explode("-",$a[$i]);
		$select=myquery("select * from craft_resource where id='$b[0]'");
		$build=mysql_fetch_array($select);
		echo "$build[name] - $b[1] ед.";
		echo '<br>';
	}
	echo'</b>';
}


function user($user)
{
	$select=myquery("select * from game_users where user_id='$user'");
	$usr=mysql_fetch_array($select);
	echo "<a href=http://".DOMAIN."/view/?userid=".$user." target=_blank>$usr[name]</a>";
}

function rab($build)
{
	$select=myquery("select * from craft_build_rab where build_id='$build' and (date_rab+dlit)>=".time()."");
	$num=mysql_num_rows($select);
	$usr=mysql_fetch_array($select);
	echo $num;
}

function rab_names($build)
{
	$select=myquery("select * from craft_build_rab where build_id='$build' and (date_rab+dlit)>=".time()."");
    $n = mysql_num_rows($select);
    $i = 0;
	if ($n>0)
	{
		echo'(';
		while($usr=mysql_fetch_array($select))
		{
            $i++;
			user($usr['user_id']);
            if ($i<$n)
            {
			    echo', ';
            }
		}
		echo')';
	}
}

?>