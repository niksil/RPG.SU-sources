<?

if (function_exists("start_debug")) start_debug(); 

if ($build_user==$user_id)
{
	if (!isset($see))
	{
		$select=myquery("select * from craft_build_user where user_id='$user_id' and map=".$char['map_name']." and x='".$char['map_xpos']."' and y='".$char['map_ypos']."'");
		$usr=mysql_fetch_array($select);
		echo'<form action="" method="post">Выставить здание на продажу за: ';
		echo "<input type=text name=sell value=$usr[sell] size=10> ";
		echo "<input name=see type=submit value=Ок>";
	}
	else
	{
		$sell=(int)$sell;
		myquery("update craft_build_user set sell='$sell' where user_id='$user_id' and map=".$char['map_name']." and x='".$char['map_xpos']."' and y='".$char['map_ypos']."'");
		echo'Готово';
	}
}

if (function_exists("save_debug")) save_debug(); 

?>