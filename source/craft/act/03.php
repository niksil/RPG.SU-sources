<?

if (function_exists("start_debug")) start_debug(); 

if ($build_user==$user_id)
{
	$select=myquery("select * from craft_build where id='".$build_type."'");
	if (mysql_num_rows($select))
	{
		$building=mysql_fetch_array($select);
		echo" $building[name] <br>";
		echo" Количество мест: $building[col] <br><br> Здание приносит: <br>";
		dohod($building['res_dob'],0);
		echo" <br>Ты платишь за работу:<br>";
		dohod($build_dohod, $build_gold);
	}
}

if (function_exists("save_debug")) save_debug(); 

?>