<?

if (function_exists("start_debug")) start_debug(); 

if ($build_user==$user_id)
{
	myquery("UPDATE craft_build_user SET sell=0 WHERE user_id=$user_id AND map=".$char['map_name']." AND x=".$char['map_xpos']." AND y=".$char['map_ypos']."");
	echo 'Возвращено с торгов';
}
else
{
	if ($char['GP']>=$build_sell)
	{
		myquery("UPDATE game_users SET GP=GP-".$build_sell.",CW=CW-".($build_sell*money_weight)." WHERE user_id=$user_id");
        setGP($user_id,-$build_sell,16);
		myquery("UPDATE game_users SET GP=GP+".$build_sell.",CW=CW+".($build_sell*money_weight)." WHERE user_id=$build_user");
        setGP($build_user,$build_sell,17);
		myquery("UPDATE craft_build_user SET user_id=$user_id WHERE user_id=$build_user AND map=".$char['map_name']." AND x=".$char['map_xpos']." AND y=".$char['map_ypos']."");
		echo'Куплено';
	}
	else
	{
		echo'У тебя не хватает денег';	
	}
}

if (function_exists("save_debug")) save_debug(); 

?>