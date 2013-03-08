<?php

if (function_exists("start_debug")) start_debug(); 

if (isset($card))
{
	list($st) = mysql_fetch_array(myquery("SELECT func FROM game_users WHERE user_id='$user_id'"));
	if ($st!='arcomage_wait')
	{
	    list($hod)=mysql_fetch_array(myquery("SELECT hod FROM arcomage WHERE id = '".$char['arcomage']."'"));
	    $layer=myquery("select * from arcomage_users where user_id='$user_id' AND arcomage_id='".$char['arcomage']."'");
	    $charboy=mysql_fetch_array($layer);
	    $check = mysql_result(myquery("SELECT COUNT(*) FROM arcomage_users_cards WHERE user_id='$user_id' AND arcomage_id='".$char['arcomage']."' AND card_id='$card'"),0,0);
	    if ($check==1)
	    {
	        if (!isset($fall) and check_dostup($card,$charboy)==1)
	        {
	            myquery("INSERT INTO arcomage_history (arcomage_id,user_id,card_id,hod) VALUES ('".$char['arcomage']."','$user_id','$card','$hod')") or die(mysql_error());
	        }
	        //удалим у игрока сходвишую карту
	        myquery("DELETE FROM arcomage_users_cards WHERE user_id='$user_id' AND arcomage_id='".$char['arcomage']."' AND card_id='$card'") or die(mysql_error());
	        //дадим игроку новую карту
	        $new_card = get_new_card($charboy);
	        myquery("INSERT INTO arcomage_users_cards (arcomage_id,user_id,card_id) VALUES ('".$char['arcomage']."','$user_id','$new_card')") or die(mysql_error());
	        if (extra_hod($card)==1)
	        {
	            myquery("UPDATE game_users SET func='arcomage_boy' WHERE user_id='$user_id'") or die(mysql_error());
	        }
	        else
			{
				myquery("UPDATE game_users SET func='arcomage_wait',hod=".time()." WHERE user_id='$user_id'") or die(mysql_error());
			}
	    }
	}
}

if (function_exists("save_debug")) save_debug(); 

header("location:?type=arcomage_boy");
{if (function_exists("save_debug")) save_debug(); exit;}
?>