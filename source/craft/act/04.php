<?

if (function_exists("start_debug")) start_debug(); 

if ($build_user==$user_id)
{
	if (!isset($see))
	{
		$select=myquery("select * from craft_resource_user where user_id='$user_id'");
		echo'<form action="" method="post">Выплачивать по<br>';
		$i=0;
		while($res=mysql_fetch_array($select))
		{
			$selec=myquery("select * from craft_resource where id='".$res['res_id']."'");
			$build=mysql_fetch_array($selec);
			echo "$build[name] - <input type=hidden name=re[$i] value=$res[res_id]><input type=text name=col[$i] size=3> ед. в час<br>";
			$i++;
		}
		echo "<input type=text name=gp size=3> золотых в час<br><input name=see type=submit value=Сохранить>";
	}
	else
	{
		$dohod='';
		if (isset($re))
		{	
			while (list($a,$d)=each($re) and list($aa,$dd)=each($col))
			{
				$d=(int)$d;
				if ($dd>=1)
				{
					$dohod.="$d-$dd|";
				}
			}
		}
		$dohod = substr("$dohod", 0, -1);
        $gp=(int)$gp;
		echo'Выплаты изменены';
		myquery("update craft_build_user set dohod='$dohod', gold=$gp where user_id=$user_id and x=".$char['map_xpos']." and y=".$char['map_ypos']." and map=".$char['map_name']."");
	}
}

if (function_exists("save_debug")) save_debug(); 

?>