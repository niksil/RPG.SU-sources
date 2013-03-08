<?php

if (function_exists("start_debug")) start_debug(); 

if (isset($_GET['card']))
{
	if ($charboy['func']==1)
	{
	    $check = mysql_result(myquery("SELECT COUNT(*) FROM arcomage_users_cards WHERE user_id='$user_id' AND arcomage_id='".$charboy['arcomage_id']."' AND card_id='$card'"),0,0);
        
        if ((isset($_GET['fall'])) AND ($_GET['card']==55))
        {
            $check = 0;
        }
        
	    if ($check==1)
	    {
            $hod = $arcomage['hod'];
            
            $sel_hod = myquery("SELECT * FROM arcomage_history WHERE arcomage_id='".$arcomage['id']."' AND user_id='$user_id' AND hod='$hod'");
            $cards2end = 1;
            // calculate history            
            while (($h_card = mysql_fetch_array($sel_hod)) AND ($cards2end > 0))
            {
                if ($h_card['fall'] == 1)
                    $cards2end -= 1;
                else
                {
                    $cards2end -= 1;
                    $cards2end += extra_hod($h_card['card_id']);
                }
            }
            // and last card (not inseted into DB yet)
            $cards2end -= 1 - (1 - isset($_GET['fall'])) * extra_hod($card);
            
            if ($cards2end < 1)
            {
                myquery("UPDATE arcomage_users SET func=2,hod=".time()." WHERE user_id='$user_id'");
                setLocation("arcomage.php");
            }
            
            if(isset($_GET['fall']))
            {
                myquery("INSERT INTO arcomage_history (arcomage_id,user_id,card_id,fall,hod) VALUES ('".$charboy['arcomage_id']."','$user_id','$card',1,'$hod')");
            }
            elseif (!isset($_GET['fall']) and check_dostup($card,$charboy)==1)
	        {

	            myquery("INSERT INTO arcomage_history (arcomage_id,user_id,card_id,fall,hod) VALUES ('".$charboy['arcomage_id']."','$user_id','$card',0,'$hod')");
	            if ($cards2end >= 0)
                {
                    $users=array();
                    $sel_init = myquery("SELECT * FROM arcomage_users WHERE arcomage_id='".$charboy['arcomage_id']."'");
                    while($userboy=mysql_fetch_array($sel_init))
                    {
                        $q = $userboy['user_id'];
                        $users[$q]['tower']         =$userboy['tower'];
                        $users[$q]['wall']          =$userboy['wall'];
                        $users[$q]['bricks']        =$userboy['bricks'];
                        $users[$q]['bricks_add']    =$userboy['bricks_add'];
                        $users[$q]['gems']          =$userboy['gems'];
                        $users[$q]['gems_add']      =$userboy['gems_add'];
                        $users[$q]['monsters']      =$userboy['monsters'];
                        $users[$q]['monsters_add']  =$userboy['monsters_add'];
                        $users[$q]['win']           =0;
                        $users[$q]['lose']          =0;
                        $users[$q]['hod']           =0;
                    }
/*
                    $users[$user_id]['tower']        = 0;
                    $users[$user_id]['wall']         = 0;
                    $users[$user_id]['bricks']       = 0;
                    $users[$user_id]['bricks_add']   = 0;
                    $users[$user_id]['gems']         = 0;
                    $users[$user_id]['gems_add']     = 0;
                    $users[$user_id]['monsters']     = 0;
                    $users[$user_id]['monsters_add'] = 0;
                    $users[$user_id]['win']          = 0;
                    $users[$user_id]['lose']         = 0;
                    $users[$user_id]['hod']          = 0;
*/                    
                    $users[0]['tower']        = 0;
                    $users[0]['wall']         = 0;
                    $users[0]['bricks']       = 0;
                    $users[0]['bricks_add']   = 0;
                    $users[0]['gems']         = 0;
                    $users[0]['gems_add']     = 0;
                    $users[0]['monsters']     = 0;
                    $users[0]['monsters_add'] = 0;
                    $users[0]['win']          = 0;
                    $users[0]['lose']         = 0;
                    $users[0]['hod']          = 0;
                    
                    $kogo = 0;
                    if ($arcomage['user1'] == $user_id)
                      $kogo = $arcomage['user2'];
                    else
                      $kogo = $arcomage['user1'];


                    make_action_card($users, $user_id, $kogo, $card);
                    myquery("UPDATE arcomage_users SET bricks=".$users[$user_id]['bricks'].",gems=".$users[$user_id]['gems'].",monsters=".$users[$user_id]['monsters']." WHERE user_id=$user_id");
                    $charboy['bricks']   = $users[$user_id]['bricks'];
                    $charboy['gems']     = $users[$user_id]['gems'];
                    $charboy['monsters'] = $users[$user_id]['monsters'];
                }
            }

	        //удалим у игрока сходвишую карту
	        myquery("DELETE FROM arcomage_users_cards WHERE user_id='$user_id' AND arcomage_id='".$charboy['arcomage_id']."' AND card_id='$card'");
	        //дадим игроку новую карту
	        $new_card = get_new_card($charboy, $hod);
	        myquery("INSERT INTO arcomage_users_cards (arcomage_id,user_id,card_id) VALUES ('".$charboy['arcomage_id']."','$user_id','$new_card')");
	    }
	}
}

if (function_exists("save_debug")) save_debug();
?>